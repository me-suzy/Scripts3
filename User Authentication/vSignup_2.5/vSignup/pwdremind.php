<?
/*
# File: pwdremind.php
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
<html>
<head>
<title>vSignup Password Reminder</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<?php
	// Random Password Generator
	// Added 07242004
	function GeneratePassword() {
		$newpass = '';
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		srand((double)microtime()*1000000);  	
		$i = 0;	
		while ($i < 8) {  // change for other length
			$num = rand() % strlen($salt);	
			$tmp = substr($salt, $num, 1);
			$newpass = $newpass . $tmp;	
			$i++;	
		}
		return $newpass;
	}
	
	include_once ("signupconfig.php");
	include_once ("auth.php");
	$connection = mysql_connect($dbhost, $dbusername, $dbpassword);
	$db = mysql_select_db($dbname);

	// Check if we have instantiated $action and $act variable
	// If yes, get the value from previous posting
	// If not, set values to null or ""
	 
	if (isset($_POST['remind'])) 
	{
		$remind = $_POST['remind'];
		$username = addslashes($_POST['username']);
	}
	else
	{
		$remind = '';
		$username = '';
	}

	if ($remind == 'Send')
	{
		$qReminder = mysql_query("SELECT * FROM signup WHERE uname='$username'");
		$RemindRow = mysql_fetch_array($qReminder);
		$numrows = mysql_num_rows($qReminder);

		$qEmailer = mysql_query("SELECT * FROM emailer WHERE profile='Password Reminder'");
		$EmailerRow = mysql_fetch_array($qEmailer);
		$qDetails = mysql_query("SELECT * FROM authuser WHERE uname='$username'");
		$Details = mysql_fetch_array($qDetails);

		if (mysql_num_rows($qReminder) == 0)
		{
			print "<font face=\"Verdana\" size=\"2\" color=\"#FF0000\">";
			print "		<b>The user does not have a record in our database.</b>";
			print "</font>";
			exit();
		}
		else
		{
			// Get values for emailer
			$name = $EmailerRow["name"];
			$email = $EmailerRow["email"];
			$subject = $EmailerRow["subject"];
			$newpass = GeneratePassword();
			
			// Username Details
			$username = $Details['uname'];
			$team = $Details['team'];
			$level = $Details['level'];
			$status = $Details['status'];			
			
			// User Details for signup
			$fname = $RemindRow["fname"];
			$lname = $RemindRow["lname"];
			$UserEmail = $RemindRow["email"];		
			
			// Insert HTML line breaks for newlines in $ReminderMessage
			// FIND A BETTER WAY FOR THIS. WE SHOULD AVOID USING HTML TAGS FOR EMAIL
			// BECAUSE NOT ALL CLIENTS SUPPORT HTML IN EMAIL MESSAGES
			$ReminderMessage = $EmailerRow["emailmessage"];
		
			// Replace occurances of [[UNAME]] in template
			$ReminderMessage = str_replace ("[[UNAME]]", $username, $ReminderMessage);

			// Replace occurances of [[FNAME]] in template
			$ReminderMessage = str_replace ("[[FNAME]]", $fname, $ReminderMessage);

			// Replace occurances of [[LNAME]] in template
			$ReminderMessage = str_replace ("[[LNAME]]", $lname, $ReminderMessage);

			// Replace occurances of [[EMAIL]] in template
			$ReminderMessage = str_replace ("[[EMAIL]]", $UserEmail, $ReminderMessage);
			
			// Replace occurances of [[NEWPASS]] in template
			$ReminderMessage = str_replace ("[[NEWPASS]]", $newpass, $ReminderMessage);
			
			// Change password in database
			$user = new auth();
			$update = $user->modify_user($username, $newpass, $team, $level, $status);

			// Send mail if password has been changed properly
			if ($update == 1)
			{
				$sent = @mail($UserEmail, $subject, $ReminderMessage,  "From:$name<$email>\nReply-to:$email"); 
				// $sent = @mail($UserEmail, $subject, $ReminderMessage,  "From:$name<$email>\nReply-to:$email\nContent-Type: text/html; charset=iso-8859-15"); 
			}

			// echo $ReminderMessage;	// DEBUGGER
			
			if ($sent)
			{
				print "<font face=\"Verdana\" size=\"2\" color=\"#FF0000\">";
				print "		<b>The email has been sent to the email address you've specified during the signup process.</b>";
				print "		<p>&nbsp;</p>";
				print "</font>";
				include ("login.php");	
				exit();			
			}
			exit();
		}
	}
?>

<body bgcolor="#FFFFFF" text="#000000">
<p><font face="Arial, Helvetica, sans-serif" size="4"><b>PASSWORD REMINDER</b></font></p>
<table width="60%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><font face="Arial, Helvetica, sans-serif" size="2">Please 
      enter your username below and click &quot;Send&quot; to receive your password 
      again via email. The message will be sent to the email address you've specified 
      during the signup process. </font></td>
  </tr>
</table>
<form name="PasswordReminder" method="post" action="pwdremind.php">
	<font face="Arial, Helvetica, sans-serif" size="2">
		<b>Username: </b>
		<input type="text" name="username" size="15" maxlength="15">
		<input type="submit" name="remind" value="Send">
	</font>
</form>
<br>
</body>
</html>
