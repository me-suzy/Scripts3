<?
	/*****  AdminASP::get  ***************************************
	 *
	 *  $Id: get.php,v 1.3 2002/06/11 09:02:34 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_GET_AdminASP_LOADED ) == true )
		return ;

	$_OFFICE_GET_AdminASP_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  AdminASP_get_AllUsers  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$page
	 *	$page_per
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$users ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Jan 19, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminASP_get_AllUsers( &$dbh,
					$page,
					$page_per )
	{
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
			$query = "SELECT * FROM chat_asp ORDER BY contact_email ASC LIMIT $begin_index, $page_per" ;
		}
		else
		{
			$query = "SELECT * FROM chat_asp ORDER BY contact_email ASC" ;
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

	/*****  AdminASP_get_UserInfo  *******************************
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
	 *	Kyle Hicks				Jan 19, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminASP_get_UserInfo( &$dbh,
										$aspid )
	{
		if ( $aspid == "" )
		{
			return false ;
		}

		$query = "SELECT * FROM chat_asp WHERE aspID = $aspid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  AdminASP_get_UserInfoByLoginPass  ************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$login
	 *	$password
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Jan 19, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminASP_get_UserInfoByLoginPass( &$dbh,
						$login,
						$password )
	{
		if ( ( $login == "" ) || ( $password == "" ) )
		{
			return false ;
		}

		$query = "SELECT * FROM chat_asp WHERE login = '$login' AND password = '$password'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  AdminASP_get_ASPInfoByASPLogin  ************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$login
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$data ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Jan 19, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminASP_get_ASPInfoByASPLogin( &$dbh,
						$login )
	{
		if ( $login == "" )
		{
			return false ;
		}

		$query = "SELECT * FROM chat_asp WHERE login = '$login'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/*****  AdminASP_get_IsLoginTaken  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$login
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Jan 19, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminASP_get_IsLoginTaken( &$dbh,
						$login )
	{
		if ( $login == "" )
		{
			return false ;
		}

		$query = "SELECT * FROM chat_asp WHERE login = '$login'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['aspID'] ;
		}
		return false ;
	}

	/*****  AdminASP_get_TotalUsers  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Jan 23, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminASP_get_TotalUsers( &$dbh )
	{

		$query = "SELECT count(*) AS total FROM chat_asp" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data['total'] ;
		}
		return false ;
	}

?>