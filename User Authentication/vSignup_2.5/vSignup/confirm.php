<?
/*
# File: confirm.php
# Script Name: vSignup 2.5
# Author: Vincent Ryan Ong
# Email: support@beanbug.net
#
# Description:
# vSignup is a member registration script which utilizes vAuthenticate
# for its security handling. This handy script features email verification,
# sending confirmation email message, restricting email domains that are 
# allowed for membership, and much more.
#
# This script is a freeware but if you want to give donations,
# please send your checks (coz cash will probably be stolen in the
# post office) to:
#
# Vincent Ryan Ong
# Rm. 440 Wellington Bldg.
# 655 Condesa St. Binondo, Manila
# Philippines, 1006
*/
?>
<? 
    require_once ("signupconfig.php");
    require_once ("authconfig.php");
 ?>
<html>
<head>
<title>Confirm Membership</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?
	if (isset($_GET['confirmkey']))
	{
		$confirmkey = $_GET['confirmkey'];
	}

	include_once ("auth.php");
	$connection = mysql_connect($dbhost, $dbusername, $dbpassword);
	$db = mysql_select_db($dbname);
	$result = mysql_query("SELECT * FROM signup WHERE confirmkey='$confirmkey'");
	$numrow = mysql_num_rows ($result);

	// Confirmation key is not found
	if ($numrow==0)
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\">";
		print "<b>Your confirmation key is not valid!<br>";
		print "Please contact the system administrator for your membership details.</b></font></p>";
		exit;
	}
	else
	{
		// signup table
		$row = mysql_fetch_array($result);
		$username = $row["uname"];
		$AuthResult = mysql_query("SELECT * FROM authuser WHERE uname='$username'");
		
		// authuser table
		$row = mysql_fetch_array($AuthResult);
		$password = $row["passwd"];
		$team = $row["team"];
		$level = $row["level"];
		$status = $row["status"];
	}
	
	// Make member active
	$x = new auth();
	$Activate = $x->modify_user($username, '', $team, $level, "active");

	if ($Activate==1)
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\">";
		print "<b>Your membership has been confirmed!<br>";
		print "You may login below to enter the members area.</b></font></p>";
		include ($login);
	}
?>
</html>
