<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$sid = $action = $error = $requestid = $sessionid = "" ;
	$close_window = $focus_window = $close_window = $do_countdown = $reload_tracker = $alert = 0 ;
	$do_pull = 1 ;
	$session_admin = $HTTP_SESSION_VARS['session_admin'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : $HTTP_POST_VARS['sid'] ;
	$start = ( isset( $HTTP_GET_VARS['start'] ) ) ? $HTTP_GET_VARS['start'] : "" ;
	$reload_tracker = ( isset( $HTTP_GET_VARS['reload_tracker'] ) ) ? $HTTP_GET_VARS['reload_tracker'] : "" ;
	if ( isset( $HTTP_POST_VARS['sessionid'] ) ) { $sessionid = $HTTP_POST_VARS['sessionid'] ; }
	if ( isset( $HTTP_GET_VARS['sessionid'] ) ) { $sessionid = $HTTP_GET_VARS['sessionid'] ; }
	if ( isset( $HTTP_POST_VARS['requestid'] ) ) { $requestid = $HTTP_POST_VARS['requestid'] ; }
	if ( isset( $HTTP_GET_VARS['requestid'] ) ) { $requestid = $HTTP_GET_VARS['requestid'] ; }
	if ( isset( $HTTP_POST_VARS['l'] ) ) { $l = $HTTP_POST_VARS['l'] ; }
	if ( isset( $HTTP_GET_VARS['l'] ) ) { $l = $HTTP_GET_VARS['l'] ; }
	if ( isset( $HTTP_POST_VARS['x'] ) ) { $x = $HTTP_POST_VARS['x'] ; }
	if ( isset( $HTTP_GET_VARS['x'] ) ) { $x = $HTTP_GET_VARS['x'] ; }

	if ( !file_exists( "../web/$l/$l-conf-init.php" ) || !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: index.php" ) ;
		exit ;
	}
	include_once("../web/conf-init.php") ;
	include_once("../web/$l/$l-conf-init.php") ;
	include_once("$DOCUMENT_ROOT/system.php") ;
	include_once("$DOCUMENT_ROOT/lang_packs/$LANG_PACK.php") ;
	include_once("$DOCUMENT_ROOT/API/sql.php" ) ;
	include_once("$DOCUMENT_ROOT/API/Chat/Util.php" ) ;
	include_once("$DOCUMENT_ROOT/API/Chat/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/put.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/remove.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/update.php") ;
	include_once("$DOCUMENT_ROOT/API/Users/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Users/update.php") ;
?>
<?
	// make sure they have access to this page
	// if admin session is set, then they have access
	if ( !$session_admin[$sid]['admin_id'] )
	{
		HEADER( "location: ../index.php" ) ;
		exit ;
	}

	// initialize

	$sound_file = "cellular.wav" ;
	$winapp = ( $HTTP_SESSION_VARS['session_admin'][$sid]['winapp'] ) ? $HTTP_SESSION_VARS['session_admin'][$sid]['winapp'] : 0 ;

	// reset this to ZERO when they login so it starts the tracking of request process
	$HTTP_SESSION_VARS['session_admin'][$sid]['requests_reload'] = 0 ;

	// we use $rand to prevent loading from cached pages
	mt_srand ((double) microtime() * 1000000);
	$rand = mt_rand() ;
	$admin = AdminUsers_get_UserInfo( $dbh, $session_admin[$sid]['admin_id'], $session_admin[$sid]['aspID'] ) ;

	// if there is a kill signal, then let's close this window
	if ( $admin['signal'] == 9 )
	{
		// set the close window var, then put admin status back to normal
		$close_window = 1 ;
		// set the last activity time back so it is offline status instantly
		AdminUsers_update_LastActiveTime( $dbh, $admin['userID'], time() - 60, $sid ) ;
		AdminUsers_update_Status( $dbh, $admin['userID'], 0 ) ;
		AdminUsers_update_Signal( $dbh, $admin['userID'], 0, $session_admin[$sid]['aspID'] ) ;
	}
	else if ( ( $admin['last_active_time'] > $admin_idle ) && $start && ( $admin['session_sid'] != $sid ) )
	{
		// go ahead and login if activity time has been idel.
		// why?  this prevents so multiple login at various places... only check when
		// $start variable is passed ($start = first loading of console)
		$error = "You are logged in at another location!" ;
		$do_pull = 0 ;
	}
	else if ( $start )
	{
		// the $start variable comes from the admin page.  it passes this variable
		// so the admin console (this) knows to update status to Active on
		// initial start of console.  make sure there is no error.
		AdminUsers_update_LastActiveTime( $dbh, $admin['userID'], time(), $sid ) ;
		AdminUsers_update_Status( $dbh, $admin['userID'], 1 ) ;
		AdminUsers_update_Signal( $dbh, $admin['userID'], 0, $session_admin[$sid]['aspID'] ) ;
	}

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "reject" )
	{
		$requestinfo = ServiceChat_get_ChatRequestInfo( $dbh, $requestid ) ;
		//$sessioninfo = ServiceChat_get_ChatSessionInfo( $dbh, $sessionid ) ;
		ServiceChat_update_ChatRequestLogStatus( $dbh, $session_admin[$sid]['admin_id'], $sessionid, 3 ) ;

		// create a flag to indicate that it has been rejected
		$fp = fopen ("$DOCUMENT_ROOT/web/chatpolling/$sessionid.txt", "wb+") ;
		fclose( $fp ) ;
		// put a big number as new admin ID for now... it will be updated by visitor
		// pulling script phplive/pull/chat.php
		ServiceChat_update_TransferCall( $dbh, $requestid, 999999999, $requestinfo['deptID'] ) ;
	}
	else if ( $action == "status" )
	{
		$HTTP_SESSION_VARS['session_admin'][$sid]['available_status'] = $HTTP_GET_VARS['status'] ;
		AdminUsers_update_Status( $dbh, $admin['userID'], $HTTP_GET_VARS['status'] ) ;
		$session_admin[$sid]['close_timer'] = time() ;
	}
	else if ( $action == "kill" )
	{
		// in UNIX -9 is kill... so let's use 9 as kill signal
		AdminUsers_update_Signal( $dbh, $admin['userID'], 9, $session_admin[$sid]['aspID'] ) ;
		$do_pull = 0 ;
		$do_countdown = 1 ;
	}

	// call admin again here just incase the status is set from above
	// action == "status"... so we want the latest admin information
	$admin = AdminUsers_get_UserInfo( $dbh, $admin['userID'], $session_admin[$sid]['aspID'] ) ;

	// do chat request session adding so we know when to ring alarm
	if ( !$error )
	{
		$chat_requests = ServiceChat_get_UserChatRequests( $dbh, $admin['userID'] ) ;
		$total_requests = count( $chat_requests ) ;
		if ( $total_requests > 0 )
		{
			if ( ( $total_requests != $session_admin[$sid]['requests'] ) && !$winapp )
				$focus_window = 1 ;     // only do it if not a winapp
			$alert = 1 ;
		}
		$HTTP_SESSION_VARS['session_admin'][$sid]['requests'] = $total_requests ;
		$session_admin[$sid]['requests'] = $total_requests ;

		// set this to equal so it does not trigger the reload while calling the pull image
		$HTTP_SESSION_VARS['session_admin'][$sid]['requests_reload'] = $session_admin[$sid]['requests'] ;
	}

	// check to see if status if offline and window has been idel for a while
	// .. if offline and idel, let's close it (just incase they left the window open)
	$time_to_close = time() - ( 60 * $admin['console_close_min'] ) ;
	$minutes_left = round( ( $session_admin[$sid]['close_timer'] - $time_to_close )/60 ) ;
	if ( ( $admin['available_status'] == 0 ) && ( $session_admin[$sid]['close_timer'] < $time_to_close ) && ( $admin['session_sid'] == $sid ) )
		$close_window = 1 ;
