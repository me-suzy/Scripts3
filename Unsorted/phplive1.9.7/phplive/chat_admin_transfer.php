<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$isavailable = $transferred = 0 ;
	$action = $sessionid = $requestid = $userid = $deptid = $j_string = "" ;
	$session_chat = $HTTP_SESSION_VARS['session_chat'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : "" ;
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_POST_VARS['sessionid'] ) ) { $sessionid = $HTTP_POST_VARS['sessionid'] ; }
	if ( isset( $HTTP_GET_VARS['sessionid'] ) ) { $sessionid = $HTTP_GET_VARS['sessionid'] ; }
	if ( isset( $HTTP_POST_VARS['requestid'] ) ) { $requestid = $HTTP_POST_VARS['requestid'] ; }
	if ( isset( $HTTP_GET_VARS['requestid'] ) ) { $requestid = $HTTP_GET_VARS['requestid'] ; }
	if ( isset( $HTTP_POST_VARS['userid'] ) ) { $userid = $HTTP_POST_VARS['userid'] ; }
	if ( isset( $HTTP_GET_VARS['userid'] ) ) { $userid = $HTTP_GET_VARS['userid'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }

	if ( !file_exists( "web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "web/conf-init.php" ) )
	{
		print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting...</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;	
	include_once("./web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./lang_packs/$LANG_PACK.php") ;
	include_once("./API/sql.php") ;
	include_once("./API/Chat/Util.php") ;
	include_once("./API/Users/get.php") ;
	include_once("./API/Chat/get.php") ;
?>
<?
	// initialize
	// we use $rand to prevent loading from cached pages
	mt_srand ((double) microtime() * 1000000);
	$rand = mt_rand() ;
?>
<?
	// functions
?>
<?
	// conditions
	if ( $action == "transfer_verify" )
	{
		// let's make sure operator is available before we confirm
		// the transfer
		$user = AdminUsers_get_UserInfo( $dbh, $userid, $session_chat[$sid]['aspID'] ) ;
		$department = AdminUsers_get_DeptInfo( $dbh, $deptid, $session_chat[$sid]['aspID'] ) ;
		if ( ( $user['available_status'] == 1 ) && ( $user['last_active_time'] > $admin_idle ) && $department['deptID'] )
			$isavailable = 1 ;
	}
	else if ( $action == "transfer_doit" )
	{
		$transfer_operator = AdminUsers_get_UserInfo( $dbh, $userid, $session_chat[$sid]['aspID'] ) ;
		$department = AdminUsers_get_DeptInfo( $dbh, $deptid, $session_chat[$sid]['aspID'] ) ;

		$transfer_message = $LANG['CHAT_CALL_TRANSFER'] ;
		if ( !$transfer_message || ( $transfer_message == "" ) )
			$transfer_message = "Please hold while being transferred to" ;
		$string = "<STRIP_FOR_PLAIN><font color=\"$CLIENT_COLOR\">** $transfer_message <b>$transfer_operator[name], $department[name]</b>. **</font><br></STRIP_FOR_PLAIN>" ;
		// save the transfer message for current transcript
		$HTTP_SESSION_VARS['session_chat'][$sid]['transcript'] .= $string ;

		// append the transfer message to the visitor's chat session
		UtilChat_AppendToChatfile( $session_chat[$sid]['chatfile_put'], $string ) ;
		UtilChat_AppendToChatfile( $session_chat[$sid]['chatfile_transcript'], $string ) ;

		/***********************************************************
		* dump the entire chat session for the new transfer operator
		***********************************************************/
		// the new operator will pickup the file
		$new_operator_file = $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'] ;
		// set it to empty so it does not look for the file.
		// the file is now used by the transferred operator.
		$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'] = "" ;
		// set the put file to DUMP.txt so the visitor does not see messages
		// if THIS operator types a message... limit clutter
		$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'] = "DUMP.txt" ;
		UtilChat_AppendToChatfile( $new_operator_file, $session_chat[$sid]['transcript'] ) ;
		/**************** end dump ******************/

		// update the chatsessionlist table so it is updated with the new operator
		ServiceChat_update_SessionListLogin( $dbh, $sessionid, $session_chat[$sid]['screen_name'], $transfer_operator['login'] ) ;
		// put chat request for other the other operator
		ServiceChat_update_TransferCall( $dbh, $requestid, $userid, $deptid ) ;

		/************************************************************
		* perhaps in future, there will be a call transfer confirmation...
		* for now, transfer and then be done.... (similar to regular phone call transfer)
		*************************************************************/

		$j_string = preg_replace( "/'/", "&#039;", $string ) ;
		$j_string .= "<STRIP_FOR_PLAIN><font color=\"$CLIENT_COLOR\">** Call has been tranferred.  Your session has ended. **</font><br></STRIP_FOR_PLAIN>" ;
		
		$transferred = 1 ;
	}
?>
<html>
<head>
<title> Chat [admin transfer call] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="./css/base.css">

<script language="JavaScript">
<!--
	function start_timer( c )
	{
		if ( c == 0 )
			location.href = "chat_admin_transfer.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>&userid=<? echo $userid ?>&action=transfer_doit&deptid=<? echo $deptid ?>" ;
		document.counter.counter_value.value = c ;
		--c ;
		setTimeout( "start_timer("+c+")", 1000 ) ;
	}

	function transferok()
	{
		window.parent.window.do_write('<? echo $j_string ?>') ;
	}

	function do_load()
	{
		<? if ( ( $action == "transfer_verify" ) && $isavailable ): ?>
			start_timer( 10 ) ;
		<? endif ; ?>
		<? if ( $transferred ): ?>
			transferok() ;
		<? endif ; ?>
	}
//-->
</script>

</head>
<body bgColor="<? echo $CHAT_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" OnLoad="do_load()">
<center>
<br>
<span class="smalltxt">

<? if ( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'] == "" ): ?>
<big><b>This session has ended.</b></big>


<? elseif ( $isavailable && ( $action == "transfer_verify" ) ): ?>
<form name="counter">
Call transfer to <b><? echo "$user[name]" ?></b> of <b><? echo $department['name'] ?></b> in <input type="text" name="counter_value" value="" class="input" size=2> or <a href="chat_admin_transfer.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>&userid=<? echo $userid ?>&action=transfer_doit&deptid=<? echo $deptid ?>">transfer now!</a><p>
<a href="chat_admin_transfer.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>&userid=<? echo $userid ?>&rand=<? echo $rand ?>">Click Here</a> to STOP this call transfer.
</form>


<? elseif ( $action == "transfer_verify" ): ?>
Operator <b><? echo $user['name'] ?></b> is currently OFFLINE and is unavailable.  <a href="chat_admin_transfer.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>&userid=<? echo $userid ?>&rand=<? echo $rand ?>">Go Back</a>.


<? elseif ( $action == "transfer_doit" ): ?>
<big><b>Transfer Success!</b></big>


<?
	else:
	$departments = AdminUsers_get_AllDepartments( $dbh, $session_chat[$sid]['aspID'] ) ;
?>
Click on the available operator to transfer this call.
<table cellspacing=1 cellpadding=1 border=0 width="90%">
<tr>
	<form method="POST" action="chat_admin_transfer.php">
	<input type="hidden" name="action" value="transfer">
	<td align="center"><span class="smalltxt">
<?
	for ( $c = 0; $c < count( $departments ); ++$c )
	{
		$department = $departments[$c] ;
		$department_users = AdminUsers_get_AllDeptUsers( $dbh, $department['deptID'] ) ;

		print "
				<table cellspacing=1 cellpadding=2 borer=0 width=\"90%\">
				<tr>
						<td colspan=5><span class=\"smalltxt\">Department: <b>$department[name]</b><br></td>
				</tr>
				<tr bgColor=\"#000000\">
					<td width=\"46\"><span class=\"smalltxt\">&nbsp;</td>
					<td width=\"50\"><span class=\"smalltxt\"><font color=\"#FFFFFF\">Login</td>
					<td width=\"90\"><span class=\"smalltxt\"><font color=\"#FFFFFF\">Name</td>
					<td width=\"50\"><span class=\"smalltxt\"><font color=\"#FFFFFF\">Status</td>
					<td nowrap><span class=\"smalltxt\"><font color=\"#FFFFFF\">Supporting</td>
				</tr>
		" ;
		for ( $c2 = 0; $c2 < count( $department_users ); ++$c2 )
		{
			$user = $department_users[$c2] ;
			$total_sessions = ServiceChat_get_UserTotalChatSessions( $dbh, $user['name'] ) ;

			$bgcolor = "#C9C9C9" ;
			if ( $c2 % 2 )
				$bgcolor = "#DADADA" ;

			$bgcolor_status = "#FFE8E8" ;
			$status = "offline" ;
			$activity = "not available" ;
			if ( ( $user['available_status'] == 1 ) && ( $user['last_active_time'] > $admin_idle ) )
			{
				$status = "online" ;
				$activity = "$total_sessions requests" ;
				$bgcolor_status = "#E1FFE9" ;
			}

			$transfer_button = "&nbsp;" ;
			if ( $user['userID'] != $session_chat[$sid]['admin_id'] )
				$transfer_button = "<a href=\"chat_admin_transfer.php?sessionid=$sessionid&sid=$sid&requestid=$requestid&userid=$user[userID]&action=transfer_verify&deptid=$department[deptID]\"><img src=\"pics/buttons/transfer.gif\" width=\"45\" height=\"18\" alt=\"Transfer Call\" border=0></a>" ;

			print "
				<tr bgColor=\"$bgcolor\">
					<td><span class=\"smalltxt\">$transfer_button</td>
					<td><span class=\"smalltxt\">$user[login]</td>
					<td><span class=\"smalltxt\">$user[name]</td>
					<td bgColor=\"$bgcolor_status\"><span class=\"smalltxt\">$status</td>
					<td><span class=\"smalltxt\">$activity</td>
				</tr>
			" ;
		}
		print "
			</table>
		" ;
	}
?>
	</td>
	</form>
</tr>
</table>

<? endif ; ?>


<br>
<br>
</center>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>