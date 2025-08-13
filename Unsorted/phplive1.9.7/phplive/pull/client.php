<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$sid = $x = "" ;
	$session_admin = $HTTP_SESSION_VARS['session_admin'] ;
	if ( isset( $HTTP_GET_VARS['sid'] ) ) { $sid = $HTTP_GET_VARS['sid'] ; }
	if ( isset( $HTTP_GET_VARS['x'] ) ) { $x = $HTTP_GET_VARS['x'] ; }

	include_once("../web/conf-init.php") ;
	include_once("$DOCUMENT_ROOT/system.php") ;
	include_once("$DOCUMENT_ROOT/API/sql.php") ;
	include_once("$DOCUMENT_ROOT/API/Footprint_unique/get.php") ;

	// get all the non-idle/non-expired visitors
	$total_active_footprints = ServiceFootprintUnique_get_TotalActiveFootprints( $dbh, $x ) ;
	mysql_close( $dbh['con'] ) ;

	$image_path = "$DOCUMENT_ROOT/pics/counters/0.gif" ;
	if ( file_exists( "$DOCUMENT_ROOT/pics/counters/$total_active_footprints.gif" ) )
		$image_path = "$DOCUMENT_ROOT/pics/counters/$total_active_footprints.gif" ;
	else if ( $total_active_footprints > 20 )
		$image_path = "$DOCUMENT_ROOT/pics/counters/21.gif" ;

	// if the active visitors are changed (reduced or increased), let's put the
	// image so it tells the system to reload the window
	if ( $total_active_footprints != $session_admin[$sid]['active_footprints'] )
	{
		$HTTP_SESSION_VARS['session_admin'][$sid]['active_footprints'] = $total_active_footprints ;
		Header( "Content-type: image/gif" ) ;
		readfile( $image_path ) ;
	}
?>
