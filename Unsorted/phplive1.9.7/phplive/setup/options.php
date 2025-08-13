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
	include_once("../API/sql.php") ;
	include_once("../API/Util_Optimize.php") ;
	include_once("../API/Users/update.php") ;
	include_once("../API/Footprint/get.php") ;
	include_once("../API/Footprint/remove.php") ;
?>
<?
	// initialize

	// update all admins status to not available if they have been idle
	AdminUsers_update_IdleAdminStatus( $dbh, $admin_idle ) ;

	$now = mktime( 0,0,0,date("m"),date("j"),date("Y") ) ;
	$oldest_footprintstat_date = ServiceFootprint_get_LatestFootprintStatDate( $dbh, $session_setup['aspID'] ) ;
	$oldest_footprintstat_date = mktime( 0,0,0,date("m", $oldest_footprintstat_date),date("j", $oldest_footprintstat_date),date("Y", $oldest_footprintstat_date) ) ;

	// > 0 because if there is no data, database spits out negative numbers
	if ( ( $oldest_footprintstat_date < $now ) && ( $oldest_footprintstat_date > 0 ) )
	{
		$month = date("m", $oldest_footprintstat_date ) ;
		$day = date("j", $oldest_footprintstat_date ) ;
		$year = date("Y", $oldest_footprintstat_date ) ;
		HEADER( "location: optimize.php?month=$month&day=$day&year=$year" ) ;
		exit ;
	}
	else
	{
		if ( isset( $HTTP_GET_VARS['optimized'] ) )
		{
			// do removing of old data (over 1 month old)
			$expireday = $now - (60*60*24*30) ;
			ServiceFootprint_remove_OldFootprints( $dbh, $session_setup['aspID'], $expireday ) ;

			$tables = ARRAY( "chat_admin", "chatcanned", "chatdepartments", "chatfootprints", "chatfootprintsunique", "chatrequestlogs", "chatrequests", "chatsessionlist", "chatsessions", "chattranscripts", "chatuserdeptlist" ) ;
			Util_OPT_Database( $dbh, $tables ) ;
		}
	}
?>
<?
	// functions
?>
<?
	// conditions
?>
<html>
<head>
<title> PHP Live! Config Options </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="../css/base.css">

