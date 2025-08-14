<b>Trends</b><br><br>

<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

//Compensate for time differences
$sys_time = new_stamp(date('Y-m-d H:i:s'), $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);

$hits_all = array();
$visits_all = array();

//Define Initial Frist Date to Prevent Errors
$first_date = date('Y-m-d');

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
		
		$all_key = date('Y', strtotime($ld[0]));
		$hits_all[$all_key]++;
		if($ld[5]==1)
			$visits_all[$all_key]++;
		$real_hits++;
		
		//Finds first date
		if(date('YmdHis', strtotime($ld[0]))<date('YmdHis', strtotime($first_date)))
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
		
		//Find Starting Date of Logs
		if($ld[0]=='START_TIME')
			$first_date = $ld[1];
		
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
				$all_key = date('Y', strtotime($ld[0]));
				$hits_all[$all_key] = $hits_all[$all_key] + $ld[1];
				$visits_all[$all_key] = $visits_all[$all_key] + $ld[2];
			}
		}
	}
}

ksort($hits_all);
ksort($visits_all);
?>

<table class="main" cellspacing="0" cellpadding="0">
<tr><td>Hits </td><td width="5"></td><td width="50" class="hitbar"></td></tr>
<tr><td>Visits </td><td width="5"></td><td width="50" class="visitbar"></td></tr>
</table><br>

<br>

<table class="box" cellspacing="0" cellpadding="5">
<tr class="head"><td>All Years</td></tr>
<tr><td>

<?
$mp = find_mp($hits_all, $SETTINGS['trend_scale']);



for($c=date('Y', strtotime($first_date));$c<=date('Y', strtotime($sys_time));$c++)
{
	//HITS
	if(!$hits_all[$c])
		$hits_all[$c] = 0;
	
	$width = round($hits_all[$c]*$mp);
	$alt_width = round($SETTINGS['trend_scale']-$width);
	echo('<table class="main" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><a href="index.php?page=trends_year&y='.$c.'">'.$c.'</a></td><td class="hitbar" width="'.$width.'"></td><td width="'.$alt_width.'"> '.$hits_all[$c].'</td></tr></table>');
	
	//VISITS
	if(!$visits_all[$c])
		$visits_all[$c] = 0;
		
	$width = round($visits_all[$c]*$mp);
	$alt_width = round($SETTINGS['trend_scale']-$width);
	echo('<table class="main" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"></td><td class="visitbar" width="'.$width.'"></td><td width="'.$alt_width.'"> '.$visits_all[$c].'</td></tr></table>');
	echo('<table cellpadding="0" cellspacing="0" border="0" height="3"><tr height="3"><td></td></tr></table>');
}
?>
</td></tr></table>