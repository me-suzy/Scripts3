<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.9
// Supplied by          : CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
require("adminglobal.php");

if ($firstlogin == 1) {
	$auth = adminauthenticate($IN_USER, $IN_PW);
	if ($auth == invalid) {
	    session_unset();
	    session_destroy();
	    header("location:login.php?invalid=yes");
		exit;
	}
}

if(!(session_is_registered("IN_USER"))) {
	header("location:login.php");
	exit;
} else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>SunShop Administration</title>
	<!-- frames -->
	<frameset  cols="170,*">
	    <frame name="menu" src="menu.php" marginwidth="3" marginheight="3" scrolling="auto" frameborder="0" noresize>
	    <frame name="main" src="admin.php" marginwidth="3" marginheight="3" scrolling="auto" frameborder="0" noresize>
	</frameset>
</head>
<body>
Your browser does not support frames! Please download a frame capable browser such as Microsoft Internet Explorer.
</body>
</html>
<?PHP
}
?>
