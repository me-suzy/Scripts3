<?php
// ******************************* Edit Below ****************************
// Please add your vB license number below, it will NOT be used for
// anything other than setting cookies for vB3 due to the method it uses
// for storing user's passwords.
// You can get this number by viewing any PHP file from vB3 and looking
// on the top few lines of the file
$vb_license = '';
// ******************************* Finish Edit ***************************

function dologout() {

  global $HTTP_COOKIE_VARS,$vb_prefix,$userid,$loggedin;

  if ($loggedin) {
    query("DELETE FROM ".$vb_prefix."session WHERE userid = $userid");
  }

  foreach ($HTTP_COOKIE_VARS AS $key => $val) {
    if (substr($key,0,2) == "bb") {
      updatecookie($key, '', time()-1800);
    }
  }
}

function dologin($name,$password) {

  global $inadmin,$vb_prefix,$vb_license,$ipaddress,$HTTP_COOKIE_VARS;

  $userinfo = query_first("SELECT
    ".$vb_prefix."user.usergroupid,
    ".$vb_prefix."user.username,
    ".$vb_prefix."user.userid,
    ".$vb_prefix."user.password,
    ".$vb_prefix."user.salt,
    ".$vb_prefix."userban.bandate,
    ".$vb_prefix."userban.liftdate
    FROM ".$vb_prefix."user
    LEFT JOIN ".$vb_prefix."userban ON ".$vb_prefix."user.userid = ".$vb_prefix."userban.userid
    WHERE ".$vb_prefix."user.username = '$name'");

  if ($userinfo) {

    if ($userinfo[password] == md5(md5($password) . $userinfo[salt])) {
      if ($userinfo[usergroupid] == 3) {
        $error = "email_notactivated";
      } elseif ($userinfo[usergroupid] == 4) {
        $error = "account_notmoderated";
      } elseif ($userinfo[bandate] & (($userinfo[liftdate] == 0) | (time() < $userinfo[liftdate]))) {
        $error = "user_banned";
      } else {

        foreach ($HTTP_COOKIE_VARS AS $key => $val) {
          if (substr($key,0,2) == "bb") {
            updatecookie($key, '', time()-1800);
          }
        }

        query("DELETE FROM ".$vb_prefix."session WHERE (userid = 0) AND (host = '$ipaddress')");

        updatecookie("bbuserid",$userinfo[userid]);
        updatecookie("bbpassword",md5($userinfo['password'] . $vb_license));
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

  global $inadmin,$vb_prefix,$vb_license,$HTTP_POST_VARS;

  if ($userid > 0) {

    $userinfo = query_first("SELECT
      ".$vb_prefix."user.usergroupid,
      ".$vb_prefix."user.username,
      ".$vb_prefix."user.salt,
      ".$vb_prefix."user.userid,
      ".$vb_prefix."user.password,
      ".$vb_prefix."user.email,
      ".$vb_prefix."user.options,
      ".$vb_prefix."user.pmunread,
      ".$vb_prefix."usergroup.genericoptions,
      news_staff.*
      FROM ".$vb_prefix."user
      INNER JOIN ".$vb_prefix."usergroup ON ".$vb_prefix."user.usergroupid = ".$vb_prefix."usergroup.usergroupid
      LEFT JOIN news_staff ON ".$vb_prefix."user.userid = news_staff.userid
      WHERE ".$vb_prefix."user.userid = $userid");

    if ($userinfo) {

      if ($inadmin & !empty($HTTP_POST_VARS[username])) {
        $userpassword = md5(md5($userpassword.$userinfo[salt]).$vb_license);
      }

      if ((md5($userinfo[password] . $vb_license)) == $userpassword) {
        if (($userinfo[usergroupid] != 3) & ($userinfo[usergroupid] != 4)) {
          if ($userinfo[genericoptions] & 32) {
            unset($userinfo);
            if ($inadmin) {
              return false;
            } else {
              standarderror("user_banned");
            }
          }

          if ($GLOBALS[getpmmsg] & !$inadmin) {
            $GLOBALS[num_private_msgs] = $userinfo[pmunread];
          }

          $userinfo[viewsigs] = iif($userinfo[options] & 1,1,0);
          $userinfo[activated] = 1;
          $userinfo[moderated] = 1;
          $userinfo[showemail] = iif($userinfo[options] & 256,1,0);
          $userinfo[emailnotification] = iif($userinfo[options] & 16384,1,0);
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

  global $foruminfo,$forumpath,$vb_prefix;

  $getdata = query("SELECT * FROM ".$vb_prefix."datastore WHERE title IN ('forumcache','userstats')");

  while ($data = fetch_array($getdata)) {

    if ($data['title'] == 'userstats') {
      $userdata = unserialize($data['data']);
    } else {
      $forumdata = unserialize($data['data']);
    }
  }

  $forumpostcount = 0;

  if (is_array($forumdata)) {
    foreach ($forumdata AS $forum) {
      $forumpostcount += $forum['replycount'];
    }
  }

  $forumnewestmember = $userdata[newusername];
  $forummembercount = $userdata[numbermembers];

  eval("\$forumstats = \"".returnpagebit("misc_stats_forum")."\";");

  return $forumstats;

}

function get_vb_prefix($path) {
  require("$path/includes/config.php");

  return $tableprefix;
}

$vb_prefix = get_vb_prefix($forumpath);

// Set basic user details from cookies
$userid = intval($HTTP_COOKIE_VARS[bbuserid]);
$userpassword = $HTTP_COOKIE_VARS[bbpassword];

// Set table names
$foruminfo[user_table] = $vb_prefix."user";
$foruminfo[usertext_table] = $vb_prefix."usertextfield";
$foruminfo[smilie_table] = $vb_prefix."smilie";

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
$foruminfo[member_email_path] = "$forumpath/sendmessage.php?do=mailmember&userid=\$id";
$foruminfo[member_info_path] = "$forumpath/member.php?userid=\$id";
$foruminfo[member_list_path] = "$forumpath/memberlist.php";
$foruminfo[member_search_path] = "$forumpath/memberlist.php?do=search";
$foruminfo[pm_main_path] = "$forumpath/private.php";
$foruminfo[pm_form_path] = "$forumpath/private.php?do=newpm&userid=\$touserid";
$foruminfo[pwd_forgot_path] = "$forumpath/login.php?do=lostpw";
$foruminfo[register_path] = "$forumpath/register.php";
$foruminfo[smilie_path] = "$forumpath/misc.php?do=showsmilies";
$foruminfo[usercp_path] = "$forumpath/usercp.php";
$foruminfo[usercp_pwd_path] = "$forumpath/profile.php?do=editpassword";
$foruminfo[usercp_profile_path] = "$forumpath/profile.php?do=editprofile";

$foruminfo[smilie_image_path] = "$forumpath/";

/*======================================================================*\
|| ####################################################################
|| # File: includes/forum_vb_30.php
|| ####################################################################
\*======================================================================*/

?>
