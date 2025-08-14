<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + ip2country.php - identify country from ip for showlog
// +
// + i2country function was written by http://www.php.net developers ,
// + modified (fixed) by  Bakr Alsharif (http://SysTurn.com) (opening April 2005),
// + and completely recoded and fastened for weblog / showlog by Daniel Sokoll 
// +
// + ip2country uses the IP-to-Country Database provided by WebHosting.Info
// + (http://www.webhosting.info), available from http://ip-to-country.webhosting.info."
// +
// + Creation:		28.03.2005 - Daniel Sokoll (http://www.sirsocke.de)
// + Last Update:	26.04.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$ip2c_version = "1.6.4";

// --- read database ------------------------------------------------------------------------------
if(!(isset($show) && $show=="info")) { // --- do not if $show=="info"
  if(file_exists($ip2c_db)) {
    $db_fp = fopen($ip2c_db,"r");
    for ($ip2c_db_cnt = 0; ! feof($db_fp); $ip2c_db_cnt++) {
      $ip2c_db_data[$ip2c_db_cnt] = fgets($db_fp, 256);
    }
    fclose($db_fp);
    $i2c_cnt = 0;
  } else {
    $show = "error";
    $error_string[$ip2c_db] = $lang["error"]["not_found"];
  }
}

// --- function ip2country ------------------------------------------------------------------------
// - input:	IP
// - global:	$unknown_text
// - output:	country-code
function ip2country($ipnum) {
  global $ip2c_db_cnt, $ip2c_db_data, $unknown_text, $i2c_cnt;

  if(trim($ipnum)=="") return $unknown_text;

  $ip = (float) sprintf("%u", ip2long($ipnum));
  $country = $unknown_text;
  $search_start = 0;
  $search_end = $ip2c_db_cnt;
  $search_size = $ip2c_db_cnt;

  $cnt = 0;
  while($search_size > 3) {
    $search_split = ($search_start+intval($search_size/2));
    if($ip < substr($ip2c_db_data[$search_split], 0, 10)) {
      $search_end = $search_split;
    } else {
      $search_start = $search_split;
    }
    $search_size = ($search_end-$search_start);
    $cnt++;
  }

  $range_start = 0; $range_end = 0;
  for($j=$search_start;$j<$search_end;$j++) {
    $record = $ip2c_db_data[$j];
    $range_start = substr($record, 0, 10);
    $range_end = substr($record, 10, 10);
    $cnt++;
    if(($range_start <= $ip && $range_end >= $ip)) {
      $country = substr($record, 20, 2);
      break;
    }
  }
  $i2c_cnt+=$cnt; // -- benchmarking

  return $country;
} ?>