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

  global $ibf_cookieid;

  global $ibf_cookieid,$ibf_prefix,$loggedin,$userid;

  if ($loggedin) {
    query("DELETE FROM ".$ibf_prefix."sessions WHERE member_id = $userid");
  }

  updatecookie($ibf_cookieid."member_id",0,time()-1800);
  updatecookie($ibf_cookieid."pass_hash",0,time()-1800);
}

function dologin($name,$password,$adminlogin=0) {

  global $ibf_prefix,$ibf_cookieid,$inadmin;

  $userinfo = query_first("SELECT
    ".$ibf_prefix."members.id,
    ".$ibf_prefix."members.mgroup,
    ".$ibf_prefix."members.name,
    ".$ibf_prefix."members.password,
    ".$ibf_prefix."groups.g_view_board
    FROM ".$ibf_prefix."members
    LEFT JOIN ".$ibf_prefix."groups ON ".$ibf_prefix."members.mgroup = ".$ibf_prefix."groups.g_id
    WHERE ".$ibf_prefix."members.name = '$name'");

  if ($userinfo) {

    if ($userinfo[password] == md5($password)) {
      if ($userinfo[mgroup] == 1) {
        $error = "account_notmoderated";
      } elseif ($userinfo[g_view_board] == 0) {
        $error = "user_banned";
      } else {
        updatecookie($ibf_cookieid."member_id",$userinfo[id]);
        updatecookie($ibf_cookieid."pass_hash",md5($password));
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
    return $userinfo[id];
  }

}

function validateuser($userid,$userpassword) {

  global $ibf_prefix,$inadmin;

  if ($userid > 0) {

    $userinfo = query_first("SELECT
      ".$ibf_prefix."members.mgroup,
      ".$ibf_prefix."members.name,
      ".$ibf_prefix."members.password,
      ".$ibf_prefix."members.email,
      ".$ibf_prefix."members.hide_email,
      ".$ibf_prefix."members.view_sigs,
      ".$ibf_prefix."members.new_msg,
      ".$ibf_prefix."groups.g_view_board,
      news_staff.*
      FROM ".$ibf_prefix."members
      LEFT JOIN ".$ibf_prefix."groups ON ".$ibf_prefix."members.mgroup = ".$ibf_prefix."groups.g_id
      LEFT JOIN news_staff ON ".$ibf_prefix."members.id = news_staff.userid
      WHERE ".$ibf_prefix."members.id = $userid");

    if ($userinfo) {

      if ($userinfo[password] == $userpassword) {
        if ($userinfo[mgroup] != 1) {
          if ($userinfo[g_view_board] == 0) {
            unset($userinfo);
            if ($inadmin) {
              return false;
            } else {
              standarderror("user_banned");
            }
          }

          if ($GLOBALS[getpmmsg] & !$inadmin) {
            $GLOBALS[num_private_msgs] = $userinfo[new_msg];
          }

          $userinfo[username] = $userinfo[name];
          $userinfo[activated] = 1;
          $userinfo[moderated] = 1;
          $userinfo[showemail] = iif($userinfo[hide_email],0,1);
          $userinfo[viewsigs] = $userinfo[view_sigs];
          $userinfo[commentdefault] = $GLOBALS[commentreplydefault];
          $userinfo[emailnotification] = $GLOBALS[emailreplydefault];

          unset($userinfo[name]);
          unset($userinfo[view_sigs]);
          unset($userinfo[hide_email]);

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
  global $foruminfo,$forumpath,$ibf_prefix;

  $forumpostcount = query_first("SELECT COUNT(pid) AS count FROM ".$ibf_prefix."posts");
  $forumpostcount = $forumpostcount[count];

  $getforummembers = query_first("SELECT COUNT(id) AS count,MAX(id) AS max FROM ".$ibf_prefix."members");
  $forummembercount = $getforummembers[count];

  $forumnewestmember = query_first("SELECT id,name FROM ".$ibf_prefix."members WHERE id = $getforummembers[max]");
  $forumnewestmember = $forumnewestmember[name];

  eval("\$forumstats = \"".returnpagebit("misc_stats_forum")."\";");
  return $forumstats;
}

include("$forumpath/conf_global.php");
$ibf_prefix = $INFO[sql_tbl_prefix];
$ibf_cookieid = $INFO[cookie_id];
$ibf_htmlurl = $INFO[html_url];
unset($INFO);

// Set basic user details from cookies
$userid = intval($HTTP_COOKIE_VARS[$ibf_cookieid.'member_id']);
$userpassword = $HTTP_COOKIE_VARS[$ibf_cookieid.'pass_hash'];

// Set table names
$foruminfo[user_table] = $ibf_prefix."members";
$foruminfo[smilie_table] = $ibf_prefix."emoticons";

// Set field names
$foruminfo[email_field] = "email";
$foruminfo[joindate_field] = "joined";
$foruminfo[posts_field] = "posts";
$foruminfo[signature_field] = "signature";
$foruminfo[smiliepath_field] = "image";
$foruminfo[smilietext_field] = "typed";
$foruminfo[smilietitle_field] = "id";
$foruminfo[userid_field] = "id";
$foruminfo[username_field] = "name";

// File paths
$foruminfo[member_email_path] = "$forumpath/index.php?act=Mail&CODE=00&MID=\$id";
$foruminfo[member_info_path] = "$forumpath/index.php?act=Profile&CODE=03&MID=\$id";
$foruminfo[member_list_path] = "$forumpath/index.php?act=Members";
$foruminfo[member_search_path] = "$forumpath/index.php?act=Members";
$foruminfo[pm_main_path] = "$forumpath/index.php?act=Msg&CODE=01";
$foruminfo[pm_form_path] = "$forumpath/index.php?act=Msg&CODE=04&MID=\$touserid";
$foruminfo[pwd_forgot_path] = "$forumpath/index.php?act=Reg&CODE=10";
$foruminfo[register_path] = "$forumpath/index.php?act=Reg&CODE=00";
$foruminfo[smilie_path] = "$forumpath/index.php?act=legends&CODE=emoticons";
$foruminfo[usercp_path] = "$forumpath/index.php?act=UserCP&CODE=00";
$foruminfo[usercp_pwd_path] = "$forumpath/index.php?act=UserCP&CODE=28";
$foruminfo[usercp_profile_path] = "$forumpath/index.php?act=UserCP&CODE=01";

$foruminfo[smilie_image_path] = "$ibf_htmlurl/emoticons/";

/*======================================================================*\
|| ####################################################################
|| # File: includes/forum_ib_10.php
|| ####################################################################
\*======================================================================*/

?>