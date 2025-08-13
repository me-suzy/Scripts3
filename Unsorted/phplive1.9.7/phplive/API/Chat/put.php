<?
	/*****  ServiceChat::put  ***************************************
	 *
	 *  $Id: put.php,v 1.8 2002/07/31 09:06:06 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_PUT_ServiceChat_LOADED ) == true )
		return ;

	$_OFFICE_PUT_ServiceChat_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceChat_put_ChatSessionList  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$screen_name
	 *	$sessionid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$id ( inserted id )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 8, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_put_ChatSessionList( &$dbh,
				$screen_name,
				$sessionid )
	{
		if ( ( $sessionid == "" ) || ( $screen_name == "" ) )
		{
			return false ;
		}

		$now = time() ;

		$query = "SELECT * FROM chatsessionlist WHERE sessionID = $sessionid AND screen_name = '$screen_name'" ;
		database_mysql_query( $dbh, $query ) ;
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;

			if ( $data['sessionID'] )
				return true ;
			else
			{
				$query = "INSERT INTO chatsessionlist VALUES( $sessionid, '$screen_name', $now )" ;
				database_mysql_query( $dbh, $query ) ;

				if ( $dbh[ 'ok' ] )
				{
					return true ;
				}
			}
		}
		return false ;
	}

	/*****  ServiceChat_put_ChatSession  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$screen_name
	 *	$initiate_file
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$id ( inserted id )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 8, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_put_ChatSession( &$dbh,
				$screen_name,
				$initiate_file )
	{
		if ( $screen_name == "" )
		{
			return false ;
		}

		$now = time() ;
		$query = "INSERT INTO chatsessions VALUES($now, '$screen_name', $now, '$initiate_file')" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return $now ;
		}
		return false ;
	}

	/*****  ServiceChat_put_ChatRequestLog  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$deptid
	 *	$sessionid
	 *	$display_resolution
	 *	$page
	 *	$aspid
	 *	$status
	 *	$ip
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$id ( inserted id )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Dec 14, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_put_ChatRequestLog( &$dbh,
				$userid,
				$deptid,
				$sessionid,
				$display_resolution,
				$page,
				$aspid,
				$status,
				$ip,
				$browser )
	{
		if ( ( $userid == "" ) || ( $sessionid == "" )
			|| ( $deptid == "" ) || ( $aspid == "" ) 
			|| ( $ip == "" ) )
		{
			return false ;
		}

		$hostname  = gethostbyaddr( $ip ) ;
		$browser = addslashes( $browser ) ;

		$now = time() ;

		// put the data in a log file to keep for records and statistics.
		// keep in mind, we use $chatfile AS the primary ID because there can
		// only be ONE instance of the chat file (uses time() as part of name).
		$query = "REPLACE INTO chatrequestlogs VALUES ('$sessionid', '$userid', '$deptid', '$ip', '$hostname', '$display_resolution', '$browser', $now, '$status', '$page', $aspid)" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  ServiceChat_put_ChatRequest  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$deptid
	 *	$from_screen_name
	 *	$sessionid
	 *	$display_resolution
	 *	$visitor_time
	 *	$page
	 *	$question
	 *	$status
	 *	$ip
	 *	$browser
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$id ( inserted id )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Dec 14, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceChat_put_ChatRequest( &$dbh,
				$userid,
				$deptid,
				$from_screen_name,
				$sessionid,
				$display_resolution,
				$visitor_time,
				$page,
				$aspid,
				$question,
				$status,
				$ip,
				$browser )
	{
		if ( ( $userid == "" ) || ( $from_screen_name == "" )
			|| ( $sessionid == "" ) || ( $deptid == "" )
			|| ( $aspid == "" ) || ( $ip == "" ) )
		{
			return false ;
		}

		$browser = addslashes( $browser ) ;
		$question = addslashes( $question ) ;

		$now = time() ;

		$query = "SELECT * FROM chatrequests WHERE userID = '$userid' AND from_screen_name = '$from_screen_name' AND sessionID = $sessionid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			if ( $data['requestID'] )
				return $data['requestID'] ;
			else
			{
				$query = "INSERT INTO chatrequests VALUES (0, '$userid', '$deptid', '$aspid', '$from_screen_name', $sessionid, $now, '$status', '$ip', '$browser', '$display_resolution', '$visitor_time', '$page', '$question')" ;
				database_mysql_query( $dbh, $query ) ;

				if ( $dbh[ 'ok' ] )
				{
					if ( $id = database_mysql_insertid ( $dbh ) )
					{
						$browser = stripslashes( $browser ) ;
						if ( ServiceChat_put_ChatRequestLog( $dbh, $userid, $deptid, $sessionid, $display_resolution, $page, $aspid, $status, $ip, $browser ) )
						{
							return $id ;
						}
					}
				}
				return false ;
			}
		}
		return false ;
	}
?>
