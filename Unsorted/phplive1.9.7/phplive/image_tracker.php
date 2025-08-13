<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*
	* This script tracks users and updates their status as they visit different
	* pages.  Footprint is done with image.php, NOT this script.  This script
	* is mainly used to keep the visitor status current so it registers the visitor
	* as still on the site.
	*******************************************************/
	$page = $x = $l = "" ;
	if ( isset( $HTTP_GET_VARS['page'] ) ) { $page = $HTTP_GET_VARS['page'] ; }
	if ( isset( $HTTP_GET_VARS['x'] ) ) { $x = $HTTP_GET_VARS['x'] ; }
	if ( isset( $HTTP_GET_VARS['l'] ) ) { $l = $HTTP_GET_VARS['l'] ; }

	if ( !file_exists( "./web/$l/$l-conf-init.php" ) || !file_exists( "./web/conf-init.php" ) )
	{
		print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting...</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;
	include_once("./web/$l/$l-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./API/sql.php") ;
	include_once("./API/Footprint_unique/put.php") ;
	include_once("./API/Footprint_unique/remove.php") ;

	// clean up footprints first
	ServiceFootprintUnique_remove_IdleFootprints( $dbh, $x ) ;

	// image to load, if no request is made
	$image_path_no = "$DOCUMENT_ROOT/pics/empty_nodelete.gif" ;
	// image to load if request is made
	$image_path_yes = "$DOCUMENT_ROOT/pics/empty_nodelete2.gif" ;

	// put unique footprint for client pull
	$remote_addr = $HTTP_SERVER_VARS['REMOTE_ADDR'] ;
	$page = urldecode( $page ) ;
	if ( !preg_match( "/(phplive_notally)/", $page ) )
		ServiceFootprintUnique_put_Footprint( $dbh, $remote_addr, $page, $x ) ;
	
	mysql_close( $dbh['con'] ) ;
	if ( file_exists( "$DOCUMENT_ROOT/web/chatrequests/$remote_addr" ) && $remote_addr )
	{
		Header( "Content-type: image/gif" ) ;
		readfile( $image_path_yes ) ;
	}
	else
	{
		Header( "Content-type: image/gif" ) ;
		readfile( $image_path_no ) ;
	}
?>