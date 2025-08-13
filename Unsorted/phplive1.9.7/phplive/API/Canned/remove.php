<?
	/*****  ServiceCanned::remove  ***************************************
	 *
	 *  $Id: remove.php,v 1.2 2002/06/11 09:02:34 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_REMOVE_ServiceCanned_LOADED ) == true )
		return ;

	$_OFFICE_REMOVE_ServiceCanned_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceCanned_remove_UserCanned  ************************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$cannedid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kory Cline				Nov 12, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceCanned_remove_UserCanned( &$dbh,
						$userid,
						$cannedid )
	{
		if ( ( $userid == "" ) || ( $cannedid == "" ) )
		{
			return false ;
		}

		$query = "DELETE FROM chatcanned WHERE userID = $userid AND cannedID = $cannedid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}

		return false ;
	}

?>
