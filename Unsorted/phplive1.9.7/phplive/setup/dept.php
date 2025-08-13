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
	include_once("../lang_packs/$LANG_PACK.php") ;
	include_once("../system.php") ;
	include_once("../API/Util.php") ;
	include_once("../API/sql.php") ;
	include_once("../API/Users/get.php") ;
	include_once("../API/Users/update.php") ;
	include_once("../API/Canned/put.php") ;
	include_once("../API/Canned/get.php") ;
	include_once("../API/Canned/remove.php") ;
	include_once("../API/Canned/update.php") ;
?>
<?

	// initialize
	$deptid = "" ;
	$updated = $cannedid = 0 ;

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

	// set adminid to big number since the setup user does not have admin id (operator id).
	// the big number will make sure it does not conflict with future operator ids.
	$adminid = 10000000 + $session_setup['aspID'] ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }
	if ( isset( $HTTP_GET_VARS['cannedid'] ) ) { $cannedid = $HTTP_GET_VARS['cannedid'] ; }
	if ( isset( $HTTP_POST_VARS['cannedid'] ) ) { $cannedid = $HTTP_POST_VARS['cannedid'] ; }

	if ( !$deptid )
	{
		HEADER( "location: adddept.php" ) ;
		exit ;
	}
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
			ServiceCanned_update_Canned( $dbh, $adminid, $cannedid, $HTTP_POST_VARS['name'], $HTTP_POST_VARS['message'] ) ;
		else
			ServiceCanned_put_UserCanned( $dbh, $adminid, $deptid, $HTTP_POST_VARS['type'], $HTTP_POST_VARS['name'], $HTTP_POST_VARS['message'] ) ;
		$cannedid = 0 ;
		$updated = 1 ;
	}
	else if ( $action == "delete_canned" )
	{
		$action = $HTTP_GET_VARS['prev_action'] ;
		ServiceCanned_remove_UserCanned( $dbh, $adminid, $cannedid ) ;
		$cannedid = 0 ;
		$updated = 1 ;
	}
	else if ( $action == "add_hours" )
	{
		$action = $HTTP_POST_VARS['prev_action'] ;
		AdminUsers_update_DeptValue( $dbh, $session_setup['aspID'], $deptid, "message", $HTTP_POST_VARS['message'] ) ;
		$updated = 1 ;
	}
	else if ( $action == "update_greeting" )
	{
		$action = $HTTP_POST_VARS['prev_action'] ;
		AdminUsers_update_DeptValue( $dbh, $session_setup['aspID'], $deptid, "greeting", $HTTP_POST_VARS['greeting'] ) ;
		$updated = 1 ;
	}
	$deptinfo = AdminUsers_get_DeptInfo( $dbh, $deptid, $session_setup['aspID'] ) ;
