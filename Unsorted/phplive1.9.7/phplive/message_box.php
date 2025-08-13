<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$action = $deptid = $l = $x = "" ;
	if ( isset( $HTTP_POST_VARS['l'] ) ) { $l = $HTTP_POST_VARS['l'] ; }
	if ( isset( $HTTP_GET_VARS['l'] ) ) { $l = $HTTP_GET_VARS['l'] ; }
	if ( isset( $HTTP_POST_VARS['x'] ) ) { $x = $HTTP_POST_VARS['x'] ; }
	if ( isset( $HTTP_GET_VARS['x'] ) ) { $x = $HTTP_GET_VARS['x'] ; }
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }

	if ( !file_exists( "web/$l/$l-conf-init.php" ) || !file_exists( "web/conf-init.php" ) )
	{
		print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting...</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;
	include_once("./web/$l/$l-conf-init.php") ;
	include_once("./system.php") ;
	include_once("./lang_packs/$LANG_PACK.php") ;
	include_once("./API/sql.php" ) ;
	include_once("./API/Users/get.php") ;
?>
<?
	// initialize
	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
	{
		$text_width = "35" ;
		$textbox_width = "40" ;
	}
	else
	{
		$text_width = "15" ;
		$textbox_width = "30" ;
	}

	$deptinfo = AdminUsers_get_DeptInfo( $dbh, $deptid, $x ) ;
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "submit" )
	{
		if ( $deptinfo['email'] )
		{
			$subject = "PHP Live! Message Box: $HTTP_POST_VARS[subject]" ;
			mail( $deptinfo['email'], $HTTP_POST_VARS['subject'], $HTTP_POST_VARS['message'], "From: $HTTP_POST_VARS[name] <$HTTP_POST_VARS[email]>") ;
		}
	}
?>
<html>
<head>
<title> <? echo $LANG['TITLE_LEAVEMESSAGE'] ?> </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="css/base.css">
<script language="JavaScript" src="js/newwin.js"></script>

<script language="JavaScript">
<!--
	function do_submit()
	{
		if ( ( document.form.name.value == "" ) || ( document.form.email.value == "" )
			|| ( document.form.subject.value == "" ) || ( document.form.message.value == "" ) )
			alert( "<? echo $LANG['MESSAGE_BOX_JS_A_ALLFIELDSSUP'] ?>" ) ;
		else if ( document.form.email.value.indexOf("@") == -1 )
			alert( "<? echo $LANG['MESSAGE_BOX_JS_A_INVALIDEMAIL'] ?>" ) ;
		else
			document.form.submit() ;
	}
//-->
</script>

</head>
<body bgColor="<? echo $CHAT_REQUEST_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
<center>

<?
	if ( file_exists( "web/$l/$LOGO" ) && $LOGO )
		$logo = "web/$l/$LOGO" ;
	else if ( file_exists( "web/$LOGO_ASP" ) && $LOGO_ASP )
		$logo = "web/$LOGO_ASP" ;
	else
		$logo = "pics/phplive_logo.gif" ;
?>
<img src="<? echo $logo ?>">
<br>
<span class="basetxt">

<? if ( $action == "submit" ): ?>
<? echo $LANG['MESSAGE_BOX_SENT'] ?> <b><? echo $deptinfo['name'] ?></b>.
<p>
<a href="JavaScript:parent.window.close()"><img src="pics/buttons/close_window.gif" border=0 alt="<? echo $LANG['WORD_CLOSE'] ?>"></a>
<p>





<? else: ?>
<? echo $LANG['MESSAGE_BOX_MESSAGE'] ?>
<br>
<?= ( $deptinfo['message'] ) ? stripslashes( $deptinfo['message'] ) : "" ?>
<p>
<form method="POST" action="message_box.php" name="form">
<input type="hidden" name="action" value="submit">
<input type="hidden" name="deptid" value="<? echo $deptid ?>">
<input type="hidden" name="x" value="<? echo $x ?>">
<input type="hidden" name="l" value="<? echo $l ?>">
<? include("$DOCUMENT_ROOT/API/Users/cp.php") ; ?>
<table cellspacing=0 cellpadding=1 border=0>
<tr>
	<td><span class="basetxt"><font color="#FF0000">*</font> <? echo $LANG['WORD_NAME'] ?></td>
	<td><font size=2> <input type="text" name="name" size="<? echo $text_width ?>" maxlength="255" class="input"></td>
</tr>
<tr>
	<td><span class="basetxt"><font color="#FF0000">*</font> <? echo $LANG['WORD_EMAIL'] ?></td>
	<td><font size=2> <input type="text" name="email" size="<? echo $text_width ?>" maxlength="255" class="input"></td>
</tr>
<tr>
	<td><span class="basetxt"><font color="#FF0000">*</font> <? echo $LANG['WORD_SUBJECT'] ?></td>
	<td><font size=2> <input type="text" name="subject" size="<? echo $text_width ?>" maxlength="255" class="input"></td>
</tr>
<tr>
	<td valign="top"><span class="basetxt"><font color="#FF0000">*</font> <? echo $LANG['WORD_MESSAGE'] ?></td>
	<td valign="top"><font size=2> <textarea cols="<? echo $textbox_width ?>" rows="5" name="message" class="textarea"></textarea><br>
	<a href="JavaScript:do_submit()"><img src="pics/buttons/submit.gif" border=0 alt="<? echo $LANG['WORD_SEND'] ?>"></a>
	</td>
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
