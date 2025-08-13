<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	/*******************************************************
	* Main thing is, we don't want this script to take too much
	* time in processing, since it's the main heart of PHP Live!
	* So, keep it basic in here.  We just get what we want done
	* and then load the page.
	*******************************************************/
	session_start() ;
	$j_string = "" ;
	$reload_main = $start = 0 ;
	$session_chat = $HTTP_SESSION_VARS['session_chat'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : $HTTP_POST_VARS['sid'] ;
	if ( isset( $HTTP_POST_VARS['sessionid'] ) ) { $sessionid = $HTTP_POST_VARS['sessionid'] ; }
	if ( isset( $HTTP_GET_VARS['sessionid'] ) ) { $sessionid = $HTTP_GET_VARS['sessionid'] ; }
	if ( isset( $HTTP_POST_VARS['requestid'] ) ) { $requestid = $HTTP_POST_VARS['requestid'] ; }
	if ( isset( $HTTP_GET_VARS['requestid'] ) ) { $requestid = $HTTP_GET_VARS['requestid'] ; }
	if ( isset( $HTTP_POST_VARS['start'] ) ) { $start = $HTTP_POST_VARS['start'] ; }
	if ( isset( $HTTP_GET_VARS['start'] ) ) { $start = $HTTP_GET_VARS['start'] ; }

	if ( !file_exists( "web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "web/conf-init.php" ) )
	{
		print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting...</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;	
	include_once("./web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./API/sql.php") ;
	include_once("./API/Chat/get.php") ;

	// initialize

	mt_srand ((double) microtime() * 1000000);
	$rand = mt_rand() ;

	// functions

	// see if chat file contains data... if so, then get it and put it on their screen
	if ( file_exists( "./web/chatsessions/".$session_chat[$sid]['chatfile_get'] ) && !is_dir( "./web/chatsessions/".$session_chat[$sid]['chatfile_get'] ) )
	{
		$data = file( "./web/chatsessions/".$session_chat[$sid]['chatfile_get'] ) ;
		$string_to_write = join( "", $data ) ;
		// lets take out the javascript so when viewing it, it doesn't give error or
		// try to push the page...
		$transcript_string = preg_replace( "/<script(.*?)<\/script>/", "", $string_to_write ) ;
		$HTTP_SESSION_VARS['session_chat'][$sid]['transcript'] .= $transcript_string ;

		// let's delete the file so we know we got the data and is now ready for new data
		unlink( "./web/chatsessions/".$session_chat[$sid]['chatfile_get'] ) ;
		// remove the write flag file
		if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/w_".$session_chat[$sid]['chatfile_put'] ) )
			unlink( "$DOCUMENT_ROOT/web/chatsessions/w_".$session_chat[$sid]['chatfile_put'] ) ;

		// if it is admin, then lets take out the pushing so he/she does not see it... besides
		// admin is the one pushing the page... option for visitor to send commands is not
		// enabled yet.. but maybe in future... so keep this check here for now
		if ( $session_chat[$sid]['isadmin'] )
		{
			// JKEEP is a flag to indicate NOT to strip the JavaScript
			if ( !preg_match( "/<JKEEP>/", $string_to_write ) )
				$string_to_write = $transcript_string ;
		}
		$j_string = preg_replace( "/'/", "&#039;", $string_to_write ) ;
		$reload_main = 1 ;
	}
?>
<html><head><title> [chat session] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<script language="JavaScript">
<!--
	<? if ( $reload_main || $start ): ?>
		window.parent.window.do_write('<? echo $j_string ?>') ;
	<? endif ; ?>

	var pullimage ;
	var loaded = 0 ;
	var pullimage = new Image ;

	function checkifloaded()
	{
		loaded = pullimage.width ;
		if ( loaded == 1 )
			do_reload() ;
		else if ( loaded == 2 )
		{
			window.parent.window.toggle('Lwrite') ;
		}
		else
		{
			window.parent.window.toggle('Lwriteno') ;
		}
	}

	function dounique()
	{
		var date = new Date() ;
		return date.getTime() ;
	}

	function do_reload()
	{
		window.parent.window.toggle('Lwriteno') ;
		location.href = "chat_session.php?rand=<? echo $rand ?>&sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>" ;
	}

	function do_pull()
	{
		var unique = dounique() ;
		pullimage = new Image ;

		pullimage.src = '<? echo $BASE_URL ?>/pull/chat.php?sid=<? echo $sid ?>&sessionid=<? echo $sessionid ?>&requestid=<? echo $requestid ?>&unique='+unique+"&iswriting="+window.parent.window.iswriting ;
		pullimage.onload = checkifloaded ;
		setTimeout("do_pull()",2000) ;
	}
//-->
</script></head>
<body bgColor="<? echo $CHAT_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" OnLoad="do_pull()">
<? if ( ( !$start ) && ( $session_chat[$sid]['sound'] == "on" ) ): ?>
<EMBED src="sounds/receive.wav" width=0 height=0 autostart=true loop=false>
<? endif ; ?>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body></html>
<?
	mysql_close( $dbh['con'] ) ;
?>