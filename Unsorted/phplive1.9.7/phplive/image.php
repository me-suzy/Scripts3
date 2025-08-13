<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	if ( !file_exists( "./web/".$HTTP_GET_VARS['l']."/".$HTTP_GET_VARS['l']."-conf-init.php" ) || !file_exists( "./web/conf-init.php" ) )
	{
		if ( preg_match( "/(osicode.com)|(osicodes.com)|(osicodes.net)|(phplivesupport.com)/", $HTTP_SERVER_VARS['SERVER_NAME'] ) )
		{
			$image_path = "http://www.osicode.com/pics/ad.gif" ;
			Header( "Content-type: image/gif" ) ;
			readfile( $image_path ) ;
		}
		else
			print "<font color=\"#FF0000\">Config error: reason: $HTTP_GET_VARS[l] config not found!  Exiting... [image.php]</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;
	include_once("./web/".$HTTP_GET_VARS['l']."/".$HTTP_GET_VARS['l']."-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./API/sql.php") ;
	include_once("./API/Users/get.php") ;
	include_once("./API/Footprint/get.php") ;
	include_once("./API/Footprint/put.php") ;
	include_once("./API/Footprint_unique/put.php") ;
	include_once("./API/Refer/put.php") ;

	if ( $SUPPORT_LOGO_OFFLINE )
		$status_image = $SUPPORT_LOGO_OFFLINE ;
	else
		$status_image = "phplive_support_offline.gif" ;

	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; } else { $deptid = "" ; }
	if ( isset( $HTTP_GET_VARS['page'] ) ) { $page = urldecode( $HTTP_GET_VARS['page'] ) ; } else { $page = "" ; }

	$deptinfo = AdminUsers_get_DeptInfo( $dbh, $deptid, $HTTP_GET_VARS['x'] ) ;
	if ( $deptinfo['status_image_offline'] && file_exists( "$DOCUMENT_ROOT/web/".$HTTP_GET_VARS['l']."/$deptinfo[status_image_offline]" ) )
		$status_image = $deptinfo['status_image_offline'] ;

	if ( AdminUsers_get_AreAnyAdminOnline( $dbh, $deptid, $admin_idle, $HTTP_GET_VARS['x'] ) )
	{
		if ( $SUPPORT_LOGO_ONLINE )
			$status_image = $SUPPORT_LOGO_ONLINE ;
		else
			$status_image = "phplive_support_online.gif" ;

		if ( $deptinfo['status_image_online'] && file_exists( "$DOCUMENT_ROOT/web/".$HTTP_GET_VARS['l']."/$deptinfo[status_image_online]" ) )
			$status_image = $deptinfo['status_image_online'] ;
	}

	// get ips that SHOULD NOT be tracked
	$ip_notrack_string = "" ;
	if ( isset( $IPNOTRACK ) )
		$ip_notrack_string = $IPNOTRACK ;

	// do the tracking, if needed
	if ( $VISITOR_FOOTPRINT && !preg_match( "/(phplive_notally)/", $page ) && !preg_match( "/$HTTP_SERVER_VARS[REMOTE_ADDR]/", $ip_notrack_string ) )
		ServiceFootprint_put_Footprint( $dbh, $HTTP_SERVER_VARS['REMOTE_ADDR'], $page, $HTTP_GET_VARS['x'] ) ;
	
	// track refer
	$refer = urldecode( $HTTP_GET_VARS['refer'] ) ;
	ServiceRefer_put_Refer( $dbh, $HTTP_GET_VARS['x'], $HTTP_SERVER_VARS['REMOTE_ADDR'], $refer ) ;

	// put unique footprint for client pull
	if ( !preg_match( "/(phplive_notally)/", $page ) && !preg_match( "/$HTTP_SERVER_VARS[REMOTE_ADDR]/", $ip_notrack_string ) )
		ServiceFootprintUnique_put_Footprint( $dbh, $HTTP_SERVER_VARS['REMOTE_ADDR'], $page, $HTTP_GET_VARS['x'] ) ;

	if ( file_exists( "$DOCUMENT_ROOT/web/".$HTTP_GET_VARS['l']."/$status_image" ) && $status_image )
		$image_path = "$DOCUMENT_ROOT/web/".$HTTP_GET_VARS['l']."/$status_image" ;
	else
		$image_path = "$DOCUMENT_ROOT/pics/$status_image" ;

	// check for restricted only domains
	$url_restriction_string_array = ARRAY() ;
	$url_restriction_string = "" ;
	$from_page = "" ;
	if ( file_exists( "$DOCUMENT_ROOT/web/".$HTTP_GET_VARS['l']."/url_restriction.txt" ) )
	{
		$url_restriction_string_array = file( "$DOCUMENT_ROOT/web/".$HTTP_GET_VARS['l']."/url_restriction.txt" ) ;
		$url_restriction_string = $url_restriction_string_array[0] ;
	}
	if ( isset( $HTTP_SERVER_VARS['HTTP_REFERER'] ) ) {  $from_page = $HTTP_SERVER_VARS['HTTP_REFERER'] ; }
	if ( !$from_page )
		$from_page = $page;
	preg_match( "/^(http:\/\/)?([^\/]+)/i", $from_page, $matches ) ;
	// check to see if domain is internal (such as localhost). if it is, skip the
	// second layer of domain name check (taking out www. from www.domain.com)
	$domain = "" ;
	if ( isset( $matches[2] ) )
	{
		$domain = $matches[2] ;
		if ( preg_match( "/\./", $domain ) && $domain )
		{
			preg_match( "/[^\.\/]+\.[^\.\/]+$/", $domain, $matches ) ;
			$domain = $matches[0] ;
		}
	}
	if ( !preg_match( "/$domain/", $url_restriction_string ) && ( $url_restriction_string != "" ) )
		$image_path = "" ;

	mysql_close( $dbh['con'] ) ;
	Header( "Content-type: image/gif" ) ;
	readfile( $image_path ) ;
?>