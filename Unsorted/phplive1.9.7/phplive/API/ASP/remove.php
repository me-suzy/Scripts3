<?
	/*****  AdminASP::remove  ***************************************
	 *
	 *  $Id: remove.php,v 1.4 2002/06/18 07:15:46 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_REMOVE_AdminASP_LOADED ) == true )
		return ;

	$_OFFICE_REMOVE_AdminASP_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/
	include_once( "$DOCUMENT_ROOT/API/ASP/get.php" ) ;
	include_once( "$DOCUMENT_ROOT/API/Users/get.php" ) ;
	include_once( "$DOCUMENT_ROOT/API/Users/remove.php" ) ;

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  AdminASP_remove_user  ************************************
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
	 *	true ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				jan 23, 2002
	 *
	 *****************************************************************/
	FUNCTION AdminASP_remove_user( &$dbh,
						$aspid )
	{
		if ( $aspid == "" )
		{
			return false ;
		}

		global $DOCUMENT_ROOT ;

		/*************************************************
		* we do not mess with the chatsession tables.. tables related
		* to chat gets auto cleaned
		*************************************************/

		$aspinfo = AdminASP_get_UserInfo( $dbh, $aspid ) ;

		if ( isset( $aspinfo['aspID'] ) )
		{
			$users = AdminUsers_get_AllUsers( $dbh, 0, 0, $aspid ) ;
			for ( $c = 0; $c < count( $users ); ++$c )
			{
				$user = $users['userID'] ;
				$query = "DELETE FROM chatcanned WHERE userID = $user[userID]" ;
				database_mysql_query( $dbh, $query ) ;
				$query = "DELETE FROM chatuserdeptlist WHERE userID = $user[userID]" ;
				database_mysql_query( $dbh, $query ) ;
				$query = "DELETE FROM chattranscripts WHERE userID = $user[userID]" ;
				database_mysql_query( $dbh, $query ) ;
				$query = "DELETE FROM chatrequestlogs WHERE userID = $user[userID]" ;
				database_mysql_query( $dbh, $query ) ;
				$query = "DELETE FROM chat_admin WHERE userID = $user[userID]" ;
				database_mysql_query( $dbh, $query ) ;
			}
			// do another run down ONLY using aspID
			$query = "DELETE FROM chat_admin WHERE aspID = $aspid" ;
			database_mysql_query( $dbh, $query ) ;
			$query = "DELETE FROM chatdepartments WHERE aspID = $aspid" ;
			database_mysql_query( $dbh, $query ) ;
			$query = "DELETE FROM chatfootprints WHERE aspID = $aspid" ;
			database_mysql_query( $dbh, $query ) ;
			$query = "DELETE FROM chatrequestlogs WHERE aspID = $aspid" ;
			database_mysql_query( $dbh, $query ) ;
			$query = "DELETE FROM chattranscripts WHERE aspID = $aspid" ;
			database_mysql_query( $dbh, $query ) ;
			$query = "DELETE FROM chatfootprintsunique WHERE aspID = $aspid" ;
			database_mysql_query( $dbh, $query ) ;

			$query = "DELETE FROM chat_asp WHERE aspID = $aspid" ;
			database_mysql_query( $dbh, $query ) ;

			// now let's clean the user files from the system
			if ( $dir = @opendir( "$DOCUMENT_ROOT/web/$aspinfo[login]" ) )
			{
				while( $file = readdir( $dir ) )
				{
					if ( preg_match( "/[A-Za-z0-9]/", $file ) )
						unlink( "$DOCUMENT_ROOT/web/$aspinfo[login]/$file" ) ;
				} 
				closedir($dir) ;
				rmdir( "$DOCUMENT_ROOT/web/$aspinfo[login]" ) ;
			}
			return true ;
		}
		else
			return false ;
	}

?>
