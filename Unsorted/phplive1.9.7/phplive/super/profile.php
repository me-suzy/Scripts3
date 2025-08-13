<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	include_once("../web/conf-init.php") ;
	include_once("../system.php") ;
	include_once("../lang_packs/$LANG_PACK.php") ;
	include_once("../API/sql.php" ) ;
	include_once("../API/Form.php") ;
	include_once("../API/ASP/get.php") ;
	include_once("../API/ASP/update.php") ;
?>
<?

	// initialize
	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "20" ;
	else
		$text_width = "10" ;

	$success = 0 ;

	// get variables
	$action = $error = "" ;
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
?>
<?
	// functions
?>
<?
	// conditions
	if ( $action == "update" )
	{
		// make sure login is not taken if new login is different
		$aspid = $HTTP_POST_VARS['aspid'] ;
		$orig_login = $HTTP_POST_VARS['orig_login'] ;
		$login = $HTTP_POST_VARS['login'] ;
		$password = $HTTP_POST_VARS['password'] ;
		$company = $HTTP_POST_VARS['company'] ;
		$contact_name = $HTTP_POST_VARS['contact_name'] ;
		$contact_email = $HTTP_POST_VARS['contact_email'] ;
		$max_dept = $HTTP_POST_VARS['max_dept'] ;
		$max_users = $HTTP_POST_VARS['max_users'] ;
		$footprints = $HTTP_POST_VARS['footprints'] ;
		$active_status = $HTTP_POST_VARS['active_status'] ;
		$initiate_chat = ( isset( $HTTP_POST_VARS['initiate_chat'] ) ) ? $HTTP_POST_VARS['initiate_chat'] : 0 ;
		$nopconnect = $HTTP_POST_VARS['nopconnect'] ;

		if ( ( ( $orig_login != $login ) && !AdminASP_get_IsLoginTaken( $dbh, $login ) ) || ( $orig_login == $login ) )
		{
			if ( AdminASP_update_user( $dbh, $aspid, $login, $password, $company, $contact_name, $contact_email, $max_dept, $max_users, $footprints, $active_status, $initiate_chat ) )
			{
				if ( file_exists( "../web/$orig_login/$orig_login-conf-init.php" ) )
				{
					include_once( "../web/$orig_login/$orig_login-conf-init.php" ) ;

					// check to see if login is different.  if so, then we need to rename
					// the directory and then remove the old conf file
					if ( $orig_login != $login && file_exists( "../web/$orig_login/$orig_login-conf-init.php" ) )
					{
						unlink( "../web/$orig_login/$orig_login-conf-init.php" ) ;
						rename( "../web/$orig_login", "../web/$login" ) ;
					}

					$conf_string = "0LEFT_ARROW0?
						\$LOGO = '$LOGO' ;
						\$COMPANY_NAME = '$company' ;
						\$SUPPORT_LOGO_ONLINE = '$SUPPORT_LOGO_ONLINE' ;
						\$SUPPORT_LOGO_OFFLINE = '$SUPPORT_LOGO_OFFLINE' ;
						\$VISITOR_FOOTPRINT = '$footprints' ;
						\$TEXT_COLOR = '$TEXT_COLOR' ;
						\$LINK_COLOR = '$LINK_COLOR' ;
						\$ALINK_COLOR = '$ALINK_COLOR' ;
						\$VLINK_COLOR = '$VLINK_COLOR' ;
						\$CLIENT_COLOR = '$CLIENT_COLOR' ;
						\$ADMIN_COLOR = '$ADMIN_COLOR' ;
						\$CHAT_BACKGROUND = '$CHAT_BACKGROUND' ;
						\$CHAT_REQUEST_BACKGROUND = '$CHAT_REQUEST_BACKGROUND' ;
						\$CHAT_BOX_BACKGROUND = '$CHAT_BOX_BACKGROUND' ;
						\$CHAT_BOX_TEXT = '$CHAT_BOX_TEXT' ;
						\$POLL_TIME = '$POLL_TIME' ;
						\$INITIATE = '$initiate_chat' ;
						\$IPNOTRACK = '$IPNOTRACK' ;
						\$LANG_PACK = '$LANG_PACK' ;?0RIGHT_ARROW0" ;
				}
				else
				{
					if ( is_dir( "../web/$login" ) != true )
						mkdir( "../web/$login", 0755 ) ;
					$conf_string = "0LEFT_ARROW0?
						\$LOGO = '' ;
						\$COMPANY_NAME = '$company' ;
						\$SUPPORT_LOGO_ONLINE = 'phplive_support_online.gif' ;
						\$SUPPORT_LOGO_OFFLINE = 'phplive_support_offline.gif' ;
						\$VISITOR_FOOTPRINT = '$footprints' ;
						\$TEXT_COLOR = '#000000' ;
						\$LINK_COLOR = '#35356A' ;
						\$ALINK_COLOR = '#35356A' ;
						\$VLINK_COLOR = '#35356A' ;
						\$CLIENT_COLOR = '#888888' ;
						\$ADMIN_COLOR = '#0000FF' ;
						\$CHAT_BACKGROUND = '#FFFFFF' ;
						\$CHAT_REQUEST_BACKGROUND = '#FFFFFF' ;
						\$CHAT_BOX_BACKGROUND = '#FFFFFF' ;
						\$CHAT_BOX_TEXT = '#000000' ;
						\$POLL_TIME = '30' ;
						\$INITIATE = '$initiate_chat' ;
						\$IPNOTRACK = '' ;
						\$LANG_PACK = 'English'; ?0RIGHT_ARROW0" ;
				}

				$conf_string = preg_replace( "/0LEFT_ARROW0/", "<", $conf_string ) ;
				$conf_string = preg_replace( "/0RIGHT_ARROW0/", ">", $conf_string ) ;
				$fp = fopen ("../web/$login/$login-conf-init.php", "wb+") ;
				fwrite( $fp, $conf_string, strlen( $conf_string ) ) ;
				fclose( $fp ) ;

				// now change the default conf file that is used for
				// ALL the sites
				if ( !isset( $ASP_KEY ) ) { $ASP_KEY = "" ; }
				$conf_string = "0LEFT_ARROW0?
					\$ASP_KEY = '$ASP_KEY' ;
					\$NO_PCONNECT = '$nopconnect' ;
					\$DATABASETYPE = '$DATABASETYPE' ;
					\$DATABASE = '$DATABASE' ;
					\$SQLHOST = '$SQLHOST' ;
					\$SQLLOGIN = '$SQLLOGIN' ;
					\$SQLPASS = '$SQLPASS' ;
					\$DOCUMENT_ROOT = '$DOCUMENT_ROOT' ;
					\$BASE_URL = '$BASE_URL' ;
					\$SITE_NAME = '$company' ;
					\$LOGO_ASP = '$LOGO_ASP' ;
					\$LANG_PACK = '$LANG_PACK' ;?0RIGHT_ARROW0" ;
				$conf_string = preg_replace( "/0LEFT_ARROW0/", "<", $conf_string ) ;
				$conf_string = preg_replace( "/0RIGHT_ARROW0/", ">", $conf_string ) ;
				$fp = fopen ("../web/conf-init.php", "wb+") ;
				fwrite( $fp, $conf_string, strlen( $conf_string ) ) ;
				fclose( $fp ) ;

				$NO_PCONNECT = $nopconnect ;
				$INITIATE = $initiate_chat ;
				$success = 1 ;
			}
		}
		else
			$error = "That login is already in use." ;
	}

	$userinfo = AdminASP_get_UserInfo( $dbh, 1 ) ;
	$active_select = active_status( $userinfo['active_status'] ) ;
	$footprints_select = yesno( $userinfo['footprints'] ) ;
	$initiate_select = yesno( $userinfo['initiate_chat'] ) ;
