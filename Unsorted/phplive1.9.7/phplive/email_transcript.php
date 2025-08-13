<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$action = $sessionid = "" ;
	$session_chat = $HTTP_SESSION_VARS['session_chat'] ;
	if ( isset( $HTTP_POST_VARS['sid'] ) ) { $sid = $HTTP_POST_VARS['sid'] ; }
	if ( isset( $HTTP_GET_VARS['sid'] ) ) { $sid = $HTTP_GET_VARS['sid'] ; }
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
	include_once("./API/sql.php" ) ;
	include_once("./API/ASP/get.php") ;
	include_once("./API/Users/get.php") ;
?>
<?
	// initialize
	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "35" ;
	else
		$text_width = "15" ;
	
	$aspinfo = AdminASP_get_UserInfo( $dbh, $session_chat[$sid]['aspID'] ) ;
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "submit" )
	{
		$admin = AdminUsers_get_UserInfo( $dbh, $session_chat[$sid]['admin_id'], $session_chat[$sid]['aspID'] ) ;
		$department = AdminUsers_get_DeptInfo( $dbh, $session_chat[$sid]['deptid'], $session_chat[$sid]['aspID'] ) ;
		$date = date( "D m/d/y h:i a", time() ) ;
		$subject = "$aspinfo[company] Chat Transcript" ;
		$header = "$LANG[WORD_COMPANY]: $COMPANY_NAME
$LANG[WORD_DEPARTMENT]: $department[name]
$LANG[WORD_NAME]: $admin[name]
$LANG[WORD_EMAIL]: $admin[email]
$LANG[WORD_DAY]: $date" ;
		$transcript = preg_replace( "/Chat \[dynamic box\]/", "", $session_chat[$sid]['transcript'] ) ;
		$transcript = preg_replace( "/<br>/", "\n", $transcript ) ;
		$transcript = preg_replace( "/<hr>/", "\n---------------------------------------------\n", $transcript ) ;
		$transcript = preg_replace( "/<(.*?)>/", "", $transcript ) ;
		$transcript = "$header $transcript" ;
		$transcript = wordwrap( $transcript, 70 ) ;
		$visitor_name = preg_replace( "/<(.*?)>/", "", $session_chat[$sid]['visitor_name'] ) ;

		$message = preg_replace( "/%%transcript%%/", $transcript, stripslashes( $aspinfo['trans_email'] ) ) ;
		$message = preg_replace( "/%%username%%/", $visitor_name, $message ) ;
		mail( $HTTP_POST_VARS['email'], $subject, $message, "From: $aspinfo[company] <$aspinfo[contact_email]>") ;
	}
?>
<html>
<head>
<title> [ no title - inside frame - will not show ] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="css/base.css">
<script language="JavaScript" src="js/newwin.js"></script>

<script language="JavaScript">
<!--
	function do_submit()
	{
		if ( document.form.email.value == "" )
			alert( "<? echo $LANG['MESSAGE_BOX_JS_A_ALLFIELDSSUP'] ?>" ) ;
		else if ( document.form.email.value.indexOf("@") == -1 )
			alert( "<? echo $LANG['MESSAGE_BOX_JS_A_INVALIDEMAIL'] ?>" ) ;
		else
			document.form.submit() ;
	}

	function viewit()
	{
		window.open( "chat_viewit.php?sid=<? echo $sid ?>", "<? echo time() ?>", "scrollbars=yes,menubar=no,toolbar=no,resizable=0,location=no,width=450,height=520" ) ;
	}
//-->
</script>

</head>
<body bgColor="<? echo $CHAT_REQUEST_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
<center>
<?
if ( file_exists( "web/".$session_chat[$sid]['asp_login']."/$LOGO" ) && $LOGO )
	$logo = "web/".$session_chat[$sid]['asp_login']."/$LOGO" ;
else if ( file_exists( "web/$LOGO_ASP" ) && $LOGO_ASP )
	$logo = "web/$LOGO_ASP" ;
else
	$logo = "pics/phplive_logo.gif" ;
?>
<img src="<? echo $logo ?>">
<p>
<span class="basetxt">

<? if ( $action == "submit" ): ?>
<? echo $LANG['CHAT_TRANSCRIPT_SENT'] ?> <b><? echo $HTTP_POST_VARS['email'] ?></b>.
<p>
<a href="JavaScript:parent.window.close()"><img src="pics/buttons/close_window.gif" border=0 alt="<? echo $LANG['WORD_CLOSE'] ?>"></a>
<p>





<? else: ?>
<? echo stripslashes( $aspinfo['trans_message'] ) ?>
<p>
<form method="POST" action="email_transcript.php" name="form">
<input type="hidden" name="action" value="submit">
<input type="hidden" name="sid" value="<? echo $sid ?>">
<input type="hidden" name="sessionid" value="<? echo $sessionid ?>">
<table cellspacing=0 cellpadding=1 border=0>
<tr>
	<td><span class="basetxt"><font color="#FF0000">*</font> <? echo $LANG['WORD_EMAIL'] ?></td>
	<td><font size=2> <input type="text" name="email" size="<? echo $text_width ?>" maxlength="255" class="input"></td>
</tr>
<tr><td colspan=2>&nbsp;</td>
<tr>
	<td>&nbsp;</td>
	<td><span class="basetxt"><a href="JavaScript:do_submit()"><img src="pics/buttons/submit.gif" border=0 alt="<? echo $LANG['WORD_SEND'] ?>"></a> &nbsp; <a href="JavaScript:parent.window.close()"><img src="pics/buttons/close_window.gif" border=0 alt="<? echo $LANG['WORD_CLOSE'] ?>"></td>
</tr>
<tr>
	<td></td>
	<td><span class="basetxt"><? echo $LANG['CHAT_PRINTER_FRIENDLY'] ?> &nbsp; <a href="JavaScript:viewit()"><img src="pics/printer.gif" border=0 alt="<? echo $LANG['CHAT_PRINTER_FRIENDLY'] ?>"></a></td>
</tr>
</table>
</form>

<? endif ; ?>

<br>
&nbsp; <span class="smalltxt"><? echo $LANG['DEFAULT_BRANDING'] ?> &copy; OSI Codes Inc.</span>


<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>
