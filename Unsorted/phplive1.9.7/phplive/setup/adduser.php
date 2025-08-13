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
	include_once("../API/Users/get.php") ;
	include_once("../API/Users/put.php") ;
	include_once("../API/Users/remove.php") ;
	include_once("../API/Users/update.php") ;
	include_once("../API/ASP/get.php") ;
?>
<?

	// initialize
	$action = $userid = $deptid = $error = "" ;
	$success = 0 ;

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "20" ;
	else
		$text_width = "10" ;
	$timespan_select = ARRAY( "Days", "Months", "Years" ) ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }
	if ( isset( $HTTP_GET_VARS['userid'] ) ) { $userid = $HTTP_GET_VARS['userid'] ; }
	if ( isset( $HTTP_POST_VARS['userid'] ) ) { $userid = $HTTP_POST_VARS['userid'] ; }
	if ( isset( $HTTP_GET_VARS['success'] ) ) { $success = $HTTP_GET_VARS['success'] ; }
	if ( isset( $HTTP_POST_VARS['success'] ) ) { $success = $HTTP_POST_VARS['success'] ; }
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "add_user" )
	{
		// if $userid is passed, then we want to update that userid
		if ( $userid )
		{
			$edit_admin = AdminUsers_get_UserInfo( $dbh, $userid, $session_setup['aspID'] ) ;
			if ( !AdminUsers_get_IsNameTaken( $dbh, $HTTP_POST_VARS['name'], $session_setup['aspID'] ) || ( $edit_admin['name'] == $HTTP_POST_VARS['name'] ) )
			{
				AdminUsers_update_User( $dbh, $userid, $HTTP_POST_VARS['login'], $HTTP_POST_VARS['password'], $HTTP_POST_VARS['name'], $HTTP_POST_VARS['email'], $COMPANY_NAME, $session_setup['aspID'] ) ;
				
				$userid = 0 ;
				unset( $HTTP_POST_VARS['edit_admin'] ) ;
				$success = 1 ;
			}
			else
				$error = "The name ($HTTP_POST_VARS[name]) is already in use.  Please choose another name." ;
		}
		else
		{
			// let's check to make sure they do not exceed max number of users
			$aspinfo = AdminASP_get_UserInfo( $dbh, $session_setup['aspID'] ) ;
			$total_users = AdminUsers_get_TotalUsers( $dbh, $session_setup['aspID'] ) ;

			if ( $total_users < $aspinfo['max_users'] )
			{
				if ( !AdminUsers_get_IsLoginTaken( $dbh, $HTTP_POST_VARS['login'], $session_setup['aspID'] ) )
				{
					if ( !AdminUsers_get_IsNameTaken( $dbh, $HTTP_POST_VARS['name'], $session_setup['aspID'] ) )
					{
						if ( AdminUsers_put_user( $dbh, $HTTP_POST_VARS['login'], $HTTP_POST_VARS['password'], $HTTP_POST_VARS['name'], $HTTP_POST_VARS['email'], $COMPANY_NAME, $session_setup['aspID'] ) )
							$success = 1 ;
						else
							$error = "Error: ".$dbh[error] ;
					}
					else
						$error = "The name ($HTTP_POST_VARS[name]) is already in use.  Please choose another name." ;
				}
				else
					$error = "The login ($HTTP_POST_VARS[login]) is already in use. Please use another." ;
			}
			else
				$error = "Your MAX operator limit has been reached!  User COULD NOT be added." ;
		}
	}
	else if ( ( $action == "add_deptuser" ) && isset( $HTTP_POST_VARS['users'] ) )
	{
		$users = $HTTP_POST_VARS['users'] ;
		for ( $c = 0; $c < count( $users ); ++$c )
		{
			AdminUsers_put_DeptUser( $dbh, $users[$c], $deptid ) ;
		}
		$deptid = 0 ;
		$success = 1 ;
	}
	else if ( $action == "delete" )
	{
		AdminUsers_remove_user( $dbh, $userid, $session_setup['aspID'] ) ;
		$userid = 0 ;
		unset( $HTTP_POST_VARS['edit_admin'] ) ;
	}
	else if ( $action == "delete_deptuser" )
	{
		AdminUsers_remove_DeptUser( $dbh, $userid, $deptid ) ;
		$userid = 0 ;
		unset( $HTTP_POST_VARS['edit_admin'] ) ;
	}
	else if ( $action == "order" )
	{
		$users = $HTTP_POST_VARS['order'] ;
		while ( LIST ( $userid, $order ) = EACH( $users ) )
		{
			AdminUsers_update_UserDeptOrder( $dbh, $userid, $deptid, $order ) ;
		}
		HEADER ( "location: adduser.php?deptid=$deptid&success=1" ) ;
		exit ;
	}

	if ( $userid )
		$edit_admin = AdminUsers_get_UserInfo( $dbh, $userid, $session_setup['aspID'] ) ;

	$admins = AdminUsers_get_AllUsers( $dbh, 0, 0, $session_setup['aspID'] ) ;
	$departments = AdminUsers_get_AllDepartments( $dbh, $session_setup['aspID'] ) ;
