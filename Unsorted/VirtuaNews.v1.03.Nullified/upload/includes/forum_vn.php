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

function dologout() {
  updatecookie("vnuserid",0,time()-1800);
  updatecookie("vnpassword",0,time()-1800);
}

function dologin($name,$password,$adminlogin=0) {

  global $inadmin;

  $userinfo = query_first("SELECT userid,username,password,activated,moderated,isbanned FROM news_user WHERE username = '$name'");

  if ($userinfo) {

    if ($userinfo[password] == md5($password)) {
      if ($userinfo[activated] == 0) {
        $error = "email_notactivated";
      } elseif ($userinfo[moderated] == 0) {
        $error = "account_notmoderated";
      } elseif ($userinfo[isbanned]) {
        $error = "user_banned";
      } else {
        updatecookie("vnuserid",$userinfo[userid]);
        updatecookie("vnpassword",md5($password));
        $error = "";
      }
    } else { // Password wrong
      $error = "wrong_password";
    }
  } else { // Username wrong
    $error = "wrong_username";
  }

  if (!empty($error) & $inadmin) {
    return false;
  } elseif (!empty($error) & !$inadmin) {
    standarderror($error);
  } else {
    return $userinfo[userid];
  }

}

function validateuser($userid,$userpassword) {

  global $inadmin;

  if ($userid > 0) {

    $userinfo = query_first("SELECT
      news_user.username,
      news_user.password,
      news_user.email,
      news_user.showemail,
      news_user.activated,
      news_user.moderated,
      news_user.commentdefault,
      news_user.viewsigs,
      news_user.allowpm,
      news_user.isbanned,
      news_user.emailnotification,
      news_staff.*
      FROM news_user
      LEFT JOIN news_staff ON news_user.userid = news_staff.userid
      WHERE news_user.userid = $userid");

    if ($userinfo) {

      if ($userinfo[password] == $userpassword) {
        if ($userinfo[moderated] & $userinfo[activated]) {
          if ($userinfo[isbanned]) {
            unset($userinfo);
            if ($inadmin) {
              return false;
            } else {
              standarderror("user_banned");
            }
          }

          if ($GLOBALS[getpmmsg] & !$inadmin) {
            $count_pms = query_first("SELECT COUNT(id) AS count FROM news_pm WHERE (touserid = $userid) AND (folder = 1) AND (readdate = 0)");
            $GLOBALS[num_private_msgs] = $count_pms[count];
          }

          return $userinfo;
        } else { // Account not activated
          unset($userinfo);
          return false;
        }
      } else { // Password wrong
        unset($userinfo);
        return false;
      }
    } else { // Userid wrong
      unset($userinfo);
      return false;
    }
  } else { // No userid
    unset($userinfo);
    return false;
  }
}

function returnforumstats() {
  return false;
}

// Set basic user details from cookies
$userid = intval($HTTP_COOKIE_VARS[vnuserid]);
$userpassword = $HTTP_COOKIE_VARS[vnpassword];

// Set table names
$foruminfo[user_table] = "news_user";
$foruminfo[smilie_table] = "news_smilie";

// Set field names
$foruminfo[email_field] = "email";
$foruminfo[joindate_field] = "joindate";
$foruminfo[posts_field] = "posts";
$foruminfo[signature_field] = "signature";
$foruminfo[smiliepath_field] = "smiliepath";
$foruminfo[smilietext_field] = "smilietext";
$foruminfo[smilietitle_field] = "title";
$foruminfo[userid_field] = "userid";
$foruminfo[username_field] = "username";

// File paths
$foruminfo[member_email_path] = "member.php?action=email&id=\$id";
$foruminfo[member_info_path] = "member.php?action=profile&id=\$id";
$foruminfo[member_list_path] = "member.php?action=list";
$foruminfo[member_search_path] = "member.php?action=list_search";
$foruminfo[pm_main_path] = "user.php?action=pm";
$foruminfo[pm_form_path] = "user.php?action=pm_form&touserid=\$touserid";
$foruminfo[pwd_forgot_path] = "member.php?action=pwd_forgot";
$foruminfo[register_path] = "register.php";
$foruminfo[smilie_path] = "help.php?action=smilie";
$foruminfo[usercp_path] = "user.php";
$foruminfo[usercp_pwd_path] = "user.php?action=pwd";
$foruminfo[usercp_profile_path] = "member.php?action=profile&id=\$id";

$foruminfo[smilie_image_path] = "";

/*======================================================================*\
|| ####################################################################
|| # File: includes/forum_vn.php
|| ####################################################################
\*======================================================================*/

?>