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
	include_once("../API/Refer/get.php") ;
?>
<?

	// initialize
	$m = $d = $y = $error = "" ;
	$success = 0 ;

	// get variables
	if ( isset( $HTTP_GET_VARS['m'] ) ) { $m = $HTTP_GET_VARS['m'] ; }
	if ( isset( $HTTP_GET_VARS['d'] ) ) { $d = $HTTP_GET_VARS['d'] ; }
	if ( isset( $HTTP_GET_VARS['y'] ) ) { $y = $HTTP_GET_VARS['y'] ; }

	if ( !$m || !$y || !$d )
	{
		$m = date( "m",mktime() ) ;
		$y = date( "Y",mktime() ) ;
		$d = date( "j",mktime() ) ;
	}
	$stat_begin = mktime( 0,0,0,date( "m",mktime() ),date( "j",mktime() ),date( "Y",mktime() ) ) ;

	$selected_begin = mktime( 0,0,0,$m,$d,$y ) ;
	$selected_date = date( "D F d, Y", $selected_begin ) ;
	$refers = ServiceRefer_get_ReferOnDate( $dbh, $session_setup['aspID'], $selected_begin, ( $selected_begin + (60*60*24) ) ) ;
?>
<?
	// functions
?>
<?
	// conditions
?>
<html>
<head>
<title> Refer URLs </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
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
		<big><b>10 day Refer URL stats</b></big> - <a href="options.php">back to menu</a>
		<br>
		<font color="#FF0000"><? echo $error ?></font>
		<br>
		<img src="../pics/icons/stats.gif" width="32" height="32" border=0 alt="" align="left"> See where your visitors are coming from with Refer URL stats below.  You can use the data to maximize your ad campaign or to get a better understanding of your visitors.  The system tracks only 10 days of stats.  Please check here regularly or print out each day for your records.
		<p>
	
		<table cellspacing=1 cellpadding=2 border=0 width="100%">
		<tr bgColor="#8080C0">
			<?
				for ( $c = $stat_begin; $c > ( $stat_begin - (60*60*24*10) ); 1 )
				{
					$date = date( "D F d", $c ) ;
					$m = date( "m",$c ) ;
					$y = date( "Y",$c ) ;
					$d = date( "j",$c ) ;
					print "<td><span class=\"smalltxt\"><a href=\"refer.php?m=$m&d=$d&y=$y\"><font color=\"#FFFFFF\">$date</font></a></td>" ;
					$c -= (60*60*24) ;
				}
			?>
		</tr>
		</table>
		<p>
		<big><b>Refer stats for date: <? echo $selected_date ?></big></b> &nbsp; <span class="smalltxt">(max 500 results)</span>
		<br>
		<table cellspacing=1 cellpadding=1 border=0 width="100%">
		<tr bgColor="#8080C0">
			<td><span class="basetxt"><font color="#FFFFF">Refer URL</td>
			<td width="50"><span class="basetxt"><font color="#FFFFF">Total</td>
		</tr>
		<?
			for ( $c = 0; $c < count( $refers ); ++$c )
			{
				$refer = $refers[$c] ;
				$bgcolor = "#EEEEF7" ;
				if ( $c % 2 )
					$bgcolor = "#E6E6F2" ;

				print "
					<tr bgColor=\"$bgcolor\">
						<td><span class=\"basetxt\"><a href=\"$refer[refer_url]\" target=\"new\">$refer[refer_url]</a></td>
						<td><span class=\"basetxt\">$refer[total]</td>
					</tr>
				" ;
			}
		?>
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