?>
<html>
<head>
<title> Operator [ request monitor ] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="../css/base.css">

<script language="JavaScript">
<!--
	function do_countdown( counter )
	{
		document.form.ticker.value = counter ;
		if ( counter == 0 )
			location.href = "admin_requests.php?status=<? echo $admin['available_status'] ?>&sid=<? echo $sid ?>&start=1&l=<? echo $l ?>&x=<? echo $x ?>&reload_tracker=1" ;
		--counter ;
		setTimeout("do_countdown("+counter+")",1000) ;
	}

	function do_focus()
	{
		if ( <? echo $focus_window ?> )
			parent.window.focus() ;

		if ( <? echo $close_window ?> )
			do_window_close() ;
		else if ( <? echo $do_countdown ?> )
			do_countdown( <? echo $admin_idle_value ?> ) ;
		else if ( <? echo $do_pull ?> )
			parent.window.do_pull() ;

		<? if ( $reload_tracker ): ?>
			parent.window.admin_puller.location.href = "admin_puller.php?sid=<? echo $sid ?>&start=1&l=<? echo $l ?>&x=<? echo $x ?>" ;
		<? endif ; ?>
	}

	function do_logout()
	{
		if ( confirm( "Logout Admin Console?" ) )
			parent.window.location.href = "../index.php?action=logout&sid=<? echo $sid ?>&winapp=<? echo $winapp ?>" ;
	}

	function do_window_close()
	{
		if ( <? echo $winapp ?> )
			parent.window.location.href = "../index.php?action=logout&sid=<? echo $sid ?>&winapp=<? echo $winapp ?>" ;
		else
			parent.window.close() ;
	}

	// if status is Offline (0), then let's refresh this window every minute to
	// activate auto close if left idle
	<? if ( $admin['available_status'] == 0 ): ?>
	setTimeout("location.href='admin_requests.php?status=<? echo $admin['available_status'] ?>&sid=<? echo $sid ?>&l=<? echo $l ?>&x=<? echo $x ?>'",60000) ;
	<? endif ; ?>

