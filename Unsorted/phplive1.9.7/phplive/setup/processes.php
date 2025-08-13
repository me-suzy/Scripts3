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
	include_once("../API/Chat/Util.php") ;
	include_once("../API/Users/get.php") ;
	include_once("../API/Users/update.php") ;
	include_once("../API/Chat/get.php") ;
	include_once("../API/Chat/remove.php") ;
?>
<?

	// initialize
	$action = $error_mesg = $adminid = $sessionid = "" ;

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "12" ;
	else
		$text_width = "9" ;

	$success = 0 ;
	// update all admins status to not available if they have been idle
	AdminUsers_update_IdleAdminStatus( $dbh, $admin_idle ) ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['adminid'] ) ) { $adminid = $HTTP_GET_VARS['adminid'] ; }
	if ( isset( $HTTP_GET_VARS['sessionid'] ) ) { $sessionid = $HTTP_GET_VARS['sessionid'] ; }
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "kill_chat" )
	{
		$file_visitor = $sessionid."_admin.txt" ;
		$file_admin = $sessionid."_visitor.txt" ;

		$string = "<STRIP_FOR_PLAIN><font color=\"#FF0000\"><b>** Session was killed by root user.  This session has ended. **</b></font><br><script language=\"JavaScript\">alert( \"Session has been killed by root user.  Window will now close in 10 seconds!\" ) ; setTimeout(\"parent.window.close()\", 10000) ;</script></STRIP_FOR_PLAIN>" ;
		UtilChat_AppendToChatfile( $file_visitor, $string ) ;
		UtilChat_AppendToChatfile( $file_admin, $string ) ;

		// call the script again to give it some time so the message above gets
		// written to the chat screen.  Why?  the system auto cleans chat files if
		// there is no chat parties for that session... thus, the message above could
		// get wiped out without ever making it on the screen.  so let's delay it a bit
		HEADER( "location: processes.php?sessionid=$sessionid&action=kill_done" ) ;
		exit ;
	}
	else if ( $action == "kill_done" )
	{
		// just delete the chatsessionlist content... why?  because there is an
		// auto clean that will sweep through and delete the chat session and
		// all chat files for sessions that are not active (no parties in the session)
		ServiceChat_remove_ChatSessionlist( $dbh, $sessionid ) ;
		$action = "chat" ;
		$success = 1 ;
	}
	else if ( $action == "close_consol" )
	{
		// in UNIX -9 is kill... so let's use 9 as kill signal
		AdminUsers_update_Signal( $dbh, $adminid, 9, $session_setup['aspID'] ) ;
		$action = "consol" ;
		$success = 1 ;
	}
