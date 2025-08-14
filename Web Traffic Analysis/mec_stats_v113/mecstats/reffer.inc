<h1>Refferer Data</h1>

<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

$domain = array();

//Reads logfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);
	
	if($ld[0]!=NULL)
	{
		$domain_key = get_ref_domain($ld[3]);
		if($domain_key)
		{
			$domain[$domain_key]++;
			$total++;
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
			//Stop Looking at refferers when we hit the next section
			if($ld[0]=='HITS')
			{
				$inref = FALSE;
			}
	
			//Are we looking at refferers yet?
			if($ld[0]=='REFFERERS')
			{
				$inref = TRUE;
			}
			elseif($inref)
			{
				$domain[$ld[0]] = $domain[$ld[0]] + $ld[1];
				$total = $total + $ld[1];
			}
		}
	}
}

arsort($domain);
?>

<b>Based on Total Number of Reffered Hits:</b> <?=$total;?><br><br>

<table class="box" cellspacing="0" cellpadding="5">
<tr class="head"><td>Refferer Data</td></tr>
<tr><td>
<?
$mp = find_mp($domain, $SETTINGS['ref_scale']);

$count = 0;
foreach($domain as $key=>$ele)
{
	if($count<$SETTINGS['ref_num'])
	{
		$percent = round($ele*100/$total, 1);
		
		$width = $ele*$mp;
		$alt_width = 300-$width;
		
		echo('<table class="main"><tr><td width="160"><a href="index.php?page=reffer_detail&dom='.$key.'">'.$key.'</a></td><td width="40">'.$ele.'</td><td class="bar" width="'.$width.'"></td><td width="'.$alt_width.'">'.$percent.'%</td></tr></table>');
		$count++;
	}
}
?>
</td></tr>
</table>
