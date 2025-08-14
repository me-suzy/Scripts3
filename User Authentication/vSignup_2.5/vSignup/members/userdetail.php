<?
/*
# File: members/userdetail.php
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
	include_once ("../auth.php");
	include_once ("../authconfig.php");
	include_once ("../check.php");
	
	$connection = mysql_connect($dbhost, $dbusername, $dbpass);
	$SelectedDB = mysql_select_db($dbname);
	
	/*
	if (isset($_GET['username']))
	{
		$username = $_GET['username'];
	}
	elseif (isset($_POST['username']))
	{
		$username = $_POST['username'];
	}

	else
	{
		// Feel free to change the error message below. Just make sure you put a "\" before
		// any double quote.
		print "<font face=\"Arial, Helvetica, sans-serif\" size=\"5\" color=\"#FF0000\">";
		print "<b>Illegal Method</b>";
		print "</font><br>";
  		print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"#000000\">";
		print "<b>You used an illegal method to access this page.</b></font>";
		
		exit; // End program execution. This will disable continuation of processing the rest of the page.
	}
	*/
	
	$username = $USERNAME;
?>
<?
	if (isset($_POST['Submit']))
	{
		//$username = $_POST['username'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$country = $_POST['country'];
		$zipcode = $_POST['zipcode'];
		$status = $_POST['status'];	// flag that says if user exists in the signup table or not
		
		// Error Checking to make sure that all required fields are filled out
		$action = 1;
		if ($fname == "")
		{
			$message = "First Name field cannot be left blank!";
			$action = 0;
		}
		elseif ($lname == "")
		{
			$message = "Last Name field cannot be left blank!";
			$action = 0;
		}
		elseif ($email == "")
		{
			$message = "Email field cannot be left blank!";
			$action = 0;
		}
		else
		{		
			// If user was added in control panel, we need to use INSERT to create a user record
			// in the signup table
			if ($status == 0 && $action != 0)
			{
				$qNewDetails = "INSERT INTO signup VALUES ('', '$username', '$fname', '$lname', '$email', 
									'$country', '$zipcode', '', 'ADDED VIA USER DETAIL PANEL')";
			}
			elseif ($status == 1 && $action != 0)
			{
				$qNewDetails = "UPDATE signup SET fname='$fname', lname='$lname', email='$email', 
									country='$country', zipcode='$zipcode' WHERE uname='$username'";
			}
			
			
			if (mysql_query($qNewDetails))
			{
				$message = "User details updated successfully!";
				$action = 1;
			}
			else
			{
				$message = "There was a problem udpating the user details.";
				$action = 0;
			}
		}
	}
	
	// Ger user details for data population below
	$qUserDetails = "SELECT * FROM signup where uname='$username'";
	$UserDetails = mysql_query($qUserDetails);
	if (mysql_num_rows($UserDetails))
	{
		$DetailsRow = mysql_fetch_array($UserDetails);	
		$fname = $DetailsRow['fname'];
		$lname = $DetailsRow['lname'];
		$email = $DetailsRow['email'];
		$country = $DetailsRow['country'];
		$zipcode = $DetailsRow['zipcode'];
		$status = 1; // means user exists in the signup table
	}
	else
	{
		$fname = "";
		$lname = "";
		$email = "";
		$country = "";
		$zipcode = "";
		$status = 0; // means user does not exist in the signup table
		$message = "";
	}
?>
<html>
<head>
<title>vSignup Sample User Login Results</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">

<p><font face="Arial, Helvetica, sans-serif" size="5"><b>vSignup Sample User Login Results</b></font>
</p>
<form name="vSignup" method="POST" action="userdetail.php">
  <table width="75%" border="1" cellspacing="0" cellpadding="0" align="left">
    <tr valign="middle"> 
      <td colspan="2" bgcolor="#FFFFCC"> 
        <div align="center"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">User Details </font></b></div>
    </td>
  </tr>
    <tr valign="middle"> 
      <td colspan="2"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">* 
        Fields in <font color="#990000">red</font> font are required to have a value</font></td>
  </tr>
    <tr valign="middle"> 
      <td width="20%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">Username</font></b></td>
      <td width="80%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<?php echo $username; ?>
        </font></b></td>
  </tr>
    <tr valign="middle">
      <td bgcolor="#CCCCCC"><b><font size="1">&nbsp;</font></b></td>
      <td><i><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;</font></i></td>
    </tr>
    <tr valign="middle"> 
      <td width="20%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#990000">First 
        Name</font></b></td>
      <td width="80%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="fname" size="30" maxlength="30" value="<?php echo $fname; ?>">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="20%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#990000">Last 
        Name</font></b></td>
      <td width="80%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="lname" size="20" maxlength="20" value="<?php echo $lname; ?>">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="20%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#990000">Email 
        Address</font></b></td>
      <td width="80%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="email" size="45" maxlength="45" value="<?php echo $email; ?>">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="20%" bgcolor="#CCCCCC"><b><font size="1">&nbsp;</font></b></td>
      <td width="80%"><i><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;</font></i></td>
  </tr>
    <tr valign="middle"> 
      <td width="20%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Country</font></b></td>
      <td width="80%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="country" size="20" maxlength="20" value="<?php echo $country; ?>">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="20%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Zip 
        Code</font></b></td>
      <td width="80%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="zipcode" size="20" maxlength="20" value="<?php echo $zipcode; ?>">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td colspan="2" bgcolor="#FFFFCC">
        <div align="center"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"></font></b><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
		  <!-- <input type="hidden" name="username" value="<?php echo $username; ?>"> -->				  
		  <input type="hidden" name="status" value="<?php echo $status; ?>">					  
		  <input type="submit" name="Submit" value="Save Changes">&nbsp;
          <input type="reset" name="Reset" value="Reset">
          </font></b></div>
      </td>
  </tr>
    <tr valign="middle">
      <td colspan="2" bgcolor="#FFFFCC">
	  <a href="index.php">
	  <font size="2" face="Verdana, Arial, Helvetica, sans-serif">&lt;&lt; Back</font></a></td>
    </tr>
</table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<table width="75%" border="1" cellspacing="0" cellpadding="0" align="left" bordercolor="#000000">
  <tr>
    <td bgcolor="#990000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#FFFFCC">Message:</font></b></td>
  </tr>
  <tr>
    <td><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#0000FF">
      <?
		  	if (isset($message)) 
			{
			 	print $message;
		  	}
			else 
			{
				print "<BR>&nbsp;";
			}
		  ?>
    </font></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
