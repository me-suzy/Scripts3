<?
	/*****  ServiceTranscripts::remove  ***************************************
	 *
	 *  $Id: remove.php,v 1.2 2002/06/11 09:02:34 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_REMOVE_ServiceTranscripts_LOADED ) == true )
		return ;

	$_OFFICE_REMOVE_ServiceTranscripts_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceTranscripts_remove_ChatRequest  ***************************
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
	FUNCTION ServiceTranscripts_remove_ChatRequest( &$dbh,
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

?>
