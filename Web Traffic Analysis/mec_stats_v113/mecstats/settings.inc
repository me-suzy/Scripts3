<?
//Written by Matt Toigo

//SETTINGS
$SETTINGS = array();
$SETTINGS['exclude'] = array();

//Name of directory that MEC Stats is running in NO SLASHES
$SETTINGS['dir_name'] = 'mecstats';

//Time offset default 0,0
//Uncomment the line below to see the current time on your server
//echo(date('H:i A'));
//Used to compensate for a difference in your timezone and your servers timezone
//Use + and negative integers
//If the displayed times are one hour fast then set $SETTINGS['offset_hours'] to -1
//If the displayed times are one hour slow then set $SETTINGS['offset_hours'] to 1
$SETTINGS['offset_hours'] = 0;
$SETTINGS['offset_minutes'] = 0;

//Name of logfile - default log.txt
$SETTINGS['logfile'] = 'log.txt';

//Number of visitors to display in latest visitors
$SETTINGS['latest'] = 30;

//Exclude pages with the follwing patterns in the url from Page Rankings
//Useful if you have a search or forums on your site and don't want all searches/pages to appear as ranked pages
//Copy the line below, remove the two slashes, replace 'term' with text found in a url that you wouldn't want to show up.
//$SETTINGS['exclude'][] = 'term';

//Number of pages to display on Page Rankings - default 100
$SETTINGS['ranked_pages'] = 30;

//Number of domains to display on refferers page - default 30
$SETTINGS['ref_num'] = 30;

//Admin IP
//This is your ip address, if you don't want the stats to count you when you visit your site enter your IP address
$SETTINGS['admin_ip'] = '';

//Trends Scale
//Used to set the width for the trends graphs - default 350
$SETTINGS['trend_scale'] = 350;

//Browser/OS Scale
//Used to set the width for the browser and os graphs - default 300
$SETTINGS['brow_os_scale'] = 300;

//Refferer Scale
//Used to set the width for the refferer graph - default 300
$SETTINGS['ref_scale'] = 300;

//Search Scale
//Used to set the width for the search graphs
$SETTINGS['search_scale'] = 300;

//ARCHIVE SETTINGS
//Automatically Archive Log Files, default = TRUE;
$SETTINGS['auto_archive'] = TRUE;

//Auto Archive Size Limit, default 500000
$SETTINGS['archive_limit'] = 500000;

//Number of refferers to archive, default = top 100
$SETTINGS['ref_arch_ammount'] = 100;
?>
