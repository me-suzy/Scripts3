<?
	/*****  AdminUsers::get  ***************************************
	 *
	 *  $Id: get.php,v 1.9 2002/08/28 06:20:03 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_GET_AdminUsers_LOADED ) == true )
		return ;

	$_OFFICE_GET_AdminUsers_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  AdminUsers_get_AreAnyAdminOnline  *********************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$deptid
	 *	$idle
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
	 *	Nate Lee				Dec 27, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_AreAnyAdminOnline( &$dbh,
								$deptid,
								$idle,
								$aspid )
	{
		if ( ( $idle == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}

		// if $deptid is passed, then let's check ONLY admins of that department
		if ( $deptid )
			$query = "SELECT count(*) AS total FROM chat_admin, chatuserdeptlist WHERE chat_admin.userID = chatuserdeptlist.userID AND chatuserdeptlist.deptID = $deptid AND chat_admin.available_status = 1 AND chat_admin.last_active_time > $idle AND chat_admin.aspID = $aspid" ;
		else
			$query = "SELECT count(*) AS total FROM chat_admin WHERE available_status = 1 AND last_active_time > $idle AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  AdminUsers_get_AllUsers  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$page
	 *	$page_per
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$users ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Nov 5, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_AllUsers( &$dbh,
					$page,
					$page_per,
					$aspid )
	{
		if ( $aspid == "" )
		{
			return false ;
		}

		$users = ARRAY() ;

		$page -= 1 ;
		if ( $page < 1 )
		{
			$begin_index = 0 ;
		}
		else
		{
			$begin_index = $page * $page_per ;
		}

		if ( $page_per )
		{
			$query = "SELECT * FROM chat_admin WHERE aspID = $aspid ORDER BY login ASC LIMIT $begin_index, $page_per" ;
		}
		else
		{
			$query = "SELECT * FROM chat_admin WHERE aspID = $aspid ORDER BY login ASC" ;
		}
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while ( $data = database_mysql_fetchrow( $dbh ) )
			{
				$users[] = $data ;
			}
			return $users ;
		}
		return false ;
	}

	/*****  AdminUsers_get_TotalUsers  *******************************
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
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				NOv 5, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_TotalUsers( &$dbh,
										$aspid )
	{
		if ( $aspid == "" )
		{
			return false ;
		}

		$query = "SELECT count(*) AS total FROM chat_admin WHERE aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  AdminUsers_get_TotalDepartments  *******************************
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
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				NOv 5, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_TotalDepartments( &$dbh,
										$aspid )
	{
		if ( $aspid == "" )
		{
			return false ;
		}

		$query = "SELECT count(*) AS total FROM chatdepartments WHERE aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  AdminUsers_get_AllDeptUsers  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$deptid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$users ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 25, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_AllDeptUsers( &$dbh,
					$deptid )
	{
		if ( $deptid == "" )
		{
			return false ;
		}

		$users = ARRAY() ;

		$query = "SELECT chat_admin.* FROM chat_admin, chatuserdeptlist WHERE chat_admin.userID = chatuserdeptlist.userID and chatuserdeptlist.deptID = $deptid ORDER BY chat_admin.login ASC" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			while ( $data = database_mysql_fetchrow( $dbh ) )
			{
				$users[] = $data ;
			}
			return $users ;
		}
		return false ;
	}

	/*****  AdminUsers_get_UserInfo  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				NOv 5, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_UserInfo( &$dbh,
										$userid,
										$aspid )
	{
		if ( ( $userid == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}

		$query = "SELECT * FROM chat_admin WHERE userID = $userid AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  AdminUsers_get_DeptInfo  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$deptid
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 25, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_DeptInfo( &$dbh,
									$deptid,
									$aspid )
	{
		if ( ( $deptid == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}

		$query = "SELECT * FROM chatdepartments WHERE deptID = $deptid AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  AdminUsers_get_AllDeptUsersAvailable  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$deptid
	 *	$exclude_userid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				NOv 5, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_AllDeptUsersAvailable( &$dbh,
										$deptid,
										$exclude_userid )
	{
		if ( $deptid == "" )
		{
			return false ;
		}

		$admins = ARRAY() ;

		$query = "SELECT * FROM chat_admin, chatuserdeptlist WHERE chatuserdeptlist.userID = chat_admin.userID AND chatuserdeptlist.deptID = $deptid AND chat_admin.available_status = 1 $exclude_userid ORDER BY chatuserdeptlist.ordernum ASC" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$admins[] = $data ;
			}
			return $admins ;
		}
		return false ;
	}

	/*****  AdminUsers_get_LessLoadedDeptUser  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$deptid
	 *	$exclude_userid
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 14, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_LessLoadedDeptUser( &$dbh,
										$deptid,
										$exclude_userid,
										$aspid )
	{
		if ( ( $deptid == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}
		
		$lessloaded_admin = ARRAY() ;
		$admins = AdminUsers_get_AllDeptUsersAvailable( $dbh, $deptid, $exclude_userid ) ;

		// if there is only one admin in that department, then just return that admin's info
		if ( count( $admins ) == 1 )
			return $admins[0] ;

		for ( $c = 0; $c < count( $admins ); ++$c )
		{
			$admin = $admins[$c] ;
			$query = "SELECT COUNT(*) AS total FROM chatrequests WHERE userID = $admin[userID]" ;
			database_mysql_query( $dbh, $query ) ;

			if ( $dbh[ 'ok' ] )
			{
				$data = database_mysql_fetchrow( $dbh ) ;

				if ( !isset( $lessloaded_count ) || ( $data['total'] < $lessloaded_count ) )
				{
					$lessloaded_admin = $admin ;
					$lessloaded_count = $data['total'] ;
				}
			}
		}
		
		// if available admin exist
		if ( isset( $lessloaded_admin['userID'] ) )
			return $lessloaded_admin ;
		else
		{
			$deptinfo = AdminUsers_get_DeptInfo( $dbh, $deptid, $aspid ) ;
			return $deptinfo ;
		}
	}

	/*****  AdminUsers_get_UserInfoByLoginPass  ************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$login
	 *	$password
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Nov 5, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_UserInfoByLoginPass( &$dbh,
						$login,
						$password,
						$aspid )
	{
		if ( ( $login == "" ) || ( $password == "" ) ||
			( $aspid == "" ) )
		{
			return false ;
		}

		$password = md5( $password ) ;

		$query = "SELECT * FROM chat_admin WHERE login = '$login' AND password = '$password' AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  AdminUsers_get_IsLoginTaken  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$login
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
	 *	Holger				Nov 5, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_IsLoginTaken( &$dbh,
						$login,
						$aspid )
	{
		if ( ( $login == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}

		$query = "SELECT * FROM chat_admin WHERE login = '$login' AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			if ( $data['userID'] )
				return $data['userID'] ;
		}
		return false ;
	}

	/*****  AdminUsers_get_IsNameTaken  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$name
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
	 *	Kyle				June 18, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_IsNameTaken( &$dbh,
						$name,
						$aspid )
	{
		if ( ( $name == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}

		$query = "SELECT * FROM chat_admin WHERE name = '$name' AND aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			if ( $data['userID'] )
				return $data['userID'] ;
		}
		return false ;
	}

	/*****  AdminUsers_get_IsUserInDept  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$deptid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 28, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_IsUserInDept( &$dbh,
						$userid,
						$deptid )
	{
		if ( ( $userid == "" ) || ( $deptid == "" ) )
		{
			return false ;
		}

		$query = "SELECT chat_admin.* FROM chat_admin, chatuserdeptlist WHERE chat_admin.userID = chatuserdeptlist.userID AND chat_admin.userID = $userid AND chatuserdeptlist.deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			if ( $data['userID'] )
				return $data['userID'] ;
		}
		return false ;
	}

	/*****  AdminUsers_get_AllDepartments  ******************************
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
	 *	$departments ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 14, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_AllDepartments( &$dbh,
						$aspid )
	{
		if ( $aspid == "" )
		{
			return false ;
		}

		$departments = ARRAY() ;

		$query = "SELECT * FROM chatdepartments WHERE aspID = $aspid ORDER BY name ASC" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$departments[] = $data ;
			}
			return $departments ;
		}
		return false ;
	}

	/*****  AdminUsers_get_UserDepartments  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$departments ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 28, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_UserDepartments( &$dbh,
								$userid )
	{
		if ( $userid == "" )
		{
			return false ;
		}

		$departments = ARRAY() ;

		$query = "SELECT * FROM chatdepartments, chatuserdeptlist WHERE chatdepartments.deptID = chatuserdeptlist.deptID AND chatuserdeptlist.userID = $userid ORDER BY chatdepartments.name ASC" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$departments[] = $data ;
			}
			return $departments ;
		}
		return false ;
	}

	/*****  AdminUsers_get_TotalDepartmentUsersOnline  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$deptid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data[total] ( number of online users in department )
	 *	false ( failure )
	 *
	 *  History:
	 *	Holger				Dec 14, 2001
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_TotalDepartmentUsersOnline( &$dbh,
					$deptid )
	{
		if ( $deptid == "" )
		{
			return false ;
		}

		$query = "SELECT COUNT(*) AS total FROM chat_admin, chatuserdeptlist WHERE chatuserdeptlist.userID = chat_admin.userID AND chatuserdeptlist.deptID = $deptid AND chat_admin.available_status = 1" ;
		database_mysql_query( $dbh, $query ) ;
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  AdminUsers_get_CanUserInitiate  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$total ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				March 13, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_CanUserInitiate( &$dbh,
								$userid )
	{
		if ( $userid == "" )
		{
			return false ;
		}

		$query = "SELECT count(*) AS total FROM chatdepartments, chatuserdeptlist WHERE chatdepartments.deptID = chatuserdeptlist.deptID AND userID = $userid AND chatdepartments.initiate_chat = 1" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

	/*****  AdminUsers_get_DeptUserOrderNum  **************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$userid
	 *	$deptid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$ordernum ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				August 28, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminUsers_get_DeptUserOrderNum( &$dbh,
								$userid,
								$deptid )
	{
		if ( ( $userid == "" ) || ( $deptid == "" ) )
		{
			return false ;
		}

		$query = "SELECT ordernum FROM chatuserdeptlist WHERE userID = $userid AND deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['ordernum'] ;
		}
		return false ;
	}
?>