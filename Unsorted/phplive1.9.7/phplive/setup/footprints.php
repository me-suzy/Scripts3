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
	include_once("../API/Logs/get.php") ;
	include_once("../API/Footprint/get.php") ;
?>
<?

	// initialize
	// initialize
	$action = "" ;
	$m = $y = $d = "" ;
	if ( isset( $HTTP_GET_VARS['m'] ) ) { $m = $HTTP_GET_VARS['m'] ; }
	if ( isset( $HTTP_GET_VARS['d'] ) ) { $d = $HTTP_GET_VARS['d'] ; }
	if ( isset( $HTTP_GET_VARS['y'] ) ) { $y = $HTTP_GET_VARS['y'] ; }

	if ((!$m) || (!$y))
	{
		$m = date( "m",mktime() ) ;
		$y = date( "Y",mktime() ) ;
		$d = date( "j",mktime() ) ;
	}

	if ( !$d )
	{
		// this is for the monthly breakdown
		$stat_begin = mktime( 0,0,0,$m,1,$y ) ;
		$stat_end = mktime( 23,59,59,$m,31,$y ) ;
	}
	else
	{
		$stat_begin = mktime( 0,0,0,$m,$d,$y ) ;
		$stat_end = mktime( 23,59,59,$m,$d,$y ) ;
	}

	$stat_date = date( "D F d, Y", $stat_begin ) ;
	$top_url_visits = ServiceFootprint_get_DayFootprint( $dbh, "", $stat_begin, $stat_end, 15, $session_setup['aspID'], $d, 0 ) ;
	$top_live_requests = ServiceLogs_get_DayMostLiveRequestPage( $dbh, $stat_begin, $stat_end, 15, $session_setup['aspID'] ) ;

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
<title> Traffic and Visitor Footprints </title>
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
	<td valign="top" width="100%"><span class="basetxt">
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
		<b><big>Traffic and Visitor Footprints</big></b> - <a href="options.php">back to menu</a>
		<br>
		<br>
		<img src="../pics/icons/stats.gif" width="32" height="32" border=0 alt="" align="left"> This report shows visitor footprints and page views.  It breaks down the top most visited pages by daily or monthly.  It also reports number of total page views and unique visits.  This report also shows you the most frequent page(s) that the visitor request support from.
		<p>
		<small>Click on the month to see monthly breakdown.</small>
		<!-- begin main table -->
		<table cellspacing=2 cellpadding=2 border=0 width="100%">
		<tr>
			<td valign="top"><span class="basetxt">
				<? Util_Cal_DrawCalendar( $dbh, $m, $y, "footprints.php?", "footprints.php", "footprints.php?action=expand_month" ) ; ?>
			</td>
			<td valign="top" nowrap width="100%"><span class="basetxt">


				<? if ( $action == "expand_month" ): ?>
				<table cellspacing=1 cellpadding=1 border=0 width="100%">
				<tr bgColor="#8080C0">
					<td width="180"><span class="smalltxt"><font color="#FFFFFF">Day</td>
					<td><span class="smalltxt"><font color="#FFFFFF">Page Views</td>
					<td><span class="smalltxt"><font color="#FFFFFF">Unique Visits</td>
				</tr>
				<?
					$grand_total_page_views = $grand_total_unique_visits = 0 ;
					for ( $c = 1; $m == date( "m", mktime( 0,0,0,$m,$c,$y ) ); ++$c )
					{
						$day = date( "F d, Y D", mktime( 0,0,0,$m,$c,$y ) ) ;

						$stat_begin = mktime( 0,0,0,$m,$c,$y ) ;
						$stat_end = mktime( 23,59,59,$m,$c,$y ) ;
						$total_page_views = ServiceFootprint_get_TotalDayFootprint( $dbh, $stat_begin, $stat_end, $session_setup['aspID'], 0 ) ;
						$total_unique_visits = ServiceFootprint_get_TotalUniqueDayVisits( $dbh, $stat_begin, $stat_end, $session_setup['aspID'], 0 ) ;
						$grand_total_page_views += $total_page_views ;
						$grand_total_unique_visits += $total_unique_visits ;

						$bgcolor = "#EEEEF7" ;
						if ( $c % 2 )
							$bgcolor = "#E6E6F2" ;

						print "
							<tr bgColor=\"$bgcolor\">
								<td><span class=\"basetxt\"><a href=\"footprints.php?d=$c&m=$m&y=$y\">$day</td>
								<td><span class=\"basetxt\">$total_page_views &nbsp;</td>
								<td><span class=\"basetxt\">$total_unique_visits &nbsp;</td>
							</tr>" ;
					}
				?>
				<tr bgColor="#8080C0">
					<td width="180" nowrap><span class="smalltxt"><font color="#FFFFFF">Grand Total for Month</td>
					<td><span class="smalltxt"><font color="#FFFFFF"><? echo $grand_total_page_views ?></td>
					<td><span class="smalltxt"><font color="#FFFFFF"><? echo $grand_total_unique_visits ?></td>
				</tr>
				</table>
				<br>
				Top 15 Visited Pages for this Month
				<br>
				<span class="smalltxt">(clicking from here will NOT be tallied)</span>
				<!-- end top 15 visited -->
				<table cellspacing=1 cellpadding=1 border=0 width="100%">
				<?
					for ( $c = 0; $c < count( $top_url_visits );++$c )
					{
						$footprint = $top_url_visits[$c] ;
						if ( !$footprint['url'] )
							$url_string = "<i>data empty</i>" ;
						else
						{
							$goto_url = "$footprint[url]?phplive_notally" ;
							if ( preg_match( "/\?/", $footprint['url'] ) )
								$goto_url = "$footprint[url]&phplive_notally" ;
							$url_string = "<a href=\"$goto_url\" target=\"new\">$footprint[url]</a>" ;
						}

						$bgcolor = "#EEEEF7" ;
						if ( $c % 2 )
							$bgcolor = "#E6E6F2" ;

						print "<tr bgColor=\"$bgcolor\"><td><span class=\"smalltxt\">$footprint[total]</td><td><span class=\"smalltxt\">$url_string</td></tr>\n" ;
					}
				?>
				</table>
				<!-- end top 15 visited -->
				
				<br>
				Top 15 Live! Clicked Pages for this Month
				<br>
				<span class="smalltxt">(clicking from here will NOT be tallied)</span>
				<!-- end top 15 visited -->
				<table cellspacing=1 cellpadding=1 border=0 width="100%">
				<?
					for ( $c = 0; $c < count( $top_live_requests );++$c )
					{
						$footprint = $top_live_requests[$c] ;
						if ( !$footprint['url'] )
							$url_string = "<i>data empty</i>" ;
						else
						{
							$goto_url = "$footprint[url]?phplive_notally" ;
							if ( preg_match( "/\?/", $footprint['url'] ) )
								$goto_url = "$footprint[url]&phplive_notally" ;
							$url_string = "<a href=\"$goto_url\" target=\"new\">$footprint[url]</a>" ;
						}

						$bgcolor = "#EEEEF7" ;
						if ( $c % 2 )
							$bgcolor = "#E6E6F2" ;

						print "<tr bgColor=\"$bgcolor\"><td><span class=\"smalltxt\">$footprint[total]</td><td><span class=\"smalltxt\">$url_string</td></tr>\n" ;
					}
				?>
				</table>
				<!-- end top 15 visited -->








				<?
					else:
					$total_page_views = ServiceFootprint_get_TotalDayFootprint( $dbh, $stat_begin, $stat_end, $session_setup['aspID'], 0 ) ;
					$total_unique_visits = ServiceFootprint_get_TotalUniqueDayVisits( $dbh, $stat_begin, $stat_end, $session_setup['aspID'], 0 ) ;
				?>
				<b><? echo $stat_date ?></b><br>
				<li> <? echo $total_page_views ?> total page views
				<li> <? echo $total_unique_visits ?> total unique visits
				<br>

				<hr>
				Top 15 Visited Pages
				<br>
				<span class="smalltxt">(clicking from here will NOT be tallied)</span>
				<!-- end top 15 visited -->
				<table cellspacing=1 cellpadding=1 border=0 width="100%">
				<?
					for ( $c = 0; $c < count( $top_url_visits );++$c )
					{
						$footprint = $top_url_visits[$c] ;
						//$url_unique_hits = ServiceFootprint_get_TotalUniqueURLDayVisits( $dbh, $stat_begin, $stat_end, $session_setup[aspID], $footprint[url] ) ;

						if ( !$footprint['url'] )
							$url_string = "<i>data empty</i>" ;
						else
						{
							$goto_url = "$footprint[url]?phplive_notally" ;
							if ( preg_match( "/\?/", $footprint['url'] ) )
								$goto_url = "$footprint[url]&phplive_notally" ;
							$url_string = "<a href=\"$goto_url\" target=\"new\">$footprint[url]</a>" ;
						}

						$bgcolor = "#EEEEF7" ;
						if ( $c % 2 )
							$bgcolor = "#E6E6F2" ;

						print "<tr bgColor=\"$bgcolor\"><td><span class=\"smalltxt\">$footprint[total]</td><td><span class=\"smalltxt\">$url_string</td></tr>\n" ;
					}
				?>
				</table>
				<!-- end top 15 visited -->
				
				<br>

				Top 15 Live! Clicked Pages
				<br>
				<span class="smalltxt">(clicking from here will NOT be tallied)</span>
				<!-- end top 15 visited -->
				<table cellspacing=1 cellpadding=1 border=0 width="100%">
				<?
					for ( $c = 0; $c < count( $top_live_requests );++$c )
					{
						$footprint = $top_live_requests[$c] ;
						if ( !$footprint['url'] )
							$url_string = "<i>data empty</i>" ;
						else
						{
							$goto_url = "$footprint[url]?phplive_notally" ;
							if ( preg_match( "/\?/", $footprint['url'] ) )
								$goto_url = "$footprint[url]&phplive_notally" ;
							$url_string = "<a href=\"$goto_url\" target=\"new\">$footprint[url]</a>" ;
						}

						$bgcolor = "#EEEEF7" ;
						if ( $c % 2 )
							$bgcolor = "#E6E6F2" ;

						print "<tr bgColor=\"$bgcolor\"><td><span class=\"smalltxt\">$footprint[total]</td><td><span class=\"smalltxt\">$url_string</td></tr>\n" ;
					}
				?>
				</table>
				<!-- end top 15 visited -->
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