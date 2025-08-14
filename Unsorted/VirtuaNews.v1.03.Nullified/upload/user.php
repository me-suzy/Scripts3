<?php

/*======================================================================*\
|| #################################################################### ||
||  Program Name         : VirtuaNews Pro                                 
||  Release Version      : 1.0.3                                          
||  Program Author       : VirtuaSystems                                  
||  Supplied by          : Ravish                                         
||  Nullified by         : WTN Team                                       
||  Distribution         : via WebForum, ForumRU and associated file dumps
|| #################################################################### ||
\*======================================================================*/

require("global.php");

function randompassword($characters=8) {

  // Function used to create an 8 character random password when user resets password

  $char_arr = array(
    "a","A","b","B","c","C","d","D","e","E","f","F","g","G","h",
    "H","i","I","j","J", "k","K","l","L","m","M","n","N","o","O",
    "p","P","q","Q","r","R","s","S","t","T", "u","U","v","V","w",
    "W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8",
    "9","0");

  $max_elements = count($char_arr) - 1;

  mt_srand(time());

  for ($i=1;$i<$characters;$i++) {
    $password .= $char_arr[mt_rand(0,$max_elements)];
  }

  return $password;

}

// Do login before permission check

if ($action == "dologin") {

  if ($loggedin) {
    standarderror("already_logged_in");
  }

  if ((trim($name) == "") | (trim($password) == "")) {
    standarderror("blank_field");
  }

  dologin($HTTP_POST_VARS[name],$HTTP_POST_VARS[password]);

  standardredirect("login",iif($redirect,$redirect,"user.php"));
}

// Do actions for forgotten password before login check

if ($action == "pwd_forgot") {
  if ($use_forum) {
    header_redirect($foruminfo[pwd_forgot_path],"Lost Password");
  }

  $referer = iif($HTTP_REFERER,xss_clean($HTTP_REFERER),"index.php");

  $navbar = makenavbar("Forgot Password");
  include("static/sub_pages/user_forget_form_".$pagesetid.".php");
}

