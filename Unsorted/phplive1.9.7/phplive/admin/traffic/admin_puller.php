<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$sid = $action = $ip = $start = "" ;
	$do_pull = 1 ;
	$session_admin = $HTTP_SESSION_VARS['session_admin'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : $HTTP_POST_VARS['sid'] ;
	$l = ( isset( $HTTP_GET_VARS['l'] ) ) ? $HTTP_GET_VARS['l'] : "" ;
	$x = ( isset( $HTTP_GET_VARS['x'] ) ) ? $HTTP_GET_VARS['x'] : "" ;
	$ip = ( isset( $HTTP_GET_VARS['ip'] ) ) ? $HTTP_GET_VARS['ip'] : "" ;
	$start = ( isset( $HTTP_GET_VARS['start'] ) ) ? $HTTP_GET_VARS['start'] : "" ;

	if ( !file_exists( "../../web/$l/$l-conf-init.php" ) || !file_exists( "../../web/conf-init.php" ) )
	{
		HEADER( "location: ../index.php" ) ;
		exit ;
	}
	include_once("../../web/conf-init.php") ;
	include_once("../../web/$l/$l-conf-init.php") ;
	include_once("$DOCUMENT_ROOT/system.php") ;
	include_once("$DOCUMENT_ROOT/lang_packs/$LANG_PACK.php") ;
	include_once("$DOCUMENT_ROOT/API/sql.php" ) ;
	include_once("$DOCUMENT_ROOT/API/Chat/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Users/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Footprint_unique/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Footprint/get.php") ;
	include_once("$DOCUMENT_ROOT/API/Refer/get.php") ;
?>
<?
	// make sure they have access to this page
	// if admin session is set, then they have access
	if ( !$session_admin[$sid]['admin_id'] )
	{
		HEADER( "location: ../../index.php" ) ;
		exit ;
	}

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
	{
		$text_width = "65" ;
	}
	else
	{
		$text_width = "30" ;
	}

	// initialize
	$m = date( "m",mktime() ) ;
	$y = date( "Y",mktime() ) ;
	$d = date( "j",mktime() ) ;

	// the timespan to get the stats
	$begin = mktime( 0,0,0,$m,$d,$y ) ;
	$end = mktime( 23,59,59,$m,$d,$y ) ;

	// we use $rand to prevent loading from cached pages
	mt_srand ((double) microtime() * 1000000);
	$rand = mt_rand() ;

	$admin = AdminUsers_get_UserInfo( $dbh, $session_admin[$sid]['admin_id'], $session_admin[$sid]['aspID'] ) ;
	$can_initiate = AdminUsers_get_CanUserInitiate( $dbh, $session_admin[$sid]['admin_id'] ) ;
	if ( !$can_initiate || !$INITIATE )
		$action = "close_console" ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
	
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "footprints" )
	{
		$do_pull = 0 ;
		$footprints_today = ServiceFootprint_get_DayFootprint( $dbh, $ip, $begin, $end, 0, $x, 0, 0 ) ;
		$footprints_beforetoday = ServiceFootprint_get_BeforeDayFootprint( $dbh, $ip, $begin, 20, $x ) ;
	}
	else if ( $action == "chat" )
	{
		$do_pull = 0 ;
		$select_dept = "" ;
		$admin_departments = AdminUsers_get_UserDepartments( $dbh, $session_admin[$sid]['admin_id'] ) ;
		$chatrequestinfo = ServiceChat_get_IPChatRequestInfo( $dbh, $x, $ip ) ;

		for ( $c = 0; $c < count( $admin_departments ); ++$c )
		{
			$department = $admin_departments[$c] ;
			$select_dept .= "<option value=\"$department[deptID]\">$department[name]</option>" ;
		}
		$footprint = ServiceFootprintUnique_get_IPFootprintInfo( $dbh, $ip, $x ) ;
		$duration = $footprint['updated'] - $footprint['created'] ;
		if ( $duration > 60 )
			$duration = floor( $duration/60 ) . " min" ;
		else
			$duration = $duration . " sec" ;
		
		$idle = time() - $FOOTPRINT_IDLE ;
		
		if ( $chatrequestinfo['requestID'] )
			$status = "<font color=\"#FF0000\">Visitor is currently on a support call.</font>" ;
		else if ( $footprint['updated'] > $idle )
			$status = "<a href=\"JavaScript:void(0)\" OnClick=\"open_chat( '$footprint[ip]', '$footprint[url]' )\">Click HERE to initiate chat now!</a>" ;
		else
			$status = "<font color=\"#FF0000\">Visitor has left site or is unavailable.</font>" ;
	}
	else if ( $action == "close_console" )
	{
		$do_pull = 0 ;
		$HTTP_SESSION_VARS['session_admin'][$sid]['traffic_monitor'] = 0 ;
	}
	else if ( $action == "open_console" )
	{
		$HTTP_SESSION_VARS['session_admin'][$sid]['traffic_monitor'] = 1 ;
	}
