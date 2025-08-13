<?
	/*****  ServiceCanned::get  **********************************
	 *
	 *  $Id: get.php,v 1.5 2002/07/17 11:07:04 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_GET_ServiceCanned_LOADED ) == true )
		return ;

	$_OFFICE_GET_ServiceCanned_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceCanned_get_UserCannedByType  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$deptid
	 *	$type
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kory Cline				Dec 12, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceCanned_get_UserCannedByType( &$dbh,
						$userid,
						$deptid,
						$type )
	{
		if ( ( $userid == "" ) || ( $type == "" ) )
		{
			return false ;
		}

		$canned = ARRAY() ;
		$dept_string = "" ;

		if ( $deptid && ( $userid > 10000000 ) )
			$dept_string = "AND deptID = $deptid" ;
		else if ( $deptid )
			$dept_string = "OR deptID = $deptid" ;

		$query = "SELECT * FROM chatcanned WHERE ( userID = '$userid' $dept_string ) AND type = '$type'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$canned[] = $data ;
			}
			return $canned ;
		}
		return false ;
	}

	/*****  ServiceCanned_get_CannedInfo  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$cannedid
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
	 *	Kory Cline				Dec 12, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceCanned_get_CannedInfo( &$dbh,
						$cannedid,
						$userid )
	{
		if ( ( $cannedid == "" ) || ( $userid == "" ) )
		{
			return false ;
		}

		$query = "SELECT * FROM chatcanned WHERE cannedID = '$cannedid' AND userID = '$userid'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

?>
