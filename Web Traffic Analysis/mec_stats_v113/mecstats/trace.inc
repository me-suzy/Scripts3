<h1>Trace a Visitors Path</h1>

<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

$path = array();

//Reads logfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);

	//Compensate for time differences
	$ld[0] = new_stamp($ld[0], $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);
	
	//Gets hits with same ip and after initial hit
	if($ld[2]==$_GET[ip] && date('YmdHis', strtotime($ld[0]))>=$_GET['time'])
	{
		$path[] = $ld;
	}
}
fclose($logfile);

//Finds the hit were interested in
$details = array();
$details = $path[0];
array_shift($path);

//Finds the hits associated with the unique visitor hit
$new_visit = FALSE;
$final_session = array();
foreach($path as $key=>$ele)
{
		if($path[$key][5]==1)
			$new_visit = TRUE;
			
		if(!$new_visit)
			$final_session[] = $path[$key];
}

//Deals with long referers
if(strlen($details[3])>60)
	$ref = chunk_split($details[3], 60, ' ');
else
	$ref = $details[3];

echo('<table class="pages" cellpadding="3" cellspacing="0" width="500">');

//Displays initial info
echo('<tr class="head"><td>'.date('m/d/Y g:iA',strtotime($details[0])).'</b></td></tr>');
echo('<tr><td>IP: '.$details[2].'<br>');
echo('Browser: '.find_browser($details[1], $browser_def).'<br>');
echo('Operating System: '.find_os($details[1], $os_def).'<br>');
echo('Referer: <a href="'.$details[3].'">'.$ref.'</a></td></tr>');

$se = find_se($details[3], $se_def);
if($se)
{
	$terms = find_query($se, $details[3], $se_def);
	if($terms)
		echo('<tr><td>This visitor found your website when searching for <b>'.$terms.'</b> on <b>'.$se.'</b>.</td></tr>');
	else
		echo('<tr><td>This visitor found your website when searching with <b>'.$se.'</b>.</td></tr>');
}

//Deals with long page names
if(strlen($details[4])>60)
	$page_name = chunk_split($details[4], 60, ' ');
else
	$page_name = $details[4];

?><tr<?if(count($final_session)==0){?> class="bottom"<?}?>><td>The first page they viewed was<br><a href="<?=$details[4];?>"><?=$page_name;?></a><br> at <?=date('m/d/Y g:i:sA', strtotime($details[0]));?></td></tr><?

//Displays path
$count = 1;
foreach($final_session as $key=>$ele)
{
	//Deals with long page names
	if(strlen($final_session[$key][4])>60)
		$page_name = chunk_split($final_session[$key][4], 60, ' ');
	else
		$page_name = $final_session[$key][4];

	if(count($final_session)==$count)
		echo('<tr class="bottom"><td>The last page they viewed was<br><a href="'.$final_session[$key][4].'">'.$page_name.'</a><br>at '.date('m/d/Y g:i:sA', strtotime($final_session[$key][0])).'</td></tr>');
	else
		echo('<tr><td>The next page they viewed was<br><a href="'.$final_session[$key][4].'">'.$page_name.'</a><br>at '.date('m/d/Y g:i:sA', strtotime($final_session[$key][0])).'</td></tr>');

	$count++;
}
?>
</table>