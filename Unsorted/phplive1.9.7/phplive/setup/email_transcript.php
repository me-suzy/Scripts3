<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	if ( isset( $HTTP_SESSION_VARS['session_setup'] ) ) { $session_setup = $HTTP_SESSION_VARS['session_setup'] ; } else { HEADER( "location: index.php" ) ; exit ; }
	if ( !file_exists( "../web/$session_setup[login]/$session_setup[login]-conf-init.php" ) || !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: index.php" ) ;
		exit ;
	}
	include_once("../web/conf-init.php");
	include_once("../web/$session_setup[login]/$session_setup[login]-conf-init.php") ;
	include_once("../system.php") ;
	include_once("../lang_packs/$LANG_PACK.php") ;
	include_once("../API/sql.php" ) ;
	include_once("../API/ASP/update.php") ;
?>
<?

	// initialize
	$action = $error = "" ;
	$success = 0 ;

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
	{
		$text_width = "70" ;
		$textbox_width = "80" ;
	}
	else
	{
		$text_width = "35" ;
		$textbox_width = "40" ;
	}

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "update" )
	{
		AdminASP_update_TableValue( $dbh, $session_setup['aspID'], "trans_message", $HTTP_POST_VARS['trans_message'] ) ;
		AdminASP_update_TableValue( $dbh, $session_setup['aspID'], "trans_email", $HTTP_POST_VARS['trans_email'] ) ;
		$HTTP_SESSION_VARS['session_setup']['trans_message'] = $HTTP_POST_VARS['trans_message'] ;
		$HTTP_SESSION_VARS['session_setup']['trans_email'] = $HTTP_POST_VARS['trans_email'] ;
		$session_setup['trans_message'] = $HTTP_POST_VARS['trans_message'] ;
		$session_setup['trans_email'] = $HTTP_POST_VARS['trans_email'] ;
		$success = 1 ;
	}
?>
<html>
<head>
<title> Customize Email Transcript Page and Email Message </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	function do_submit()
	{
		if ( ( document.form.trans_message.value == "" ) || ( document.form.trans_email.value == "" ) )
			alert( "All fields MUST be provided." ) ;
		else if ( document.form.trans_email.value.indexOf("%%transcript%%") == -1 )
			alert( "Email body MUST contain the %%transcript%% variable." ) ;
		else
		{
			if ( confirm( "Really update?" ) )
				document.form.submit() ;
		}
	}

	function do_alert()
	{
		if( <? echo $success ?> )
			alert( 'Success!' ) ;
	}
//-->
</script>
<link rel="Stylesheet" href="../css/base.css">
<script language="JavaScript" src="../js/global.js"></script>
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" OnLoad="do_alert()">
<span class="basetxt">
<table cellspacing=0 cellpadding=0 border=0 width="98%">
<tr>
	<td valign="top"><span class="basetxt">
		<?
			if ( file_exists( "../web/$session_setup[login]/$LOGO" ) && $LOGO )
				$logo = "../web/$session_setup[login]/$LOGO" ;
			else if ( file_exists( "../web/$LOGO_ASP" ) && $LOGO_ASP )
				$logo = "../web/$LOGO_ASP" ;
			else
				$logo = "../pics/phplive_logo.gif" ;
		?>
		<img src="<? echo $logo ?>">
		<br>
		<big><b>Customize Email Transcript Page and Email Message</b></big> - <a href="options.php">back to menu</a>
		<br>
		<font color="#FF0000"><? echo $error ?></font>
		<br>
		<img src="../pics/icons/paint.gif" width="32" height="32" border=0 alt="" align="left"> You can customize the web page message of the "Email Transcripts" page and also the email content of the transcript letter.  Place your company profile, promotions or other informative informations inside the outgoing transcript email.
		<p>
	
		<form method="POST" action="email_transcript.php" name="form">
		<input type="hidden" name="action" value="update">
		<table cellspacing=1 cellpadding=2 border=0 width="90%">
		<tr>
			<td valign="top" align="right" nowrap><span class="basetxt">Webpage Text</td>
			<td valign="top" align=><span class="basetxt"><font size=2> <input type="text" name="trans_message" size="<? echo $text_width ?>" maxlength="255" class="input" value="<? echo stripslashes( $session_setup['trans_message'] ) ?>"></td>
		</tr>
		<tr><td colspan=2>&nbsp;</td></tr>
		<tr>
			<td>&nbsp;</td>
			<td><span class="basetxt">below is the transcript email<br>
			<span class="smalltxt">
				<li> <font color="#FF7777">%%username%%</font> - chat name used by visitor (optional)<br>
				<li> <font color="#FF0000">%%transcript%%</font> - the complete chat transcript. (MUST be included.)
			</span></td>
		<tr>
			<td valign="top" align="right" nowrap><span class="basetxt">Email Body</td>
			<td valign="top"><span class="basetxt"><font size=2> <textarea cols="<? echo $textbox_width ?>" name="trans_email" rows="12" wrap="virtual" class="textarea"><? echo stripslashes( $session_setup['trans_email'] ) ?></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="JavaScript:do_submit()"><img src="../pics/buttons/submit.gif" border=0 alt="Submit"></a></td>
		</tr>
		</table>
		</form>
	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>