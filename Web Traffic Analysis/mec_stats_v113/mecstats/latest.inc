<h1>Latest Visitors</h1>

<table class="pages" cellpadding="3" cellspacing="0" width="500">
<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

$reversed = array();

$ini_count = 0;
//Reads logfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);
	$reversed[] = $ld;
}
$reversed = array_reverse($reversed);
	
$count = 0;
foreach($reversed as $ele)
{
	if($ele[5]==1 && !find_robot($ele[1], $se_def) && $count<$SETTINGS['latest'])
	{
		//Compensate for time differences
		$ele[0] = new_stamp($ele[0], $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);
	
		//Deals with long referers
		if(strlen($ele[3])>60)
			$ref = chunk_split($ele[3], 60, ' ');
		else
			$ref = $ele[3];
			
		//Deals with long page names
		if(strlen($ele[4])>60)
			$page_name = chunk_split($ele[4], 60, ' ');
		else
			$page_name = $ele[4];
	
		?><tr<?if($count==$SETTINGS['latest']-1){?> class="bottom"<?}?>><td><?
		echo('<b>'.date('m/d/Y g:iA',strtotime($ele[0])).'</b><br>');
		echo('IP: '.$ele[2].'<br>');
		echo('Page: <a href="'.$page_name.'">'.$page_name.'</a><br>');
		echo('Refferer: <a href="'.$ele[3].'">'.$ref.'</a><br>');
		echo('Browser: '.find_browser($ele[1], $browser_def).'<br>');
		echo('Operating System: '.find_os($ele[1], $os_def).'<br>');
		echo('<a href="index.php?page=trace&ip='.$ele[2].'&time='.date('YmdHis', strtotime($ele[0])).'">Trace this visitor\'s path</a>');
		//echo('AGENT: '.$ele[1].'<br>');
		echo('</td></tr>');
	
		$count++;
	}
}
?>
</table>

