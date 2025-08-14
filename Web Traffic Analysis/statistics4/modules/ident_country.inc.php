<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + ident_country.inc.php - identify country from weblog-logfile for showlog
// +
// + Creation:		24.03.2005 - Daniel Sokoll
// + Last Update:	04.05.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$ident_country_version = "1.0.6";
$ident_country_files = "countries/";			// path to flags
$ident_language_files = "languages/";		// path to tranlations

$ident_country_lang_file = $ident_language_files."c_".$lang_code.".dat";
if (!file_exists($ident_country_lang_file)) $ident_country_lang_file = $ident_language_files."c_en.dat";

if(file_exists($ident_country_lang_file)) {
  $file_fp = fopen($ident_country_lang_file, 'r');
  for ($i = 0; ! feof($file_fp); $i++) {
    $line = fgets($file_fp, 256);
    $tmp = explode(";",$line);
    $line = trim($tmp[0]);
    if($line!="") {
      $tmp = explode("=",$line);
      $ident_country_name[trim($tmp[0])] = trim($tmp[1]);
    }
  }
} else {
  $show = "error";
  $error_string[$ident_country_lang_file] = $lang["error"]["not_found"];
}

$ident_country_flag_file = $ident_country_files."c_flags.dat";
if (file_exists($ident_country_flag_file)) {
  $file_fp = fopen($ident_country_flag_file, 'r');
  for ($i = 0; ! feof($file_fp); $i++) {
    $line = fgets($file_fp, 256);
    $tmp = explode(";",$line);
    $line = trim($tmp[0]);
    if($line!="") {
      $tmp = explode("=",$line);
      $ident_country_flag[trim($tmp[0])] = trim($tmp[1]);
    }
  }
} else {
  $show = "error";
  $error_string[$ident_country_flag_file] = $lang["error"]["not_found"];
}

function ident_country($work) {
  global $ident_country_name, $ident_country_flag;

  $data["text"] = (isset($ident_country_name[trim($work)])?$ident_country_name[trim($work)]:0);
  $data["flag"] = (isset($ident_country_flag[trim($work)])?$ident_country_flag[trim($work)]:0);
  $data[0] = $data["text"];
  $data[1] = $data["flag"];
  
  return($data);
} ?>