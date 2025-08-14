<h1>Search Engine Data</h1>

<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

$robots = array();
$se = array();
$query = array();
$mnogo = array();

//Reads through logfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);
	
	//Creates Robot array
	if(find_robot($ld[1], $se_def))
	{
		$robots_key = find_robot($ld[1], $se_def);
		$robots[$robots_key]++;
		$bot_total++;
	}
	
	//Gets Search Engine Referers
	if($ld[5]==1 && $ld[3]!='' && find_se($ld[3], $se_def))
	{
		$se_key = find_se($ld[3], $se_def);
		$se[$se_key]++;
		$se_total++;
		
		//Gets actual Search Engine Query
		if(find_query($se_key, $ld[3], $se_def))
		{
			$query_key = strtolower(find_query($se_key, $ld[3], $se_def));
			$query[$query_key]++;
		}
	}
	
	//Gets MNOGO Data
	if(is_mnogo($ld[4]))
	{
		$mnogo_key = is_mnogo($ld[4]);
		$mnogo[$mnogo_key]++;
		$mnogo_total++;
	}
	
	if($ld[5]==1)
		$visit_total++;
	$total++;
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
		
		//Set Total Hits and Visits
		if($ld[0]=='TOTAL_HITS')
			$total = $total + $ld[1];
		if($ld[0]=='TOTAL_VISITS')
			$visit_total = $visit_total + $ld[1];	
			
		//Stop Looking at robots when we hit the next section
		if($ld[0]=='SEARCHENGINES')
		{
			$inrobots = FALSE;
		}
		//Are we looking at robots yet?
		if($ld[0]=='ROBOTS')
		{
			$inrobots = TRUE;
		}
		elseif($inrobots)//Figure out hits in last day/week/month
		{
			$robots[$ld[0]] = $robots[$ld[0]] + $ld[1];
			$bot_total = $bot_total + $ld[1];
		}
		
		//Stop Looking at search engines when we hit the next section
		if($ld[0]=='SEARCHQUERIES')
		{
			$inengines = FALSE;
		}
		//Are we looking at robots yet?
		if($ld[0]=='SEARCHENGINES')
		{
			$inengines = TRUE;
		}
		elseif($inengines)//Figure out hits in last day/week/month
		{
			$se[$ld[0]] = $se[$ld[0]] + $ld[1];
			$se_total = $se_total + $ld[1];
		}
		
		//Stop Looking at search queries when we hit the next section
		if($ld[0]=='REFFERERS')
		{
			$inquery = FALSE;
		}
		//Are we looking at robots yet?
		if($ld[0]=='SEARCHQUERIES')
		{
			$inquery = TRUE;
		}
		elseif($inquery)//Figure out hits in last day/week/month
		{
			$query[$ld[0]] = $query[$ld[0]] + $ld[1];
		}
	}
}

arsort($robots);
arsort($se);
arsort($query);
arsort($mnogo);

//Fixes Devision by zero errors
if($total!='')
	$percent_bots = round($bot_total*100/$total, 1);
else
	$percent_bots = 0;
	
if($visit_total!='')
	$percent_se_ref = round($se_total*100/$visit_total, 1);
else
	$percent_se_ref = 0;
?>

<b>Search Engine Referals</b><br>
Percent of Visitors from Search Engines: <?=$percent_se_ref;?>%
<br><br>

<table class="box" cellspacing="0" cellpadding="5">
<tr class="head"><td>Search Engine Referal Data</td></tr>
<tr><td>


<?
$mp = find_mp($se, $SETTINGS['search_scale']);

foreach($se as $key=>$ele)
{
	$percent = round($ele*100/$se_total, 1);
	
	$width = $ele*$mp;
	$alt_width = 300-$width;
	
	echo('<table class="main"><tr><td width="120"><a href="'.$se_def[$key]->url.'">'.$key.'</a></td><td width="80">'.$ele.'</td><td class="bar" width="'.$width.'"></td><td width="'.$alt_width.'">'.$percent.'%</td></tr></table>');
}
?>
</td></tr></table>
<br><br>
<b>Search Engine Robots</b><br>
Number of Hits from Robots: <?=$bot_total;?><br>
Percent of all Website Hits from Robots: <?=$percent_bots;?>%<br><br>

<table class="box" cellspacing="0" cellpadding="5">
<tr class="head"><td>Robot Data</td></tr>
<tr><td>

<?
$mp = find_mp($robots, $SETTINGS['search_scale']);

foreach($robots as $key=>$ele)
{
	$percent = round($ele*100/$bot_total, 1);
	
	$width = $ele*$mp;
	$alt_width = 300-$width;
	
	echo('<table class="main"><tr><td width="160"><a href="'.$se_def[$key]->bot_url.'">'.$key.'</a></td><td width="40">'.$ele.'</td><td class="bar" width="'.$width.'"></td><td width="'.$alt_width.'">'.$percent.'%</td></tr></table>');
}
?>
</td></tr></table>
<br>
<b>Search Terms</b>
<br><br>
<table class="box" cellspacing="0" cellpadding="5">
<tr class="head"><td>Search Terms Data</td></tr>
<tr><td>
<?
$mp = find_mp($query, $SETTINGS['search_scale']);

foreach($query as $key=>$ele)
{
	$percent = round($ele*100/$se_total, 1);
	
	$width = $ele*$mp;
	$alt_width = 300-$width;
	
	echo('<table class="main"><tr><td width="160" rowspan="2">'.$key.'</td><td width="40">'.$ele.'</td><td height="10" class="bar" width="'.$width.'"></td><td width="'.$alt_width.'">'.$percent.'%</td></tr><tr><td colspan="2"></table>');
}
?>
</td></tr>
</table>