?>
<html>
<head>
<title> Operator [ visitor traffic monitor ] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="../../css/base.css">

<script language="JavaScript">
<!--
	function start_pulling()
	{
		<? if ( !$HTTP_SESSION_VARS['session_admin'][$sid]['traffic_monitor'] ): ?>
			parent.window.control_pull_traffic( "stop" ) ;
		<? elseif ( !$do_pull ): ?>
			parent.window.control_pull_traffic( "stop" ) ;
		<? else: ?>
			parent.window.control_pull_traffic( "start" ) ;
		<? endif ; ?>
	}

	function open_chat( ip, page )
	{
		index = document.form.deptid.selectedIndex ;
		deptid = document.form.deptid[index].value ;
		message = escape( document.form.message.value ) ;
		page = escape( page ) ;
		url = "../../request.php?action=initiate&ip="+ip+"&sid=<? echo $sid ?>&userid=<? echo $session_admin[$sid]['admin_id'] ?>&l=<? echo $l ?>&x=<? echo $x ?>&deptid="+deptid+"&question="+message+"&page="+page ;
		winname = parent.window.dounique() ;
		newwin = window.open(url, winname, "scrollbars=no,menubar=no,resizable=0,location=no,width=450,height=560") ;
		setTimeout("location.href = 'admin_puller.php?action=chat&start=1&rand=<? echo $rand ?>&sid=<? echo $sid ?>&l=<? echo $l ?>&x=<? echo $x ?>&ip=<? echo $ip ?>'",15000) ;
		newwin.focus() ;
	}
//-->
</script>

</head>
<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" marginheight="0" marginwidth="0" topmargin="0" leftmargin="2" OnLoad="start_pulling()">


<? if ( $action == "footprints" ): ?>
<span class="smalltxt"><b>Visitor Traffic Monitor</b> - [ <a href="admin_puller.php?counter=0&sid=<? echo $sid ?>&action=close_console&start=1&l=<? echo $l ?>&x=<? echo $x ?>">close traffic monitor</a> ]</span>
<br>
<span class="smalltxt">
<? echo $ip ?> -&gt; [ <a href="admin_puller.php?rand=<? echo $rand ?>&sid=<? echo $sid ?>&l=<? echo $l ?>&x=<? echo $x ?>&start=1">main</a> ]
[ <a href="admin_puller.php?action=footprints&rand=<? echo $rand ?>&sid=<? echo $sid ?>&l=<? echo $l ?>&x=<? echo $x ?>&ip=<? echo $ip ?>">view footprints</a> ]
[ <a href="admin_puller.php?action=chat&rand=<? echo $rand ?>&sid=<? echo $sid ?>&l=<? echo $l ?>&x=<? echo $x ?>&ip=<? echo $ip ?>">initiate chat</a> ]
<p>
<!-- display only if visitor footprints is enabled -->
Pages visited today - based on ip -<br>
<table cellspacing=1 cellpadding=1 border=0>
<?
	for ( $c = 0; $c < count( $footprints_today );++$c )
	{
		$footprint = $footprints_today[$c] ;
		print "<tr><td><span class=\"smalltxt\">$footprint[total]</td><td><span class=\"smalltxt\"><a href=\"$footprint[url]?phplive_notally\" target=\"new\">$footprint[url]</a></td></tr>\n" ;
	}