?>
<html>
<head>
<title> Super Admin </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	function do_update_user()
	{
		var success = 1 ;
		for( c = 2; c < ( document.form.length - 1 ); ++c )
		{
			if ( document.form[c].value == "" )
			{
				alert( "All fields MUST be filled." ) ;
				success = 0 ;
				break ;
			}
		}
		if ( success )
		{
			if ( document.form.max_dept.value < 1 )
				alert( "Max department must be AT LEAST one." ) ;
			else if ( document.form.max_users.value < 1 )
				alert( "Max operators must be AT LEAST one." ) ;
			else
			{
				if ( confirm( "Are you sure?" ) )
					document.form.submit() ;
			}
		}
	}

	function do_alert()
	{
		if( <? echo $success ?> )
			alert( 'Success!' ) ;
	}
//-->
</script>
<link rel="Stylesheet" href="../css/base.css">
<script language="JavaScript" src="../js/global.js"></script>
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" OnLoad="do_alert()">
<table cellspacing=0 cellpadding=0 border=0 width="98%">
<tr>
	<td valign="top"><span class="basetxt">
		<?
			if ( file_exists( "../web/$LOGO_ASP" ) && $LOGO_ASP )
				$logo = "../web/$LOGO_ASP" ;
			else
				$logo = "../pics/phplive_logo.gif" ;
		?>
		<img src="<? echo $logo ?>">
		<br>
		<b><big>Site Management</big></b> - <a href="index.php">back to menu</a><br>
		<br>
		<b>About persistant connects:</b><br>
		When a webpage requests to talk with the database, it has to create a new process for the database connection. When your visitor moves to another page, a persistent connect will re-use that same connection for the next page. Our testing shows that persistent connection OFF has lower server load and strain.
		<p>
		<font color="#FF0000"><? echo $error ?></font>
		<form method="POST" action="profile.php" name="form">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="aspid" value="<? echo $userinfo['aspID'] ?>">
		<input type="hidden" name="orig_login" value="<? echo $userinfo['login'] ?>">

		<input type="radio" value="0" name="nopconnect" class="radio" <?= ( !$NO_PCONNECT ) ? "checked" : "" ?>> Persistent connect ON.<br>
		<input type="radio" value="1" name="nopconnect" class="radio" <?= ( $NO_PCONNECT ) ? "checked" : "" ?>> Persistent connect OFF. (Recommended)
		<p>
		<table cellpadding=1 cellspacing=1 border=0>
		<tr>
			<td><span class="basetxt">Company</td>
			<td><span class="basetxt"><font size=2 face="arial"> <input type="text" name="company" size="<? echo $text_width ?>" maxlength="50" class="input" value="<? echo $userinfo['company'] ?>"></td>
		</tr>
		<tr>
			<td><span class="basetxt">Site Login</td>
			<td><font size=2 face="arial"> <input type="text" name="login" size="<? echo $text_width ?>" maxlength="15" class="input" value="<? echo $userinfo['login'] ?>"></td>
			<td><span class="basetxt">Password</td>
			<td><span class="basetxt"><font size=2 face="arial"> <input type="text" name="password" size="<? echo $text_width ?>" maxlength="15" class="input" value="<? echo $userinfo['password'] ?>"></td>
		</tr>
		<tr>
			<td><span class="basetxt">Contact Name</td>
			<td><span class="basetxt"><font size=2 face="arial"> <input type="text" name="contact_name" size="<? echo $text_width ?>" maxlength="50" class="input" value="<? echo $userinfo['contact_name'] ?>"></td>
			<td><span class="basetxt">Contact Email</td>
			<td><span class="basetxt"><font size=2 face="arial"> <input type="text" name="contact_email" size="<? echo $text_width ?>" maxlength="150" class="input" value="<? echo $userinfo['contact_email'] ?>"></td>
		</tr>
		<tr>
			<td><span class="basetxt">Max Department</td>
			<td><span class="basetxt"><input type="text" name="max_dept" size="4" maxlength="3" onKeyPress="return numbersonly(event)" class="input" value="<? echo $userinfo['max_dept'] ?>"></td>
		</tr>
		<tr>
			<td><span class="basetxt">Max Operators</td>
			<td><span class="basetxt"><input type="text" name="max_users" size="4" maxlength="3" onKeyPress="return numbersonly(event)" class="input" value="<? echo $userinfo['max_users'] ?>"></td>
		</tr>
		<tr>
			<td><span class="basetxt">Active Status</td>
			<td><span class="basetxt"><select name="active_status" class="select"><? echo $active_select ?></select></td>
		</tr>
		<tr>
			<td><span class="basetxt">Footprints</td>
			<td><span class="basetxt"><select name="footprints" class="select"><? echo $footprints_select ?></select></td>
		</tr>
		<? if ( file_exists( "$DOCUMENT_ROOT/admin/traffic/admin_puller.php" ) ): ?>
		<tr>
			<td colspan=4><span class="smalltxt"><font color="#FF0000">If initiate chat is disabled, operator's traffic monitor will also be disabled.<br>This setting will override every department's initiate settings.</font></td>
		</tr>
		<tr>
			<td><span class="basetxt">Initiate Chat</td>
			<td><span class="basetxt"><select name="initiate_chat" class="select"><? echo $initiate_select ?></select></td>
		</tr>
		<? endif ; ?>
		<tr>
			<td colspan=4>&nbsp;</td>
		</tr>
		<tr>
			<td></td>
			<td><a href="JavaScript:do_update_user()"><img src="../pics/buttons/submit.gif" border=0></a></td>
		</tr>
		</table>
		</form>


		<br>
		
	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>