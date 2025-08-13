<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$sid = $action = $deptid = $order_by = $sort_by = $page = $error = $chat_session = $search_string = "" ;
	$updated = $winapp = 0 ;
	if ( isset( $HTTP_POST_VARS['winapp'] ) ) { $winapp = $HTTP_POST_VARS['winapp'] ; }
	if ( isset( $HTTP_GET_VARS['winapp'] ) ) { $winapp = $HTTP_GET_VARS['winapp'] ; }
	if ( !isset( $HTTP_SESSION_VARS['session_admin'] ) )
	{
		HEADER( "location: ../index.php?winapp=$winapp" ) ;
		exit ;
	}
	$session_admin = $HTTP_SESSION_VARS['session_admin'] ;
	if ( isset( $HTTP_POST_VARS['sid'] ) ) { $sid = $HTTP_POST_VARS['sid'] ; }
	if ( isset( $HTTP_GET_VARS['sid'] ) ) { $sid = $HTTP_GET_VARS['sid'] ; }
	
	if( !$sid )
	{
		HEADER( "location: ../index.php?winapp=$winapp" ) ;
		exit ;
	}
	if ( !isset( $session_admin[$sid]['asp_login'] ) || !file_exists( "../web/".$session_admin[$sid]['asp_login']."/".$session_admin[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: ../index.php?winapp=$winapp" ) ;
		exit ;
	}
	include_once("../web/conf-init.php");
	include_once("../web/".$session_admin[$sid]['asp_login']."/".$session_admin[$sid]['asp_login']."-conf-init.php") ;
	include_once("$DOCUMENT_ROOT/system.php") ;
	include_once("$DOCUMENT_ROOT/lang_packs/$LANG_PACK.php") ;
	include_once("$DOCUMENT_ROOT/API/sql.php" ) ;
	include_once("$DOCUMENT_ROOT/API/Util.php") ;
	include_once("$DOCUMENT_ROOT/API/Util_Page.php") ;
	include_once("$DOCUMENT_ROOT/API/Users/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Users/update.php") ;
	include_once("$DOCUMENT_ROOT/API/Transcripts/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Logs/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Logs/remove.php") ;
?>
<?
	// initialize
	if ( !$session_admin )
	{
		HEADER( "location: ../index.php?winapp=$winapp" ) ;
		exit ;
	}

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
	{
		$text_width = "20" ;
		$text_width_long = "60" ;
		$textbox_width = "80" ;
	}
	else
	{
		$text_width = "10" ;
		$text_width_long = "30" ;
		$textbox_width = "40" ;
	}

	// check to make sure session is set.  if not, user is not authenticated.
	// send them back to login
	if ( !$session_admin[$sid]['admin_id'] )
	{
		HEADER( "location: ../index.php?winapp=$winapp" ) ;
		exit ;
	}
	$now = time() ;

	// update all admins status to not available if they have been idle
	AdminUsers_update_IdleAdminStatus( $dbh, $admin_idle ) ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }
	if ( isset( $HTTP_POST_VARS['order_by'] ) ) { $order_by = $HTTP_POST_VARS['order_by'] ; }
	if ( isset( $HTTP_GET_VARS['sort_by'] ) ) { $sort_by = $HTTP_GET_VARS['sort_by'] ; }
	if ( isset( $HTTP_POST_VARS['page'] ) ) { $page = $HTTP_POST_VARS['page'] ; }
	if ( isset( $HTTP_GET_VARS['page'] ) ) { $page = $HTTP_GET_VARS['page'] ; }
	if ( isset( $HTTP_POST_VARS['chat_session'] ) ) { $chat_session = $HTTP_POST_VARS['chat_session'] ; }
	if ( isset( $HTTP_GET_VARS['chat_session'] ) ) { $chat_session = $HTTP_GET_VARS['chat_session'] ; }
	if ( isset( $HTTP_POST_VARS['search_string'] ) ) { $search_string = $HTTP_POST_VARS['search_string'] ; }
	if ( isset( $HTTP_GET_VARS['search_string'] ) ) { $search_string = $HTTP_GET_VARS['search_string'] ; }
?>
<?
	// functions
?>
<?
	// conditions
	
	if ( $action == "update_greeting" )
	{
		$action = $prev_action ;
		AdminUsers_update_UserValue( $dbh, $session_admin[$sid]['admin_id'], "greeting", $greeting ) ;
		$updated = 1 ;
	}
	else if ( $action == "update_password" )
	{
		$admin = AdminUsers_get_UserInfo( $dbh, $session_admin[$sid]['admin_id'], $session_admin[$sid]['aspID'] ) ;

		if ( md5( $HTTP_POST_VARS['curpassword'] ) == $admin['password'] )
		{
			AdminUsers_update_Password( $dbh, $session_admin[$sid]['admin_id'], $HTTP_POST_VARS['newpassword'] ) ;
			$updated = 1 ;
		}
		else
		{
			$action = $HTTP_POST_VARS['prev_action'] ;
			$error = "Your current password is invalid." ;
		}
	}

	$admin = AdminUsers_get_UserInfo( $dbh, $session_admin[$sid]['admin_id'], $session_admin[$sid]['aspID'] ) ;
	$can_initiate = AdminUsers_get_CanUserInitiate( $dbh, $session_admin[$sid]['admin_id'] ) ;
	$console_window_height = 260 ;
	if ( $can_initiate )
		$console_window_height = 360 ;
?>
<html>
<head>
<title> Support Admin </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="../css/base.css">
<script language="JavaScript" src="../js/global.js"></script>

<script language="JavaScript">
<!--
	// do everything here before it loads
	<?
		if ( $session_admin[$sid]['winapp'] == 1 )
			print "		winapp() ;\n" ;
	?>

	// when viewing transcripts, we need set this or Javascript error
	var loaded = 1 ;

	function open_admin()
	{
		var date = new Date() ;
		var winname = date.getTime() ;
		url = "admin_consol.php?sid=<? echo $sid ?>" ;
		newwin = window.open(url, winname, "scrollbars=yes,menubar=no,resizable=1,location=no,width=500,height=<? echo $console_window_height ?>") ;
		newwin.focus() ;
		//location.href = "index.php?sid=<? echo $sid ?>&deptid=<? echo $deptid ?>" ;
	}

	function do_alert()
	{
		<?
			if ( $updated )
				print "		alert( \"Success!\" ) ;\n" ;
		?>
	}

	function do_search()
	{
		string = replace( document.form.search_string.value, " ", "" ) ;
		if ( string.length < 3 )
			alert( "Search string must be AT LEAST 3 characters." )
		else
			document.form.submit() ;
	}

	function do_submit_pass()
	{
		if ( ( document.form.newpassword.value == "" ) || ( document.form.curpassword.value == "" ) )
			alert( "All fields must be supplied." ) ;
		else if ( document.form.newpassword.value != document.form.vnewpassword.value )
			alert( "Passwords do not match." ) ;
		else
			document.form.submit() ;
	}

	function winapp()
	{
		url = "admin_consol.php?sid=<? echo $sid ?>" ;
		location.href = url ;
	}
//-->
</script>

</head>
<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" OnLoad="do_alert()">
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
<center>
<br>
<?
	if ( file_exists( "../web/".$session_admin[$sid]['asp_login']."/$LOGO" ) && $LOGO )
		$logo = "../web/".$session_admin[$sid]['asp_login']."/$LOGO" ;
	else if ( file_exists( "../web/$LOGO_ASP" ) && $LOGO_ASP )
		$logo = "../web/$LOGO_ASP" ;
	else
		$logo = "../pics/phplive_logo.gif" ;
?>
<img src="<? echo $logo ?>">
<br><span class="basetxt">
<? include_once( "./header.php" ); ?>
<br>

<?
	if ( $action == "edit_greeting" ):
	$greeting = Util_Format_ConvertSpecialChars( $admin['greeting'] ) ;
?>
<form method="POST" action="index.php" name="form">
<input type="hidden" name="action" value="update_greeting">
<input type="hidden" name="prev_action" value="<? echo $action ?>">
<input type="hidden" name="sid" value="<? echo $sid ?>">
<input type="hidden" name="deptid" value="<? echo $deptid ?>">
<big><b>Edit Greeting</b></big><br>
<table cellspacing=1 cellpadding=2 border=0 width="90%">
<tr>
	<td valign="top"><span class="basetxt">
	Current Greeting<br>
	<span class="smalltxt">
	<font color="#660000">%%user%%</font> - visitors's login<br>
	<font color="#660000">%%date%%</font> - today's date
	<span class="smalltxt">
	</td>
	<td valign="top"><span class="basetxt"><textarea cols="<? echo $textbox_width ?>" name="greeting" rows="5" wrap="virtual" class="textarea"><? echo $greeting ?></textarea></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="image" src="../pics/buttons/submit.gif" border=0></td>
</tr>
</table>
</form>











<?
	elseif ( $action == "edit_password" ):
?>
<? if ( md5( "Demo" ) == $admin['password'] ) : ?>
<big><b>Password change has been disabled for Demo operator.</b></big>

<? else: ?>
<form method="POST" action="index.php" name="form">
<input type="hidden" name="action" value="update_password">
<input type="hidden" name="prev_action" value="<? echo $action ?>">
<input type="hidden" name="sid" value="<? echo $sid ?>">
<input type="hidden" name="deptid" value="<? echo $deptid ?>">
<big><b>Edit Password</b></big><br>
<font color="#FF0000"><? echo $error ?></font>
<p>
<table cellspacing=1 cellpadding=1 border=0>
</tr>
	<td align="right"><span class="basetxt">Current Password</td>
	<td><span class="basetxt"><font size=2><input type="password" name="curpassword" size="<? echo $text_width ?>" maxlength="15" class="input"></td>
</tr>
</tr>
	<td align="right"><span class="basetxt">New Password</td>
	<td><span class="basetxt"><font size=2><input type="password" name="newpassword" size="<? echo $text_width ?>" maxlength="15" class="input"></td>
</tr>
</tr>
	<td align="right"><span class="basetxt">Verify New Password</td>
	<td><span class="basetxt"><font size=2><input type="password" name="vnewpassword" size="<? echo $text_width ?>" maxlength="15" class="input"></td>
</tr>
<tr>
	<td><span class="basetxt">&nbsp;</td>
	<td><span class="basetxt"><a href="JavaScript:do_submit_pass()"><img src="../pics/buttons/submit.gif" border=0></a></td>
</tr>
</table>
</form>
<? endif ; ?>














<?
	elseif ( $action == "view_transcript" ):
	$transcriptinfo = ServiceTranscripts_get_TranscriptInfo(  $dbh, $chat_session ) ;
	$userinfo = AdminUsers_get_UserInfo( $dbh, $transcriptinfo['userID'], $session_admin[$sid]['aspID'] ) ;
	$date = date( "D m/d/y h:i a", $transcriptinfo['created'] ) ;
	$requestlog = ServiceLogs_get_SessionRequestLog( $dbh, $chat_session ) ;
	$chat_session = preg_replace( "/\.txt/", "", $chat_session ) ;
?>
Transcript: <b><? echo $chat_session  ?></b> on <b><? echo $date ?></b><br>
<table cellspacing=1 cellpadding=2 border=0 width="90%">
<tr>
	<td bgColor="#8080C0"><span class="smalltxt"><font color="#FFFFFF">
	<b>Operator</b>: <? echo $userinfo['name'] ?><br>
	<b>Visitor</b>: <? echo $transcriptinfo['from_screen_name'] ?><br>
	<b>IP</b>: <? echo $requestlog['ip'] ?><br>
	<b>Browser</b>: <? echo $requestlog['browser_os'] ?><br>
	<b>Screen Resolution</b>: <? echo $requestlog['display_resolution'] ?>
	</td>
</tr>
<tr>
	<td valign="top" bgColor="<? echo $CHAT_BOX_BACKGROUND ?>"><span class="basetxt">
	<? echo $transcriptinfo['formatted'] ?>
	</td>
</tr>
</table>











<?
	else:
?>
<table cellspacing=1 cellpadding=2 border=0 width="90%">
<tr>
	<td valign="top"><span class="basetxt">
		<br>

		Go ONLINE -> <a href="JavaScript:open_admin()"><img src="../pics/buttons/launch_consol.gif" border=0></a>
		<hr>
		<br>
		<b>Saved Support Transcripts</b><br>

		<?
			$select_dept = $searched_string = $page_string = "" ;
			$transcripts = ARRAY() ;
			if ( $deptid )
			{
				ServiceLogs_remove_DeptExpireTranscripts( $dbh, $deptid, $session_admin[$sid]['aspID'] ) ;
				$department = AdminUsers_get_DeptInfo( $dbh, $deptid, $session_admin[$sid]['aspID'] ) ;
				if ( AdminUsers_get_IsUserInDept( $dbh, $session_admin[$sid]['admin_id'], $department['deptID'] ) )
				{
					if ( $department['transcript_share'] )
					{
						$transcripts = ServiceTranscripts_get_DeptTranscripts( $dbh, $department['deptID'], $order_by, $sort_by, $page, 20, $search_string ) ;
						$total_transcripts = ServiceTranscripts_get_TotalDeptTranscripts( $dbh, $department['deptID'], $search_string ) ;
					}
					else
					{
						$transcripts = ServiceTranscripts_get_UserDeptTranscripts( $dbh, $admin['userID'], $department['deptID'], $order_by, $sort_by, $page, 20, $search_string ) ;
						$total_transcripts = ServiceTranscripts_get_TotalUserDeptTranscripts( $dbh, $admin['userID'], $department['deptID'], $search_string ) ;
					}
					$page_string = Page_util_CreatePageString( $dbh, $page, "index.php?sid=$sid&deptid=$deptid", 20, $total_transcripts ) ;
				}
			}
			
			$admin_departments = AdminUsers_get_UserDepartments( $dbh, $session_admin[$sid]['admin_id'] ) ;
			for ( $c = 0; $c < count( $admin_departments ); ++$c )
			{
				$department = $admin_departments[$c] ;
				$selected = "" ;
				if ( $department['deptID'] == $deptid )
					$selected = "selected" ;

				$select_dept .= "<option value=\"$department[deptID]\" $selected>$department[name]</option>" ;
			}
			print "<form name=dept_select>To view saved transcripts, please <font color=\"#0000FF\">select your department</font>: <select name=\"department\" class=\"select\" OnChange=\"index=this.selectedIndex;if(index>0){location.href='index.php?sid=$sid&deptid='+document.dept_select.department[index].value}\"><option value=\"-- select department --\">&nbsp;</option>$select_dept</select></form><br>" ;

			if ( $search_string )
				$searched_string = "Searched: \"$search_string\" &nbsp;|&nbsp; Transcripts Found: $total_transcripts &nbsp;|&nbsp; [ <a href=\"index.php?sid=$sid&deptid=$deptid\">reset</a> ]<br>" ;
		?>

		<? echo $searched_string ?>
		<span class="smalltxt">Page: <? echo $page_string ?></span><br>
		<table cellspacing=1 cellpadding=1 border=0 width="100%">
		<tr bgColor="#8080C0">
			<td><span class="basetxt">&nbsp;</td>
			<td><span class="basetxt"><font color="#FFFFFF">Operator</td>
			<td><span class="basetxt"><font color="#FFFFFF">Visitor</td>
			<td><span class="basetxt"><font color="#FFFFFF">Created</td>
			<td><span class="basetxt"><font color="#FFFFFF">Size</td>
		</tr>
		<?
			for ( $c = 0; $c < count( $transcripts ); ++$c )
			{
				$transcript = $transcripts[$c] ;
				$userinfo = AdminUsers_get_UserInfo( $dbh, $transcript['userID'], $session_admin[$sid]['aspID'] ) ;
				$date = date( "D m/d/y h:i a", $transcript['created'] ) ;

				// take out the tags to make it more accurate size. (gets rid of all
				// the javascript tags and all that
				$size = Util_Format_Bytes( strlen( strip_tags( $transcript['plain'] ) ) ) ;
	
				$bgcolor = "#EEEEF7" ;
				if ( $c % 2 )
					$bgcolor = "#E6E6F2" ;

				print "
				<tr bgcolor=\"$bgcolor\">
					<td><span class=\"basetxt\"><a href=\"index.php?action=view_transcript&chat_session=$transcript[chat_session]&sid=$sid&deptid=$deptid\"><img src=\"../pics/view.gif\" border=0></a></td>
					<td><span class=\"basetxt\">$userinfo[name]</td>
					<td><span class=\"basetxt\">$transcript[from_screen_name]</td>
					<td><span class=\"basetxt\">$date</td>
					<td><span class=\"basetxt\">$size</td>
				</tr>
				" ;
			}
		?>
		</table>
		<span class="smalltxt">Page: <? echo $page_string ?></span>
		<p>

		<!-- begin search area -->
		<form method="GET" action="index.php" name="form">
		<input type="hidden" name="sid" value="<? echo $sid ?>">
		<input type="hidden" name="deptid" value="<? echo $deptid ?>">
		<table cellspacing=1 cellpadding=1 border=0>
		<tr>
			<td colspan=3><span class="smalltxt">System generated messages, such as party left and greeting messages, are ignored during search.</span></td>
		</tr>
		<tr>
			<td><span class="basetxt">Search string: </td>
			<td><span class="basetxt"><input type="text" name="search_string" value="<? echo $search_string ?>" size="25" maxlength="50" class="input"></td>
			<td><a href="JavaScript:do_search()"><img src="../pics/buttons/search.gif" border=0></a></td>
		</tr>
		</table>
		</form>
		<!-- end search area -->
		<br>
	</td>
</tr>
</table>

<? endif ; ?>

<p>
<? include_once( "./footer.php" ); ?>
</center>

<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>