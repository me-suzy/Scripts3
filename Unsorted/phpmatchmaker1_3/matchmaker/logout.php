<?
require_once("php_inc.php");
session_start();
include("header_inc.php");
db_connect();

$day = date("d");
$month = date("m");
$year = date("Y");
$string = $year . $month . $day;
$result = mysql_query("update user set lastlogin = '$string' where username = '$valid_user'");

$old = $valid_user;
$result_unreg = session_unregister("valid_user");
$result_dest = session_destroy();

do_html_heading("Logging out");
?>
You are now logged out. <a href="login.php">Login again</a> ?
<? include("footer_inc.php"); ?>
