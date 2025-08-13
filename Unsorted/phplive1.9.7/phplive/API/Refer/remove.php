<?
	/*****  ServiceRefer::remove  ***************************************
	 *
	 *  $Id: remove.php,v 1.2 2002/07/20 21:09:54 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_REMOVE_ServiceRefer_LOADED ) == true )
		return ;

	$_OFFICE_REMOVE_ServiceRefer_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceRefer_remove_OldRefer  ***************************
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
	 *	Holger				March 3, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceRefer_remove_OldRefer( &$dbh,
						$aspid )
	{
		if ( $aspid == "" )
		{
			return false ;
		}

		// keep 10 days of refer
		$expired = time() - (60*60*24*10) ;

		$query = "DELETE FROM chatrefer WHERE aspID = $aspid AND created < $expired" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return true ;
	}

?>