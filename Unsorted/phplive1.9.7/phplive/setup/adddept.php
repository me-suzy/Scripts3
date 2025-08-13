<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	if ( isset( $HTTP_SESSION_VARS['session_setup'] ) ) { $session_setup = $HTTP_SESSION_VARS['session_setup'] ; } else { HEADER( "location: index.php" ) ; exit ; }
	if ( !file_exists( "../web/".$session_setup['login']."/".$session_setup['login']."-conf-init.php" ) || !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: index.php" ) ;
		exit ;
	}
	include_once("../web/conf-init.php");
	include_once("../web/".$session_setup['login']."/".$session_setup['login']."-conf-init.php") ;
	include_once("../system.php") ;
	include_once("../lang_packs/$LANG_PACK.php") ;
	include_once("../API/sql.php" ) ;
	include_once("../API/Users/get.php") ;
	include_once("../API/Users/put.php") ;
	include_once("../API/Users/remove.php") ;
	include_once("../API/Users/update.php") ;
	include_once("../API/ASP/get.php") ;
?>
<?
	// initialize
	$action = $error = $deptid = $edit_exp_value = $edit_exp_word = "" ;

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "20" ;
	else
		$text_width = "10" ;

	$success = $close_window = 0 ;

	$timespan_select = ARRAY( 1=>"Days", 2=>"Months", 3=>"Years" ) ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "add_dept" )
	{
		$aspinfo = AdminASP_get_UserInfo( $dbh, $session_setup['aspID'] ) ;
		$total_departments = AdminUsers_get_TotalDepartments( $dbh, $session_setup['aspID'] ) ;
		
		// let's make sure they don't exceed their max departments
		if ( $total_departments <= $aspinfo['max_dept'] )
		{
			if ( !$deptid && ( $total_departments == $aspinfo['max_dept'] ) )
				$error = "Your MAX department limit has been reached!  Department COULD NOT be added." ;
			else
			{
				$initiate_chat = ( isset( $HTTP_POST_VARS['initiate_chat'] ) ) ? $HTTP_POST_VARS['initiate_chat'] : 0 ;
				if ( AdminUsers_put_department( $dbh, $deptid, $HTTP_POST_VARS['name'], $HTTP_POST_VARS['email'], $HTTP_POST_VARS['save_transcripts'], $HTTP_POST_VARS['share_transcripts'], $HTTP_POST_VARS['exp_value'], $HTTP_POST_VARS['exp_word'], $session_setup['aspID'], $initiate_chat, $LANG['CHAT_GREETING'] ) )
				{
					$deptid = "" ;
					$success = 1 ;
				}
				else
					$error = "Error: ".$dbh['error'] ;
			}
		}
		else
			$error = "Your MAX department limit has been reached!  Department COULD NOT be added." ;
	}
	else if ( $action == "do_delete" )
	{
		AdminUsers_remove_Dept( $dbh, $deptid, $HTTP_POST_VARS['transfer_deptid'], $session_setup['aspID'] ) ;
		$close_window = 1 ;
	}

	if ( $deptid )
	{
		$edit_dept = AdminUsers_get_DeptInfo( $dbh, $deptid, $session_setup['aspID'] ) ;
		LIST( $edit_exp_value, $edit_exp_word ) = explode( "<:>", $edit_dept['transcript_expire_string'] ) ;
	}

	$departments = AdminUsers_get_AllDepartments( $dbh, $session_setup['aspID'] ) ;
?>
<html>
<head>
<title> Department Management </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	function do_update_dept()
	{
		if ( ( document.dept.name.value == "" ) || ( document.dept.email.value == "" ) )
			alert( "All fields must be supplied." ) ;
		else
			document.dept.submit() ;
	}

	function do_delete( deptid )
	{
		window.open("adddept.php?action=confirm_delete&deptid="+deptid, 'Confirm', 'scrollbars=no,menubar=no,resizable=0,location=no,width=250,height=200') ;
	}

	function do_alert()
	{
		if( <? echo $success ?> )
			alert( 'Success!' ) ;
		if( <? echo $close_window ?> )
		{
			opener.window.location.href = "adddept.php?s=1" ;
			window.close() ;
		}
	}

	function confirm_delete()
	{
		if ( confirm( "Really delete department?" ) )
			document.form.submit() ;
	}
//-->
</script>
<link rel="Stylesheet" href="../css/base.css">
<script language="JavaScript" src="../js/global.js"></script>
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" OnLoad="do_alert()">
<span class="basetxt">

<? 
	if ( $action == "confirm_delete" ):
	$deptinfo = AdminUsers_get_DeptInfo( $dbh, $deptid, $session_setup['aspID'] ) ;
