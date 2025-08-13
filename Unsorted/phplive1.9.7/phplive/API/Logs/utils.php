<?
	include_once("$DOCUMENT_ROOT/API/Service/Schedules/php/get.php") ;
	/*
		Draws out a calendar (in html) of the month/year
		passed to it date passed in format mm-dd-yyyy 
	*/
	function mk_drawCalendar( $dbh, $m, $y, $href, $href_self, $target, $userid )
	{
		GLOBAL $BASE_URL ;
		GLOBAL $C_CALENDAR_ROW1 ;
		GLOBAL $C_CALENDAR_ROW2 ;
		GLOBAL $C_CALENDAR_TODAY ;
		GLOBAL $C_CALENDAR_DARK ;
		GLOBAL $BGCOLORDARK ;

		if ((!$m) || (!$y))
		{ 
			$m = date("m",mktime());
			$y = date("Y",mktime());
		}
		$today_m = date("m",mktime());
		$today_y = date("Y",mktime());
		$today_d = date("j",mktime());

		/*== get what weekday the first is on ==*/
		$tmpd = getdate( mktime( 0,0,0,$m,1,$y ) );
		$month = $tmpd["month"]; 
		$firstwday= $tmpd["wday"];
		$lastday = mk_getLastDayofMonth( $m, $y);

		/*== get the events ==*/
		$begin = mktime(0,0,1,$m,1,$y) ;
		$end = mktime(23,59,59,$m,31,$y) ;
		$schs_hash = ServiceSchedules_get_EventsOnDayQuick( $dbh, $userid, $m, $y ) ;

	?>
	<table cellpadding=1 cellspacing=0 border=0 width="170">
	<tr><td colspan=7 bgcolor="<? echo $C_CALENDAR_ROW1 ?>">
		<table cellpadding=0 cellspacing=0 border=0 width="100%">
			<tr>
				<td width="30"><span class="smalltxt">
					<a href="<?=$href_self?>?m=<?=(($m-1)<1) ? 12 : $m-1 ?>&y=<?=(($m-1)<1) ? $y-1 : $y ?>&d=1">&lt;&lt;</a> &nbsp;
					<a href="<?=$href_self?>?m=<?=$m?>&y=<?=((($m-1)<1) ? $y-1 : $y)-1?>&d=1">&lt;</a></td>
				<td align="center"><span class="basetxt"><?="$month $y"?></font></td>
				<td width="30" align="right"><span class="smalltxt">
					<a href="<?=$href_self?>?m=<?=$m?>&y=<?=((($m+1)>12) ? $y+1 : $y)+1 ?>&d=1">&gt;</a> &nbsp;
					<a href="<?=$href_self?>?m=<?=(($m+1)>12) ? 1 : $m+1 ?>&y=<?=(($m+1)>12) ? $y+1 : $y ?>&d=1">&gt;&gt;</a>
				</td>
			</tr>
		</table>
	</td></tr>
	<tr bgColor="<? echo $C_CALENDAR_DARK ?>"><td><span class="basetxt">Su</th><td><span class="basetxt">M</td>
		<td><span class="basetxt">T</td><td><span class="basetxt">W</td>
		<td><span class="basetxt">Th</td><td><span class="basetxt">F</td>
		<td><span class="basetxt">Sa</td></tr>
	<?  $d = 1;
		$wday = $firstwday;
		$firstweek = true;

		/*== loop through all the days of the month ==*/
		while ( $d <= $lastday) 
		{

			/*== set up blank days for first week ==*/
			if ($firstweek) {
				print "<tr>";
				for ($i=1; $i<=$firstwday; $i++) 
				{ print "<td><font size=2>&nbsp;</font></td>"; }
				$firstweek = false;
			}

			/*== Sunday start week with <tr> ==*/
			if ($wday==0) { print "<tr>"; }

			$bgcolor = $BGCOLOR ;
			$time_begin = mktime(0,0,0,$m,$d,$y) ;
			$time_end = mktime(23,59,59,$m,$d,$y) ;

			$bgcolor = $C_CALENDAR_ROW1 ;
			if ( $schs_hash[$d] )
				$bgcolor = $BGCOLORDARK ;

			if ( ( $today_d == $d ) && ( $today_m == $m ) && ( $today_y == $y ) )
				$bgcolor = $C_CALENDAR_TODAY ;

			/*== print out the date here ==*/  
			/*== body of data ==*/
			if ( $href )
			{
				print "
					<td bgColor=\"$bgcolor\"><span class=\"smalltxt\"><a href=\"$href?session=$RAND&m=$m&d=$d&y=$y\" target=\"$target\">$d</a></td>
				" ;
			}
			else
			{
				$month_expand = date("F",mktime(1,1,1,$m,$d,$y));
				$day_expand = date("D",mktime(1,1,1,$m,$d,$y));
				$year_expand = date("Y",mktime(1,1,1,$m,$d,$y));
				$date_string = "$day_expand $month_expand $d, $year_expand" ;
				print "
					<td bgColor=\"$bgcolor\"><span class=\"smalltxt\"><a href=\"JavaScript:void(0);\" OnClick=\"parent.window.schedule_main.put_begin_date($m,$d,$y,'$date_string');\">$d</a></td>
				" ;
			}

			/*== Saturday end week with </tr> ==*/
			if ($wday==6) { print "</tr>\n"; }

			$wday++;
			$wday = $wday % 7;
			$d++;
		}
	?>
	</tr></table>
	<?
	/*== end drawCalendar function ==*/
	} 

	/*== get the last day of the month ==*/
	function mk_getLastDayofMonth($mon,$year)
	{
		for ($tday=28; $tday <= 31; $tday++) 
		{
			$tdate = getdate(mktime(0,0,0,$mon,$tday,$year));
			if ($tdate["mon"] != $mon) 
			{ break; }

		}
		$tday--;

		return $tday;
	}
?>
