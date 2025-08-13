<?
	/*****  ServiceFootprintUnique::put  ***************************
	 *
	 *  $Id: put.php,v 1.3 2002/07/17 11:07:04 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_PUT_ServiceFootprintUnique_LOADED ) == true )
		return ;

	$_OFFICE_PUT_ServiceFootprintUnique_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceFootprintUnique_put_Footprint  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$ip
	 *	$url
	 *	$aspid
	 *	$update_url
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$id ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger					Feb 26, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceFootprintUnique_put_Footprint( &$dbh,
					$ip,
					$url,
					$aspid )
	{
		if ( ( $ip == "" ) || ( $url == "" )
			|| ( $aspid == "" ) )
		{
			return false ;
		}

		$now = time() ;

		$query = "SELECT * FROM chatfootprintsunique WHERE ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;
		$data = database_mysql_fetchrow( $dbh ) ;

		if ( isset( $data['ip'] ) )
			$query = "REPLACE INTO chatfootprintsunique VALUES( '$ip', $data[created], $now, '$url', $aspid )" ;
		else
			$query = "REPLACE INTO chatfootprintsunique VALUES( '$ip', $now, $now, '$url', $aspid )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

?>