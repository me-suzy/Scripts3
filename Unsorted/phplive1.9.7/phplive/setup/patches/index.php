<?
	$current_version = "1.9.7.2" ;
	include( "../../web/VERSION_KEEP.php" ) ;

	$error = "" ;
	$url = 0 ;

	switch ( $PHPLIVE_VERSION )
	{
		case "1.9":
			$url = "1.9.5_patch.php" ;
			break ;
		case "1.9.5":
			$url = "1.9.6_patch.php" ;
			break ;
		case "1.9.6":
			$url = "1.9.7_patch.php" ;
			break ;
		case "1.9.7":
			$url = "1.9.7.1_patch.php" ;
			break ;
		case "1.9.7.1":
			$url = "1.9.7.2_patch.php" ;
			break ;
		case "1.9.7.2":
			$error = "Your PHP Live! system is UP TO DATE.  No more patches available.<p> <a href=\"../index.php\"><li> Click Here to return to you Setup area.</a>" ;
			break ;
		default:
			$error = "Your PHP Live! version is too old.  You MUST do a FRESH install.  Remove this current system and install NEW." ;
	}
?>
<html>
<head>
<title> Upgrading and Patching your PHP Live! system </title>
<script language="JavaScript">
<!--
	if ( '<? echo $url ?>' && ( '<? echo $url ?>' != '0' ) )
		setTimeout("location.href='<? echo $url ?>'",5000) ;
//-->
</script>
<link rel="Stylesheet" href="../../css/base.css">
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A">
<table cellspacing=0 cellpadding=0 border=0 width="98%">
<tr>
	<td valign="top"><span class="basetxt">
		<img src="../../pics/phplive_logo.gif">
		<p>

		<? if ( $url ): ?>
		<big><b>Upgrade Patch Available!  Redirecting you to the correct patch page....</b></big>

		<? else: ?>
		<font color="#FF0000"><big><b><? echo $error ?></b></big></big>

		<? endif ; ?>
	</td>
</tr>
</table>
<p>
<font color="#9999B5" size=2>Powered by <a href="http://www.phplivesupport.com" target="new">PHP Live! Support</a> &copy; OSI Codes Inc.</font>
<br>
</body>
</html>