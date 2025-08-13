<?
	/*******************************************************
	* COPYRIGHT OSI CODES - PHP Live!
	*******************************************************/
	session_start() ;
	$action = $error_mesg = "" ;
	$success = 0 ;
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
	include_once("../API/sql.php") ;
	include_once("../API/Footprint/get.php") ;
	include_once("../API/Footprint/put.php") ;
?>
<?
	// get variables
	if ( isset( $HTTP_POST_VARS['action'] ) ) { $action = $HTTP_POST_VARS['action'] ; }
	if ( isset( $HTTP_GET_VARS['action'] ) ) { $action = $HTTP_GET_VARS['action'] ; }

	$month = $HTTP_GET_VARS['month'] ? $HTTP_GET_VARS['month'] : $HTTP_POST_VARS['month'] ;
	$day = $HTTP_GET_VARS['day'] ? $HTTP_GET_VARS['day'] : $HTTP_POST_VARS['day'] ;
	$year = $HTTP_GET_VARS['year'] ? $HTTP_GET_VARS['year'] : $HTTP_POST_VARS['year'] ;

	// initialize
	if ( preg_match( "/MSIE/", $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ) )
		$text_width = "12" ;
	else
		$text_width = "9" ;

	$now = mktime( 0,0,0,date("m"),date("j"),date("Y") ) ;

	$stat_begin = mktime( 0,0,0,$month,$day,$year ) ;
	$stat_end = mktime( 23,59,59,$month,$day,$year ) ;

	if ( !$month || !$day || !$year )
	{
		HEADER( "location: $BASE_URL/setup/options.php" ) ;
		exit ;
	}

	// make sure it does not log current day, because current day is REAL-TIME
	if ( $stat_begin >= $now )
	{
		HEADER( "location: $BASE_URL/setup/options.php?optimized=1" ) ;
		exit ;
	}

	$nextday = mktime( 0,0,0,$month,$day+1,$year ) ;
	$nextday_month = date("m", $nextday ) ;
	$nextday_day = date("j", $nextday ) ;
	$nextday_year = date("Y", $nextday ) ;

	$optimize_day = date( "F j, Y", $stat_begin ) ;
?>
<?
	// functions
?>
<?
	// conditions
?>
<html>
<head>
<title> Optimize Disc Space </title>
<!-- copyright OSI Codes Inc. http://www.osicodes.com [DO NOT DELETE] -->

<script language="JavaScript">
<!--
	function do_alert()
	{
		<? if ( $success ) { print "		alert( 'Success!' ) ;\n" ; } ?>
	}

	function do_reload()
	{
		setTimeout("location.href='optimize.php?month=<? echo $nextday_month ?>&day=<? echo $nextday_day ?>&year=<? echo $nextday_year ?>'",1000) ;
	}
//-->
</script>
<link rel="Stylesheet" href="../css/base.css">
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
		<big><b>Optimize Disc Space</b></big> - <a href="options.php">back to menu</a><p>

		<font color="#FF0000"><? echo $error_mesg ?></font>
		<p>

		<big><b>Optimizing log files (<font color="#110000"><i><? echo $optimize_day ?></i></font>).  Please hold &nbsp;<img src="<? echo $BASE_URL ?>/pics/progress.gif" width="75" height="3" border=0 alt=""></big></b><br>
		<font color="#FF0000">* DO NOT STOP THIS PROCESS!  Please wait until finished...</font>
		<p>
		
		<?
			// do the processing here so that the output of above can be displayed first
			$top_url_visits = ServiceFootprint_get_DayFootprint( $dbh, "", $stat_begin, $stat_end, 25, $session_setup['aspID'], $day, 1 ) ;
			for ( $c = 0; $c < count( $top_url_visits ); ++$c )
			{
				$footprint = $top_url_visits[$c] ;
				ServiceFootprint_put_FootprintURLStat( $dbh, $session_setup['aspID'], $stat_begin, $footprint['url'], $footprint['total'] ) ;
			}

			// put daily page view and unique hits
			$total_page_views = ServiceFootprint_get_TotalDayFootprint( $dbh, $stat_begin, $stat_end, $session_setup['aspID'], 1 ) ;
			$total_unique_visits = ServiceFootprint_get_TotalUniqueDayVisits( $dbh, $stat_begin, $stat_end, $session_setup['aspID'], 1 ) ;
			ServiceFootprint_put_FootprintStat( $dbh, $session_setup['aspID'], $stat_begin, $total_page_views, $total_unique_visits ) ;

			print "Optimizing: $total_unique_visits visits, $total_page_views page views\n" ;

			print "
				<script language=\"JavaScript\">
				<!--
					do_reload() ;
				//-->
				</script>
				" ;
		?>

	</td>
</tr>
</table>
<p>
<? include_once( "./footer.php" ) ; ?>
<br>
<!-- copyright OSI Codes, http://www.osicodes.com [DO NOT DELETE] -->
</body>
</html>