<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + create_ip2c_db.php - create database and index from ip-to-country.csv
// +
// + Creation:		28.03.2005 - Daniel Sokoll
// + Last Update:	20.08.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$input_file = "ip-to-country.csv";
$output_file = "ip2country.db";
$version_file = "ip2country.dat";
$version_data = date ("Y-m-d", filemtime($input_file));

// --- create ip2country-database and index ---
$output = "";
$old_tmp_num = "";
$index = "";
$counter = 1;
$split_cnt = 0;
$in_fp = fopen($input_file, 'r') or die("Can't open $input_file\n");
for ($log_no = 0; ! feof($in_fp); $log_no++) {
  $tmp_data = fgets($in_fp, 256);
  if(trim($tmp_data) != "") {
  $tmp_data = preg_replace("/\"/","",$tmp_data);
  $tmp_work = explode(",",$tmp_data);
  $tmp_len = strlen($tmp_work[0]);
  if($tmp_len == 8) {
    $tmp_num = substr($tmp_work[0],0,1);
    $tmp_work[0] = "00".$tmp_work[0];
  } elseif($tmp_len == 9) {
    $tmp_num = substr($tmp_work[0],0,2);
    $tmp_work[0] = "0".$tmp_work[0];
  } else {
    $tmp_num = substr($tmp_work[0],0,3);
  }
  while(strlen($tmp_work[1])<10) {
    $tmp_work[1] = "0".$tmp_work[1];
  }
  $output[$counter] = $tmp_work[0].$tmp_work[1].strtolower($tmp_work[2])."\n";
  $old_tmp_num = $tmp_num;
  }
  $counter++;
}
fclose($in_fp);

// --- add localhost ---
$output[$counter++] = "21307064332130706433lh\n"; // localhost 127.0.0.1

// --- sort output array ---
sort($output);

$output_new = "";
for($i=0;$i<$counter;$i++) {
  $output_new .= ($output[$i]!=""?$output[$i]:"");
}

// --- write ip2country-database ---
$out_fp = fopen($output_file, 'w+') or die("Can't open $output_file\n");
fwrite($out_fp, trim($output_new));
fclose($out_fp);

// --- write ip2country-version-file ---
$ver_fp = fopen($version_file, 'w+') or die("Can't open $version_file\n");
fwrite($ver_fp, trim($version_data));
fclose($ver_fp);

?>
<h1>database created</h1>