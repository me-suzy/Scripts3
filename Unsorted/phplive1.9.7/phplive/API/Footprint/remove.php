<?
	/*****  ServiceFootprint::remove  ***************************************
	 *
	 *  $Id: remove.php,v 1.2 2002/06/11 09:02:34 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_remove_ServiceFootprint_LOADED ) == true )
		return ;

	$_OFFICE_remove_ServiceFootprint_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceFootprint_remove_OldFootprints  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$aspid
	 *	$expireday
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$footprints ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				May 31, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceFootprint_remove_OldFootprints( &$dbh,
								$aspid,
								$expireday )
	{
		if ( ( $aspid == "" ) || ( $expireday == "" ) )
		{
			return false ;
		}

		$query = "DELETE FROM chatfootprints WHERE aspID = $aspid AND created < $expireday" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

?>