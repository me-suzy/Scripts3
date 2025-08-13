<?
	/*****  ServiceCanned::update  **********************************
	 *
	 *  $Id: update.php,v 1.2 2002/06/11 09:02:34 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_update_ServiceCanned_LOADED ) == true )
		return ;

	$_OFFICE_update_ServiceCanned_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceCanned_update_Canned  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$cannedid
	 *	$name
	 *	$message
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kory Cline				Jan 24, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceCanned_update_Canned( &$dbh,
						$userid,
						$cannedid,
						$name,
						$message )
	{
		if ( ( $userid == "" ) || ( $cannedid == "" )
			|| ( $name == "" ) || ( $message == "" ) )
		{
			return false ;
		}

		$name = addslashes( $name ) ;
		$message = addslashes( $message ) ;

		$query = "UPDATE chatcanned SET name = '$name', message = '$message' WHERE cannedID = $cannedid AND userID = $userid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

?>
