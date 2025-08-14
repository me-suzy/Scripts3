<?php


require("global.php");

switch($action) {

case "":

  if ($use_forum) {
    header_redirect($foruminfo[register_path],"Register");
  }

  if ($loggedin & !$allowmultiplesignups) {
    standarderror("already_registered");
  }

  if (!$allowsignup) {
    standarderror("signup_disabled");
  }

  $getdata = query("SELECT id,title,description,required,maxlength,size,hidden FROM news_profilefield WHERE editable = 1 ORDER BY displayorder");
  while ($field = fetch_array($getdata)) {
    $field[hiddentext] = iif($field[hidden]," (Hidden to users)","");
    if ($field[required] == 1) {
      eval("\$custom_fields_required .= \"".returnpagebit("register_form_custom_field")."\";");
    } else {
      eval("\$custom_fields_optional .= \"".returnpagebit("register_form_custom_field")."\";");
    }
  }

  if ($emailreplydefault) {
    $checked_on[emailnotification] = " checked=\"checked\"";
    $checked_off[emailnotification] = "";
  } else {
    $checked_on[emailnotification] = "";
    $checked_off[emailnotification] = " checked=\"checked\"";
  }

  if ($commentreplydefault == 2) {
    $checked_on[commentdefault] = " checked=\"checked\"";
    $checked_off[commentdefault] = "";
  } else {
    $checked_on[commentdefault] = "";
    $checked_off[commentdefault] = " checked=\"checked\"";
  }

  if ($allowusersigs) {
    $checked_on[viewsigs] = " checked=\"checked\"";
    $checked_off[viewsigs] = "";
  } else {
    $checked_on[viewsigs] = "";
    $checked_off[viewsigs] = " checked=\"checked\"";
  }

  if ($allowusersigs) {
    $checked_on[viewsigs] = " checked=\"checked\"";
    $checked_off[viewsigs] = "";
  } else {
    $checked_on[viewsigs] = "";
    $checked_off[viewsigs] = " checked=\"checked\"";
  }

  if ($allowpms == "2") {
    $checked_on[allowpm] = " checked=\"checked\"";
    $checked_off[allowpm] = "";
  } else {
    $checked_on[allowpm] = "";
    $checked_off[allowpm] = " checked=\"checked\"";
  }

  if ($enableemail) {
    $checked_on[emailpm] = " checked=\"checked\"";
    $checked_off[emailpm] = "";
  } else {
    $checked_on[emailpm] = "";
    $checked_off[emailpm] = " checked=\"checked\"";
  }

  $navbar = makenavbar("Register");
  include("static/sub_pages/register_form_".$pagesetid.".php");

break;

case "addmember":

  if ($use_forum) {
    header_redirect($foruminfo[register_path],"Register");
  }

  if ($loggedin & !$allowmultiplesignups) {
    standarderror("already_registered");
  }

  if (!$allowsignup) {
    standarderror("signup_disabled");
  }

  if ((trim($name) == "") | (trim($email) == "") | (trim($password) == "") | ($emailnotification == "") | ($commentdefault == "") | ($showemail == "")) {
    standarderror("blank_field");
  }

  if ($password != $password_confirm) {
    standarderror("password_mismatch");
  }

  if ($email != $email_confirm) {
    standarderror("email_mismatch");
  }

  if (($commentuserlimit > 0) & (strlen($name) > $commentuserlimit)) {
    standarderror("user_long");
  }

  if (($commentemaillimit > 0) & (strlen($email) > $commentemaillimit)) {
    standarderror("email_long");
  }

  if (query_first("SELECT userid FROM news_user WHERE username = '$name'")) {
    standarderror("username_taken");
  }

  if ($requireuniqueemail) {
    if (query_first("SELECT userid FROM news_user WHERE email = '$email'")) {
      standarderror("email_taken");
    }
  }

  if ($maximages != 0) {
    $sigparsed = qhtmlparse($signature,iif($usehtmlloggedin,0,1),$allowurlloggedin,$allowimgloggedin,$allowsmiliesloggedin,$allowbbcodeloggedin);
    if (countchar($sigparsed,"<img") > $maximages) {
      standarderror("too_many_images");
    }
  }

  $getdata = query("SELECT id,required,maxlength FROM news_profilefield ORDER BY id");
  while ($field = fetch_array($getdata)) {
    if ($field[required] == 1) {
      if ($customfield[$field[id]] == "") {
        standarderror("blank_field");
      }
    }
    if (($field[maxlength] > 0) & (strlen($customfield[$field[id]]) > $field[maxlength])) {
      standarderror("field_long");
    }
    $custominsert .= ",'".$customfield[$field[id]]."'";
  }

  query("INSERT INTO news_user VALUES (NULL,'$name','".md5($password)."','$email','$showemail','$homepage','$icq','$aim','$yahoo','$signature','".iif($requirevalidemail,0,1)."','".iif($usermoderation,0,1)."','".time()."','0','".intval($emailnotification)."','".intval($commentdefault)."','".intval($viewsigs)."','".intval($allowpm)."','".intval($emailpm)."','0')");

  $userid = getlastinsert();

  query("INSERT INTO news_userfield VALUES ('$userid'".$custominsert.")");

  $name = stripslashes($name);

  if ($requirevalidemail) {
    mt_srand(time());
    $activateid = mt_rand(0,9999999999);
    $user[userid] = $userid;
    $user[username] = $name;
    query("INSERT INTO news_activation VALUES (NULL,'$userid','".time()."','$activateid','1')");
    eval("\$email_msg = \"".returnpagebit("register_email_activation_msg")."\";");
    eval("\$email_subject = \"".returnpagebit("register_email_activation_subject")."\";");
  } elseif ($usermoderation) {
    eval("\$email_msg = \"".returnpagebit("register_email_mod_msg")."\";");
    eval("\$email_subject = \"".returnpagebit("register_email_mod_subject")."\";");
  } else {
    eval("\$email_msg = \"".returnpagebit("register_email_details_msg")."\";");
    eval("\$email_subject = \"".returnpagebit("register_email_details_subject")."\";");
  }

  mail($email,$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>");

  if ($newuseremail) {
    $getstaff = query("SELECT
      ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
      ".$foruminfo[user_table].".".$foruminfo[email_field]." AS email
      FROM news_staff
      LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      WHERE news_staff.caneditusers = 1");

    while ($to = fetch_array($getstaff)) {
      eval("\$email_msg = \"".returnpagebit("register_email_newuser_msg")."\";");
      eval("\$email_subject = \"".returnpagebit("register_email_newuser_subject")."\";");

      mail($to[email],$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>");
    }
  }

  updatecookie("vnuserid",$userid);
  updatecookie("vnpassword",md5($password));

  standardredirect("registration_done","index.php");

break;

case "activate":

  settype($u,"integer");
  settype($a,"integer");

  if ($data_arr = query_first("SELECT news_activation.date,news_activation.id,news_user.username,news_user.email,news_user.activated FROM news_activation LEFT JOIN news_user ON news_activation.userid = news_user.userid WHERE (news_activation.activateid = $a) AND (news_activation.userid = $u) AND (news_activation.type = 1)")) {
    if ($data_arr[date] < (time()-86400)) {
      $referer = iif($HTTP_REFERER,xss_clean($HTTP_REFERER),"index.php");
      standarderror("activation_expired");
    }

    query("DELETE FROM news_activation WHERE id = $data_arr[id]");
    query("UPDATE news_user SET activated = 1 WHERE userid = $u");

    $name = stripslashes($data_arr[username]);
    $email = $data_arr[email];

    if ($usermoderation) {
      eval("\$email_msg = \"".returnpagebit("register_email_mod_msg")."\";");
      eval("\$email_subject = \"".returnpagebit("register_email_mod_subject")."\";");
    } else {
      eval("\$email_msg = \"".returnpagebit("register_email_details_msg")."\";");
      eval("\$email_subject = \"".returnpagebit("register_email_details_subject")."\";");
    }
    mail($email,$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>");

    standardredirect("activation_complete","index.php");

  } else {
    standarderror("invalid_id");
  }

break;

case "lost_activate":

  $referer = iif($HTTP_REFERER,xss_clean($HTTP_REFERER),"index.php");
  $navbar = makenavbar("Request Activation Details");
  include("static/sub_pages/register_lost_".$pagesetid.".php");

break;

case "new_activate":

  if ($user = query_first("SELECT userid,username,email FROM news_user WHERE username = '$name'")) {

    if ($temp_arr = query_first("SELECT id FROM news_activation WHERE (userid = $user[userid]) AND (type = 1)")) {
      query("DELETE FROM news_activation WHERE id = $temp_arr[id]");
    } else {
      standarderror("already_activated");
    }

    mt_srand(time());
    $activateid = mt_rand(0,999999);

    query("INSERT INTO news_activation VALUES (NULL,'$user[userid]','".time()."','$activateid','1')");

    $user[username] = stripslashes($user[username]);
    eval("\$email_msg .= \"".returnpagebit("register_email_activation_msg")."\";");
    eval("\$email_subject .= \"".returnpagebit("register_email_activation_subject")."\";");

    mail($user[email],$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>");
    standardredirect("acitvation_resent",iif($redirect,$redirect,"index.php"));

  } else {
    standarderror("wrong_username");
  }

break;

default:
  standarderror("invalid_link");
}

/*======================================================================*\
|| ####################################################################
|| # File: register.php
|| ####################################################################
\*======================================================================*/

?>