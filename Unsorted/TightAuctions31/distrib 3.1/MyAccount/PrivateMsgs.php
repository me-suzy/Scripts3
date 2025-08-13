<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );
   include( "../privatemsgs.inc" );
?>

<html>
<head>
<title>Private Messages</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
	ValidateLoginRedirect();
?>
</head>
<SCRIPT LANGUAGE="JavaScript">
<!-- 

function viewMsg( MsgID )
{
	var winNew = window.open("ViewPrivateMsg.php?MsgID=" + MsgID, "InboxMsg", "width=500,height=450, scrollbars=yes, resizable=yes");	
	winNew.focus();
}

function deleteInboxMsg( MsgID, Subject )
{
    if ( window.confirm( "Delete from Inbox: " + Subject ) )
    {
		document.Messages.action="PrivateMsgs.php?action=InboxDeleteMsg&MsgID=" + MsgID;
		document.Messages.submit();
	}
}

function deleteSentMsg( MsgID, Subject )
{
    if ( window.confirm( "Delete from Sent: " + Subject ) )
    {
		document.Messages.action="PrivateMsgs.php?action=SentDeleteMsg&MsgID=" + MsgID;
		document.Messages.submit();
	}
}

//-->
</SCRIPT>
<body bgcolor="#FFFFFF">
<?
	include( "../header.inc" );
?> 
<?
  if ( isset( $action ) )
  {
	$UserAcctID = GetSessionUserID();	

	if ( $action == "InboxDeleteMsg" )
	{	
		// Make sure that the message belongs to the current user and is in the "Inbox" folder
	
		$query = "SELECT AcctRefFlag FROM PrivateEmails WHERE EmailID=$MsgID AND ToAcctID=$UserAcctID";
		$result = mysql_query( $query, $link );		
	
		if ( !($row = mysql_fetch_row( $result )) )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The message does not exist in the Inbox folder.</font></p>\n";
			print( "$ErrorMsg" );			
		}
		else
		{		
			// Remove the reference for "To"

			$AcctRefFlag = $row[0] & (~$PRMSG_TO_REFERENCE);

			if ( $AcctRefFlag == 0 )
			{
				// No more references so delete the message

				$query = "DELETE FROM PrivateEmails WHERE EmailID=$MsgID";
				$result = mysql_query( $query, $link );		

				if ( !$result )
				{
					print("<h3><font color=\"#FF0033\">Error executing delete private message query.</font></h3></body></html>\n");    
					echo "<br>\n";
					echo mysql_error();		
					exit;
				}
			}
			else
			{
				// Still a reference so update the message with the new references

				$query = "UPDATE PrivateEmails SET AcctRefFlag=$AcctRefFlag WHERE EmailID=$MsgID";
				$result = mysql_query( $query, $link );		

				if ( !$result )
				{
					print("<h3><font color=\"#FF0033\">Error executing update private message query.</font></h3></body></html>\n");    
					echo "<br>\n";
					echo mysql_error();		
					exit;
				}
			}
		}
	}
	else if ( $action == "SentDeleteMsg" )
	{
		// Make sure that the message belongs to the current user and is in the "Sent" folder
	
		$query = "SELECT AcctRefFlag FROM PrivateEmails WHERE EmailID=$MsgID AND FromAcctID=$UserAcctID";
		$result = mysql_query( $query, $link );		
	
		if ( !($row = mysql_fetch_row( $result )) )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">The message does not exist in the Sent folder.</font></p>\n";
			print( "$ErrorMsg" );			
		}
		else
		{		
			// Remove the reference for "From"

			$AcctRefFlag = $row[0] & (~$PRMSG_FROM_REFERENCE);

			if ( $AcctRefFlag == 0 )
			{
				// No more references so delete the message

				$query = "DELETE FROM PrivateEmails WHERE EmailID=$MsgID";
				$result = mysql_query( $query, $link );		

				if ( !$result )
				{
					print("<h3><font color=\"#FF0033\">Error executing delete private message query.</font></h3></body></html>\n");    
					echo "<br>\n";
					echo mysql_error();		
					exit;
				}
			}
			else
			{
				// Still a reference so update the message with the new references

				$query = "UPDATE PrivateEmails SET AcctRefFlag=$AcctRefFlag WHERE EmailID=$MsgID";
				$result = mysql_query( $query, $link );		

				if ( !$result )
				{
					print("<h3><font color=\"#FF0033\">Error executing update private message query.</font></h3></body></html>\n");    
					echo "<br>\n";
					echo mysql_error();		
					exit;
				}
			}
		}
	}
  }
?> 

