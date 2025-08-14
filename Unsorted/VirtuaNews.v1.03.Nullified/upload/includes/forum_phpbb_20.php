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

  global $phpbb_cookiename;
  updatecookie($phpbb_cookiename."_data","",time()-1800);
}

function dologin($name,$password,$adminlogin=0) {

  global $phpbb_cookiename,$inadmin;

  $userinfo = query_first("SELECT
    user_id AS userid,
    user_active,
    username,
    user_password
    FROM ".USERS_TABLE."
    WHERE username = '$name'");

  if ($userinfo) {

    if ($userinfo[user_password] == md5($password)) {
      if ($userinfo[user_active] == 0) {
        $error = "account_notmoderated";
      } else {

        $cookiedata[userid] = $userinfo[userid];
        $cookiedata[autologinid] = md5($password);

        updatecookie($phpbb_cookiename."_data",urlencode(serialize($cookiedata)));
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
      ".USERS_TABLE.".user_active,
      ".USERS_TABLE.".username,
      ".USERS_TABLE.".user_password,
      ".USERS_TABLE.".user_email,
      ".USERS_TABLE.".user_viewemail,
      ".USERS_TABLE.".user_notify,
      ".USERS_TABLE.".user_actkey,
      ".USERS_TABLE.".user_new_privmsg,
      news_staff.*
      FROM ".USERS_TABLE."
      LEFT JOIN news_staff ON ".USERS_TABLE.".user_id = news_staff.userid
      WHERE ".USERS_TABLE.".user_id = $userid");

    if ($userinfo) {

      if ($userinfo[user_password] == $userpassword) {
        if (empty($userinfo[user_actkey])) {
          if ($userinfo[user_active] == 0) {
            unset($userinfo);
            if ($inadmin) {
              return false;
            } else {
              standarderror("user_banned");
            }
          }

          if ($GLOBALS[getpmmsg] & !$inadmin) {
            $GLOBALS[num_private_msgs] = $userinfo[user_new_privmsg];
          }

          $userinfo[activated] = 1;
          $userinfo[moderated] = 1;
          $userinfo[showemail] = $userinfo[user_viewemail];
          $userinfo[viewsigs] = 1;
          $userinfo[commentdefault] = $GLOBALS[commentreplydefault];
          $userinfo[emailnotification] = $GLOBALS[user_notify];
          $userinfo[email] = $userinfo[user_email];

          unset($userinfo[user_new_privmsg]);
          unset($userinfo[user_active]);
          unset($userinfo[user_actkey]);
          unset($userinfo[user_notify]);
          unset($userinfo[user_viewemail]);

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
  global $foruminfo,$forumpath;

  $forumpostcount = query_first("SELECT COUNT(post_id) AS count FROM ".POSTS_TABLE);
  $forumpostcount = $forumpostcount[count];

  $getforummembers = query_first("SELECT COUNT(user_id) AS count,MAX(user_id) AS max FROM ".USERS_TABLE);
  $forummembercount = $getforummembers[count];

  $forumnewestmember = query_first("SELECT user_id,username FROM ".USERS_TABLE." WHERE user_id = $getforummembers[max]");
  $forumnewestmember = $forumnewestmember[username];

  eval("\$forumstats = \"".returnpagebit("misc_stats_forum")."\";");
  return $forumstats;
}

define("IN_PHPBB",1);

include("$forumpath/extension.inc");
include("$forumpath/config.$phpEx");
include("$forumpath/includes/constants.$phpEx");

$getdata = query("SELECT config_name,config_value FROM ".CONFIG_TABLE." WHERE config_name IN ('cookie_name','smilies_path')");
while ($data_arr = fetch_array($getdata)) {
  ${$data_arr[config_name]} = $data_arr[config_value];
}

$phpbb_cookiename = $cookie_name;

// Set basic user details from cookies

$cookiedata = unserialize(stripslashes(urldecode($HTTP_COOKIE_VARS[$phpbb_cookiename.'_data'])));
$userid = intval($cookiedata[userid]);
$userpassword = $cookiedata[autologinid];

// Set table names
$foruminfo[user_table] = USERS_TABLE;
$foruminfo[smilie_table] = SMILIES_TABLE;

// Set field names
$foruminfo[email_field] = "user_email";
$foruminfo[joindate_field] = "user_regdate";
$foruminfo[posts_field] = "user_posts";
$foruminfo[signature_field] = "user_sig";
$foruminfo[smiliepath_field] = "smile_url";
$foruminfo[smilietext_field] = "code";
$foruminfo[smilietitle_field] = "emoticon";
$foruminfo[userid_field] = "user_id";
$foruminfo[username_field] = "username";

// File paths
$foruminfo[member_email_path] = "$forumpath/profile.php?mode=email&".POST_USERS_URL."=\$id";
$foruminfo[member_info_path] = "$forumpath/profile.php?mode=viewprofile&".POST_USERS_URL."=\$id";
$foruminfo[member_list_path] = "$forumpath/memberlist.php";
$foruminfo[member_search_path] = "$forumpath/memberlist.php";
$foruminfo[pm_main_path] = "$forumpath/privmsg.php?folder=inbox";
$foruminfo[pm_form_path] = "$forumpath/privmsg.php?mode=post";
$foruminfo[pwd_forgot_path] = "$forumpath/profile.php?mode=sendpassword";
$foruminfo[register_path] = "$forumpath/profile.php?mode=register";
$foruminfo[smilie_path] = "$forumpath/posting.php?mode=smilies";
$foruminfo[usercp_path] = "$forumpath/profile.php?mode=editprofile";
$foruminfo[usercp_pwd_path] = "$forumpath/profile.php?mode=editprofile";
$foruminfo[usercp_profile_path] = "$forumpath/profile.php?mode=editprofile";

$foruminfo[smilie_image_path] = "$forumpath/$smilies_path/";

/*======================================================================*\
|| ####################################################################
|| # File: includes/forum_phpbb_20.php
|| ####################################################################
\*======================================================================*/

?>