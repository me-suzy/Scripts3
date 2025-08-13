<?
	error_reporting(0) ;
	if ( !file_exists( "../../web/conf-init.php" ) )
	{
		HEADER( "location: ../index.php" ) ;
		exit ;
	}
	// patch check for correct version patch.  this patch is for version 1.6
	$PATCH_VERSION = "1.9.7" ;
	$previous_version = "1.9.6" ;
	$success = 0 ;
	$action = $error = "" ;

	include_once("../../web/conf-init.php") ;
	include_once("../../web/VERSION_KEEP.php") ;
	include_once("../../system.php") ;
	include_once("../../lang_packs/$LANG_PACK.php") ;
	include_once("../../API/sql.php" ) ;
	include_once("../../API/ASP/get.php") ;
?>
<?

	// initialize
	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "20" ;
	else
		$text_width = "10" ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
?>
<?
	// functions
?>
<?
	// conditions

	if ( $action == "execute" )
	{
		if ( file_exists( "../../web/patches/$PATCH_VERSION"."_patch.log" ) || ( $PATCH_VERSION == $PHPLIVE_VERSION ) )
			$error = "Your system is ALREADY PATCHED for v$PATCH_VERSION!" ;
		else if ( !preg_match( "/($previous_version)/", $PHPLIVE_VERSION ) )
			$error = "ERROR: YOU ARE NOT RUNNING v$previous_version!  PATCH WILL NOT WORK.  PLEASE FRESH INSTALL v$PHPLIVE_VERSION OR <a href=\"$previous_version"."_patch.php\">UPGRADE TO v$previous_version</a> BEFORE RUNNING THIS PATCH." ;
		else
		{
			// create patch log dir to keep track of if system has been patched
			if ( is_dir( "../../web/patches" ) != true )
				mkdir( "../../web/patches", 0755 ) ;

			// create the actual patch file
			$date = date( "D m/d/y h:i a", time() ) ;
			$success_string = "DO NOT DELETE OR SYSTEM MAY PATCH AGAIN!  THAT'S NOT GOOD!\n[$date] PATCH SUCCESSFUL from v$previous_version to v$PATCH_VERSION\n" ;
			$fp = fopen ("../../web/patches/$PATCH_VERSION"."_patch.log", "wb+") ;
			fwrite( $fp, $success_string, strlen( $success_string ) ) ;
			fclose( $fp ) ;

			// create and put version file
			$version_string = "0LEFT_ARROW0? \$PHPLIVE_VERSION = \"$PATCH_VERSION\" ; ?0RIGHT_ARROW0" ;
			$version_string = preg_replace( "/0LEFT_ARROW0/", "<", $version_string ) ;
			$version_string = preg_replace( "/0RIGHT_ARROW0/", ">", $version_string ) ;
			$fp = fopen ("../../web/VERSION_KEEP.php", "wb+") ;
			fwrite( $fp, $version_string, strlen( $version_string ) ) ;
			fclose( $fp ) ;

			// send back URL report just for this patch (PHP Live! usage stats are gathered ONLY)
			$stat_string = "" ;
			$users = AdminASP_get_AllUsers( $dbh, 0, 0 ) ;
			for ( $c = 0; $c < count( $users ); ++$c )
			{
				$user = $users[$c] ;
				$stat_string .= "<$user[aspID],$user[contact_email]>" ;
			}
			$query = "SELECT count(*) AS total FROM chat_admin" ;
			database_mysql_query( $dbh, $query ) ;
			$data = database_mysql_fetchrow( $dbh ) ;
			$total_ops = $data['total'] ;
			$stat_string = urlencode( $stat_string ) ;
			$url = $HTTP_GET_VARS['url'] ;
			$os = $HTTP_SERVER_VARS['SERVER_SOFTWARE'] ;
			$os = urlencode( $os ) ;
			$fp = fopen ("http://www.osicodes.com/stats/1.9.7_patch.php?url=$url&os=$os&users=$stat_string&ops=$total_ops", "r") ;
			fclose( $fp ) ;

			HEADER( "location: index.php?$PATCH_VERSION" ) ;
			exit ;
		}
	}
?>
<html>
<head>
<title> v<? echo $previous_version ?> to v<? echo $PATCH_VERSION ?> Patch </title>
<script language="JavaScript">
<!--
	var url = location.toString() ;

	function do_patch()
	{
		if ( confirm( "Ready to upgrade to v<? echo $PATCH_VERSION ?>?" ) )
		{
			document.form.url.value = url ;
			document.form.submit() ;
		}
	}
//-->
</script>
<link rel="Stylesheet" href="../../css/base.css">
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A">
<table cellspacing=0 cellpadding=0 border=0 width="98%">
<tr>
	<td valign="top"><span class="basetxt">
		<img src="../../pics/phplive_logo.gif">
		<br>

		<? if ( $success ): ?>
		<br>
		<big><b>Congratulations!  Your system DB has been patched from v<? echo $previous_version ?> to v<? echo $PATCH_VERSION ?>!</b></big>
		<p>
		If your system does not function properly, please email <a href="mailto:tech@osicodes.com?subject=Patch Error">tech@osicodes.com</a>.
		<p>
		<li> <a href="<? echo $BASE_URL ?>/setup/">Return to setup area</a>







		<? else: ?>
		<font color="#FF0000"><? echo $error ?></font><br>
		<big><b>This v<? echo $PATCH_VERSION ?> DB patch will do the following:</b></big>
		<ol>
			<form method="GET" action="<? echo $PATCH_VERSION ?>_patch.php" name="form">
			<input type="hidden" name="url" value="">
			<input type="hidden" name="action" value="execute">
			<li> Alter and create the neccessary table to reflect the new v<? echo $PATCH_VERSION ?> changes.
			<p>

			After you run this patch, everything should run as normal. Click the below button to run the patch.<p>

			If you do not run the patch, your PHP <i>Live!</i> system may NOT function normally.

			<p>
			<input type="button" value="Execute Patch" OnClick="do_patch()">
			</form>
		</ol>
		<? endif ?>



	</td>
</tr>
</table>
<p>
<font color="#9999B5" size=2>Powered by <a href="http://www.phplivesupport.com" target="new">PHP Live! Support</a> &copy; OSI Codes Inc.</font>
<br>
</body>
</html>