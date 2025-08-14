<h1>Trends - Day</h1>

<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

//Compensate for time differences
$sys_time = new_stamp(date('Y-m-d H:i:s'), $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);

$hits_today = array();
$visits_today = array();

//Creates time if viewing past day
if($_GET['m']=='' || $_GET['y']=='' || $_GET['d']=='' || (date('Y', strtotime($sys_time))==$_GET['y'] && date('m', strtotime($sys_time))==$_GET['m'] && date('j', strtotime($sys_time))==$_GET['d']))
{
	$cur_time = date('Y-m-d H:i:01', strtotime($sys_time));
}
else
{
	$cur_time = $_GET['y'].'-'.$_GET['m'].'-'.$_GET['d'].' 01:01:01';
}

//Reads through logfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);
			
	//Make sure were not looking at a blank line
	if($ld[0]!=NULL)
	{
		//Compensate for time differences
		$ld[0] = new_stamp($ld[0], $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);
		
		if(date('Ymd', strtotime($cur_time))==date('Ymd', strtotime($ld[0])))
		{
			$today_key = date('G', strtotime($ld[0]));
			$hits_today[$today_key]++;
			if($ld[5]==1)
			{
				$visits_today[$today_key]++;
			}
			$real_hits++;
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
			//Are we looking at dates yet?
			if($ld[0]=='HITS')
			{
				$indates = TRUE;
			}
			elseif($indates)//Figure out hits in last day/week/month
			{
				//Gets Todays Data
				if(date("Ymd", strtotime($cur_time))==date("Ymd", strtotime($ld[0])))
				{
					$today_key = 0;
					$hits_today[$today_key] = $hits_today[$today_key] + $ld[1];
					$visits_today[$today_key] = $visits_today[$today_key] + $ld[2];
				}
			}
		}
	}
}

ksort($hits_today);
ksort($visits_today);
?>

<table class="main" cellspacing="0" cellpadding="0">
<tr><td>Hits </td><td width="5"></td><td width="50" class="hitbar"></td></tr>
<tr><td>Visits </td><td width="5"></td><td width="50" class="visitbar"></td></tr>
</table><br>

<table class="box" cellspacing="0" cellpadding="5">
<tr class="head"><td><?=date('m/d/Y', strtotime($cur_time));?></td></tr>
<tr><td>
<?
if($today)
	$limit = date('G', strtotime($sys_time));
else
	$limit = 23;
	
$mp = find_mp($hits_today, $SETTINGS['trend_scale']);

for($c=0;$c<$limit+1;$c++)
{
	//HITS
	if(!$hits_today[$c])
		$hits_today[$c] = 0;
	
	//Deals with 12hr clock
	$display_hour = $c;
	if($display_hour>12)
	{
		$display_hour = $display_hour-12;
		$display_hour .=':00PM';
	}
	elseif($display_hour==0)
		$display_hour = '12:00AM';
	elseif($display_hour==12)
		$display_hour = '12:00PM';
	else
		$display_hour = $display_hour.':00AM';
	

	$width = round($hits_today[$c]*$mp);
	$alt_width = round($SETTINGS['trend_scale']-$width);
	echo('<table class="main" cellpadding="0" cellspacing="0" border="0"><tr><td width="70">'.$display_hour.'</td><td class="hitbar" width="'.$width.'"></td><td width="'.$alt_width.'"> '.$hits_today[$c].'</td></tr></table>');
	
	//VISITS
	if(!$visits_today[$c])
		$visits_today[$c] = 0;
		
	$width = round($visits_today[$c]*$mp);
	$alt_width = round($SETTINGS['trend_scale']-$width);
	echo('<table class="main" cellpadding="0" cellspacing="0" border="0"><tr><td width="70"></td><td class="visitbar" width="'.$width.'"></td><td width="'.$alt_width.'"> '.$visits_today[$c].'</td></tr></table>');
	echo('<table cellpadding="0" cellspacing="0" border="0" height="3"><tr height="3"><td></td></tr></table>');
}
?>
</td>
</tr>
</table>
