<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$sid = $action = $deptid = "" ;
	$session_admin = $HTTP_SESSION_VARS['session_admin'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : $HTTP_POST_VARS['sid'] ;
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	if ( isset( $HTTP_POST_VARS['deptid'] ) ) { $deptid = $HTTP_POST_VARS['deptid'] ; }
	if ( isset( $HTTP_GET_VARS['deptid'] ) ) { $deptid = $HTTP_GET_VARS['deptid'] ; }

	if ( !file_exists( "../web/".$session_admin[$sid]['asp_login']."/".$session_admin[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "../web/conf-init.php" ) )
	{
		HEADER( "location: index.php" ) ;
		exit ;
	}
	include_once("../web/conf-init.php") ;
	include_once("../web/".$session_admin[$sid]['asp_login']."/".$session_admin[$sid]['asp_login']."-conf-init.php") ;
	include_once("../system.php") ;
	include_once("../API/sql.php") ;
	include_once("../API/Util_Optimize.php") ;
	include_once("../API/Users/get.php") ;
	include_once("../API/Chat/get.php") ;
?>
<?
	// functions
?>
<?
	// conditions
?>
<html>
<head>
<title> Operator Console [options] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="../css/base.css">

<script language="JavaScript">
<!--

//-->
</script>
</head>
<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" marginheight="0" marginwidth="0" topmargin="0" leftmargin="2">
<span class="basetxt">
<form action="admin_options.php" method="POST">


<?
	if ( $action == "select_dept"):
	$user_select = "" ;
	$deptinfo = AdminUsers_get_DeptInfo( $dbh, $deptid, $session_admin[$sid]['aspID'] ) ;
	$department_users = AdminUsers_get_AllDeptUsers( $dbh, $deptinfo['deptID'] ) ;
	for ( $c = 0; $c < count( $department_users ); ++$c )
	{
		$user = $department_users[$c] ;

		if ( $user['userID'] != $session_admin[$sid]['admin_id'] )
		{
			//$total_sessions = ServiceChat_get_UserTotalChatSessions( $dbh, $user['name'] ) ;
			$status = "offline" ;
			$bgcolor = "#FFDDDD" ;
			if ( ( $user['available_status'] == 1 ) && ( $user['last_active_time'] > $admin_idle ) )
			{
				$status = "online" ;
				$bgcolor = "#DDDDFF" ;
			}
			$user_select .= "<option value=\"$user[userID]\" style=\"background:$bgcolor\">$user[name] ($status)</option>" ;
		}
	}
?>
<input type="hidden" name="action" value="request">
<input type="hidden" name="sid" value="<? echo $sid ?>">
<table cellspacing=0 cellpadding=2 border=0 height="100%">
<tr>
	<td><span class="basetxt"><? echo $deptinfo['name'] ?> -></td>
	<td><span class="basetxt"><font size=2><select name="deptid" class="select"><? echo $user_select ?></select></td>
	<td><span class="basetxt"><input type=image src="../pics/buttons/submit.gif" border=0 alt="Select Department"></td><td><span class="smalltxt">&nbsp; [ <a href="admin_options.php?sid=<? echo $sid ?>">cancel</a> ]</td>
</tr>
</table>









<? 
	else:
	$department_select = "" ;
	$departments = AdminUsers_get_AllDepartments( $dbh, $session_admin[$sid]['aspID'] ) ;
	for ( $c = 0; $c < count( $departments ); ++$c )
	{
		$department = $departments[$c] ;
		$department_select .= "<option value=\"$department[deptID]\">$department[name]</option>" ;
	}
?>
<input type="hidden" name="action" value="select_dept">
<input type="hidden" name="sid" value="<? echo $sid ?>">
<table cellspacing=0 cellpadding=2 border=0 height="100%">
<tr>
	<td><span class="basetxt">Operator to Operator chat request -></td>
	<td><span class="basetxt"><font size=2><select name="deptid" class="select"><? echo $department_select ?></select></td>
	<td><span class="basetxt"><input type=image src="../pics/buttons/submit.gif" border=0 alt="Select Department"></td>
</tr>
</table>
<? endif ; ?>






</form>
</span>
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>