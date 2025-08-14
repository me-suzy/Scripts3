<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + ident_language.inc.php - identify language from weblog-logfile for showlog
// +
// + Creation:		25.04.2005- Daniel Sokoll
// + Last Update:	04.05.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$ident_language_version = "1.0.2";
$ident_country_files = "countries/";			// path to flags
$ident_language_files = "languages/";		// path to tranlations

$ident_language_lang_file = $ident_language_files."l_".$lang_code.".dat";
if(!file_exists($ident_language_lang_file)) $ident_language_lang_file = $ident_language_files."l_en.dat";

if (file_exists($ident_language_lang_file)) {
  $file_fp = fopen($ident_language_lang_file, 'r');
  for ($i = 0; ! feof($file_fp); $i++) {
    $line = fgets($file_fp, 256);
    if($line!="") {
      $tmp = explode("|",$line);
      $ident_language_name[trim($tmp[0])] = trim($tmp[2]);
      $ident_language_flag[trim($tmp[0])] = (isset($tmp[1]) && trim($tmp[1]) != ""?trim($tmp[1]):false);
    }
  }
} else {
  $show = "error";
  $error_string[$ident_language_lang_file] = $lang["error"]["not_found"];
}

// --- find additional language ----------------------------------------------------------------------------
// --- input paremeters: $agent
function find_language($agent) {
  $tmp_lang = "---";
  if(preg_match("/ \[([a-z]{2})\]/",$agent,$regs)) $tmp_lang = $regs[1];
  elseif(preg_match("/ ([a-z]{2}\-[a-z]{2})\;.*gecko/i",$agent,$regs)) $tmp_lang = strtolower($regs[1]);
  if($tmp_lang != "---") {
    if(strstr($tmp_lang,"-")) {
      $tmp_work = explode("-", $tmp_lang);
      if(trim($tmp_work[0]) == trim($tmp_work[1])) {
        $tmp_lang = $tmp_work[0];
      }
    }
  }
  return($tmp_lang);
}

// --- ident language ---------------------------------------------------------------------------------------
// --- input paremeters: $work: language-code
function ident_language($work) {
  global $ident_language_name, $ident_language_flag, $ident_country_flag, $unknown_text;

  $data["text"] = false;
  $data["flag"] = false;
  if(!isset($ident_language_name[trim($work)])) $work = substr($work,0,2);
  $data["text"] = (isset($ident_language_name[trim($work)])?$ident_language_name[trim($work)]:0);
  if(isset($ident_language_flag[trim($work)])) {
    $data["flag"] = (isset($ident_country_flag[$ident_language_flag[trim($work)]])?$ident_country_flag[$ident_language_flag[trim($work)]]:0);
    $data[0] = $data["text"];
    $data[1] = $data["flag"];
  }
  return($data);
} ?>