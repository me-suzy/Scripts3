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
?>
<?

	// initialize
	$action = "" ;
	$success = 0 ;
	$error_mesg = "" ;

	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "12" ;
	else
		$text_width = "9" ;

	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }
?>
<?
	// functions
?>
<?
	// conditions
	if ( $action == "exclude_ip" )
	{
		$action = "footprints" ;

		$ip_notrack_string = $IPNOTRACK ;
		$new_ip = $HTTP_POST_VARS['ip1'].".".$HTTP_POST_VARS['ip2'].".".$HTTP_POST_VARS['ip3'].".".$HTTP_POST_VARS['ip4']." " ;

		// make sure it's not already in the list
		if ( !preg_match( "/$new_ip/", $ip_notrack_string ) )
			$ip_notrack_string .= $new_ip ;

		$conf_string = "0LEFT_ARROW0?
			\$LOGO = '$LOGO' ;
			\$COMPANY_NAME = '$COMPANY_NAME' ;
			\$SUPPORT_LOGO_ONLINE = '$SUPPORT_LOGO_ONLINE' ;
			\$SUPPORT_LOGO_OFFLINE = '$SUPPORT_LOGO_OFFLINE' ;
			\$VISITOR_FOOTPRINT = '$VISITOR_FOOTPRINT' ;
			\$TEXT_COLOR = '$TEXT_COLOR' ;
			\$LINK_COLOR = '$LINK_COLOR' ;
			\$ALINK_COLOR = '$ALINK_COLOR' ;
			\$VLINK_COLOR = '$VLINK_COLOR' ;
			\$CLIENT_COLOR = '$CLIENT_COLOR' ;
			\$ADMIN_COLOR = '$ADMIN_COLOR' ;
			\$CHAT_BACKGROUND = '$CHAT_BACKGROUND' ;
			\$CHAT_REQUEST_BACKGROUND = '$CHAT_REQUEST_BACKGROUND' ;
			\$CHAT_BOX_BACKGROUND = '$CHAT_BOX_BACKGROUND' ;
			\$CHAT_BOX_TEXT = '$CHAT_BOX_TEXT' ;
			\$POLL_TIME = '$POLL_TIME' ;
			\$INITIATE = '$INITIATE' ;
			\$IPNOTRACK = '$ip_notrack_string' ;
			\$LANG_PACK = '$LANG_PACK' ;?0RIGHT_ARROW0" ;

		$conf_string = preg_replace( "/0LEFT_ARROW0/", "<", $conf_string ) ;
		$conf_string = preg_replace( "/0RIGHT_ARROW0/", ">", $conf_string ) ;
		$fp = fopen ("../web/$session_setup[login]/$session_setup[login]-conf-init.php", "wb+") ;
		fwrite( $fp, $conf_string, strlen( $conf_string ) ) ;
		fclose( $fp ) ;

		$IPNOTRACK = $ip_notrack_string ;
	}
	else if ( $action == "remove_excluded_ip" )
	{
		$action = "footprints" ;

		$ip_notrack_string = $IPNOTRACK ;
		$ip_notrack_string = preg_replace( "/$HTTP_POST_VARS[excluded_ips] /", "", $ip_notrack_string ) ;

		$conf_string = "0LEFT_ARROW0?
			\$LOGO = '$LOGO' ;
			\$COMPANY_NAME = '$COMPANY_NAME' ;
			\$SUPPORT_LOGO_ONLINE = '$SUPPORT_LOGO_ONLINE' ;
			\$SUPPORT_LOGO_OFFLINE = '$SUPPORT_LOGO_OFFLINE' ;
			\$VISITOR_FOOTPRINT = '$VISITOR_FOOTPRINT' ;
			\$TEXT_COLOR = '$TEXT_COLOR' ;
			\$LINK_COLOR = '$LINK_COLOR' ;
			\$ALINK_COLOR = '$ALINK_COLOR' ;
			\$VLINK_COLOR = '$VLINK_COLOR' ;
			\$CLIENT_COLOR = '$CLIENT_COLOR' ;
			\$ADMIN_COLOR = '$ADMIN_COLOR' ;
			\$CHAT_BACKGROUND = '$CHAT_BACKGROUND' ;
			\$CHAT_REQUEST_BACKGROUND = '$CHAT_REQUEST_BACKGROUND' ;
			\$CHAT_BOX_BACKGROUND = '$CHAT_BOX_BACKGROUND' ;
			\$CHAT_BOX_TEXT = '$CHAT_BOX_TEXT' ;
			\$POLL_TIME = '$POLL_TIME' ;
			\$INITIATE = '$INITIATE' ;
			\$IPNOTRACK = '$ip_notrack_string' ;
			\$LANG_PACK = '$LANG_PACK' ;?0RIGHT_ARROW0" ;

		$conf_string = preg_replace( "/0LEFT_ARROW0/", "<", $conf_string ) ;
		$conf_string = preg_replace( "/0RIGHT_ARROW0/", ">", $conf_string ) ;
		$fp = fopen ("../web/$session_setup[login]/$session_setup[login]-conf-init.php", "wb+") ;
		fwrite( $fp, $conf_string, strlen( $conf_string ) ) ;
		fclose( $fp ) ;

		$IPNOTRACK = $ip_notrack_string ;
	}
	else if ( $action == "update_polling" )
	{
		$action = "polling" ;

		$conf_string = "0LEFT_ARROW0?
			\$LOGO = '$LOGO' ;
			\$COMPANY_NAME = '$COMPANY_NAME' ;
			\$SUPPORT_LOGO_ONLINE = '$SUPPORT_LOGO_ONLINE' ;
			\$SUPPORT_LOGO_OFFLINE = '$SUPPORT_LOGO_OFFLINE' ;
			\$VISITOR_FOOTPRINT = '$VISITOR_FOOTPRINT' ;
			\$TEXT_COLOR = '$TEXT_COLOR' ;
			\$LINK_COLOR = '$LINK_COLOR' ;
			\$ALINK_COLOR = '$ALINK_COLOR' ;
			\$VLINK_COLOR = '$VLINK_COLOR' ;
			\$CLIENT_COLOR = '$CLIENT_COLOR' ;
			\$ADMIN_COLOR = '$ADMIN_COLOR' ;
			\$CHAT_BACKGROUND = '$CHAT_BACKGROUND' ;
			\$CHAT_REQUEST_BACKGROUND = '$CHAT_REQUEST_BACKGROUND' ;
			\$CHAT_BOX_BACKGROUND = '$CHAT_BOX_BACKGROUND' ;
			\$CHAT_BOX_TEXT = '$CHAT_BOX_TEXT' ;
			\$POLL_TIME = '$HTTP_POST_VARS[polltime]' ;
			\$INITIATE = '$INITIATE' ;
			\$IPNOTRACK = '$IPNOTRACK' ;
			\$LANG_PACK = '$LANG_PACK' ;?0RIGHT_ARROW0" ;

		$conf_string = preg_replace( "/0LEFT_ARROW0/", "<", $conf_string ) ;
		$conf_string = preg_replace( "/0RIGHT_ARROW0/", ">", $conf_string ) ;
		$fp = fopen ("../web/$session_setup[login]/$session_setup[login]-conf-init.php", "wb+") ;
		fwrite( $fp, $conf_string, strlen( $conf_string ) ) ;
		fclose( $fp ) ;

		$POLL_TIME = $HTTP_POST_VARS['polltime'] ;
		$success = 1 ;
	}
