<?
/*
# File: process.php
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
<? require_once ("signupconfig.php"); ?>
<html>
<head>
<title>vSignup 2.5</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<?
	include ("auth.php");
	$connection = mysql_connect($dbhost, $dbusername, $dbpassword);
	$db = mysql_select_db($dbname);

    // EDIT THIS IF YOU MODIFIED THE SIGNUP PAGE OR
    // IF YOU ARE USING YOUR OWN SIGNUP FORM
    // We  use the addslashes() function on some variables to prevent SQL Injection
    $username = addslashes($_POST['username']);
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = addslashes($_POST['email']);
    $country = $_POST['country'];
    $zipcode = $_POST['zipcode'];

	// SIGNUP SETTINGS
	$qSetup = mysql_query("SELECT * FROM signupsetup");
	$SetupRow = mysql_fetch_array($qSetup);
	$ValidEmailDomains = $SetupRow['validemail'];
	$profile = $SetupRow['profile'];
	$defaultgroup = $SetupRow['defaultgroup'];
	$defaultlevel = $SetupRow['defaultlevel'];
	$AutoApprove = $SetupRow['autoapprove'];
	$AutoSend = $SetupRow['autosend'];
	$AutoSendAdmin = $SetupRow['autosendadmin'];
	
	// EMAILER SETTINGS
	$qEmailer = mysql_query("SELECT * FROM emailer WHERE profile='$profile'");
	$EmailerRow = mysql_fetch_array($qEmailer);
	$EmailerName = $EmailerRow["name"];
	$EmailerFrom = $EmailerRow["email"];
	$EmailerSubject = $EmailerRow["subject"];
	$EmailerMessage = $EmailerRow["emailmessage"];

	// SIGNUP FORM PROCESSING
	$EmailQuery = mysql_query("SELECT * FROM signup WHERE email='$email'");
	$email = strtolower($email);
	$EmailExist = mysql_num_rows($EmailQuery);	// Returns 0 if not yet existing
	$username = strtolower($username);
	$UsernameQuery = mysql_query ("SELECT * FROM signup WHERE uname='$username'");
	$UsernameExist = mysql_num_rows($UsernameQuery);
	
	if (trim($ValidEmailDomains)=="")
	{
		$EmailArray = "";
	}
	else
	{
		$EmailArray = split (" ", $ValidEmailDomains);
	}
	
	// Generate confirmation key for settings which require one
	$confirmkey =  md5(uniqid(rand())); 

	// CHECK FOR RESERVED USERNAMES
	if (trim($username)=='sa' || trim($username)=='admin' || trim($username)=='test')
	{
		$UsernameExist = 1;
	}
	
	// CHECK FOR REQUIRED FIELDS
	if (empty($username))
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\"><b>Username field cannot be left blank!</b></font></p>";
		exit;
	}
	if (empty($password))
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\"><b>Password field cannot be left blank!</b></font></p>";
		exit;
	}
	if (empty($fname))
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\"><b>First Name field cannot be left blank!</b></font></p>";
		exit;
	}
	if (empty($lname))
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\"><b>Last Name field cannot be left blank!</b></font></p>";
		exit;
	}
	if (empty($email))
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\"><b>Email field cannot be left blank!</b></font></p>";
		exit;
	}
	
	// Validate Email Address String
  	$good = ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
               '@'.
               '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
               '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',
               $email);	
	if (!$good)
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\"><b>Email field has invalid characters!</b></font></p>";
		exit;
	}
	
	// Validate Email Address String - FOR VALID EMAIL DOMAINS
	$found=false;
	if ($EmailArray!="")
	{
		for ($ct=0;$ct<=sizeof($EmailArray)-1;$ct++)
		{
			if (eregi($EmailArray[$ct], $email))
			{
				$ct=sizeof($EmailArray);
				$found=true;
			}
			else
			{
				$found=false;
			}
		}
	}
	else
	{
		$found = true;
	}
	if (!$found)
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\"><b>Email address does not belong to the list of allowable email domains!</b></font></p>";
		exit;
	}

	// Make sure username does not yet exist in the db
	if ($UsernameExist>0)
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\"><b>Username already exists in the database!</b></font></p>";
		exit;
	}
	
	// Make sure email address does not yet exist in the db
	if ($EmailExist>0)
	{
		print "<p><font size=\"3\" face=\"Verdana, Arial\" color=\"#FF0000\"><b>Email address already exists in the database!</b></font></p>";
		exit;
	}
	
	// *********************************************************
	// CHANGE THIS IF YOU WANT TO ADD FIELDS TO YOUR SIGNUP FORM
	// *********************************************************
	// Add new member to table signup
	$addmember = mysql_query("INSERT INTO signup VALUES ('','$username','$fname','$lname','$email','$country','$zipcode',NOW(),'$confirmkey')");
	
	// If SUCCESSFUL, add to vAuthenticate tables too
	if ($addmember)
	{
		// Is the member auto-approved or not?
		if ($AutoApprove==1)
		{
			$MemberStatus = "active";
		}
		else
		{
			$MemberStatus = "inactive";
		}

		$AddToAuth = new auth();
		$add = $AddToAuth->add_user($username,$password,$defaultgroup,$defaultlevel,$MemberStatus,'', 0);
	}
	
	// Do we automatically send email notification to member or not?
	if ($AutoSend == 1)
	{
		// if successful in adding to vAuthenticate, send confirmation email
		if ($add==1)
		{	
			// Replace all occurrences of the keys
			// AVAILABLE KEYS: [[UNAME]], [[LNAME]], [[FNAME]], [[PASSWD]], [[EMAIL]], [[CONFIRM]]			
			$EmailerMessage = str_replace("[[UNAME]]", $username, $EmailerMessage);
			$EmailerMessage = str_replace("[[PASSWD]]", $password, $EmailerMessage);
			$EmailerMessage = str_replace("[[FNAME]]", $fname, $EmailerMessage);
			$EmailerMessage = str_replace("[[LNAME]]", $lname, $EmailerMessage);
			$EmailerMessage = str_replace("[[EMAIL]]", $email, $EmailerMessage);
			$EmailerMessage = str_replace("[[CONFIRM]]", $confirm . '?confirmkey=' . $confirmkey, $EmailerMessage);
				
			$sent = @mail($email, $EmailerSubject, $EmailerMessage, "From:$EmailerName<$EmailerFrom>\nReply-to:$EmailerFrom"); 
			// $sent = @mail($email, $EmailerSubject, $EmailerMessage, "From:$EmailerName<$EmailerFrom>\nReply-to:$EmailerFrom\nContent-Type: text/html; charset=iso-8859-15"); 
		}
	}
	// echo $EmailerMessage;	// DEBUGGER

	// Do we automatically send notification message to the admin's email address (see signupconfig.php)?
	if ($AutoSendAdmin == 1)
	{
		if ($add==1)
		{
			$AdminSubject = "New Membership Application!";
			$AdminMessage = "This is to inform you that " . $username . " has applied for membership to our site.";			
			$sent = @mail($adminemail, $AdminSubject, $AdminMessage, "From:$EmailerName<$EmailerFrom>\nReply-to:$EmailerFrom"); 
		}
	}
?>

<p><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b>Thank 
  you for signing up!</b></font></p>

<?
	if ($AutoSend == 1) 
	{
		print "<p><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">";
		print "A confirmation email was sent to the email address you specified. <br>";
		print "Please confirm your membership as soon as you receive the email.";
  		print "</font></p>";
	}
	else
	{
		print "<p><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">";
		print "Please click <a href=\"$RelLogin\">here</a> to go back to the login area";
  		print "</font></p>";
	}
?>

</body>
</html>
