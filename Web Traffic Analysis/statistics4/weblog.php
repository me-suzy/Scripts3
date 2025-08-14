<?php
// --------------------------------------------------------------------------------------------
// - weblog - logger for detailed information on visitors
// - Initially written by Daniel Sokoll 2000 - http://www.sirsocke.de
// -
// - Release v2.0.0:	05.01.2005 - Daniel Sokoll
// - Release v4.0.0:	06.09.2005 - Daniel Sokoll
// - Last Changes:	27.09.2005 - Daniel Sokoll v4.2.1
// -
// - This program is free software; you can redistribute it and/or modify it under
// - the terms of the GNU General Public License as published by the Free Software
// - Foundation; either version 2 of the License, or (at your option) any later version.
// --------------------------------------------------------------------------------------------
// --- include global config ---
include_once("config/statistics.conf.php");
if(!isset($_GET["res"])) {
  $vst_file = $weblog_showlog_dir.$vst_file;
  $cnt_file = $weblog_showlog_dir.$cnt_file;
  $log_file = $weblog_showlog_dir.$log_file;
  $bak_file = $weblog_showlog_dir.$bak_file;
}
$tmp_log_file = $log_file.".tmp";

// --- check if files are present -----------------------------------------------------------
if($weblog_check_for_files && !isset($_GET["res"])) {
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
  if (! file_exists($tmp_log_file)) {
    $log_fp = fopen($tmp_log_file, 'w+') or die("Can't open $tmp_log_file\n");
    flock($log_fp, 2);
    fwrite($log_fp, '');
    fclose($log_fp);
  }
}

// --- functions ----------------------------------------------------------------------------
function weblog_is_Reload() { // --- check for reload ---
  global $vst_file, $weblog_anti_reload;

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
    if($min_diff<=$weblog_anti_reload) {
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

function weblog_replace_weak_chars($text) { // --- replace bad characters ---
  if(strstr($text, "|")) $text = str_replace("|","!",$text);
  return $text;
}

function weblog_ignore_IP($client) { // --- check if IP should be ignored ---
  global $weblog_ignore_ip;
  if(!empty($weblog_ignore_ip)) {
    $ipmatch = explode(",", $weblog_ignore_ip);
    if (empty($ipmatch)) return false;
    for($i = count($ipmatch) - 1; $i >= 0; $i--) {
      $test = trim($ipmatch[$i]);
      if (substr($client, 0, strlen($test)) === $test) return true;
    }
    return false;
  } else return false;
}

// --- main ---------------------------------------------------------------------------------
if(!weblog_ignore_IP(getenv('REMOTE_ADDR'))) {
  if(!isset($_GET["res"])) {
    if (!weblog_is_Reload()) { // --- check if it's a pagereload ---
      // --- log first part of data ----------------------------------------------------------
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

      // --- generate log entry ---
      // --- date / time ---
      $log_time = date("Y-m-d H:i:s",mktime(date("H"), (date("i")+$weblog_time_offset), date("s"), date("m"), date("d"), date("Y")));

      // --- IP / Host ---
      if ($log_ip = getenv('REMOTE_ADDR')) {
        $log_host = weblog_replace_weak_chars(gethostbyaddr($log_ip));
      } else { 
        $log_ip = "";
        $log_host = "";
      }

      // --- Agent ---
      if ($log_agent = getenv('HTTP_USER_AGENT')) {
        $log_agent = weblog_replace_weak_chars($log_agent);
      } else $log_agent = "";

      // --- referer ---
      if ($log_referer = getenv('HTTP_REFERER')) {
        $log_referer = weblog_replace_weak_chars($log_referer);
      } else $log_referer = "";

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
        $log_language = weblog_replace_weak_chars($log_language);
      } else $log_language = "";

      // --- forwarder ---
      $log_forwarder = weblog_replace_weak_chars((getenv('HTTP_X_FORWARDED_FOR')?getenv('HTTP_X_FORWARDED_FOR'):""));

      // --- write temprary logfile ---
      $logsize = filesize($tmp_log_file);
      $logString = ($logsize==0?"":"\n").$log_time."|".$log_ip."|".$log_host."|".$log_agent."|".$log_referer."|".$log_language."|".$log_forwarder;
      $log_fp = fopen($tmp_log_file, 'a') or die("Can't open $tmp_log_file\n");
      flock($log_fp, 2);
      fwrite($log_fp, $logString);
      fclose($log_fp);

      // --- return resource-ID ---
      $weblog_id = base64_encode($log_time."|".$log_ip);
    } else $weblog_id = false;

  } else {
    // --- log second part of data -------------------------------------------------------------------------------------------
    $tmpsize = filesize($tmp_log_file);
    if($tmpsize > 0) {
      $tmp_entry = explode("|",base64_decode($_GET["res"]));
      if(!isset($tmp_entry[0]) || strlen($tmp_entry[0]) != 19) $tmp_entry[0] = "1999-09-09 19:09:09";
      if(!isset($tmp_entry[1]) || substr_count($tmp_entry[1],".") != 3) $tmp_entry[1] = "0.0.0.0";
      // --- backup log file ---
      copy($log_file,$bak_file);

      $logsize = filesize($log_file);
      // --- read remporary logdata ---
      $tmp_fp = fopen($tmp_log_file, 'r') or die("Can't open $tmp_log_file\n");
      flock($tmp_fp, 2);
      $log_fp = fopen($log_file, 'a') or die("Can't open $log_file\n");
      flock($log_fp, 2);

      for ($line_no = 0; ! feof($tmp_fp); $line_no++) {
        $tmp_data = trim(fgets($tmp_fp, 1024));
        $tmp_values = explode("|",$tmp_data);
        if($tmp_data!="") {
          if($tmp_entry[0] == $tmp_values[0] && $tmp_entry[1] == $tmp_values[1]) {
            // --- screen size ---
            if(isset($_GET["sh"]) && ($_GET["sh"] && !is_nan($_GET["sh"]) && $_GET["sh"] < 4000) && isset($_GET["sw"]) && ($_GET["sw"] && !is_nan($_GET["sw"]) && $_GET["sw"] < 4000)) {
              $log_screen = weblog_replace_weak_chars($_GET["sw"]."X".$_GET["sh"]);
            } else $log_screen = "";

            // --- screen colordepth ---
            if(isset($_GET["sc"]) && ($_GET["sc"] && !is_nan($_GET["sc"]))) $log_color = $_GET["sc"];
            else $log_color = "";
            $log_color = weblog_replace_weak_chars($log_color);
            $logString = $tmp_data."|".$log_screen."|".$log_color;
          } else $logString = $tmp_data."||";
          $logString = ($logsize==0?"":"\n").$logString;
          fwrite($log_fp, $logString);
        }
      }
      fclose($log_fp);
      fclose($tmp_fp);
      // --- finally reset temporay lofile-data ---
      $tmp_fp = fopen($tmp_log_file, 'w') or die("Can't open $tmp_log_file\n");
      fwrite($tmp_fp, '');
      fclose($tmp_fp);
    }
  }
} ?>