?>
<html>
<head>
<title> Preferences </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	function add_ip()
	{
		if ( ( document.ip.ip1.value == "" ) || ( document.ip.ip2.value == "" )
			|| ( document.ip.ip3.value == "" ) || ( document.ip.ip4.value == "" ) )
			alert( "IP is Invalid." ) ;
		else if ( ( document.ip.ip1.value > 255 ) || ( document.ip.ip2.value > 255 )
			|| ( document.ip.ip3.value > 255 ) || ( document.ip.ip4.value > 255 ) )
			alert( "Each IP value cannot be greater then 255." ) ;
		else
		{
			if ( confirm( "Don't track page view data and footprints for this IP?" ) )
				document.ip.submit() ;
		}
	}

	function do_remove_ip( index )
	{
		if ( index < 0 )
			alert( "Please select an IP to remove from list." ) ;
		else
		{
			if ( confirm( "Remove this IP from exclude list?" ) )
				document.ip_excluded.submit() ;
		}
	}

	function update_tracking()
	{
		if ( confirm( "Are you sure?" ) )
			document.tracking.submit() ;
	}

	function update_polling()
	{
		if ( document.polling.polltime.value < 10 )
			alert( "Must be at LEAST 10 seconds or more." ) ;
		else
		{
			if ( confirm( "Are you sure?" ) )
				document.polling.submit() ;
		}
	}

	function do_alert()
	{
		<? if ( $success ) { print "		alert( 'Success!' ) ;\n" ; } ?>
	}
