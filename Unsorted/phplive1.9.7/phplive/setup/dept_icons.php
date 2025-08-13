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
	include_once("../lang_packs/$LANG_PACK.php") ;
	include_once("../system.php") ;
	include_once("../API/sql.php") ;
	include_once("../API/Users/get.php") ;
	include_once("../API/Users/update.php") ;
?>
<?

	// initialize
	$action = "" ;
	$deptid = "" ;
	$success = 0 ;
	$error_mesg = "" ;

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "20" ;
	else
		$text_width = "10" ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }
	if ( isset( $HTTP_GET_VARS['success'] ) ) { $success = $HTTP_GET_VARS['success'] ; }

	if ( !$deptid )
	{
		HEADER( "location: adddept.php" ) ;
		exit ;
	}
?>
<?
	// functions
?>
<?
	// conditions

	$deptinfo = AdminUsers_get_DeptInfo( $dbh, $deptid, $session_setup['aspID'] ) ;
	if ( $action == "upload_status_image" )
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

		$filename = "DEPT_$now.$extension" ;
		if ( eregi( "gif", $filetype ) ||  eregi( "jpeg", $filetype ) )
		{
			if( copy( $HTTP_POST_FILES['pic']['tmp_name'], "../web/$session_setup[login]/$filename" ) )
			{
				if ( $HTTP_POST_VARS['logo_name'] == "status_image_online" )
				{
					if ( file_exists ( "../web/$session_setup[login]/$deptinfo[status_image_online]" ) && $deptinfo['status_image_online'] )
						unlink( "../web/$session_setup[login]/$deptinfo[status_image_online]" ) ;
					AdminUsers_update_DeptValue( $dbh, $session_setup['aspID'], $deptid, "status_image_online", $filename ) ;
				}
				else if ( $HTTP_POST_VARS['logo_name'] == "status_image_offline" )
				{
					if ( file_exists ( "../web/$session_setup[login]/$deptinfo[status_image_offline]" ) && $deptinfo['status_image_offline'] )
						unlink( "../web/$session_setup[login]/$deptinfo[status_image_offline]" ) ;
					AdminUsers_update_DeptValue( $dbh, $session_setup['aspID'], $deptid, "status_image_offline", $filename ) ;
				}
			}
			unlink( $HTTP_POST_FILES['pic']['tmp_name'] ) ;

			HEADER( "location: dept_icons.php?deptid=$deptid&success=1" ) ;
			exit ;
		}
		else if ( $pic_name != "" )
			$error_mesg = "Please upload ONLY GIF or JPEG formats.<br>" ;
	}
?>
<html>
<head>
<title> Department Preferences </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="../css/base.css">
<script language="JavaScript" src="../js/global.js"></script>

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
		<?
			if ( $success )
				print "		alert( \"Success!\" ) ;\n" ;
		?>
	}
//-->
</script>
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
		<b><big>Department Preferences</big></b> - <a href="adddept.php">manage departments</a> - <a href="options.php">back to main</a>
		<br>
		<br>
		<img src="../pics/icons/manager.gif" width="32" height="32" border=0 alt="" align="left"> Each department can have their own online/offline status image.  The department specific status image will only display if you generate department specific HTML code.
		<p>
		<big><b><? echo $deptinfo['name'] ?> Department Online/Offline Status Image</b></big>
		<p>
		<font color="#FF0000"><? echo $error_mesg ?></font>
		<table cellspacing=1 cellpadding=1 border=0>
			<form method="POST" action="dept_icons.php" enctype="multipart/form-data" name="support_logo_online">
			<input type="hidden" name="action" value="upload_status_image">
			<input type="hidden" name="deptid" value="<? echo $deptid ?>">
			<input type="hidden" name="logo_name" value="status_image_online">
			<tr><td><span class="basetxt">Upload Department <u>Online</u> Support Image </td><td><span class="basetxt"><font size=1><input type="file" name="pic" size="20" class="input"></td><td><span class="basetxt"> <a href="JavaScript:do_upload(document.support_logo_online)"><img src="../pics/buttons/upload.gif" border=0></a></td></tr>
			<tr><td colspan=3><span class="basetxt">Your Current Department <i>ONLINE</i> Support Image <img src="
			<?
				if ( file_exists( "../web/$session_setup[login]/$deptinfo[status_image_online]" ) && $deptinfo['status_image_online'] )
					echo "../web/$session_setup[login]/$deptinfo[status_image_online]";
				else if ( isset( $SUPPORT_LOGO_ONLINE ) && $SUPPORT_LOGO_ONLINE && file_exists( "../web/$session_setup[login]/$SUPPORT_LOGO_ONLINE" ) )
					echo "../web/$session_setup[login]/$SUPPORT_LOGO_ONLINE" ;
				else
					echo "../pics/phplive_support_online.gif" ;
			?>"></td></tr>
			</form>

			<tr><td colspan==3>&nbsp;<br></td></tr>

			<form method="POST" action="dept_icons.php" enctype="multipart/form-data" name="support_logo_offline">
			<input type="hidden" name="action" value="upload_status_image">
			<input type="hidden" name="deptid" value="<? echo $deptid ?>">
			<input type="hidden" name="logo_name" value="status_image_offline">
			<tr><td><span class="basetxt">Upload Department <u>Offline</u> Support Image </td><td><span class="basetxt"><font size=1><input type="file" name="pic" size="20" class="input"></td><td><span class="basetxt"> <a href="JavaScript:do_upload(document.support_logo_offline)"><img src="../pics/buttons/upload.gif" border=0></a></td></tr>
			<tr><td colspan=3><span class="basetxt">Your Current Department <i>OFFLINE</i> Support Image <img src="
			<?
				if ( file_exists( "../web/$session_setup[login]/$deptinfo[status_image_offline]" ) && $deptinfo['status_image_offline'] )
					echo "../web/$session_setup[login]/$deptinfo[status_image_offline]";
				else if ( isset( $SUPPORT_LOGO_OFFLINE ) && $SUPPORT_LOGO_OFFLINE && file_exists( "../web/$session_setup[login]/$SUPPORT_LOGO_OFFLINE" ) )
					echo "../web/$session_setup[login]/$SUPPORT_LOGO_OFFLINE" ;
				else
					echo "../pics/phplive_support_offline.gif" ;
			?>"></td></tr>
			</form>
		</table>
		

	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>