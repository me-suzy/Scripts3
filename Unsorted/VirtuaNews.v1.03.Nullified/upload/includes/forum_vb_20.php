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
  updatecookie("bbuserid",0,time()-1800);
  updatecookie("bbpassword",0,time()-1800);
}

function dologin($name,$password) {

  global $inadmin;

  $userinfo = query_first("SELECT
    user.userid,
    user.usergroupid,
    user.username,
    user.password,
    usergroup.canview
    FROM user
    LEFT JOIN usergroup ON user.usergroupid = usergroup.usergroupid
    WHERE user.username = '$name'");

  if ($userinfo) {

    if ($userinfo[password] == $password) {
      if ($userinfo[usergroupid] == 3) {
        $error = "email_notactivated";
      } elseif ($userinfo[usergroupid] == 4) {
        $error = "account_notmoderated";
      } elseif ($userinfo[canview] == 0) {
        $error = "user_banned";
      } else {
        updatecookie("bbuserid",$userinfo[userid]);
        updatecookie("bbpassword",md5($password));
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
      user.usergroupid,
      user.username,
      user.password,
      user.email,
      user.showemail,
      user.options,
      user.emailnotification,
      usergroup.canview,
      news_staff.*
      FROM user
      LEFT JOIN usergroup ON user.usergroupid = usergroup.usergroupid
      LEFT JOIN news_staff ON user.userid = news_staff.userid
      WHERE user.userid = $userid");

    if ($userinfo) {

      if (md5($userinfo[password]) == $userpassword) {
        if (($userinfo[usergroupid] != 3) & ($userinfo[usergroupid] != 4)) {
          if ($userinfo[canview] == 0) {
            unset($userinfo);
            if ($inadmin) {
              return false;
            } else {
              standarderror("user_banned");
            }
          }

          if ($GLOBALS[getpmmsg] & !$inadmin) {
            $count_pms = query_first("SELECT COUNT(userid) AS count FROM privatemessage WHERE (userid = $userid) AND (messageread = 0)");
            $GLOBALS[num_private_msgs] = $count_pms[count];
          }

          $userinfo[viewsigs] = iif($userinfo[options] & 1,1,0);
          $userinfo[activated] = 1;
          $userinfo[moderated] = 1;
          $userinfo[commentdefault] = $GLOBALS[commentreplydefault];

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

  $forumpostcount = query_first("SELECT COUNT(postid) AS count FROM post");
  $forumpostcount = $forumpostcount[count];

  $getforummembers = query_first("SELECT count(userid) AS count,MAX(userid) AS max FROM user");
  $forummembercount = $getforummembers[count];

  $forumnewestmember = query_first("SELECT userid,username FROM user WHERE userid = $getforummembers[max]");
  $forumnewestmember = $forumnewestmember[username];

  eval("\$forumstats = \"".returnpagebit("misc_stats_forum")."\";");

  return $forumstats;

}

// Set basic user details from cookies
$userid = intval($HTTP_COOKIE_VARS[bbuserid]);
$userpassword = $HTTP_COOKIE_VARS[bbpassword];

// Set table names
$foruminfo[user_table] = "user";
$foruminfo[smilie_table] = "smilie";

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
$foruminfo[member_email_path] = "$forumpath/member.php?action=mailform&userid=\$id";
$foruminfo[member_info_path] = "$forumpath/member.php?action=getinfo&userid=\$id";
$foruminfo[member_list_path] = "$forumpath/memberlist.php";
$foruminfo[member_search_path] = "$forumpath/memberlist.php?action=search";
$foruminfo[pm_main_path] = "$forumpath/private.php";
$foruminfo[pm_form_path] = "$forumpath/private.php?action=newmessage&userid=$touserid";
$foruminfo[pwd_forgot_path] = "$forumpath/member.php?action=lostpw";
$foruminfo[register_path] = "$forumpath/register.php";
$foruminfo[smilie_path] = "$forumpath/misc.php?action=showsmilies";
$foruminfo[usercp_path] = "$forumpath/usercp.php";
$foruminfo[usercp_pwd_path] = "$forumpath/member.php?action=editpassword";
$foruminfo[usercp_profile_path] = "$forumpath/member.php?action=editprofile";

$foruminfo[smilie_image_path] = "$forumpath/";

/*======================================================================*\
|| ####################################################################
|| # File: includes/forum_vb_20.php
|| ####################################################################
\*======================================================================*/

?>