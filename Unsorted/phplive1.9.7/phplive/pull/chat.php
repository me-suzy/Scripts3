<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$sessionid = $requestid = $string = "" ;
	$session_chat = $HTTP_SESSION_VARS['session_chat'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : $HTTP_POST_VARS['sid'] ;
	if ( isset( $HTTP_POST_VARS['sessionid'] ) ) { $sessionid = $HTTP_POST_VARS['sessionid'] ; }
	if ( isset( $HTTP_GET_VARS['sessionid'] ) ) { $sessionid = $HTTP_GET_VARS['sessionid'] ; }
	if ( isset( $HTTP_POST_VARS['requestid'] ) ) { $requestid = $HTTP_POST_VARS['requestid'] ; }
	if ( isset( $HTTP_GET_VARS['requestid'] ) ) { $requestid = $HTTP_GET_VARS['requestid'] ; }

	if ( !file_exists( "../web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "../web/conf-init.php" ) )
	{
		print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting...</font>" ;
		exit ;
	}
	include_once("../web/conf-init.php") ;	
	include_once("../web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php") ;
	include_once("$DOCUMENT_ROOT/system.php") ;
	include_once("$DOCUMENT_ROOT/API/sql.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/Util.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/update.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/remove.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Chat/put.php") ;
	include_once("$DOCUMENT_ROOT/API/Users/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Logs/get.php") ;

	// update chat activity time to keep track to see if they are still chatting
	ServiceChat_update_ChatActivityTime( $dbh, $session_chat[$sid]['screen_name'], $sessionid, 0 ) ;
	ServiceChat_remove_CleanChatSessionList( $dbh ) ;
	$total_chatting = ServiceChat_get_SessionUserTotal( $dbh, $sessionid ) ;

	// print out a message if the party has left (if there is no record of the party in
	// the chatsessionlist table.
	if ( ( $total_chatting <= 1 ) && ( $HTTP_SESSION_VARS['session_chat'][$sid]['total_counter'] >= 2 ) )
	{
		if ( $HTTP_SESSION_VARS['session_chat'][$sid]['total_counter'] == 2 )
		{
			// ONLY use this lang pack if needed....we don't want to keep loading it with
			// each refresh.
			include_once("$DOCUMENT_ROOT/lang_packs/$LANG_PACK.php") ;

			// JKEEP is used as a flag to signal NOT to strip JavaScript for the admin's chat...
			// usually JavaScript is stripped so the pushed webpage does not popup.
			$string = "<STRIP_FOR_PLAIN><script language=\"JavaScript\">window.parent.window.options.stop_timer();</script><JKEEP><hr><font color=\"#FF0000\">$LANG[CHAT_PARTYLEFTSESSION]</font><hr></STRIP_FOR_PLAIN>" ;
			UtilChat_AppendToChatfile( $session_chat[$sid]['chatfile_get'], $string ) ;
			UtilChat_AppendToChatfile( $session_chat[$sid]['chatfile_transcript'], $string ) ;
			++$HTTP_SESSION_VARS['session_chat'][$sid]['total_counter'] ;
			// add the last one so it does not keep writing the message
		}
	}
	else if ( ( $total_chatting == 1 ) && ( !$HTTP_SESSION_VARS['session_chat'][$sid]['total_counter'] ) )
	{
		++$HTTP_SESSION_VARS['session_chat'][$sid]['total_counter'] ;
		// $session_chat[$sid][total_counter] is now 1
	}
	// this situation is when admin accepts chat... then there is already 2 people but
	// $session_chat[total_counter] is undefined
	else if ( ( $total_chatting == 2 ) && ( !$HTTP_SESSION_VARS['session_chat'][$sid]['total_counter'] ) )
	{
		$HTTP_SESSION_VARS['session_chat'][$sid]['total_counter'] = 1 ;
		// $session_chat[$sid][total_counter] is now 1
	}
	else if ( ( $total_chatting == 2 ) && ( $HTTP_SESSION_VARS['session_chat'][$sid]['total_counter'] <= 1 ) )
	{
		++$HTTP_SESSION_VARS['session_chat'][$sid]['total_counter'] ;
		// $session_chat[$sid][total_counter] is now 2
	}

	// let's check the polling time so we can automatically transfer the call
	// to the next operator if the current operator does not pickup
	// let's ONLY do this if one person is chatting and they are NOT admin
	if ( ( $HTTP_SESSION_VARS['session_chat'][$sid]['admin_poll_time'] < ( time() - $POLL_TIME ) ) && ( $total_chatting == 1 ) && !$session_chat[$sid]['isadmin'] && ( $HTTP_SESSION_VARS['session_chat'][$sid]['total_counter'] == 1 ) || file_exists( "$DOCUMENT_ROOT/web/chatpolling/$sessionid.txt" ) )
	{
		// delete the chatpolling flag (happens when operator rejects)
		if ( file_exists( "$DOCUMENT_ROOT/web/chatpolling/$sessionid.txt" ) )
			unlink( "$DOCUMENT_ROOT/web/chatpolling/$sessionid.txt" ) ;

		$admin = AdminUsers_get_LessLoadedDeptUser( $dbh, $session_chat[$sid]['deptid'], $HTTP_SESSION_VARS['session_chat'][$sid]['admin_poll_list'], $session_chat[$sid]['aspID'] ) ;
		// if we were able to poll another admin, then let's transfer the call...
		// if not, send the call to "leave a message" form

		if ( isset( $admin['userID'] ) )
		{
			// put request log here so it tracks if operator took the call, not took the call,
			// or reject (busy) the call
			$requestloginfo = ServiceLogs_get_SessionRequestLog( $dbh, $sessionid ) ;
			$browser = stripslashes( $requestloginfo['browser'] ) ;
			ServiceChat_put_ChatRequestLog( $dbh, $admin['userID'], $requestloginfo['deptID'], $sessionid, $requestloginfo['display_resolution'], $requestloginfo['url'], $requestloginfo['aspID'], 0, $requestloginfo['ip'], $browser ) ;

			ServiceChat_update_TransferCall( $dbh, $requestid, $admin['userID'], $session_chat[$sid]['deptid'] ) ;
			$HTTP_SESSION_VARS['session_chat'][$sid]['admin_poll_list'] .= " AND chat_admin.userID <> $admin[userID]" ;
			$HTTP_SESSION_VARS['session_chat'][$sid]['admin_poll_time'] = time() + 3 ;	// add 3 seconds extra buffer

			// update the chat info file so it saves the proper transcript data since it is now polled
			// to a new operator
			$transcript_data_file = $sessionid."_transcript_info.txt" ;
			$transcript_data = $admin['userID']."<:>".$session_chat[$sid]['visitor_name']."<:>".$session_chat[$sid]['deptid']."<:>".$session_chat[$sid]['aspID']."<:>".$session_chat[$sid]['transcript_autosave'] ;
			// override the chat info file
			$fp = fopen ("$DOCUMENT_ROOT/web/chatsessions/$transcript_data_file", "wb+") ;
			fwrite( $fp, $transcript_data, strlen( $transcript_data ) ) ;
			fclose( $fp ) ;
		}
		else
		{
			$string = "<script language=\"JavaScript\"> parent.window.location.href=\"$BASE_URL/message_box.php?l=".$session_chat[$sid]['asp_login']."&x=".$session_chat[$sid]['aspID']."&deptid=".$session_chat[$sid]['deptid']."\"; </script>" ;
			UtilChat_AppendToChatfile( $session_chat[$sid]['chatfile_get'], $string ) ;
			ServiceChat_remove_ChatRequest( $dbh, $requestid ) ;
		}
	}

	// put writing flag file if is writing
	if ( isset( $HTTP_GET_VARS['iswriting'] ) && ( $HTTP_GET_VARS['iswriting'] == 1 ) )
	{
		$fp = fopen("$DOCUMENT_ROOT/web/chatsessions/w_".$session_chat[$sid]['chatfile_put'], "a");
		fwrite( $fp, $string, strlen( $string ) ) ;
		fclose( $fp ) ;
	}
	else if ( isset( $HTTP_GET_VARS['iswriting'] ) )
	{
		// remove the write flag file
		if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/w_".$session_chat[$sid]['chatfile_put'] ) )
			unlink( "$DOCUMENT_ROOT/web/chatsessions/w_".$session_chat[$sid]['chatfile_put'] ) ;
	}
	mysql_close( $dbh['con'] ) ;

	Header( "Content-type: image/gif" ) ;
	if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/".$session_chat[$sid]['chatfile_get'] ) && !is_dir( "$DOCUMENT_ROOT/web/chatsessions/".$session_chat[$sid]['chatfile_get'] ) )
		readfile( "$DOCUMENT_ROOT/pics/empty_nodelete.gif" ) ;
	else if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/w_".$session_chat[$sid]['chatfile_get'] ) )
		readfile( "$DOCUMENT_ROOT/pics/empty_nodelete2.gif" ) ;
	else
		readfile( "$DOCUMENT_ROOT/pics/empty_nodelete3.gif" ) ;
?>