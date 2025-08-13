<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$session_chat = $HTTP_SESSION_VARS['session_chat'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : $HTTP_POST_VARS['sid'] ;

	if ( !file_exists( "web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "web/conf-init.php" ) )
	{
		print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting...</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;	include_once("./web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./lang_packs/$LANG_PACK.php") ;
	include_once("./API/sql.php") ;
	include_once("./API/Users/get.php") ;

	// initialize
	$date = date( "D m/d/y h:i a", time() ) ;
	$admin = AdminUsers_get_UserInfo( $dbh, $session_chat[$sid]['admin_id'], $session_chat[$sid]['aspID'] ) ;
	$department = AdminUsers_get_DeptInfo( $dbh, $session_chat[$sid]['deptid'], $session_chat[$sid]['aspID'] ) ;
?>
<?
	// functions
?>
<?
	// conditions
?>
<html>
<head>
<title> <? echo $LANG['TITLE_PRINTVIEW'] ?> </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="./css/base.css">
<script language="JavaScript">var loaded = 0 ;</script>
</head>
<body bgColor="<? echo $CHAT_BOX_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
<br>
<table cellspacing=0 cellpadding=2 border=0 width="100%">
<tr>
	<td><span class="basetxt" width="100%">
		<!-- begin header -->
		<?
			if ( file_exists( "web/".$session_chat[$sid]['asp_login']."/$LOGO" ) && $LOGO )
				$logo = "web/".$session_chat[$sid]['asp_login']."/$LOGO" ;
			else if ( file_exists( "web/$LOGO_ASP" ) && $LOGO_ASP )
				$logo = "web/$LOGO_ASP" ;
			else
				$logo = "pics/phplive_logo.gif" ;
		?><img src="<? echo $logo ?>">
		<br>
		<table cellspacing=1 cellpadding=1 border=0>
		<tr>
			<td><span class="basetxt"><b><? echo $LANG['WORD_COMPANY'] ?></b></td>
			<td><span class="basetxt"> <? echo $admin['company'] ?></td>
		</tr>
		<tr>
			<td><span class="basetxt"><b><? echo $LANG['WORD_DEPARTMENT'] ?></b></td>
			<td><span class="basetxt"> <? echo $department['name'] ?></td>
		</tr>
		<tr>
			<td><span class="basetxt"><b><? echo $LANG['WORD_NAME'] ?></b></td>
			<td><span class="basetxt"><? echo $admin['name'] ?></td>
		</tr>
		<tr>
			<td><span class="basetxt"><b><? echo $LANG['WORD_EMAIL'] ?></b></td>
			<td><span class="basetxt"> <a href="mailto:$admin[email]"><? echo $admin['email'] ?></a></td>
		</tr>
		<tr>
			<td><span class="basetxt"><b><? echo $LANG['WORD_DAY'] ?></b></td>
			<td><span class="basetxt"> <? echo $date ?></td>
		</tr>
		</table>
		<!-- end header -->

		<br>
		<? echo $session_chat[$sid]['transcript'] ?>
		<br>
		<hr>
		<br>
	</td>
</tr>
</table>
<br>
<center>
<!-- DO NOT REMOVE THE COPYRIGHT NOTICE OF "&nbsp; OSI Codes Inc." -->
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
<span class="smalltxt"><? echo $LANG['DEFAULT_BRANDING'] ?> &copy; OSI Codes Inc.</span>
</center>
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>