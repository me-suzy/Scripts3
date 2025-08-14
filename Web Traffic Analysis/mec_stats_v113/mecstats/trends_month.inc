<h1>Trends - Month</h1>

<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

//Compensate for time differences
$sys_time = new_stamp(date('Y-m-d H:i:s'), $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);

$hits_month = array();
$visits_month = array();

//Creates time if viewing past day
if($_GET['m']=='' || $_GET['y']=='' || (date('Y', strtotime($sys_time))==$_GET['y'] && date('m', strtotime($sys_time))==$_GET['m']))
{
	$this_month = TRUE;
	$cur_time = date('Y-m-d H:i:01', strtotime($sys_time));
}
else
{
	$cur_time = $_GET['y'].'-'.$_GET['m'].'-01 01:01:01';
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
		
		if(date('Ym', strtotime($cur_time))==date('Ym', strtotime($ld[0])))
		{
			$month_key = date('j', strtotime($ld[0]));
			$hits_month[$month_key]++;
			if($ld[5]==1)
			{
				$visits_month[$month_key]++;
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
				//Gets This Months
				if(date("Ym", strtotime($cur_time))==date("Ym", strtotime($ld[0])))
				{
					$month_key = date('j', strtotime($ld[0]));
					$hits_month[$month_key] = $hits_month[$month_key] + $ld[1];
					$visits_month[$month_key] = $visits_month[$month_key] + $ld[2];
				}
			}
		}
	}
}



ksort($hits_month);
ksort($visits_month);
?>

<table class="main" cellspacing="0" cellpadding="0">
<tr><td>Hits </td><td width="5"></td><td width="50" class="hitbar"></td></tr>
<tr><td>Visits </td><td width="5"></td><td width="50" class="visitbar"></td></tr>
</table><br>

<br>

<table class="box" cellspacing="0" cellpadding="5">
<tr class="head"><td><?=date('F, Y', strtotime($cur_time));?></td></tr>
<tr><td>

<?
if($this_month)
	$limit = date('j', strtotime($sys_time));
else
	$limit = date('t', strtotime($cur_time));
	
$mp = find_mp($hits_month, $SETTINGS['trend_scale']);
	
//Loops through and displays data
for($c=1;$c<=$limit;$c++)
{
	//HITS
	if(!$hits_month[$c])
		$hits_month[$c] = 0;
	
	$width = round($hits_month[$c]*$mp);
	$alt_width = round($SETTINGS['trend_scale']-$width);
	echo('<table class="main" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><a href="index.php?page=trends&m='.date('m', strtotime($cur_time)).'&d='.$c.'&y='.date('Y', strtotime($cur_time)).'">'.$c.'</a></td><td class="hitbar" width="'.$width.'"></td><td width="'.$alt_width.'"> '.$hits_month[$c].'</td></tr></table>');
	
	//VISITS
	if(!$visits_month[$c])
		$visits_month[$c] = 0;
		
	$width = round($visits_month[$c]*$mp);
	$alt_width = round($SETTINGS['trend_scale']-$width);
	echo('<table class="main" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"></td><td class="visitbar" width="'.$width.'"></td><td width="'.$alt_width.'"> '.$visits_month[$c].'</td></tr></table>');
	echo('<table cellpadding="0" cellspacing="0" border="0" height="3"><tr height="3"><td></td></tr></table>');
}
?>
</td></tr></table>