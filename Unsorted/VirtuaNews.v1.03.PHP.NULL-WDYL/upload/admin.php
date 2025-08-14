<?php

error_reporting(7);

require("includes/getglobals.php");

$pagestarttime = microtime();

unset($admindirectory);

require("admin/config.php");
require("includes/db_".strtolower(trim($dbservertype)).".php");
require("includes/adminfunctions.php");
require("includes/functions.php");
require("includes/writefunctions.php");

unset($db_query_count);
unset($db_query_arr);
unset($userinfo);
unset($userinfo);
unset($foruminfo);
unset($staffid);
unset($formhiddenfields);

if (empty($admindirectory) | !is_dir($admindirectory)) {
  $admindirectory = "admin";
}

$inadmin = 1;

$db = vn_connect();

if (!@include("static/options.php")) {
  saveoptions();
}

if (!empty($use_forum)) {
  require("includes/forum_".strtolower(trim($use_forum)).".php");
} else {
  require("includes/forum_vn.php");
}

if (!empty($HTTP_POST_VARS[username])) {
  $userid = dologin($HTTP_POST_VARS[username],$HTTP_POST_VARS[password]);
  $userpassword = md5($HTTP_POST_VARS[password]);
}

if ($userinfo = validateuser($userid,$userpassword)) {
  $staffid = $userinfo[id];
  if (intval($staffid) > 0) {
    $username = $userinfo[username];
    $loggedin = 1;
  } else {
    $loggedin = 0;
  }
} else {
  $loggedin = 0;
}

$location = cleanlocation();

if (!$loggedin) {

  echohtmlheader();
  echoformheader("","You are either not logged in or not a valid staff member");
  updatehiddenvar("redirect",$location);
  echotabledescription("Please enter your details to continue");
  echoinputcode("Username:","username");
  echopasswordcode("Password:","password");
  echoformfooter();
  echohtmlfooter();

  exit;
}

$cat_arr = getcat_arr();
$defaultcategory = $defaultcat_loggedout;

$timeoffset = $timeoffset * 3600;

if (file_exists("install.php")) {
  $loggedin = 0;
  adminerror("Install File Presant","The install file can pose a security risk it it is left on the server, it must be deleted before you can continue");
}

if (isset($redirect) & !empty($HTTP_POST_VARS[username]) & !empty($HTTP_POST_VARS[password])) {
  $loggedin = 0;

  echohtmlheader("<meta http-equiv=\"refresh\" content=\"1; url=$redirect\">");
  echotableheader("Logging In",1);
  echotabledescription("You are being logged in, you may click <a href=\"$redirect\">here</a> to speed up the process or if your browser does not automatically forward you.",1);
  echotablefooter();
  echohtmlfooter();
  exit;
}

$adminmenu = iif($HTTP_COOKIE_VARS[menu] == "open","open","closed");
if (!empty($HTTP_GET_VARS[menu])) {
  $adminmenu = iif($HTTP_GET_VARS[menu] == "open","open","closed");
}
updatecookie("menu",$adminmenu,iif($adminmenu == "closed",-1800,0));

$adminnav = iif($HTTP_COOKIE_VARS[useframes] == 1,"frames","page");
if (isset($HTTP_GET_VARS[useframes])) {
  $adminnav = iif($HTTP_GET_VARS[useframes],"frames","page");
}
updatecookie("useframes",iif($adminnav == "frames",1,0),iif($adminnav == "page",-1800,0));

unset($canpostnews);

foreach ($cat_arr AS $key => $val) {
  if ($userinfo[canpost_.$val[topid]]) {
    $canpostnews = 1;
    break;
  }
}

$userinfo[caneditmisc] = 1;

unset($action_arr);

$action_arr[article] = "editarticles";
$action_arr[category] = "editcategories";
$action_arr[log] = "viewlog";
$action_arr[maintain] = "maintaindb";
$action_arr[misc] = "editmisc";
$action_arr[module] = "editmodules";
$action_arr[option] = "editoptions";
$action_arr[poll] = "editpolls";
$action_arr[profilefield] = "editprofilefields";
$action_arr[smilie] = "editsmilies";
$action_arr[staff] = "editstaff";
$action_arr[theme] = "editthemes";
$action_arr[user] = "editusers";

foreach ($action_arr AS $key => $val) {
  if (substr($action,0,strlen($key)) == $key) {
    if ($userinfo[can.$val]) {
      include($admindirectory."/".$key.".php");
      exit;
    } else {
      adminerror("You are not allowed to access this section","You do not have permission to do this action");
    }
  }
}

unset($action_arr); // These actions check permissions themselves so no check needed here

$action_arr[] = "comment";
$action_arr[] = "newslogo";
$action_arr[] = "news";

foreach ($action_arr AS $val) {
  if (substr($action,0,strlen($val)) == $val) {
    include($admindirectory."/".$val.".php");
    exit;
  }
}

$moddata = getmoddata();;
while ($mod_arr = fetch_array($moddata)) {
  if (substr($action,0,strlen($mod_arr[name])) == $mod_arr[name]) {
    if ($userinfo[caneditmod_.$mod_arr[name]]) {
      $modname = $mod_arr[name];
      $modid = $mod_arr[id];
      include("modules/$mod_arr[name]/admin.php");
      exit;
    } else {
      adminerror("You are not allowed to access this section","You do not have permission to do this action");
    }
  }
}

if ($adminnav == "frames") {

  echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
  <title>$sitename Admin Panel</title>
</head>

<frameset cols=\"200, *\" frameborder=\"no\" border=\"0\" framespacing=\"0\">
  <frame name=\"menu\" noresize=\"noresize\" scrolling=\"auto\" src=\"admin.php?action=misc_menu\">
  <frame name=\"main\" noresize=\"noresize\" scrolling=\"auto\" src=\"admin.php?action=misc_index\">
</frameset>
</html>";

} else {
  $action = "misc_index";
  include("$admindirectory/misc.php");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin.php
|| ####################################################################
\*======================================================================*/

?>