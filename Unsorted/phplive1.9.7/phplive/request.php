<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$l = ( isset( $HTTP_GET_VARS['l'] ) ) ? $HTTP_GET_VARS['l'] : "" ;
	$x = ( isset( $HTTP_GET_VARS['x'] ) ) ? $HTTP_GET_VARS['x'] : "" ;
	$action = ( isset( $HTTP_GET_VARS['action'] ) ) ? $HTTP_GET_VARS['action'] : "" ;
	$sessionid = ( isset( $HTTP_GET_VARS['sessionid'] ) ) ? $HTTP_GET_VARS['sessionid'] : "" ;
	$requestid = ( isset( $HTTP_GET_VARS['requestid'] ) ) ? $HTTP_GET_VARS['requestid'] : "" ;
	$userid = ( isset( $HTTP_GET_VARS['userid'] ) ) ? $HTTP_GET_VARS['userid'] : "" ;
	$ip = ( isset( $HTTP_GET_VARS['ip'] ) ) ? $HTTP_GET_VARS['ip'] : "" ;
	$question = ( isset( $HTTP_GET_VARS['question'] ) ) ? $HTTP_GET_VARS['question'] : "" ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : "" ;
	$page = ( isset( $HTTP_GET_VARS['page'] ) ) ? $HTTP_GET_VARS['page'] : "" ;
	$deptid = ( isset( $HTTP_GET_VARS['deptid'] ) ) ? $HTTP_GET_VARS['deptid'] : "" ;

	if ( !file_exists( "web/$l/$l-conf-init.php" ) || !file_exists( "web/conf-init.php" ) )
	{
		if ( preg_match( "/(osicode.com)|(osicodes.com)|(osicodes.net)|(phplivesupport.com)/", $HTTP_SERVER_VARS['SERVER_NAME'] ) )
			HEADER( "location: http://www.osicode.com/ad.php" ) ;
		else
			print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting... [request.php]</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;
	include_once("./web/$l/$l-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./lang_packs/$LANG_PACK.php") ;
?>
<?
	// initialize
	if ( !isset( $HTTP_SESSION_VARS['session_chat'] ) )
	{
		session_register( "session_chat" ) ;
		$session_chat = ARRAY() ;
		$HTTP_SESSION_VARS['session_chat'] = ARRAY() ;
	}
?>
<?
	// functions
?>
<?
	// conditions
	// check to see if chat is initiated by admin... if so, then take them to the initiated
	// chat, not to requesting chat.
	$remote_addr = $HTTP_SERVER_VARS['REMOTE_ADDR'] ;
	if ( file_exists( "$DOCUMENT_ROOT/web/chatrequests/$remote_addr" ) && $remote_addr )
	{
		$action = "initiate_accept" ;
	}
?>
<html>
<head>
<title> <? echo $LANG['TITLE_SUPPORTREQUEST'] ?> </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
</head>
<frameset rows="8,*,8" cols="*" border="0" frameborder="0" framespacing=0>
	<frame src="request_ftop.php?l=<? echo $l ?>" name="ftop" noresize border=0 scrolling=no>
	<frameset rows="*" cols="9,*,9" border="0" frameborder="0" framespacing=0>
		<frame src="request_fleft.php?l=<? echo $l ?>" name="fleft" noresize border=0 scrolling=no>
		<frame src="request_fmain.php?l=<? echo $l ?>&x=<? echo $x ?>&deptid=<? echo $deptid ?>&page=<? echo $page ?>&action=<? echo $action ?>&sessionid=<? echo $sessionid ?>&requestid=<? echo $requestid ?>&sid=<? echo $sid ?>&userid=<? echo $userid ?>&ip=<? echo $ip ?>&question=<? echo $question ?>" name="fmain" noresize border=0 scrolling=auto>
		<frame src="request_fright.php?l=<? echo $l ?>" name="fright" noresize border=0 scrolling=no>
	</frameset>
	<frame src="request_fbottom.php?l=<? echo $l ?>" name="fbottom" noresize border=0 scrolling=no>
</frameset>
<noframes>
</html>