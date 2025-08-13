<?
	/*****  ServiceFootprintUnique::get  ****************************
	 *
	 *  $Id: get.php,v 1.5 2002/06/14 17:02:40 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_GET_ServiceFootprintUnique_LOADED ) == true )
		return ;

	$_OFFICE_GET_ServiceFootprintUnique_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceFootprintUnique_get_ActiveFootprints  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$footprints ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger					Feb 26, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceFootprintUnique_get_ActiveFootprints( &$dbh,
								$aspid )
	{
		if ( $aspid == "" )
		{
			return false ;
		}

		global $FOOTPRINT_IDLE ;
		$idle = time() - $FOOTPRINT_IDLE ;
		$footprints = ARRAY() ;

		$query = "SELECT * FROM chatfootprintsunique WHERE updated > $idle AND aspID = $aspid ORDER BY created ASC LIMIT 50" ;
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

	/*****  ServiceFootprintUnique_get_TotalActiveFootprints  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$footprints ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger					Feb 26, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceFootprintUnique_get_TotalActiveFootprints( &$dbh,
								$aspid )
	{
		if ( $aspid == "" )
		{
			return false ;
		}

		global $FOOTPRINT_IDLE ;
		$idle = time() - $FOOTPRINT_IDLE ;

		$query = "SELECT count(*) AS total FROM chatfootprintsunique WHERE updated > $idle AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  ServiceFootprintUnique_get_IPFootprintInfo  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$ip
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$footprints ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger					Feb 26, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceFootprintUnique_get_IPFootprintInfo( &$dbh,
								$ip,
								$aspid )
	{
		if ( ( $ip == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}

		$query = "SELECT * FROM chatfootprintsunique WHERE ip = '$ip' AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

?>