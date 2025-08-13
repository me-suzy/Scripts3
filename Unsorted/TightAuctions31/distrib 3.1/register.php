<?
  include( "usersession.inc" );
  include( "config.php" );
  include( "dblink.inc" );

  $RegisterSucceeded = false;
  $ErrorMsg = "";

  if ( isset($MemberID) || isset($Password) || isset($Password2) || isset($Email) || 
	   isset($First) || isset($Middle) || isset($Last) ||
	   isset($City) || isset($State) )
  {
	$MemberID = trim($MemberID);
	$Password = trim($Password);
	$Password2 = trim($Password2);
	$Email = trim($Email);
	$First = trim($First);
	$Middle = trim($Middle);
	$Last = trim($Last);
	$City = trim($City);
	$State = trim($State);

	if ( ($MemberID=="") || ($Password=="") || ($Password2=="") || ($Email=="") || 
		 ($First=="") || ($Last=="") ||		// Middle is not validated
		 ($City=="") || ($State=="") )
	{
		$ErrorMsg = "<p><font color=\"#FF0033\">All fields must be filled in to complete registration.</font></p>\n";
	}
    else
    {
		// Validate lengths

		if ( (strlen($MemberID) < 4) || (strlen($MemberID) > 10) )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The <b>Member ID</b> must be between 4 to 10 characters long.</font></p>\n";
		}
		else if ( !ereg("^([0-9,a-z,A-Z]*)?$", $MemberID) )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The <b>Member ID</b> can only contain letters and numbers.</font></p>\n";
		}
		else if ( !ereg("^([0-9,a-z,A-Z]*)?$", $Password) )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The <b>Password</b> can only contain letters and numbers.</font></p>\n";
		}
		else if ( (strlen($Password) < 4) || (strlen($Password) > 10) )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The <b>Password</b> must be between 4 to 10 characters long.</font></p>\n";
		} 
		else if ( strcmp($Password, $Password2) != 0 )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The passwords do not match.  Please enter the same password twice.</font></p>\n";
		} 
		else if ( strstr($Email, "@") == "" )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">Please enter in a valid e-mail address.</font></p>\n";
		}
		else if (strlen($Email) > 50) 
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The e-mail address cannot be more than 50 characters long.</font></p>\n";
		}
		else if ( strlen($First) > 20 )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The first name cannot be longer than 20 characters.</font></p>\n";
		}
		else if ( strlen($Middle) > 20 )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The middle name cannot be longer than 20 characters.</font></p>\n";
		}
		else if ( strlen($Last) > 20 )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The last name cannot be longer than 20 characters.</font></p>\n";
		}
		else if (strlen($City) > 40)
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The city cannot be more than 40 characters long.</font></p>\n";
		}
		else if (strlen($State) > 2)
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The state cannot be more than 2 characters long.</font></p>\n";
		}
		else
		{		
			$MemberID = addslashes($MemberID);
			$Password = addslashes($Password);
			$Email = addslashes($Email);
			$First = addslashes($First);
			$Middle = addslashes($Middle);
			$Last = addslashes($Last);
			$City = addslashes($City);
			$State = addslashes($State);
		
			// Check to see if a user with that MemberID already exists
	
			$query = "SELECT * FROM UserAccounts WHERE MemberID='$MemberID'";
			$result = mysql_query( $query, $link );		
	
			if ( mysql_numrows( $result ) != 0 )
			{
				$ErrorMsg = "<p><font color=\"#FF0033\">The <b>Member ID</b> is already in use.  Please select another Member ID.</font></p>\n";
			}
			else
			{
        			$TimeNow = time();

				$IP = getenv("REMOTE_ADDR"); 

				$query = "INSERT INTO UserAccounts (MemberID, Password, Email, First, Middle, Last, City, State, DateRegistered, CreatedIPAddress, ModifiedIPAddress) ";
				$query .= "VALUES('$MemberID', '$Password', '$Email', '$First', '$Middle', '$Last', '$City', '$State', $TimeNow, '$IP', '$IP')";

				mysql_query( $query, $link );

			    $RegisterSucceeded = true;
			}
		}
	}
  }
?>



<html>
<head>
<title>Register</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.inputbox
{
    BORDER-RIGHT: #FFCC00 1px solid;
    BORDER-TOP: #FFCC00 1px solid;
    BORDER-LEFT: #FFCC00 1px solid;
    BORDER-BOTTOM: #FFCC00 1px solid;
    FONT-WEIGHT: normal;
    FONT-SIZE: 12px;
    COLOR: #333333;
    BACKGROUND-COLOR: #FCE9B3
}
-->
</style>
</head>

<?
	if ($RegisterSucceeded)
	{
		print("<BODY onload=\"document.RegisterConfirm.submit();\" bgcolor=\"#FFFFFF\">\n");
	}
	else
	{
		print("<body bgcolor=\"#FFFFFF\" onload=\"document.Register.MemberID.focus();\" >\n");
	}
?>

<form action="registerconfirm.php" method="post" name="RegisterConfirm">
	<input type="hidden" name="MemberID" value="<? echo htmlentities($MemberID); ?>">
</form>

<?
	include( "header.inc" );
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="12%" bgcolor="#FCE9B3">&nbsp;</td>
          <td width="88%"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="30">&nbsp;</td>
                <td width="*"><font face="Arial, Helvetica, sans-serif" size="3"><b><font size="4" color="#003399">Member 
                  Registration -- Join us it's free!</font></b></font></td>
              </tr>
              <tr>
                <td width="30">&nbsp;</td>
                <td width="*">&nbsp;</td>
              </tr>
              <tr> 
                <td width="30">&nbsp;</td>
                <td width="*"> 
<?
  if ($ErrorMsg)
	print("$ErrorMsg\n");

  if (!$RegisterSucceeded)
  {
	include("register.inc");
  }
?> </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?
	include( "footer.inc" );
?>
</body>
</html>
