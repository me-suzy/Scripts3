<?
	/*****  ServiceTranscripts::get  **********************************
	 *
	 *  $Id: get.php,v 1.3 2002/06/11 09:02:34 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_GET_ServiceTranscripts_LOADED ) == true )
		return ;

	$_OFFICE_GET_ServiceTranscripts_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceTranscripts_get_DeptTranscripts  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$deptid
	 *	$order_by
	 *	$sort_by
	 *	$page
	 *	$page_per
	 *	$search_string
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$chat_transcripts ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceTranscripts_get_DeptTranscripts( &$dbh,
						$deptid,
						$order_by,
						$sort_by,
						$page,
						$page_per,
						$search_string )
	{
		if ( ( $deptid == "" ) || ( $page_per == "" ) )
		{
			return false ;
		}

		$chat_transcripts = ARRAY() ;

		$page -= 1 ;
		if ( $page < 1 )
		{
			$begin_index = 0 ;
		}
		else
		{
			$begin_index = $page * $page_per ;
		}

		if ( !$order_by )
			$order_by = "created" ;
		if ( !$sort_by )
			$sort_by = "DESC" ;

		// if $search_string is provided, then we want to search
		if ( $search_string )
			$query = "SELECT * FROM chattranscripts WHERE deptID = '$deptid' AND plain LIKE '%$search_string%' ORDER BY $order_by $sort_by LIMIT $begin_index, $page_per" ;
		else
			$query = "SELECT * FROM chattranscripts WHERE deptID = '$deptid' ORDER BY $order_by $sort_by LIMIT $begin_index, $page_per" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$chat_transcripts[] = $data ;
			}
			return $chat_transcripts ;
		}
		return false ;
	}

	/*****  ServiceTranscripts_get_UserDeptTranscripts  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$deptid
	 *	$order_by
	 *	$sort_by
	 *	$page
	 *	$page_per
	 *	$search_string
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$chat_transcripts ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceTranscripts_get_UserDeptTranscripts( &$dbh,
						$userid,
						$deptid,
						$order_by,
						$sort_by,
						$page,
						$page_per,
						$search_string)
	{
		if ( ( $userid == "" ) || ( $deptid == "" )
			|| ( $page_per == "" ) )
		{
			return false ;
		}

		$chat_transcripts = ARRAY() ;

		$page -= 1 ;
		if ( $page < 1 )
		{
			$begin_index = 0 ;
		}
		else
		{
			$begin_index = $page * $page_per ;
		}

		if ( !$order_by )
			$order_by = "created" ;
		if ( !$sort_by )
			$sort_by = "DESC" ;

		// if $search_string is provided, then we want to search
		if ( $search_string )
			$query = "SELECT * FROM chattranscripts WHERE userID = $userid AND deptID = $deptid AND plain LIKE '%$search_string%' ORDER BY $order_by $sort_by LIMIT $begin_index, $page_per" ;
		else
			$query = "SELECT * FROM chattranscripts WHERE userID = $userid AND deptID = $deptid ORDER BY $order_by $sort_by LIMIT $begin_index, $page_per" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$chat_transcripts[] = $data ;
			}
			return $chat_transcripts ;
		}
		return false ;
	}

	/*****  ServiceTranscripts_get_TotalDeptTranscripts  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$deptid
	 *	$search_string
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$date[total] ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceTranscripts_get_TotalDeptTranscripts( &$dbh,
						$deptid,
						$search_string )
	{
		if ( $deptid == "" )
		{
			return false ;
		}

		// if $search_string is provided, then we want to search
		if ( $search_string )
			$query = "SELECT COUNT(*) AS total FROM chattranscripts WHERE deptID = '$deptid' AND plain LIKE '%$search_string%'" ;
		else
			$query = "SELECT COUNT(*) AS total FROM chattranscripts WHERE deptID = '$deptid'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  ServiceTranscripts_get_TotalUserDeptTranscripts  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$deptid
	 *	$search_string
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$date[total] ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceTranscripts_get_TotalUserDeptTranscripts( &$dbh,
						$userid,
						$deptid,
						$search_string )
	{
		if ( ( $userid == "" ) || ( $deptid == "" ) )
		{
			return false ;
		}

		// if $search_string is provided, then we want to search
		if ( $search_string )
			$query = "SELECT COUNT(*) AS total FROM chattranscripts WHERE userID = $userid AND deptID = $deptid AND plain LIKE '%$search_string%'" ;
		else
			$query = "SELECT COUNT(*) AS total FROM chattranscripts WHERE userID = $userid AND deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  ServiceTranscripts_get_TranscriptInfo  *************************
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
	 *	$date ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceTranscripts_get_TranscriptInfo( &$dbh,
						$chat_session )
	{
		if ( $chat_session == "" )
		{
			return false ;
		}

		$query = "SELECT * FROM chattranscripts WHERE chat_session = '$chat_session'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}
?>
