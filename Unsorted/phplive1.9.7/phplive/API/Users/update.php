<?
	/*****  AdminUsers::update  ***************************************
	 *
	 *  $Id: update.php,v 1.6 2002/08/28 06:20:03 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_UPDATE_AdminUsers_LOADED ) == true )
		return ;

	$_OFFICE_UPDATE_AdminUsers_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  AdminUsers_update_Status  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$status
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 3, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_update_Status( &$dbh,
					  $userid,
					  $status )
	{
		if ( $userid == "" )
		{
			return false ;
		}

		$query = "UPDATE chat_admin SET available_status = $status WHERE userID = $userid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  AdminUsers_update_Signal  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$signal
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Jan 20, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_update_Signal( &$dbh,
					  $userid,
					  $signal,
					  $aspid )
	{
		if ( ( $userid == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}

		$query = "UPDATE chat_admin SET signal = $signal WHERE userID = $userid AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  AdminUsers_update_LastActiveTime  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$time
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 3, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_update_LastActiveTime( &$dbh,
					  $userid,
					  $time,
					  $sid )
	{
		if ( ( $userid == "" ) || ( $sid == "" ) )
		{
			return false ;
		}

		$query = "UPDATE chat_admin SET last_active_time = $time, session_sid = '$sid' WHERE userID = $userid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  AdminUsers_update_UserValue  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$tbl_name
	 *	$value
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Dec 12, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_update_UserValue( &$dbh,
					  $userid,
					  $tbl_name,
					  $value )
	{
		if ( ( $userid == "" ) || ( $tbl_name == "" ) )
		{
			return false ;
		}

		$value = addslashes( $value ) ;

		$query = "UPDATE chat_admin SET $tbl_name = '$value' WHERE userID = $userid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  AdminUsers_update_IdleAdminStatus  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$idle
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Nov 3, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_update_IdleAdminStatus( &$dbh, $idle )
	{
		if ( $idle == "" )
		{
			return false ;
		}

		$query = "UPDATE chat_admin SET available_status = 0 WHERE last_active_time < $idle" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  AdminUsers_update_Password  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$password
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 18, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_update_Password( &$dbh,
					  $userid,
					  $password )
	{
		if ( $password == "" )
		{
			return false ;
		}

		$password = md5( $password ) ;

		$query = "UPDATE chat_admin SET password = '$password' WHERE userID = $userid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  AdminUsers_update_User  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$login
	 *	$password
	 *	$name
	 *	$email
	 *	$company
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 19, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_update_User( &$dbh,
						$userid,
						$login,
						$password,
						$name,
						$email,
						$company,
						$aspid )
	{
		if ( ( $login == "" ) || ( $password == "" )
			|| ( $name == "" ) || ( $email == "" )
			|| ( $company == "" ) || ( $userid == "" )
			|| ( $aspid == "" ) )
		{
			return false ;
		}

		$password = md5( $password ) ;

		$query = "UPDATE chat_admin SET login = '$login', password = '$password', name = '$name', email = '$email', company = '$company' WHERE userID = $userid AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  AdminUsers_update_DeptValue  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$aspid
	 *	$deptid
	 *	$tbl_name
	 *	$value
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle				April 27, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_update_DeptValue( &$dbh,
					  $aspid,
					  $deptid,
					  $tbl_name,
					  $value )
	{
		if ( ( $aspid == "" ) || ( $deptid == "" )
			|| ( $tbl_name == "" ) )
		{
			return false ;
		}

		$value = addslashes( $value ) ;

		$query = "UPDATE chatdepartments SET $tbl_name = '$value' WHERE deptID = $deptid AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}

	/*****  AdminUsers_update_UserDeptOrder  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$deptid
	 *	$order
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle				August 28, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_update_UserDeptOrder( &$dbh,
					  $userid,
					  $deptid,
					  $order )
	{
		if ( ( $userid == "" ) || ( $deptid == "" ) )
		{
			return false ;
		}

		$query = "UPDATE chatuserdeptlist SET ordernum = '$order' WHERE userID = $userid AND deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			return true ;
		}
		return false ;
	}
?>
