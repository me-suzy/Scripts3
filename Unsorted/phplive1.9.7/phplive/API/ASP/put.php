<?
	/*****  AdminASP::put  ***************************************
	 *
	 *  $Id: put.php,v 1.7 2002/07/20 20:41:24 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_PUT_AdminASP_LOADED ) == true )
		return ;

	$_OFFICE_PUT_AdminASP_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  AdminASP_put_user  *******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$login
	 *	$password
	 *	$company
	 *	$contact_name
	 *	$contact_email
	 *	$max_dept
	 *	$max_users
	 *	$footprints
	 *	$active_status
	 *	$initiate_chat
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$id ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Jan 23, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminASP_put_user( &$dbh,
					$login,
					$password,
					$company,
					$contact_name,
					$contact_email,
					$max_dept,
					$max_users,
					$footprints,
					$active_status,
					$initiate_chat )
	{
		if ( ( $login == "" ) || ( $password == "" )
			|| ( $contact_name == "" ) || ( $contact_email == "" )
			|| ( $company == "" ) || ( $max_dept == "" )
			|| ( $max_users == "" ) )
		{
			return false ;
		}

		global $VALIDATE_KEY ;
		if ( !isset( $VALIDATE_KEY ) || ( $VALIDATE_KEY != 1975 ) )
			return false ;
		$now = time() ;

		$query = "SELECT aspID FROM chat_asp WHERE login = '$login'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow ( $dbh ) ;

			if ( $data['userID'] )
			{
				return false ;
			}

			$query = "INSERT INTO chat_asp VALUES (0, '$login', '$password', '$company', '$contact_name', '$contact_email', '$max_dept', '$max_users', '$footprints', $now, 0, '$active_status', '$initiate_chat', 'If you would like to receive a copy of this chat session transcript, please input your email address below and Submit.', 'Hello %%username%%,

Below is the complete transcript of your chat session:
===
%%transcript%%
===

Thank you


')" ;
			database_mysql_query( $dbh, $query ) ;

			if ( $dbh[ 'ok' ] )
			{
				$id = database_mysql_insertid ( $dbh ) ;
				return $id ;
			}
			return false ;
		}
		return false ;
	}

?>