<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$window_close = 0 ;
	$action = $sessionid = $j_string = "" ;
	$session_chat = $HTTP_SESSION_VARS['session_chat'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : $HTTP_POST_VARS['sid'] ;
	if ( isset( $HTTP_POST_VARS['sessionid'] ) ) { $sessionid = $HTTP_POST_VARS['sessionid'] ; }
	if ( isset( $HTTP_GET_VARS['sessionid'] ) ) { $sessionid = $HTTP_GET_VARS['sessionid'] ; }
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }

	if ( !file_exists( "web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "web/conf-init.php" ) )
	{
		print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting...</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;	include_once("./web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./lang_packs/$LANG_PACK.php") ;
	include_once("./API/sql.php") ;
	include_once("./API/Chat/Util.php") ;
	include_once("./API/Chat/get.php") ;
	include_once("./API/Chat/remove.php") ;
?>
<?
	// initialize
	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "50" ;
	else
		$text_width = "25" ;
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "submit" )
	{
		$message = strip_tags( $HTTP_POST_VARS['message'] ) ;
		$message = stripslashes( $message ) ;

		// put in admin commands IF session is admin
		if ( $session_chat[$sid]['isadmin'] )
			$message = UtilChat_ParseForCommands( $message ) ;
		else
		{
			// see if the operator is still the same.  if the chat has been
			// transferred, we want to make sure we update the admin name
			$session_parties = ServiceChat_get_ChatSessionLogins( $dbh, $sessionid ) ;
			if ( isset ( $session_parties['admin'] ) && ( $session_parties['admin'] != $session_chat[$sid]['admin_name'] ) )
			{
				$HTTP_SESSION_VARS['session_chat'][$sid]['admin_name'] = $session_parties['admin'] ;
				$session_chat[$sid]['admin_name'] = $session_parties['admin'] ;
			}
		}

		$string = "<STRIP_FOR_PLAIN><font color=\"$CLIENT_COLOR\">".$session_chat[$sid]['screen_name']."</font> : </STRIP_FOR_PLAIN>$message<br>" ;
		if ( $session_chat[$sid]['isadmin'] )
			$string = "<STRIP_FOR_PLAIN><b><font color=\"$ADMIN_COLOR\">".$session_chat[$sid]['screen_name']."</font> : </STRIP_FOR_PLAIN>$message</b><br>" ;

		$transcript_string = preg_replace( "/<script(.*?)<\/script>/", "", $string ) ;
		$HTTP_SESSION_VARS['session_chat'][$sid]['transcript'] .= $transcript_string ;
		// if it is admin, then lets take out the pushing so he/she does not see it... besides
		// admin is the one pushing the page
		if ( $session_chat[$sid]['isadmin'] )
			$j_string = $transcript_string ;
		else
			$j_string = $string ;
		$j_string = preg_replace( "/'/", "&#039;", $j_string ) ;
		UtilChat_AppendToChatfile( $session_chat[$sid]['chatfile_put'], $string ) ;
		UtilChat_AppendToChatfile( $session_chat[$sid]['chatfile_transcript'], $string ) ;

		if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/w_".$session_chat[$sid]['chatfile_put'] ) )
			unlink( "$DOCUMENT_ROOT/web/chatsessions/w_".$session_chat[$sid]['chatfile_put'] ) ;
	}
	else if ( $action == "close" )
	{
		ServiceChat_remove_ChatSessionListByScreenName( $dbh, $sessionid, $session_chat[$sid]['screen_name'] ) ;
		// let's remove chat initiate flag if it exists
		if ( file_exists( "$DOCUMENT_ROOT/web/chatrequests/".$session_chat[$sid]['initiate'] ) && $session_chat[$sid]['initiate'] )
			unlink( "$DOCUMENT_ROOT/web/chatrequests/".$session_chat[$sid]['initiate'] ) ;
		if ( file_exists( "$DOCUMENT_ROOT/web/chatsessions/w_".$session_chat[$sid]['chatfile_put'] ) )
			unlink( "$DOCUMENT_ROOT/web/chatsessions/w_".$session_chat[$sid]['chatfile_put'] ) ;
		$window_close = 1 ;
	}
?>
<html>
<head>
<title> Chat [writer] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="./css/base.css">

<script language="JavaScript">
<!--
	function do_loaded()
	{
		window.parent.window.do_write('<? echo $j_string ?>') ;
		document.form.message.focus() ;
		document.form.message.value = "" ;
		if ( <? echo $window_close ?> )
		{
			if ( <? echo $session_chat[$sid]['isadmin'] ?> )
				parent.window.parent.window.close() ;
			else
				parent.window.location.href ='email_transcript.php?sid=<? echo $sid ?>&sessionid=<? echo $sessionid ?>' ;
		}
	}

	function send_iswriting()
	{
		window.parent.window.toggle_iswriting(1) ;
	}
//-->
</script>

</head>
<body bgColor="<? echo $CHAT_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" OnLoad="do_loaded()" OnUnload="window.parent.window.toggle_iswriting(0)">
<center>
<form method="POST" action="chat_write.php" name="form">
<input type="hidden" name="action" value="submit">
<input type="hidden" name="sessionid" value="<? echo $sessionid ?>">
<input type="hidden" name="sid" value="<? echo $sid ?>">
<table cellspacing=0 cellpadding=1 border=0>
<tr>
	<td><input type="text" name="message" size="<? echo $text_width ?>" class="input" autocomplete="off" onKeyPress="send_iswriting()"></td>
	<td><font size=2><input type=image src="pics/buttons/submit.gif" border=0 alt="<? echo $LANG['WORD_SEND'] ?>"></td>
	<td nowrap><span class="smalltxt">&nbsp;[<a href="chat_write.php?action=close&sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>"><? echo $LANG['WORD_CLOSE'] ?></a>]</span></td>
</tr>
</table>
</form>
</center>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>