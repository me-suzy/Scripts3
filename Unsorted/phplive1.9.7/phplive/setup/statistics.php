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
	include_once("../API/Util_Cal.php" ) ;
	include_once("../API/Users/get.php") ;
	include_once("../API/Logs/get.php") ;

	/*************************************
	* note about status of request:
	* 0 = not taken
	* 1 = taken
	* 2 = not taken
	* 3 = rejected
	* 4 = initiated by operator
	*************************************/
?>
<?

	// initialize
	$action = "" ;
	$m = $y = $d = "" ;

	if ( isset( $HTTP_GET_VARS['m'] ) ) { $m = $HTTP_GET_VARS['m'] ; }
	if ( isset( $HTTP_GET_VARS['d'] ) ) { $d = $HTTP_GET_VARS['d'] ; }
	if ( isset( $HTTP_GET_VARS['y'] ) ) { $y = $HTTP_GET_VARS['y'] ; }

	$departments = AdminUsers_get_AllDepartments( $dbh, $session_setup['aspID'] ) ;
	$admins = AdminUsers_get_AllUsers( $dbh, 0, 0, $session_setup['aspID'] ) ;
	$browsers = ARRAY (
		"IE 6.0" => "MSIE 6.0",
		"IE 5.0" => "MSIE 5.0",
		"IE 5.01" => "MSIE 5.01",
		"IE 5.5" => "MSIE 5.5",
		"Netscape 4.7x" => "Mozilla/4.7",
		"Netscape 6/6.x" => "Netscape6"
	) ;

	if ((!$m) || (!$y))
	{
		$m = date( "m",mktime() ) ;
		$y = date( "Y",mktime() ) ;
		$d = date( "j",mktime() ) ;
	}

	// the timespan to get the stats
	$stat_begin = mktime( 0,0,0,$m,$d,$y ) ;
	$stat_end = mktime( 23,59,59,$m,$d,$y ) ;

	$stat_date = date( "D F d, Y", $stat_begin ) ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
?>
<?
	// functions
?>
<?
	// conditions
