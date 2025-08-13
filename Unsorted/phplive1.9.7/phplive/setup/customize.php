<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	if ( isset( $HTTP_SESSION_VARS['session_setup'] ) ) { $session_setup = $HTTP_SESSION_VARS['session_setup'] ; } else { HEADER( "location: index.php" ) ; exit ; }
	if ( !file_exists( "../web/$session_setup[login]/$session_setup[login]-conf-init.php" ) || !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: index.php" ) ;
		exit ;
	}
	include_once("../web/conf-init.php");
	include_once("../web/$session_setup[login]/$session_setup[login]-conf-init.php") ;
	include_once("../system.php") ;
	include_once("../lang_packs/$LANG_PACK.php") ;
?>
<?

	// initialize
	$action = $error_mesg = "" ;
	$success = 0 ;

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "12" ;
	else
		$text_width = "9" ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['success'] ) ) { $success = $HTTP_GET_VARS['success'] ; }
?>
<?
	// functions
?>
<?
	// conditions
	if ( $action == "upload_logo" )
	{
		$pic_name = $HTTP_POST_FILES['pic']['name'] ;
		$now = time() ;
		$filename = eregi_replace( " ", "_", $pic_name ) ;
		$filename = eregi_replace( "%20", "_", $filename ) ;

		$filesize = $HTTP_POST_FILES['pic']['size'] ;
		$filetype = $HTTP_POST_FILES['pic']['type'] ;

		if ( eregi( "gif", $filetype ) )
			$extension = "GIF" ;
		elseif ( eregi( "jpeg", $filetype ) )
			$extension = "JPEG" ;

		$filename = $HTTP_POST_VARS['logo_name']."_$now.$extension" ;
		if ( eregi( "gif", $filetype ) ||  eregi( "jpeg", $filetype ) )
		{
			if( copy( $HTTP_POST_FILES['pic']['tmp_name'], "../web/$session_setup[login]/$filename" ) )
			{
				if ( $HTTP_POST_VARS['logo_name'] == "LOGO" )
				{
					if ( file_exists ( "../web/$session_setup[login]/$LOGO" ) && $LOGO )
						unlink( "../web/$session_setup[login]/$LOGO" ) ;
					$LOGO = $filename ;
				}

				// set if not set in conf file
				if ( !isset( $IPNOTRACK ) ) { $IPNOTRACK = "" ; }

				$conf_string = "0LEFT_ARROW0?
				\$LOGO = '$LOGO' ;
				\$COMPANY_NAME = '$COMPANY_NAME' ;
				\$SUPPORT_LOGO_ONLINE = '$SUPPORT_LOGO_ONLINE' ;
				\$SUPPORT_LOGO_OFFLINE = '$SUPPORT_LOGO_OFFLINE' ;
				\$VISITOR_FOOTPRINT = '$VISITOR_FOOTPRINT' ;
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
				\$INITIATE = '$INITIATE' ;
				\$IPNOTRACK = '$IPNOTRACK' ;
				\$LANG_PACK = '$LANG_PACK' ;?0RIGHT_ARROW0" ;

				$conf_string = preg_replace( "/0LEFT_ARROW0/", "<", $conf_string ) ;
				$conf_string = preg_replace( "/0RIGHT_ARROW0/", ">", $conf_string ) ;
				$fp = fopen ("../web/$session_setup[login]/$session_setup[login]-conf-init.php", "wb+") ;
				fwrite( $fp, $conf_string, strlen( $conf_string ) ) ;
				fclose( $fp ) ;
			}
			unlink( $HTTP_POST_FILES['pic']['tmp_name'] ) ;

			HEADER( "location: customize.php?action=logo&success=1" ) ;
			exit ;
		}
		else if ( $pic_name != "" )
			$error_mesg = "Please upload ONLY GIF or JPEG formats.<br>" ;
	}
	if ( $action == "upload_icons" )
	{
		$pic_name = $HTTP_POST_FILES['pic']['name'] ;
		$now = time() ;
		$filename = eregi_replace( " ", "_", $pic_name ) ;
		$filename = eregi_replace( "%20", "_", $filename ) ;

		$filesize = $HTTP_POST_FILES['pic']['size'] ;
		$filetype = $HTTP_POST_FILES['pic']['type'] ;

		if ( eregi( "gif", $filetype ) )
			$extension = "GIF" ;
		elseif ( eregi( "jpeg", $filetype ) )
			$extension = "JPEG" ;

		$filename = $HTTP_POST_VARS['logo_name']."_$now.$extension" ;
		if ( eregi( "gif", $filetype ) ||  eregi( "jpeg", $filetype ) )
		{
			if( copy( $HTTP_POST_FILES['pic']['tmp_name'], "../web/$session_setup[login]/$filename" ) )
			{
				if ( $HTTP_POST_VARS['logo_name'] == "SUPPORT_LOGO_ONLINE" )
				{
					if ( file_exists ( "../web/$session_setup[login]/$SUPPORT_LOGO_ONLINE" ) && $SUPPORT_LOGO_ONLINE )
						unlink( "../web/$session_setup[login]/$SUPPORT_LOGO_ONLINE" ) ;
					$SUPPORT_LOGO_ONLINE = $filename ;
				}
				else if ( $HTTP_POST_VARS['logo_name'] == "SUPPORT_LOGO_OFFLINE" )
				{
					if ( file_exists ( "../web/$session_setup[login]/$SUPPORT_LOGO_OFFLINE" ) && $SUPPORT_LOGO_OFFLINE )
						unlink( "../web/$session_setup[login]/$SUPPORT_LOGO_OFFLINE" ) ;
					$SUPPORT_LOGO_OFFLINE = $filename ;
				}

				// set if not set in conf file
				if ( !isset( $IPNOTRACK ) ) { $IPNOTRACK = "" ; }

				$conf_string = "0LEFT_ARROW0?
				\$LOGO = '$LOGO' ;
				\$COMPANY_NAME = '$COMPANY_NAME' ;
				\$SUPPORT_LOGO_ONLINE = '$SUPPORT_LOGO_ONLINE' ;
				\$SUPPORT_LOGO_OFFLINE = '$SUPPORT_LOGO_OFFLINE' ;
				\$VISITOR_FOOTPRINT = '$VISITOR_FOOTPRINT' ;
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
				\$INITIATE = '$INITIATE' ;
				\$IPNOTRACK = '$IPNOTRACK' ;
				\$LANG_PACK = '$LANG_PACK' ;?0RIGHT_ARROW0" ;

				$conf_string = preg_replace( "/0LEFT_ARROW0/", "<", $conf_string ) ;
				$conf_string = preg_replace( "/0RIGHT_ARROW0/", ">", $conf_string ) ;
				$fp = fopen ("../web/$session_setup[login]/$session_setup[login]-conf-init.php", "wb+") ;
				fwrite( $fp, $conf_string, strlen( $conf_string ) ) ;
				fclose( $fp ) ;
			}
			unlink( $HTTP_POST_FILES['pic']['tmp_name'] ) ;

			HEADER( "location: customize.php?action=icons&success=1" ) ;
			exit ;
		}
		else if ( $pic_name != "" )
			$error_mesg = "Please upload ONLY GIF or JPEG formats.<br>" ;
	}
	else if ( $action == "update_colors" )
	{
		$form = $HTTP_POST_VARS['form'] ;

		$conf_string = "0LEFT_ARROW0?
		\$LOGO = '$LOGO' ;
		\$COMPANY_NAME = '$COMPANY_NAME' ;
		\$SUPPORT_LOGO_ONLINE = '$SUPPORT_LOGO_ONLINE' ;
		\$SUPPORT_LOGO_OFFLINE = '$SUPPORT_LOGO_OFFLINE' ;
		\$VISITOR_FOOTPRINT = '$VISITOR_FOOTPRINT' ;
		\$TEXT_COLOR = '$form[TEXT_COLOR]' ;
		\$LINK_COLOR = '$form[LINK_COLOR]' ;
		\$ALINK_COLOR = '$form[ALINK_COLOR]' ;
		\$VLINK_COLOR = '$form[VLINK_COLOR]' ;
		\$CLIENT_COLOR = '$form[CLIENT_COLOR]' ;
		\$ADMIN_COLOR = '$form[ADMIN_COLOR]' ;
		\$CHAT_BACKGROUND = '$form[CHAT_BACKGROUND]' ;
		\$CHAT_REQUEST_BACKGROUND = '$form[CHAT_REQUEST_BACKGROUND]' ;
		\$CHAT_BOX_BACKGROUND = '$form[CHAT_BOX_BACKGROUND]' ;
		\$CHAT_BOX_TEXT = '$form[CHAT_BOX_TEXT]' ;
		\$POLL_TIME = '$POLL_TIME' ;
		\$INITIATE = '$INITIATE' ;
		\$IPNOTRACK = '$IPNOTRACK' ;
		\$LANG_PACK = '$form[LANG_PACK]' ;?0RIGHT_ARROW0" ;

		$conf_string = preg_replace( "/0LEFT_ARROW0/", "<", $conf_string ) ;
		$conf_string = preg_replace( "/0RIGHT_ARROW0/", ">", $conf_string ) ;
		$fp = fopen ("../web/$session_setup[login]/$session_setup[login]-conf-init.php", "wb+") ;
		fwrite( $fp, $conf_string, strlen( $conf_string ) ) ;
		fclose( $fp ) ;
		HEADER( "location: customize.php?action=colors&success=1" ) ;
		exit ;
	}