?>
<form method="POST" action="adddept.php" name="form">
<input type="hidden" name="action" value="do_delete">
<input type="hidden" name="deptid" value="<? echo $deptid ?>">

Transfer all users, transcripts and logs from department <b><font color="#660000"><? echo $deptinfo['name'] ?></font></b> to:
<p>
<select name="transfer_deptid" class="select" class="select">
<?
	for ( $c = 0; $c < count( $departments ); ++$c )
	{
		$department = $departments[$c] ;

		if ( $department['deptID'] != $deptid )
			print "<option value=".$department['deptID'].">".$department['name']."</option>" ;
	}
?>
</select>
<p>
<a href="JavaScript:confirm_delete()"><img src="../pics/buttons/submit.gif" border=0></a></td>
</form>


<? elseif ( $close_window ): ?>
<!-- put nothing here if window is to close -->



<? else: ?>
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

		<form method="POST" action="adddept.php" name="dept">
		<input type="hidden" name="action" value="add_dept">
		<input type="hidden" name="deptid" value="<?= isset( $edit_dept['deptID'] ) ? $edit_dept['deptID'] : "" ?>">
		<big><b>Create/Edit Department</b></big> - <a href="options.php">back to menu</a>
		<br>
		<font color="#FF0000"><? echo $error ?></font>
		<br>
		<img src="../pics/icons/manager.gif" width="32" height="32" border=0 alt="" align="left"> Here, you can create, edit or delete your company departments.  The <i>department email</i> is where the messages will delivered on the "Leave a Message Form" when operators are not available.  The <i>auto save transcripts</i> enables the system to automatically save all support transcripts without manually saving them.  Setting transcript <i>expire time</i> to zero (0) will NOT delete the transcripts.
		<p>

		<table cellspacing=1 cellspacing=1 border=0>
		<tr>
			<td><span class="basetxt">Department</td>
			<td><font size=2 face="arial"> <input type="text" name="name" size="<? echo $text_width ?>" maxlength="30" class="input" value="<?= isset( $edit_dept['name'] ) ? $edit_dept['name'] : "" ?>"></td>
		</tr>
		<tr>
			<td><span class="basetxt">Department Email </td>
			<td><font size=2 face="arial"> <input type="text" name="email" size="<? echo $text_width ?>" maxlength="150" class="input" value="<?= isset( $edit_dept['email'] ) ? $edit_dept['email'] : "" ?>"></td>
		</tr>
		<tr>
			<td><span class="basetxt">Auto save transcripts? </td>
			<td><span class="basetxt"><font size=2 face="arial">
				<select name="save_transcripts" class="select">
				<?= ( isset( $edit_dept['transcript_save'] ) && $edit_dept['transcript_save'] ) ? "<option value=1 selected>Yes<option value=0>No" : "<option value=1>Yes<option value=0 selected>No" ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><span class="basetxt">Share saved transcripts? </td>
			<td><span class="basetxt"><font size=2 face="arial">
				<select name="share_transcripts" class="select">
				<?= ( isset( $edit_dept['transcript_share'] ) && $edit_dept['transcript_share'] ) ? "<option value=1 selected>Yes<option value=0>No" : "<option value=1>Yes<option value=0 selected>No" ?>
				</select>
			</td>
		</tr>
		<? if ( $INITIATE && file_exists( "$DOCUMENT_ROOT/admin/traffic/admin_puller.php" ) ): ?>
		<tr>
			<td colspan=2><span class="smalltxt"><font color="#FF0000">If initiate chat is disabled, operator's traffic monitor will also be disabled.</font></td>
		</tr>
		<tr>
			<td><span class="basetxt">Allow initiate chat? </td>
			<td><span class="basetxt"><font size=2 face="arial">
				<select name="initiate_chat" class="select">
				<?= ( isset( $edit_dept['initiate_chat'] ) && $edit_dept['initiate_chat'] ) ? "<option value=1 selected>Yes<option value=0>No" : "<option value=1>Yes<option value=0 selected>No" ?>
				</select>
			</td>
		</tr>
		<? endif ; ?>
		<tr>
			<td><span class="basetxt">Transcripts expire after</td>
			<td><span class="basetxt"><font size=2 face="arial">
				<input type="text" name="exp_value" size=2 maxlength=3 class="input" value="<? echo $edit_exp_value ?>" onKeyPress="return numbersonly(event)">
				<select name="exp_word" class="select">
				<?
					while ( LIST( $option_value, $option_string ) = EACH( $timespan_select ) )
					{
						$selected = "" ;
						if ( $option_value == $edit_exp_word )
							$selected = "selected" ;

						print "					<option value=\"$option_value\" $selected>$option_string</option>\n" ;
					}

					// reset it so we can use again below
					reset( $timespan_select ) ;
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><br><a href="JavaScript:do_update_dept()"><img src="../pics/buttons/submit.gif" border=0></a><br><br></td>
		</tr>
		</table>
		</form>
	</td>
