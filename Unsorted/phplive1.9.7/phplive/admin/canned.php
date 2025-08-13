<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$sid = $action = $deptid = $cannedid = "" ;
	$updated = 0 ;
	if ( !isset( $HTTP_SESSION_VARS['session_admin'] ) )
	{
		HEADER( "location: ../index.php" ) ;
		exit ;
	}
	$session_admin = $HTTP_SESSION_VARS['session_admin'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : $HTTP_POST_VARS['sid'] ;

	if ( !isset( $session_admin[$sid]['asp_login'] ) || !file_exists( "../web/".$session_admin[$sid]['asp_login']."/".$session_admin[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: ../index.php" ) ;
		exit ;
	}
	include_once("../web/conf-init.php");
	include_once("../web/".$session_admin[$sid]['asp_login']."/".$session_admin[$sid]['asp_login']."-conf-init.php") ;
	include_once("../system.php") ;
	include_once("../lang_packs/$LANG_PACK.php") ;
	include_once("../API/sql.php" ) ;
	include_once("../API/Util.php") ;
	include_once("../API/Users/get.php") ;
	include_once("../API/Users/update.php") ;
	include_once("../API/Canned/put.php") ;
	include_once("../API/Canned/get.php") ;
	include_once("../API/Canned/remove.php") ;
	include_once("../API/Canned/update.php") ;
?>
<?
	// initialize
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
		HEADER( "location: ../" ) ;
		exit ;
	}

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }
	if ( isset( $HTTP_POST_VARS['cannedid'] ) ) { $cannedid = $HTTP_POST_VARS['cannedid'] ; }
	if ( isset( $HTTP_GET_VARS['cannedid'] ) ) { $cannedid = $HTTP_GET_VARS['cannedid'] ; }
?>
<?
	// functions
?>
<?
	// conditions
	
	if ( $action == "add_canned" )
	{
		$action = $HTTP_POST_VARS['prev_action'] ;
		if ( $cannedid )
			ServiceCanned_update_Canned( $dbh, $session_admin[$sid]['admin_id'], $cannedid, $HTTP_POST_VARS['name'], $HTTP_POST_VARS['message'] ) ;
		else
			ServiceCanned_put_UserCanned( $dbh, $session_admin[$sid]['admin_id'], $deptid, $HTTP_POST_VARS['type'], $HTTP_POST_VARS['name'], $HTTP_POST_VARS['message'] ) ;
		$cannedid = 0 ;
		$updated = 1 ;
	}
	else if ( $action == "delete_canned" )
	{
		$action = $HTTP_GET_VARS['prev_action'] ;
		ServiceCanned_remove_UserCanned( $dbh, $session_admin[$sid]['admin_id'], $cannedid ) ;
		$cannedid = 0 ;
		$updated = 1 ;
	}

	$admin = AdminUsers_get_UserInfo( $dbh, $session_admin[$sid]['admin_id'], $session_admin[$sid]['aspID'] ) ;
	$deptinfo = AdminUsers_get_DeptInfo( $dbh, $deptid, $session_admin[$sid]['aspID'] ) ;
?>
<html>
<head>
<title> Support Admin </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="../css/base.css">
<script language="JavaScript" src="../js/global.js"></script>

<script language="JavaScript">
<!--
	function do_submit()
	{
		if ( ( document.form.name.value == "" ) || ( document.form.message.value == "" ) )
			alert( "All fields must be supplied." ) ;
		else
			document.form.submit() ;
	}

	function do_delete( cannedid )
	{
		if ( confirm( "Are you sure you want to delete?" ) )
			location.href = "canned.php?action=delete_canned&prev_action=<? echo $action ?>&deptid=<? echo $deptid ?>&sid=<? echo $sid ?>&cannedid="+cannedid ;
	}

	function put_command(selected_index)
	{
		if ( selected_index > 0 )
			document.form.message.value = document.form.command[selected_index].value ;
	}

	function check_command()
	{
		if ( document.form.command.selectedIndex == 0 )
		{
			alert( "Please choose a command first." ) ;
			document.form.command.focus() ;
		}
	}

	function do_alert()
	{
		<?
			if ( $updated )
				print "		alert( \"Success!\" ) ;\n" ;
		?>
	}
//-->
</script>

</head>
<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" OnLoad="do_alert()">
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
	if ( $action == "canned_responses" ):
	$canneds = ServiceCanned_get_UserCannedByType( $dbh, $session_admin[$sid]['admin_id'], $deptid, 'r' ) ;
	$cannedinfo = ServiceCanned_get_CannedInfo( $dbh, $cannedid, $session_admin[$sid]['admin_id'] ) ;
?>
<big><b>Canned Responses</b></big><br>
Canned responses are quick way to write messages that you would need to type frequently.<br>
<table cellspacing=1 cellpadding=1 border=0 width="90%">
<tr bgColor="#8080C0">
	<td nowrap><span class="basetxt"><font color="#FFFFFF">Reference</td>
	<td><span class="basetxt"><font color="#FFFFFF">Message</span></td>
	<td width="20"><span class="basetxt">&nbsp;</td>
	<td width="20"><span class="basetxt">&nbsp;</td>
</tr>
<?
	for ( $c = 0; $c < count( $canneds ); ++$c )
	{
		$canned = $canneds[$c] ;

		$canned_name = Util_Format_ConvertSpecialChars( $canned['name'] ) ;
		$canned_message = Util_Format_ConvertSpecialChars( $canned['message'] ) ;

		$bgcolor = "#EEEEF7" ;
		if ( $c % 2 )
			$bgcolor = "#E6E6F2" ;

		$edit_string = "&nbsp;" ;
		$delete_string = "&nbsp;" ;
		if ( $canned['userID'] == $admin['userID'] )
		{
			$edit_string = "<a href=\"canned.php?sid=$sid&deptid=$deptid&action=canned_responses&cannedid=$canned[cannedID]\">Edit</a>" ;
			$delete_string = "<a href=\"JavaScript:do_delete( $canned[cannedID] )\">Delete</a>" ;
		}

		if ( ( $canned['userID'] == $admin['userID'] ) || ( $canned['userID'] > 10000000 ) )
		{
			print "
				<tr bgcolor=\"$bgcolor\">
					<td><span class=\"basetxt\">$canned_name</td>
					<td><span class=\"basetxt\">$canned_message</td>
					<td><span class=\"smalltxt\">$edit_string</td>
					<td><span class=\"smalltxt\">$delete_string</td>
				</tr>
			" ;
		}
	}
?>
<tr>
	<td colspan=3>&nbsp;</td>
</tr>

<? if ( !isset( $deptinfo['deptID'] ) ): ?>
<tr>
	<td colspan=4><span class="basetxt">
	<font color="#FF0000"><big><b>!</b></big></font> Before you can input Canned Commands, you must <big><b><a href="index.php?sid=<? echo $sid ?>">select a department at the main page</a></b></big>.
	</td>
</tr>

<? else: ?>
<tr>
	<td><span class="smalltxt">&nbsp;</td>
	<td colspan=2><span class="smalltxt"><font color="#660000">%%user%%</font> - visitors's login</td>
</tr>
<tr>
	<form method="POST" action="canned.php" name="form">
	<input type="hidden" name="action" value="add_canned">
	<input type="hidden" name="prev_action" value="<? echo $action ?>">
	<input type="hidden" name="type" value="r">
	<input type="hidden" name="sid" value="<? echo $sid ?>">
	<input type="hidden" name="deptid" value="<? echo $deptid ?>">
	<input type="hidden" name="cannedid" value="<? echo $cannedid ?>">
	<td><span class="basetxt"><input type="text" name="name" size="<? echo $text_width ?>" maxlength="20" class="input" value="<? echo $cannedinfo['name'] ?>"></td>
	<td><span class="basetxt"><input type="text" name="message" size="<? echo $text_width_long ?>" class="input" value="<? echo $cannedinfo['message'] ?>"></td>
	<td colspan=2><a href="JavaScript:do_submit()"><img src="../pics/buttons/submit.gif" border=0></a></td>
	</form>
</tr>
<? endif ; ?>

</table>








<?
	elseif ( $action == "canned_commands" ):
	$selected_push = $selected_email = $selected_url = $selected_image = "" ;
	$canneds = ServiceCanned_get_UserCannedByType( $dbh, $session_admin[$sid]['admin_id'], $deptid, 'c' ) ;
	$cannedinfo = ServiceCanned_get_CannedInfo( $dbh, $cannedid, $session_admin[$sid]['admin_id'] ) ;
	if ( preg_match( "/^push:/", $cannedinfo['message'] ) )
		$selected_push = "selected" ;
	else if ( preg_match( "/^email:/", $cannedinfo['message'] ) )
		$selected_email = "selected" ;
	else if ( preg_match( "/^url:/", $cannedinfo['message'] ) )
		$selected_url = "selected" ;
	else if ( preg_match( "/^image:/", $cannedinfo['message'] ) )
		$selected_image = "selected" ;
?>
<big><b>Canned Commands</b></big><br>
Canned responses are quick way to write messages that you would need to type frequently.<br>
<table cellspacing=1 cellpadding=1 border=0 width="90%">
<tr bgColor="#8080C0">
	<td><span class="basetxt"><font color="#FFFFFF">Reference</td>
	<td><span class="basetxt"><font color="#FFFFFF">Message</span></td>
	<td width="20"><span class="basetxt">&nbsp;</td>
	<td width="20"><span class="basetxt">&nbsp;</td>
</tr>
<?
	for ( $c = 0; $c < count( $canneds ); ++$c )
	{
		$canned = $canneds[$c] ;

		$canned_name = Util_Format_ConvertSpecialChars( $canned['name'] ) ;
		$canned_message = Util_Format_ConvertSpecialChars( $canned['message'] ) ;

		$bgcolor = "#EEEEF7" ;
		if ( $c % 2 )
			$bgcolor = "#E6E6F2" ;

		$edit_string = "&nbsp;" ;
		$delete_string = "&nbsp;" ;
		if ( $canned['userID'] == $admin['userID'] )
		{
			$edit_string = "<a href=\"canned.php?sid=$sid&deptid=$deptid&action=canned_commands&cannedid=$canned[cannedID]\">Edit</a>" ;
			$delete_string = "<a href=\"JavaScript:do_delete( $canned[cannedID] )\">Delete</a>" ;
		}

		if ( ( $canned['userID'] == $admin['userID'] ) || ( $canned['userID'] > 10000000 ) )
		{
			print "
				<tr bgcolor=\"$bgcolor\">
					<td><span class=\"basetxt\">$canned_name</td>
					<td><span class=\"basetxt\">$canned_message</td>
					<td><span class=\"smalltxt\">$edit_string</td>
					<td><span class=\"smalltxt\">$delete_string</td>
				</tr>
			" ;
		}
	}
?>
<tr>
	<td colspan=3>&nbsp;</td>
</tr>
<? if ( !isset( $deptinfo['deptID'] ) ): ?>
<tr>
	<td colspan=4><span class="basetxt">
	<font color="#FF0000"><big><b>!</b></big></font> Before you can input Canned Commands, you must <big><b><a href="index.php?sid=<? echo $sid ?>">select a department at the main page</a></b></big>.
	</td>
</tr>

<? else: ?>
<tr>
	<form method="POST" action="canned.php" name="form">
	<input type="hidden" name="action" value="add_canned">
	<input type="hidden" name="prev_action" value="<? echo $action ?>">
	<input type="hidden" name="type" value="c">
	<input type="hidden" name="sid" value="<? echo $sid ?>">
	<input type="hidden" name="deptid" value="<? echo $deptid ?>">
	<input type="hidden" name="cannedid" value="<? echo $cannedid ?>">
	<td><span class="basetxt"><input type="text" name="name" size="<? echo $text_width ?>" maxlength="20" class="input" value="<? echo $cannedinfo['name'] ?>"></td>
	<td><span class="basetxt">
		<select name="command" class="select" OnChange="put_command( this.selectedIndex )"><option value=""></option><option value="push:" <? echo $selected_push ?>>push:</option>
		<option value="email:" <? echo $selected_email ?>>email:</option>
		<option value="url:" <? echo $selected_url ?>>url:</option>
		<option value="image:" <? echo $selected_image ?>>image:</option>
		</select><input type="text" name="message" size="<? echo $text_width_long - 10 ?>" class="input" OnFocus="check_command()" OnBlur="new_string=replace(this.value, ' ', '');this.value=new_string;return true;" value="<? echo $cannedinfo['message'] ?>">
	</td>
	<td colspan=2><a href="JavaScript:do_submit()"><img src="../pics/buttons/submit.gif" border=0></a></td>
	</form>
</tr>
<tr>
	<td colspan=4>
</tr>
<tr>
	<td><span class="basetxt">&nbsp;</td>
	<td colspan=4><span class="basetxt">
		<big><b>Admin Commands: </b></big><br>
		<font color="#494F72"><b>url:</b></font><i>URL</i> (hyperlink a URL)<br>
		<font color="#494F72"><b>email:</b></font><i>example@osicodes.com</i> (hyperlink an Email)<br>
		<font color="#494F72"><b>image:</b></font><i>URL/sample.gif</i> (embed an image)<br>
		<font color="#494F72"><b>push:</b></font><i>URL</i> (opens new browser containing URL of webpage, word docs, etc.)<br>
	</td>
</tr>
<? endif ; ?>
</table>













<? else: ?>
<!-- future release may use this space -->



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