?>
<html>
<head>
<title> Department Preferences </title>
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
			location.href = "dept.php?action=delete_canned&prev_action=<? echo $action ?>&deptid=<? echo $deptid ?>&cannedid="+cannedid ;
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
			alert( "Please chose a command first." ) ;
			document.form.command.focus() ;
		}
	}

	function view_screen()
	{
		if ( confirm( "If you've made changes, please submit before viewing." ) )
		{
			var request_url = "../request.php?l=<? echo $session_setup['login'] ?>&x=<? echo $session_setup['aspID'] ?>&deptid=<? echo $deptid ?>&page=message" ;
			newwin = window.open( request_url, "demo", 'scrollbars=no,menubar=no,resizable=0,location=no,screenX=50,screenY=100,width=450,height=350' ) ;
			newwin.focus() ;
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
		<b><big>Department Preferences</big></b> - <a href="adddept.php">manage departments</a> - <a href="options.php">back to main</a>
		<br>
		<br>

		<?
			if ( $action == "canned_responses" ):
			$canneds = ServiceCanned_get_UserCannedByType( $dbh, $adminid, $deptid, 'r' ) ;
			$cannedinfo = ServiceCanned_get_CannedInfo( $dbh, $cannedid, $adminid ) ;
		?>
		<img src="../pics/icons/manager.gif" width="32" height="32" border=0 alt="" align="left"> You can set department global canned responses here.  The below canned responses will be displayed on all the department operators' canned drop down menus.  Department operators will not have access to add/edit/delete these.  You may only add/edit/delete the global canned responses here in the setup area.
		<p>
		<big><b><?= ( isset( $deptinfo['name'] ) ) ? $deptinfo['name'] : "" ?> Department Canned Responses</b></big><br>
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

				print "
					<tr bgcolor=\"$bgcolor\">
						<td><span class=\"basetxt\">$canned_name</td>
						<td><span class=\"basetxt\">$canned_message</td>
						<td><span class=\"smalltxt\"><a href=\"dept.php?deptid=$deptid&action=canned_responses&cannedid=$canned[cannedID]\">Edit</a></td>
						<td><span class=\"smalltxt\"><a href=\"JavaScript:do_delete( $canned[cannedID] )\">Delete</a></td>
					</tr>
				" ;
			}
		?>
		<tr>
			<td colspan=3>&nbsp;</td>
		</tr>
		<tr>
			<td><span class="smalltxt">&nbsp;</td><td colspan=2><span class="smalltxt"><font color="#660000">%%user%%</font> - visitors's login</td>
		</tr>
		<tr>
			<form method="POST" action="dept.php" name="form">
			<input type="hidden" name="action" value="add_canned">
			<input type="hidden" name="prev_action" value="<? echo $action ?>">
			<input type="hidden" name="type" value="r">
			<input type="hidden" name="deptid" value="<? echo $deptid ?>">
			<input type="hidden" name="cannedid" value="<? echo $cannedid ?>">
			<td><span class="basetxt"><input type="text" name="name" size="<? echo $text_width ?>" maxlength="20" class="input" value="<?= ( isset( $cannedinfo['name'] ) ) ? $cannedinfo['name'] : "" ?>"></td>
			<td><span class="basetxt"><input type="text" name="message" size="<? echo $text_width_long ?>" class="input" value="<?= ( isset( $cannedinfo['message'] ) ) ? $cannedinfo['message'] : "" ?>"></td>
			<td colspan=2><a href="JavaScript:do_submit()"><img src="../pics/buttons/submit.gif" border=0></a></td>
			</form>
		</tr>
		</table>








		<?
			elseif ( $action == "canned_commands" ):
			$canneds = ServiceCanned_get_UserCannedByType( $dbh, $adminid, $deptid, 'c' ) ;
			$cannedinfo = ServiceCanned_get_CannedInfo( $dbh, $cannedid, $adminid ) ;
			$selected_push = $selected_email = $selected_image = $selected_url = "" ;
			if ( preg_match( "/^push:/", $cannedinfo['message'] ) )
				$selected_push = "selected" ;
			else if ( preg_match( "/^email:/", $cannedinfo['message'] ) )
				$selected_email = "selected" ;
			else if ( preg_match( "/^url:/", $cannedinfo['message'] ) )
				$selected_url = "selected" ;
			else if ( preg_match( "/^image:/", $cannedinfo['message'] ) )
				$selected_image = "selected" ;
		?>
		<img src="../pics/icons/manager.gif" width="32" height="32" border=0 alt="" align="left"> You can set department global canned commands here.  The below canned commands will be displayed on all the department operators' canned drop down menus.  Department operators will not have access to add/edit/delete these.  You may only add/edit/delete the global canned commands here in the setup area.
		<p>
		<big><b><?= ( isset( $deptinfo['name'] ) ) ? $deptinfo['name'] : "" ?> Department Canned Commands</b></big><br>
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

				print "
					<tr bgcolor=\"$bgcolor\">
						<td><span class=\"basetxt\">$canned_name</td>
						<td><span class=\"basetxt\">$canned_message</td>
						<td><span class=\"smalltxt\"><a href=\"dept.php?deptid=$deptid&action=canned_commands&cannedid=$canned[cannedID]\">Edit</a></td>
						<td><span class=\"smalltxt\"><a href=\"JavaScript:do_delete( $canned[cannedID] )\">Delete</a></td>
					</tr>
				" ;
			}
		?>
		<tr>
			<td colspan=3>&nbsp;</td>
		</tr>
		<tr>
			<form method="POST" action="dept.php" name="form">
			<input type="hidden" name="action" value="add_canned">
			<input type="hidden" name="prev_action" value="<? echo $action ?>">
			<input type="hidden" name="type" value="c">
			<input type="hidden" name="deptid" value="<? echo $deptid ?>">
			<input type="hidden" name="cannedid" value="<? echo $cannedid ?>">
			<td><span class="basetxt"><input type="text" name="name" size="<? echo $text_width ?>" maxlength="20" class="input" value="<? echo $cannedinfo['name'] ?>"></td>
			<td><span class="basetxt">
				<select name="command" class="select" OnChange="put_command( this.selectedIndex )"><option value=""></option><option value="push:" <? echo $selected_push ?>>push:</option>
				<option value="email:" <? echo $selected_email ?>>email:</option>
				<option value="url:" <? echo $selected_url ?>>url:</option>
				<option value="image:" <? echo $selected_image ?>>image:</option>
				</select><input type="text" name="message" size="<? echo $text_width_long - 10 ?>" class="input" OnFocus="check_command()" OnBlur="new_string=replace(this.value, ' ', '');this.value=new_string;return true;" value="<? echo $cannedinfo['message'] ?>"></td>
			<td colspan=2><a href="JavaScript:do_submit()"><img src="../pics/buttons/submit.gif" border=0></a></td>
			</form>
		</tr>
		<tr>
			<td colspan=2>
		</tr>
		<tr>
			<td><span class="basetxt">&nbsp;</td>
			<td colspan=2><span class="smalltxt">
				<font color="#494F72"><b>url:</b></font><i>URL</i> (hyperlink a URL)<br>
				<font color="#494F72"><b>email:</b></font><i>example@osicodes.com</i> (hyperlink a URL)<br>
				<font color="#494F72"><b>image:</b></font><i>URL/sample.gif</i> (embed an image)<br>
				<font color="#494F72"><b>push:</b></font><i>URL</i> (opens new browser containing URL of webpage, word docs, etc.)<br>
			</td>
		</tr>
		</table>




		<? elseif ( $action == "hours" ): ?>
		<img src="../pics/icons/manager.gif" width="32" height="32" border=0 alt="" align="left"> You can place a short message stating your department hours, away message, or best time or ways to reach the department.  It's up to you to put whatever short message you want displayed in the department "Leave a Message" area.
		<p>
		[ <a href="JavaScript:view_screen()">view current "Leave a Message" screen</a> ]
		<p>

		<big><b><? echo $deptinfo['name'] ?> Department Hours of Operation (optional)</b></big><br>
		<form method="POST" action="dept.php" name="form">
		<input type="hidden" name="action" value="add_hours">
		<input type="hidden" name="prev_action" value="<? echo $action ?>">
		<input type="hidden" name="deptid" value="<? echo $deptid ?>">
		(HTML is allowed.  Please keep this message very brief.)
		<br>
		<textarea cols="<? echo $textbox_width ?>" rows="3" wrap="virtual" class="input" name="message"><? echo stripslashes( $deptinfo['message'] ) ?></textarea><p>
		<input type="image" src="../pics/buttons/submit.gif" border=0 alt="Submit">
		</form>
		








		<? elseif ( $action == "greeting" ): ?>
		<img src="../pics/icons/manager.gif" width="32" height="32" border=0 alt="" align="left"> Edit your department chat greeting.  This message is displayed when a visitor waits while a support representative is being contacted.
		<p>
		<form method="POST" action="dept.php" name="form">
		<input type="hidden" name="action" value="update_greeting">
		<input type="hidden" name="prev_action" value="<? echo $action ?>">
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
			<td valign="top"><span class="basetxt"><textarea cols="<? echo $textbox_width ?>" name="greeting" rows="5" wrap="virtual" class="textarea"><?= ( isset( $deptinfo['greeting'] ) ) ? $deptinfo['greeting'] : "" ?></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="image" src="../pics/buttons/submit.gif" border=0></td>
		</tr>
		</table>
		</form>






		<? else: ?>
		<!-- future release may use this -->

		<? endif ; ?>

	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>