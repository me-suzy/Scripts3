<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$l = "" ;
	// try to get cookie value first
	if ( isset( $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_SITE'] ) ) { $l = $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_SITE'] ; }
	if ( isset( $HTTP_GET_VARS['l'] ) ) { $l = $HTTP_GET_VARS['l'] ; }
	if ( isset( $HTTP_POST_VARS['l'] ) ) { $l = $HTTP_POST_VARS['l'] ; }

	if ( !file_exists( "./web/conf-init.php" ) )
	{
		HEADER( "location: setup/index.php" ) ;
		exit ;
	}
	include_once("./web/conf-init.php") ;
	if ( file_exists( "web/$l/$l-conf-init.php" ) && $l )
		include_once("./web/$l/$l-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./lang_packs/$LANG_PACK.php") ;
	include_once("./API/sql.php" ) ;
	include_once("./API/Users/get.php") ;
	include_once("./API/Users/update.php") ;
	include_once("./API/Chat/remove.php") ;
	include_once("./API/ASP/get.php") ;
?>
<?
	// initialize
	$action = $error = $sid = $site = $remember = "" ;
	$sound_file = "cellular.wav" ;
	$isadmin = $winapp = $autologin = 0 ;

	if ( !isset( $HTTP_SESSION_VARS['session_admin'] ) )
	{
		session_register( "session_admin" ) ;
		$session_admin = ARRAY() ;
		$HTTP_SESSION_VARS['session_admin'] = ARRAY() ;
	}

	// check to see if the site login is passes.  if not, then let's see how many
	// sites are in the asp model.  if only ONE, then default to that one.
	$total_sites = AdminASP_get_TotalUsers( $dbh ) ;
	if ( $total_sites == 1 )
	{
		$site = AdminASP_get_AllUsers( $dbh, 0, 1 ) ;
		$l = $site[0]['login'] ;
	}

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_POST_VARS['winapp'] ) ) { $winapp = $HTTP_POST_VARS['winapp'] ; }
	if ( isset( $HTTP_GET_VARS['winapp'] ) ) { $winapp = $HTTP_GET_VARS['winapp'] ; }
?>
<?
	// functions
?>
<?
	// conditions

	if ( ( isset( $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_LOGIN'] ) && isset( $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_PASSWORD'] ) && isset( $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_SITE'] ) ) && !$action )
		$autologin = 1 ;

	if ( $action == "login" )
	{
		if ( $l )
			$site = $l ;
		else
			$site = $HTTP_POST_VARS['site'] ;

		$aspinfo = AdminASP_get_ASPInfoByASPLogin( $dbh, $site ) ;
		$admin = AdminUsers_get_UserInfoByLoginPass( $dbh, $HTTP_POST_VARS['login'], $HTTP_POST_VARS['password'], $aspinfo['aspID'] ) ;
		if ( $admin['userID'] && ( $admin['aspID'] == $aspinfo['aspID'] ) )
		{
			// set $sid.  $sid is used to keep track of this admin user.  $sid allows
			// so a user can log into several admin departments on same computer.  it is
			// passed everywhere the admin goes.
			$sid = time() ;

			$HTTP_SESSION_VARS['session_admin'][$sid] = ARRAY() ;

			$HTTP_SESSION_VARS['session_admin'][$sid]['screen_name'] = $HTTP_POST_VARS['login'] ;
			$HTTP_SESSION_VARS['session_admin'][$sid]['admin_id'] = $admin['userID'] ;
			$HTTP_SESSION_VARS['session_admin'][$sid]['requests'] = 0 ;
			$HTTP_SESSION_VARS['session_admin'][$sid]['aspID'] = $aspinfo['aspID'] ;
			$HTTP_SESSION_VARS['session_admin'][$sid]['asp_login'] = $aspinfo['login'] ;
			$HTTP_SESSION_VARS['session_admin'][$sid]['active_footprints'] = 0 ;
			$HTTP_SESSION_VARS['session_admin'][$sid]['winapp'] = "$winapp" ;
			$HTTP_SESSION_VARS['session_admin'][$sid]['close_timer'] = 0 ;
			$HTTP_SESSION_VARS['session_admin'][$sid]['traffic_monitor'] = 0 ;
			$HTTP_SESSION_VARS['session_admin'][$sid]['available_status'] = 1 ;
			$isadmin = 1 ;

			// check to see if they want to be remembered... if so, just set cookie.
			// let's set it for 1 month for now.
			$cookie_lifespan = time() + 60*60*24*30 ;
			if ( isset( $HTTP_POST_VARS['remember'] ) )
			{
				setcookie( "COOKIE_PHPLIVE_LOGIN", $HTTP_POST_VARS['login'], $cookie_lifespan ) ;
				setcookie( "COOKIE_PHPLIVE_PASSWORD", $HTTP_POST_VARS['password'], $cookie_lifespan ) ;
				setcookie( "COOKIE_PHPLIVE_SITE", $aspinfo['login'], $cookie_lifespan ) ;
			}
		}
		else
		{
			// reset cookie if cookies are set
			if ( isset( $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_LOGIN'] ) && isset( $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_PASSWORD'] ) )
			{
				setcookie( "COOKIE_PHPLIVE_LOGIN", "", -1 ) ;
				setcookie( "COOKIE_PHPLIVE_PASSWORD", "", -1 ) ;
				setcookie( "COOKIE_PHPLIVE_SITE", "", -1 ) ;
			}
			$error = "Login failed!  NOTE: password is (CaSE senSiTiVE)." ;
		}
	}
	else if ( $action == "logout" )
	{
		if ( isset( $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_LOGIN'] ) && isset( $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_PASSWORD'] ) )
		{
			setcookie( "COOKIE_PHPLIVE_LOGIN", "", -1 ) ;
			setcookie( "COOKIE_PHPLIVE_PASSWORD", "", -1 ) ;
			setcookie( "COOKIE_PHPLIVE_SITE", "", -1 ) ;
		}
		$sid = $HTTP_GET_VARS['sid'] ;
		HEADER( "location: index.php?l=".$HTTP_SESSION_VARS['session_admin'][$sid]['asp_login']."&winapp=$winapp" ) ;
		exit ;
	}
	else
	{
		// do the cleaning of the chat database of old requests and sessions.
		ServiceChat_remove_CleanChatSessionList( $dbh ) ;
		ServiceChat_remove_CleanChatSessions( $dbh ) ;
		ServiceChat_remove_CleanChatRequests( $dbh ) ;
	}