?>
<html>
<head>
<title> Support Request Stats </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	
//-->
</script>
<link rel="Stylesheet" href="../css/base.css">
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A">
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
		<b><big>Support Request Stats</big></b> - <a href="options.php">back to menu</a>
		<br>
		<br>
		<img src="../pics/icons/stats.gif" width="32" height="32" border=0 alt="" align="left"> This page shows you the report of the support request breakdown by department(s) and operator(s).  By selecting the date on the calendar, you can view past support requests.  This report also shows you number of requests rejected or not taken.
		<p>
		<small>Click on the month to see monthly breakdown.</small>
		<!-- begin main table -->
		<table cellspacing=2 cellpadding=2 border=0 width="100%">
		<tr>
			<td valign="top"><span class="basetxt">
				<? Util_Cal_DrawCalendar( $dbh, $m, $y, "statistics.php?", "statistics.php", "statistics.php?action=expand_month" ) ; ?>
			</td>
			<td valign="top" width="100%"><span class="basetxt">

				<? if ( $action == "expand_month" ): ?>
				<table cellspacing=1 cellpadding=1 border=0 width="100%">
				<tr bgColor="#8080C0">
					<td width="180"><span class="smalltxt"><font color="#FFFFFF">Day</td>
					<td><span class="smalltxt"><font color="#FFFFFF">Taken</td>
				</tr>
				<?
					$total_requests = 0 ;
					for ( $c = 1; $m == date( "m", mktime( 0,0,0,$m,$c,$y ) ); ++$c )
					{
						$day = date( "F d, Y D", mktime( 0,0,0,$m,$c,$y ) ) ;

						$stat_begin = mktime( 0,0,0,$m,$c,$y ) ;
						$stat_end = mktime( 23,59,59,$m,$c,$y ) ;
						$total = ServiceLogs_get_TotalRequestsPerDay( $dbh, "", $stat_begin, $stat_end, 1, $session_setup['aspID'] ) ;
						$total_requests += $total ;

						$bgcolor = "#EEEEF7" ;
						if ( $c % 2 )
							$bgcolor = "#E6E6F2" ;

						print "<tr bgColor=\"$bgcolor\"><td><span class=\"basetxt\"><a href=\"statistics.php?d=$c&m=$m&y=$y\">$day</td><td><span class=\"basetxt\">$total</td></tr>" ;
					}
				?>
				<tr bgColor="#8080C0">
					<td width="180" nowrap><span class="smalltxt"><font color="#FFFFFF">Total Requests Taken</td>
					<td><span class="smalltxt"><font color="#FFFFFF"><? echo $total_requests ?></td>
				</tr>
				</table>





				<? else: ?>
				<b><? echo $stat_date ?></b>
				<p>

				Department Breakdown <small>- <a href="adddept.php">Manage Departments</a></small>
				<!-- begin departments -->
				<table cellspacing=1 cellpadding=1 border=0 width="100%">
				<tr bgColor="#8080C0">
					<td width="150" nowrap><span class="basetxt"><font color="#FFFFFF">Department</td>
					<td nowrap><span class="smalltxt"><font color="#FFFFFF">Taken</td>
					<td nowrap><span class="smalltxt"><font color="#FFFFFF">Not Taken</td>
					<td nowrap><span class="smalltxt"><font color="#FFFFFF">Rejected</td>
					<td nowrap><span class="smalltxt"><font color="#FFFFFF">Initiated</td>
				</tr>
				<?
					// 0-request not taken, 1-request taken, 3-rejected
					for ( $c = 0; $c < count( $departments ); ++$c )
					{
						$department = $departments[$c] ;
						$total_taken = ServiceLogs_get_TotalRequestsPerDay( $dbh, $department['deptID'], $stat_begin, $stat_end, 1, $session_setup['aspID'] ) ;
						$total_nottaken = ServiceLogs_get_TotalRequestsPerDay( $dbh, $department['deptID'], $stat_begin, $stat_end, 0, $session_setup['aspID'] ) ;
						$total_rejected = ServiceLogs_get_TotalRequestsPerDay( $dbh, $department['deptID'], $stat_begin, $stat_end, 3, $session_setup['aspID'] ) ;
						$total_initiated = ServiceLogs_get_TotalRequestsPerDay( $dbh, $department['deptID'], $stat_begin, $stat_end, 3, $session_setup['aspID'] ) ;

						$bgcolor = "#EEEEF7" ;
						if ( $c % 2 )
							$bgcolor = "#E6E6F2" ;

						print "
						<tr bgColor=\"$bgcolor\">
							<td width=\"120\"><span class=\"basetxt\">$department[name]</td>
							<td><span class=\"smalltxt\">$total_taken</td>
							<td><span class=\"smalltxt\">$total_nottaken</td>
							<td><span class=\"smalltxt\">$total_rejected</td>
							<td><span class=\"smalltxt\">$total_initiated</td>
						</tr>
						" ;
					}
				?>
				</table>
				<!-- end departments -->
				<br>

				<!-- begin user stats -->
				User Breakdown: <small>- <a href="adduser.php">Manage Users</a></small><br>
				<table cellspacing=1 cellpadding=1 border=0 width="100%">
				<tr bgColor="#8080C0">
					<td width="60"><span class="smalltxt"><font color="#FFFFFF">Login</td>
					<td width="80" nowrap><span class="smalltxt"><font color="#FFFFFF">Name</td>
					<td nowrap><span class="smalltxt"><font color="#FFFFFF">Taken</td>
					<td nowrap><span class="smalltxt"><font color="#FFFFFF">Not Taken</td>
					<td nowrap><span class="smalltxt"><font color="#FFFFFF">Rejected</td>
					<td nowrap><span class="smalltxt"><font color="#FFFFFF">Initiated</td>
				</tr>
				<?
					// 0-request not taken, 1-request taken, 3-rejected
					for ( $c = 0; $c < count( $admins ); ++$c )
					{
						$admin = $admins[$c] ;
						$bgcolor = "#EEEEF7" ;
						if ( $c % 2 )
							$bgcolor = "#E6E6F2" ;

						$total_taken = ServiceLogs_get_TotalUserRequestCountPerDay( $dbh, $admin['userID'], $stat_begin, $stat_end, 1, $session_setup['aspID'] ) ;
						$total_nottaken = ServiceLogs_get_TotalUserRequestCountPerDay( $dbh, $admin['userID'], $stat_begin, $stat_end, 0, $session_setup['aspID'] ) ;
						$total_rejected = ServiceLogs_get_TotalUserRequestCountPerDay( $dbh, $admin['userID'], $stat_begin, $stat_end, 3, $session_setup['aspID'] ) ;
						$total_initiated = ServiceLogs_get_TotalUserRequestCountPerDay( $dbh, $admin['userID'], $stat_begin, $stat_end, 4, $session_setup['aspID'] ) ;

						//$date = date( "m/d/y", $admin['created'] ) ;

						print "
							<tr bgcolor=\"$bgcolor\">
								<td><span class=\"smalltxt\">$admin[login]</td>
								<td><span class=\"smalltxt\">$admin[name]</td>
								<td><span class=\"smalltxt\">$total_taken</td>
								<td><span class=\"smalltxt\">$total_nottaken</td>
								<td><span class=\"smalltxt\">$total_rejected</td>
								<td><span class=\"smalltxt\">$total_initiated</td>
							</tr>
						" ;
					}
				?>
				</table>
				<!-- end user stats -->
				<p>
				<span class="smalltxt">
				<font color="#0000FF">Taken</font> - Operator has taken a visitor's support request.<br>
				<font color="#0000FF">Not Taken</font> - Operator did not take NOR rejected the request.  They let it timeout.<br>
				<font color="#0000FF">Rejected</font> - Operator has clicked "Busy" and rejected the request.<br>
				<font color="#0000FF">Initiated</font> - Operator initiated the chat request.
				</span>
				<? endif ; ?>


			</td>
		</tr>
		</table>
		<!-- end main table -->
	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>