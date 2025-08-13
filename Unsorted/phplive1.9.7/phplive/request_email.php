<?
	include_once("./web/conf-init.php") ;
	include_once("./system.php") ;
?>
<html>
<head>
<title>  </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->
<!-- BEGIN PHP Live! Code, copyright 2001 OSI Codes -->
<script language="JavaScript">
<!--
// the reason for using date is to set a unique value so the status
// image is NOT CACHED (Netscape problem).  keep this or bad things could happen
var date = new Date() ;
var unique = date.getTime() ;
var url = '<? echo $HTTP_SERVER_VARS['REMOTE_ADDR'] ?>' ;
var request_url = "<? echo $BASE_URL ?>/request.php?l=<? echo $HTTP_GET_VARS['l'] ?>&x=<? echo $HTTP_GET_VARS['x'] ?>&deptid=<?= isset( $HTTP_GET_VARS['deptid'] ) ? $HTTP_GET_VARS['deptid'] : 0 ?>&page="+url ;
function launch_support()
{
	newwin = window.open( request_url, unique, 'scrollbars=no,menubar=no,resizable=0,location=no,screenX=50,screenY=100,width=450,height=350' ) ;
	newwin.focus() ;
	window.close() ;
}
//-->
</script>
<!-- END PHP Live! Code, copyright 2001 OSI Codes -->
</head>
<body bgColor="#FFFFFF" OnLoad="launch_support()">
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>