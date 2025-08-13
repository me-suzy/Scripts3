<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$session_chat = $HTTP_SESSION_VARS['session_chat'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : $HTTP_POST_VARS['sid'] ;

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
	include_once("./API/Util.php") ;
	include_once("./API/Users/get.php") ;
	include_once("./API/Canned/get.php") ;
?>
<?
	// initialize

	// we use $rand to prevent loading from cached pages
	mt_srand ((double) microtime() * 1000000);
	$rand = mt_rand() ;
?>
<?
	// functions
?>
<?
	// conditions
?>
<html>
<head>
<title> Chat [admin canned] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="./css/base.css">

<script language="JavaScript">
<!--
	function do_select( the_form )
	{
		index = the_form.selections.selectedIndex ;
		orig_message = parent.window.writer.document.form.message.value ;
		new_message = the_form.selections[index].value ;
		parent.window.writer.document.form.message.value = new_message ;
	}
//-->
</script>

</head>
<body bgColor="<? echo $CHAT_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
<center>
<span class="basetxt">
<span class="smalltxt">Go to your admin area to add more canned messages - <a href="chat_admin_canned.php?sid=<? echo $sid ?>&requestid=<? echo $requestid ?>&rand=<? echo $rand ?>">Refresh</a></span>
<table cellspacing=1 cellpadding=2 border=0>
<tr>
	<form name="canned_responses">
	<td><span class="smalltxt">
		Canned Responses<br>
		<select name="selections" class="select">
		<option value="">&nbsp;
		<?
			$canneds = ServiceCanned_get_UserCannedByType( $dbh, $session_chat[$sid]['admin_id'], $session_chat[$sid]['deptid'], 'r' ) ;
			for ( $c = 0; $c < count( $canneds ); ++$c )
			{
				$canned = $canneds[$c] ;
				$canned_name = Util_Format_ConvertSpecialChars( $canned['name'] ) ;
				$canned_message = Util_Format_ConvertSpecialChars( $canned['message'] ) ;

				print "		<option value=\"$canned_message\">$canned_name</option>\n" ;
			}
		?>
		</select>
		<a href="JavaScript:do_select( document.canned_responses )"><img src="pics/buttons/select.gif" border=0 alt="Select"></a>
		<span>
	</td>
	</form>
	<td><font size=1>&nbsp;</font></td>
	<form name="canned_commands">
	<td><span class="smalltxt">
		Canned Commands<br>
		<select name="selections" class="select">
		<option value="">&nbsp;
		<?
			$canneds = ServiceCanned_get_UserCannedByType( $dbh, $session_chat[$sid]['admin_id'], $session_chat[$sid]['deptid'], 'c' ) ;
			for ( $c = 0; $c < count( $canneds ); ++$c )
			{
				$canned = $canneds[$c] ;
				$canned_name = Util_Format_ConvertSpecialChars( $canned['name'] ) ;
				$canned_message = Util_Format_ConvertSpecialChars( $canned['message'] ) ;

				print "		<option value=\"$canned_message\">$canned_name</option>\n" ;
			}
		?>
		</select>
		<a href="JavaScript:do_select( document.canned_commands )"><img src="pics/buttons/select.gif"  border=0 alt="Select"></a>
		</span>
	</td>
	</form>
</tr>
</table>
</center>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>