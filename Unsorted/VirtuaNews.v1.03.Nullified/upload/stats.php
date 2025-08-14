<?php

/*======================================================================*\
|| #################################################################### ||
||  Program Name         : VirtuaNews Pro                                 
||  Release Version      : 1.0.3                                          
||  Program Author       : VirtuaSystems                                  
||  Supplied by          : Ravish                                         
||  Nullified by         : WTN Team                                       
||  Distribution         : via WebForum, ForumRU and associated file dumps
|| #################################################################### ||
\*======================================================================*/

require('global.php');

if (!$allowstats | !$dositestats) {
  standarderror('stats_disabled');
}

$navbar = makenavbar('Stats');

$getstats = query('SELECT name,type,value FROM news_useragent ORDER BY type,value DESC');

while ($stats = fetch_array($getstats)) {

  $user_count = $stats['value'];
  $percent = round(($stats['value'] / $uniquestotal) * 100);

  if ($stats['type'] == 'browser') {
    $browser_count ++;
    $browser_name = $stats['name'];

    eval("\$browser_info .= \"".returnpagebit('stats_browser_row')."\";");
  } elseif ($stats['type'] == "os") {
    $os_count ++;
    $os_name = $stats['name'];

    eval("\$os_info .= \"".returnpagebit('stats_os_row')."\";");
  }
}

include('static/sub_pages/stats_' . $pagesetid . '.php');

/*======================================================================*\
|| ####################################################################
|| # File: stats.php
|| ####################################################################
\*======================================================================*/

?>