<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	if ( !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: ../setup/index.php" ) ;
		exit ;
	}
	include_once("../web/conf-init.php") ;
	include_once("../system.php") ;
	include_once("../lang_packs/$LANG_PACK.php") ;
	include_once("../API/sql.php" ) ;
	include_once("../API/Form.php") ;
	include_once("../API/ASP/get.php") ;
?>
<?

	// initialize
?>
<?
	// functions
?>
<?
	// conditions
?>
<html>
<head>
<title> Super Admin </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
//-->
</script>
<link rel="Stylesheet" href="../css/base.css">
<script language="JavaScript" src="../js/global.js"></script>
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A">
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
		<big><b>Congratulations!  System is successfully setup!</b></big><p>
		This is the super admin area.  You can update your company information and customize your company logo here.
		<p>
		[ <a href="profile.php">Your Site Profile</a> ]
		[ <a href="customize.php">Customize Logo</a> ]

		<?
			if ( file_exists( "asp.php" ) && $ASP_KEY )
				print "		<big><b>[ <a href=\"asp.php\">ASP Service Suite</a> ]</b></big>" ;
		?>
		<p>
		PASSWORD PROTECT THIS (super/) DIRECTORY!
		<br>
		<hr>
		<big><b>Site setup area:</b></big>
		<p>
		To customize your site online/offline icons, manage departments and users, view logs, and other setup tasks, please go to the below setup area and login with your site login and password (from above profile).
		<p>
		<big><b><a href="../setup/index.php"><? echo $BASE_URL ?>/setup/</a></b></big>
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