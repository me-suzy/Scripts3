<?
	include("usersession.inc");

	if ( isset($MemberID) )
	{
		if ($SavePwd == "save")
			NewLogin($MemberID, true);
		else
			NewLogin($MemberID, true);
	}
?>

<html>
<head>
<title>Registration Confirmation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<?
	include( "header.inc" );
?>
<blockquote>
<p>&nbsp;</p><h2>Registration Confirmation</h2>
</blockquote>
<blockquote>
  <p><font face="Arial, Helvetica, sans-serif" size="3" color="#006600"><b>Welcome <? print("$MemberID") ?>. Your registration is now complete! 
    </b> </font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="3"><a href="/MyAccount/MyAccount.php">Click 
    here to go to your account information.</a></font></p>
</blockquote>
<?
	include( "footer.inc" );
?>
</body>
</html>