if ($action == "pwd_email") {

  if ($use_forum) {
    header_redirect($foruminfo[pwd_forgot_path],"Lost Password");
  }

  if (trim($name) == "") {
    standarderror("blank_field");
  }

  if ($data_arr = query_first("SELECT userid,email FROM news_user WHERE username = '$name'")) {

    $userid = $data_arr[userid];
    $email = $data_arr[email];
    $name = stripslashes($name);

    query("DELETE FROM news_activation WHERE (userid = $userid) AND (type = 2)");
    mt_srand(time());
    $activateid = mt_rand(0,999999);

    query("INSERT INTO news_activation VALUES (NULL,'$userid','".time()."','$activateid','2')");

    eval("\$email_msg .= \"".returnpagebit("user_forget_request_emailmsg")."\";");
    eval("\$email_subject .= \"".returnpagebit("user_forget_request_emailsub")."\";");

    mail($email,$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>");
    standardredirect("pwd_forgot",iif($redirect,$redirect,"index.php"));

  } else {
    standarderror("wrong_username");
  }

}

if ($action == "pwd_reset") {

  if ($use_forum) {
    header_redirect($foruminfo[pwd_forgot_path],"Lost Password");
  }

  settype($u,"integer");
  settype($a,"integer");

  if ($data_arr = query_first("SELECT news_activation.date,news_activation.id,news_user.username,news_user.email FROM news_activation LEFT JOIN news_user ON news_activation.userid = news_user.userid WHERE (news_activation.activateid = $a) AND (news_activation.userid = $u) AND (news_activation.type = 2)")) {
    if ($data_arr[date] < (time()-86400)) {
      standarderror("password_request_expired");
    }

    $password = randompassword();

    query("DELETE FROM news_activation WHERE id = $data_arr[id]");
    query("UPDATE news_user SET password = '".md5($password)."' WHERE userid = $u");

    $name = stripslashes($data_arr[username]);
    $email = $data_arr[email];

    eval("\$email_msg .= \"".returnpagebit("user_forget_reset_emailmsg")."\";");
    eval("\$email_subject .= \"".returnpagebit("user_forget_reset_emailsub")."\";");
    mail($email,$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>");

    standardredirect("pwd_reset","user.php");

  } else {
    standarderror("invalid_id");
  }

}

// Check Permissions

if (!$loggedin) {
  if (!empty($userinfo)) {
    if ($userinfo[activated] == 0) {
      standarderror("email_notactivated");
    } elseif ($userinfo[moderated] == 0) {
      standarderror("account_notmoderated");
    }
  } else {
    $action = "login";
  }
}

if ($action == "login") {
  if ($loggedin) {
    standarderror("already_logged_in");
  } else {

    if (empty($referer)) {
      if (empty($HTTP_REFERER)) {
        $referer = "index.php";
      } else {
        $referer = xss_clean($HTTP_REFERER);
      }
    }

    $navbar = makenavbar("Login");
    include("static/sub_pages/user_login_".$pagesetid.".php");
  }
}

if ($action == "") {

  if ($use_forum) {
    header_redirect($foruminfo[usercp_path],"Lost Password");
  }

  if (isset($num_private_messages)) {
    $new_pms = $num_private_messages;
  } else {
    $count_pms = query_first("SELECT COUNT(id) AS count FROM news_pm WHERE (touserid = $userid) AND (folder = 1) AND (readdate = 0)");
    $new_pms = $count_pms[count];
  }

  settype($perpage,"integer");
  settype($pagenum,"integer");
  unset($subscribedposts);
  unset($newmessages);

  $getdata = query("SELECT
    news_pm.id,
    news_pm.senddate,
    news_pm.readdate,
    news_pm.subject,
    news_pm.touserid,
    news_pm.fromuserid,
    news_user.username AS fromusername
    FROM news_pm
    LEFT JOIN news_user ON news_pm.fromuserid = news_user.userid
    WHERE (news_pm.touserid = $userid) AND (folder = 1) AND (readdate = 0)
    ORDER BY senddate DESC");

  while ($message = fetch_array($getdata)) {
    $message[senddate] = date($pmdate,$message[senddate]-$timeoffset);
    $message[fromusername] = htmlspecialchars($message[fromusername]);

    eval("\$message[readdate] = \"".returnpagebit("user_pm_unread")."\";");

    eval("\$newmessages .= \"".returnpagebit("user_index_pm")."\";");
  }

  if (!isset($newmessages)) {
    eval("\$newmessages = \"".returnpagebit("user_index_pm_empty")."\";");
  }

  if ($perpage < 1) {
    $perpage = $usersubperpage;
  }

  if ($pagenum < 1) {
    $pagenum = 1;
  }

  $numrecords = query_first("SELECT COUNT(id) AS count FROM news_subscribe WHERE userid = $userid");

  $pagenav = pagenav($perpage,$pagenum,"user.php?",$numrecords[count]);

  $getdata = query("SELECT
    news_subscribe.lastview,
    news_news.id,
    news_news.title,
    news_news.catid,
    news_news.lastcommentuser AS LC_username,
    MAX(news_comment.time) AS LC_time,
    MIN(news_comment.id) AS FU_id,
    MAX(news_comment.id) AS LC_id,
    COUNT(news_comment.id) AS newposts,
    news_user.username,
    news_user.userid
    FROM news_subscribe
    LEFT JOIN news_news ON news_subscribe.newsid = news_news.id
    LEFT JOIN news_comment ON news_subscribe.newsid = news_comment.newsid AND (news_subscribe.lastview < news_comment.time)
    LEFT JOIN news_staff ON news_news.staffid = news_staff.id
    LEFT JOIN news_user ON news_user.userid = news_staff.userid
    WHERE (news_subscribe.userid = $userid)
    GROUP BY news_news.id
    ORDER BY LC_time DESC,news_news.time DESC
    LIMIT ".($pagenum - 1) * $perpage.",$perpage");

  while ($post = fetch_array($getdata)) {

    $post[LC_username] = htmlspecialchars($post[LC_username]);
    $post[username] = htmlspecialchars($post[username]);

    if ($post[newposts]) {
      $post[newposts] = "<b>$post[newposts]</b>";
      eval("\$FU_link = \"".returnpagebit("user_index_first_unread_link")."\";");
    } else {
      unset($FU_link);
      $temp = query_first("SELECT id,time FROM news_comment WHERE newsid = $post[id] ORDER BY time DESC LIMIT 1");
      $post[LC_time] = $temp[time];
      $post[LC_id] = $temp[id];
    }

    $post[LC_time] = iif($post[LC_time],date($commentdate,$post[LC_time]-$timeoffset),"No Posts");
    eval("\$subscribedposts .= \"".returnpagebit("user_index_post")."\";");
  }

  if (!isset($subscribedposts)) {
    eval("\$subscribedposts = \"".returnpagebit("user_index_post_empty")."\";");
  }

  $navbar = makenavbar("User Panel");
  include("static/sub_pages/user_index_".$pagesetid.".php");
}

if ($action == "logout") {

  dologout();

  eval("\$welcometext = \"".returnpagebit("misc_welcometext_logged_out")."\";");
  standardredirect("logout",iif($HTTP_REFERER,xss_clean($HTTP_REFERER),"index.php"));
}

if ($action == "profile") {

  if ($use_forum) {
    header_redirect($foruminfo[usercp_profile_path],"Edit Password");
  }

  $userinfo = query_first("SELECT
    news_user.email,
    news_user.showemail,
    news_user.homepage,
    news_user.icq,
    news_user.aim,
    news_user.yahoo,
    news_user.signature,
    news_user.emailnotification,
    news_user.commentdefault,
    news_user.viewsigs,
    news_user.allowpm,
    news_user.emailpm,
    news_userfield.*
    FROM news_user
    LEFT JOIN news_userfield ON news_user.userid = news_userfield.userid
    WHERE news_user.userid = $userid");

  $getdata = query("SELECT id,title,description,required,maxlength,size,hidden FROM news_profilefield WHERE editable = 1 ORDER BY displayorder");
  while ($field = fetch_array($getdata)) {
    $field[hiddentext] = iif($field[hidden]," (Hidden to users)","");
    $field[value] = $userinfo[field.$field[id]];
    if ($field[required] == 1) {
      eval("\$custom_fields_required .= \"".returnpagebit("register_form_custom_field")."\";");
    } else {
      eval("\$custom_fields_optional .= \"".returnpagebit("register_form_custom_field")."\";");
    }
  }

  $userinfo[signature] = htmlspecialchars($userinfo[signature]);

  if ($userinfo[emailnotification]) {
    $checked_on[emailnotification] = " checked=\"checked\"";
    $checked_off[emailnotification] = "";
  } else {
    $checked_on[emailnotification] = "";
    $checked_off[emailnotification] = " checked=\"checked\"";
  }

  if ($userinfo[commentdefault] == 2) {
    $checked_on[commentdefault] = " checked=\"checked\"";
    $checked_off[commentdefault] = "";
  } else {
    $checked_on[commentdefault] = "";
    $checked_off[commentdefault] = " checked=\"checked\"";
  }

  if ($userinfo[showemail]) {
    $checked_off[showemail] = " checked=\"checked\"";
    $checked_on[showemail] = "";
  } else {
    $checked_off[showemail] = "";
    $checked_on[showemail] = " checked=\"checked\"";
  }

  if ($userinfo[viewsigs]) {
    $checked_on[viewsigs] = " checked=\"checked\"";
    $checked_off[viewsigs] = "";
  } else {
    $checked_on[viewsigs] = "";
    $checked_off[viewsigs] = " checked=\"checked\"";
  }

  if ($userinfo[allowpm]) {
    $checked_on[allowpm] = " checked=\"checked\"";
    $checked_off[allowpm] = "";
  } else {
    $checked_on[allowpm] = "";
    $checked_off[allowpm] = " checked=\"checked\"";
  }

  if ($userinfo[emailpm]) {
    $checked_on[emailpm] = " checked=\"checked\"";
    $checked_off[emailpm] = "";
  } else {
    $checked_on[emailpm] = "";
    $checked_off[emailpm] = " checked=\"checked\"";
  }

  $navbar = makenavbar("Profile","User Panel","user.php");
  include("static/sub_pages/user_profile_edit_".$pagesetid.".php");
}

if ($action == "profile_update") {

  if ($use_forum) {
    header_redirect($foruminfo[usercp_profile_path],"Edit Password");
  }

  if ((trim($email) == "") | (trim($emailnotification) == "") | (trim($commentdefault) == "") | (trim($showemail) == "")) {
    standarderror("blank_field");
  }

  if ($email != $email_confirm) {
    standarderror("email_mismatch");
  }

  if (($commentemaillimit > 0) & (strlen($email) > $commentemaillimit)) {
    standarderror("email_long");
  }

  if ($requireuniqueemail & ($email != $userinfo[email])) {
    if (query_first("SELECT userid FROM news_user WHERE email = '$email'")) {
      standarderror("email_taken");
    }
  }

  if ($maximages != 0) {
    if ($staffid) {
      $sigparsed = qhtmlparse($signature,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
    } else {
      $sigparsed = qhtmlparse($signature,$user_allowhtml,$user_allowimg,$user_allowsmilies,$user_allowqhtml);
    }
    if (countchar($sigparsed,"<img") > $maximages) {
      standarderror("too_many_images");
    }
  }

  $getdata = query("SELECT id,required,maxlength FROM news_profilefield");
  while ($field = fetch_array($getdata)) {
    if ($field[required] == 1) {
      if (trim($customfield[$field[id]]) == "") {
        standarderror("blank_field");
      }
    }
    if (($field[maxlength] > 0) & (strlen($customfield[$field[id]]) > $field[maxlength])) {
      standarderror("field_long");
    }
    $customupdate .= iif($customupdate," , field$field[id] = '".$customfield[$field[id]]."'","field$field[id] = '".$customfield[$field[id]]."'");
  }

  if ($requirevalidemail & ($email != $userinfo[email])) {
    mt_srand(time());
    $activateid = mt_rand(0,9999999999);
    $user[userid] = $userid;
    $user[username] = $username;
    query("INSERT INTO news_activation VALUES (NULL,'$userid','".time()."','$activateid','1')");
    query("UPDATE news_user SET activated = '0' WHERE userid = $userid");
    eval("\$email_msg .= \"".returnpagebit("user_profile_email_msg")."\";");
    eval("\$email_subject .= \"".returnpagebit("user_profile_email_subject")."\";");
    mail($email,$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>");
  }

  if ($customupdate) {
    query("UPDATE news_userfield SET $customupdate WHERE userid = $userid");
  }

  query("UPDATE news_user SET
    email = '$email',
    showemail = '$showemail',
    homepage = '$homepage',
    icq = '$icq',
    aim = '$aim',
    yahoo = '$yahoo',
    signature = '$signature',
    emailnotification = '$emailnotification',
    commentdefault = '$commentdefault',
    viewsigs = '$viewsigs',
    allowpm = '$allowpm',
    emailpm = '$emailpm'
    WHERE userid = $userid");

  standardredirect("profile_updated","user.php");

}

if ($action == "pwd") {

  if ($use_forum) {
    header_redirect($foruminfo[usercp_pwd_path],"Edit Password");
  }

  $navbar = makenavbar("Edit Password","User Panel","user.php");
  include("static/sub_pages/user_pwd_edit_".$pagesetid.".php");

}

if ($action == "pwd_update") {

  if ($use_forum) {
    header_redirect($foruminfo[usercp_pwd_path],"Edit Password");
  }

  if ((trim($password) == "") | (trim($newpassword) == "")) {
    standarderror("blank_field");
  }

  if ($newpassword != $newpassword_confirm) {
    standarderror("password_mismatch");
  }

  $userinfo = query_first("SELECT password FROM news_user WHERE userid = $userid");

  if ($userinfo[password] == md5($password)) {
    query("UPDATE news_user SET password = '".md5($newpassword)."' WHERE userid = $userid");
  } else {
    standarderror("wrong_password");
  }

  updatecookie("vnuserpassword",md5($newpassword));

  standardredirect("pwd_updated","user.php");

}

if (($action == "pm") | ($action == "pm_show") | ($action == "pm_delete") | ($action == "pm_new") | ($action == "pm_send")) {

  if ($use_forum) {
    if (($action == "pm_new") | ($action == "pm_send")) {
      header_redirect(eval("return \"$foruminfo[pm_form_path]\";"),"Send PM");
    } else {
      header_redirect($foruminfo[pm_main_path],"View PM's");
    }
  }

  if (!isuserallowed($allowpms)) {
    standarderror("pm_no_perms");
  } elseif (!$userinfo[allowpm]) {
    standarderror("pm_disabled");
  }
}

if ($action == "pm") {

  settype($perpage,"integer");
  settype($pagenum,"integer");
  unset($messagelist);

  if ($perpage < 1) {
    $perpage = $pmperpage;
  }

  if ($pagenum < 1) {
    $pagenum = 1;
  }

  $offset = ($pagenum - 1) * $perpage;

  if ($folder == "outbox") {
    $folder = array();
    $folder[number] = 2;
    $folder[name] = "outbox";
    $folder[dirc] = "to";
    $folder[condition] = "from";
  } else {
    $folder = array();
    $folder[number] = 1;
    $folder[name] = "inbox";
    $folder[dirc] = "from";
    $folder[condition] = "to";
  }

  $getdata = query("SELECT
    news_pm.id,
    news_pm.senddate,
    news_pm.readdate,
    news_pm.subject,
    news_pm.touserid,
    news_pm.fromuserid,
    news_user.username AS ".$folder[dirc]."username
    FROM news_pm
    LEFT JOIN news_user ON news_pm.".$folder[dirc]."userid = news_user.userid
    WHERE (news_pm.".$folder[condition]."userid = $userid) AND (folder = ".$folder[number].")
    ORDER BY senddate DESC
    LIMIT $offset,$perpage");

  while ($message = fetch_array($getdata)) {
    $message[senddate] = date($pmdate,$message[senddate]-$timeoffset);
    $message[$folder[dirc].'username'] = htmlspecialchars($message[$folder[dirc].'username']);
    if ($message[readdate]) {
      $message[readdate] = date($pmdate,$message[readdate]-$timeoffset);
    } else {
      eval("\$message[readdate] = \"".returnpagebit("user_pm_unread")."\";");
    }
    eval("\$messagelist .= \"".returnpagebit("user_pm_".$folder[name]."_record")."\";");
  }

  if (!isset($messagelist)) {
    eval("\$messagelist = \"".returnpagebit("user_pm_empty")."\";");
  }

  $numrecords = query_first("SELECT COUNT(id) AS count FROM news_pm WHERE (news_pm.".$folder[condition]."userid = $userid) AND (folder = ".$folder[number].")");
  $pagenav = pagenav($perpage,$pagenum,"user.php?action=pm",$numrecords[count]);

  $navbar = makenavbar("PM ".ucwords($folder[name]),"User Panel","user.php");
  include("static/sub_pages/user_pm_".$folder[name]."_".$pagesetid.".php");

}

if ($action == "pm_show") {

  settype($id,"integer");

  if ($message = query_first("SELECT news_pm.id,news_pm.subject,news_pm.message,news_pm.folder,news_pm.senddate,news_pm.readdate,news_pm.showsig,news_pm.copyid,news_pm.fromuserid,news_pm.touserid,news_user.username AS fromusername,news_user.signature,news_staff.id AS staffid FROM news_pm LEFT JOIN news_user ON news_pm.fromuserid = news_user.userid LEFT JOIN news_staff ON news_user.userid = news_staff.userid WHERE news_pm.id = $id")) {
    if (($message[fromuserid] == $userid) | (($message[touserid] == $userid) & ($message[folder] == 1))) {
      if (($message[readdate] == 0) & ($message[folder] == "1")) {
        query("UPDATE news_pm SET readdate = '".time()."' WHERE (id = $id) OR (id = $message[copyid])");
      }
      if ($message[showsig] & $allowusersigs & $userinfo[viewsigs] & $message[signature]) {
        if ($message[staffid]) {
          $message[signature] = qhtmlparse($message[signature],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
        } else {
          $message[signature] = qhtmlparse($message[signature],$user_allowhtml,$user_allowimg,$user_allowsmilies,$user_allowqhtml);
        }
        eval("\$message[signature] = \"".returnpagebit("comments_comment_signature")."\";");
      } else {
        unset($message[signature]);
      }

      $message[fromusername] = htmlspecialchars($message[fromusername]);

      $touserinfo = query_first("SELECT username FROM news_user WHERE userid = $message[touserid]");
      $message[tousername] = htmlspecialchars($touserinfo[username]);

      $message[senddate] = date($pmdate,$message[senddate]-$timeoffset);

      if ($message[staffid]) {
        $message[message] = qhtmlparse($message[message],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
      } else {
        $message[message] = qhtmlparse($message[message],$user_allowhtml,$user_allowimg,$user_allowsmilies,$user_allowqhtml);
      }

      $navbar = makenavbar("View PM","User Panel","user.php");
      include("static/sub_pages/user_pm_show_".$pagesetid.".php");
    } else {
      standarderror("pm_invalid_user");
    }
  } else {
    standarderror("invalid_id");
  }
}

if ($action == "pm_new") {

  $pmlimit = iif($staffid,$pmlimitstaff,$pmlimituser);
  $pmcount = query_first("SELECT COUNT(id) AS count FROM news_pm WHERE (touserid = $userid)".iif($pmcountsent," OR ((fromuserid = $userid) AND (folder = 2))",""));

  if (($pmcount[count] >= $pmlimit) & ($pmlimit > 0)) {
    standarderror("pm_full");
  }

  settype($touserid,"integer");
  if ($touserid != 0) {
    if ($touserinfo = query_first("SELECT username,allowpm FROM news_user WHERE userid = $touserid")) {
      $touserinfo[username] = htmlspecialchars($touserinfo[username]);
      if ($touserinfo[allowpm] == 0) {
        standarderror("pm_bounce");
      }
    } else {
      standarderror("invalid_id");
    }
  }

  if ($user_allowsmilies | ($staffid & $staff_allowsmilies)) {
    $smilies = getsmilietable();
  } else {
    eval("\$smilies = \"".returnpagebit("comments_smilies_disabled")."\";");
  }

  if ($user_allowqhtml | ($staffid & $staff_allowqhtml)) {
    eval("\$autoparse_check = \"".returnpagebit("comments_add_autoparse_check")."\";");
  } else {
    $autoparse_check = "";
  }

  $qhtmlcode = returnqhtmllinks();

  if ($allowusersigs) {
    if (query_first("SELECT userid FROM news_user WHERE (userid = $userid) AND (signature <> '')")) {
      $checked[signature] = " checked=\"checked\"";
    }
    eval("\$signature_check = \"".returnpagebit("comments_add_signature_check")."\";");
  }

  eval("\$loggedinuser = \"".returnpagebit("comments_logged_in")."\";");

  $sentcounttext = iif($pmcountsent,"will","will not");

  $navbar = makenavbar("Send PM","User Panel","user.php");
  include("static/sub_pages/user_pm_form_".$pagesetid.".php");

}

if ($action == "pm_send") {

  if ((trim($content) == "") | (trim($tousername) == "") | (trim($subject) == "")) {
    standarderror("blank_field");
  }

  if ($touserinfo = query_first("SELECT news_user.userid,news_user.email,news_user.allowpm,news_user.emailpm,news_staff.id FROM news_user LEFT JOIN news_staff ON news_user.userid = news_staff.userid WHERE news_user.username = '$tousername'")) {
    if ($touserinfo[allowpm] == 0) {
      standarderror("pm_bounce");
    }
  } else {
    standarderror("wrong_username");
  }

  $pmlimit = iif($touserinfo[id] != "",$pmlimitstaff,$pmlimituser);
  $pmcount = query_first("SELECT COUNT(id) AS count FROM news_pm WHERE (touserid = $touserinfo[userid])".iif($pmcountsent," OR ((fromuserid = $touserinfo[userid]) AND (folder = 2))",""));

  if (($pmcount[count] >= $pmlimit) & ($pmlimit > 0)) {
    standarderror("pm_bounce");
  }

  if ($parseurl & ($user_allowqhtml | ($staffid & $staff_allowqhtml))) {
    $content = autoparseurl($content);
  }

  if ($maximages != 0) {
    if ($staffid) {
      $pmparsed = qhtmlparse($content,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
    } else {
      $pmparsed = qhtmlparse($content,$user_allowhtml,$user_allowimg,$user_allowsmilies,$user_allowqhtml);
    }
    if (countchar($pmparsed,"<img") > $maximages) {
      standarderror("too_many_images");
    }
  }

  if (($pmcharlimit > 0) & (strlen($content) > $pmcharlimit)) {
    standarderror("field_long");
  }

  if ($savesent) {
    query("INSERT INTO news_pm VALUES (NULL,'$userid','$touserinfo[userid]','2','".time()."','0','$subject','$content','$showsig','0')");
    $copyid = getlastinsert();
  } else {
    $copyid = 0;
  }

  query("INSERT INTO news_pm VALUES (NULL,'$userid','$touserinfo[userid]','1','".time()."','0','$subject','$content','$showsig','$copyid')");
  $pmid = getlastinsert();

  if ($touserinfo[emailpm]) {
    eval("\$email_msg .= \"".returnpagebit("user_pm_email_msg")."\";");
    eval("\$email_subject .= \"".returnpagebit("user_pm_email_subject")."\";");
    mail($touserinfo[email],$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>");
  }

  standardredirect("pm_sent","user.php?action=pm");
}

if ($action == "pm_delete") {

  settype($id,"integer");

  if ($message = query_first("SELECT folder,fromuserid,touserid FROM news_pm WHERE id = $id")) {
    if (($message[fromuserid] == $userid) | (($message[touserid] == $userid) & ($message[folder] == 1))) {
      query("DELETE FROM news_pm WHERE id = $id");
      standardredirect("pm_delete","user.php?action=pm".iif($message[folder] == 2,"&folder=outbox",""));
    } else {
      standarderror("pm_invalid_user");
    }
  } else {
    standarderror("invalid_id");
  }
}

/*======================================================================*\
|| ####################################################################
|| # File: user.php
|| ####################################################################
\*======================================================================*/

?>