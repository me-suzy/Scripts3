<?php 
require 'start.php';
require $settings->admindir .'/admincommonfuncs.php';
if ($HTTP_SERVER_VARS['QUERY_STRING'] != '') $querystring = '&'. $HTTP_SERVER_VARS['QUERY_STRING'];
$url = $settings->dirurl .'/index.php?custom=yes&TID=custom-javascript%20export&headerfooter=no'. $querystring;
$toplist = geturl($url);
$toplist = addslashes($toplist);
$toplist = str_replace("'", "&#39;", $toplist);
$toplist = str_replace('<!-- BEGIN TOPLIST 1 -->', '', $toplist);
$toplist = str_replace('<!-- END TOPLIST 1 -->', '', $toplist);
$toplist = str_replace("\r\n", "", $toplist);
echo "document.write('". $toplist ."');\n"
?>