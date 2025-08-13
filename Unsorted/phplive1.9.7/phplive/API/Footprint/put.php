<?
	/*****  ServiceFootprint::put  ***************************************
	 *
	 *  $Id: put.php,v 1.5 2002/06/13 08:21:23 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_PUT_ServiceFootprint_LOADED ) == true )
		return ;

	$_OFFICE_PUT_ServiceFootprint_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceFootprint_put_Footprint  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$ip
	 *	$url
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$id ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Dec 2, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceFootprint_put_Footprint( &$dbh,
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

		$query = "INSERT INTO chatfootprints VALUES( 0, '$ip', $now, '$url', $aspid )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  ServiceFootprint_put_FootprintURLStat  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$aspid
	 *	$statdate
	 *	$url
	 *	$clicks
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				May 24, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceFootprint_put_FootprintURLStat( &$dbh,
					$aspid,
					$statdate,
					$url,
					$clicks )
	{
		if ( ( $aspid == "" ) || ( $statdate == "" )
			|| ( $url == "" ) )
		{
			return false ;
		}

		$now = time() ;

		$query = "INSERT INTO chatfootprinturlstats VALUES( 0, '$aspid', '$statdate', $now, '$url', '$clicks' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  ServiceFootprint_put_FootprintStat  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$aspid
	 *	$statdate
	 *	$pageviews
	 *	$uniquevisits
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				May 24, 2002
	 *
	 *****************************************************************/
	FUNCTION ServiceFootprint_put_FootprintStat( &$dbh,
					$aspid,
					$statdate,
					$pageviews,
					$uniquevisits )
	{
		if ( ( $aspid == "" ) || ( $statdate == "" ) )
		{
			return false ;
		}

		$now = time() ;

		$query = "INSERT INTO chatfootprintstats VALUES( '$aspid', '$statdate', $now, '$pageviews', '$uniquevisits' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}
?>