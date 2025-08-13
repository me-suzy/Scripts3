<?
	/*****  ServiceCanned::put  ***************************************
	 *
	 *  $Id: put.php,v 1.5 2002/06/26 05:30:20 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_PUT_ServiceCanned_LOADED ) == true )
		return ;

	$_OFFICE_PUT_ServiceCanned_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceCanned_put_UserCanned  *************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$deptid
	 *	$type
	 *	$name
	 *	$message
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$id ( inserted id )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 12, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceCanned_put_UserCanned( &$dbh,
				$userid,
				$deptid,
				$type,
				$name,
				$message )
	{
		if ( ( $userid == "" ) || ( $type == "" )
			|| ( $name == "" ) || ( $message == "" )
			|| ( $deptid == "" ) )
		{
			return false ;
		}

		$query = "SELECT * FROM chatcanned WHERE userID = $userid AND type = '$type' AND name = '$name'" ;
		database_mysql_query( $dbh, $query ) ; 

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;

			if ( $data['cannedID'] )
				return true ;
			else
			{
				$name = addslashes( $name ) ;
				$message = addslashes( $message ) ;
				
				$query = "INSERT INTO chatcanned VALUES( 0, $userid, '$deptid', '$type', '$name', '$message' )" ;
				database_mysql_query( $dbh, $query ) ;

				if ( $dbh[ 'ok' ] )
				{
					$id = database_mysql_insertid ( $dbh ) ;
					return $id ;
				}
			}
		}
		return false ;
	}

?>