<form method="post" action="PrivateMsgs.php" name="Messages">
</form>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top"> 
    <td> 
      <blockquote> 
        <div align="right">
          <p><b><font face="Verdana, Arial, Helvetica, sans-serif"><a href="PrivateMsgs.php">Reload 
            Messages</a></font></b></p>
          </div>
        <font face="Verdana, Arial, Helvetica, sans-serif" size="3"><font size="2">Messages 
        sent to you by other users will have also been sent to the e-mail address 
        that was in your profile at the time the message was sent.</font></font> 
        <h3><font face="Arial, Helvetica, sans-serif" color="#3366cc">Inbox</font></h3>
        <table width="100%" border="1" cellspacing="0" cellpadding="4">
          <tr> 
            <td width="50" bgcolor="#3366cc">&nbsp;</td>
            <td width="200" bgcolor="#3366cc"> 
              <div align="center"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif" size="2"><b>Date </b></font></div>
            </td>
            <td width="*" bgcolor="#3366cc"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif" size="2"><b>Subject</b></font></td>
          </tr>
<?
		$UserAcctID = GetSessionUserID();	

		$query = "SELECT EmailID, TimeSent, Subject, AcctRefFlag FROM PrivateEmails WHERE ToAcctID=$UserAcctID ORDER BY TimeSent";
		$result = mysql_query( $query, $link );		
		
		while ( $row = mysql_fetch_row( $result ) )
		{
			if ( IsPrivateMsgToSet( $row[3] ) )
			{
				$TimeSent = date( "D, M j, Y  h:i A", $row[1] );

				$SubjectEscaped = htmlentities( $row[2] );
				if ( trim($SubjectEscaped == "" ) )
					$SubjectEscaped = "[No Subject]";

				$SubjectJS = ereg_replace( "'", "\'", $SubjectEscaped );

				print( "<tr>\n" );
				print( "  <td width=\"50\">\n" );
				print( "    <div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"javascript:deleteInboxMsg($row[0], '$SubjectJS');\">Delete</a></font></div>\n" );
				print( "  </td>\n" );
				print( "  <td width=\"200\">\n" );
				print( "    <font face=\"Arial, Helvetica, sans-serif\" size=\"2\">$TimeSent CST</font>\n" );
				print( "  </td>\n" );		
				print( "  <td width=\"*\">\n" );
				print( "    <font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"javascript:viewMsg($row[0]);\">$SubjectEscaped</a></font></div>\n" );
				print( "  </td>\n" );		
				print( "</tr>\n" );
			}
		}	
?>
        </table>
	</blockquote>
	<blockquote> 
        <p>&nbsp;</p>
        <h3><font face="Arial, Helvetica, sans-serif" color="#3366cc">Sent</font></h3>
        <table width="100%" border="1" cellspacing="0" cellpadding="4">
          <tr> 
            <td width="50" bgcolor="#3366cc">&nbsp;</td>
            <td width="200" bgcolor="#3366cc"> 
              <div align="center"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif" size="2"><b>Date</b></font></div>
            </td>
            <td width="*" bgcolor="#3366cc"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif" size="2"><b>Subject</b></font></td>
          </tr>
<?
		$query = "SELECT EmailID, TimeSent, Subject, AcctRefFlag FROM PrivateEmails WHERE FromAcctID=$UserAcctID ORDER BY TimeSent";
		$result = mysql_query( $query, $link );		
		
		while ( $row = mysql_fetch_row( $result ) )
		{
			if ( IsPrivateMsgFromSet( $row[3] ) )
			{
				$TimeSent = date( "D, M j, Y  h:i A", $row[1] );

				$SubjectEscaped = htmlentities( $row[2] );
				if ( trim($SubjectEscaped == "" ) )
					$SubjectEscaped = "[No Subject]";

				$SubjectJS = ereg_replace( "'", "\'", $SubjectEscaped );

				print( "<tr>\n" );
				print( "  <td width=\"50\">\n" );
				print( "    <div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"javascript:deleteSentMsg($row[0], '$SubjectJS');\">Delete</a></font></div>\n" );
				print( "  </td>\n" );
				print( "  <td width=\"200\">\n" );
				print( "    <font face=\"Arial, Helvetica, sans-serif\" size=\"2\">$TimeSent CST</font>\n" );
				print( "  </td>\n" );		
				print( "  <td width=\"*\">\n" );
				print( "    <font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"javascript:viewMsg($row[0]);\">$SubjectEscaped</a></font></div>\n" );
				print( "  </td>\n" );		
				print( "</tr>\n" );
			}
		}	
?>
        </table>
      <p>&nbsp;</p>
	</blockquote>
    </td>
  </tr>
</table>
<?
	include( "../footer.inc" );
?>
</body>
</html>
