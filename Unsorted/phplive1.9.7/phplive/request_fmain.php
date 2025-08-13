<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$page = $action = $deptid = $page = $error = $userid = $resolution = $datetime = "" ;
	$remote_addr = $HTTP_SERVER_VARS['REMOTE_ADDR'] ;
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }
	if ( isset( $HTTP_POST_VARS['page'] ) ) { $page = $HTTP_POST_VARS['page'] ; }
	if ( isset( $HTTP_GET_VARS['page'] ) ) { $page = $HTTP_GET_VARS['page'] ; }
	if ( isset( $HTTP_POST_VARS['l'] ) ) { $l = $HTTP_POST_VARS['l'] ; }
	if ( isset( $HTTP_GET_VARS['l'] ) ) { $l = $HTTP_GET_VARS['l'] ; }
	if ( isset( $HTTP_POST_VARS['x'] ) ) { $x = $HTTP_POST_VARS['x'] ; }
	if ( isset( $HTTP_GET_VARS['x'] ) ) { $x = $HTTP_GET_VARS['x'] ; }
	if ( isset( $HTTP_POST_VARS['sessionid'] ) ) { $sessionid = $HTTP_POST_VARS['sessionid'] ; }
	if ( isset( $HTTP_GET_VARS['sessionid'] ) ) { $sessionid = $HTTP_GET_VARS['sessionid'] ; }
	if ( isset( $HTTP_POST_VARS['requestid'] ) ) { $requestid = $HTTP_POST_VARS['requestid'] ; }
	if ( isset( $HTTP_GET_VARS['requestid'] ) ) { $requestid = $HTTP_GET_VARS['requestid'] ; }
	if ( isset( $HTTP_POST_VARS['ip'] ) ) { $ip = $HTTP_POST_VARS['ip'] ; }
	if ( isset( $HTTP_GET_VARS['ip'] ) ) { $ip = $HTTP_GET_VARS['ip'] ; }
	if ( isset( $HTTP_POST_VARS['userid'] ) ) { $userid = $HTTP_POST_VARS['userid'] ; }
	if ( isset( $HTTP_GET_VARS['userid'] ) ) { $userid = $HTTP_GET_VARS['userid'] ; }
	if ( isset( $HTTP_POST_VARS['question'] ) ) { $question = $HTTP_POST_VARS['question'] ; }
	if ( isset( $HTTP_GET_VARS['question'] ) ) { $question = $HTTP_GET_VARS['question'] ; }

	if ( !file_exists( "web/$l/$l-conf-init.php" ) || !file_exists( "web/conf-init.php" ) || !isset( $HTTP_SESSION_VARS['session_chat'] ) )
	{
		print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting... [request_fmain.php]</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;
	include_once("./web/$l/$l-conf-init.php") ;
	include_once("$DOCUMENT_ROOT/web/VERSION_KEEP.php") ;
	include_once("$DOCUMENT_ROOT/system.php") ;
	include_once("$DOCUMENT_ROOT/lang_packs/$LANG_PACK.php") ;
	include_once("$DOCUMENT_ROOT/API/sql.php") ;
	include_once("$DOCUMENT_ROOT/API/Util.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/Util.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/put.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/remove.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/update.php") ;
	include_once("$DOCUMENT_ROOT/API/Users/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Users/update.php") ;
	include_once("$DOCUMENT_ROOT/API/Logs/remove.php") ;
	include_once("$DOCUMENT_ROOT/API/Footprint_unique/remove.php") ;
	include_once("$DOCUMENT_ROOT/API/ASP/get.php") ;
?>
<?
	// initialize
	// this is used to generate a unique chat session file. it is used to reference
	// this new chat session.
	$sid = time() ;

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
	{
		$text_width = "25" ;
		$text_width_question = "60" ;
	}
	else
	{
		$text_width = "15" ;
		$text_width_question = "30" ;
	}

	$session_ended = $session_busy = 0 ;
	// update all admins status to not available if they have been idle
	AdminUsers_update_IdleAdminStatus( $dbh, $admin_idle ) ;
	// lets delete old transcripts if deptid is passed
	ServiceLogs_remove_DeptExpireTranscripts( $dbh, $deptid, $x ) ;
	// delete old unique footprints used for initiate request tracking
	ServiceFootprintUnique_remove_IdleFootprints( $dbh, $x ) ;

	// check for restricted only domains
	$url_restriction_string = "" ;
	if ( file_exists( "./web/$l/url_restriction.txt" ) )
	{
		$url_restriction_string_array = file( "./web/$l/url_restriction.txt" ) ;
		$url_restriction_string = $url_restriction_string_array[0] ;
	}
	$from_page = isset( $HTTP_SERVER_VARS['HTTP_REFERER'] ) ? $HTTP_SERVER_VARS['HTTP_REFERER'] : "" ;
	if ( !$from_page )
		$from_page = $page ;

	preg_match( "/^(http:\/\/)?([^\/]+)/i", $from_page, $matches ) ;
	// check to see if domain is internal (such as localhost). if it is, skip the
	// second layer of domain name check (taking out www. from www.domain.com)
	$domain = isset( $matches[2] ) ? $matches[2] : "" ;
	if ( preg_match( "/\./", $domain ) )
	{
		preg_match( "/[^\.\/]+\.[^\.\/]+$/", $domain, $matches ) ;
		$domain = $matches[0] ;
	}
	if ( !preg_match( "/$domain/", $url_restriction_string ) && ( $url_restriction_string != "" ) )
	{
		print "
		<html><head>
		<title>RESTRICTION ERROR</title>
		<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
		</head><body>
		<h1>RESTRICTED</h1>
		This service is restricted.  This website you just came from is not authorized to access this service.<p>
		<hr>
		<address>$LANG[DEFAULT_BRANDING] &copy; OSI Codes Inc.</address>
		</body></html>
		" ;
		exit ;
	}
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "request" )
	{
		// check for admins online and get the less loaded admin (has the fewest chat requests)
		$admin = AdminUsers_get_LessLoadedDeptUser( $dbh, $deptid, "", $x ) ;
		$aspinfo = AdminASP_get_UserInfo( $dbh, $x ) ;
		$deptinfo = AdminUsers_get_DeptInfo( $dbh, $deptid, $x ) ;

		if ( isset( $admin['userID'] ) && ( $admin['available_status'] == 1 ) && ( $admin['last_active_time'] > $admin_idle ) && ( $admin['aspID'] == $aspinfo['aspID'] ) )
		{
			// Use $sid as part of chatfile name so it's more difficult to locate other chat files.
			$chatfile = $sid ;

			$resolution = "" ;
			if( $HTTP_POST_VARS['display_width'] && $HTTP_POST_VARS['display_height'] )
				$resolution = "$HTTP_POST_VARS[display_width] x $HTTP_POST_VARS[display_height]" ;

			// let's tack on <$x> char for screen name... WHY?
			// the <$sid> is so there can be multiple same login used.. weird thing..
			// html tags must begin with letter... hence the "o"
			$from_screen_name = "<o$sid>$HTTP_POST_VARS[from_screen_name]" ;
			$question = strip_tags( $HTTP_POST_VARS['question'] ) ;

			// the zero (0) at the put_ChatSession is a flag to tell if the chatsession has
			// been initiated by admin or by visitor.  0 is visitor, ip or php session var is
			// used if initiated by operator admin.
			$sessionid = ServiceChat_put_ChatSession( $dbh, $from_screen_name, 0 ) ;
			$requestid = ServiceChat_put_ChatRequest( $dbh, $admin['userID'], $deptid, $from_screen_name, $sessionid, $resolution, $HTTP_POST_VARS['datetime'], $page, $aspinfo['aspID'], $question, 1, $remote_addr, $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) ;
			ServiceChat_put_ChatSessionList( $dbh, $from_screen_name, $sessionid ) ;

			if ( $sessionid && $requestid )
			{
				// initialize session settings
				UtilChat_InitializeChatSession( $sid, " AND chat_admin.userID <> $admin[userID]", $from_screen_name, $admin['name'], $from_screen_name, $admin['userID'], $deptid, "", $aspinfo['aspID'], $aspinfo['login'], 0 ) ;

				// chat files goes like:
				// [sessionid]_[visitor OR admin].txt
				$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'] = $sessionid."_admin.txt" ;
				$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'] = $sessionid."_visitor.txt" ;
				$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_transcript'] = $sessionid."_transcript.txt" ;
				$HTTP_SESSION_VARS['session_chat'][$sid]['transcript_autosave'] = "" ;
				// now let's create a file that holds the crucial chat session data
				// format: adminid<:>visitorname<:>deptid<:>aspid<:>--autosaveflag--
				$autosave_flag = "" ;
				if ( $deptinfo['transcript_save'] )
					$HTTP_SESSION_VARS['session_chat'][$sid]['transcript_autosave'] = "autosave_transcript" ;
				$transcript_data_file = $sessionid."_transcript_info.txt" ;
				$transcript_data = $admin['userID']."<:>".$from_screen_name."<:>".$deptid."<:>".$aspinfo['aspID']."<:>".$HTTP_SESSION_VARS['session_chat'][$sid]['transcript_autosave'] ;

				if ( !file_exists( "./web/chatsessions/".$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'] ) )
				{
					$date = date( "D m/d/y h:i a", time() ) ;
					$greeting = preg_replace( "/%%user%%/", $from_screen_name, Util_Format_ConvertSpecialChars( $deptinfo['greeting'] ) ) ;
					$greeting = preg_replace( "/%%date%%/", $date, $greeting ) ;
					$greeting = preg_replace( "/([\r\n])/", "", nl2br( $greeting ) ) ;

					// the <STRIP_FOR_PLAIN> is a marker.  anything from <STRIP_FOR_PLAIN> to
					// </STRIP_FOR_PLAIN> will be stripped during plain saving of chat
					// transcript.  the plain chat transcript is used for searching.  the
					// formatted transcript is the orignal transcript, with colors, javascript,
					// tags and all that.  we do not want to do a search agains that formatted
					// file.. has more data then we really need to search against.
					$string = "<html><head><title> Chat [dynamic box] </title><link rel=\"Stylesheet\" href=\"$BASE_URL/css/base.css\"></head><body bgColor=\"$CHAT_BOX_BACKGROUND\" text=\"$CHAT_BOX_TEXT\" link=\"$LINK_COLOR\" alink=\"$ALINK_COLOR\" vlink=\"$VLINK_COLOR\" marginheight=2 marginwidth=2 topmargin=2 leftmargin=2><span class=\"basetxt\"> <hr><STRIP_FOR_PLAIN>$greeting</STRIP_FOR_PLAIN><hr><span class=\"smalltxt\"><i>\"$question\"</i></span><hr>" ;
					UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'], $string ) ;
					UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'], $string ) ;
					UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_transcript'], $string ) ;
					UtilChat_AppendToChatfile( $transcript_data_file, $transcript_data ) ;

					// let's remove the NULL.txt file (this file is a central dump location
					// for messages AFTER the operator has transferred the call to another department.
					if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/DUMP.txt" ) )
						unlink( "$DOCUMENT_ROOT/web/chatsessions/DUMP.txt" ) ;
				}
			}
			else
				$error = $LANG['CHAT_NOTCREATE'] ;
		}
		else
		{
			HEADER( "location: message_box.php?l=$l&x=$x&deptid=$deptid" ) ;
			exit ;
		}
	}
	else if ( $action == "initiate" )
	{
		// first, let's see if they are already on support (initiated by another admin or
		// they request support)... if so, we don't want to initiate again.
		$ip_requestinfo = ServiceChat_get_IPChatRequestInfo( $dbh, $x, $ip ) ;
		
		$page = urldecode( $page ) ;
		if ( isset( $ip_requestinfo['status'] ) && $ip_requestinfo['status'] )
			$session_busy = 1 ;
		else
		{
			$admin = AdminUsers_get_UserInfo( $dbh, $userid, $x ) ;
			$aspinfo = AdminASP_get_UserInfo( $dbh, $x ) ;
			$deptinfo = AdminUsers_get_DeptInfo( $dbh, $deptid, $x ) ;

			// we don't check for online or idle status because the chat is being initiated.  so
			// operator can be offline status and still initiate chat.
			if ( $admin['aspID'] == $aspinfo['aspID'] )
			{
				// Use $sid as part of chatfile name so it's more difficult to locate other chat files.
				$chatfile = $sid ;

				$from_screen_name = "Visitor-" . substr( $sid, 7, strlen( $sid ) ) ;
				$question = urldecode( $question ) ;

				$sessionid = ServiceChat_put_ChatSession( $dbh, $admin['name'], $ip ) ;
				$requestid = ServiceChat_put_ChatRequest( $dbh, $admin['userID'], $deptid, $from_screen_name, $sessionid, $resolution, $datetime, $page, $x, $question, 4, $ip, "" ) ;
				ServiceChat_put_ChatSessionList( $dbh, $admin['name'], $sessionid ) ;

				if ( $sessionid && $requestid )
				{
					// chat files goes like:
					// [sessionid]_[visitor OR admin].txt
					$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'] = $sessionid."_admin.txt" ;
					$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'] = $sessionid."_visitor.txt" ;
					$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_transcript'] = $sessionid."_transcript.txt" ;
					$HTTP_SESSION_VARS['session_chat'][$sid]['transcript_autosave'] = "" ;
					// now let's create a file that holds the crucial chat session data
					// format: adminid<:>visitorname<:>deptid<:>aspid<:>--autosaveflag--
					if ( $deptinfo['transcript_save'] )
						$HTTP_SESSION_VARS['session_chat'][$sid]['transcript_autosave'] = "autosave_transcript" ;
					$transcript_data_file = $sessionid."_transcript_info.txt" ;
					$transcript_data = $admin['userID']."<:><initiated>$from_screen_name<:>".$deptid."<:>$x<:>".$HTTP_SESSION_VARS['session_chat'][$sid]['transcript_autosave'] ;

					if ( !file_exists( "./web/chatsessions/".$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'] ) )
					{
						$string = "<html><head><title> Chat [dynamic box] </title><link rel=\"Stylesheet\" href=\"$BASE_URL/css/base.css\"></head><body bgColor=\"$CHAT_BOX_BACKGROUND\" text=\"$CHAT_BOX_TEXT\" link=\"$LINK_COLOR\" alink=\"$ALINK_COLOR\" vlink=\"$VLINK_COLOR\" marginheight=2 marginwidth=2 topmargin=2 leftmargin=2><span class=\"basetxt\"><STRIP_FOR_PLAIN><b><font color=\"$ADMIN_COLOR\">$admin[name]</font> : $question</b><br></STRIP_FOR_PLAIN>" ;
						UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'], $string ) ;
						UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'], $string ) ;
						UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_transcript'], $string ) ;
						UtilChat_AppendToChatfile( $transcript_data_file, $transcript_data ) ;

						UtilChat_InitializeChatSession( $sid, "", $admin['name'], $admin['name'], $from_screen_name, $admin['userID'], $deptid, $ip, $x, $l, 1 ) ;

						// now let's put the chat request so the visitor is notified.  we use a flat text
						// file instead of database because it is much faster access time then calling
						// db just to get request.
						$fp = fopen( "$DOCUMENT_ROOT/web/chatrequests/$ip", "wb+" ) ;
						fwrite( $fp, $requestid, strlen( $requestid ) ) ;
						fclose( $fp ) ;

						// let's remove the NULL.txt file (this file is a central dump location
						// for messages AFTER the operator has transferred the call to another department.
						if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/DUMP.txt" ) )
							unlink( "$DOCUMENT_ROOT/web/chatsessions/DUMP.txt" ) ;
					}
				}
				else
					$error = $LANG['CHAT_NOTCREATE'] ;
			}
			else
			{
				HEADER( "location: message_box.php?l=$l&x=$x&deptid=$deptid" ) ;
				exit ;
			}
		}
	}
	elseif ( ( $action == "initiate_accept" ) && file_exists( "$DOCUMENT_ROOT/web/chatrequests/$remote_addr" ) && $remote_addr )
	{
		$requestarray = file( "$DOCUMENT_ROOT/web/chatrequests/$remote_addr" ) ;
		$requestinfo = ServiceChat_get_ChatRequestInfo( $dbh, $requestarray[0] ) ;
		$aspinfo = AdminASP_get_UserInfo( $dbh, $requestinfo['aspID'] ) ;
		$requestid = $requestinfo['requestID'] ;
		$sessionid = $requestinfo['sessionID'] ;

		// now remove the initiate file so it does not popup chat box again if they decide to browse to
		// another webpage.
		unlink( "$DOCUMENT_ROOT/web/chatrequests/$remote_addr" ) ;
		if ( isset( $requestinfo['requestID'] ) && $requestinfo['requestID'] )
		{
			$admin = AdminUsers_get_UserInfo( $dbh, $requestinfo['userID'], $aspinfo['aspID'] ) ;
			$sessioninfo = ServiceChat_get_ChatSessionInfo( $dbh, $requestinfo['sessionID'] ) ;
			$department = AdminUsers_get_DeptInfo( $dbh, $requestinfo['deptID'], $aspinfo['aspID'] ) ;
			ServiceChat_put_ChatSessionList( $dbh, $requestinfo['from_screen_name'], $requestinfo['sessionID'] ) ;

			// chat files goes like:
			// [sessionid]_[visitor OR admin].txt
			$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'] = $sessioninfo['sessionID']."_visitor.txt" ;
			$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'] = $sessioninfo['sessionID']."_admin.txt" ;
			$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_transcript'] = $sessionid."_transcript.txt" ;

			$string = "<STRIP_FOR_PLAIN><font color=\"$CLIENT_COLOR\">** <b>$requestinfo[from_screen_name]</b> has joined. **</font><br></STRIP_FOR_PLAIN>" ;
			UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'], $string ) ;
			UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'], $string ) ;
			UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_transcript'], $string ) ;

			UtilChat_InitializeChatSession( $sid, "", $requestinfo['from_screen_name'], $admin['name'], $requestinfo['from_screen_name'], $requestinfo['userID'], $requestinfo['deptID'], "", $aspinfo['aspID'], $aspinfo['login'], 0 ) ;
		}
		else
			$session_ended = 1 ;
	}
	elseif ( $action == "accept" )
	{
		$admin = AdminUsers_get_UserInfo( $dbh, $userid, $x ) ;
		$requestinfo = ServiceChat_get_ChatRequestInfo( $dbh, $requestid ) ;

		// if the request still exist, then let's go ahead and start the session
		// if NOT exist (party has left or they were taken to "leave a message" form),
		// then let's put a message that the session has ended
		if ( isset( $requestinfo['requestID'] ) && $requestinfo['requestID'] )
		{
			$sessioninfo = ServiceChat_get_ChatSessionInfo( $dbh, $requestinfo['sessionID'] ) ;
			$department = AdminUsers_get_DeptInfo( $dbh, $requestinfo['deptID'], $x ) ;
			ServiceChat_put_ChatSessionList( $dbh, $admin['name'], $requestinfo['sessionID'] ) ;
			ServiceChat_update_ChatRequestLogStatus( $dbh, $userid, $requestinfo['sessionID'], 1 ) ;

			// chat files goes like:
			// [sessionid]_[visitor OR admin].txt
			$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'] = $sessioninfo['sessionID']."_visitor.txt" ;
			$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'] = $sessioninfo['sessionID']."_admin.txt" ;
			$HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_transcript'] = $sessionid."_transcript.txt" ;

			$string = "<STRIP_FOR_PLAIN><font color=\"$CLIENT_COLOR\">** $LANG[CHAT_REQUEST_NOWSPEAKINGWITH] <b>$admin[name]</b>, <b>$department[name]</b>. **</font><br></STRIP_FOR_PLAIN>" ;
			UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_put'], $string ) ;
			UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_get'], $string ) ;
			UtilChat_AppendToChatfile( $HTTP_SESSION_VARS['session_chat'][$sid]['chatfile_transcript'], $string ) ;

			UtilChat_InitializeChatSession( $sid, "", $admin['name'], $admin['name'], $sessioninfo['screen_name'], $admin['userID'], $requestinfo['deptID'], "", $x, $l, 1 ) ;
		}
		else
			$session_ended = 1 ;
	}
	else
	{
		// do the cleaning of the chat database and chat session txt files
		// of old requests and sessions.
		ServiceChat_remove_CleanChatSessionList( $dbh ) ;
		ServiceChat_remove_CleanChatSessions( $dbh ) ;
		ServiceChat_remove_CleanChatRequests( $dbh ) ;

		// let's check to see if department ID is passed ($deptid).
		// if it is, then we want to see if anyone is available for that
		// department.  if not, let's send the visitor to the
		// leave a message area.
		$department = AdminUsers_get_DeptInfo( $dbh, $deptid, $x ) ;
		if ( isset( $department['deptID'] ) )
		{
			$total = AdminUsers_get_TotalDepartmentUsersOnline( $dbh, $deptid ) ;
			// the $page == "message" is forced because it is viewing example
			// of the page (from management department area in setup area)
			if ( !$total || ( $page == "message" ) )
			{
				HEADER( "location: message_box.php?l=$l&x=$x&deptid=$deptid" ) ;
				exit ;
			}
		}
		else
		{
			// check to see if only ONE department... if it is, then let's see if
			// anyone is online.  if no one from that ONE department is online,
			// let's take the visitor to leave a message area.
			$departments = AdminUsers_get_AllDepartments( $dbh, $x ) ;
			$total = count( $departments ) ;
			if ( count( $departments ) == 1 )
			{
				if ( !$total = AdminUsers_get_TotalDepartmentUsersOnline( $dbh, $departments[0]['deptID'] ) )
				{
					$location = "message_box.php?l=$l&x=$x&deptid=".$departments[0]['deptID'] ;
					HEADER( "location: $location" ) ;
					exit ;
				}
				else
					$deptid = $departments[0]['deptID'] ;
			}
		}
	}
?>
<html>
<head>
<title> <? echo $LANG['TITLE_SUPPORTREQUEST'] ?> </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="./css/base.css">

<script language="JavaScript">
<!--
	var win_width = window.screen.availWidth ;
	var win_height = window.screen.availHeight ;

	var now = new Date() ;
	var day = now.getDate() ;
	var time = ( now.getMonth() + 1 ) + '/' + now.getDate() + '/' +  now.getYear() + ' ' ;

	var hours = now.getHours() ;
	var minutes = now.getMinutes() ;
	var seconds = now.getSeconds() ;

	if (hours > 12){
		time += hours - 12 ;
	}  else
	if (hours > 10){
		time += hours ;
	} else
	if (hours > 0){
		time += "0" + hours ;
	} else
		time = "12" ;

	time += ((minutes < 10) ? ":0" : ":") + minutes ;
	time += ((seconds < 10) ? ":0" : ":") + seconds ;
	time += (hours >= 12) ? " P.M." : " A.M." ;

	function do_submit()
	{
		var dept_checked = 0 ;
		
		if ( document.form.deptid.value )
			dept_checked = 1 ;
		else
		{
			for( c = 0; c < document.form.deptid.length; ++c )
			{
				if ( document.form.deptid[c].checked )
					dept_checked = 1 ;
			}
		}

		if ( ( document.form.from_screen_name.value == "" ) || ( document.form.question.value == "" ) || ( dept_checked == 0 ) )
		{
			alert( "<? echo $LANG['MESSAGE_BOX_JS_A_ALLFIELDSSUP'] ?>" ) ;
		}
		else
		{
			document.form.display_width.value = win_width ;
			document.form.display_height.value = win_height ;
			document.form.datetime.value = time ;
			document.form.submit() ;
		}
	}

	function open_chat()
	{
		<? if ( $sessionid && $requestid && !$session_ended ): ?>
			url = "chat.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&userid=<? echo $admin['userID'] ?>&requestid=<? echo $requestid ?>" ;
			location.href = url ;
			parent.window.focus() ;
		<? elseif ( $session_ended ): ?>
			alert( "Party has left and chat session has ended.  Window will now close." ) ;
			parent.window.close() ;
		<? elseif ( $session_busy ): ?>
			alert( "Party is currently on another support.  Can't initiate.  Window will now close." ) ;
			parent.window.close() ;
		<? endif ; ?>
	}
//-->
</script>

</head>
<body bgColor="<? echo $CHAT_REQUEST_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" OnLoad="open_chat()">

<? if ( ( $action != "accept" ) && ( $action != "initiate" ) && ( $action != "initiate_accept" ) ): ?>
<?
	if ( file_exists( "web/$l/$LOGO" ) && $LOGO )
		$logo = "web/$l/$LOGO" ;
	else if ( file_exists( "web/$LOGO_ASP" ) && $LOGO_ASP )
		$logo = "web/$LOGO_ASP" ;
	else
		$logo = "pics/phplive_logo.gif" ;
?>
<img src="<? echo $logo ?>">
<? endif ; ?>

<?
	// this page will call itself with action request. if there is error,
	// then print it out and ask them to try again.
	if ( $action == "request" ):
?>
<br><br>
<table cellspacing=0 cellpadding=4 border=0>
<tr>
	<td><span class="basetxt"><font color="#FF0000">
		<big><? echo $error ?></font></big>
		<p>
		<? if ( $error ): ?>
		<a href="JavaScript:JavaScript:history.go(-1)"><? echo $LANG['WORD_BACK'] ?></a>
		<? endif ; ?>
	</td>
</tr>
</table>




<?
	// if comming from admin area (action = accept), then we don't
	// want to desplay the login screen.  the page will jump
	// to chat window automatically.
	elseif ( ( $action == "accept" ) || ( $action == "initiate" ) || ( $action == "initiate_accept" ) ):
?>








<?
	// default view.  show login screen and all admins.
	else:
	$isonline = 0 ;
?>
<form method="POST" action="request_fmain.php" name="form">
<input type="hidden" name="action" value="request">
<input type="hidden" name="display_width" value="">
<input type="hidden" name="display_height" value="">
<input type="hidden" name="datetime" value="">
<input type="hidden" name="x" value="<? echo $x ?>">
<input type="hidden" name="l" value="<? echo $l ?>">
<input type="hidden" name="page" value="<? echo $page ?>">
<? include("$DOCUMENT_ROOT/API/Users/cp.php") ; ?>
<table cellspacing=0 cellpadding=4 border=0>
<tr>
	<td><span class="basetxt">
		<big><b><? echo $LANG['CHAT_REQUEST_TITLE'] ?></b></big><p>

		<?
			/*****************************************
			* if $deptid is passed, ONLY show that
			* department information and option
			*****************************************/
			$department = AdminUsers_get_DeptInfo( $dbh, $deptid, $x ) ;
			if ( $department['deptID'] ):
			if ( $total = AdminUsers_get_TotalDepartmentUsersOnline( $dbh, $deptid ) )
				$isonline = 1 ;
			print "<input type=\"hidden\" name=\"deptid\" value=\"$department[deptID]\">" ;
		?>


		<?
			/*****************************************
			* if no deptid is passed, let's show the
			* visitor ALL the available departments
			*****************************************/
			else:
			$departments = AdminUsers_get_AllDepartments( $dbh, $x ) ;
		?>
		<font color="#FF0000">*</font> <? echo $LANG['CHAT_REQUEST_SELECTDEPT'] ?><br>
		<table cellspacing=0 cellpadding=2 border=0>
		<?
			for ( $c = 0; $c < count( $departments ); ++$c )
			{
				$department = $departments[$c] ;

				$status_string = "<td><img src=\"pics/status_offline.gif\" width=\"13\" height=\"13\" border=0 alt=\"Offline\"></td><td><span class=\"basetxt\">(Offline)</td>" ;
				if ( $total = AdminUsers_get_TotalDepartmentUsersOnline( $dbh, $department['deptID'] ) )
				{
					$status_string = "<td><img src=\"pics/status_online.gif\" width=\"13\" height=\"13\" border=0 alt=\"Online!\"></td><td><span class=\"basetxt\">(Online!)</td>" ;
					$isonline = 1 ;
				}

				print "<tr><td><input type=\"radio\" value=\"$department[deptID]\" name=\"deptid\"></td><td><span class=\"basetxt\"> $department[name]</td>$status_string</tr>" ;
			}
		?>
		</table>
		<? endif ; ?>

		<p>
		<? if ( !$isonline ): ?>
			<input type="hidden" name="from_screen_name" value="offline">
			<input type="hidden" name="question" value="offline">
		<? else: ?>
			<font color="#FF0000">*</font> <? echo $LANG['CHAT_REQUEST_INPUTSCREENNAME'] ?><br>
			<font size=2><input type="text" name="from_screen_name" size="<? echo $text_width ?>" maxlength="15" class="input" value="<?= isset( $HTTP_SERVER_VARS['HTTP_USER'] ) ? $HTTP_SERVER_VARS['HTTP_USER'] : "" ?>"></font>
			<p>
			<font color="#FF0000">*</font> <?= ( $LANG['CHAT_REQUEST_QUESTION'] ) ? $LANG['CHAT_REQUEST_QUESTION'] : "What is your question?" ?><br>
			<font size=2><input type="text" name="question" size="<? echo $text_width_question ?>" maxlength="150" class="input"></font>
		<? endif ; ?>
		<p>
		<a href="JavaScript:do_submit()"><img src="pics/buttons/submit.gif" border=0></a>
		<br>
	</td>
</tr>
</table>
</form>
<p>
<!-- DO NOT REMOVE THE COPYRIGHT NOTICE OF "&nbsp; OSI Codes Inc." -->
&nbsp; <span class="smalltxt"><? echo $LANG['DEFAULT_BRANDING'] ?> v<? echo $PHPLIVE_VERSION ?> &copy; OSI Codes Inc.</span>



<? endif ; ?>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>