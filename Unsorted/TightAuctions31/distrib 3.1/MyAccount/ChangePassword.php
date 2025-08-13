<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );
?>
<html>
<head>
<title>Change Password</title>
<?
	ValidateLoginRedirect();
?>
</head>
<body bgcolor="#FFFFFF">
<?
	include( "../header.inc" );
?> 

<?
	if ( isset($SubmitChangePassword) )
	{	
		$ErrorMsg = "";
	
		// Make sure that the new passwords are the same 	
	
		if ( strcmp($Password, $Password2) != 0 )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The passwords do not match.  Please enter the same password twice.</font></p>\n";
		} 
		else if ( !ereg("^([0-9,a-z,A-Z]*)?$", $Password) )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The <b>Password</b> can only contain letters and numbers.</font></p>\n";
		}
		else if ( (strlen($Password) < 4) || (strlen($Password) > 10) )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The <b>Password</b> must be between 4 to 10 characters long.</font></p>\n";
		} 		
	
		if ( $ErrorMsg != "" )
		{
			print( "$ErrorMsg" );
		}
		else
		{
			$MemberID = GetSessionMemberID();
	
			$CurrentPassword = addslashes($CurrentPassword);
			$Password = addslashes($Password);
	
			// Verify to make sure that the current password entered matches before updating
	
			$query = "SELECT * FROM UserAccounts WHERE MemberID='$MemberID' AND Password='$CurrentPassword'";
				
			$result = mysql_query( $query, $link );
			if ( mysql_numrows( $result ) == 0 )
			{
				print( "<p><font color=\"#FF0033\">The password entered does not match the current password.  Please enter the correct password.</font></p>\n" );
			}
			else
			{
				$IP = getenv("REMOTE_ADDR"); 

				$query = "UPDATE UserAccounts SET Password='$Password', ModifiedIPAddress='$IP' WHERE MemberID='$MemberID'";
				$result = mysql_query( $query, $link );
	
				if ( $result )
				{
	
$ConfirmMsg = <<<CONFIRMMSG
<blockquote>
<p>&nbsp;</p><h2>Password Change Confirmation</h2>
</blockquote>
<blockquote>
	<p><font face="Arial, Helvetica, sans-serif" size="3" color="#006600">Your password has been updated.</font></p>
	<p><font face="Arial, Helvetica, sans-serif" size="3"><a href="/">Click here to continue.</a></font></p>
</blockquote>
CONFIRMMSG;
	
					print( "$ConfirmMsg" );
	
					include( "../footer.inc" );
					print( "</body></html>\n" );
					exit;
				}
				else
				{
					print( "<p><font color=\"#FF0033\">Error updating password query.</font></p>\n" );
				}
			}		
		}		
	}
?> 

<form action="ChangePassword.php" name="ChangePassword" method="post">
  <input type="hidden" name="SubmitChangePassword" value="1">

  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="25%">&nbsp;</td>
      <td width="75%"><font face="Arial, Helvetica, sans-serif" size="2">Enter 
        in your current password and then your new password to change the password 
        for your account.</font></td>
    </tr>
    <tr> 
      <td width="18%"><b><font face="Arial, Helvetica, sans-serif">Current Password:</font></b></td>
      <td width="82%"> 
        <input type="password" name="CurrentPassword" size="10" maxlength="10">
      </td>
    </tr>
    <tr> 
      <td width="18%"><b><font face="Arial, Helvetica, sans-serif">New Password:</font></b></td>
      <td width="82%"> 
        <input type="password" name="Password" size="10" maxlength="10">
      </td>
    </tr>
    <tr> 
      <td width="18%"><b><font face="Arial, Helvetica, sans-serif">New Password 
        (again):</font></b></td>
      <td width="82%"> 
        <input type="password" name="Password2" size="10" maxlength="10">
      </td>
    </tr>
    <tr> 
      <td width="18%">&nbsp;</td>
      <td width="82%">&nbsp;</td>
    </tr>
    <tr> 
      <td width="18%">&nbsp;</td>
      <td width="82%"><a href="javascript:document.ChangePassword.submit()"><font face="Arial, Helvetica, sans-serif"><b><font size="2">Change 
        Password</font></b></font></a></td>
    </tr>
    <tr> 
      <td width="18%">&nbsp;</td>
      <td width="82%"><a href="javascript:document.ChangePassword.submit()"><font face="Arial, Helvetica, sans-serif"><b><font size="2">Cancel 
        and Go Back To Update Member Profile</font></b></font></a></td>
    </tr>
  </table>
</form>
<?
	include( "../footer.inc" );
?> 
</body>
</html>
