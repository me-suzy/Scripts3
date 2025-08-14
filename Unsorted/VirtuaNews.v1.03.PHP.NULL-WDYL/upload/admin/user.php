<?php


if (preg_match("/(admin\/user.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

if ($use_forum) {
  adminerror("Not Using VirtuaNews User Tables","This site is currently set to use the user tables from the forums on this site.  To edit the users you must use the administration panel for the forums.");
}

updateadminlog(iif($id,"id = $id",""));

switch ($action) {

case "user":

  echohtmlheader("adminjs");

  $data_arr = query_first("SELECT count(userid) AS count FROM news_user WHERE moderated = 0");

  if ($data_arr[count]) {
    echotableheader("Moderate Users",1);
    echotabledescription("There are currently $data_arr[count] users awaiting moderated.  To moderate them please go <a href=\"admin.php?action=user_mod\">here</a>.",1);
    echotablefooter();
    echo "<br />\n";
  }

  echoformheader("user_dosearch","Edit Users");
  echotabledescription("You may use this page to search for users so that you can edit their account.  To continue enter the search conditions below and press submit (blank fields will be ignored).");
  echoinputcode("Username Contains:","name","",40,1,40);
  echoinputcode("Email Address Contains:","email","",40,1);
  echoinputcode("Homepage Contains:","homepage","",40,1);
  echoinputcode("ICQ Number Contains:","icq","",40,1);
  echoinputcode("AOL Messenger Handle Contains:","aim","",40,1);
  echoinputcode("Yahoo Messenger Handle Contains:","yahoo","",40,1);
  echoinputcode("Posts Less Than:","posts_max","",40,1);
  echoinputcode("Posts More Than:","posts_min","",40,1);

  echo "  <tr>
    <td>Is Banned:</td>
    <td>
      <select name=\"isbanned\" class=\"form\">
        <option value=\"any\">--- Any ---</option>
        <option value=\"1\">Yes</option>
        <option value=\"0\">No</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>Is Moderated:</td>
    <td>
      <select name=\"ismoderated\" class=\"form\">
        <option value=\"any\">--- Any ---</option>
        <option value=\"1\">Yes</option>
        <option value=\"0\">No</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>Has Activated Email:</td>
    <td>
      <select name=\"activatedemail\" class=\"form\">
        <option value=\"any\">--- Any ---</option>
        <option value=\"1\">Yes</option>
        <option value=\"0\">No</option>
      </select>
    </td>
  </tr>\n";

  $getdata = query("SELECT id,title FROM news_profilefield ORDER BY displayorder");
  while ($field = fetch_array($getdata)) {
    echoinputcode($field[title]." contains:","customfield[$field[id]]","",40,1);
  }

  echoformfooter();
  echohtmlfooter();

break;

case "user_dosearch":

  unset($sql_arr);
  unset($sqlcondition);

  if ($name != "") {
    $sql_arr[] = "(news_user.username LIKE '%$name%')";
  }

  if ($email != "") {
    $sql_arr[] = "(news_user.email LIKE '%$email%')";
  }

  if ($homepage != "") {
    $sql_arr[] = "(news_user.homepage LIKE '%$homepage%')";
  }

  if ($icq != "") {
    $sql_arr[] = "(news_user.icq LIKE '%$icq%')";
  }

  if ($aim != "") {
    $sql_arr[] = "(news_user.aim LIKE '%$aim%')";
  }

  if ($yahoo != "") {
    $sql_arr[] = "(news_user.yahoo LIKE '%$yahoo%')";
  }

  if ($posts_min != "") {
    $sql_arr[] = "(news_user.posts > ".intval($posts_min).")";
  }

  if ($posts_max != "") {
    $sql_arr[] = "(news_user.posts < ".intval($posts_max).")";
  }

  if ($isbanned != "any") {
    $sql_arr[] = "(news_user.isbanned = $isbanned)";
  }

  if ($ismoderated != "any") {
    $sql_arr[] = "(news_user.moderated = $ismoderated)";
  }

  if ($activatedemail != "any") {
    $sql_arr[] = "(news_user.activated = $activatedemail)";
  }

  $getdata = query("SELECT id,title FROM news_profilefield ORDER BY displayorder");
  while ($field = fetch_array($getdata)) {
    if ($customfield[$field[id]] != "") {
      $sql_arr[] = "(news_userfield.field$field[id] LIKE '%".$customfield[$field[id]]."%')";
    }
  }

  if ($sql_arr) {
    $sqlcondition = " WHERE ".join(" AND ",$sql_arr);
  }

  $getdata = query("SELECT news_user.userid,news_user.username,news_user.activated,news_staff.id FROM news_user LEFT JOIN news_staff USING(userid) LEFT JOIN news_userfield USING(userid)$sqlcondition ORDER BY news_user.username");

  echohtmlheader();
  echotableheader("Edit Users",1);
  echotabledescription("Your search has returned ".countrows($getdata)." result(s).  To continue please click the appropriate link next to the user you wish to edit.",1);
  echotabledescription(returnlinkcode("Search Again","admin.php?action=user"),1);

  $tablerows = returnminitablerow("<b>Username</b>","<b>Options</b>");

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow(htmlspecialchars($data_arr[username]),returnlinkcode("Edit","admin.php?action=user_edit&id=$data_arr[userid]")." |".returnlinkcode("Email User","admin.php?action=user_email&id=$data_arr[userid]")." |".returnlinkcode("View PM's","admin.php?action=user_pmuserlist&id=$data_arr[userid]").iif($data_arr[userid] != $userid," |".returnlinkcode("Delete","admin.php?action=user_delete&id=$data_arr[userid]"))." |".returnlinkcode("Email User Password","user.php?action=pwd_email&name=".urlencode($data_arr[username]),1).iif($data_arr[activated] == 0," |".returnlinkcode("Email Activation Details","admin.php?action=user_emailactivate&id=$data_arr[userid]")).iif(!$data_arr[id] & $userinfo[caneditstaff]," |".returnlinkcode("Add To Staff","admin.php?action=staff_add&id=$data_arr[userid]")).iif($userinfo[canmaintaindb]," |".returnlinkcode("Prune Users Comments","admin.php?action=maintain_user_c&id=$data_arr[userid]")));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."    ",1);
  echotablefooter();
  echohtmlfooter();

break;

case "user_mod":

  $getdata = query("SELECT userid,username,email FROM news_user WHERE moderated = 0 ORDER BY username");

  $javascript = "  <script type=\"text/javascript\">

  function ca(theform) {
    var i=0;
    for (var i=0;i<theform.elements.length;i++) {
      if ((theform.elements[i].name != 'checkall') && (theform.elements[i].type=='checkbox')) {
        theform.elements[i].checked = theform.checkall.checked;
      }
    }
  }

  </script>";

  echohtmlheader($javascript);

  if (countrows($getdata)) {
    echoformheader("user_domod","Moderate Users");
    echotabledescription("The list below is a list of new users which have yet to be moderated.  If you wish to moderate a user, then check allow, otherwise check delete to remove the user.  Once you have completed, please click submit to save the changes.");

    $tablerows = returnminitablerow("<b>Username</b>","<b>Options</b>","<input type=\"checkbox\" name=\"checkall\" value=\"1\" onclick=\"ca(this.form)\"> <b>Moderate</b>");

    while ($data_arr = fetch_array($getdata)) {
      $tablerows .= returnminitablerow(htmlspecialchars($data_arr[username]),returnlinkcode("Edit Profile","admin.php?action=user_edit&id=$data_arr[userid]",1)." |".returnlinkcode("Delete","admin.php?action=user_delete&id=$data_arr[userid]"),"<input type=\"checkbox\" name=\"users[$data_arr[userid]]\" value=\"1\" />");
    }

    echotabledescription("\n".returnminitable($tablerows,0,100)."    ");
    echotablefooter();
    echo "<br />";

    echotableheader("Send Email To Validated Users");
    echotabledescription("This email will be sent to all users which you validate.");
    echoinputcode("Email Subject:","email_subject","Account Moderated At \$sitename");
    echotextareacode("Email Message:","email_msg",returnpagebit("register_email_moderated"),10,75);
    echoformfooter();
  } else {
    echotableheader("Moderate Users");
    echotabledescription("Currently there are no users awaiting moderation and there is nothing to do here.");
    echotablefooter();
  }
  echohtmlfooter();

break;

case "user_domod":

  unset($user_ids);

  if (count($users) > 0) {
    foreach ($users AS $key => $val) {
      if ($val == 1) {
        $user_ids[] = intval($key);
      }
    }
  }

  if ($user_ids) {
    query("UPDATE news_user SET moderated = 1 WHERE userid IN (".join(",",$user_ids).")");

    $getdata = query("SELECT activated,username,email FROM news_user WHERE userid IN (".join(",",$user_ids).")");

    while ($data_arr = fetch_array($getdata)) {
      if ($data_arr[activated]) {
        $name = $data_arr[username];
        $email = $data_arr[email];

        eval("\$email_subject = \"".$email_subject."\";");
        eval("\$email_msg = \"".$email_msg."\";");

        mail($email,$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>");
      }
    }
  }

  writeallpages();
  echoadminredirect("admin.php?action=user_mod");
  exit;

break;

case "user_add":

  echohtmlheader();
  echoformheader("user_new","Add User");
  echotabledescription("Use this page to add a new user to your site.  Please note, using this form to create a new user will mean that users are automatically moderated andtheir email address will not need activating (if enabled).");
  echoinputcode("Username:","name");
  echoinputcode("Password:","loginpassword");
  echoinputcode("Email Address:","email");
  echoinputcode("Homepage:","homepage","http://",40,1);
  echoinputcode("ICQ Number:","icq","",40,1);
  echoinputcode("AOL Messenger Handle:","aim","",40,1);
  echoinputcode("Yahoo Messenger Handle:","yahoo","",40,1);
  echotextareacode("Signature:","signature","",5,40,1);
  echoyesnocode("Hide Email:","hideemail",0);
  echoyesnocode("Use Email Notification By Default:","emailnotification",$emailreplydefault);
  echoyesnocode("View User Signatures:","viewsigs",$allowusersigs);
  echoyesnocode("Use PM System:","allowpm",iif($allowpms == 2,1,0));
  echoyesnocode("Email About New PM:","emailpm",$enableemail);
  echoyesnocode("Comment Display Default:","commentdefault",$commentreplydefault-1,"All Shown","All Hidden");
  echoyesnocode("Is Banned:","isbanned",0);

  $getdata = query("SELECT id,title,description,required,maxlength,size,hidden FROM news_profilefield ORDER BY displayorder");
  while ($field = fetch_array($getdata)) {
    echoinputcode($field[title].":","customfield[$field[id]]","",$field[size],iif($field[required],0,1));
  }

  echoyesnocode("Add User To Staff:","addstaff",0);

  echotablefooter();
  echo "<br />";
  echotableheader("Email Details - Sent to new user");
  echoinputcode("Subject:","email_subject",returnpagebit("register_email_details_subject"));
  echotextareacode("Message:","email_msg",returnpagebit("register_email_details_msg"),10,50);
  echoformfooter();
  echohtmlfooter();

break;

case "user_new":

  if (($email == "") | ($emailnotification == "") | ($commentdefault == "") | ($hideemail == "") | ($viewsigs == "") | ($name == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  if ($requireuniqueemail) {
    if (query_first("SELECT userid FROM news_user WHERE email = '$email'")) {
      adminerror("Email Taken","The email address you have specified is already in use by another member and you cannot use the same address.");
    }
  }

  if (query_first("SELECT userid FROM news_user WHERE username = '$name'")) {
    adminerror("Username Taken","The username you have specified is already in use by another member and you cannot use the same name.");
  }

  $commentdefault++;

  $getdata = query("SELECT id,required FROM news_profilefield ORDER BY id");
  while ($field = fetch_array($getdata)) {
    if (($customfield[$field[id]] == "") & ($field[required] == 1)) {
      adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
    }
    $custominsert .= ",'".$customfield[$field[id]]."'";
  }

  query("INSERT INTO news_user VALUES (NULL,'$name','".md5($loginpassword)."','$email','".iif($hideemail,0,1)."','$homepage','$icq','$aim','$yahoo','$signature','1','1','".time()."','0','$emailnotification','$commentdefault','$viewsigs','$allowpm','$emailpm','$isbanned')");
  $newuserid = getlastinsert();
  query("INSERT INTO news_userfield VALUES ('$newuserid'$custominsert)");

  $name = stripslashes($name);

  eval("\$email_msg = \"".$email_msg."\";");
  eval("\$email_subject = \"".$email_subject."\";");

  mail($email,$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>");

  writeallpages();

  if ($addstaff) {
    echoadminredirect("admin.php?action=staff_add&id=$newuserid");
  } else {
    echoadminredirect("admin.php?action=user");
  }

break;

case "user_edit":

  verifyid("news_user",$id,"userid");

  $data_arr = query_first("SELECT
    news_user.username,
    news_user.email,
    news_user.homepage,
    news_user.icq,
    news_user.aim,
    news_user.yahoo,
    news_user.signature,
    news_user.showemail,
    news_user.emailnotification,
    news_user.commentdefault,
    news_user.posts,
    news_user.viewsigs,
    news_user.allowpm,
    news_user.emailpm,
    news_user.isbanned,
    news_userfield.*
    FROM news_user
    LEFT JOIN news_userfield USING(userid)
    WHERE news_user.userid = $id");

  echohtmlheader();
  echoformheader("user_update","Edit User");
  updatehiddenvar("id",$id);
  echotabledescription("Use this page to edit a user on your site.");
  echotabledescription(returnlinkcode("Email User","admin.php?action=user_email&id=$id")." |".returnlinkcode("View Private Messages","admin.php?action=user_pmuserlist&id=$id")." |".returnlinkcode("Delete All Private Messages","admin.php?action=user_pm_delete&id=$id&all=1")." |".returnlinkcode("Send Private Message","user.php?action=pm_new&touserid=$id",1));
  echoinputcode("Username:","name",$data_arr[username]);
  echoinputcode("Password:<br />Leave blank to keep the same","loginpassword");
  echoinputcode("Email Address:","email",$data_arr[email]);
  echoinputcode("Homepage:","homepage",$data_arr[homepage],40,1);
  echoinputcode("ICQ Number:","icq",$data_arr[icq],40,1);
  echoinputcode("AOL Messenger Handle:","aim",$data_arr[aim],40,1);
  echoinputcode("Yahoo Messenger Handle:","yahoo",$data_arr[yahoo],40,1);
  echotextareacode("Signature:","signature",$data_arr[signature],5,40,1);
  echoyesnocode("Hide Email:","hideemail",iif($data_arr[showemail],0,1));
  echoyesnocode("Use Email Notification By Default:","emailnotification",$data_arr[emailnotification]);
  echoyesnocode("Comment Display Default:","commentdefault",$data_arr[commentdefault]-1,"All Shown","All Hidden");
  echoyesnocode("View User Signatures:","viewsigs",$data_arr[viewsigs]);
  echoyesnocode("Use PM System:","allowpm",$data_arr[allowpm]);
  echoyesnocode("Email About New PM:","emailpm",$data_arr[emailpm]);
  echoyesnocode("Is Banned:","isbanned",$data_arr[isbanned]);
  echoinputcode("Number Of Posts:","posts",$data_arr[posts]);

  $getdata = query("SELECT id,title,description,required,maxlength,size,hidden FROM news_profilefield ORDER BY displayorder");
  while ($field = fetch_array($getdata)) {
    echoinputcode($field[title].":","customfield[$field[id]]",$data_arr[field.$field[id]],$field[size],iif($field[required],0,1));
  }

  echoformfooter();
  echohtmlfooter();

break;

case "user_update":

  verifyid("news_user",$id,"userid");

  if (($email == "") | ($emailnotification == "") | ($commentdefault == "") | ($hideemail == "") | ($name == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  if ($isbanned & ($id == $userid)) {
    adminerror("Cannot Ban Yourself","You cannot ban yourself from the site.");
  }

  if ($requireuniqueemail) {
    if (query_first("SELECT userid FROM news_user WHERE (userid = $id) AND (email != '$email')")) {
      if (query_first("SELECT userid FROM news_user WHERE email = '$email'")) {
        adminerror("Email Taken","The email address you have specified is already in use by another member and you cannot use the same address.");
      }
    }
  }

  if ($temp = query_first("SELECT userid,username FROM news_user WHERE (userid = $id) AND (username != '$name')")) {
    if (query_first("SELECT userid FROM news_user WHERE username = '$name'")) {
      adminerror("Username Taken","The username you have specified is already in use by another member and you cannot use the same name.");
    }
    query("UPDATE news_news SET lastcommentuser  = '$name' WHERE lastcommentuser = '".mysql_escape_string($temp[username])."'");
  }

  $getdata = query("SELECT id,required,maxlength FROM news_profilefield");
  while ($field = fetch_array($getdata)) {
    if ($field[required] == 1) {
      if ($customfield[$field[id]] == "") {
        adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
      }
    }
    $customupdate .= iif($customupdate," , field$field[id] = '".$customfield[$field[id]]."'","field$field[id] = '".$customfield[$field[id]]."'");
  }

  if ($customupdate) {
    query("UPDATE news_userfield SET $customupdate WHERE userid = $id");
  }

  query("UPDATE news_user SET
    username = '$name',
    email = '$email',".iif($loginpassword != "","\n    password = '".md5($loginpassword)."',","")."
    showemail = '".iif($hideemail,0,1)."',
    homepage = '$homepage',
    icq = '$icq',
    aim = '$aim',
    yahoo = '$yahoo',
    signature = '$signature',
    emailnotification = '$emailnotification',
    commentdefault = '$commentdefault',
    posts = '$posts',
    viewsigs = '$viewsigs',
    allowpm = '$allowpm',
    emailpm = '$emailpm',
    isbanned = '$isbanned'
    WHERE userid = $id");

  if (($id == $userid) & ($loginpassword != "")) {
    updatecookie("userpassword",md5($loginpassword));
  }

  writeallpages();
  echoadminredirect("admin.php?action=user");

break;


case "user_delete":

  verifyid("news_user",$id,"userid");

  if ($id == $userid) {
    adminerror("Cannot Delete Yourself","You cannot delete yourself from the user tables.");
  }

  echodeleteconfirm("user","user_kill",$id," This will also remove them from the staff table if the user is already staff, and it will turn any comments made by this user into guest comments.".iif($userinfo[canmaintaindb],"  If you would like to prune the comments by this user first please click".returnlinkcode("here","admin.php?action=maintain_user_c&id=$id")."."),iif(preg_match("/action=user_mod/i",$HTTP_REFERER),"&referer=mod"));

break;

case "user_kill":

  settype($id,"integer");
  if ($temp = query_first("SELECT username,showemail,email FROM news_user WHERE userid = $id")) {
    if ($id == $userid) {
      adminerror("Cannot Delete Yourself","You cannot delete yourself from the user tables.");
    }
  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

  query("DELETE FROM news_user WHERE userid = $id");
  query("DELETE FROM news_userfield WHERE userid = $id");
  query("DELETE FROM news_staff WHERE userid = $id");
  query("DELETE FROM news_pm WHERE ((touserid = $id) AND (folder = 1)) OR ((fromuserid = $id) AND (folder = 2))");
  query("DELETE FROM news_activation WHERE userid = $id");
  query("UPDATE news_comment SET username = '$user[username]' , useremail = '".iif($temp[showemail],$temp[email])."' , userid = '0' WHERE userid = $id");
  writeallpages();

  if (($referer == "mod") & query_first("SELECT userid FROM news_user WHERE moderated = 0")) {
    echoadminredirect("admin.php?action=user_mod");
  } else {
    echoadminredirect("admin.php?action=user");
  }
  exit;

break;

case "user_email":

  if (isset($id)) {
    settype($id,"integer");
    $data_arr = query_first("SELECT username FROM news_user WHERE userid = $id");
    if (!$data_arr) {
      adminerror("Invalid ID","You have specified an invalid id.");
    }
  }

  echohtmlheader();
  echoformheader("user_doemail","Email Users");
  echotabledescription("You may use this page to email your users.  You can enter your message below and select conditions for emailing the users (who you wish to email), if you wish to email everyone then leave all the search fields blank.  To continue enter the search conditions below and your email message and press submit (blank fields will be ignored).");

  if ($data_arr) {
    updatehiddenvar("id",$id);
    echotablerow("Email User:",htmlspecialchars($data_arr[username]));
  }

  echoinputcode("Email Subject:","email_subject");
  echotextareacode("Email Message:<br />You may use the variables \$user[userid] \$user[username] \$user[email] in your message to make each one user specific","email_msg","",10,50,0,40);

  if (!$data_arr) {
    echotablefooter();
    echotableheader("Search Conditions");
    echoinputcode("Username Contains:","name","",40,1,40);
    echoinputcode("Email Address Contains:","email","",40,1);
    echoinputcode("Homepage Contains:","homepage","",40,1);
    echoinputcode("ICQ Number Contains:","icq","",40,1);
    echoinputcode("AOL Messenger Handle Contains:","aim","",40,1);
    echoinputcode("Yahoo Messenger Handle Contains:","yahoo","",40,1);
    echoinputcode("Posts Less Than:","posts_max","",40,1);
    echoinputcode("Posts More Than:","posts_min","",40,1);
    echoyesnocode("Is Banned:","isbanned",0);
  }

  echoformfooter();
  echohtmlfooter();

break;

case "user_doemail":

  if (($email_subject == "") | ($email_msg == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  if (isset($id)) {
    verifyid("news_user",$id,"userid");
    $sqlcondition = " WHERE userid = $id";
  } else {
    if ($name != "") {
      $sql_arr[] = "(username LIKE '%$name%')";
    }

    if ($email != "") {
      $sql_arr[] = "(email LIKE '%$email%')";
    }

    if ($homepage != "") {
      $sql_arr[] = "(homepage LIKE '%$homepage%')";
    }

    if ($icq != "") {
      $sql_arr[] = "(icq LIKE '%$icq%')";
    }

    if ($aim != "") {
      $sql_arr[] = "(aim LIKE '%$aim%')";
    }

    if ($yahoo != "") {
      $sql_arr[] = "(yahoo LIKE '%$yahoo%')";
    }

    if ($posts_min != "") {
      $sql_arr[] = "(posts > ".intval($posts_min).")";
    }

    if ($posts_max != "") {
     $sql_arr[] = "(posts < ".intval($posts_max).")";
    }

    $sql_arr[] = " (news_user.isbanned = $isbanned)";

    if ($sql_arr) {
      $sqlcondition = " WHERE ".join(" AND ",$sql_arr);
    }
  }

  echohtmlheader();
  echotableheader("Sending Emails",1);
  echo "  <tr>\n    <td>\n";

  $getdata = query("SELECT userid,username,email FROM news_user$sqlcondition ORDER BY username");

  $num_sent = countrows($getdata);

  $original_msg = $email_msg;

  while ($user = fetch_array($getdata)) {
    echo "      Sending email to $user[username].... ";

    eval("\$email_msg = \"".$original_msg."\";");

    if (@mail($user[email],$email_subject,$email_msg,"From: \"$sitename Mailer\" <$webmasteremail>")) {
      echo "Succeeded<br />\n";
    } else {
      echo "Failed<br />\n";
    }
  }

  echo "    </td>\n  </tr>\n";
  echotabledescription("Sending complete, there were $num_sent email(s) sent in total.");
  echotablefooter();
  echohtmlfooter();

break;

case "user_emailactivate":

  if ($user = query_first("SELECT userid,username,email FROM news_user WHERE userid = '$id'")) {

    if ($temp_arr = query_first("SELECT id FROM news_activation WHERE (userid = $user[userid]) AND (type = 1)")) {
      query("DELETE FROM news_activation WHERE id = $temp_arr[id]");
    } else {
      adminerror("Already Activated","The user you have specified has already activated their email account.");
    }

    mt_srand(time());
    $activateid = mt_rand(0,999999);

    query("INSERT INTO news_activation VALUES (NULL,'$id','".time()."','$activateid','1')");

    $user[username] = stripslashes($user[username]);

    eval("\$email_msg .= \"".returnpagebit("register_email_activation_msg")."\";");
    eval("\$email_subject .= \"".returnpagebit("register_email_activation_subject")."\";");

    if (@mail($user[email],$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>")) {
      echoadminredirect("admin.php?action=user");
      exit;
    } else {
      adminerror("Mail Not Sent","The email has failed to send, please try again");
    }

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "user_pm":

  $staff_arr = array();
  $user_arr = array();
  $staff_count = array();
  $user_count = array();

  $getdata = query("SELECT COUNT(news_pm.id) AS count,news_pm.touserid,news_staff.id FROM news_pm LEFT JOIN news_staff ON news_staff.userid = news_pm.touserid WHERE (folder = 1) GROUP BY (news_pm.touserid) ORDER BY count DESC");

  while ($data_arr = fetch_array($getdata)) {
    if ($data_arr[id]) {
      $staff_arr[$data_arr[touserid]] += $data_arr[count];
    } else {
      $user_arr[$data_arr[touserid]] += $data_arr[count];
    }
  }

  if ($pmcountsent) {
    $getdata = query("SELECT COUNT(news_pm.id) AS count,news_pm.fromuserid,news_staff.id FROM news_pm LEFT JOIN news_staff ON news_staff.userid = news_pm.fromuserid WHERE (folder = 2) GROUP BY (news_pm.fromuserid) ORDER BY count DESC");

    while ($data_arr = fetch_array($getdata)) {
      if ($data_arr[id]) {
        $staff_arr[$data_arr[fromuserid]] += $data_arr[count];
      } else {
        $user_arr[$data_arr[fromuserid]] += $data_arr[count];
      }
    }
  }

  echohtmlheader();
  echotableheader("Private Messages Stats",1);
  echotabledescription("This page will allow you to see how many users are using the private messaging system.",1);

  $tablerows = returnminitablerow("<b>Number Of PM's</b>","<b>Number Of Users</b>","<b>Options</b>");

  echotabledescription("<b>Staff</b>: (".iif($pmlimitstaff,$pmlimitstaff." message limit","No limit").")",1);

  if ($staff_arr) {
    foreach ($staff_arr AS $key => $val) {
      $staff_count[$val][total] ++;
      $staff_count[$val][ids] .= iif(isset($staff_count[$val][ids]),",$key",$key);
    }
    foreach ($staff_count AS $key => $val) {
      $tablerows .= returnminitablerow($key,$val[total],returnlinkcode("List All Staff","admin.php?action=user_pmlist&id=$val[ids]"));
    }
    echotabledescription("\n".returnminitable($tablerows,0,100)."    ",1);
  } else {
    echotabledescription("No staff with private messages.",1);
  }

  echotabledescription("<b>Users</b>: (".iif($pmlimituser,$pmlimituser." message limit","No limit").")",1);

  if ($user_arr) {
    unset($tablerows);
    foreach ($user_arr AS $key => $val) {
      $user_count[$val][total] ++;
      $user_count[$val][ids] .= iif(isset($user_count[$val][ids]),",$key",$key);
    }
    foreach ($user_count AS $key => $val) {
      $tablerows .= returnminitablerow($key,$val[total],returnlinkcode("List All Users","admin.php?action=user_pmlist&id=$val[ids]"));
    }
    echotabledescription("\n".returnminitable($tablerows,0,100)."    ",1);
  } else {
    echotabledescription("No users with private messages.",1);
  }

  echotablefooter();
  echohtmlfooter();

break;

case "user_pmlist":

  if (isset($id)) {  // This will stip any non-numerical values out
    $id = explode(",",$id);
    foreach ($id AS $val) {
      $ids[] = intval($val);
    }
    $id = join(",",$ids);
  } else {
    $id = 0;
  }

  $pmcount = query_first("SELECT COUNT(news_pm.id) AS count,news_staff.id FROM news_pm LEFT JOIN news_staff ON news_staff.userid = news_pm.touserid WHERE ((news_pm.touserid = ".substr($id,0,1).") AND (news_pm.folder = 1))".iif($pmcountsent," OR ((news_pm.fromuserid = ".substr($id,0,1).") AND (news_pm.folder = 2))","")." GROUP BY (news_pm.touserid)");

  $getdata = query("SELECT userid,username FROM news_user WHERE userid IN ($id) ORDER BY username");

  echohtmlheader();
  echotableheader(iif($pmcount[id],"Staff","Users")." With $pmcount[count] Private messages",1);
  echotabledescription("This page lists all the ".iif($pmcount[id],"staff","users")." with $pmcount[count] private messages (please note, if you have set that messages do not add to the message limit, then they wont be included here).",1);

  $tablerows = returnminitablerow("<b>Username</b>","<b>Options</b>");

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow(htmlspecialchars($data_arr[username]),returnlinkcode("Edit User","admin.php?action=user_edit&id=$data_arr[userid]")." |".returnlinkcode("Email User","admin.php?action=user_email&id=$data_arr[userid]")." |".returnlinkcode("List All PM's","admin.php?action=user_pmuserlist&id=$data_arr[userid]")." |".returnlinkcode("Delete All PM's","admin.php?action=user_pm_delete&id=$data_arr[userid]&all=1")." |".returnlinkcode("Send New PM","user.php?action=pm_new&touserid=$data_arr[userid]"));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."    ",1);
  echotablefooter();
  echohtmlfooter();

break;

case "user_pmuserlist":

  verifyid("news_user",$id,"userid");

  $user = query_first("SELECT userid,username FROM news_user WHERE userid = $id");

  echohtmlheader();
  echotableheader("List Private Messages",1);
  echotabledescription("Below is a list of all the private messages for the user ".htmlspecialchars($user[username]).", to edit or delete a private message for the user click on the appropriate link next to it.",1);
  echotabledescription(returnlinkcode("Edit Users Profile","admin.php?action=user_edit&id=$user[userid]")." |".returnlinkcode("Delete All Private Messages","admin.php?action=user_pm_delete&id=$id&all=1")." |".returnlinkcode("Send Private Message","user.php?action=pm_new&touserid=$id",1));
  echotablefooter();

  echotableheader("PM Inbox For ".htmlspecialchars($user[username]),1);

  $getdata = query("SELECT news_pm.id,news_pm.subject,news_pm.fromuserid,news_user.username AS fromusername,news_pm.senddate FROM news_pm LEFT JOIN news_user ON news_user.userid = news_pm.fromuserid WHERE (news_pm.touserid = $id) AND (folder = 1)");

  $header = returnminitablerow("<b>Subject</b>","<b>Sent From</b>","<b>Sent At</b>","<b>Options</b>");
  unset($tablerows);

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow($data_arr[subject],returnlinkcode(htmlspecialchars($data_arr[fromusername]),"admin.php?action=user_edit&id=$data_arr[fromuserid]"),date($pmdate,$data_arr[senddate]-$timeoffset),returnlinkcode("Edit","admin.php?action=user_pm_edit&id=$data_arr[id]")." |".returnlinkcode("Delete","admin.php?action=user_pm_delete&id=$data_arr[id]"));
  }

  if ($tablerows) {
    echotabledescription("\n".returnminitable($header.$tablerows,0,100)."    ",1);
  } else {
    echotabledescription("Empty",1);
  }

  echotablefooter();

  echotableheader("PM Outbox For ".htmlspecialchars($user[username]),1);

  $getdata = query("SELECT news_pm.id,news_pm.subject,news_pm.touserid,news_user.username AS tousername,news_pm.senddate FROM news_pm LEFT JOIN news_user ON news_user.userid = news_pm.touserid WHERE (news_pm.fromuserid = $id) AND (folder = 2)");

  $header = returnminitablerow("<b>Subject</b>","<b>Sent To</b>","<b>Sent At</b>","<b>Options</b>");
  unset($tablerows);

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow($data_arr[subject],returnlinkcode(htmlspecialchars($data_arr[tousername]),"admin.php?action=user_edit&id=$data_arr[touserid]"),date($pmdate,$data_arr[senddate]-$timeoffset),returnlinkcode("Edit","admin.php?action=user_pm_edit&id=$data_arr[id]")." |".returnlinkcode("Delete","admin.php?action=user_pm_delete&id=$data_arr[id]"));
  }

  if ($tablerows) {
    echotabledescription("\n".returnminitable($header.$tablerows,0,100)."    ",1);
  } else {
    echotabledescription("Empty",1);
  }
  echotablefooter();
  echohtmlfooter();

break;

case "user_pm_edit":

  verifyid("news_pm",$id);

  $data_arr = query_first("SELECT
    news_pm.subject,
    news_pm.message,
    news_pm.senddate,
    news_pm.readdate,
    news_pm.touserid,
    news_pm.fromuserid,
    news_user.username AS tousername,
    news_pm.showsig
    FROM news_pm
    LEFT JOIN news_user ON news_user.userid = news_pm.touserid
    WHERE id = $id");

  $fromuserinfo = query_first("SELECT username FROM news_user WHERE userid = $data_arr[fromuserid]");
  $data_arr[fromusername] = $fromuserinfo[username];

  echohtmlheader("qhtmlcode");
  echoformheader("user_pm_update","Edit Private Message");
  updatehiddenvar("id",$id);
  echotablerow("Sent From:",returnlinkcode(htmlspecialchars($data_arr[fromusername]),"admin.php?action=user_edit&id=$data_arr[fromuserid]"));
  echotablerow("Sent To:",returnlinkcode(htmlspecialchars($data_arr[tousername]),"admin.php?action=user_edit&id=$data_arr[touserid]"));
  echotablerow("Date Sent:",date($pmdate,$data_arr[senddate]-$timeoffset));
  echotablerow("Date Read:",iif($data_arr[readdate],date($pmdate,$data_arr[readdate]-$timeoffset),"Unread"));
  echoinputcode("Subject:","subject",$data_arr[subject]);
  echoqhtmlhelp();
  echotextareacode("Message:","content",$data_arr[message],10,75);
  echoyesnocode("Show Signature:","showsig",$data_arr[showsig]);
  echoyesnocode("Auto Parse URL's:","parseurl",$pmallowqhtmlcode);
  echoformfooter();
  echohtmlfooter();

break;

case "user_pm_update":

  if (($subject == "") | ($content == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  verifyid("news_pm",$id);

  query("UPDATE news_pm SET subject = '$subject' , message = '".iif($parseurl,autoparseurl($content),$content)."' , showsig = '$showsig' WHERE id = $id");

  writeallpages();
  echoadminredirect("admin.php?action=user_pm");
  exit;

break;

case "user_pm_delete":

  if ($all) {
    verifyid("news_user",$id,"userid");
  } else {
    verifyid("news_pm",$id);
  }
  echodeleteconfirm("private messages","user_pm_kill",$id,iif($all," This will delete ALL the private messages for the selected user.",""),"&all=".iif($all,1,0));

break;

case "user_pm_kill":

  if ($all) {
    verifyid("news_user",$id,"userid");
  } else {
    verifyid("news_pm",$id);
    $user = query_first("SELECT fromuserid FROM news_pm WHERE id = $id");
  }

  query("DELETE FROM news_pm WHERE ".iif($all,"((touserid = $id) AND (folder = 1)) OR ((fromuserid = $id) AND (folder = 2))","id = $id"));

  writeallpages();
  if ($all) {
    echoadminredirect("admin.php?action=user_edit&id=$id");
  } else {
    echoadminredirect("admin.php?action=user_pmuserlist&id=$user[fromuserid]");
  }

  exit;

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/user.php
|| ####################################################################
\*======================================================================*/

?>