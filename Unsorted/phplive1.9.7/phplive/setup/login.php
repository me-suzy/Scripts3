<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	if ( isset( $HTTP_SESSION_VARS['session_setup'] ) ) { $session_setup = $HTTP_SESSION_VARS['session_setup'] ; }
	include_once("../web/conf-init.php") ;
	include_once("../lang_packs/$LANG_PACK.php") ;
	include_once("../system.php") ;
	include_once("../API/sql.php") ;
	include_once("../API/ASP/get.php") ;
?>
<?
	// initialize
	$action = $error = "" ;
	if ( !isset( $HTTP_SESSION_VARS['session_setup'] ) )
	{
		session_register( "session_setup" ) ;
		$session_setup = ARRAY() ;
		$HTTP_SESSION_VARS['session_setup'] = ARRAY() ;
	}
	if ( !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: index.php" ) ;
		exit ;
	}

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
?>
<?
	// functions
?>
<?
	// conditions
	if ( $action == "login" )
	{
		$login = $password = "" ;
		if ( isset( $HTTP_POST_VARS['login'] ) ) { $login = $HTTP_POST_VARS['login'] ; }
		if ( isset( $HTTP_GET_VARS['login'] ) ) { $login = $HTTP_GET_VARS['login'] ; }
		if ( isset( $HTTP_POST_VARS['password'] ) ) { $password = $HTTP_POST_VARS['password'] ; }
		if ( isset( $HTTP_GET_VARS['password'] ) ) { $password = $HTTP_GET_VARS['password'] ; }

		$admin = AdminASP_get_UserInfoByLoginPass( $dbh, $login, $password ) ;
		if ( $admin['aspID'] )
		{
			if ( $admin['active_status'] )
			{
				$HTTP_SESSION_VARS['session_setup'] = $admin ;
				HEADER( "location: options.php" ) ;
				exit ;
			}
			else
				$error = "Account is inactive." ;
		}
		else
			$error = "Invalid login or password." ;
	}
	else if ( $action == "logout" )
	{
		session_unregister( "session_setup" ) ;
	}
?>
<html>
<head>
<title> Site Admin Login </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	
//-->
</script>
<link rel="Stylesheet" href="../css/base.css">
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A">
<table cellspacing=0 cellpadding=0 border=0 width="98%">
<tr>
	<td valign="top"><span class="basetxt">
		<img src="<?= ( file_exists( "../web/$LOGO_ASP" ) && $LOGO_ASP ) ? "../web/$LOGO_ASP" : "../pics/phplive_logo.gif" ?>">
		<p>

		<b><big>Site Setup Area Login</big></b>
		<br>
		<font color="#FF0000"><? echo $error ?></font>
		<br>
		To setup departments, users, customize your support environment and other site admin tasks, please login below using your site admin login and password.
		<br>
		<br>
		<form method="POST" action="login.php" name="form">
		<input type="hidden" name="action" value="login">
		<table cellspacing=0 cellpadding=4 border=0>
		<tr>
			<td><span class="basetxt">Site Login</td>
			<td><span class="basetxt"><font size=2><input type="text" name="login" size="10" maxlength="15" class="input"></td>
			<td><span class="basetxt">Password <span class="smalltxt">(case sensitive)</small></td>
			<td><span class="basetxt"><font size=2><input type="password" name="password" size="10" maxlength="15" class="input"></td>
			<td><input type="image" src="../pics/buttons/submit.gif" border=0 alt="Login"></a></td>
		</tr>
		</table>
		</form>

	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>