?>
<html>
<head>
<title> Customize </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	function do_upload(the_form)
	{
		if ( the_form.pic.value == "" )
			alert( "Input cannot be blank." ) ;
		else
			the_form.submit() ;
	}

	function do_submit()
	{
		ok = 1 ;
		for ( c = 0; c < document.customize.length; ++c )
		{
			if ( document.customize[c].value == "" )
			{
				ok = 0 ;
				break ;
			}
		}
		if ( ok )
			document.customize.submit() ;
		else
			alert( "All fields must be supplied." ) ;
	}

	function do_alert()
	{
		<? if ( $success ) { print "		alert( 'Success!' ) ;\n" ; } ?>
	}
//-->
</script>
<link rel="Stylesheet" href="../css/base.css">
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" OnLoad="do_alert()">
<table cellspacing=0 cellpadding=0 border=0 width="98%">
<tr>
	<td valign="top"><span class="basetxt">
		<?
			if ( file_exists( "../web/$session_setup[login]/$LOGO" ) && $LOGO )
				$logo = "../web/$session_setup[login]/$LOGO" ;
			else if ( file_exists( "../web/$LOGO_ASP" ) && $LOGO_ASP )
				$logo = "../web/$LOGO_ASP" ;
			else
				$logo = "../pics/phplive_logo.gif" ;
		?>
		<img src="<? echo $logo ?>">
		<br>
		<big><b>Customize</b></big> - <a href="options.php">back to menu</a>
		<br>

		<font color="#FF0000"><? echo $error_mesg ?></font>
		<br>

		<? if ( ( $action == "logo" ) && file_exists( "../super/asp.php" ) ) : ?>
		<img src="../pics/icons/paint.gif" width="32" height="32" border=0 alt="" align="left"> Customize your company logo by uploading the logo image (GIF/JPEG) file below (max width: 370px - max height: 56px).
		<br><br>
		<p>
		<table cellspacing=1 cellpadding=1 border=0>
			<form method="POST" action="customize.php" enctype="multipart/form-data" name="logo">
			<input type="hidden" name="action" value="upload_logo">
			<input type="hidden" name="logo_name" value="LOGO">
			<tr><td><span class="basetxt">Upload LOGO </td><td><span class="basetxt"><font size=1><input type="file" name="pic" size="20" class="input"></td><td><span class="basetxt"> <a href="JavaScript:do_upload(document.logo)"><img src="../pics/buttons/upload.gif" border=0></a></td></tr>
			</form>
		</table>


		<? elseif ( $action == "icons" ): ?>
		<img src="../pics/icons/paint.gif" width="32" height="32" border=0 alt="" align="left"> Set your default chat icons below.  If you would like to customize icons for EACH department, <a href="dept.php">click here</a>.
		<p>
		<table cellspacing=1 cellpadding=1 border=0>
			<form method="POST" action="customize.php" enctype="multipart/form-data" name="support_logo_online">
			<input type="hidden" name="action" value="upload_icons">
			<input type="hidden" name="logo_name" value="SUPPORT_LOGO_ONLINE">
			<tr><td><span class="basetxt">Upload <u>Online</u> Support Image </td><td><span class="basetxt"><font size=1><input type="file" name="pic" size="20" class="input"></td><td><span class="basetxt"> <a href="JavaScript:do_upload(document.support_logo_online)"><img src="../pics/buttons/upload.gif" border=0></a></td></tr>
			<tr><td colspan=3><span class="basetxt">Your Current <i>ONLINE</i> Support Image <img src="<?= ( file_exists( "../web/$session_setup[login]/$SUPPORT_LOGO_ONLINE" ) && $SUPPORT_LOGO_ONLINE ) ? "../web/$session_setup[login]/$SUPPORT_LOGO_ONLINE" : "../pics/phplive_support_online.gif" ?>"></td></tr>
			</form>

			<tr><td colspan==3>&nbsp;<br></td></tr>

			<form method="POST" action="customize.php" enctype="multipart/form-data" name="support_logo_offline">
			<input type="hidden" name="action" value="upload_icons">
			<input type="hidden" name="logo_name" value="SUPPORT_LOGO_OFFLINE">
			<tr><td><span class="basetxt">Upload <u>Offline</u> Support Image </td><td><span class="basetxt"><font size=1><input type="file" name="pic" size="20" class="input"></td><td><span class="basetxt"> <a href="JavaScript:do_upload(document.support_logo_offline)"><img src="../pics/buttons/upload.gif" border=0></a></td></tr>
			<tr><td colspan=3><span class="basetxt">Your Current <i>OFFLINE</i> Support Image <img src="<?= ( file_exists( "../web/$session_setup[login]/$SUPPORT_LOGO_OFFLINE" ) && $SUPPORT_LOGO_OFFLINE ) ? "../web/$session_setup[login]/$SUPPORT_LOGO_OFFLINE" : "../pics/phplive_support_offline.gif" ?>"></td></tr>
			</form>
		</table>


		<? elseif ( $action == "colors" ): ?>
		<img src="../pics/icons/paint.gif" width="32" height="32" border=0 alt="" align="left"> Customize your chat window colors below.  Colors are in HEX values.  <span class="smalltxt">- <a href="http://google.yahoo.com/bin/query?p=javascript+RGB+slider&hc=0&hs=0" target="new">Search Yahoo! for HEX Color Chart Help</a></span><br>
		<p>
		<form method="POST" action="customize.php" name="customize">
		<input type="hidden" name="action" value="update_colors">
		<table cellspacing=2 cellpadding=2 border=0>
		<tr>
			<td colspan=4><span class="smalltxt">
			<u>Client Login Color</u>: Visitor's login name color during chat.<br>
			<u>Admin Login Color</u>: Admin's login name color during chat.<br>
			<u>Text Color</u>: Color of text throughout the system EXCEPT 'Chat Box Text Color' has it's own color.<br>
			<u>Link Color</u>: Color of links throughout the entire system.<br>
			<u>ALink Color</u>: Color of link press throughout the entire system.<br>
			<u>VLink Color</u>: Color of visited links throughout the entire system.<br>
			<u>Chat Bgcolor</u>: Background color of chat session window.<br>
			<u>Chat Box Bgcolor</u>: Background color of chat message area (where message appears).<br>
			<u>Chat Box Text Color</u>: The text color of text messages during chat.<br>
			<u>Chat Request Bgcolor</u>: Background color of live support request window (used also for leave a message window).
			<br>
			</td>
		</tr>
		<tr>
			<td><span class="basetxt">Client Login Color</td>
			<td><span class="basetxt">Admin Login Color</td>
		</tr>
		<tr>
			<td bgColor="<? echo $CLIENT_COLOR ?>"><span class="basetxt"><font size=1><input type="text" name="form[CLIENT_COLOR]" value="<? echo $CLIENT_COLOR ?>" size="<? echo $text_width ?>" maxlength="7" class="input"></td>
			<td bgColor="<? echo $ADMIN_COLOR ?>"><span class="basetxt"><font size=1><input type="text" name="form[ADMIN_COLOR]" value="<? echo $ADMIN_COLOR ?>" size="<? echo $text_width ?>" maxlength="7" class="input"></td>
		</tr>
		<tr>
			<td><span class="basetxt">Text Color</td>
			<td><span class="basetxt">Link Color</td>
			<td><span class="basetxt">ALink Color</td>
			<td><span class="basetxt">VLink Color</td>
		</tr>
		<tr>
			<td bgColor="<? echo $TEXT_COLOR ?>"><span class="basetxt"><font size=1><input type="text" name="form[TEXT_COLOR]" value="<? echo $TEXT_COLOR ?>" size="<? echo $text_width ?>" maxlength="7" class="input"></td>
			<td bgColor="<? echo $LINK_COLOR ?>"><span class="basetxt"><font size=1><input type="text" name="form[LINK_COLOR]" value="<? echo $LINK_COLOR ?>" size="<? echo $text_width ?>" maxlength="7" class="input"></td>
			<td bgColor="<? echo $ALINK_COLOR ?>"><span class="basetxt"><font size=1><input type="text" name="form[ALINK_COLOR]" value="<? echo $ALINK_COLOR ?>" size="<? echo $text_width ?>" maxlength="7" class="input"></td>
			<td bgColor="<? echo $VLINK_COLOR ?>"><span class="basetxt"><font size=1><input type="text" name="form[VLINK_COLOR]" value="<? echo $VLINK_COLOR ?>" size="<? echo $text_width ?>" maxlength="7" class="input"></td>
		</tr>
		<tr>
			<td><span class="basetxt">Chat Bgcolor</td>
			<td><span class="basetxt">Chat Box Bgcolor</td>
			<td><span class="basetxt">Chat Box Text Color</td>
			<td><span class="basetxt">Chat Request Bgcolor</td>
		</tr>
		<tr>
			<td bgColor="<? echo $CHAT_BACKGROUND ?>"><span class="basetxt"><font size=1><input type="text" name="form[CHAT_BACKGROUND]" value="<? echo $CHAT_BACKGROUND ?>" size="<? echo $text_width ?>" maxlength="7" class="input"></td>
			<td bgColor="<? echo $CHAT_BOX_BACKGROUND ?>"><span class="basetxt"><font size=1><input type="text" name="form[CHAT_BOX_BACKGROUND]" value="<? echo $CHAT_BOX_BACKGROUND ?>" size="<? echo $text_width ?>" maxlength="7" class="input"></td>
			<td bgColor="<? echo $CHAT_BOX_TEXT ?>"><span class="basetxt"><font size=1><input type="text" name="form[CHAT_BOX_TEXT]" value="<? echo $CHAT_BOX_TEXT ?>" size="<? echo $text_width ?>" maxlength="7" class="input"></td>
			<td bgColor="<? echo $CHAT_REQUEST_BACKGROUND ?>"><span class="basetxt"><font size=1><input type="text" name="form[CHAT_REQUEST_BACKGROUND]" value="<? echo $CHAT_REQUEST_BACKGROUND ?>" size="<? echo $text_width ?>" maxlength="7" class="input"></td>
		</tr>
		<tr>
			<td colspan=4><span class="basetxt">
				Language: 
				<select name="form[LANG_PACK]" class="select">
				<?
					if ( $dir = @opendir( "../lang_packs" ) )
					{
						while( $file = readdir( $dir ) )
						{
							if ( ( $file = preg_replace( "/\.php/", "", $file ) ) && !preg_match( "/(.bak)|(CVS)/", $file ) && preg_match( "/[0-9a-z]/i", $file ) )
							{
								$selected = "" ;
								if ( $file == $LANG_PACK )
									$selected = "selected" ;
								print "<option value=\"$file\" $selected>$file" ;
							}
						} 
						closedir($dir) ;
					}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan=4><br><a href="JavaScript:do_submit()"><img src="../pics/buttons/submit.gif" border=0></a></td>
		</tr>
		</table>

		</form>
		<? else: ?>





		<? endif ; ?>
	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>