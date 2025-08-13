<?php
#$Id: auth.inc.php,v 1.14 2003/09/29 18:06:52 ryan Exp $
require_once('includes/config.inc.php');
require_once('includes/var.inc.php');
require_once('includes/functions.inc.php');
require_once('includes/moduleVar.inc.php');
$core = new coreFunctions();
$core->connectdb();
$ssl = $core->dbCall("secure");
if ($_SERVER["SERVER_PORT"] != 443 && $ssl == 1){
    $head = "Location: https://" . $_SERVER["HTTP_HOST"] .  $_SERVER["REQUEST_URI"];
    header($head);
}



if (isset($_COOKIE["PXL"])){
    $userinfo = $core->findUserInfo($_COOKIE["PXL"]);
    if ($userinfo[6] == 1){ $core->logout($userinfo, "s"); }
    if ($userinfo[5] != 1){ $core->logout($userinfo, "a"); }
}
else {
    if (!isset($username)){ header("Location: login.php"); }
    $password = md5($_POST['password']);
    $username = strtolower($_POST['username']);
    $result = mysql_query("select user_id from users where password = '$password' and lower(username) = '$username'");
    $count = mysql_num_rows($result);
    if ($count != 1){ header("Location: login.php?code=i"); }
    else {
        list($user_id) = mysql_fetch_row($result);
        setcookie("PXL", $user_id, time() + 604800, '', '', $ssl);
        $core->addLog("Login", $username, $user_id);
        $userinfo = $core->findUserInfo($user_id);
        if ($_POST['username'] == "admin" && $_POST['password'] == "admin"){
            setcookie("FIRST", 1, 0, '', '', $ssl);
            header("Location: config.php");
        }
        header("Location: index.php");
    }
}




