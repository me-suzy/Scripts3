<?
	/*****  ServiceChat::get  **********************************
	 *
	 *  $Id: get.php,v 1.7 2002/08/04 07:40:05 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_GET_ServiceChat_LOADED ) == true )
		return ;

	$_OFFICE_GET_ServiceChat_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceChat_get_UserChatRequests  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 10, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_UserChatRequests( &$dbh,
						$userid )
	{
		if ( $userid == "" )
		{
			return false ;
		}

		$chat_requests = ARRAY() ;

		$query = "SELECT * FROM chatrequests WHERE userID = '$userid' AND status = 1" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$chat_requests[] = $data ;
			}
			return $chat_requests ;
		}
		return false ;
	}

	/*****  ServiceChat_get_UserTotalChatRequests  *******************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$total ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks					Jan 6, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_UserTotalChatRequests( &$dbh,
						$userid )
	{
		if ( $userid == "" )
		{
			return false ;
		}

		$query = "SELECT count(*) AS total FROM chatrequests WHERE userID = '$userid' AND status = 1" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  ServiceChat_get_UserTotalChatSessions  *******************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$login
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$total ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks					Jan 10, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_UserTotalChatSessions( &$dbh,
						$login )
	{
		if ( $login == "" )
		{
			return false ;
		}

		$query = "SELECT count(*) AS total FROM chatsessionlist WHERE screen_name = '$login'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  ServiceChat_get_ChatRequestInfo  *************************
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
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 10, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_ChatRequestInfo( &$dbh,
						$requestid )
	{
		if ( $requestid == "" )
		{
			return false ;
		}

		$query = "SELECT * FROM chatrequests WHERE requestID = $requestid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  ServiceChat_get_ChatSessionInfo  *************************
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
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 10, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_ChatSessionInfo( &$dbh,
						$sessionid )
	{
		if ( $sessionid == "" )
		{
			return false ;
		}

		$query = "SELECT * FROM chatsessions WHERE sessionID = $sessionid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  ServiceChat_get_UserChatRejects  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$screen_name
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				March 8, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_UserChatRejects( &$dbh,
						$screen_name )
	{
		if ( $screen_name == "" )
		{
			return false ;
		}

		$query = "SELECT * FROM chatrequests WHERE from_screen_name = '$from_screen_name' AND status = 0 LIMIT 1" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  ServiceChat_get_SessionUserTotal  *************************
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
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				March 8, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_SessionUserTotal( &$dbh,
						$sessionid )
	{
		if ( $sessionid == "" )
		{
			return false ;
		}

		$query = "SELECT count(*) AS total FROM chatsessionlist WHERE sessionID = $sessionid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  ServiceChat_get_ChatSessions  *******************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$total ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks					Jan 20, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_ChatSessions( &$dbh,
							$aspid )
	{
		if ( $aspid == "" )
		{
			return false ;
		}

		$sessions = ARRAY() ;

		$query = "SELECT * FROM chatsessions, chatrequests WHERE chatrequests.aspID = '$aspid' AND chatrequests.sessionID = chatsessions.sessionID ORDER BY chatrequests.created DESC" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$sessions[] = $data ;
			}
			return $sessions ;
		}
		return false ;
	}

	/*****  ServiceChat_get_ChatSessionLogins  *******************
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
	 *	$total ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks					Jan 20, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_ChatSessionLogins( &$dbh,
								$sessionid )
	{
		if ( $sessionid == "" )
		{
			return false ;
		}

		$session = ARRAY() ;

		$query = "SELECT * FROM chatsessionlist WHERE sessionID = $sessionid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				if( preg_match( "/<(.*)>/", $data['screen_name'] ) )
					$session['visitor'] = $data['screen_name'] ;
				else
					$session['admin'] = $data['screen_name'] ;
			}
			return $session ;
		}
		return false ;
	}

	/*****  ServiceChat_get_IPChatRequestInfo  ********************
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
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Mar 3, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_IPChatRequestInfo( &$dbh,
						$aspid,
						$ip )
	{
		if ( ( $aspid == "" ) || ( $ip == "" ) )
		{
			return false ;
		}

		$query = "SELECT * FROM chatrequests WHERE ip_address = '$ip' AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  ServiceChat_get_TotalInitiatedOnDate  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				July 31, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_get_TotalInitiatedOnDate( &$dbh,
						$aspid,
						$ip,
						$start,
						$end )
	{
		if ( ( $aspid == "" ) || ( $start == "")
			|| ( $end == "" ) || ( $ip == "" ) )
		{
			return false ;
		}

		$query = "SELECT count(*) AS total FROM chatrequestlogs WHERE status = 4 AND aspID = $aspid AND created >= $start AND created < $end AND ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}
?>