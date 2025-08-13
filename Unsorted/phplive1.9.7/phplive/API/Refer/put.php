<?
	/*****  ServiceRefer::put  ***************************
	 *
	 *  $Id: put.php,v 1.1 2002/07/17 11:07:04 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_PUT_ServiceRefer_LOADED ) == true )
		return ;

	$_OFFICE_PUT_ServiceRefer_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceRefer_put_Refer  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$id ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger					July 17, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceRefer_put_Refer( &$dbh,
					$aspid,
					$ip,
					$refer )
	{
		if ( ( $ip == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}

		$now = time() ;

		$query = "SELECT * FROM chatrefer WHERE ip = '$ip' AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		$data = database_mysql_fetchrow( $dbh ) ;

		if ( !isset( $data['ip'] ) )
		{
			$query = "INSERT INTO chatrefer VALUES ( '$aspid', $now, '$ip', '$refer' )" ;
			database_mysql_query( $dbh, $query ) ;

			if ( $dbh[ 'ok' ] )
			{
				return true ;
			}
		}
		return false ;
	}

?>