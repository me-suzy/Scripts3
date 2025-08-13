<?
    include( "config.php" );
	include( "usersession.inc" );		
	include( "dblink.inc" );

	$LoggedIn = IsLoggedIn();

	if ( $LoggedIn )
		$MemberID = GetSessionMemberID();

	$ToMemberID = addslashes($ToMemberID);
	$MemberFeedback = "( <a href=\"ViewFeedback.php?MemberID=$ToMemberID\" target=\"_blank\">View Feedback</a> )";

	$query = "SELECT First, Middle, Last, DateRegistered FROM UserAccounts WHERE MemberID='$ToMemberID'";
	$result = mysql_query( $query, $link );		

	if ( $row = mysql_fetch_row( $result ) )
	{
		$First  = $row[0];
		$Middle = $row[1];
		$Last   = $row[2];
		$DateRegistered = date( "F j, Y", $row[3] );			
	}

	if ( $Subject != "" )
	{
		$Subject = "Re: ".$Subject;
	}

	if ( $Body != "" )
	{
		$Body = "\n\n\n--- Original Message ---\n".$Body;
	}
?>

<html>
<head>
<title>Member Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
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

.button {
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

<body bgcolor="#FFFFFF" <? if ($LoggedIn) print( "onload=\"document.SendMessage.Subject.focus();\"" ); ?>>

<h2><font face="Arial, Helvetica, sans-serif" color="#3366cc">Member Details</font></h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="130"><font face="Arial, Helvetica, sans-serif"><b><font color="#3366cc">Member:</font></b></font></td>
    <td width="*"><? print( "$ToMemberID $MemberFeedback"); ?></td>
  </tr>
  <tr> 
    <td><b><font face="Arial, Helvetica, sans-serif" color="#3366cc">Name: </font></b></td>
    <td><? print( "$First $Middle $Last" ); ?></td>
  </tr>
  <tr>
    <td><b><font face="Arial, Helvetica, sans-serif" color="#3366cc">Member Since:</font></b></td>
    <td><? print( "$DateRegistered" ); ?></td>
  </tr>
</table>
<hr noshade>

<?
$LoginNotice = <<<LOGINNOTICE
<font face="Arial, Helvetica, sans-serif" size="2">
  <p align="center"><a href="signin.php" target="_blank">To send a message to this member, you must be logged in.</a></p>
LOGINNOTICE;

	if ( !$LoggedIn )
	{
		print( "$LoginNotice <p align=\"center\"><a href=\"viewmember.php?ToMemberID=$ToMemberID\">Click here to reload this page again after logging in.</a></p></font></body></html>\n" );
		exit;
	}
?>

<h4><font face="Arial, Helvetica, sans-serif" color="#3366cc">Send A Private Message To This Member</font></h4>
<form method="post" action="sendmessage.php" name="SendMessage">
  <input type="hidden" name="FromMemberID" value=<? print( "\"$MemberID\"" ); ?> >
  <input type="hidden" name="ToMemberID" value=<? print( "\"$ToMemberID\"" ); ?> >
  <table width="100%" border="0" cellspacing="2" cellpadding="0">
    <tr> 
      <td width="90"><font face="Arial, Helvetica, sans-serif"><b><font color="#3366cc">To:</font></b></font></td>
      <td width="*"><? echo $ToMemberID; ?></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif"><b><font color="#3366cc">From:</font></b></font></td>
      <td><? echo $MemberID; ?></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif"><b><font color="#3366cc">Subject:</font></b></font></td>
      <td> 
        <input class="inputbox" type="text" name="Subject" size="40" maxlength="255" value=<? print( "\"$Subject\"" ); ?>>
      </td>
    </tr>
    <tr> 
      <td valign="top"><font face="Arial, Helvetica, sans-serif"><b><font color="#3366cc">Message:</font></b></font></td>
      <td valign="top"> 
        <textarea class="inputbox" name="Body" cols="40" rows="15"><? print( "$Body" ); ?></textarea>
      </td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
      <td valign="top"> 
        <input class="button" type="submit" name="Submit" value="Send Message">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
