<?
//Written by Matt Toigo

require_once('settings.inc');

$doc_root = '..';

//Create new timestamp compensating for difference in timezone, takes 0000-00-00 00:00:00
function new_stamp($stamp, $hours, $minutes)
{
	$new = date('Y-m-d H:i:s', mktime(date('H', strtotime($stamp))+$hours,date('i', strtotime($stamp))+$minutes,date('s', strtotime($stamp)),date('n', strtotime($stamp)),date('j', strtotime($stamp)),date('Y', strtotime($stamp))));
	return $new;
}

//Finds graph multiplier
function find_mp($data, $scale)
{
	//Finds highest value
	$high_val = 0;
	foreach($data as $ele)
		if($ele>$high_val)
			$high_val = $ele;
			
		if($high_val>0)
			$mp = ($scale-15)/$high_val;
		else
			$mp = 0;
	
	return $mp;
}

//Get base domain of refferer
function get_ref_domain($ref)
{
	$x = FALSE;

	$length = strlen($ref);
	
	if(!preg_match("/$_SERVER[SERVER_NAME]/", $ref))
	{
	
		if(preg_match('/http:\/\//', $ref))
			$x = substr($ref, 7, $length);
			
		if(preg_match('/http:\/\/www./', $ref))
			$x = substr($ref, 11, $length);
			
		if(preg_match('/\//', $x))
			$end = strpos($x, '/');
		else
			$end = strlen($x); 
		
		$x = substr($x, 0, $end);
	}
	return $x;
}
		
//Exclude urls
function exclude($page, $exclude)
{
	$x = TRUE;
	foreach($exclude as $ele)
		if(preg_match("/$ele/i", $page))
			$x = FALSE;
	return $x;
}

//Finds the highest value in an array
function find_high($data)
{
	$high_value = 0;
	foreach($data as $ele)
	{
		if($ele > $high_value)
			$high_value = $ele;
	}
	return $high_value;
}

//Rules for finding browser/os
require_once('agent_defs.inc');

function find_browser($agent, $browser_def)
{
	$browser = 'Other';

	foreach($browser_def as $key=>$ele)
		if(preg_match("/$ele/i", $agent))
			$browser = $key;
			
	if($agent=='')
		$browser = 'Unknown';
		
	return $browser;
}

function find_os($agent, $os_def)
{
	$os = 'Other';
	
	foreach($os_def as $key=>$ele)
		if(preg_match("/$ele/i", $agent))
			$os = $key;
			
	if($agent=='')
		$os = 'Unknown';
			
	return $os;
}

function find_robot($agent, $se_def)
{
		$robot = FALSE;
		
		foreach($se_def as $key=>$ele)
			if($ele->check_bot($agent))
				$robot = $ele->name;
				
		return $robot;
}

function find_se($ref, $se_def)
{
	$se = FALSE;
		
	foreach($se_def as $key=>$ele)
		if($ele->check_ref($ref))
			$se = $ele->name;
	
	return $se;
}

//Finds a search query
function find_query($se_name, $ref, $se_def)
{
	$query = '';
	
	$query = $se_def[$se_name]->check_query($ref);
		
	return $query;
}
			

//SEARCH ENGINE CLASS, STORES ALL DATA FOR A PARTICULAR SEARCH ENGINE
class SE {
	var $name;
	var $agent_pattern;
	var $url;
	var $bot_url;
	var $ss_start;
	var $ref_pattern;
	
	//Assigns Values
	function SE($x_name, $x_agent_pattern, $x_ref_pattern, $x_url, $x_bot_url, $x_ss_start)
	{
		$this->name = $x_name;
		$this->agent_pattern = $x_agent_pattern;
		$this->ref_pattern = $x_ref_pattern;
		$this->url = $x_url;
		$this->bot_url = $x_bot_url;
		$this->ss_start = $x_ss_start;
	}
	
	//Checks for this search engines bot
	function check_bot($agent)
	{
		if($this->agent_pattern!='')
			if(preg_match("/$this->agent_pattern/i", $agent))
				return $this->name;
			else
				return FALSE;
	}
	
	//Checks for search engine referal
	function check_ref($ref)
	{
		if($this->ref_pattern!='')
			if(preg_match("/$this->ref_pattern/i", $ref))
				return $this->name;
			else
				return FALSE;
	}
	
	//Get query - revise and shorten logic
	function check_query($ref)
	{
		$query = FALSE;
		if($this->ss_start!='' && preg_match("/$this->ss_start/i", $ref))
		{
				$start = strpos($ref, $this->ss_start)+strlen($this->ss_start);
				
				//Find next &
				$next_param = strlen($ref); //end of url if no &
				for($c=$start+1;$c<strlen($ref);$c++)
				{
					if($ref{$c}=='&')
					{
						$next_param = $c;
						break;
					}
				}
						
				$terms = substr($ref, $start, ($next_param-$start));

				$query = stripslashes(urldecode($terms));

		}
		return $query;
	}
	
}

//MNOGO SPECIFIC FUNCTIONS
function is_mnogo($page)
{
	$query = FALSE;
	$flag = 'q=';
	if(preg_match('/section=search/i', $page) && preg_match('/tag/i', $page))
	{
		$start = strpos($page, $flag)+strlen($flag);
		$next_param = strlen($page); //end of url if no &
		for($c=$start+1;$c<strlen($page);$c++)
		{
			if($page{$c}=='&')
			{
				$next_param = $c;
				break;
			}
		}
		
		$terms = substr($page, $start, ($next_param-$start));

		$query = stripslashes(urldecode($terms));
	}
	return $query;
}
?>