</tr>
<tr>
	<td><span class="basetxt">
		<!-- begin department list -->

		<table cellspacing=1 cellpadding=2 border=0 width="100%">
		<?
			for ( $c = 0; $c < count( $departments ); ++$c )
			{
				$department = $departments[$c] ;

				$transcripts_share = "No" ;
				if ( $department['transcript_share'] )
					$transcripts_share = "Yes" ;
				$transcripts_save = "No" ;
				if ( $department['transcript_save'] )
					$transcripts_save = "Yes" ;
				$initiate_chat = "No" ;
				if ( $department['initiate_chat'] )
					$initiate_chat = "Yes" ;

				$initiate_column = "" ;
				if ( $INITIATE )
					$initiate_column = "<td><span class=\"smalltxt\">$initiate_chat</td>" ;

				LIST ( $expire_value, $expire_string ) = explode( "<:>", $department['transcript_expire_string'] ) ;
				$bgcolor = "#EEEEF7" ;
				if ( $c % 2 )
					$bgcolor = "#E6E6F2" ;

				$delete_string = "" ;
				if ( count( $departments ) > 1 )
					$delete_string = "<a href=\"JavaScript:do_delete( ".$department['deptID']." )\">Delete</a>" ;

				$initiate_option = "" ;
				if ( $INITIATE )
					$initiate_option = "<td><span class=\"smalltxt\"><font color=\"#FFFFFF\">Initiate Chat</td>" ;
				print "
					<tr bgColor=\"#8080C0\">
						<td><span class=\"smalltxt\"><font color=\"#FFFFFF\">Department</td>
						<td width=\"150\"><span class=\"smalltxt\"><font color=\"#FFFFFF\">Email</td>
						<td><span class=\"smalltxt\"><font color=\"#FFFFFF\">Auto Save Transcripts</td>
						<td><span class=\"smalltxt\"><font color=\"#FFFFFF\">Share Transcripts</td>
						<td><span class=\"smalltxt\"><font color=\"#FFFFFF\">Transcripts Expire</td>
						$initiate_option
						<td><span class=\"smalltxt\">&nbsp;</td>
						<td><span class=\"smalltxt\">&nbsp;</td>
					</tr>
					<tr bgColor=\"$bgcolor\">
						<td><span class=\"smalltxt\">$department[name]</td>
						<td><span class=\"smalltxt\"><a href=\"mailto:$department[email]\">$department[email]</a></td>
						<td><span class=\"smalltxt\">$transcripts_save</td>
						<td><span class=\"smalltxt\">$transcripts_share</td>
						<td><span class=\"smalltxt\">$expire_value $timespan_select[$expire_string]</td>
						$initiate_column
						<td><span class=\"smalltxt\"><a href=\"adddept.php?deptid=$department[deptID]\">Edit</a></td>
						<td><span class=\"smalltxt\">$delete_string</td>
					</tr>
					<tr bgColor=\"$bgcolor\">
						<td><span class=\"smalltxt\">&nbsp;</td>
						<td colspan=7 bgColor=\"#D7D7FF\"><span class=\"smalltxt\">
							<img src=\"../pics/dot.gif\" width=5 height=5> <a href=\"dept_icons.php?deptid=$department[deptID]\">online/offline icon</a> 
							&nbsp;&nbsp;
							<img src=\"../pics/dot.gif\" width=5 height=5> <a href=\"dept.php?action=greeting&deptid=$department[deptID]\">edit greeting</a> 
							&nbsp;&nbsp;
							<img src=\"../pics/dot.gif\" width=5 height=5> <a href=\"dept.php?action=hours&deptid=$department[deptID]\">support hours</a> 
							&nbsp;&nbsp;
							<img src=\"../pics/dot.gif\" width=5 height=5> <a href=\"dept.php?action=canned_responses&deptid=$department[deptID]\">canned responses</a>
							&nbsp;&nbsp;
							<img src=\"../pics/dot.gif\" width=5 height=5> <a href=\"dept.php?action=canned_commands&deptid=$department[deptID]\">canned commands</a>
						</td>
					</tr>
					<tr>
						<td colspan=8>&nbsp;</td>
					</tr>
				" ;
			}
		?>
		</table>

		<!-- end department list -->
	</td>
</tr>
</table>
<? endif ; ?>

<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>