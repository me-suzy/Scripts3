<?php
//Written by Matt Toigo

//archive.php can only be run when included from index.php
if(!preg_match('/index.php/', $_SERVER[SCRIPT_FILENAME]))
	die('You do not have access to this page.');

//Deals with variables used on summary page where this file is included
$total_hits = 0;
$total_visits = 0;

$pages = array();
$browsers = array();
$os = array();
$robots = array();
$se = array();
$query = array();
$hits_days = array();
$visits_days = array();
$domain = array();
$hits_hour = array();
$visits_hour = array();

$logfile = fopen($SETTINGS['logfile'], 'r');

//Compensate for time differences
$sys_time = new_stamp(date('Y-m-d H:i:s'), $SETTINGS['offset_hours'], $SETTINGS['offset_minutes']);

//Reads logfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);
	
	if($ld[0]!=NULL)
	{
		//LEAVING OUT TIME DIFFERENCE
		
		//Find all time highest hits per hour
		//Creates parallel arrays of hits and visits with dates as the keys
		$hour_key = date('YmdH', strtotime($ld[0]));
		$hits_hour[$hour_key]++;
		if($ld[5]==1)
		{
			$visits_hour[$hour_key]++;
		}
		
		//Creates array where page name is key and hitcount is element
		$pages[$ld[4]]++;
	
		//Find Browser/OS Data
		if(!find_robot($ld[1], $se_def))
		{
			$browser_key = find_browser($ld[1], $browser_def);
			$browsers[$browser_key]++;
			
			$os_key = find_os($ld[1], $os_def);
			$os[$os_key]++;
		}
		
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
		
		//Find Refferal Data
		$domain_key = get_ref_domain($ld[3]);
		if($domain_key && count($domain)<=$SETTINGS['ref_arch_ammount'])
		{
			$domain[$domain_key]++;
		}
		
		//Creates parallel arrays of hits and visits with dates as the keys
		$day_key = date('Y-m-d', strtotime($ld[0]));
		$hits_days[$day_key]++;
		if($ld[5]==1)
		{
			$visits_days[$day_key]++;
		}
	
		//Find first hit
		if($total_hits==0)
			$start_time = $ld[0];
		
		//Gets Alltime Data
		if($ld[5]==1)
			$total_visits++;
		$total_hits++;
	}
}
fclose($logfile);

