<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$session_chat = $HTTP_SESSION_VARS['session_chat'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : "" ;
	$action = ( isset( $HTTP_GET_VARS['action'] ) ) ? $HTTP_GET_VARS['action'] : "" ;
	$requestid = ( isset( $HTTP_GET_VARS['requestid'] ) ) ? $HTTP_GET_VARS['requestid'] : "" ;

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
	include_once("./API/Chat/get.php") ;
	include_once("./API/Footprint/get.php") ;
	include_once("./API/Logs/get.php") ;
?>
<?
	// initialize
	$m = date( "m",mktime() ) ;
	$y = date( "Y",mktime() ) ;
	$d = date( "j",mktime() ) ;

	// the timespan to get the stats
	$begin = mktime( 0,0,0,$m,$d,$y ) ;
	$end = mktime( 23,59,59,$m,$d,$y ) ;
	$requestinfo = ServiceChat_get_ChatRequestInfo( $dbh, $requestid ) ;
	$total_request = ServiceLogs_get_TotalIpRequests( $dbh, $requestinfo['ip_address'] ) ;
?>
<?
	// functions
?>
<?
	// conditions
?>
<html>
<head>
<title> Chat [admin view footprints] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="./css/base.css">

<script language="JavaScript">
<!--
//-->
</script>

</head>
<body bgColor="<? echo $CHAT_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
<center>
<br>
<span class="smalltxt">

<?
	if ( ( $action == "footprints" ) && $VISITOR_FOOTPRINT ):
	$footprints_today = ServiceFootprint_get_DayFootprint( $dbh, $requestinfo['ip_address'], $begin, $end, 0, $session_chat[$sid]['aspID'], 0, 0 ) ;
	$footprints_beforetoday = ServiceFootprint_get_BeforeDayFootprint( $dbh, $requestinfo['ip_address'], $begin, 15, $session_chat[$sid]['aspID'] ) ;
?>
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
Pages visited before today (top 15 pages last 3 months) - based on ip -<br>
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






<? else: ?>
Clicked from: <a href="<? echo $requestinfo['url'] ?>" target="new"><? echo $requestinfo['url'] ?></a><br>
Visitor's gathered information.<br>
<table cellspacing=1 cellpadding=1 border=0>
<tr>
	<td align="right"><span class="smalltxt"><u>Live! Request</u></td>
	<td><span class="smalltxt"><? echo $total_request ?> time(s)</td>
</tr>
<tr>
	<td align="right"><span class="smalltxt"><u>Browser and OS</u></td>
	<td><span class="smalltxt"><? echo $requestinfo['browser_type'] ?></td>
</tr>
<tr>
	<td align="right"><span class="smalltxt"><u>IP Address</u></td>
	<td><span class="smalltxt"><? echo $requestinfo['ip_address'] ?></td>
</tr>
<tr>
	<td align="right"><span class="smalltxt"><u>Host Name</u></td>
	<td><span class="smalltxt"><? echo gethostbyaddr( $requestinfo['ip_address'] ) ?></td>
</tr>
<tr>
	<td align="right"><span class="smalltxt"><u>Screen Resolution</u></td>
	<td><span class="smalltxt"><? echo $requestinfo['display_resolution'] ?></td>
</tr>
<tr>
	<td align="right"<span class="smalltxt"><u>Visitor's Time</u></td>
	<td><span class="smalltxt"><? echo $requestinfo['visitor_time'] ?></td>
</tr>
</table>
<br>
<? endif ; ?>

<br>
<br>
</center>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>
<?
	mysql_close( $dbh['con'] ) ;
?>