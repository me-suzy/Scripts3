<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );
?>
<html>
<head>
<title>Update Account Profile</title>
<?
	ValidateLoginRedirect();
?>
</head>
<body bgcolor="#FFFFFF">
<?
	include( "../header.inc" );
?> 
<?
	if ( !isset($UpdateProfileSubmit) )
	{
		// Load the account profile values

		$MemberID = GetSessionMemberID();	

		$query = "SELECT Email, First, Middle, Last, City, State FROM UserAccounts WHERE MemberID='$MemberID'";
		$result = mysql_query( $query, $link );

		if ($row = mysql_fetch_row($result) )
		{
			$Email  = $row[0];
			$First  = $row[1];
			$Middle = $row[2];
			$Last   = $row[3];
			$City   = $row[4];
			$State  = $row[5];
		}		
		else
		{
			print( "<h2>Member not found.<h2></body></html>\n" );
			exit;
		}
	}
	else
	{
		$ErrorMsg = "";

		// Update the profile 

		if ( strstr($Email, "@") == "" )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">Please enter in a valid e-mail address.</font></p>\n";
		}

		if ($ErrorMsg == "")
		{
			$Email  = addslashes($Email);
			$First  = addslashes($First);
			$Middle = addslashes($Middle);
			$Last   = addslashes($Last);
			$City   = addslashes($City);
			$State  = addslashes($State);

			$MemberID = GetSessionMemberID();

			$IP = getenv("REMOTE_ADDR"); 

			$query = "UPDATE UserAccounts SET Email='$Email', First='$First', Middle='$Middle', Last='$Last', City='$City', State='$State', ModifiedIPAddress='$IP' WHERE MemberID='$MemberID'";
			$result = mysql_query( $query, $link );		

			if ( !$result )
			{
				print("<h3><font color=\"#FF0033\">Error executing update account information query.</font></h3></body></html>\n");    
				echo "<br>\n";
				echo mysql_error();		
				exit;
			}
			else
			{
$ConfirmMsg = <<<CONFIRMMSG
<blockquote>
<p>&nbsp;</p><h2>Profile Change Confirmation</h2>
</blockquote>
<blockquote>
  <p><font face="Arial, Helvetica, sans-serif" size="3" color="#006600">Your account profile has been updated.</font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="3"><a href="/">Click here to continue.</a></font></p>
</blockquote>
CONFIRMMSG;

				print( "$ConfirmMsg" );

				include( "../footer.inc" );
				print( "</body></html>\n" );
				exit;
			}
		}
		else
		{
			print( "$ErrorMsg" );
		}		
	}
?>
<form action="UpdateProfile.php" name="UpdateProfile" method="post">
  <input type="hidden" name="UpdateProfileSubmit" value="1">

  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="18%">&nbsp;</td>
      <td width="82%"><font face="Arial, Helvetica, sans-serif" size="2"><b><a href="ChangePassword.php">Click 
        here to change your password.</a></b></font></td>
    </tr>
    <tr>
      <td width="18%">&nbsp;</td>
      <td width="82%">&nbsp;</td>
    </tr>
    <tr> 
      <td width="18%"><b><font face="Arial, Helvetica, sans-serif">E-mail:</font></b></td>
      <td width="82%"> 
        <input type="text" name="Email" size="20" maxlength="50" <? print("value=\"$Email\"") ?> >
      </td>
    </tr>
    <tr> 
      <td width="18%">&nbsp;</td>
      <td width="82%">&nbsp;</td>
    </tr>
    <tr> 
      <td width="18%"><b><font face="Arial, Helvetica, sans-serif">Name:</font></b></td>
      <td width="82%"> 
        <table width="50%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="33%"><font face="Arial, Helvetica, sans-serif" size="2">First</font></td>
            <td width="33%"><font face="Arial, Helvetica, sans-serif" size="2">Middle</font></td>
            <td width="33%"><font face="Arial, Helvetica, sans-serif" size="2">Last</font></td>
          </tr>
          <tr> 
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input type="text" name="First" maxlength="20" size="10" <? print("value=\"$First\"") ?> >
              </font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input type="text" name="Middle" maxlength="20" size="10" <? print("value=\"$Middle\"") ?> >
              </font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input type="text" name="Last" maxlength="20" size="10" <? print("value=\"$Last\"") ?> >
              </font></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td width="18%">&nbsp;</td>
      <td width="82%">&nbsp;</td>
    </tr>
    <tr> 
      <td width="18%">&nbsp;</td>
      <td width="82%"><font face="Arial, Helvetica, sans-serif" size="2">City 
        and State information is used to display the region from where you are 
        located when you post an ad.</font></td>
    </tr>
    <tr> 
      <td width="18%"><b><font face="Arial, Helvetica, sans-serif">City:</font></b></td>
      <td width="82%"> 
        <input type="text" name="City" size="25" maxlength="50" <? print("value=\"$City\"") ?> >
      </td>
    </tr>
    <tr> 
      <td width="18%"><b><font face="Arial, Helvetica, sans-serif">State:</font></b></td>
      <td width="82%"> 
        <input type="text" name="State" size="2" maxlength="2" <? print("value=\"$State\"") ?> >
      </td>
    </tr>
    <tr> 
      <td width="18%">&nbsp;</td>
      <td width="82%">&nbsp;</td>
    </tr>
    <tr> 
      <td width="18%">&nbsp;</td>
      <td width="82%"><a href="javascript:document.UpdateProfile.submit()"><font face="Arial, Helvetica, sans-serif"><b><font size="2">Update 
        Account Profile</font></b></font></a></td>
    </tr>
  </table>
</form>
<?
	include( "../footer.inc" );
?>
</body>
</html>

