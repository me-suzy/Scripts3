<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$session_chat = $HTTP_SESSION_VARS['session_chat'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : "" ;
	$requestid = ( isset( $HTTP_GET_VARS['requestid'] ) ) ? $HTTP_GET_VARS['requestid'] : $HTTP_POST_VARS['requestid'] ;
	$sessionid = ( isset( $HTTP_GET_VARS['sessionid'] ) ) ? $HTTP_GET_VARS['sessionid'] : $HTTP_POST_VARS['sessionid'] ;
	$userid = ( isset( $HTTP_GET_VARS['userid'] ) ) ? $HTTP_GET_VARS['userid'] : $HTTP_POST_VARS['userid'] ;
	if ( !file_exists( "web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "web/conf-init.php" ) )
	{
		print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting... [chat.php]</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;	
	include_once("./web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./lang_packs/$LANG_PACK.php") ;
	include_once("./API/sql.php") ;
	include_once("./API/Chat/update.php") ;

	// set frame row properties depending if admin or regular request
	$frame_row_properties = "60,100%,33,36,20,*" ;
	if ( $session_chat[$sid]['isadmin'] )
		$frame_row_properties = "60,100%,33,36,73,20,140,*" ;
	
	// update the status so it does not show up on the admin area, if it is admin
	if ( $session_chat[$sid]['isadmin'] )
		ServiceChat_update_ChatRequestStatus( $dbh, $requestid, 0 ) ;
	
	// let's start the poll time
	$HTTP_SESSION_VARS['session_chat'][$sid]['admin_poll_time'] = time() ;
	$window_title = preg_replace( "/<(.*)>/", "", $session_chat[$sid]['visitor_name'] ) .": Support Request" ;
?>
<html>
<head>
<title> <? echo $window_title  ?> </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<script language="JavaScript" src="./js/browsercheck.js"></script>
<script language="JavaScript">
<!--
	var iswriting = 0 ;

	function pushit( url, winname )
	{
		window.open( url, winname, "scrollbars=yes,menubar=no,toolbar=yes,resizable=1,location=yes") ;
		parent.window.focus() ;
	}

	function toggle_iswriting( value )
	{
		iswriting = value ;
	}
//-->
</script>
</head>
<frameset rows="<? echo $frame_row_properties ?>" cols="*" border="0" frameborder="0">
	<!-- header to show logo and options -->
	<frame src="chat_header.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&userid=<? echo $userid ?>" name="header" noresize border=0 scrolling=no>
	<!-- the area where messages are displayed -->
	<frame src="chat_box.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>" name="main" noresize border=0 scrolling=auto>
	<!-- the chat writer form -->
	<frame src="chat_write.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>" name="writer" noresize border=0 scrolling=no>
	<frame src="chat_options.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>" name="options" noresize border=0 scrolling=no>
	<? if ( $session_chat[$sid]['isadmin'] ): ?>
		<!-- show admin area if admin -->
		<frame src="chat_admin_canned.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>" name="admin_canned" noresize border=0 scrolling=no>
		<frame src="chat_admin_options.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>" name="admin_options" noresize border=0 scrolling=no>
		<frame src="chat_admin_vinfo.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>" name="admin_main" noresize border=0 scrolling=auto>
	<? else: ?>
	<frame src="chat_powered.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>" name="powered" noresize border=0 scrolling=no>
	<? endif ; ?>
	<!-- session check -->
	<frame src="chat_session.php?sessionid=<? echo $sessionid ?>&requestid=<? echo $requestid ?>&sid=<? echo $sid ?>&start=1" name="session" noresize border=0 scrolling=no>
</frameset>
<noframes>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>