<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );
?>
<html>
<head>
<title>View Message</title>
<?
	ValidateLoginRedirect();
?>
<style type="text/css">
<!--
.inputbox
{
    BORDER-RIGHT: #FFCC00 1px solid;
    BORDER-TOP: #FFCC00 1px solid;
    BORDER-LEFT: #FFCC00 1px solid;
    BORDER-BOTTOM: #FFCC00 1px solid;
    FONT-WEIGHT: normal;
    FONT-SIZE: 14px;
    COLOR: #333333;
    BACKGROUND-COLOR: #FCE9B3
}

.button 
{
   font-family: arial;
   font-size:11px;
   font-weight: normal;
   color: 222222;
   background-color:#DDDDDD;
   border-color: CCCCCC;
   border-style: groove;
}
-->
</style>
</head>
<body bgcolor="#FFFFFF">
<?
	// Make sure that the requested message belongs to the current user

	$UserAcctID = GetSessionUserID();	
	
	$query = "SELECT ToAcctID, FromAcctID, Subject, Body, TimeSent FROM PrivateEmails WHERE EmailID=$MsgID AND (ToAcctID=$UserAcctID OR FromAcctID=$UserAcctID)";
	$result = mysql_query( $query, $link );		
	
	if ( !($row = mysql_fetch_row( $result )) )
	{
		$ErrorMsg = "<p><font color=\"#FF0033\">Invalid message.</font></p></body></html>\n";
		print( "$ErrorMsg" );			
		exit;		
	}
	else
	{			
		$ToAcctID   = $row[0];
		$FromAcctID = $row[1];
		$Subject    = $row[2];
		$Body       = $row[3];
		$Date       = date( "D, M j, Y  h:i A", $row[4] );		

		$query = "SELECT MemberID FROM UserAccounts WHERE UserAccountID=$ToAcctID";
		$result = mysql_query( $query, $link );		
	
		if ( $row = mysql_fetch_row( $result ) )
			$ToUser = $row[0];

		$query = "SELECT MemberID FROM UserAccounts WHERE UserAccountID=$FromAcctID";
		$result = mysql_query( $query, $link );		
	
		if ( $row = mysql_fetch_row( $result ) )
			$FromUser = $row[0];
	}
?>

<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr> 
    <td width="90"><font face="Arial, Helvetica, sans-serif"><b><font color="#3366cc">To:</font></b></font></td>
    <td width="*"><? echo $ToUser; ?></td>
  </tr>
  <tr> 
    <td><font face="Arial, Helvetica, sans-serif"><b><font color="#3366cc">From:</font></b></font></td>
    <td><? echo $FromUser; ?></td>
  </tr>
  <tr> 
    <td height="22"><font face="Arial, Helvetica, sans-serif"><b><font color="#3366cc">Date:</font></b></font></td>
    <td height="22"><? echo $Date; ?></td>
  </tr>
  <tr> 
    <td valign="top"><font face="Arial, Helvetica, sans-serif"><b><font color="#3366cc">Subject:</font></b></font></td>
    <td valign="top"><? echo $Subject; ?></td>
  </tr>
  <tr> 
    <td valign="top"><font face="Arial, Helvetica, sans-serif"><b><font color="#3366cc">Message:</font></b></font></td>
    <td valign="top"> 
      <textarea class="inputbox" name="textarea" cols="40" rows="15"><? echo $Body; ?></textarea>
    </td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top">&nbsp;</td>
    <td valign="top">
      <form method="post" action="../viewmember.php" name="Reply">
		<input type="hidden" name="ToMemberID" value=<? print("\"$FromUser\""); ?> >
		<input type="hidden" name="Subject" value=<? echo "\""; echo htmlentities("$Subject"); echo "\""; ?> >
		<input type="hidden" name="Body" value=<? echo "\""; echo htmlentities("$Body"); echo "\""; ?> >
      </form>
      <a href="javascript:document.Reply.submit()"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Reply To Sender</b></font></a>
    </td>
  </tr>
</table>
</body>
</html>
