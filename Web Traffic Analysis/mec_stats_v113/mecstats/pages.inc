<h1>Page Rankings</h1>

<?
//Written by Matt Toigo

$pages = array();

$logfile = fopen($SETTINGS['logfile'], 'r');

//Get data from logfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);
	
	//Make sure were not looking at a blank line
	if($ld[0]!=NULL)
	{
		//Creates array where page name is key and hitcount is element
		$pages[$ld[4]]++;
		$total++;
	}
}

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
			//Stop Looking at dates when we hit the next section
			if($ld[0]=='BROWSERS')
			{
				$indates = FALSE;
			}
	
			//Are we looking at pages yet?
			if($ld[0]=='PAGES')
			{
				$indates = TRUE;
			}
			elseif($indates)//Figure out hits in last day/week/month
			{
				$pages[$ld[0]] = $pages[$ld[0]] + $ld[1];
				$total = $total + $ld[1];
			}
		}
	}
}

arsort($pages);
?>

<table class="pages" cellpadding="5" cellspacing="0"><tr class="head"><td>Rank</td><td>Page Name</td><td>Hits</td><td>Percent</td></tr>
<?
//Display Page Rank Data
$position = 1;
$size = count($pages);
foreach($pages as $key => $ele)
{
	if(exclude($key, $SETTINGS['exclude']) && $position<$SETTINGS['ranked_pages']+1) //exclude patterns
	{
		$percent = round($ele*100/$total, 2);
		?><tr<?if($size==$position || $position==$SETTINGS['ranked_pages']){?> class="bottom"<?}?>><td><?=$position;?></td><td><?=$key;?> - <a target="new" href="<?=$doc_root.$key;?>">Open Page</a></td><td><?=$ele;?></td><td><?=$percent;?>%</td></tr><?
	}
	$position++;
}
?>
</table>