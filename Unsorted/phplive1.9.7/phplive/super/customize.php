<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	include_once("../web/conf-init.php");
	include_once("../system.php") ;
	include_once("../lang_packs/$LANG_PACK.php") ;
?>
<?

	// initialize
	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "12" ;
	else
		$text_width = "9" ;

	// get variables
	$action = $error_mesg = $success = "" ;
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
		$now = time() ;
		$pic_name = $HTTP_POST_FILES['pic']['name'] ;
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
			if( copy( $HTTP_POST_FILES['pic']['tmp_name'], "../web/$filename" ) )
			{
				if ( $HTTP_POST_VARS['logo_name'] == "LOGO" )
				{
					if ( file_exists ( "../web/$LOGO_ASP" ) && $LOGO_ASP )
						unlink( "../web/$LOGO_ASP" ) ;
					$LOGO = $filename ;
				}

				if ( !isset( $ASP_KEY ) ) { $ASP_KEY = "" ; }
				$conf_string = "0LEFT_ARROW0?
					\$ASP_KEY = '$ASP_KEY' ;
					\$NO_PCONNECT = '$NO_PCONNECT' ;
					\$DATABASETYPE = '$DATABASETYPE' ;
					\$DATABASE = '$DATABASE' ;
					\$SQLHOST = '$SQLHOST' ;
					\$SQLLOGIN = '$SQLLOGIN' ;
					\$SQLPASS = '$SQLPASS' ;
					\$DOCUMENT_ROOT = '$DOCUMENT_ROOT' ;
					\$BASE_URL = '$BASE_URL' ;
					\$SITE_NAME = '$SITE_NAME' ;
					\$LOGO_ASP = '$LOGO' ;
					\$LANG_PACK = '$LANG_PACK' ;?0RIGHT_ARROW0" ;
				$conf_string = preg_replace( "/0LEFT_ARROW0/", "<", $conf_string ) ;
				$conf_string = preg_replace( "/0RIGHT_ARROW0/", ">", $conf_string ) ;
				$fp = fopen ("../web/conf-init.php", "wb+") ;
				fwrite( $fp, $conf_string, strlen( $conf_string ) ) ;
				fclose( $fp ) ;
			}
			unlink( $HTTP_POST_FILES['pic']['tmp_name'] ) ;

			HEADER( "location: customize.php?success=1" ) ;
			exit ;
		}
		else if ( $pic_name != "" )
			$error_mesg = "Please upload ONLY GIF or JPEG formates.<br>" ;
	}
?>
<html>
<head>
<title> Super Admin </title>
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
			if ( file_exists( "../web/$LOGO_ASP" ) && $LOGO_ASP )
				$logo = "../web/$LOGO_ASP" ;
			else
				$logo = "../pics/phplive_logo.gif" ;
		?>
		<img src="<? echo $logo ?>">
		<br>
		<b><big>Site Management</big></b> - <a href="index.php">back to menu</a><p>
		Update your logo below.  Please make sure the file is ONLY GIF or JPEG file format (max width: 370px - max height: 56px).
		<p>

		<font color="#FF0000"><? echo $error_mesg ?></font>
		<table cellspacing=1 cellpadding=1 border=0>
			<form method="POST" action="customize.php" enctype="multipart/form-data" name="logo">
			<input type="hidden" name="action" value="upload_logo">
			<input type="hidden" name="logo_name" value="LOGO">
			<tr><td><span class="basetxt">Upload Company Logo </td><td><span class="basetxt"><font size=1><input type="file" name="pic" size="20" class="input"></td><td><span class="basetxt"> <a href="JavaScript:do_upload(document.logo)"><img src="../pics/buttons/upload.gif" border=0></a></td></tr>
			</form>
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