<h1>Browser and Operating System Data</h1>

<?
//Written by Matt Toigo

$browsers = array();
$os = array();

$logfile = fopen($SETTINGS['logfile'], 'r');

//Reads through textfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);
	
	//Puts browsers and os in arrays
	if(!find_robot($ld[1], $se_def))
	{
		$browser_key = find_browser($ld[1], $browser_def);
		$browsers[$browser_key]++;
		
		$os_key = find_os($ld[1], $os_def);
		$os[$os_key]++;
		
		$total++;
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
			
		//FIND BROWSER STATS
		//Are we still looking at browsers
		if($ld[0]=='OS')
			$inbrow = FALSE;
			
		//Are we looking at browsers yet?
		if($ld[0]=='BROWSERS')
		{
			$inbrow = TRUE;
		}
		elseif($inbrow)//Figure out hits in last day/week/month
		{
			$browsers[$ld[0]] = $browsers[$ld[0]] + $ld[1];
			$total = $total + $ld[1];
		}
		
		//FIND OS STATS
		//Are we still looking at OS
		if($ld[0]=='ROBOTS')
			$inos = FALSE;
			
		//Are we looking at OS yet?
		if($ld[0]=='OS')
		{
			$inos = TRUE;
		}
		elseif($inos)//Figure out hits in last day/week/month
		{
			$os[$ld[0]] = $os[$ld[0]] + $ld[1];
		}
		
		
	}
}
arsort($browsers);
arsort($os);
?>

<table class="box" cellspacing="0" cellpadding="5">
<tr class="head"><td>Browser Data</td></tr>
<tr><td>
<?
$mp = find_mp($browsers, $SETTINGS['brow_os_scale']);

foreach($browsers as $key=>$ele)
{
	$percent = round($ele*100/$total, 1);
	
	$width = $ele*$mp;
	$alt_width = 300-$width;
	
	echo('<table class="main"><tr><td width="160">'.$key.'</td><td width="40">'.$ele.'</td><td class="bar" width="'.$width.'"></td><td width="'.$alt_width.'">'.$percent.'%</td></tr></table>');
}
?>
</td></tr>
</table>
<br>

<table class="box" cellspacing="0" cellpadding="5">
<tr class="head"><td>Operating System Data</td></tr>
<tr><td>
<?

$mp = find_mp($os, $SETTINGS['brow_os_scale']);
foreach($os as $key=>$ele)
{
	$percent = round($ele*100/$total, 1);
	
	$width = $ele*$mp;
	$alt_width = 300-$width;
	
	echo('<table class="main"><tr><td width="160">'.$key.'</td><td width="40">'.$ele.'</td><td class="bar" width="'.$width.'"></td><td width="'.$alt_width.'">'.$percent.'%</td></tr></table>');
}
?>
</td></tr>
</table>