?>
<html>
<head>
<title> Manage Users </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	function do_update_user()
	{
		if ( ( document.user.name.value == "" ) || ( document.user.login.value == "" )
			|| ( document.user.password.value == "" ) || ( document.user.email.value == "" ) )
		{
			alert( "All fields must be supplied." ) ;
		}
		else
			document.user.submit() ;
	}

	function do_delete( userid )
	{
		if ( confirm( "Are you sure you want to delete?" ) )
			location.href = "adduser.php?action=delete&userid="+userid ;
	}

	function remove_deptuser( userid, deptid )
	{
		if ( confirm( "Really delete this user from this department?" ) )
			location.href = "adduser.php?action=delete_deptuser&userid="+userid+"&deptid="+deptid ;
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
		<big><b>Create/Edit Operators</b></big> - <a href="options.php">back to menu</a>
		<br>
		<font color="#FF0000"><? echo $error ?></font>
		<br>
		<img src="../pics/icons/manager.gif" width="32" height="32" border=0 alt="" align="left"> Create operators here.  Remember, after you create a new operator, YOU MUST ASSIGN the operator to a department (if there are no operators assigned to a department, status will always remain offline!).  The system also allows you to assign an operator to multiple departments. 
		<p>
	
		<table cellspacing=1 cellpadding=2 border=0>
		<tr>
			<td valign="top"><span class="basetxt">
				<!-- top header with user and dept info -->
				<table cellspacing=0 cellpadding=1 border=0>
				<tr>
					<td valign="top"><span class="basetxt">
						<big><b><font color="#FF0000">Step 1</font>: Create an Operator</b></big><br>
						<form method="POST" action="adduser.php" name="user">
						<input type="hidden" name="action" value="add_user">
						<input type="hidden" name="userid" value="<?= isset( $edit_admin['userID'] ) ? $edit_admin['userID'] : "" ?>">
						<table cellpadding=1 cellspacing=1 border=0>
						<tr>
							<td><span class="basetxt">Login</td>
							<td><font size=2 face="arial"> <input type="text" name="login" size="<? echo $text_width ?>" maxlength="15" class="input" value="<?= isset( $edit_admin['login'] ) ? $edit_admin['login'] : "" ?>"></td>
							<td><span class="basetxt">Password</td>
							<td><span class="basetxt"><font size=2 face="arial"> <input type="text" name="password" size="<? echo $text_width ?>" maxlength="15" class="input"></td>
						</tr>
						<tr>
							<td><span class="basetxt">Name</td>
							<td><span class="basetxt"><font size=2 face="arial"> <input type="text" name="name" size="<? echo $text_width ?>" maxlength="50" class="input" value="<?= isset( $edit_admin['name'] ) ? $edit_admin['name'] : "" ?>"></td>
							<td><span class="basetxt">Email</td>
							<td><span class="basetxt"><font size=2 face="arial"> <input type="text" name="email" size="<? echo $text_width ?>" maxlength="150" class="input" value="<?= isset( $edit_admin['email'] ) ? $edit_admin['email'] : "" ?>"></td>
						</tr>
						<tr>
							<td bgColor="#C7C7E2" colspan=4><span class="basetxt">Submit to add operator -&gt; <a href="JavaScript:do_update_user()"><img src="../pics/buttons/submit.gif" border=0></a></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<!-- end top header with user and dept info -->
				</form>
				<hr>

				<!-- users list begin -->
				<p>
				<big><b><font color="#FF0000">Step 2</font>: Assign Operator to a department.</b></big><br>
				<table cellspacing=1 cellpadding=2 border=0 width="100%">
				<tr bgColor="#8080C0">
					<td width="5"><span class="smalltxt">&nbsp;</td>
					<td width="60"><span class="smalltxt"><font color="#FFFFFF">Login</td>
					<td><span class="smalltxt"><font color="#FFFFFF">Name</td>
					<td><span class="smalltxt"><font color="#FFFFFF">Company</td>
					<td><span class="smalltxt"><font color="#FFFFFF">Email</td>
					<td><span class="smalltxt">&nbsp;</td>
					<td><span class="smalltxt">&nbsp;</td>
				</tr>
				<form method="POST" action="adduser.php">
				<input type="hidden" name="action" value="add_deptuser">
				<?
					for ( $c = 0; $c < count( $admins ); ++$c )
					{
						$admin = $admins[$c] ;
						$bgcolor = "#EEEEF7" ;
						if ( $c % 2 )
							$bgcolor = "#E6E6F2" ;

						$date = date( "D m/d/y h:i a", $admin['created'] ) ;

						print "
							<tr bgcolor=\"$bgcolor\">
								<td><span class=\"smalltxt\"><font size=2><input type=\"checkbox\" name=\"users[]\" value=\"$admin[userID]\" class=\"checkbox\"></td>
								<td><span class=\"smalltxt\">$admin[login]</td>
								<td><span class=\"smalltxt\">$admin[name]</td>
								<td><span class=\"smalltxt\">$admin[company]</td>
								<td><span class=\"smalltxt\"><a href=\"mailto:$admin[email]\">$admin[email]</a></td>
								<td><span class=\"smalltxt\"><a href=\"adduser.php?userid=$admin[userID]\">Edit</a></td>
								<td><span class=\"smalltxt\"><a href=\"JavaScript:do_delete( $admin[userID] )\">Delete</a></td>
							</tr>
						" ;
					}
				?>
				<tr>
					<td colspan="8" bgColor="#C7C7E2"><span class="basetxt">Add checked operator(s) to department <select name="deptid" class="select">
					<?
						for ( $c = 0; $c < count( $departments ); ++$c )
						{
							$department = $departments[$c] ;
							print "<option value=$department[deptID]>$department[name]</option>" ;
						}
					?>
					</select> <input type="image" src="../pics/buttons/submit.gif" border=0></td>
				</tr>
				</form>
				</table>
				<!-- users list end -->
			</td>

			<td valign="top" width=300><span class="basetxt">
				<span class="smalltxt">Department(s) and assigned operator(s).</span>
				<table cellspacing=1 cellpadding=1 border=0 width="100%">
				<tr bgColor="#8080C0">
					<td width=80><span class="smalltxt"><font color="#FFFFFF">Department</td>
					<td><span class="smalltxt"><font color="#FFFFFF">Operators</td>
				</tr>
				<?
					for ( $c = 0; $c < count( $departments ); ++$c )
					{
						$department = $departments[$c] ;
						$department_users = AdminUsers_get_AllDeptUsers( $dbh, $department['deptID'] ) ;

						$bgcolor = "#EEEEF7" ;
						if ( $c % 2 )
							$bgcolor = "#E6E6F2" ;
				
						print "<tr bgColor=\"$bgcolor\"><td valign=\"top\"><span class=\"smalltxt\">$department[name]</td>" ;
						print "
								<td valign=\"top\"><font size=1><span class=\"smalltxt\">
								<form method=POST action=\"adduser.php\">
								<input type=\"hidden\" name=\"action\" value=\"order\">
								<input type=\"hidden\" name=\"deptid\" value=\"$department[deptID]\">
								<table cellspacing=0 cellpadding=2 border=0>
						" ;

						$update_string = "" ;
						if ( count( $department_users ) > 0 )
							$update_string = "Update Request Order <input type=\"image\" src=\"../pics/buttons/submit.gif\" border=0>" ;
						for ( $c2 = 0; $c2 < count( $department_users ); ++$c2 )
						{
							$user = $department_users[$c2] ;
							$ordernum = AdminUsers_get_DeptUserOrderNum( $dbh, $user['userID'], $department['deptID'] ) ;
							print "
									<tr>
									<td><span class=\"smalltxt\">$user[login]</td>
									<td><font size=1><span class=\"smalltxt\"><input type=\"text\" name=\"order[$user[userID]]\" value=\"$ordernum\" class=\"smalltxt\" size=2 maxlength=3 onKeyPress=\"return numbersonly(event)\"></td>
									<td><span class=\"smalltxt\">[<a href=\"JavaScript:remove_deptuser( $user[userID], $department[deptID] )\">remove</a>]</td>
									</tr>
									" ;
						}
						print "</table>$update_string</form></td></tr>" ;
					}
				?>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>