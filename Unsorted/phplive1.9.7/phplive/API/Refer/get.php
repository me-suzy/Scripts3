<?
	/*****  ServiceRefer::get  ****************************
	 *
	 *  $Id: get.php,v 1.3 2002/07/20 23:18:45 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_GET_ServiceRefer_LOADED ) == true )
		return ;

	$_OFFICE_GET_ServiceRefer_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceRefer_get_ReferInfo  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$footprints ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger					July 17, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceRefer_get_ReferInfo( &$dbh,
								$aspid,
								$ip )
	{
		if ( ( $aspid == "" ) || ( $ip == "" ) )
		{
			return false ;
		}

		$query = "SELECT * FROM chatrefer WHERE aspID = '$aspid' AND ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  ServiceRefer_get_ReferOnDate  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$footprints ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger					July 20, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceRefer_get_ReferOnDate( &$dbh,
								$aspid,
								$start,
								$end )
	{
		if ( ( $aspid == "" ) || ( $start == "" ) )
		{
			return false ;
		}

		$refer = ARRAY() ;

		$query = "SELECT DISTINCT(refer_url) AS refer_url, count(*) AS total FROM chatrefer WHERE aspID = $aspid AND created >= $start AND created < $end AND refer_url <> '' GROUP BY refer_url ORDER BY total DESC LIMIT 500" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while ( $data = database_mysql_fetchrow( $dbh ) )
				$refer[] = $data ;
			return $refer ;
		}
		return false ;
	}

?>