//Find highest hits and visitors per hour
$high_hits_hour = find_high($hits_hour);
$high_visits_hour = find_high($visits_hour);


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
			//Set Total Hits and Visits
			if($ld[0]=='TOTAL_HITS')
				$total_hits = $total_hits + $ld[1];
			if($ld[0]=='TOTAL_VISITS')
				$total_visits = $total_visits + $ld[1];	
			if($ld[0]=='START_TIME')
				$start_time = $ld[1];
			if($ld[0]=='HIGH_HITS_HOUR' && $ld[1]>$high_hits_hour)
				$high_hits_hour = $ld[1];
			if($ld[0]=='HIGH_VISITS_HOUR' && $ld[1]>$high_visits_hour)
				$high_visits_hour = $ld[1];
				
			//Fixes bug with $start_time having an extra line ending
			$start_time = preg_replace('/\n/', '', $start_time);
				
			//GET PAGE DATA
			//Stop Looking at pages when we hit the next section
			if($ld[0]=='BROWSERS')
				$inpages = FALSE;
			//Are we looking at pages yet?
			if($ld[0]=='PAGES')
				$inpages = TRUE;
			elseif($inpages)
				$pages[$ld[0]] = $pages[$ld[0]] + $ld[1];
				
			//FIND BROWSER STATS
			//Are we still looking at browsers
			if($ld[0]=='OS')
				$inbrow = FALSE;
			//Are we looking at browsers yet?
			if($ld[0]=='BROWSERS')
				$inbrow = TRUE;
			elseif($inbrow)
				$browsers[$ld[0]] = $browsers[$ld[0]] + $ld[1];
			
			//FIND OS STATS
			//Are we still looking at OS
			if($ld[0]=='ROBOTS')
				$inos = FALSE;
			//Are we looking at OS yet?
			if($ld[0]=='OS')
				$inos = TRUE;
			elseif($inos)
				$os[$ld[0]] = $os[$ld[0]] + $ld[1];
				
			//FIND ROBOT DATA
			//Stop Looking at robots when we hit the next section
			if($ld[0]=='SEARCHENGINES')
				$inrobots = FALSE;
			//Are we looking at robots yet?
			if($ld[0]=='ROBOTS')
				$inrobots = TRUE;
			elseif($inrobots)
				$robots[$ld[0]] = $robots[$ld[0]] + $ld[1];
				
			//FIND SEARCH ENGINE REFFERALS
			//Stop Looking at search engines when we hit the next section
			if($ld[0]=='SEARCHQUERIES')
				$inengines = FALSE;
			//Are we looking at search engines yet?
			if($ld[0]=='SEARCHENGINES')
				$inengines = TRUE;
			elseif($inengines)
				$se[$ld[0]] = $se[$ld[0]] + $ld[1];
			
			//FIND SEARCH QUERY DATA
			//Stop Looking at search queries when we hit the next section
			if($ld[0]=='REFFERERS')
				$inquery = FALSE;
			//Are we looking at queries yet?
			if($ld[0]=='SEARCHQUERIES')
				$inquery = TRUE;
			elseif($inquery)//Figure out hits in last day/week/month
				$query[$ld[0]] = $query[$ld[0]] + $ld[1];
				
			//FIND REFFERER DATA
			//Stop Looking at refferers when we hit the next section
			if($ld[0]=='HITS')
				$inref = FALSE;
			//Are we looking at refferers yet?
			if($ld[0]=='REFFERERS')
				$inref = TRUE;
			elseif($inref)
				$domain[$ld[0]] = $domain[$ld[0]] + $ld[1];
				
			//FIND HITS/VISITS FOR EACH DAY
			if($ld[0]=='HITS')
				$inhits = TRUE;
			elseif($inhits)
			{
				$day_key = date('Y-m-d', strtotime($ld[0]));
				$hits_days[$day_key] = $hits_days[$day_key] + $ld[1];
				$visits_days[$day_key] = $visits_days[$day_key] + $ld[2];
			}
		}
	}
}
fclose($archivefile);



$resource = fopen('arch.txt', 'w');

$nl = "\n";

$write_string = 'ARCH_DATE|'.date('YmdHi').$nl;

$write_string .= 'TOTAL_HITS|'.$total_hits.$nl;
$write_string .= 'TOTAL_VISITS|'.$total_visits.$nl;
$write_string .= 'HIGH_HITS_HOUR|'.$high_hits_hour.$nl;
$write_string .= 'HIGH_VISITS_HOUR|'.$high_visits_hour.$nl;
$write_string .= 'START_TIME|'.$start_time.$nl;
$write_string .= 'PAGES|'.$nl;

foreach($pages as $name=>$hits)
	$write_string .= $name.'|'.$hits.$nl;

$write_string .= 'BROWSERS|'.$nl;
foreach($browsers as $name=>$hits)
	$write_string .= $name.'|'.$hits.$nl;
	
$write_string .= 'OS|'.$nl;
foreach($os as $name=>$hits)
	$write_string .= $name.'|'.$hits.$nl;
	
$write_string .= 'ROBOTS|'.$nl;
foreach($robots as $name=>$hits)
	$write_string .= $name.'|'.$hits.$nl;
	
$write_string .= 'SEARCHENGINES|'.$nl;
foreach($se as $name=>$hits)
	$write_string .= $name.'|'.$hits.$nl;
	
$write_string .= 'SEARCHQUERIES|'.$nl;
foreach($query as $name=>$hits)
	$write_string .= $name.'|'.$hits.$nl;
	
$write_string .= 'REFFERERS|'.$nl;
foreach($domain as $name=>$hits)
	$write_string .= $name.'|'.$hits.$nl;

$write_string .= 'HITS|'.$nl;
foreach($hits_days as $day=>$number)
	$write_string .= $day.'|'.$number.'|'.$visits_days[$day].$nl;

fwrite($resource, $write_string);
fclose($resource);
?>