?>
</table>
<br>
Pages visited before today (top 20 pages) - based on ip -<br>
<table cellspacing=1 cellpadding=1 border=0>
<?
	for ( $c = 0; $c < count( $footprints_beforetoday );++$c )
	{
		$footprint = $footprints_beforetoday[$c] ;
		print "<tr><td><span class=\"smalltxt\">$footprint[total]</td><td><span class=\"smalltxt\"><a href=\"$footprint[url]?phplive_notally\" target=\"new\">$footprint[url]</a></td></tr>\n" ;
	}
?>
</table>
<!-- end visitor footprings -->













<?
	elseif ( $action == "chat" ):
	$start_date = mktime( 0,0,0,date("m"),date("j"),date("Y") ) ;
	$end_date = mktime( 23,59,59,date("m"),date("j"),date("Y") ) ;
	$total_initiated = ServiceChat_get_TotalInitiatedOnDate( $dbh, $x, $ip, $start_date, $end_date ) ;
?>
<span class="smalltxt"><b>Visitor Traffic Monitor</b> - [ <a href="admin_puller.php?counter=0&sid=<? echo $sid ?>&action=close_console&start=1&l=<? echo $l ?>&x=<? echo $x ?>">close traffic monitor</a> ]</span>
<br>
<span class="smalltxt">
<? echo $ip ?> -&gt; [ <a href="admin_puller.php?rand=<? echo $rand ?>&sid=<? echo $sid ?>&l=<? echo $l ?>&x=<? echo $x ?>&start=1">main</a> ]
[ <a href="admin_puller.php?action=footprints&rand=<? echo $rand ?>&sid=<? echo $sid ?>&l=<? echo $l ?>&x=<? echo $x ?>&ip=<? echo $ip ?>">view footprints</a> ]
[ <a href="admin_puller.php?action=chat&rand=<? echo $rand ?>&sid=<? echo $sid ?>&l=<? echo $l ?>&x=<? echo $x ?>&ip=<? echo $ip ?>">initiate chat</a> ]
<p>
<form name="form" method="GET" action="admin_puller.php" OnSubmit="alert('To initiate chat, click on the link below.'); return false">
<input type="hidden" name="action" value="chat">
<input type="hidden" name="rand" value="<? echo $rand ?>">
<input type="hidden" name="sid" value="<? echo $sid ?>">
<input type="hidden" name="l" value="<? echo $l ?>">
<input type="hidden" name="x" value="<? echo $x ?>">
<input type="hidden" name="ip" value="<? echo $ip ?>">
<table cellspacing=1 cellpadding=1 border=0>
<tr>
	<td bgColor="#8080C0"><font color="#FFFFFF"><span class="smalltxt">Currently on page</td>
	<td><span class="smalltxt"><? echo $footprint['url'] ?></td>
</tr>
<tr>
	<td bgColor="#8080C0"><font color="#FFFFFF"><span class="smalltxt">Duration on site</td>
	<td><span class="smalltxt"><? echo $duration ?></td>
</tr>
<tr>
	<td bgColor="#8080C0"><font color="#FFFFFF"><span class="smalltxt">Initiated Today</td>
	<td><span class="smalltxt"><? echo $total_initiated ?> time(s)</td>
</tr>
<tr>
	<td bgColor="#8080C0"><font color="#FFFFFF"><span class="smalltxt">Choose department</td>
	<td><span class="smalltxt"><font size=2><select name="deptid" class="select"><? echo $select_dept ?></select></td>
</tr>
<tr>
	<td bgColor="#8080C0"><font color="#FFFFFF"><span class="smalltxt">Opening question</td>
	<td><span class="smalltxt"><font size=2><input type="text" name="message" size="<? echo $text_width ?>" class="input" maxlength=150 value="Is there something I can help you with today?"></td>
