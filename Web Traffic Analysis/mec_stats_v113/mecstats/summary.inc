<h1>Summary Data</h1>

<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

//Compensate for time differences
$sys_time = new_stamp(date('Y-m-d H:i:s'), $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);

//Reads logfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);
	
	//Make sure this isn't a blank line
	if($ld[0]!=NULL)
	{
		//Compensate for time differences
		$ld[0] = new_stamp($ld[0], $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);
		
		//Gets Last Hours Data
		if(date("YmdH", strtotime($ld[0])) >= date("YmdH", mktime (date('H', strtotime($sys_time))-1,date('i', strtotime($sys_time)),date('s', strtotime($sys_time)),date('n', strtotime($sys_time)),date('j', strtotime($sys_time)),date('Y', strtotime($sys_time)))))
		{
			if($ld[5]==1)
				$hour_total_visit++;
			$hour_total_hits++;
		}
		
		//Gets Todays Data
		if(date("Ymd", strtotime($sys_time))==date("Ymd", strtotime($ld[0])))
		{
			if($ld[5]==1)
				$today_total_visit++;
			$today_total_hits++;
		}
		
		//Gets Data from the LAST week
		if(date("Ymd", strtotime($ld[0])) >= date("Ymd", mktime (date('H', strtotime($sys_time)),date('i', strtotime($sys_time)),date('s', strtotime($sys_time)),date('n', strtotime($sys_time)),date('j', strtotime($sys_time))-6,date('Y', strtotime($sys_time)))))
		{
			if($ld[5]==1)
				$week_total_visit++;
			$week_total_hits++;
		}
		
		//Gets Data From this month
		if(date("Ym", strtotime($ld[0])) == date("Ym", strtotime($sys_time)))
		{
			if($ld[5]==1)
				$month_total_visit++;
			$month_total_hits++;
		}
		
		//Gets Alltime Data
		if($ld[5]==1)
			$total_visit++;
		$total_hits++;
	}
}
fclose($logfile);

//Include archived files, only works with one archive for now
if(file_exists('arch.txt'))
{
	$archivefile = fopen('arch.txt', 'r');
	while(!feof($archivefile))
	{
		$line = fgets($archivefile, 1024);
		$ld = explode('|', $line);
		
		//Make sure were not looking at a blank line
		if($ld[0]!=NULL)
		{
			//Set Total Hits and Visits
			if($ld[0]=='TOTAL_HITS')
				$total_hits = $total_hits + $ld[1];
			if($ld[0]=='TOTAL_VISITS')
			{
				$total_visit = $total_visit + $ld[1];	
			}
				
			//Are we looking at dates yet?
			if($ld[0]=='HITS')
			{
				$indates = TRUE;
			}
			elseif($indates)//Figure out hits in last day/week/month
			{
			
				//Gets Data From this month
				if(date("Ym", strtotime($ld[0])) == date("Ym", strtotime($sys_time)))
				{
					$month_total_hits = $month_total_hits + $ld[1];
					$month_total_visit = $month_total_visit + $ld[2];
				}
			
				//Finds Hits in Last Week
				if(date("Ymd", strtotime($ld[0])) >= date("Ymd", mktime (date('H', strtotime($sys_time)),date('i', strtotime($sys_time)),date('s', strtotime($sys_time)),date('n', strtotime($sys_time)),date('j', strtotime($sys_time))-6,date('Y', strtotime($sys_time)))))
				{
					$week_total_hits = $week_total_hits + $ld[1];
					$week_total_visit = $week_total_visit + $ld[2];
				}
				
				//Gets Todays Data
				if(date("Ymd", strtotime($sys_time))==date("Ymd", strtotime($ld[0])))
				{
					$today_total_hits = $today_total_hits + $ld[1];
					$today_total_visit = $today_total_visit + $ld[2];
				}
			}
		}
	}
}
fclose($archivefile);
?>
<table><tr><td valign="top">

<table class="pages" cellspacing="0" cellpadding="5" width="265">
	<tr>
		<td class="bluebold">Total Hits: </td>
		<td align="right"><?=$total_hits;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">Total Visitors: </td>
		<td align="right"><?=$total_visit;?></td>
	</tr>
</table>
<br>
<table class="pages" cellspacing="0" cellpadding="5" width="265">
	<tr>
		<td class="bluebold">Total Hits in the last Hour: </td>
		<td align="right"><?=$hour_total_hits;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">Total Visitors in the last Hour: </td>
		<td align="right"><?=$hour_total_visit;?></td>
	</tr>
</table>
<br>
<table class="pages" cellspacing="0" cellpadding="5" width="265">
	<tr>
		<td class="bluebold">Total Hits Today: </td>
		<td align="right"><?=$today_total_hits;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">Total Visitors Today: </td>
		<td align="right"><?=$today_total_visit;?></td>
	</tr>
</table>

</td><td width="20"></td><td valign="top">

<table class="pages" cellspacing="0" cellpadding="5" width="265">
	<tr>
		<td class="bluebold">Total Hits in the last Week: </td>
		<td align="right"><?=$week_total_hits;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">Total Visitors in the last Week: </td>
		<td align="right"><?=$week_total_visit;?></td>
	</tr>
</table>
<br>
<table class="pages" cellspacing="0" cellpadding="5" width="265">
	<tr>
		<td class="bluebold">Total Hits this Month: </td>
		<td align="right"><?=$month_total_hits;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">Total Visitors this Month: </td>
		<td align="right"><?=$month_total_visit;?></td>
	</tr>
</table>	

</td></tr></table>
<br>
<?
require('archive_check.inc');
?>
