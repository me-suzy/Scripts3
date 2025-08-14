<?php
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + weblog - logger for detailed information on visitors                               
// + Initially written by Daniel Sokoll 2000 - http://www.sirsocke.de
// +
// + Release v2.0.0:	05.01.2005 - Daniel Sokoll
// + Release v3.0.0:	13.04.2005 - Daniel Sokoll
// + Last Changes:	26.08.2005 - Daniel Sokoll v3.4.1
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include_once("config/statistics.conf.php");

// --- first run actions ---
if($check_for_files) {
  if (! file_exists($vst_file)) {
    $vst_fp = fopen($vst_file, 'w+') or die("Can't open $vst_file\n");
    flock($vst_fp, 2);
    fwrite($vst_fp, '0.0.0.0|0');
    fclose($vst_fp);
  }

  if (! file_exists($cnt_file)) {
    $cnt_fp = fopen($cnt_file, 'w+') or die("Can't open $cnt_file\n");
    flock($cnt_fp, 2);
    fwrite($cnt_fp, '0');
    fclose($cnt_fp);
  }

  if (! file_exists($log_file)) {
    $log_fp = fopen($log_file, 'w+') or die("Can't open $log_file\n");
    flock($log_fp, 2);
    fwrite($log_fp, '');
    fclose($log_fp);
  }
}

// --- functions ----------------------------------------------------------------------------
function isReload() {
  global $statistics_dir, $vst_file, $anti_reload_period;

  $lines = file($vst_file);
  $vst_fp = fopen($vst_file, 'w') or die("Can't open $vst_file\n");
  flock($vst_fp, 2);
  $tm_now = time();
  $ip_now = getenv('REMOTE_ADDR');
  $j=0;

  for($i=0;$i<sizeof($lines);$i++) {
    $vst = explode("|", $lines[$i]);
    $ip_last = $vst[0];
    $tm_last = trim($vst[1]);
    $min_diff = ($tm_now-$tm_last)/60;
    if($min_diff<=$anti_reload_period) {
      $newlines[$j] = $lines[$i];
      $j++;
      if($ip_now==$ip_last) $is_reload=true;
    }
  }
  if(isset($newlines)) {
    $content = implode("",$newlines);
    fwrite($vst_fp, $content);
  }
  fclose($vst_fp);

  return((isset($is_reload)?1:0));
}

// --- main ---------------------------------------------------------------------------------
// --- check if it's a pagereload ---
if (! isReload()) {
  // --- write visitor file ---
  $vst_fp = fopen($vst_file, 'a') or die("Can't open $vst_file\n");
  flock($vst_fp, 2);
  $tm = time();
  fwrite($vst_fp, getenv('REMOTE_ADDR')."|$tm\n");
  fclose($vst_fp);

  // --- write counter file ---
  $cnt_fp = fopen($cnt_file, 'r+') or die("Can't open $cnt_file\n");
  flock($cnt_fp, 2);
  $cnt = fread($cnt_fp, filesize($cnt_file));
  $cnt = trim($cnt);
  $cnt++;
  rewind($cnt_fp);
  fwrite($cnt_fp, $cnt);
  fclose($cnt_fp);

  // --- backup log file ---
  copy($log_file,$bak_file);

  // --- generate log entry ---
  // --- date / time ---
  $log_time = date("Y-m-d H:i:s");

  // --- IP / Host ---
  if ($log_ip = getenv('REMOTE_ADDR')) {   
    $log_host = gethostbyaddr($log_ip);
    if(strstr($log_host, "|")) str_replace("|","!",$log_host);
  } else { 
    $log_ip = "";
    $log_host = "";
  }

  // --- Agent ---
  if ($log_agent = getenv('HTTP_USER_AGENT')) {
    // --- short the agent for compatibility --- 
    if(strlen($log_agent)>90) $log_agent = substr($log_agent,0,86)."...";
    if(strstr($log_agent, "|")) str_replace("|","!",$log_agent);
  } else $log_agent = "";

  // --- referer ---
  $log_referer = (isset($_GET["ref"]) && $_GET["ref"] != ""?$_GET["ref"]:"");
    // --- short the referer for compatibility --- 
  if(strlen($log_referer)>90) $log_referer = substr($log_referer,0,86)."...";
  if(strstr($log_referer, "|")) str_replace("|","!",$log_referer);

  // --- language ---
  if ($log_language = getenv('HTTP_ACCEPT_LANGUAGE')) {
    // --- short the language for compatibility ---
    $tmp = explode(";", $log_language);
    $tmp = explode(",", $tmp[0]);
    if(strstr($tmp[0],"-")) {
      $tmp = explode("-", $tmp[0]);
      if($tmp[0] != $tmp[1]) $tmp[0] = implode("-",$tmp);
    }
    $log_language = $tmp[0];
    if(strstr($log_language, "|")) str_replace("|","!",$log_language);
  } else $log_language = "";

  // --- forwarder ---
  $log_forwarder = (getenv('HTTP_X_FORWARDED_FOR')?getenv('HTTP_X_FORWARDED_FOR'):"");
  if(strstr($log_forwarder, "|")) str_replace("|","!",$log_forwarder);

  // --- screen size ---
  if(isset($_GET["sh"]) && ($_GET["sh"] && !is_nan($_GET["sh"]) && $_GET["sh"] < 9999) && isset($_GET["sw"]) && ($_GET["sw"] && !is_nan($_GET["sw"]) && $_GET["sw"] < 9999)) {
    $log_screen = $_GET["sw"]."X".$_GET["sh"];
    if(strstr($log_screen, "|")) str_replace("|","!",$log_screen);
  } else $log_screen = "";

  // --- screen colordepth ---
  if(isset($_GET["sc"]) && ($_GET["sc"] && !is_nan($_GET["sc"]))) $log_color = $_GET["sc"];
  else $log_color = "";
  if(strstr($log_color, "|")) str_replace("|","!",$log_color);

  // --- write log file ---
  $logsize = filesize($log_file);
  $logString = ($logsize==0?"":"\n").$log_time."|".$log_ip."|".$log_host."|".$log_agent."|".$log_referer."|".$log_language."|".$log_forwarder."|".$log_screen."|".$log_color;
  $log_fp = fopen($log_file, 'a') or die("Can't open $log_file\n");
  flock($log_fp, 2);
  fwrite($log_fp, $logString);
  fclose($log_fp);
}
?>