<script language="JavaScript">
<!--
	function do_alert()
	{
		<? if ( isset( $HTTP_GET_VARS['optimized'] ) ) { print "		alert( 'Optimization of database completed!' ) ;\n" ; } ?>
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
		<big><b>Congratulations! System is configured!</b></big> - [ <a href="login.php?action=logout">logout</a> ]
		<p>
		<table cellspacing=0 cellpadding=2 border=0 width="100%">
		<tr>
			<td valign="top" nowrap><span class="basetxt">
			Basic things you should do:
			<ol>
				<li> <a href="adddept.php">Create/Edit Departments</a><br>
				<li> <a href="adduser.php">Create/Edit Operators</a>
				<li> <a href="code.php?phplive_notally">Generate HTML Code</a>
			</ol>
			</td>
			<td><img src="../pics/empty_nodelete.gif" width="50" height=1></td>
			<td valign="top" bgColor="#E9E9F3" width="100%"><span class="basetxt">
				<big><b>Support Operator Login URL</b></big><br>
				After you <a href="adduser.php">create operators</a>, they must go to the below URL to login and to go online.<p>
				<big><b><li> <a href="<? echo $BASE_URL ?>/web/<? echo $session_setup['login'] ?>/"><? echo $BASE_URL ?>/web/<? echo $session_setup['login'] ?>/</a></b></big>
			</td>
		</tr>
		</table>

		<table cellspacing=0 cellpadding=2 border=0 width="100%" bgColor="#E2E2F1">
		<tr>
			<td bgColor="#8080C0"><img src="../pics/icons/manager.gif" width="32" height="32" border=0 alt=""> <b><font color="#FFFFFF"><span class="basetxt">Manager</td><td><span class="smalltxt">&nbsp;</td>
			<td bgColor="#8080C0"><img src="../pics/icons/world.gif" width="32" height="32" border=0 alt=""> <b><font color="#FFFFFF"><span class="basetxt">HTML Code</td><td><span class="smalltxt">&nbsp;</td>
			<td bgColor="#8080C0"><img src="../pics/icons/stats.gif" width="32" height="32" border=0 alt=""> <b><font color="#FFFFFF"><span class="basetxt">Stats and Reports</td><td><span class="smalltxt">&nbsp;</td>
			<td bgColor="#8080C0"><img src="../pics/icons/gear.gif" width="32" height="32" border=0 alt=""> <b><font color="#FFFFFF"><span class="basetxt">Preferences</td>
		</tr>
		<tr>
			<td valign="top"><span class="basetxt">
				<a href="adddept.php">Create/Edit Departments</a><br>
				<a href="adduser.php">Create/Edit Operators</a>
			</td><td><span class="smalltxt">&nbsp;</td>
			<td valign="top"><span class="basetxt">
				<a href="code.php?phplive_notally">Generate HTML Code</a>
			</td><td><span class="smalltxt">&nbsp;</td>
			<td valign="top"><span class="basetxt">
				<a href="statistics.php">Support Request</a><br>
				<? if ( $VISITOR_FOOTPRINT ): ?>
				<a href="footprints.php">Traffic & Footprints</a><br>
				<? endif ; ?>
				<a href="refer.php">Refer URLs</a>
			</td><td><span class="smalltxt">&nbsp;</td>
			<td valign="top"><span class="basetxt">
				<!-- <a href="prefs.php?action=restrict">Restrict Access</a><br> v1.8--->
				<a href="prefs.php?action=footprints">Exclude IP Tracking</a><br>
				<a href="prefs.php?action=polling">Request Polling</a>
			</td>
		<tr>
		<tr>
			<td colspan=7><span class="smalltxt">&nbsp;</td>
		</tr>
		<tr>
			<td bgColor="#8080C0"><img src="../pics/icons/paint.gif" width="32" height="32" border=0 alt=""> <b><font color="#FFFFFF"><span class="basetxt">Customize Looks</td><td><span class="smalltxt">&nbsp;</td>
			<td bgColor="#8080C0"><img src="../pics/icons/process.gif" width="32" height="32" border=0 alt=""> <b><font color="#FFFFFF"><span class="basetxt">Processes</td><td><span class="smalltxt">&nbsp;</td>
			<td bgColor="#8080C0"><img src="../pics/icons/paint.gif" width="32" height="32" border=0 alt=""> <b><font color="#FFFFFF"><span class="basetxt">Misc. Customize</td><td><span class="smalltxt">&nbsp;</td>

			<? if ( file_exists( "./tracking/index.php" ) && 0 ): ?>
			<td bgColor="#8080C0"><img src="../pics/icons/paint.gif" width="32" height="32" border=0 alt=""> <b><font color="#FFFFFF"><span class="basetxt">Ad Tracking</td><td><span class="smalltxt">&nbsp;</td>
			<? endif ; ?>

		</tr>
		<tr>
			<td valign="top"><span class="basetxt">
				<? if ( file_exists( "../super/asp.php" ) && isset( $ASP_KEY ) && $ASP_KEY ): ?>
				<a href="customize.php?action=logo">Company Logo</a><br>
				<? endif ; ?>

				<a href="customize.php?action=colors">Chat Colors / Language</a><br>
				<a href="customize.php?action=icons">Default Chat Icons</a><br>
				<!-- <a href="customize.php?action=border">Chat Border Color</a> -->
			</td><td><span class="smalltxt">&nbsp;</td>
			<td valign="top"><span class="basetxt">
				<a href="processes.php?action=chat">Chat Processes</a><br>
				<a href="processes.php?action=consol">Admin Processes</a>
			</td><td><span class="smalltxt">&nbsp;</td>
			<td valign="top"><span class="basetxt">
				<a href="email_transcript.php">Email Transcript Page</a>
			</td><td><span class="smalltxt">&nbsp;</td>

			<? if ( file_exists( "./tracking/index.php" ) && 0 ): ?>
			<td valign="top"><span class="basetxt">
				<a href="./tracking/index.php">Create/Edit Campaign</a><br>
				<a href="./tracking/stats.php">Campaign Click-Throughs</a><br>
			</td><td><span class="smalltxt">&nbsp;</td>
			<? endif ; ?>
		</tr>
		</table>
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