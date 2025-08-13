<?
   include( "../config.php" );
   include( "../usersession.inc" );
   Logout();
?>
<html>
<head>
<title>Logout</title>
</head>
<body bgcolor="#FFFFFF">
<?
	include( "../header.inc" );
?>

<blockquote>
<p>&nbsp;</p><h2>Logout Confirmation</h2>
</blockquote>
<blockquote>
	<p><font face="Arial, Helvetica, sans-serif" size="3" color="#006600">You are now logged out.</font></p>
	
  <p><font face="Arial, Helvetica, sans-serif" size="3"><a href="../signin.php">Click 
    here to sign-in again.</a></font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="3"><a href="/">Click here 
    to continue.</a></font></p>
</blockquote>

<?
	include( "../footer.inc" );
?> 
</body>
</html>
