<?
	/*****  ServiceLogs::get  **********************************
	 *
	 *  $Id: get.php,v 1.4 2002/06/11 09:02:34 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_GET_ServiceLogs_LOADED ) == true )
		return ;

	$_OFFICE_GET_ServiceLogs_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceLogs_get_SessionRequestLog  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$chat_session
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceLogs_get_SessionRequestLog( &$dbh,
						$chat_session )
	{
		if ( $chat_session == "" )
		{
			return false ;
		}

		$query = "SELECT * FROM chatrequestlogs WHERE chat_session = '$chat_session'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  ServiceLogs_get_TotalDeptRequests  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$deptid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceLogs_get_TotalDeptRequests( &$dbh,
						$deptid )
	{
		if ( $deptid == "" )
		{
			return false ;
		}

		$query = "SELECT COUNT(*) AS total FROM chatrequestlogs WHERE deptID = '$deptid'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  ServiceLogs_get_DayMostLiveRequestPage  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$begin
	 *	$end
	 *	$limit
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$footprints ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 27, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceLogs_get_DayMostLiveRequestPage( &$dbh,
								$begin,
								$end,
								$limit,
								$aspid )
	{
		if ( ( $begin == "" ) || ( $end == "" )
			|| ( $aspid == "" ) )
		{
			return false ;
		}

		$limit_string = "" ;
		$footprints = ARRAY() ;

		if ( $limit )
			$limit_string = "LIMIT $limit" ;

		$query = "SELECT url, count(*) AS total FROM chatrequestlogs WHERE created > $begin AND created <= $end AND aspID = $aspid GROUP BY url ORDER BY total DESC $limit_string" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while ( $data = database_mysql_fetchrow( $dbh ) )
			{
				$footprints[] = $data ;
			}
			return $footprints ;
		}
		return false ;
	}

	/*****  ServiceLogs_get_TotalIpRequests  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$ip
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 27, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceLogs_get_TotalIpRequests( &$dbh,
						$ip )
	{
		if ( $ip == "" )
		{
			return 0 ;
		}

		$query = "SELECT COUNT(*) AS total FROM chatrequestlogs WHERE ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return 0 ;
	}

	/*****  ServiceLogs_get_TotalRequestsPerDay  *****************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$deptid
	 *	$begin
	 *	$end
	 *	$status
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceLogs_get_TotalRequestsPerDay( &$dbh,
						$deptid,
						$begin,
						$end,
						$status,
						$aspid )
	{
		if ( ( $begin == "" ) || ( $end == "" )
			|| ( $aspid == "" ) )
		{
			return false ;
		}

		$department_string = "" ;

		if ( $deptid )
			$department_string = "AND deptID = '$deptid'" ;

		$query = "SELECT COUNT(*) AS total FROM chatrequestlogs WHERE created >= $begin AND created < $end AND aspID = $aspid $department_string AND status = $status" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  ServiceLogs_get_TotalBrowserCount  **********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$browser
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceLogs_get_TotalBrowserCount( &$dbh,
						$browser )
	{
		if ( $browser == "" )
		{
			return false ;
		}

		$query = "SELECT COUNT(*) AS total FROM chatrequestlogs WHERE browser_os LIKE '%$browser%'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  ServiceLogs_get_TotalUserRequestCount  *******************
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
	 *	Holger				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceLogs_get_TotalUserRequestCount( &$dbh,
						$userid )
	{
		if ( $userid == "" )
		{
			return false ;
		}

		$query = "SELECT COUNT(*) AS total FROM chatrequestlogs WHERE userID = '$userid'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  ServiceLogs_get_TotalUserRequestCountPerDay  **************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$begin
	 *	$end
	 *	$status
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceLogs_get_TotalUserRequestCountPerDay( &$dbh,
						$userid,
						$begin,
						$end,
						$status,
						$aspid )
	{
		if ( ( $userid == "" ) || ( $begin == "" )
			|| ( $end == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}

		$query = "SELECT COUNT(*) AS total FROM chatrequestlogs WHERE userID = '$userid' AND created >= $begin AND created < $end AND status = $status AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

?>
