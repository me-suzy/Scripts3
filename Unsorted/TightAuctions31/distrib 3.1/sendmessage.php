<html>
<head>
<title>Message Sent Confirmation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF">
<br>
<?
  include( "privatemsgs.inc" );
  include( "config.php" );
  include( "dblink.inc" );

  $query = "SELECT Email FROM UserAccounts WHERE MemberID='$ToMemberID'";
  $result = mysql_query( $query, $link );		
  if ( $row = mysql_fetch_row( $result ) )
  {
    $ToEmail = $row[0];
  }

  if ( !isset($ToEmail) || ($ToEmail == "") || ( strstr($ToEmail, "@") == "" ) )
  {
	print("<p><font color=\"#FF0033\">The e-mail address was not set or is invalid when sending the message.</font></p>\n");
	print("</body></html>\n");
	exit;
  }

  $MessageSubject = "Message from $FromMemberID:  $Subject";

  $MessageBody = "<html><head><title>$MessageSubject</title></head><body>\n"; 
  $MessageBody .= "<p>\nYou have received a new message from member $FromMemberID!\n";
  $MessageBody .= "<p>\nPlease go to your <a href=\"http://$DomainName/MyAccount/\">My Account</a> section to view the e-mail.\n";
  $MessageBody .= "</body></html>";

  $Success = mail( $ToEmail, $MessageSubject, $MessageBody, "From: mailman@$EmailDomain\nContent-Type: text/html; charset=iso-8859-1\n" );

  if ( !$Success )
  {
	print("<p><font color=\"#FF0033\">An error occured while sending the e-mail.</font></p>\n");
	print("<p>Wait a while and then click on the following link to go back and try again. ");
    print("<a href=\"javascript:history.back(-1);\">Go back.</a></p>\n");
	print("<p>If the link does not work, then right click on an empty position on the page and select the back button from the context menu.</p>");
	print("</body></html>\n");
	exit;
  }
  else
  {
	// Get the account IDs for the From and To accounts.

    $FromMemberID = addslashes($FromMemberID);
	$ToMemberID   = addslashes($ToMemberID);

	$query = "SELECT UserAccountID FROM UserAccounts WHERE MemberID='$FromMemberID'";
	$result = mysql_query( $query, $link );		
	if ( $row = mysql_fetch_row( $result ) )
	{
		$FromAcctID = $row[0];
	}

	$query = "SELECT UserAccountID FROM UserAccounts WHERE MemberID='$ToMemberID'";
	$result = mysql_query( $query, $link );		
	if ( $row = mysql_fetch_row( $result ) )
	{
		$ToAcctID = $row[0];
	}

    // Insert the message into the e-mail messages table with references for both users

    $ToAndFromFlag = $PRMSG_TO_REFERENCE | $PRMSG_FROM_REFERENCE;

    $Subject = substr( $Subject, 0, 254 );	// Subject field will be truncated to 255 chars

    $Subject = addslashes($Subject);
	$Body    = addslashes($Body);

    $TimeNow = time();

	$IP = getenv("REMOTE_ADDR"); 

    $query  = "INSERT INTO PrivateEmails (FromAcctID, ToAcctID, Subject, Body, TimeSent, TimeDelivered, AcctRefFlag, IPAddress) ";
    $query .= "VALUES ($FromAcctID, $ToAcctID, '$Subject', '$Body', $TimeNow, $TimeNow, $ToAndFromFlag, '$IP')";
	$result = mysql_query( $query, $link );		
		
	if ( !$result )
	{
		print("<h3><font color=\"#FF0033\">Error executing insert e-mail query.</font></h3></body></html>\n");    
		echo mysql_error();
		exit;
	}
  }
?>
<blockquote>
<p>&nbsp;</p><h2>Message Sent Confirmation</h2>
</blockquote>
<blockquote>
  <p><font face="Arial, Helvetica, sans-serif" size="3" color="#006600"><b>Your message has been sent!</b></font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="3"><a href="javascript:window.close();">Close this window.</a></font></p>
</blockquote>
</body>
</html>
