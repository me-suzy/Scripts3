<?php // adminOnly.php : if the session variables are not set or are incorrect values then present the login screen
require_once("configure.php");
session_start();
if ((!isset($HTTP_SESSION_VARS["adminUser"])) || (!isset($HTTP_SESSION_VARS["adminPassword"])) || ($HTTP_SESSION_VARS["adminUser"] != ADMINUSER ) || ($HTTP_SESSION_VARS["adminPassword"]  != ADMINPASSWORD) ) {
	header("Location: adminLogin.php");
	exit;
}else{
	echo"<table class=\"favcolor2\" width=\"100%\"><tr><td align=\"left\"><a href=\"adminLogOut.php\">Log Out</a> | <a href=\"" . ADMINHOME . "\">Admin</a></td><td align=\"center\"><i style=\"font-size:xx-small;\">This page is admin-Login-Only secured using PHP4 session access</i></td><td align=\"right\"><a href=\"" . ADMINHOME . "\">Admin</a> | <a href=\"adminLogOut.php\">Log Out</a></td></tr></table>";
	//$sid = session_id();
	//echo"<br><span style=\"color:green;font-weight:bold;\">Session: " . $sid . "</span><br>";
	//echo "<br>The HTTP_SESSION_VARS[adminUser] is $adminUser";
	//echo "<br>The HTTP_SESSION_VARS[adminPassword] is $adminPassword<br>";
}
?>