?>
<html>
<head>
<title> Processes </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	function confirm_kill( sessionid )
	{
		if ( confirm( "This will end this chat session!  Should I proceed?" ) )
			location.href = "processes.php?action=kill_chat&sessionid="+sessionid ;
	}

	function confirm_close( adminid )
	{
		if ( confirm( "This will close this operator's console!  Should I proceed?" ) )
			location.href = "processes.php?action=close_consol&adminid="+adminid ;
	}

	function do_alert()
	{
		<? if ( $success ) { print "		alert( 'Success!' ) ;\n" ; } ?>
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
			if ( file_exists( "../web/$session_setup[login]/$LOGO" ) && $LOGO )
				$logo = "../web/$session_setup[login]/$LOGO" ;
			else if ( file_exists( "../web/$LOGO_ASP" ) && $LOGO_ASP )
				$logo = "../web/$LOGO_ASP" ;
			else
				$logo = "../pics/phplive_logo.gif" ;
		?>
		<img src="<? echo $logo ?>">
		<br>
		<big><b>Processes</b></big> - <a href="options.php">back to menu</a><p>

		<font color="#FF0000"><? echo $error_mesg ?></font>

		<?
			if ( $action == "chat" ):
			$chatsessions = ServiceChat_get_ChatSessions( $dbh, $session_setup['aspID'] ) ;
		?>
		<b>Chat Processes</b><br>
		<img src="../pics/icons/process.gif" width="32" height="32" border=0 alt="" align="left"> Below is a complete list of current active chat processes.  You may manually kill and end the process by clicking the "kill" link next to the process ID.
		<p>
		<li> <a href="processes.php?action=chat">reload list</a>
		<table cellspacing=1 cellpadding=1 border=0 width="100%">
		<tr bgColor="#8080C0">
			<td width="60"><span class="basetxt"><font color="#FFFFFF">ID</td>
			<td><span class="basetxt"><font color="#FFFFFF">Process Started</td>
			<td width="120"><span class="basetxt"><font color="#FFFFFF">Operator Name</td>
			<td width="120"><span class="basetxt"><font color="#FFFFFF">Visitor Name</td>
			<td width="100"><span class="basetxt">&nbsp;</td>
		</tr>
		<?
			for ( $c = 0; $c < count( $chatsessions ); ++$c )
			{
				$session = $chatsessions[$c] ;

				$sessionlogins = ServiceChat_get_ChatSessionLogins( $dbh, $session['sessionID'] ) ;
				$date = date( "D m/d/y h:i a", $session['created'] ) ;

				$bgcolor = "#EEEEF7" ;
				if ( $c % 2 )
					$bgcolor = "#E6E6F2" ;

				// only print out if there are active chat parties
				if ( count( $sessionlogins ) > 0 )
				{
					print "
						<tr bgColor=\"$bgcolor\">
							<td><span class=\"basetxt\">$session[sessionID]</td>
							<td><span class=\"basetxt\">$date</td>
							<td><span class=\"basetxt\">$sessionlogins[admin]</td>
							<td><span class=\"basetxt\">$sessionlogins[visitor]</td>
							<td><span class=\"basetxt\"><a href=\"JavaScript:confirm_kill( $session[sessionID] )\">kill process</a></td>
						</tr>
					" ;
				}
			}
		?>
		</table>
		






		
		<?
			elseif ( $action == "consol" ):
			$admins = AdminUsers_get_AllUsers( $dbh, 0, 0, $session_setup['aspID'] ) ;
		?>
		<b>Admin Console Processes</b><br>
		<img src="../pics/icons/process.gif" width="32" height="32" border=0 alt="" align="left"> Below is a complete list of all operators and their active Operator Console status.  If there is an admin console that was left open, you may manually kill and end the process by clicking the "kill" link next to the process ID.
		<p>
		<li> <a href="processes.php?action=consol">reload list</a>
		<table cellspacing=1 cellpadding=1 border=0 width="100%">
		<tr bgColor="#8080C0">
			<td width="150"><span class="basetxt"><font color="#FFFFFF">Operator Name</td>
			<td width="80"><span class="basetxt"><font color="#FFFFFF">Login</td>
			<td><span class="basetxt"><font color="#FFFFFF">Email</td>
			<td width="100"><span class="basetxt"><font color="#FFFFFF">Online Status</td>
			<td width="100"><span class="basetxt"><font color="#FFFFFF">Console Status</td>
			<td width="120"><span class="basetxt">&nbsp;</td>
		</tr>
		<?
			for ( $c = 0; $c < count( $admins ); ++$c )
			{
				$admin = $admins[$c] ;

				$bgcolor = "#EEEEF7" ;
				if ( $c % 2 )
					$bgcolor = "#E6E6F2" ;

				$online_status = "Offline" ;
				$bgcolor_status = "#FFE8E8" ;
				if ( $admin['available_status'] == 1 )
				{
					$online_status = "Online" ;
					$bgcolor_status = "#E1FFE9" ;
				}

				$consol_status = "Closed" ;
				$bgcolor_consol = "#FFE8E8" ;
				$kill_string = "&nbsp;" ;
				if ( $admin['signal'] == 9 )
				{
					$consol_status = "Open" ;
					$kill_string = "closing console..." ;
					$bgcolor_consol = "#E1FFE9" ;
				}
				else if ( $admin['last_active_time'] > $admin_idle )
				{
					$consol_status = "Open" ;
					$kill_string = "<a href=\"JavaScript:confirm_close( $admin[userID] )\">close console</a>" ;
					$bgcolor_consol = "#E1FFE9" ;
				}

				print "
					<tr bgColor=\"$bgcolor\">
						<td><span class=\"basetxt\">$admin[name]</td>
						<td><span class=\"basetxt\">$admin[login]</td>
						<td><span class=\"basetxt\"><a href=\"mailto:$admin[email]\">$admin[email]</a></td>
						<td bgColor=\"$bgcolor_status\"><span class=\"basetxt\">$online_status</td>
						<td bgColor=\"$bgcolor_consol\"><span class=\"basetxt\">$consol_status</td>
						<td><span class=\"basetxt\">$kill_string</td>
					</tr>
				" ;
			}
		?>
		</table>

		


		<? endif ;?>

	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>