//-->
</script>
<link rel="Stylesheet" href="../css/base.css">
<script language="JavaScript" src="../js/global.js"></script>
</head>

<body bgColor="#FFFFFF" text="#000000" link="#35356A" vlink="#35356A" alink="#35356A" OnLoad="do_alert()">
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
		<big><b>Preferences</b></big> - <a href="options.php">back to menu</a><p>

		<font color="#FF0000"><? echo $error_mesg ?></font>

		<? 
			if ( $action == "footprints" ):
			if ( $VISITOR_FOOTPRINT == 0 )
				$checked = "checked" ;
		?>
		<img src="../pics/icons/gear.gif" width="32" height="32" border=0 alt="" align="left"> To avoid misleading visitor page view and footprint tracking stats, you can <b>EXCLUDE IPs</b> from the tracking system.  All IPs that are EXCLUDED WILL NOT be tracked for the total page views and visitor footprint data.<p>
		This is useful when you are developing your site and need to refresh your pages quite often, and DON'T want to track each test visit.  Also quite useful so your internal company visits are not tracked.<p>
		NOTE: excluded IPs do not get stored to the database for footprint and page view stat reporting, but they will still show on the operator traffic monitor.
		<p>
		<font color="#660000">Your current IP is: <b><? echo $HTTP_SERVER_VARS['REMOTE_ADDR'] ?></b></font>
		<p>
		<table cellspacing=1 cellpadding=1 border=0>
		<tr>
			<form method="POST" action="prefs.php" name="ip">
			<input type="hidden" name="action" value="exclude_ip">
			<td valign="top"><span class="basetxt">Exclude IP</td>

			<td valign="top"><span class="basetxt">
				<font size=2><input type="text" name="ip1" size=3 maxlength=3 class="input" onKeyPress="return numbersonly(event)">.<input type="text" name="ip2" size=3 maxlength=3 class="input" onKeyPress="return numbersonly(event)">.<input type="text" name="ip3" size=3 maxlength=3 class="input" onKeyPress="return numbersonly(event)">.<input type="text" name="ip4" size=3 maxlength=3 class="input" onKeyPress="return numbersonly(event)">
				<br>
				<a href="JavaScript:add_ip()"><img src="../pics/buttons/submit.gif" border=0></a>
			</td>
			</form>

			<form method="POST" action="prefs.php" name="ip_excluded">
			<input type="hidden" name="action" value="remove_excluded_ip">
			<td valign="top"><span class="basetxt"><font size=2>
				<select name="excluded_ips" size=5 class="select" style="width:200;font-size:12px" width="200">
				<?
					$ips = explode( " ", $IPNOTRACK ) ;
					for( $c = 0; $c < count( $ips ); ++$c )
					{
						if ( $ips[$c] )
							print "<option value=\"$ips[$c]\">$ips[$c]</option>" ;
					}
				?>
				</select>
				<br>
				<span class="smalltxt">[<a href="JavaScript:do_remove_ip(document.ip_excluded.excluded_ips.selectedIndex)">remove SELECTED ip from list</a>]</span>
			</td>
			</form>
		</tr>
		</table>






		
		<? elseif ( $action == "polling" ): ?>
		<form method="POST" action="prefs.php" name="polling">
		<input type="hidden" name="action" value="update_polling">
		<b>Set your request polling time.</b><br>
		<img src="../pics/icons/gear.gif" width="32" height="32" border=0 alt="" align="left"> When a visitor makes a request, the request will be sent to the least active online support person of that department.  But if the support person does not answer the call within the specified time (below), the request will then automatically be <i>polled</i> to the next online support person of that department.  If the call is not taken from ANY of the online support person, the call will be directed to the "leave a message" form.
		<p>
		<font color="#646464">Must be at LEAST 10 seconds or more, 30 is recommended (keep in mind, it takes time to read the question from the visitor).</font><br>
		<input type="text" name="polltime" size=3 maxlength=3 class="input" onKeyPress="return numbersonly(event)" value="<?= ( $POLL_TIME ) ? $POLL_TIME : "30" ?>"> seconds
		<p>
		<a href="JavaScript:update_polling()"><img src="../pics/buttons/submit.gif" border=0></a>
		</form>


		<? endif ;?>

	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>