</tr>
<tr>
	<td><span class="smalltxt">&nbsp;</td>
	<td><span class="bigtxt"><b><? echo $status ?></b></td>
</tr>
</table>
</form>












<? elseif ( !$HTTP_SESSION_VARS['session_admin'][$sid]['traffic_monitor'] ): ?>
<? if ( $can_initiate && $INITIATE ): ?>
	<span class="smalltxt">&nbsp; [ <a href="admin_puller.php?action=open_console&sid=<? echo $sid ?>&l=<? echo $l ?>&x=<? echo $x ?>">open traffic monitor</a> ]</span>
<? else: ?>
	&nbsp;
<? endif ; ?>










<?
	else:
	$footprints = ServiceFootprintUnique_get_ActiveFootprints( $dbh, $x ) ;
	// embed sound if new request
	if ( !$start && ( count( $footprints ) > 0 ) )
		print "<EMBED src=\"$BASE_URL/sounds/doorbell.wav\" width=0 height=0 autostart=true loop=false>" ;
?>
<span class="smalltxt"><b>Visitor Traffic Monitor</b> - [ <a href="admin_puller.php?counter=0&sid=<? echo $sid ?>&action=close_console&start=1&l=<? echo $l ?>&x=<? echo $x ?>">close traffic monitor</a> ] - <a href="admin_puller.php?rand=<? echo $rand ?>&sid=<? echo $sid ?>&l=<? echo $l ?>&x=<? echo $x ?>&start=1">reload list</a></span>
<span class="smalltxt">
<br>
<table cellspacing=1 cellpadding=0 border=0 width="100%">
<?
	for ( $c = 0; $c < count( $footprints ); ++$c )
	{
		$footprint = $footprints[$c] ;

		$duration = $footprint['updated'] - $footprint['created'] ;
		if ( $duration > 60 )
			$duration = floor( $duration/60 ) . " min" ;
		else
			$duration = $duration . " sec" ;

		$bgcolor = "#EEEEF7" ;
		if ( $c % 2 )
			$bgcolor = "#E6E6F2" ;

		//$hostname = gethostbyaddr( $footprint['ip'] ) ;
		$referinfo = ServiceRefer_get_ReferInfo( $dbh, $x, $footprint['ip'] ) ;
		$refer_url = "<i>not available</i>" ;
		if ( isset( $referinfo['refer_url'] ) )
			$refer_url = $referinfo['refer_url'] ;

		print "
			<tr bgColor=\"$bgcolor\">
				<td width=\"100%\"><span class=\"smalltxt\">
					<table cellspacing=1 cellpadding=2 border=0>
					<tr>
						<td bgColor=\"#8080C0\"><font color=\"#FFFFFF\"><span class=\"smalltxt\">On Page</td>
						<td><span class=\"smalltxt\">$footprint[url]</td>
					</tr>
					<tr>
						<td bgColor=\"#8080C0\"><font color=\"#FFFFFF\"><span class=\"smalltxt\">IP</td>
						<td><span class=\"smalltxt\">$footprint[ip]</td>
					</tr>
					<tr>
						<td bgColor=\"#8080C0\"><font color=\"#FFFFFF\"><span class=\"smalltxt\">Refer URL</td>
						<td><span class=\"smalltxt\">$refer_url</td>
					</tr>
					<tr>
						<td bgColor=\"#8080C0\"><font color=\"#FFFFFF\"><span class=\"smalltxt\">Options</td>
						<td><span class=\"smalltxt\">[ <a href=\"admin_puller.php?action=footprints&rand=$rand&sid=$sid&l=$l&x=$x&ip=$footprint[ip]\">view footprints</a> ]
						[ <a href=\"admin_puller.php?action=chat&rand=$rand&sid=$sid&l=$l&x=$x&ip=$footprint[ip]\">initiate chat</a> ]
						</td>
					</tr>
					</table>
				</td>
				<td nowrap><span class=\"smalltxt\">$duration</td>
			</tr>
			" ;
	}
?>
</table>

<? endif ; ?>

<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>