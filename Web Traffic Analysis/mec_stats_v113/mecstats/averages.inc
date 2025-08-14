<h1>Averages</h1>
<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

//Declare arrays
$hours = array();
$hours_visit = array();
$days = array();
$days_visit = array();
$months = array();
$months_visit = array();

//Define Initial Frist Date to Prevent Errors
$first_date = date('Y-m-d');

//Read Logfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);
	
	//Make sure were not looking at a blank line
	if($ld[0]!=NULL)
	{
		//Creates array key
		$hours_key = date('YmdH', strtotime($ld[0]));
		$days_key = date('Ymd', strtotime($ld[0]));
		$months_key = date('Ym', strtotime($ld[0]));
		
		//Adds Hit
		$total_hits++;
		$hours[$hours_key]++;
		$days[$days_key]++;
		$months[$months_key]++;
		
		//Adds Visit
		if($ld[5]==1)
		{
			$total_visit++;
			$hours_visit[$hours_key]++;
			$days_visit[$days_key]++;
			$months_visit[$months_key]++;
		}
		
		//Finds first date
		if(date('YmdHis', strtotime($ld[0]))<date('YmdHis', strtotime($first_date)) && $ld[0]!='')//FIX THIS
		{
			$first_date = $ld[0];
		}
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
			//Find Starting Date of Logs
			if($ld[0]=='START_TIME')
				$first_date = $ld[1];
				
			//Find All Time High Visits and Hits
			if($ld[0]=='HIGH_HITS_HOUR')
				$arch_high_hits_hour = $ld[1];
			
			if($ld[0]=='HIGH_VISITS_HOUR')
				$arch_high_visits_hour = $ld[1];
			
			//Are we looking at dates yet?
			if($ld[0]=='HITS')
			{
				$indates = TRUE;
			}
			elseif($indates)//Figure out hits in last day/week/month
			{
				//Creates array key
				$days_key = date('Ymd', strtotime($ld[0]));
				$months_key = date('Ym', strtotime($ld[0]));
				
				//Adds Total Hits
				$total_hits = $total_hits + $ld[1];
				$total_visit = $total_visit + $ld[2];
				
				//Adds Hits for Days
				$days[$days_key] = $days[$days_key] + $ld[1];
				$days_visit[$days_key] = $days_visit[$days_key] + $ld[2];
				
				//Adds Hits for Months
				$months[$months_key] = $months[$months_key] + $ld[1];
				$months_visit[$months_key] = $months_visit[$months_key] + $ld[2];
			}
		}
	}
}
fclose($archivefile);

//Gets difference in seconds
$now = mktime();
$then = mktime(date('G', strtotime($first_date)), date('i', strtotime($first_date)), date('s', strtotime($first_date)), date('n', strtotime($first_date)), date('j', strtotime($first_date)), date('Y', strtotime($first_date)));
$difference = $now-$then;

//Calc units of time
$hour_count = ceil($difference/3600);
$day_count = ceil($difference/86400);
$month_count = ceil($difference/2592000);

//Finds Averages
$hour_average = @round($total_hits/$hour_count);
$hour_average_visit = @round($total_visit/$hour_count);
$day_average = @round($total_hits/$day_count);
$day_average_visit = @round($total_visit/$day_count);
$month_average =  @round($total_hits/$month_count);
$month_average_visit = @round($total_visit/$month_count);

//ADD TIME FOR RECORD HIGHS, WOULD NEED TO USE ARRAY KEYS THAT COULD BE FORMATTED
//Finds High Values

//Determine if log.txt or arch.txt hour highs are larger
if(find_high($hours)>$arch_high_hits_hour)
	$high_hour = find_high($hours);
else
	$high_hour = $arch_high_hits_hour;

if(find_high($hours_visit)>$arch_high_visits_hour)
	$high_hour_visit = find_high($hours_visit);
else
	$high_hour_visit = $arch_high_visits_hour;

$high_day = find_high($days);
$high_day_visit = find_high($days_visit);
$high_month = find_high($months);
$high_month_visit = find_high($months_visit);

//Compensate for difference in time
$sys_time = new_stamp($first_date, $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);
?>
<b>Stats Began on :</b> <?echo(date('m/d/Y g:i:A', strtotime($sys_time)));?><br><br>

<table><tr><td valign="top">

<table class="pages" cellspacing="0" cellpadding="5" width="250">
	<tr>
		<td class="bluebold">Average Hits Per Hour: </td>
		<td align="right"><?=$hour_average;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">All Time High: </td>
		<td align="right"><?=$high_hour;?></td>
	</tr>
</table>
<br>
<table class="pages" cellspacing="0" cellpadding="5" width="250">
	<tr>
		<td class="bluebold">Average Hits Per Day: </td>
		<td align="right"><?=$day_average;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">All Time High: </td>
		<td align="right"><?=$high_day;?></td>
	</tr>
</table>
<br>
<table class="pages" cellspacing="0" cellpadding="5" width="250">
	<tr>
		<td class="bluebold">Average Hits Per Month: </td>
		<td align="right"><?=$month_average;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">All Time High: </td>
		<td align="right"><?=$high_month;?></td>
	</tr>
</table>

</td><td width="20"></td><td>

<table class="pages" cellspacing="0" cellpadding="5" width="250">
	<tr>
		<td class="bluebold">Average Visitors Per Hour: </td>
		<td align="right"><?=$hour_average_visit;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">All Time High: </td>
		<td align="right"><?=$high_hour_visit;?></td>
	</tr>
</table>
<br>
<table class="pages" cellspacing="0" cellpadding="5" width="250">
	<tr>
		<td class="bluebold">Average Visitors Per Day: </td>
		<td align="right"><?=$day_average_visit;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">All Time High: </td>
		<td align="right"><?=$high_day_visit;?></td>
	</tr>
</table>
<br>
<table class="pages" cellspacing="0" cellpadding="5" width="250">
	<tr>
		<td class="bluebold">Average Visitors Per Month: </td>
		<td align="right"><?=$month_average_visit;?></td>
	</tr>
	<tr class="bottom">
		<td class="bluebold">All Time High: </td>
		<td align="right"><?=$high_month_visit;?></td>
	</tr>
</table>

</td></tr></table>