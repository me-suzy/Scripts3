<?
	/*****  ServiceChat::update  *****************************
	 *
	 *  $Id: update.php,v 1.7 2002/06/11 09:02:34 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_UPDATE_ServiceChat_LOADED ) == true )
		return ;

	$_OFFICE_UPDATE_ServiceChat_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceChat_update_ChatRequestStatus  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$requestid
	 *	$status
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kory Cline				Nov 8, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_update_ChatRequestStatus( &$dbh,
					  $requestid,
					  $status )
	{
		if ( $requestid == "" )
		{
			return false ;
		}

		$query = "UPDATE chatrequests SET status = '$status' WHERE requestID = $requestid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  ServiceChat_update_ChatActivityTime  ***********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$screen_name
	 *	$sessionid
	 *	$future_buffer
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kory Cline				NOv 8, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_update_ChatActivityTime( &$dbh,
					  $screen_name,
					  $sessionid,
					  $future_buffer )
	{
		if ( ( $screen_name == "" ) || ( $sessionid == "" ) )
		{
			return false ;
		}

		$now = time() ;
		$now += $future_buffer ;

		// on the query, we check to see if future buffer has been added, if so, then don't
		// update the time, or session will time out!
		$query = "UPDATE chatsessionlist SET updated = '$now' WHERE sessionID = $sessionid AND screen_name = '$screen_name' AND updated <= '$now'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  ServiceChat_update_ChatRequestLogStatus  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$chat_session
	 *	$status
	 *
	 *  Description:
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Dec 16, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_update_ChatRequestLogStatus( &$dbh,
					$userid,
					$chat_session,
					$status )
	{
		if ( ( $userid == "" ) || ( $chat_session == "" ) )
		{
			return false ;
		}

		$query = "UPDATE chatrequestlogs SET status = '$status' WHERE chat_session = '$chat_session' AND userID = $userid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  ServiceChat_update_TransferCall  ***********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$requestid
	 *	$userid
	 *	$deptid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kory Cline				Jan 10, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_update_TransferCall( &$dbh,
					  $requestid,
					  $new_userid,
					  $new_deptid )
	{
		if ( ( $requestid == "" ) || ( $new_userid == "" )
			|| ( $new_deptid == "" ) )
		{
			return false ;
		}

		$query = "UPDATE chatrequests SET userID = $new_userid, deptID = $new_deptid, status = 1 WHERE requestID = $requestid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  ServiceChat_update_SessionListLogin  ***********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$sessionid
	 *	$login_old
	 *	$login_new
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kory Cline				Jan 10, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_update_SessionListLogin( &$dbh,
					  $sessionid,
					  $login_old,
					  $login_new )
	{
		if ( ( $sessionid == "" ) || ( $login_old == "" )
			|| ( $login_new == "" ) )
		{
			return false ;
		}

		global $TRANSFER_BUFFER ;

		// here is the thing, when we transfer, we simply replace the current session chat
		// with the new operator info.  BUT, if the session is left idle, it will think
		// session has ended.  so, let's tack on some minutes to give the new operator time
		// to pick-up
		$future_buffer = time() + $TRANSFER_BUFFER ;

		$query = "UPDATE chatsessionlist SET screen_name = '$login_new', updated = '$future_buffer' WHERE sessionID = '$sessionid' AND screen_name = '$login_old'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

?>
