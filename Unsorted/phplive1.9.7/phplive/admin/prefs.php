<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$sid = $action = "" ;
	$updated = 0 ;
	if ( !isset( $HTTP_SESSION_VARS['session_admin'] ) )
	{
		HEADER( "location: ../index.php" ) ;
		exit ;
	}
	$session_admin = $HTTP_SESSION_VARS['session_admin'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : $HTTP_POST_VARS['sid'] ;

	if ( !isset( $session_admin[$sid]['asp_login'] ) || !file_exists( "../web/".$session_admin[$sid]['asp_login']."/".$session_admin[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: ../index.php" ) ;
		exit ;
	}
	include_once("../web/conf-init.php");
	include_once("../web/".$session_admin[$sid]['asp_login']."/".$session_admin[$sid]['asp_login']."-conf-init.php") ;
	include_once("$DOCUMENT_ROOT/system.php") ;
	include_once("$DOCUMENT_ROOT/lang_packs/$LANG_PACK.php") ;
	include_once("$DOCUMENT_ROOT/API/sql.php" ) ;
	include_once("$DOCUMENT_ROOT/API/Users/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Users/update.php") ;
?>
<?
	// initialize
	if ( !$session_admin )
	{
		HEADER( "location: ../index.php" ) ;
		exit ;
	}

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
	{
		$text_width = "20" ;
		$textbox_width = "80" ;
	}
	else
	{
		$text_width = "10" ;
		$textbox_width = "40" ;
	}

	// check to make sure session is set.  if not, user is not authenticated.
	// send them back to login
	if ( !$session_admin[$sid]['admin_id'] )
	{
		HEADER( "location: ../" ) ;
		exit ;
	}
	$now = time() ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }
?>
<?
	// functions
?>
<?
	// conditions
	
	if ( $action == "update" )
	{
		AdminUsers_update_UserValue( $dbh, $session_admin[$sid]['admin_id'], "console_close_min", $HTTP_POST_VARS['console_close_min'] ) ;
		$updated = 1 ;
	}

	$admin = AdminUsers_get_UserInfo( $dbh, $session_admin[$sid]['admin_id'], $session_admin[$sid]['aspID'] ) ;
?>
<html>
<head>
<title> Support Admin Preferences </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="../css/base.css">
<script language="JavaScript" src="../js/global.js"></script>

<script language="JavaScript">
<!--
	function do_alert()
	{
		<?
			if ( $updated )
				print "		alert( \"Success!\" ) ;\n" ;
		?>
	}

	function do_submit()
	{
		document.form.submit() ;
	}

	function check_console_value( minutes )
	{
		if ( minutes == "" )
			document.form.console_close_min.value = 0 ;
	}
//-->
</script>

</head>
<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" OnLoad="do_alert()">
<center>
<br>
<?
	if ( file_exists( "../web/".$session_admin[$sid]['asp_login']."/$LOGO" ) && $LOGO )
		$logo = "../web/".$session_admin[$sid]['asp_login']."/$LOGO" ;
	else if ( file_exists( "../web/$LOGO_ASP" ) && $LOGO_ASP )
		$logo = "../web/$LOGO_ASP" ;
	else
		$logo = "../pics/phplive_logo.gif" ;
?>
<img src="<? echo $logo ?>">
<br><span class="basetxt">
<? include_once( "./header.php" ); ?>
<br>

<form method="POST" action="prefs.php" name="form">
<input type="hidden" name="action" value="update">
<input type="hidden" name="sid" value="<? echo $sid ?>">
<input type="hidden" name="deptid" value="<? echo $deptid ?>">
<big><b>Preferences</b></big><br>
<table cellspacing=1 cellpadding=1 border=0 width="90%">
</tr>
	<td><span class="basetxt">
	<li> When you set your admin request console to <i>Offline</i>, the window will automatically close (for security and to limit system usage).<p>
	Switching to <i>Offline</i> status is helpful when you step away from your computer for a short time.  You can set the minutes to wait until the <i>Offline</i> console automatically closes -&gt; <font size=2><input type="text" name="console_close_min" size="3" maxlength="3" class="input" value="<?= ( $admin['console_close_min'] ) ? $admin['console_close_min'] : $HTTP_POST_VARS['console_close_min'] ?>" OnBlur="check_console_value(this.value)" onKeyPress="return numbersonly(event)"></font> minutes
	</td>
</tr>
<tr>
	<td><span class="basetxt">
		<a href="JavaScript:do_submit()"><img src="../pics/buttons/submit.gif" border=0></a>
	</td>
</tr>
</table>
</form>

<p>
<? include_once( "./footer.php" ); ?>
</center>

<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>