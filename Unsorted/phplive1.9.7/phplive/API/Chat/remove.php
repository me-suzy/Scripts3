<?
	/*****  ServiceChat::remove  ***************************************
	 *
	 *  $Id: remove.php,v 1.8 2002/07/04 00:11:42 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_REMOVE_ServiceChat_LOADED ) == true )
		return ;

	$_OFFICE_REMOVE_ServiceChat_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/
	include_once( "$DOCUMENT_ROOT/API/Transcripts/put.php" ) ;

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceChat_remove_ChatRequest  ***************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$requestid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 5, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_remove_ChatRequest( &$dbh,
						$requestid )
	{
		if ( $requestid == "" )
		{
			return false ;
		}

		$query = "DELETE FROM chatrequests WHERE requestID = $requestid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return true ;
	}

	/*****  ServiceChat_remove_ChatSessionlist  ***************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$sessionid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 20, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_remove_ChatSessionlist( &$dbh,
						$sessionid )
	{
		if ( $sessionid == "" )
		{
			return false ;
		}

		$query = "DELETE FROM chatsessionlist WHERE sessionID = $sessionid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return true ;
	}

	/*****  ServiceChat_remove_ChatSession  ***************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$sessionid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 20, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_remove_ChatSession( &$dbh,
						$sessionid )
	{
		if ( $sessionid == "" )
		{
			return false ;
		}

		$query = "DELETE FROM chatsessions WHERE sessionID = $sessionid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return true ;
	}

	/*****  ServiceChat_remove_CleanChatSessionList  ******************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 5, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_remove_CleanChatSessionList ( &$dbh )
	{
		// clean it up if chat session has been idel...
		// means they logged out or connection is broken
		global $chat_timeout ;
		$now = time() - $chat_timeout ;

		$query = "DELETE FROM chatsessionlist WHERE updated < $now" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return true ;
	}

	/*****  ServiceChat_remove_ChatSessionListByScreenName  ***********
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$sessionid
	 *	$screen_name
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Jan 16, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_remove_ChatSessionListByScreenName ( &$dbh,
						$sessionid,
						$screen_name )
	{
		if ( ( $sessionid == "" ) || ( $screen_name == "" ) )
		{
			return false ;
		}

		$query = "DELETE FROM chatsessionlist WHERE sessionID = $sessionid AND screen_name = '$screen_name'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return true ;
	}

	/*****  ServiceChat_remove_CleanChatSessions  **********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 5, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_remove_CleanChatSessions ( &$dbh )
	{
		global $DOCUMENT_ROOT ;
		$sessions = ARRAY() ;

		$query = "SELECT chatsessions.* FROM chatsessions LEFT JOIN chatsessionlist ON chatsessions.sessionID = chatsessionlist.sessionID WHERE chatsessionlist.sessionID is NULL" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			while ( $data = database_mysql_fetchrow( $dbh ) )
			{
				$sessions[] = $data ;
			}

			for ( $c = 0; $c < count( $sessions ); ++$c )
			{
				$session = $sessions[$c] ;
				$query = "DELETE FROM chatsessions WHERE sessionID = $session[sessionID]" ;
				database_mysql_query( $dbh, $query ) ;

				if ( file_exists( "$DOCUMENT_ROOT/web/chatrequests/$session[initiate]" ) && $session['initiate'] )
					unlink( "$DOCUMENT_ROOT/web/chatrequests/$session[initiate]" ) ;
				if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/$session[sessionID]"."_admin.txt" ) )
					unlink( "$DOCUMENT_ROOT/web/chatsessions/$session[sessionID]"."_admin.txt" ) ;
				if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/$session[sessionID]"."_visitor.txt" ) )
					unlink( "$DOCUMENT_ROOT/web/chatsessions/$session[sessionID]"."_visitor.txt" ) ;

				if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/w_$session[sessionID]"."_admin.txt" ) )
					unlink( "$DOCUMENT_ROOT/web/chatsessions/w_$session[sessionID]"."_admin.txt" ) ;
				if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/w_$session[sessionID]"."_visitor.txt" ) )
					unlink( "$DOCUMENT_ROOT/web/chatsessions/w_$session[sessionID]"."_visitor.txt" ) ;

				if ( file_exists( "$DOCUMENT_ROOT/web/chatpolling/$session[sessionID]".".txt" ) )
					unlink( "$DOCUMENT_ROOT/web/chatpolling/$session[sessionID]".".txt" ) ;
				if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/$session[sessionID]"."_transcript.txt" ) && file_exists( "$DOCUMENT_ROOT/web/chatsessions/$session[sessionID]"."_transcript_info.txt" ) )
				{
					$transcript_info_array = file( "$DOCUMENT_ROOT/web/chatsessions/$session[sessionID]"."_transcript_info.txt" ) ;
					$transcript_data_array = file( "$DOCUMENT_ROOT/web/chatsessions/$session[sessionID]"."_transcript.txt" ) ;
					$transcript_info = $transcript_info_array[0] ;
					$transcript_data = $transcript_data_array[0] ;
					// format: adminid<:>visitorname<:>deptid<:>aspid<:>--autosaveflag--
					LIST( $userid, $from_screen_name, $deptid, $aspid, $autosave_flag ) = explode( "<:>", $transcript_info ) ;
					if ( $autosave_flag == "autosave_transcript" )
						ServiceTranscripts_put_ChatTranscript( $dbh, $session['sessionID'], $transcript_data, $userid, $from_screen_name, $deptid, $aspid ) ;
					unlink( "$DOCUMENT_ROOT/web/chatsessions/$session[sessionID]"."_transcript.txt" ) ;
					unlink( "$DOCUMENT_ROOT/web/chatsessions/$session[sessionID]"."_transcript_info.txt" ) ;
				}
			}
		}
		return true ;
	}

	/*****  ServiceChat_remove_CleanChatRequests  **********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 8, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_remove_CleanChatRequests ( &$dbh )
	{
		$sessions = ARRAY() ;

		$query = "SELECT chatrequests.* FROM chatrequests LEFT JOIN chatsessions ON chatsessions.sessionID = chatrequests.sessionID WHERE chatsessions.sessionID is NULL" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			while ( $data = database_mysql_fetchrow( $dbh ) )
			{
				$sessions[] = $data ;
			}

			for ( $c = 0; $c < count( $sessions ); ++$c )
			{
				$session = $sessions[$c] ;
				$query = "DELETE FROM chatrequests WHERE sessionID = $session[sessionID]" ;
				database_mysql_query( $dbh, $query ) ;
			}
		}
		return true ;
	}

?>