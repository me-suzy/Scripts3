<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$save_success = $do_viewit = 0 ;
	$action = $sessionid = "" ;
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
	include_once("./web/conf-init.php") ;	
	include_once("./web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./lang_packs/$LANG_PACK.php") ;
	include_once("./API/sql.php") ;
	include_once("./API/Users/get.php") ;
	include_once("./API/Chat/get.php") ;
	include_once("./API/Chat/update.php") ;
	include_once("./API/Transcripts/put.php") ;
?>
<?
	// initialize
	$save_success = $do_viewit = 0 ;
	$admin = AdminUsers_get_UserInfo( $dbh, $session_chat[$sid]['admin_id'], $session_chat[$sid]['aspID'] ) ;
	$deptinfo = AdminUsers_get_DeptInfo( $dbh, $session_chat[$sid]['deptid'], $session_chat[$sid]['aspID'] ) ;
?>
<?
	// conditions
	if ( $action == "save" )
	{
		$sessioninfo = ServiceChat_get_ChatSessionInfo( $dbh, $sessionid ) ;
		if ( ServiceTranscripts_put_ChatTranscript( $dbh, $sessionid, $session_chat[$sid]['transcript'], $admin['userID'], $sessioninfo['screen_name'], $session_chat[$sid]['deptid'], $session_chat[$sid]['aspID'] ) )
			$save_success = 1 ;
	}
	else if ( $action == "viewit" )
	{
		// put ahead a few seconds to make up for IE stalling when there are a lot
		// of windows open... fixes so session does not say party has left
		$future_buffer = 10 ;
		ServiceChat_update_ChatActivityTime( $dbh, $session_chat[$sid]['screen_name'], $sessionid, $future_buffer ) ;
		$do_viewit = 1 ;
	}
?>
<html>
<head>
<title> chat [header] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<script language="JavaScript">
<!--
	function viewit()
	{
		window.open( "chat_viewit.php?sid=<? echo $sid ?>", "<? echo time() ?>", "scrollbars=yes,menubar=no,toolbar=no,resizable=0,location=no,width=450,height=520" ) ;
	}

	function saveit()
	{
		if ( confirm( "Save transcript (replaces previous saves of this transcript)?" ) )
			location.href = "chat_header.php?sessionid=<? echo $sessionid ?>&action=save&sid=<? echo $sid ?>" ;
	}

	function do_alert()
	{
		if ( <? echo $save_success ?> )
			alert( "Success!" ) ;
		if ( <? echo $do_viewit ?> )
			viewit() ;
	}
//-->
</script>
</head>
<body bgColor="<? echo $CHAT_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" OnLoad="do_alert()">
<table cellspacing=0 cellpadding=1 border=0 width="100%">
<tr>
	<td valign="bottom" width="150"><?
			if ( file_exists( "web/".$session_chat[$sid]['asp_login']."/$LOGO" ) && $LOGO )
				$logo = "web/".$session_chat[$sid]['asp_login']."/$LOGO" ;
			else if ( file_exists( "web/$LOGO_ASP" ) && $LOGO_ASP )
				$logo = "web/$LOGO_ASP" ;
			else
				$logo = "pics/phplive_logo.gif" ;
		?><img src="<? echo $logo ?>"></td>
	<td valign="bottom" align="right" width="100%">
		<table cellspacing=1 cellpadding=2 border=0>
		<tr>
			<? if ( ( $session_chat[$sid]['isadmin'] ) && !$deptinfo['transcript_save'] ): ?>
			<td valign="bottom"><a href="JavaScript:saveit()"><img src="pics/buttons/save.gif" border=0 alt="Save Transcript"></a></td>
			<? endif ; ?>
			<td valign="bottom"><a href="chat_header.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&action=viewit"><img src="pics/printer.gif" border=0 alt="<? echo $LANG['CHAT_PRINTER_FRIENDLY'] ?>"></a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>