<h1>Trends - Year</h1>

<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

//Compensate for time differences
$sys_time = new_stamp(date('Y-m-d H:i:s'), $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);

$hits_year = array();
$visits_year = array();

//Creates time if viewing past year
if($_GET['y']=='' || (date('Y', strtotime($sys_time))==$_GET['y']))
{
	$this_year = TRUE;
	$cur_time = date('Y-m-d H:i:01', strtotime($sys_time));
}
else
{
	$cur_time = $_GET['y'].'-01-01 01:01:01';
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
		
		if(date('Y', strtotime($cur_time))==date('Y', strtotime($ld[0])))
		{
			$year_key = date('n', strtotime($ld[0]));
			$hits_year[$year_key]++;
			if($ld[5]==1)
				$visits_year[$year_key]++;
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
			elseif($indates)//Figure out hits
			{
				//Gets This Years Hits
				if(date("Y", strtotime($cur_time))==date("Y", strtotime($ld[0])))
				{
					$year_key = date('n', strtotime($ld[0]));
					$hits_year[$year_key] = $hits_year[$year_key] + $ld[1];
					$visits_year[$year_key] = $visits_year[$year_key] + $ld[2];
				}
			}
		}
	}
}

ksort($hits_year);
ksort($visits_year);
?>

<table class="main" cellspacing="0" cellpadding="0">
<tr><td>Hits </td><td width="5"></td><td width="50" class="hitbar"></td></tr>
<tr><td>Visits </td><td width="5"></td><td width="50" class="visitbar"></td></tr>
</table><br>

<br>

<table class="box" cellspacing="0" cellpadding="5">
<tr class="head"><td><?=date('Y', strtotime($cur_time));?></td></tr>
<tr><td>

<?
$mp = find_mp($hits_year, $SETTINGS['trend_scale']);

//Determine when to stop looping through months
if($this_year)
	$date_limit = date('n', strtotime($sys_time));
else
	$date_limit = 12;

for($c=1;$c<=$date_limit;$c++)
{
	//HITS
	if(!$hits_year[$c])
		$hits_year[$c] = 0;
	
	$width = round($hits_year[$c]*$mp);
	$alt_width = round($SETTINGS['trend_scale']-$width);
	echo('<table class="main" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><a href="index.php?page=trends_month&m='.$c.'&y='.date('Y', strtotime($cur_time)).'">'.$c.'</a></td><td class="hitbar" width="'.$width.'"></td><td width="'.$alt_width.'"> '.$hits_year[$c].'</td></tr></table>');
	
	//VISITS
	if(!$visits_year[$c])
		$visits_year[$c] = 0;
		
	$width = round($visits_year[$c]*$mp);
	$alt_width = round($SETTINGS['trend_scale']-$width);
	echo('<table class="main" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"></td><td class="visitbar" width="'.$width.'"></td><td width="'.$alt_width.'"> '.$visits_year[$c].'</td></tr></table>');
	echo('<table cellpadding="0" cellspacing="0" border="0" height="3"><tr height="3"><td></td></tr></table>');
}
?>
</td></tr></table>