//-->
</script>

</head>
<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" marginheight="0" marginwidth="0" topmargin="0" leftmargin="2" OnLoad="do_focus()">
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
<span class="basetxt">
<?
        if ( file_exists( "../web/$l/$LOGO" ) && $LOGO )
                $logo = "../web/$l/$LOGO" ;
        else if ( file_exists( "../web/$LOGO_ASP" ) && $LOGO_ASP )
                $logo = "../web/$LOGO_ASP" ;
        else
                $logo = "../pics/phplive_logo.gif" ;
?>
<img src="<? echo $logo ?>">
<? if ( $error ): ?>
<table cellspacing=1 cellpadding=2 border=0 width="100%">
<tr>
	<td><span class="basetxt">
	<b><big><? echo $error ?></big></b>
	<p>
	<? if ( md5( "Demo" ) != $admin['password'] ) : ?>
	To Kill the window at the other location, <a href="JavaScript:parent.window.do_kill()">Click Here</a>.
	<p>
	To cancel <a href="JavaScript:do_window_close()">click here</a>.
	<? endif ; ?>
	</td>
</tr>
</table>

<? elseif ( $action == "kill" ): ?>
<table cellspacing=1 cellpadding=2 border=0 width="100%">
<tr>
	<td><span class="basetxt">
	<b><big>Sending kill command ...</big></b>
	<p>
	<form name="form">
	Please hold while sending kill command and closing remote console...<br>
	Re-logging in <input type="text" name="ticker" size=2 maxlength=3 disabled class="input"> second(s).
	<br>
	<li> To cancel <a href="JavaScript:do_window_close()">click here</a>.
	</form>
	</span>
	</td>
</tr>
</table>


<? else: ?>
<?
	// embed sound if new request
	if ( $alert )
		print "<EMBED src=\"../sounds/$sound_file\" width=0 height=0 autostart=true loop=false>" ;
?>
<span class="smalltxt">
<br>
<font color="#707070">
Name: <b><? echo $admin['name'] ?></b> &nbsp;|&nbsp;
Status:
<?
	// for winapp
	$logout_string = "" ;
	if ( $winapp )
		$logout_string = "&nbsp; [ <a href=\"JavaScript:do_logout()\">Logout</a> ]" ;

	if ( $admin['available_status'] == 1 )
		print "<font color=\"#0000FF\"><b>Online</b></font>  [<a href=\"JavaScript:parent.window.update_status(0)\">Offline</a>] $logout_string &nbsp; <span class=\"smalltxt\"><font color=\"#FF0000\">Keep this open and minimize. </font></span></font>" ;
	else
		print "<font color=\"#FF0000\"><b>Offline</b></font> [<a href=\"JavaScript:parent.window.update_status(1)\">Online</a>] $logout_string &nbsp; window will close in $minutes_left minutes" ;
?>
</font>
<br>
<table cellspacing=1 cellpadding=2 border=0 width="100%">
<tr bgColor="#8080C0">
	<td width="50"><span class="smalltxt"><font color="#FFFFFF">Login</td>
	<td width="100"><span class="smalltxt"><font color="#FFFFFF">Department</td>
	<td><span class="smalltxt"><font color="#FFFFFF">Question</td>
	<td width="40"><span class="smalltxt">&nbsp;</td>
</tr>
<?
	for ( $c = 0; $c < count( $chat_requests ); ++$c )
	{
		$request = $chat_requests[$c] ;
		$department = AdminUsers_get_DeptInfo( $dbh, $request['deptID'], $session_admin[$sid]['aspID'] ) ;
		$question = stripslashes( $request['question'] ) ;
		$date = date( "D m/d/y h:i", $request['created'] ) ;

		$bgcolor = "#EEEEF7" ;
		if ( $c % 2 )
			$bgcolor = "#E6E6F2" ;

		print "
		<tr bgcolor=\"$bgcolor\">
			<td><span class=\"smalltxt\">$request[from_screen_name]</td>
			<td><span class=\"smalltxt\">$department[name]</td>
			<td><span class=\"smalltxt\">$question</td>
			<td nowrap><span class=\"smalltxt\">[<a href=\"JavaScript:parent.window.open_chat($request[requestID], $request[sessionID])\">Accept</a>] - [<a href=\"JavaScript:parent.window.do_reject($request[requestID], $request[sessionID])\">Busy</a>]</td>
		</tr>
		" ;
	}
?>
</table>
<? endif ; ?>

<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>