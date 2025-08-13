<?php // adminOnly.php : if the session variables are not set or are incorrect values then present the login screen
require_once("util.php");
/* compare the PHP server version with the phpYellow Pages required version to see if 4.1.2 session handling works */
$currentPHPversion = phpversion();
$currentPHPversion = str_replace  ( ".", "", $currentPHPversion); // remove period characters example 4.1.2
$currentPHPversion = substr($currentPHPversion, 0, 2); // get the first 2 characters
// do the same data handling again for the benchmark php version of PHP 4.1.2
$yardstick = 41; // actually 4.1.2 but we chop the 2 anyway 
if($currentPHPversion < $yardstick) { // is current php version greater or less than the minimum needed 4.1.2?
	// handle with no superglobals, cannot initialize variables with $HTTP_POST_VARS['FOO'] because in safe mode these vars are lost on a redirect
	// if IIS php.ini safemode must be 'off'
	if( ($formuser != ADMINUSER) || ($formpassword != ADMINPASSWORD) ) {
		header("Location: adminLogin.php");
		exit;
	}else{
		echo"<table class=\"favcolor\" width=\"100%\"><tr><td align=\"left\"><a href=\"" . ADMINHOME . "?formuser=$formuser&formpassword=$formpassword\">Admin</a></td><td align=\"center\"><i style=\"font-size:xx-small;\">This text is your assurance of secure access to this page</i></td><td align=\"right\"><a href=\"" . ADMINHOME . "?formuser=$formuser&formpassword=$formpassword\">Admin</a></td></tr></table>";
	}
}else{
	session_start();
	if ((!isset($HTTP_SESSION_VARS["adminUser"])) || (!isset($HTTP_SESSION_VARS["adminPassword"])) || ($HTTP_SESSION_VARS["adminUser"] != ADMINUSER ) || ($HTTP_SESSION_VARS["adminPassword"]  != ADMINPASSWORD) ) {
		header("Location: adminLogin.php");
		exit;
	}else{
		echo"<table class=\"favcolor\" width=\"100%\"><tr><td align=\"left\"><a href=\"adminLogOut.php\">Log Out</a> | <a href=\"" . ADMINHOME . "\">Admin</a></td><td align=\"center\"><i style=\"font-size:xx-small;\">This text is your assurance of secure access to this page</i></td><td align=\"right\"><a href=\"" . ADMINHOME . "\">Admin</a> | <a href=\"adminLogOut.php\">Log Out</a></td></tr></table>";
	}
}
?>