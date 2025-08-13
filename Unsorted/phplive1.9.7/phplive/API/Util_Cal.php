<?
	if ( ISSET( $_OFFICE_UTIL_CAL_LOADED ) == true )
		return ;

	$_OFFICE_UTIL_CAL_LOADED = true ;

	/*****  Util_Cal_DrawCalendar  ********************************
	 *
	 *  Parameters:
	 *	$m				// month
	 *	$y				// year
	 *	$href			// target when clicked
	 *	$href_self		// script that called this function
	 *	$href_month		// target when clicked on the month
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$output ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Yim Cho					Dec 15, 2001
	 *
	 *****************************************************************/
	function Util_Cal_DrawCalendar( $dbh,
						$m,
						$y,
						$href,
						$href_self,
						$href_month )
	{
		if ( ( !$m ) || ( !$y ) )
		{ 
			$m = date( "m",mktime() ) ;
			$y = date( "Y",mktime() ) ;
		}
		$today_m = date( "m",mktime() ) ;
		$today_y = date( "Y",mktime() ) ;
		$today_d = date( "j",mktime() ) ;

		// get the weekday of the first
		$tmpd = getdate( mktime( 0,0,0,$m,1,$y ) ) ;
		$month = $tmpd["month"]; 
		$firstwday= $tmpd["wday"];
		$lastday = LastDayOfMonth( $m, $y ) ;
	?>
	<table cellpadding=1 cellspacing=0 border=0 width="170">
	<tr><td colspan=7>
		<table cellpadding=0 cellspacing=0 border=0 width="100%">
			<tr>
				<td width="30"><span class="smalltxt">
					<a href="<?=$href_self?>?m=<?=(($m-1)<1) ? 12 : $m-1 ?>&y=<?=(($m-1)<1) ? $y-1 : $y ?>&d=1">&lt;&lt;</a> &nbsp;
					<a href="<?=$href_self?>?m=<?=$m?>&y=<?=((($m-1)<1) ? $y-1 : $y)-1?>&d=1">&lt;</a></td>
				<td align="center"><span class="basetxt"><a href="<?="$href_month&m=$m&y=$y"?>"><?="$month $y"?></a></font></td>
				<td width="30" align="right"><span class="smalltxt">
					<a href="<?=$href_self?>?m=<?=$m?>&y=<?=((($m+1)>12) ? $y+1 : $y)+1 ?>&d=1">&gt;</a> &nbsp;
					<a href="<?=$href_self?>?m=<?=(($m+1)>12) ? 1 : $m+1 ?>&y=<?=(($m+1)>12) ? $y+1 : $y ?>&d=1">&gt;&gt;</a>
				</td>
			</tr>
		</table>
	</td></tr>
	<tr><td><span class="basetxt">Su</th><td><span class="basetxt">M</td>
		<td><span class="basetxt">T</td><td><span class="basetxt">W</td>
		<td><span class="basetxt">Th</td><td><span class="basetxt">F</td>
		<td><span class="basetxt">Sa</td></tr>
	<? 
		$d = 1;
		$wday = $firstwday;
		$firstweek = true;

		// loop through days of the week
		while ( $d <= $lastday ) 
		{
			// put blank fillers for the first week exmptys
			if ( $firstweek )
			{
				print "<tr>" ;
				for ( $i=1; $i <= $firstwday; $i++ ) 
					print "<td><font size=2>&nbsp;</font></td>";
				$firstweek = false;
			}

			// each sunday, (0), we place a new row
			if ( $wday == 0 )
				print "<tr>" ;

			$bgcolor = "#EEEEF7" ;
			$time_begin = mktime( 0, 0, 0, $m, $d, $y ) ;
			$time_end = mktime( 23, 59, 59, $m, $d, $y ) ;

			if ( ( $today_d == $d ) && ( $today_m == $m ) && ( $today_y == $y ) )
				$bgcolor = "#BFBFDF" ;

			// the ouput of calendar
			print "
				<td bgColor=\"$bgcolor\"><span class=\"smalltxt\"><a href=\"$href&m=$m&d=$d&y=$y\">$d</a></td>
			" ;

			// end the <tr> on a Saturday
			if ( $wday == 6 )
				print "</tr>\n" ;

			$wday++;
			$wday = $wday % 7;
			$d++;
		}
	?>
	</tr></table>
	<?
	} 

	function LastDayOfMonth( $mon, $year )
	{
		for ( $tday=28; $tday <= 31; $tday++ ) 
		{
			$tdate = getdate( mktime( 0, 0, 0, $mon, $tday, $year ) ) ;
			if ( $tdate["mon"] != $mon ) 
				break;
		}
		$tday-- ;

		return $tday ;
	}
?>