<?
	/*****  ServiceTranscripts::put  ***************************************
	 *
	 *  $Id: put.php,v 1.4 2002/06/11 09:02:34 osicodes Exp $
	 *
	 ****************************************************************/

	if ( ISSET( $_OFFICE_PUT_ServiceTranscripts_LOADED ) == true )
		return ;

	$_OFFICE_PUT_ServiceTranscripts_LOADED = true ;

	/*****

	   Internal Dependencies

	*****/

	/*****

	   Module Specifics

	*****/

	/*****

	   Module Functions

	*****/

	/*****  ServiceTranscripts_put_ChatTranscript  ******************************
	 *
	 *  Parameters:
	 *	&$dbh
	 *		Database connection.
	 *	$sessionid
	 *	$transcript
	 *	$userid
	 *	$from_screen_name
	 *	$department
	 *	$aspid
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$id ( inserted id )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				Dec 15, 2001
	 *
	 *****************************************************************/
	FUNCTION ServiceTranscripts_put_ChatTranscript( &$dbh,
				$sessionid,
				$transcript,
				$userid,
				$from_screen_name,
				$deptid,
				$aspid )
	{
		if ( ( $transcript == "" ) || ( $userid == "" )
			|| ( $deptid == "" ) || ( $from_screen_name == "" )
			|| ( $sessionid == "" ) || ( $aspid == "" ) )
		{
			return false ;
		}

		$now = time() ;

		$plain = preg_replace( "/<STRIP_FOR_PLAIN>(.*?)<\/STRIP_FOR_PLAIN>/", "", $transcript ) ;
		$plain = preg_replace( "/Chat \[dynamic box\]/", "", $plain ) ;
		$plain = addslashes( strip_tags( $plain ) ) ;
		$plain = preg_replace( "/'/", "&#039;", $plain ) ;

		$transcript = preg_replace( "/<script(.*?)<\/script>/", "", $transcript ) ;
		$transcript = preg_replace( "/<body(.*?)>/", "", $transcript ) ;
		$transcript = preg_replace( "/'/", "&#039;", $transcript ) ;

		$query = "SELECT * FROM chattranscripts WHERE chat_session = '$sessionid'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			
			if ( $data['chat_session'] )
				$query = "UPDATE chattranscripts SET userID = $userid, from_screen_name = '$from_screen_name', created = $now, deptID = '$deptid', plain = '$plain', formatted = '$formatted', aspID = $aspid WHERE chat_session = '$chat_session'" ;
			else
				$query = "INSERT INTO chattranscripts VALUES('$sessionid', $userid, '$from_screen_name', $now, '$deptid', '$plain', '$transcript', $aspid)" ;
			database_mysql_query( $dbh, $query ) ;

			if ( $dbh[ 'ok' ] )
			{
				return true ;
			}
		}
		return false ;
	}
?>