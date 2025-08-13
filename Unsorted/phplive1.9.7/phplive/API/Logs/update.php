<?
	/*****  ServiceLogs::update  *****************************
	 *
	 *  $Id: update.php,v 1.2 2002/06/11 09:02:34 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_UPDATE_ServiceLogs_LOADED ) == true )
		return ;

	$_OFFICE_UPDATE_ServiceLogs_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceLogs_update_ChatRequestStatus  *********************
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
	FUNCTION ServiceLogs_update_ChatRequestStatus( &$dbh,
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

?>