?>
<html>
<head>
<title> Support Admin </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="./css/base.css">

<script language="JavaScript">
<!--
	if ( <? echo $isadmin ?> )
		setTimeout("location.href='<? echo $BASE_URL ?>/admin/index.php?sid=<? echo $sid ?>&start=1&winapp=<? echo $winapp ?>'",4000) ;
//-->
</script>

</head>
<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A">
<center>
<br>
<?
	if ( isset( $LOGO ) && $LOGO )
	{
		if ( file_exists( "web/$l/$LOGO" ) )
			$logo = "web/$l/$LOGO" ;
	}
	else if ( isset( $LOGO_ASP ) && $LOGO_ASP )
	{
		if ( file_exists( "web/$LOGO_ASP" ) )
			$logo = "web/$LOGO_ASP" ;
		else
			$logo = "pics/phplive_logo.gif" ;
	}
	else
		$logo = "pics/phplive_logo.gif" ;
?>
<img src="<? echo $logo ?>">
<br>
<span class="basetxt">

<? if ( $isadmin == 1 ): ?>
<EMBED src="sounds/<? echo $sound_file ?>" width=0 height=0 autostart=true loop=false>
<table cellspacing=0 cellpadding=0 border=0 bgColor="#8080C0" width="450">
<tr>
	<td colspan=4><img src="pics/r_top.gif" width="450" height="10" border=0 alt=""></td>
</tr>
<tr>
	<td colspan=4 align="center"><span class="basetxt"><font color="#FFFFFF">
	<big><b>Login success! Accessing your account . . . </b></big>
	</td>
</tr>
<tr>
	<td colspan=4><img src="pics/r_bottom.gif" width="450" height="10" border=0 alt=""></td>
</tr>
</table>



<? elseif ( $autologin ): ?>
<form method="POST" action="index.php" name="form">
<input type="hidden" name="action" value="login">
<input type="hidden" name="winapp" value="<? echo $winapp ?>">
<input type="hidden" name="l" value="<? echo $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_SITE'] ?>">
<input type="hidden" name="login" value="<? echo $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_LOGIN'] ?>">
<input type="hidden" name="password" value="<? echo $HTTP_COOKIE_VARS['COOKIE_PHPLIVE_PASSWORD'] ?>">
</form>
<script language="JavaScript">
<!--
	document.form.submit()
//-->
</script>





<? elseif ( $l ): ?>
<font color="#FF0000"><? echo $error ?></font>
<form method="POST" action="index.php" name="form">
<input type="hidden" name="action" value="login">
<input type="hidden" name="winapp" value="<? echo $winapp ?>">
<input type="hidden" name="l" value="<? echo $l ?>">
<table cellspacing=0 cellpadding=0 border=0 bgColor="#8080C0" width="450">
<tr>
	<td colspan=4><img src="pics/r_top.gif" width="450" height="10" border=0 alt=""></td>
</tr>
<tr>
	<td><font color="#FFFFFF"><span class="basetxt">Operator Login</td>
	<td><span class="basetxt"><font size=2><input type="text" name="login" size="10" maxlength="15" class="input"></td>
	<td><font color="#FFFFFF"><span class="basetxt">Password</td>
	<td><span class="basetxt"><font size=2><input type="password" name="password" size="10" maxlength="15" class="input"></td>
</tr>
<tr>
	<td colspan=4><font size=1>&nbsp;</td>
</tr>
<tr>
	<td colspan=4 align="center"><span class="basetxt"><font color="#FFFFFF">
		<input type="checkbox" name="remember" value=1 class="checkbox"> Remember my ID on this computer. &nbsp;
		<input type="image" src="pics/buttons/submit.gif" border=0 alt="Login">
	</td>
</tr>
<tr>
	<td colspan=4><img src="pics/r_bottom.gif" width="450" height="10" border=0 alt=""></td>
</tr>
</table>
</form>



<? else: ?>
Error: Please make sure you put in the correct URL path.<br>
(example: <? echo $BASE_URL ?>/web/&lt;sitelogin&gt;)




<? endif ; ?>

<p>
<span class="smalltxt"><? echo $LANG['DEFAULT_BRANDING'] ?> &copy; OSI Codes Inc.</small>
</center>

<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>
