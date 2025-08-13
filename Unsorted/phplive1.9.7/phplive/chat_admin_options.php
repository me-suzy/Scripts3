<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$session_chat = $HTTP_SESSION_VARS['session_chat'] ;
	$sid = ( isset( $HTTP_GET_VARS['sid'] ) ) ? $HTTP_GET_VARS['sid'] : "" ;
	$requestid = ( isset( $HTTP_GET_VARS['requestid'] ) ) ? $HTTP_GET_VARS['requestid'] : "" ;
	$sessionid = ( isset( $HTTP_GET_VARS['sessionid'] ) ) ? $HTTP_GET_VARS['sessionid'] : "" ;

	if ( !file_exists( "web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php" ) || !file_exists( "web/conf-init.php" ) )
	{
		print "<font color=\"#FF0000\">[Configuration Error: config files not found!] Exiting...</font>" ;
		exit ;
	}
	include_once("./web/conf-init.php") ;	
	include_once("./web/".$session_chat[$sid]['asp_login']."/".$session_chat[$sid]['asp_login']."-conf-init.php") ;
	include_once("./system.php") ;
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
<title> Chat [admin options] </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<link rel="Stylesheet" href="./css/base.css">

<script language="JavaScript">
<!--
//-->
</script>

</head>
<body bgColor="<? echo $CHAT_BACKGROUND ?>" text="<? echo $TEXT_COLOR ?>" link="<? echo $LINK_COLOR ?>" alink="<? echo $ALINK_COLOR ?>" vlink="<? echo $VLINK_COLOR ?>" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
<center>
<span class="smalltxt">
[<a href="chat_admin_vinfo.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>&rand=<? echo $rand ?>" target="admin_main">Visitor Info</a>]

<? if ( $VISITOR_FOOTPRINT ): ?>
[<a href="chat_admin_vinfo.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>&rand=<? echo $rand ?>&action=footprints" target="admin_main">Footprints</a>]
<? else: ?>
[<font color="#AAAAAA">Footprints</font>]
<? endif ; ?>

[<a href="chat_admin_transfer.php?sessionid=<? echo $sessionid ?>&sid=<? echo $sid ?>&requestid=<? echo $requestid ?>&rand=<? echo $rand ?>" target="admin_main">Transfer Call</a>]
</span>
</center>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>