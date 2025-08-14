<?php
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + showlog - detailed visitorstatistics with PHP 4.2.0 or higher
// + no database is required at all
// + Initially written by Daniel Sokoll 2000 - http://www.sirsocke.de
// +
// + Release v4.0.0:	05.01.2005 - Daniel Sokoll
// + Last Changes:	30.09.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$bench_a = microtime();  // --- for benchmarking ---
$show = (isset($_GET["show"])?$_GET["show"]:false);

// --- configurations ----------------------------------------------------------------------------------
$login = array();
// --- get user configuration ---
include_once("config/statistics.conf.php");

// --- modul-definitions ---
$modul_ident_country = $modules_dir."ident_country.inc.php";
$modul_ident_language = $modules_dir."ident_language.inc.php";
$modul_ident_search = $modules_dir."ident_search.inc.php";
$modul_ident_os = $modules_dir."ident_os.inc.php";
$modul_ident_browser = $modules_dir."ident_browser.inc.php";
$modul_ident_bot = $modules_dir."ident_bot.inc.php";

// --- get languagespecific contexts ---
if (file_exists($lang_dir.$lang_code.".dat")) $lang = parse_ini_file($lang_dir.$lang_code.".dat",TRUE);
else { 
  $show = "error";
  $error_string[$lang_file] = "not found";  
}

// --- read template ----------------------------------------------------------------------------------
include_once($tpl_file);

// --- login / userrights .-----------------------------------------------------------------------------
if($login_require) {
  $id = (isset($_GET["id"])?$_GET["id"]:"");
  // --- try to detect breakin attempt ---
  if(isset($_GET["login_require"]) || isset($_POST["login_require"]) || isset($_COOKIE["login_require"]) || isset($_POST["id"]) || isset($_COOKIE["id"]) || isset($_GET["SHOWLOG"]) || isset($_POST["SHOWLOG"]) || isset($_COOKIE["SHOWLOG"])) {
    header("Location: ".$login_denied);
    exit;
  }
  // --- show loginscreen if required ---
  if($id == "" && $login_screen) {
    header("Location: login.php");
    exit;
  }
  // --- check rights ---
  $id = session_control();
  if(!isset($_SESSION["SHOWLOG"]) || !($_SESSION["SHOWLOG"] == "all" || $_SESSION["SHOWLOG"] == "view")) {
    header("Location: ".$login_denied);
    exit;
  }
}
// --- set adminrights ---
$admin = (!$login_require || (isset($_SESSION["SHOWLOG"]) && $_SESSION["SHOWLOG"] == "all")?true:false);

// --- start of function - definitions ----------------------------------------------------------------
// --- sort statistics in descending order ---
function sortall($a, $b) {
  global $statistic;
  if ($statistic[$a]>$statistic[$b]) return(-1);
  elseif ($statistic[$a]<$statistic[$b]) return(1);
  else {
    global $a,$b;
    if ($a>$b) return(-1);
    elseif ($a<$b) return(1);
    else return(0);
  }
}

// --- convert date from eu to us or from us to eu format ---
function convert_date($work,$lang_format) {
  if($lang_format == "eu") {
    if(strpos($work, "-")) {
      $tmp = explode("-", $work);
      $tmp = array_reverse($tmp);
      $work = implode($tmp,".");
    }
  } elseif($lang_format == "us") {
    if(strpos($work, ".")) {
      $tmp = explode(".",$work);
      $tmp = array_reverse($tmp);
      $work = implode($tmp,"-");
    }
  }
  return $work;
}

// --- show headline: border headline border ---
function show_header($header, $navi = false, $anchor = false, $jump_down = false, $print = false, $target = "NO_TARGET") { 
  global $color, $id, $pic, $lang, $login_require, $detail_filter_show_not;

  if($target == "NO_TARGET") $target = $_SERVER["PHP_SELF"]."?id=".$id;
  $jump_up = (is_numeric($anchor) && $anchor > 1?($anchor-1):"top");
  $jump_up_text = (is_numeric($anchor) && $anchor > 1?"prev":"top");
  if($anchor) echo "<a name=\"statistics_".$anchor."\"></a>";
  show_hr(); ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="SHOWlog statistics-title"><tr bgcolor="<?php echo $color["DEF"][2]; ?>">
<td align="left" valign="bottom" width="50"><?php if($print && !isset($_GET["print"])) { ?>
<a href="<?php echo $_SERVER["PHP_SELF"]."?show=".$print.($login_require?"&amp;id=".$id:""); ?>&amp;print=true" title="<?php echo $lang["headline"]["printview"]; ?>" target="_blank"
 onmouseover="window.status='<?php echo $lang["headline"]["printview"]; ?>'; return true;"
 onmouseout="window.status=''; return true;"><img src="<?php echo $pic["dir"]; ?>n_print.png" width="16" height="16" border="0" alt="<?php echo $lang["headline"]["printview"]; ?>" title="<?php echo $lang["headline"]["printview"]; ?>"></a>
<?php } else echo "&nbsp;"; ?></td>
<td align="center"><b><?php echo $header; ?></b></td>
<td align="center" valign="bottom" width="50"><?php
  if($navi && !isset($_GET["print"]) && !$detail_filter_show_not) { ?>
<a href="<?php echo $target; ?>" title="<?php echo $lang["default"]["back"]; ?>"
 onmouseover="window.status='<?php echo $lang["default"]["back"]; ?>'; return true;"
 onmouseout="window.status=''; return true;"><img src="<?php echo $pic["dir"]; ?>n_back.png" width="14" height="14" border="0" alt="<?php echo $lang["default"]["back"]; ?>" title="<?php echo $lang["default"]["back"]; ?>"></a>
<a href="#<?php echo "statistics_".$jump_up; ?>" title="<?php echo $lang["headline"][$jump_up_text]; ?>"
 onmouseover="window.status='<?php echo $lang["headline"][$jump_up_text]; ?>'; return true;"
 onmouseout="window.status=''; return true;"><img src="<?php echo $pic["dir"]; ?>n_up.png" width="14" height="14" border="0" alt="<?php echo $lang["headline"][$jump_up_text]; ?>" title="<?php echo $lang["headline"][$jump_up_text]; ?>"></a>
<a href="#<?php echo "statistics_".$jump_down; ?>" title="<?php echo $lang["headline"][($jump_down=="end"?"end":"next")]; ?>"
 onmouseover="window.status='<?php echo $lang["headline"][($jump_down=="end"?"end":"next")]; ?>'; return true;"
 onmouseout="window.status=''; return true;"><img src="<?php echo $pic["dir"]; ?>n_down.png" width="14" height="14" border="0" alt="<?php echo $lang["headline"][($jump_down=="end"?"end":"next")]; ?>" title="<?php echo $lang["headline"][($jump_down=="end"?"end":"next")]; ?>"></a>
<?php } else echo "&nbsp;"; ?></td>
</tr></table><?php
  show_hr();
}

// --- show backbutton ----
function back_button($target = "DEFAULT", $text = "DEFAULT", $logout = false) {
  global $lang,$id,$login_require,$login_screen, $leave;

  if(!isset($_GET["print"])) {
    if($target == "DEFAULT") $target = $_SERVER["PHP_SELF"];
    if($text == "DEFAULT") $text = $lang["default"]["back"];
    else $text = $lang["default"][$text];
    if(strstr($target,"javascript")) $logout = true;
    show_hr(); ?>
<table border="0" cellpadding="0" cellspacing="0" align="center" summary="SHOWlog backbutton">
<tr><td><form method="POST" action="<?php echo $target.($login_require && !$logout?(ereg("\?",$target)?"&amp;":"?")."id=".$id:""); ?>" name="backbutton">
<input type="submit" value="<?php echo $text; ?>" name="back" title="<?php echo $text; ?>"
 onmouseover="window.status='<?php echo $text; ?>'; return true;"
 onmouseout="window.status=''; return true;"></form></td></tr>
</table><?php
  }
}

// --- detailed  statistics with horizontal percent-bar ---
function show_detailed_stats($show, $explicit=false, $simple_view=false) {
  global $stat_details, $log_entries, $statistic, $family, $pic, $color, $unknown_text, $lang, $known_count, $do_not_show, $stat_key, $error_count, $modules_dir, $lang_code, $ident_country_name, $ident_country_flag, $id, $ident_country_files, $ident_language_name, $admin, $ident_search;

  $show_icon = (!$simple_view && ($show=="browser" || $show=="os")?true:false);
  $show_flag = ($show=="domain" || $show=="ip2c" || $show=="languages" || $show=="provider"?true:false);
  $show_logo = ($show=="search"?true:false);
  $stat_keys[$show] = array_keys($stat_details);
  $statistic = $stat_details;
  usort($stat_keys[$show], "sortall");
  $show_filter = (!isset($_GET["print"]) && !$simple_view && ($show=="browser" || $show=="os" || $show=="referer" || $show=="forward" || $show=="provider" || $show=="domain" || $show=="languages" || $show=="ip2c"|| ($show=="screen"))?true:false);
  if($simple_view) {
    if($show == "search" && !$explicit) $stat_title = $lang["default"][$show];
    else $stat_title = $lang[$show]["title_extra"];
  } else $stat_title = ($show == "screen"?$lang["screen"][($explicit?"title_detail":"title_family")]:$lang["default"][$show]);

  if($error_count!=0) echo $lang["error"]["logfile"].": ".$error_count; ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="SHOWlog statistic - outer table"><tr><td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<table border="0" cellspacing="1" cellpadding="2" class="tback"<?php echo ($do_not_show?"width=\"100%\"":""); ?> summary="SHOWlog statistic - inner table"><tr>
<td width="15"  class="default" bgcolor="<?php echo $color["DEF"][1]; ?>">&nbsp;</td>
<td class="head" width="<?php echo ($show_flag && $show!="provider"?"198\" colspan=\"2\"":"205\"").">".$stat_title; ?></td>
<?php if(!isset($_GET["print"]) && ($show_icon || $show_flag || $show_logo)) { ?><td width="<?php echo ($show_flag?"22":"15"); ?>" class="head">&nbsp;</td><?php } ?>
<td width="35" class="head" align="right" title="<?php echo $lang["userpass"]["count"]; ?>"><?php echo $lang["default"]["count"]; ?></td>
<?php if(! isset($do_not_show)) { ?><td width="195" class="head" align="center" valign="bottom" title="<?php echo $lang["headline"]["percent_bar"]; ?>"><table border="0" cellspacing="0" cellpadding="0" width="100%" summary="SHOWlog statistic - header table"><tr>
<td colspan="3"><img src="<?php echo $pic["fake"]; ?>" width="195" alt="" height="1"></td>
</tr><tr valign="bottom">
<td width="30%" align="right" class="head" style="font-size: 8px;">25%</td>
<td width="40%" align="center" class="head" style="font-size: 8px;">50%</td>
<td width="30%" align="left" class="head" style="font-size: 8px;">75%</td>
</tr></table></td>
<td width="35" class="head" align="right" title="<?php echo $lang["headline"]["percent"]." ".$lang["headline"]["percent_all"]; ?>">%</td>
<?php if(!$simple_view) { ?><td width="35" class="head" align="right" title="<?php echo $lang["headline"]["percent"]." ".$lang["headline"]["percent_wo"]; ?>">%</td><?php } ?>
<?php if($show_filter ) { ?>
<td width="12" class="head" align="right" title="<?php echo $lang["default"]["show_details"]; ?>">&nbsp;</td>
<?php } } ?>
</tr><?php
    $i = 0; 
    $hit_count = 0;
    $percent_count = 0;
    $percent_count_2 = 0;
    foreach ($stat_keys[$show] as $key) {
      // --- ident Browsericon ---
      if($show_icon) $tmp = $stat_key[$key]; 
      else $tmp = $key;
      if($simple_view) $percent = ($stat_details[$key]/$known_count*100);
      else $percent =  $stat_details[$key]/$log_entries*100;
      $known_percent = ($stat_details[$key]/$known_count*100);

      if(! isset($do_not_show)) { 
	$img_width_1 = round(($percent*2), 0);
	$img_width_2 = round((($known_percent-$percent)*2), 0);
      }
      if($tmp==$family["browser"]["name"]["BOT"] && $show != "domain" && ! $do_not_show) { // --- bot ---
	$tmp_name = $lang["default"]["crawler"];
	$tmp_color = "BOT";
	$tmp_show = false;
      } elseif($tmp==$unknown_text) { // --- unknown ---
	$tmp_name = ($admin && ($show=="browser" || $show=="os" || $show=="ip2c" || $show=="search")?"<a href=\"".$_SERVER["PHP_SELF"]."?show=".$show."&amp;show_ukn=true&amp;id=".$id."\" target=\"_blank\" title=\"".$lang["default"]["show_details"].": ".$lang["default"]["unknown"]."\"\n onmouseover=\"window.status='".$lang["default"]["show_details"].": ".$lang["default"]["unknown"]."'; return true;\"\n onmouseout=\"window.status=''; return true;\">".$lang["default"]["unknown"]."</a>":$lang["default"]["unknown"]);
	$tmp_color = "UKN";
	$tmp_show = false;
      } else {
	if($show=="referer") {
	  $tmp_key = str_replace("http://","",$key);
	  $tmp_text = $tmp_key;
	  $tmp_key = (strlen($tmp_key) > 84 ?substr($tmp_key,0,81)."<span class=\"log\">...</span>":$tmp_key);
	  $tmp_name = $tmp_key;
	} else $tmp_name = $key;
	$tmp_color = "DEF";
	$tmp_show = true;
      }
      ?><tr bgcolor="<?php echo $color[$tmp_color][($i&1?2:1)]; ?>">
<td class="head" align="right"><?php echo $i+1; ?></td>
<td align="left"<?php echo ($show_flag && $tmp_color=="UKN" && $show!="provider"?" colspan=\"2\"":"").">".($show=="referer" && $tmp_name!=$lang["default"]["unknown"]?"<a href=\"http://".$tmp_text."\" target=\"_blank\">".$tmp_name."</a>":$tmp_name); ?></td>
<?php 
      if($show_icon && !isset($_GET["print"])) { ?><td align="center"><img src="<?php echo $family[$show]["icon"][$tmp]; ?>" height="14" width="14" alt="<?php echo $family[$show]["name"][$tmp]; ?>" title="<?php echo $family[$show]["name"][$tmp]; ?>"></td><?php } 
      if($show_logo && !isset($_GET["print"])) { ?><td align="center"><?php
      if($explicit) { 
        if(isset($ident_search[$tmp_name])) { ?><img src="<?php echo $pic["dir"]."i_bot_".$ident_search[$tmp_name].".png"; ?>" height="14" width="14" alt="<?php echo $tmp_name; ?>" title="<?php echo $tmp_name; ?>"><?php } else echo "&nbsp;"; ?></td><?php 
      } else {
        if(isset($ident_search[$tmp_name])) { ?><img src="<?php echo $ident_search[$tmp_name]; ?>" height="14" width="14" alt="<?php echo $tmp_name; ?>" title="<?php echo $tmp_name; ?>"><?php } else echo "&nbsp;"; ?></td><?php 
        }
      }
      if($show_flag) {
	if($show=="languages") $tmp_data = ident_language($tmp_name);
	elseif($show=="provider")  {
	  $tmp_data = explode(".",$tmp_name);
	  $tmp_data = ident_country(strtolower($tmp_data[sizeof($tmp_data)-1]));
	} elseif($show=="ip2c" || $show=="domain")  $tmp_data = ident_country($tmp_name);
	if($tmp_color!="UKN" && $show!="provider") { ?>
<td><?php echo ($tmp_data["text"]?$tmp_data["text"]:"&nbsp;"); ?></td>
<?php
	}
	if(!isset($_GET["print"])) { ?>
<td align="center"><?php echo ($tmp_data["flag"]?"<img src=\"".$ident_country_files.$tmp_data["flag"]."\" height=\"10\" width=\"16\" alt=\"".($tmp_data["text"]?$tmp_data["text"]:$tmp_name)."\" title=\"".($tmp_data["text"]?$tmp_data["text"]:$tmp_name)."\">":"&nbsp;"); ?></td>
<?php
	}
      } ?>
<td align="right"><?php echo $stat_details[$key]; ?></td>
<?php 
    if(!isset($do_not_show)) { ?><td align="left" style="background-image: url(<?php echo $pic["h_t_back"]; ?>);"><?php
      if($img_width_1) { 
        ?><img src="<?php echo $pic["h_bar_1"]; ?>" height="10" width="<?php echo $img_width_1; ?>" alt="<?php printf("%3.2f%% %s",$percent,$lang["headline"]["percent_all"]); ?>" title="<?php printf("%3.2f%% %s",$percent,$lang["headline"]["percent_all"]); ?>"><?php 
      }
      if($tmp_show && $img_width_2) { 
	?><img src="<?php echo $pic["h_bar_2"]; ?>" height="10" width="<?php echo $img_width_2; ?>" alt="<?php printf("%3.2f%% %s",$known_percent,$lang["headline"]["percent_wo"]); ?>" title="<?php printf("%3.2f%% %s",$known_percent,$lang["headline"]["percent_wo"]); ?>"><?php 
      }
      if(!$img_width_1 && !$img_width_2) echo "&nbsp;";
      ?></td>
<td align="right" bgcolor="<?php echo $color["UKN"][($i&1?2:1)]; ?>" title="<?php echo $lang["headline"]["percent"]." ".$lang["headline"]["percent_all"]; ?>"><?php printf("%3.2f", $percent); ?></td>
<?php if(!$simple_view) { ?><td align="right" bgcolor="<?php echo $color["BOT"][($i&1?2:1)]; ?>" title="<?php echo $lang["headline"]["percent"]." ".$lang["headline"]["percent_wo"]; ?>"><?php
      if($tmp_show) printf("%3.2f", $known_percent);
      else echo "---"; ?></td><?php }
      if($show_filter) {
	echo "<td align=\"center\">";
	echo ($tmp_color != "UKN" && $tmp_color != "BOT"?"<a href=\"".$_SERVER["PHP_SELF"]."?show=details&amp;a=0&amp;b=".$log_entries."&amp;filter=".$show."&amp;value=".urlencode($tmp_name).($explicit?"&amp;explicit=1":"").($id!=""?"&amp;id=".$id:"")."\" target=\"_blank\"><img src=\"".$pic["dir"]."n_filter.png\" width=\"11\" height=\"10\" border=\"0\" alt=\"".$lang["default"]["show_details"].": ".$tmp_name."\" title=\"".$lang["default"]["show_details"].": ".$tmp_name."\"\n onmouseover=\"window.status='".$lang["default"]["show_details"].": ".$tmp_name."'; return true;\"\n onmouseout=\"window.status=''; return true;\"></a>":"&nbsp;");
	echo "</td>";
      }
    } ?>
</tr><?php
    $hit_count += $stat_details[$key];
    $percent_count += $percent;
    $percent_count_2 += $known_percent;
    $i++;
  } ?><tr>
<td class="default" bgcolor="<?php echo $color["DEF"][1]; ?>">&nbsp;</td>
<td class="head" align="left"<?php echo ($show_icon||$show_flag||$show_logo?" colspan=\"".($show_flag && $show!="provider"?(isset($_GET["print"])?2:3):(isset($_GET["print"])?1:2))."\"":""); ?>><?php echo $lang["default"]["total"]; ?></td>
<td class="head" align="right"><?php echo $hit_count; ?></td>
<?php if(! isset($do_not_show)) { ?><td class="head" align="right" colspan="<?php echo ($show_filter?($simple_view?3:4):($simple_view?2:3)); ?>"><?php printf("%3.2f",($show=="search"?$percent_count_2:$percent_count)); ?> %</td><?php } ; ?>
</tr></table></td>
</tr></table>
<?php 
}

// --- show unified menupoint ---
function show_menu_point($item, $target, $text, $hover, $count, $alert = false) { 
  global $login_require,$id;
  $show_link = ($item == "NONE"?false:true); ?>
<tr><td align="right"><?php for($i=0;$i<$count;$i++) echo ":"; ?>[&nbsp;</td>
<?php if($show_link) { ?><td align="center" id="menu_<?php echo $item; ?>" title="<?php echo $hover; ?>"><a class="link" href="<?php echo $target.($login_require?"&amp;id=".$id:""); ?>"
 onmouseover="document.getElementById('menu_<?php echo $item; ?>').className = 'highlight'; window.status='<?php echo $hover; ?>'; return true;"
 onmouseout="document.getElementById('menu_<?php echo $item; ?>').className = 'link'; window.status=''; return true;"<?php 
   if($alert) echo "\n onclick=\"javascript:return confirm('".$alert."');\"";
   echo ">".$text; ?></a></td><?php
  } else { ?><td>&nbsp;</td><?php } ?>
<td align="left">&nbsp;]<?php for($i=0;$i<$count;$i++) echo ":"; ?></td></tr>
<?php
}

if($show == "info") {
  // --- show version ---
  function show_version($system,$text,$color_show,$version,$author=false) {
    global $lang,$color; ?><tr bgcolor="<?php echo $color["DEF"][$color_show]; ?>">
<td class="head" align="left"><?php echo $lang["info"][$system]; ?></td>
<td align="left"><?php echo $text; ?></td>
<td align="<?php echo ($author?"center\">".$author."</td><td align=\"right\"":"right\" colspan=\"2\"").">".$version; ?></td>
</tr><?php
  }
}

// --- show maintain messages ---
function maintain_message($header,$text=false) {
  global $_SERVER,$id,$color,$lang;

  if(!$text) $text = $header;
  show_document("top");
  show_header($header, true, 1, "end", false, $_SERVER["PHP_SELF"]."?show=logfile&amp;id=".$id); ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="SHOWlog statistic - maintainmessages"><tr><td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<br><p class="header"><?php echo $text; ?></p>
<p><?php echo $lang["dellog"]["switch"]; ?></p><br>
</td></tr></table><?php 
  back_button($_SERVER["PHP_SELF"]."?show=logfile&amp;id=".$id);
  show_document("bottom");
  exit;
}
// --- end of function - definitions ---------------------------------------------------------------

// --- reading / checking files --------------------------------------------------------------------
if (file_exists($cnt_file)) { // --- check if counterfile is present ---
  if(filesize($cnt_file) > 0) {
    $cnt_fp = fopen($cnt_file, 'r');
    $counter_value = fread($cnt_fp, filesize($cnt_file));
    $counter_value = trim($counter_value);
    fclose($cnt_fp);
  } else {
    $show = "error";
    $error_string[$cnt_file] = $lang["error"]["zero_filesize"];
  }
} else {
  $show = "error";
  $error_string[$cnt_file] = $lang["error"]["not_found"];  
}
if (! file_exists($log_file)) { // --- check if logfile is present ---
  $show = "error";
  $error_string[$log_file] = $lang["error"]["not_found"];  
}

// --- delete / maintain routines ---------------------------------------------------------------------
// --- delete logfile ---
if ($show == "dellog" && $admin) {
  copy($log_file, $bak_file);

  $log_fp = fopen($log_file, 'w') or die("Can't open $log_file\n");
  flock($log_fp, 2);
  fwrite($log_fp, '');
  fclose($log_fp);

  maintain_message($lang[$show]["title_detail"]);

// --- delete last entry and decrease counter ---
} elseif($show == "dellast" && $admin) {
  copy($log_file, $bak_file);

  $log_fp = fopen($log_file, 'r+');
  flock($log_fp, 2);
  for ($log_no = 0; ! feof($log_fp); $log_no++) {
    $tmp_data[$log_no] = trim(fgets($log_fp, 1024));
  } 
  $tmp_end = $log_no-1;
  fclose ($log_fp);
  $log_fp = fopen($log_file, 'w');
  flock($log_fp, 2);
  for ($log_no = 0; $log_no<$tmp_end; $log_no++) {
    fwrite($log_fp, ($log_no != 0?"\n":"").$tmp_data[$log_no]);
  } 
  fclose ($log_fp);

  $cnt_fp = fopen($cnt_file, 'r+');
  flock($cnt_fp, 2);
  $cnt = trim(fread($cnt_fp, filesize($cnt_file)));
  if($cnt>0) {
    $cnt--;
    fclose ($cnt_fp);
    $cnt_fp = fopen($cnt_file, 'w');
    flock($cnt_fp, 2);
    fwrite($cnt_fp, $cnt);
  }
  fclose($cnt_fp);

  maintain_message($lang[$show]["title"]);

// --- maintain logfile ---
} elseif($show == "maintain" && $admin) {
  copy($log_file, $bak_file);
  $maintain_count = 0;
  $i = 0;
  $log_fp = fopen($log_file, 'r');
  for ($log_no = 0; ! feof($log_fp); $log_no++) {
    $tmp_data[$i] = fgets($log_fp, 1024);
    $tmp = explode("|", $tmp_data[$i]);
    if(sizeof($tmp) < 4 || (trim($tmp[0]) == "" || trim($tmp[1]) == "")) {
      $maintain_count++;
    } else {
      if((isset($tmp[3]) && (preg_match("/unknown/i", $tmp[3]) || trim($tmp[3]) == $unknown_text))|| !isset($tmp[3])) {
	$tmp[3] = "";
	$maintain_count++;
      }
      if((isset($tmp[4]) && (preg_match("/unknown/i", $tmp[4]) || trim($tmp[4]) == $unknown_text)) || !isset($tmp[4])) {
	$tmp[4] = "";
	$maintain_count++;
      }
      if(isset($tmp[5])) {
	if(preg_match("/unknown/i", $tmp[5]) || trim($tmp[5]) == $unknown_text) {
	  $tmp[5] = "";
	  $maintain_count++;
	} else {
	  $tmp[5] = strtolower($tmp[5]);
	  if(strstr($tmp[5],",") || strstr($tmp[5],";")) {
	    $tmp_lang = explode(",",$tmp[5]);
	    $tmp_lang = explode(";",$tmp_lang[0]);
	    $tmp[5] = $tmp_lang[0];
	    $maintain_count++;
	  }
	  if(strstr($tmp[5],"-")) {
	    $tmp_work = explode("-", $tmp[5]);
	    if(trim($tmp_work[0]) == trim($tmp_work[1])) {
	      $tmp[5] = $tmp_work[0];
	      $maintain_count++;
	    }
	  }
	}
      } else {
	$tmp[5] = "";
	$maintain_count++;
      }
      if(isset($tmp[6]) && (preg_match("/unknown/i", $tmp[6]) || trim($tmp[6]) == $unknown_text)) {
	$tmp[6] = "";
	$maintain_count++;
      }
      if(isset($tmp[7]) && trim($tmp[7]) == $unknown_text) {
	$tmp[7] = "";
	$maintain_count++;
      }
      if(isset($tmp[8]) && trim($tmp[8]) == $unknown_text) {
	$tmp[8] = "";
	$maintain_count++;
      } else {
	if(isset($tmp[8]) && strstr($tmp[8], " X ")) {
	  $tmp[8] = implode("X",explode(" X ",$tmp[8]));
	  $maintain_count++;
	}
      }
      for($x=0;$x<sizeof($tmp);$x++) {
	$tmp[$x] = trim($tmp[$x]);
      }
      $tmp_data[$i] = implode("|",$tmp)."\n";
      $i++;
    }  
  }
  fclose($log_fp);

  $contents = implode("",$tmp_data);
  $log_fp = fopen($log_file, 'w');
  flock($log_fp, 2);
  fwrite($log_fp, trim($contents));
  fclose($log_fp); 

  $log_fp = fopen ($log_file, "r");
  $contents = fread ($log_fp, filesize ($log_file));
  fclose ($log_fp);

  if($contents != trim($contents)) {
    $log_fp = fopen($log_file, 'w');
    flock($log_fp, 2);
    fwrite($log_fp, trim($contents));
    fclose($log_fp);
    $maintain_count++;
  }
  maintain_message($lang["logfile"]["repair"],($maintain_count == 0?$lang[$show]["no_errors"]:$maintain_count." ".$lang[$show]["found"]));
}

// --- do some more definitions -----------------------------------------------------------------------------
$known_count = 0;
$data = array();
$log_entries = 0;
$bot_count = 0;

if($show) {
  // --- define statistic-arrays ---
  $stat_details = array();
  $stat_family = array();
  $stat_key = array();
  $stat_extra = array();

  // --- define filter wariables for sensitive webservers ---
  $detail_filter = false;
  $detail_filter_bots = false;
  $detail_filter_browser = false;
  $detail_filter_os = false;
  $detail_filter_referer = false;
  $detail_filter_forward = false;
  $detail_filter_host = false;
  $detail_filter_domain = false;
  $detail_filter_language = false;
  $detail_filter_ip = false;
  $detail_filter_ip2c = false;
  $detail_filter_screen = false;
  $detail_filter_date_1 = false;
  $detail_filter_date_2 = false;
  $detail_filter_show_not = false;
  $detail_filter_fulltext = false;
  $filter_explicit = false;
  $explicit = false;

  // --- include required functions and define special variables for specific statistics ---
  if($show == "os" || $show == "browser" || $show == "details" || $show == "info" || $show == "logfile" || $show == "provider" || $show == "day" || $show == "month") {
    $family = array();
    if(file_exists($modul_ident_os)) include_once($modul_ident_os);
    else {
      $show = "error";
      $error_string[$modul_ident_os] = $lang["error"]["not_found"];
    }
    if(file_exists($modul_ident_browser)) include_once($modul_ident_browser);
    else {
      $show = "error";
      $error_string[$modul_ident_browser] = $lang["error"]["not_found"];
    }
    $bot_name = $family["browser"]["name"]["BOT"];
    $navi_next = "&gt;&gt;";
    $navi_prev = "&lt;&lt;";
    $navi_end = "&gt;&gt;|";
    $navi_start = "|&lt;&lt;";
  }

  if($show == "day") {
    if(isset($_POST["monthly"]) && $_POST["monthly"] != "") {
      $tmp = explode("|",$_POST["monthly"]);
      $_GET["mb"] = $tmp[0];
      $_GET["me"] = $tmp[1];
    } 
    if(isset($_GET["mb"]) && isset($_GET["me"]) && $_GET["mb"] != "" && $_GET["me"] != "") {
      $tmp_date = explode("-",$_GET["me"]);
      for($i=1;$i<=$tmp_date[2];$i++) {
	$stat_details[date("Y-m-d",mktime(0, 0, 0, $tmp_date[1], $i, $tmp_date[0]))] = 0;
      }  
    } else {
      $show = "error";
      $error_string[$lang["error"]["date_params"]] = $lang["error"]["not_ok"];  
    }
  }

  if($show == "month") {
    if(isset($_POST["yearly"]) && $_POST["yearly"] != "") $_GET["year"] = $_POST["yearly"];
    for($i=1;$i<=12;$i++) {
      $stat_details[date("m",mktime(0, 0, 0, $i, 1, $_GET["year"]))] = 0;
    }
  }

  if($show == "ip2c" || $show == "info" || $show == "details") {
    if(file_exists($ip2c_fkt)) include_once($ip2c_fkt);
    else {
      $show = "error";
      $error_string[$ip2c_fkt] = $lang["error"]["not_found"];
    }
  }

  if($show == "details" || $show == "languages" || $show == "info" || $show == "domain" || $show == "ip2c" || $show == "provider") {
    if(file_exists($modul_ident_country)) include_once($modul_ident_country);
    else {
      $show = "error";
      $error_string[$modul_ident_country] = $lang["error"]["not_found"];
    }
    if(file_exists($modul_ident_language)) include_once($modul_ident_language);
    else {
      $show = "error";
      $error_string[$modul_ident_language] = $lang["error"]["not_found"];
    }
  }

  if($show == "search" || $show == "info") {
    if(file_exists($modul_ident_search)) include_once($modul_ident_search);
    else {
      $show = "error";
      $error_string[$modul_ident_search] = $lang["error"]["not_found"];
    }
    if(file_exists($modul_ident_bot)) include_once($modul_ident_bot);
    else {
      $show = "error";
      $error_string[$modul_ident_bot] = $lang["error"]["not_found"];
    }
  }

  if($show == "details") {
    // --- set filter details ---
    if(isset($_POST["filter_bots"]) || isset($_GET["filter_bots"])) $detail_filter_bots = true;
    if(isset($_POST["filter"]) && $show_detail_filter) $detail_filter = true;
    if(isset($_GET["explicit"]) && $_GET["explicit"]) $filter_explicit = true;
    if(isset($_GET["filter"]) && isset($_GET["value"]) && $_GET["value"] != "") {
      if($_GET["filter"]=="browser") $detail_filter_browser = urldecode(strtolower(trim($_GET["value"])));
      elseif($_GET["filter"]=="browser") $detail_filter_browser = urldecode(strtolower(trim($_GET["value"])));
      elseif($_GET["filter"]=="os") $detail_filter_os = urldecode(strtolower(trim($_GET["value"])));
      elseif($_GET["filter"]=="referer") $detail_filter_referer = urldecode(strtolower(trim($_GET["value"])));
      elseif($_GET["filter"]=="forward") $detail_filter_forward = urldecode(strtolower(trim($_GET["value"])));
      elseif($_GET["filter"]=="provider") $detail_filter_host = urldecode(strtolower(trim($_GET["value"])));
      elseif($_GET["filter"]=="domain") $detail_filter_domain = urldecode(strtolower(trim($_GET["value"])));
      elseif($_GET["filter"]=="languages") $detail_filter_language = urldecode(strtolower(trim($_GET["value"])));
      elseif($_GET["filter"]=="ip2c") $detail_filter_ip2c = urldecode(strtolower(trim($_GET["value"])));
      elseif($_GET["filter"]=="screen") $detail_filter_screen = urldecode(strtoupper(trim($_GET["value"])));
      elseif($_GET["filter"]=="day") {
	$detail_filter_date_1 = urldecode(trim($_GET["value"]));
	$detail_filter_date_2 = 	$detail_filter_date_1;
      } elseif($_GET["filter"]=="month") {
	$tmp_date = explode("-",urldecode(trim($_GET["value"])));
	$detail_filter_date_1 = $tmp_date[0]."-".$tmp_date[1]."-01";
	$detail_filter_date_2 = $tmp_date[0]."-".$tmp_date[1]."-".date("t",mktime(0, 0, 0, $tmp_date[1], 1, $tmp_date[0]));
      }
      if($detail_filter_browser || $detail_filter_os || $detail_filter_referer || $detail_filter_forward || $detail_filter_host || $detail_filter_domain || $detail_filter_language || $detail_filter_ip2c || $detail_filter_screen || $detail_filter_date_1) {
	$detail_filter_show_not = true;
	$detail_filter = true;
      }
    } elseif($detail_filter) {
      if(isset($_POST["filter_browser"]) && isset($_POST["filter_browser_value"])) $detail_filter_browser = strtolower(trim($_POST["filter_browser_value"]));
      if(isset($_POST["filter_os"]) && isset($_POST["filter_os_value"])) $detail_filter_os = strtolower(trim($_POST["filter_os_value"]));
      if(isset($_POST["filter_ip"]) && isset($_POST["filter_ip_value"])) $detail_filter_ip = trim($_POST["filter_ip_value"]);
      if(isset($_POST["filter_date_1"]) && isset($_POST["filter_date_1_value"])) $detail_filter_date_1 = trim($_POST["filter_date_1_value"]);
      if(isset($_POST["filter_date_2"]) && isset($_POST["filter_date_2_value"])) $detail_filter_date_2 = trim($_POST["filter_date_2_value"]);
      if(isset($_POST["filter_host"]) && isset($_POST["filter_host_value"])) $detail_filter_host = strtolower(trim($_POST["filter_host_value"]));
      if(isset($_POST["filter_referer"]) && isset($_POST["filter_referer_value"])) $detail_filter_referer = strtolower(trim($_POST["filter_referer_value"]));
      if(isset($_POST["filter_language"]) && isset($_POST["filter_language_value"])) $detail_filter_language = strtolower(trim($_POST["filter_language_value"]));
      if(isset($_POST["filter_fulltext"]) && isset($_POST["filter_fulltext_value"])) $detail_filter_fulltext = strtolower(trim($_POST["filter_fulltext_value"]));
      if(!($detail_filter_ip || $detail_filter_date_1 || $detail_filter_date_2 || $detail_filter_browser || $detail_filter_os || $detail_filter_language || $detail_filter_host || $detail_filter_referer || $detail_filter_fulltext)) {
        $_GET["b"] = $detail_length;
        $detail_filter = false;
      }
    }
  }
}

// --- read logfile and generate statistics ----------------------------------------------------------
if($show != "error") {
  // --- check params for detailed logfile view ---
  if($show == "details") {
    if(isset($_GET["a"]) && $_GET["a"] < 0) $_GET["a"] = 0;
    if(isset($_GET["b"]) && $_GET["b"] < $_GET["a"]) $_GET["b"] = $_GET["a"] + $detail_length;
  }
  $error_count = 0;
  $log_no = 0;
  $log_fp = fopen($log_file, 'r') or die("Can't open $log_file\n");
  for ($line_no = 0; ! feof($log_fp); $line_no++) {
    $tmp_data = fgets($log_fp, 1024);
    if($show && trim($tmp_data != "")) {
      if($detail_filter && $detail_filter_fulltext && !strpos(" ".strtolower($tmp_data),$detail_filter_fulltext)) continue; // --- filter fultext ---
      $tmp = explode("|", $tmp_data);
      if($detail_filter_bots && ident_bot(strtolower($tmp[3]))) continue;

// --- get data for ip to country statistics ---
      if($show == "ip2c") {
	if(isset($tmp[1])) {
	  $tmp_country = ip2country(trim($tmp[1]));
	  if($tmp_country != $unknown_text) $known_count++;
	  elseif(isset($_GET["show_ukn"])) {
	    $data[$log_no]["UKN"] = (trim($tmp[1]) != ""?trim($tmp[1]):$unknown_text);
	  }
	  if(isset($stat_details[$tmp_country])) $stat_details[$tmp_country]++;
	  else $stat_details[$tmp_country] = 1;
	} else $error_count++;

// --- data for detailed overview ---
      } elseif($show == "details" && ($detail_filter || ($log_no >= $_GET["a"] && $log_no <= $_GET["b"]))) {
	$tmp_agent = (isset($tmp[3])?trim($tmp[3]):"");
	$data[$log_no]["FORWARD"] = (isset($tmp[6]) && trim($tmp[6])!=$unknown_text && trim($tmp[6])!=""?trim($tmp[6]):"---");
	if($detail_filter_forward && !strpos(" ".strtolower($data[$log_no]["FORWARD"]),$detail_filter_forward)) continue;
	$tmp_date = explode(" ",trim($tmp[0]));
	$data[$log_no]["DATE"] = $tmp_date[0];
	if($detail_filter_date_1 && convert_date($tmp_date[0],"us") < convert_date($detail_filter_date_1,"us")) continue;
	if($detail_filter_date_2 && convert_date($tmp_date[0],"us") > convert_date($detail_filter_date_2,"us")) continue;
	$data[$log_no]["TIME"] = (isset($tmp_date[1])?$tmp_date[1]:"");
	$data[$log_no]["REFERER"] = (isset($tmp[4]) && trim($tmp[4]) != ""?(!$admin && $hide_referer && preg_match("/".$hide_referer."/i",$tmp[4])?"---":preg_replace("/&/","&amp;",trim($tmp[4]))):"---");
	if($detail_filter_referer) {
	  if(strpos($data[$log_no]["REFERER"],"?")) $tmp_referer = substr($data[$log_no]["REFERER"],0,strpos($data[$log_no]["REFERER"],"?"));
	  else $tmp_referer = $data[$log_no]["REFERER"];
	  if( !strpos(" ".strtolower($tmp_referer),$detail_filter_referer)) continue;
	}
	$data[$log_no]["IP"] = (isset($tmp[1])?$tmp[1]:"");
	if($detail_filter_ip && !strpos(" ".$data[$log_no]["IP"],$detail_filter_ip)) continue;
	if($detail_filter_ip2c && ip2country(trim($tmp[1]))!=$detail_filter_ip2c) continue;
	$data[$log_no]["HOST"] = (isset($tmp[2])?$tmp[2]:"");
	if($detail_filter_host && !strpos(" ".strtolower($data[$log_no]["HOST"]),$detail_filter_host)) continue;
	if($detail_filter_domain && substr(strtolower($data[$log_no]["HOST"]),(0-strlen($detail_filter_domain)))!=$detail_filter_domain) continue;
	if(isset($tmp[5])) {
	  $tmp_lang = (isset($tmp[5]) && $tmp[5]!=$unknown_text && $tmp[5] != ""?strtolower(trim($tmp[5])):"---");
	} else $tmp_lang = "---";
	if($tmp_lang == "---") {
	  $tmp_lang = find_language($tmp_agent);
	}
	if($detail_filter_language) {
	  if($filter_explicit) {
	    if($detail_filter_language!=strtolower($tmp_lang)) continue;
	  } elseif(!strpos(" ".strtolower($tmp_lang),$detail_filter_language)) continue;
	}
	$data[$log_no]["LANGUAGE"] = $tmp_lang;
	$data[$log_no]["SCREEN"] = (isset($tmp[7]) && trim($tmp[7])!=$unknown_text && trim($tmp[7])!=""?trim($tmp[7]):(preg_match("/([0-9]{2,4}[xX][0-9]{2,4})/",$tmp_agent, $ver)?strtoupper($ver[0]):"---"));
	$data[$log_no]["COLORS"] = (isset($tmp[8]) && trim($tmp[8])!=$unknown_text && trim($tmp[8])!=""?trim($tmp[8])." ".$lang["screen"]["color_text"]:"---");
	if($detail_filter_screen && $data[$log_no]["SCREEN"]!=$detail_filter_screen && strtoupper($data[$log_no]["COLORS"])!=$detail_filter_screen) continue;
	$tmp_array = ident_browser($tmp_agent);
	$data[$log_no]["B_KEY"] = $tmp_array[0];
	$data[$log_no]["B_NAME"] = $tmp_array[1];
	if($detail_filter_browser) {
	  if($filter_explicit) {
	    if( !(trim($tmp_array[0]) == $unknown_text && $detail_filter_browser == strtolower($lang["default"]["unknown"])) && !(trim($tmp_array[0]) == "BOT" && strtolower($lang["default"]["crawler"])==$detail_filter_browser) && trim(strtolower($tmp_array[1]))!=$detail_filter_browser ) continue;
	  } else {
	    if(!(trim($tmp_array[0]) == $unknown_text && strpos(" ".strtolower($lang["default"]["unknown"]),$detail_filter_browser)) && !strpos(" ".strtolower(trim($tmp_array[1])),$detail_filter_browser) && !(trim($tmp_array[0]) == "BOT" && strpos(" ".strtolower($lang["default"]["crawler"]),$detail_filter_browser))) continue;
	  }
	}
	$tmp_array = ident_os($tmp_agent);
	$data[$log_no]["OS_KEY"] = $tmp_array[0];
	$data[$log_no]["OS_NAME"] = $tmp_array[1];
	if($detail_filter_os) {
	  if($filter_explicit) {
	    if(!(trim($tmp_array[0]) == $unknown_text && strtolower($lang["default"]["unknown"])==$detail_filter_os) && trim(strtolower($tmp_array[1]))!=$detail_filter_os && !(trim($tmp_array[0]) == "BOT" && strtolower($lang["default"]["crawler"])==$detail_filter_os)) continue;
	  } else {
	    if(!(trim($tmp_array[0]) == $unknown_text && strpos(" ".strtolower($lang["default"]["unknown"]),$detail_filter_os)) && !strpos(" ".strtolower($tmp_array[1]),$detail_filter_os) && !(trim($tmp_array[0]) == "BOT" && strpos(" ".strtolower($lang["default"]["crawler"]),$detail_filter_os))) continue;
	  }
	}
	$data[$log_no]["AGENT"] = ($tmp_agent==""?"---":$tmp_agent);

// --- get data for browser statistics ---
      } elseif($show=="browser") { 
	$tmp_js = (isset($tmp[7]) && trim($tmp[7])?$lang["browser"]["enabled"]:$lang["browser"]["disabled"]);
	if(isset($tmp[3])) {
	  $tmp_array = ident_browser($tmp[3]);
	  $tmp_key = $tmp_array[0];
	  $tmp_family = $family["browser"]["name"][$tmp_key];
	  $tmp_name = $tmp_array[1];
	} else $tmp_key = $unknown_text;
	if(!($tmp_key==$unknown_text || $tmp_key==$bot_name)) {
	  $known_count++;
	  if(isset($stat_extra[$tmp_js])) $stat_extra[$tmp_js]++;
	  else $stat_extra[$tmp_js] = 1;
	}
	if(isset($_GET["show_ukn"]) && $tmp_key==$unknown_text) {
	  $data[$log_no]["UKN"] = (isset($tmp[3])?trim($tmp[3]):$unknown_text);
	}
	if(isset($stat_details[$tmp_family])) $stat_details[$tmp_family]++;
	else {
	  $stat_details[$tmp_family] = 1;
	  $stat_key[$tmp_family] = $tmp_key;
	}
	if(isset($stat_family[$tmp_name])) $stat_family[$tmp_name]++;
	else {
	  $stat_family[$tmp_name] = 1;
	  $stat_key[$tmp_name] = $tmp_key;
	}

// --- get data for os statistics ---
      } elseif($show=="os") { 
	if(isset($tmp[3])) {
	  $tmp_array = ident_os($tmp[3]);
	  $tmp_key = $tmp_array[0];
	  $tmp_family = $family["os"]["name"][$tmp_key];
	  $tmp_name = $tmp_array[1];
	} else $tmp_key = $unknown_text;
	if(!($tmp_key==$unknown_text || $tmp_key==$bot_name)) $known_count++;
	if(isset($_GET["show_ukn"]) && $tmp_key==$unknown_text) {
	  $data[$log_no]["UKN"] = (isset($tmp[3])?trim($tmp[3]):$unknown_text);
	}
	if(isset($stat_details[$tmp_family])) $stat_details[$tmp_family]++;
	else {
	  $stat_details[$tmp_family] = 1;
	  $stat_key[$tmp_family]= $tmp_key;
	}
	if(isset($stat_family[$tmp_name])) $stat_family[$tmp_name]++;
	else {
	  $stat_family[$tmp_name] = 1;
	  $stat_key[$tmp_name]= $tmp_key;
	}

// --- get data for hits by day statistics ---
      } elseif($show == "day") {
	if(isset($tmp[0]) && strstr($tmp[0], "-")) {
	  $tmp_date = explode(" ",trim($tmp[0]));
	  $tmp_dates = $tmp_date[0];
	  $data[$log_no]["DATE"] = $tmp_dates;
	  if((isset($_GET["mb"]) && $tmp_dates >= $_GET["mb"]) && (isset($_GET["me"]) && $tmp_dates <= $_GET["me"])) {
	    if(isset($tmp[3]) && ident_bot($tmp[3])) {
	      if(isset($stat_family[$tmp_dates])) $stat_family[$tmp_dates]++;
	      else $stat_family[$tmp_dates] = 1;
	    }
	    if(isset($stat_details[$tmp_dates])) $stat_details[$tmp_dates]++;
	    else $stat_details[$tmp_dates] = 1;
	  }
	} else $error_count++;

// --- get data for hits by month statistics ---
      } elseif($show == "month") {
	if(isset($tmp[0]) && strstr($tmp[0], "-")) {
	  $tmp_date = explode(" ",trim($tmp[0]));
	  $tmp_dates = explode("-",$tmp_date[0]);
	  $data[$log_no]["DATE"] = $tmp_date[0];
	  if(isset($_GET["year"]) && $tmp_dates[0] == $_GET["year"]) {
	    $month = $tmp_dates[1];
	    if(isset($tmp[3]) && ident_bot($tmp[3])) {
	      if(isset($stat_family[$month])) $stat_family[$month]++;
	      else $stat_family[$month] = 1;
	    }
	    if(isset($stat_details[$month])) $stat_details[$month]++;
	    else $stat_details[$month] = 1;
	  }
	} else $error_count++;

// --- get data for referer statistics ---
      } elseif($show == "referer") {
	$tmp_refr = (isset($tmp[4])?trim($tmp[4]):$unknown_text);
	if(!$admin && $hide_referer && preg_match("/".$hide_referer."/i",$tmp_refr)) $tmp_refr = $unknown_text;
	if(preg_match("/^mailto:/i", $tmp_refr)) $tmp_refr = $unknown_text;
	if($tmp_refr == "" || $tmp_refr == $unknown_text) {
	  $tmp_ref = $unknown_text;
	  $tmp_host = $unknown_text;
	} else {
	  if(!preg_match("/^http:\/\//i", $tmp_refr)) $tmp_refr = "http://".$tmp_refr;
	  $tmp_refr = preg_replace("/&/","&amp;",$tmp_refr);
	  if(isset($stat_extra[$tmp_refr])) $stat_extra[$tmp_refr]++;
	  else $stat_extra[$tmp_refr] = 1;
	  $known_count++;
	  $url_array = parse_url($tmp_refr);
	  if(isset($url_array["host"])) {
	    $tmp_host = $url_array["host"];
	    if(substr_count($url_array["host"],".")>1) $url_array["host"] = substr($url_array["host"],strpos($url_array["host"],".")+1);
	    $tmp_ref = trim($url_array["host"]);
	  }
	}
	if(isset($stat_family[$tmp_ref])) $stat_family[$tmp_ref]++;
	else $stat_family[$tmp_ref] = 1;
	if(isset($stat_details[$tmp_host])) $stat_details[$tmp_host]++;
	else $stat_details[$tmp_host] = 1;

// --- get data for country statistics ---
      } elseif($show == "domain") {
	if(isset($tmp[2])) {
	  if(strstr($tmp[2], ".")) {
	    $tmp_host = explode(".", trim($tmp[2]));
	    $tmp_domain = array_pop($tmp_host);
	    $tmp_hostc = (preg_match("/[0-9]/",$tmp_domain) || $tmp_domain ==""?$unknown_text:strtolower($tmp_domain)); 
	  } else $tmp_hostc = $unknown_text;
	} 
	if($tmp_hostc != $unknown_text) $known_count++;
	if(isset($stat_details[$tmp_hostc])) $stat_details[$tmp_hostc]++;
	else $stat_details[$tmp_hostc] = 1;

// --- get data for language statistics ---
      } elseif($show == "languages") {
	$tmp_lang = (isset($tmp[5])?strtolower(trim($tmp[5])):$unknown_text);
	if(($tmp_lang == $unknown_text || $tmp_lang == "") && isset($tmp[3])) {
	  $tmp_lang = find_language($tmp[3]);
	  if($tmp_lang == "---") $tmp_lang = $unknown_text;
	}
	if($tmp_lang == $unknown_text || $tmp_lang == "") {
	  $tmp_lang_details = $unknown_text;
	  $tmp_lang_family = $unknown_text;
	} else {
	  $known_count++;
	  $tmp_lang_details = $tmp_lang;
	  $tmp_lang_family = substr($tmp_lang,0,2);
	}
	if(isset($stat_details[$tmp_lang_family])) $stat_details[$tmp_lang_family]++;
	else $stat_details[$tmp_lang_family] = 1;
	if(isset($stat_family[$tmp_lang])) $stat_family[$tmp_lang]++;
	else $stat_family[$tmp_lang] = 1;

// --- get data for sceensize / colordepth statistics ---
      } elseif($show == "screen") {
	$tmp_screen = (isset($tmp[7]) && trim($tmp[7])?strtoupper(trim($tmp[7])):$unknown_text);
	if($tmp_screen != $unknown_text) $known_count++;
	elseif(isset($tmp[3]) && preg_match("/([0-9]{2,4}[xX][0-9]{2,4})/",$tmp[3], $ver)) {
	  $tmp_screen = strtoupper($ver[0]);
	  $known_count++;
	}
	if($tmp_screen != $unknown_text) {
	  $tmp_screensize = explode("X",$tmp_screen);
	  if($tmp_screensize[0] > 1000 && ($tmp_screensize[0]/$tmp_screensize[1])>1.5) $screen_type = $lang["screen"]["wide"];
	  else $screen_type = $lang["screen"]["normal"]; 
	  if(isset($stat_extra[$screen_type])) $stat_extra[$screen_type]++;
	  else $stat_extra[$screen_type] = 1;
	}
	if(isset($stat_details[$tmp_screen])) $stat_details[$tmp_screen]++;
	else $stat_details[$tmp_screen] = 1;
	$tmp_color = (isset($tmp[8]) && trim($tmp[8]) != ""?trim($tmp[8])." ".$lang[$show]["color_text"]:$unknown_text);
	if(isset($stat_family[$tmp_color])) $stat_family[$tmp_color]++;
	else $stat_family[$tmp_color] = 1;

// --- get data for user / pass overview ---
      } elseif($show == "userpass") { 
	if(isset($tmp[4]) && $tmp[4] != "") {
	  $tmp_up = trim($tmp[4]);
	  $url_array = parse_url($tmp_up); 
	  if(isset($url_array["user"]) || isset($url_array["pass"])) {
	    $stat_details[$known_count]["user"] = trim($url_array["user"]);
	    $stat_details[$known_count]["pass"] = trim($url_array["pass"]);
	    $stat_details[$known_count]["host"] = trim($url_array["host"]);
	    $stat_details[$known_count]["url"] = $tmp_up;
	    $known_count++;
	  }
	}

// --- get data for searengine statistics ---
      } elseif($show == "search") {
	if(isset($tmp[4])) {
	  if($tmp_search = ident_searchengine(trim($tmp[4]))) {
	    $url_array = parse_url(trim($tmp[4]));
	    if(isset($url_array["query"]) && isset($url_array["host"]) && isset($url_array["path"])) {
	      $known_count++;
	      if(isset($stat_details[$tmp_search])) $stat_details[$tmp_search]++;
	      else $stat_details[$tmp_search] = 1;
	      $stat_extra[preg_replace("/&/","&amp;",substr(trim($url_array["query"]),0,-1))] = trim($url_array["host"]);
	      $search_path[preg_replace("/&/","&amp;",substr(trim($url_array["query"]),0,-1))] = trim($url_array["path"]);
	    }
	  }
	}
	$tmp_agent = (isset($tmp[3])?trim($tmp[3]):false);
	if($tmp_agent) {
	  if(ident_bot($tmp_agent)) {
	    if(! $tmp_bot = ident_robot($tmp_agent)) {
	      $tmp_bot[0] = false;
	      $tmp_bot[1] = $unknown_text;
	      $data[$log_no]["UKN"] = $tmp_agent;
	    }
	    $bot_count++;
	    if(isset($stat_family[$tmp_bot[1]])) $stat_family[$tmp_bot[1]]++;
	    else $stat_family[$tmp_bot[1]] = 1;
	    $ident_search[$tmp_bot[1]] = $tmp_bot[0];
	  }
	}

// --- get data for provider statistics ---
      } elseif($show == "provider") {
	if(!(isset($tmp[3]) && ident_bot(strtolower($tmp[3])))) {
	if(isset($tmp[2])) {
	  $tmp_array = explode(".", trim($tmp[2]));
	  $tmp_array_size = sizeof($tmp_array);
	  if($tmp_array_size > 2 && !(sizeof($tmp_array) == 4 && is_numeric($tmp_array[($tmp_array_size-1)]) && is_numeric($tmp_array[($tmp_array_size-2)]))) {
	    $tmp_host =  $tmp_array[sizeof($tmp_array)-2].".".$tmp_array[sizeof($tmp_array)-1];
	    $known_count++;
	  } else $tmp_host = $unknown_text;
	} else $tmp_host = $unknown_text;
	} else $tmp_host = "BOT";
	if(isset($stat_details[$tmp_host])) $stat_details[$tmp_host]++;
	else $stat_details[$tmp_host] = 1;

// --- get data for forward statistics ---
      } elseif($show == "forward") {
	$tmp_forward = (isset($tmp[6]) && trim($tmp[6])?trim($tmp[6]):$unknown_text);
	if($tmp_forward != $unknown_text) $known_count++;
	if(isset($stat_details[$tmp_forward])) $stat_details[$tmp_forward]++;
	else $stat_details[$tmp_forward] = 1;
      }
    }
    $bench_b = microtime(); // --- for benchmarking ---

    // --- some more definitions: first log date ---    
    if($log_no == 0) {
      $tmp = explode("|", $tmp_data);
      $tmp_date = explode(" ",trim($tmp[0]));
      $first_log = $tmp_date[0];
    }

    // --- show not identified Browsers / OS / IPs / Searchengines --- 
    if(isset($_GET["show_ukn"])) {
      if($log_no == 0) {
	$ukn = 1;
	show_document("top");
	show_header($lang["unknown"]["title_detail"].": ".$lang["default"][$show]);
	echo "<table align=\"center\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\" class=\"tback\" width=\"100%\"  summary=\"SHOWlog unknown overview\">\n"; 
     }
     if(isset($data[$log_no]["UKN"])) { 
	echo "<tr>\n<td class=\"head\" align=\"right\" width=\"25\">".$ukn."</td>\n";
	echo "<td bgcolor=\"".$color["DEF"][($ukn&1?2:1)]."\" align=\"left\">";
	if($show=="ip2c") echo ($data[$log_no]["UKN"]!=""?$data[$log_no]["UKN"]:"&nbsp;")."</td><td bgcolor=\"".$color["DEF"][($ukn&1?2:1)]."\" align=\"left\">".($data[$log_no]["UKN"]!=""?sprintf("%u", ip2long($data[$log_no]["UKN"])):"&nbsp;");
	else echo ($data[$log_no]["UKN"]==$unknown_text|| !$data[$log_no]["UKN"]?$lang["default"]["unknown"]:"<a href=\"http://www.google.de/search?hl=de&amp;q=".urlencode($data[$log_no]["UKN"])."&amp;btnG=Google-Suche&amp;meta=\" target=\"_blank\">".$data[$log_no]["UKN"]."</a>");
	echo "</td>\n</tr>\n";
	$ukn++;
      }
    }
    $log_no++;
  }

  // --- some more definitions ---
  if($known_count == 0 && $show != "userpass" && $show != "search") $known_count = 1;
  if($log_no == 1 && trim($first_log) == "") $log_no = 0;
  if(isset($_GET["show_ukn"])) $show = "nothing";

  $log_entries = $log_no;
  $line_number = $counter_value - $log_entries;
  if($line_number < 0) $line_number = 0;
  if(trim($tmp_data) != "") { 
    $tmp = explode("|", $tmp_data);
    $tmp_date = explode(" ",trim($tmp[0]));
    $last_log_date = $tmp_date[0];
    $last_log_time = $tmp_date[1];
    $last_log_entry = $tmp_data;
  } else {
    $last_log_date = 0;
    $last_log_time = 0;
    $last_log_entry = 0;
  }
} else {
  $log_entries = 1;
}
$bench_b = microtime();

if(!$show || !$detail_filter) {
  if(isset($last_log_date) && $last_log_date > 0) {
    $tmp_date_ymd = explode("-",$last_log_date);
    $year = $tmp_date_ymd[0];
    $date_mb = date("Y-m-d",mktime(0, 0, 0, $tmp_date_ymd[1], 1, $tmp_date_ymd[0]));
    $date_me = date("Y-m-d",mktime(0, 0, 0, $tmp_date_ymd[1], date("t",mktime(0, 0, 0, $tmp_date_ymd[1], 1, $tmp_date_ymd[0])), $tmp_date_ymd[0]));
  } else {
    $date_mb = date("Y-m-d");
    $date_me = $date_mb;
    $year = date("Y");
  }
  if($log_entries > 0 && isset($first_log)) {
    $lm = $lang["menu"]["since"]." ".convert_date($first_log,$lang_format);
  } else $lm=$lang["error"]["no_entries"];
}
// --- document output --------------------------------------------------------------------------------
if($show!="nothing") {
  show_document("top");
  if($show && $page_menu && !$detail_filter && !isset($_GET["print"])) { 
    show_hr(); ?>
<table border="0" cellspacing="2" cellpadding="2" width="100%" align="center" summary="SHOWlog inpagemenu design"><tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td width="33%" align="center" valign="top"><table border="0" cellspacing="0" cellpadding="0" summary="SHOWlog inpagemenu part 1"><?php
show_menu_point("browser", $_SERVER["PHP_SELF"]."?show=browser", $lang["default"]["browser"], $lang["menu"]["stat_head"].": ".$lang["default"]["browser"], 1);
show_menu_point("os", $_SERVER["PHP_SELF"]."?show=os", $lang["default"]["os"], $lang["menu"]["stat_head"].": ".$lang["default"]["os"], 2);
show_menu_point("referer", $_SERVER["PHP_SELF"]."?show=referer", $lang["default"]["referer"], $lang["menu"]["stat_head"].": ".$lang["default"]["referer"], 2);
show_menu_point("forward", $_SERVER["PHP_SELF"]."?show=forward", $lang["default"]["forward"], $lang["menu"]["stat_head"].": ".$lang["default"]["forward"], 2);
show_menu_point("search", $_SERVER["PHP_SELF"]."?show=search", $lang["default"]["search"], $lang["menu"]["stat_head"].": ".$lang["default"]["search"], 1); ?></table></td>
<td width="33%" align="center" valign="top"><table border="0" cellspacing="0" cellpadding="0" summary="SHOWlog inpagemenu part 2"><?php
show_menu_point("provider", $_SERVER["PHP_SELF"]."?show=provider", $lang["default"]["provider"], $lang["menu"]["stat_head"].": ".$lang["default"]["provider"], 1);
show_menu_point("domain", $_SERVER["PHP_SELF"]."?show=domain", $lang["default"]["domain"], $lang["menu"]["stat_head"].": ".$lang["default"]["domain"], 2);
show_menu_point("ip2c", $_SERVER["PHP_SELF"]."?show=ip2c", $lang["ip2c"]["title_detail"], $lang["menu"]["stat_head"].": ".$lang["ip2c"]["title_detail"], 2);
show_menu_point("languages", $_SERVER["PHP_SELF"]."?show=languages", $lang["menu"]["languages"], $lang["menu"]["stat_head"].": ".$lang["menu"]["languages"], 2);
show_menu_point("screen", $_SERVER["PHP_SELF"]."?show=screen", $lang["menu"]["screen"], $lang["menu"]["stat_head"].": ".$lang["menu"]["screen"], 1); ?></table></td>
<td width="33%" align="center" valign="top"><table border="0" cellspacing="0" cellpadding="0" summary="SHOWlog inpagemenu part 3"><?php
show_menu_point("day", $_SERVER["PHP_SELF"]."?show=day&amp;mb=".$date_mb."&amp;me=".$date_me, $lang["day"]["title_detail"], $lang["menu"]["stat_head"].": ".$lang["menu"]["hits_by_day"], 1);
show_menu_point("month", $_SERVER["PHP_SELF"]."?show=month&amp;year=".$year, $lang["month"]["title_detail"], $lang["menu"]["stat_head"].": ".$lang["menu"]["hits_by_month"], 2);
if($admin) {
  show_menu_point("userpass", $_SERVER["PHP_SELF"]."?show=userpass", $lang["menu"]["userpass"], $lang["menu"]["over_head"].": ".$lang["menu"]["userpass"], 2);
} else show_menu_point("NONE", "", "", "", 2);
show_menu_point("details", $_SERVER["PHP_SELF"]."?show=".($admin?"logfile":"details&amp;a=0&amp;b=".$detail_length), $lang["default"]["logfile"], $lang["menu"]["over_head"].": ".$lang["default"]["logfile"], 1); ?></table></td>
</tr></table><?php
  }
  if($show == "details" && $show_detail_filter) { ?>
<script type="text/javascript">
function filter_help(what) {
  var obj_t = eval("document.f_filter.filter_"+what+"_value");
  var obj_c = eval("document.f_filter.filter_"+what);
  if(obj_t.value != "") obj_c.checked = true;
  else obj_c.checked = false;
}
</script><?php
  }
}

// --- show mainmenu ----------------------------------------------------------------------------------
if(!$show) { ?>
<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center" summary="SHOWlog mainmenu - outer table"><tr><td>
<?php show_header($lang["menu"]["head"]." ".convert_date(date("Y-m-d"),$lang_format)); ?>
<table border="0" cellspacing="2" cellpadding="10" width="100%" align="center" summary="SHOWlog mainmenu">
<tr bgcolor="<?php echo $color["DEF"][1]; ?>"><td width="50%" align="center">
<b><u><?php echo $lang["menu"]["visitors"]; ?>:</u></b><br><span class="count"><?php echo $counter_value; ?></span><br>
<?php echo $lang["menu"]["since"]; ?> <?php echo convert_date($sl_vst_since,$lang_format); ?></td>
<td width="50%" align="center">
<b><u><?php echo $lang["menu"]["entries"];?>:</u></b><br /><span class="count"><?php echo $log_entries; ?></span><br /><?php echo $lm; ?></td>
</tr><tr bgcolor="<?php echo $color["DEF"][1]; ?>"><td align="center" valign="middle">
<table border="0" cellspacing="0" cellpadding="0" summary="SHOWlog mainmenu part 1">
<?php show_menu_point("browser", $_SERVER["PHP_SELF"]."?show=browser", $lang["default"]["browser"], $lang["menu"]["stat_head"].": ".$lang["default"]["browser"], 1);
show_menu_point("os", $_SERVER["PHP_SELF"]."?show=os", $lang["default"]["os"], $lang["menu"]["stat_head"].": ".$lang["default"]["os"], 2);
show_menu_point("referer", $_SERVER["PHP_SELF"]."?show=referer", $lang["default"]["referer"], $lang["menu"]["stat_head"].": ".$lang["default"]["referer"], 2);
show_menu_point("forward", $_SERVER["PHP_SELF"]."?show=forward", $lang["default"]["forward"], $lang["menu"]["stat_head"].": ".$lang["default"]["forward"], 2);
show_menu_point("search", $_SERVER["PHP_SELF"]."?show=search", $lang["default"]["search"], $lang["menu"]["stat_head"].": ".$lang["default"]["search"], 2);
show_menu_point("day", $_SERVER["PHP_SELF"]."?show=day&amp;mb=".$date_mb."&amp;me=".$date_me, $lang["day"]["title_detail"], $lang["menu"]["stat_head"].": ".$lang["menu"]["hits_by_day"], 2);
show_menu_point("month", $_SERVER["PHP_SELF"]."?show=month&amp;year=".$year, $lang["month"]["title_detail"], $lang["menu"]["stat_head"].": ".$lang["menu"]["hits_by_month"], 1); ?>
</table></td>
<td align="center" valign="top">
<table border="0" cellspacing="0" cellpadding="0" summary="SHOWlog mainmenu part 2">
<?php show_menu_point("provider", $_SERVER["PHP_SELF"]."?show=provider", $lang["default"]["provider"], $lang["menu"]["stat_head"].": ".$lang["default"]["provider"], 1);
show_menu_point("domain", $_SERVER["PHP_SELF"]."?show=domain", $lang["default"]["domain"], $lang["menu"]["stat_head"].": ".$lang["default"]["domain"], 2);
show_menu_point("ip2c", $_SERVER["PHP_SELF"]."?show=ip2c", $lang["ip2c"]["title_detail"], $lang["menu"]["stat_head"].": ".$lang["ip2c"]["title_detail"], 2);
show_menu_point("languages", $_SERVER["PHP_SELF"]."?show=languages", $lang["menu"]["languages"], $lang["menu"]["stat_head"].": ".$lang["menu"]["languages"], 2);
show_menu_point("screen", $_SERVER["PHP_SELF"]."?show=screen", $lang["menu"]["screen"], $lang["menu"]["stat_head"].": ".$lang["menu"]["screen"], 2);
if($admin) { show_menu_point("userpass", $_SERVER["PHP_SELF"]."?show=userpass", $lang["menu"]["userpass"], $lang["menu"]["over_head"].": ".$lang["menu"]["userpass"], 2);
} else show_menu_point("NONE", "", "", "", 2);
show_menu_point("details", $_SERVER["PHP_SELF"]."?show=".($admin?"logfile":"details&amp;a=0&amp;b=".$detail_length), $lang["default"]["logfile"], $lang["menu"]["over_head"].": ".$lang["default"]["logfile"], 1); ?>
</table></td>
</tr><tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td colspan="2" align="center"><table border="0" cellspacing="0" cellpadding="0" summary="SHOWlog mainmenu info"><?php
show_menu_point("info", $_SERVER["PHP_SELF"]."?show=info", $lang["menu"]["info"], $lang["menu"]["info"], 1); ?>
</table></td>
</tr>
</table>
<?php if($leave["code"] != "" && $leave["text"] != "") back_button($leave["code"], $leave["text"], $leave["logout"]);
  else show_hr(); ?>
</td></tr></table><?php

// --- show info about showlog ------------------------------------------------------------------------
} elseif($show == "info") {
  show_header($lang[$show]["title_detail"]." <i>SHOW<span class=\"log\">log</span></i>", true, 1, 2);
  if(file_exists($tpl_dir.$tpl_to_use."/template.txt")) $tmpl_data = parse_ini_file($tpl_dir.$tpl_to_use."/template.txt");
  // --- read ip2country-db-version ---
  $ip2c_ver_file = $ip2c_dir."ip2country.dat";
  if(file_exists($ip2c_ver_file)) {
    $ip2c_db_tmp = file($ip2c_ver_file);
    $ip2c_db_ver = convert_date($ip2c_db_tmp[0],$lang_format);
  } else $ip2c_db_ver = $lang["default"]["unknown"];
  ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="Info about SHOWlog">
<tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>" align="justify">
SHOW<span class="log">log</span> / WEB<span class="log">log</span> is free software;
you can redistribute it and/or modify it under the terms of the GNU General Public
License as published by the Free Software Foundation; either version 2 of the License,
or (at your option) any later version.</td>
</tr><tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>">SHOW<span class="log">log</span> uses the IP-to-Country Database
 provided by WebHosting.Info (<a href="http://www.webhosting.info" target="_blank">http://www.webhosting.info</a>),
 available from <a href="http://ip-to-country.webhosting.info" target="_blank">http://ip-to-country.webhosting.info</a>.</td>
</tr><tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>">
SHOW<span class="log">log</span> / WEB<span class="log">log</span> initially was written by Daniel Sokoll&nbsp;-&nbsp;<a href="http://www.sirsocke.de" target="_blank">http://www.sirsocke.de</a></td>
</tr></table><br>
<?php show_header($lang[$show]["add_ver"], true, 2, 3); ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="Info about SHOWlog">
<tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<table border="0" cellspacing="1" cellpadding="2" class="tback" width="100%" summary="Info about fileversions"><tr>
<td class="head" colspan="4" align="left"><?php echo $lang[$show]["add_ver"]; ?></td>
</tr><?php $color_cnt=1;
  show_version("system","SHOW<span class=\"log\">log</span>",($color_cnt++&1?1:2),$showlog_ver);
  show_version("system","WEB<span class=\"log\">log</span>",($color_cnt++&1?1:2),$ver_weblog);
  show_version("modul","Ident_Browser",($color_cnt++&1?1:2),$ident_browser_version);
  show_version("modul","Ident_OS",($color_cnt++&1?1:2),$ident_os_version);
  show_version("modul","Ident_BOT",($color_cnt++&1?1:2),$ident_bot_version);
  show_version("modul","Ident_Searchengine",($color_cnt++&1?1:2),$ident_search_version);
  show_version("modul","Ident_Country",($color_cnt++&1?1:2),$ident_country_version);
  show_version("modul","Ident_Language",($color_cnt++&1?1:2),$ident_language_version);
  show_version("modul","IP2Country",($color_cnt++&1?1:2),$ip2c_version);
  show_version("database","IP2Country",($color_cnt++&1?1:2),convert_date($ip2c_db_ver,$lang_format));
  show_version("database","Botlist",($color_cnt++&1?1:2),convert_date($botlist_date,$lang_format));
  show_version("template",$tmpl_data["name"]." v".$tmpl_data["version"],($color_cnt++&1?1:2),convert_date($tmpl_data["date"],$lang_format),$lang[$show]["author"].": ".$tmpl_data["author"]); ?>
</table></td>
</tr></table><br>
<?php show_header("<i>SHOW<span class=\"log\">log</span></i> ".$lang[$show]["recognizes"], true, 3, 4); ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="Info about SHOWlog">
<tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<table align="center" border="0" cellpadding="1" cellspacing="1" class="tback" width="100%" summary="Info about recognized browsers"><tr>
<td class="head" align="center" colspan="9"><?php echo $lang["default"]["browser"]; ?></td>
<?php
  $i = 0;
  $lines = sizeof($family["browser"]["name"])-2;
  $split = intval($lines/3);
  $split += ($split<($lines/3)?1:0);
  array_multisort($family["browser"]["name"]);

  foreach($family["browser"]["name"] as $value => $key) {
    if(! ($value == "BOT" || $value == "UKN")) {
      if($i%3 == 0) echo "</tr><tr bgcolor=\"".$color["DEF"][($i&1?1:2)]."\">\n";
      echo "<td class=\"head\" align=\"right\" width=\"25\">".($i+1)."</td>\n";
      echo "<td align=\"center\" width=\"16\"><img src=\"".$family["browser"]["icon"][$value]."\" width=\"14\" height=\"14\" alt=\"".$key."\" title=\"".$key."\"></td>\n";
      echo "<td align=\"left\" width=\"29%\">".$key."</td>\n";
      $i++;
    }
  }
  while($i < ($split*3)) {
    echo "<td class=\"head\">&nbsp;</td><td colspan=\"2\">&nbsp;</td>";
    $i++;
  } ?></tr>
</table></td>
</tr><tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<table align="center" border="0" cellpadding="1" cellspacing="1" class="tback" width="100%" summary="Info about recognized operating systems"><tr>
<td class="head" align="center" colspan="9"><?php echo $lang["default"]["os"]; ?></td>
<?php
  $i = 0;
  $lines = sizeof($family["os"]["name"])-2;
  $split = intval($lines/3);
  $split += ($split<($lines/3)?1:0);
  array_multisort($family["os"]["name"]);
  foreach($family["os"]["name"] as $value => $key) {
    if(! ($value == "BOT" || $value == "UKN")) {
      if($value == "WXP") $key .= " (".$lang[$show]["new"].")";
      elseif($value == "WIN") $key .= " (".$lang[$show]["old"].")";
      if($i%3 == 0) echo "</tr><tr bgcolor=\"".$color["DEF"][($i&1?1:2)]."\">\n";
      echo "<td class=\"head\" align=\"right\" width=\"25\">".($i+1)."</td>\n";
      echo "<td align=\"center\" width=\"16\"><img src=\"".$family["os"]["icon"][$value]."\" width=\"14\" height=\"14\" alt=\"".$key."\" title=\"".$key."\"></td>\n";
      echo "<td align=\"left\" width=\"29%\">".$key."</td>\n";
      $i++;
    }
  }
  while($i < ($split*3)) {
    echo "<td class=\"head\">&nbsp;</td><td colspan=\"2\">&nbsp;</td>";
    $i++;
  } ?></tr>
</table></td>
</tr><tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<table align="center" border="0" cellpadding="1" cellspacing="1" class="tback" width="100%" summary="Info about recognized searchengines"><tr>
<td class="head" align="center" colspan="9"><?php echo $lang["default"]["search"]; ?></td>
<?php
  $i = 0;
  $lines = sizeof($ident_search);
  $split = intval($lines/3);
  $split += ($split<($lines/3)?1:0);
  ksort($ident_search);
  foreach($ident_search as $value => $key) {
    if($i%3 == 0) echo "</tr><tr bgcolor=\"".$color["DEF"][($i&1?1:2)]."\">\n";
    echo "<td class=\"head\" align=\"right\" width=\"25\">".($i+1)."</td>\n";
    echo "<td align=\"center\" width=\"16\"><img src=\"".$key."\" width=\"14\" height=\"14\" alt=\"".$value."\" title=\"".$value."\"></td>\n";
    echo "<td align=\"left\" width=\"29%\">".$value."</td>\n";
    $i++;
  }
  while($i < ($split*3)) {
    echo "<td class=\"head\">&nbsp;</td><td colspan=\"2\">&nbsp;</td>";
    $i++;
  } ?></tr>
</table></td>
</tr><tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<table align="center" border="0" cellpadding="1" cellspacing="1" class="tback" width="100%" summary="Info about recognized searchengines"><tr>
<td class="head" align="center" colspan="9"><?php echo $lang["default"]["bots"]; ?></td>
<?php
  $i = 0;
  $lines = sizeof($ident_bot);
  $split = intval($lines/3);
  $split += ($split<($lines/3)?1:0);
  ksort($ident_bot);
  foreach($ident_bot as $value => $key) {
    if($i%3 == 0) echo "</tr><tr bgcolor=\"".$color["DEF"][($i&1?1:2)]."\">\n";
    echo "<td class=\"head\" align=\"right\" width=\"25\">".($i+1)."</td>\n";
    echo "<td align=\"center\" width=\"16\"><img src=\"".$key."\" width=\"14\" height=\"14\" alt=\"".$value."\" title=\"".$value."\"></td>\n";
    echo "<td align=\"left\" width=\"29%\">".$value."</td>\n";
    $i++;
  }
  while($i < ($split*3)) {
    echo "<td class=\"head\">&nbsp;</td><td colspan=\"2\">&nbsp;</td>";
    $i++;
  } ?></tr>
</table></td>
</tr>
</table><br>
<?php show_header("<i>SHOW<span class=\"log\">log</span></i> ".$lang[$show]["updates"], true, 4, "end"); ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="Info about SHOWlog">
<tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>">
SHOW<span class="log">log</span> / WEB<span class="log">log</span> projectpage: <a href="http://www.lady-s.org/stats/" target="_blank">http://www.lady-s.org/stats/</a></td>
</tr></table>
<?php  
  back_button();

// --- check if there are no entries -----------------------------------------------------------------
} elseif($log_entries < 1 && !$detail_filter) {
  $show = "error";
  $error_string[$log_file." "] = $lang["error"]["no_entries"];  

// --- show detailed statistics -----------------------------------------------------------------------
} elseif($show == "details") {
  if($admin) show_header($lang[$show]["header"], true, 1, "end", false, $_SERVER["PHP_SELF"]."?show=logfile&amp;id=".$id);
  else show_header($lang[$show]["header"], true, 1, "end");
  if($_GET["b"] > $log_entries) $_GET["b"] = $log_entries; ?>  
<table border="0" cellspacing="2" cellpadding="3" width="780" align="center" summary="logfile details"><tr><td bgcolor="<?php echo $color["DEF"][2]; ?>" align="center">
<?php if($show_detail_filter && !$detail_filter_show_not) { ?><form name="f_filter" method="POST" action="<?php echo $_SERVER["PHP_SELF"]."?show=details&amp;a=0&amp;b=".$log_entries."&amp;id=".$id; ?>">
<table border="0" cellspacing="1" cellpadding="1" class="tback" width="100%" summary="logfile filter form"><tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td width="12%" rowspan="5" valign="top"><?php echo $lang[$show]["filter"]; ?>:</td>
<td width="2%"><input type="checkbox" name="filter_date_1" value="1"<?php echo ($detail_filter_date_1?" checked=\"checked\"":""); ?>></td>
<td width="12%"><?php echo $lang[$show]["date"]." (".$lang[$show]["from"]; ?>):</td>
<td width="24%"><input type="text" name="filter_date_1_value" value="<?php echo ($detail_filter_date_1?$detail_filter_date_1:""); ?>"
size="12" maxlength="10" onkeyup="filter_help('date_1')"> [<?php echo $lang[$show]["date_format"]; ?>]</td>
<td width="2%"><input type="checkbox" name="filter_date_2" value="1"<?php echo ($detail_filter_date_2?" checked=\"checked\"":""); ?>></td>
<td width="12%"><?php echo $lang[$show]["date"]." (".$lang[$show]["until"]; ?>):</td>
<td width="24%"><input type="text" name="filter_date_2_value" value="<?php echo ($detail_filter_date_2?$detail_filter_date_2:""); ?>"
size="12" maxlength="10" onkeyup="filter_help('date_2')"> [<?php echo $lang[$show]["date_format"]; ?>]</td>
<td width="12%" rowspan="5" align="right" valign="bottom"><input type="submit" name="filter" value="<?php echo $lang[$show]["set_filter"]; ?>"></td>
</tr><tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td><input type="checkbox" name="filter_ip" value="1"<?php echo ($detail_filter_ip?" checked=\"checked\"":""); ?>></td>
<td><?php echo $lang[$show]["ip"]; ?>:</td>
<td><input type="text" name="filter_ip_value" value="<?php echo ($detail_filter_ip?$detail_filter_ip:""); ?>"
size="25" maxlength="10" onkeyup="filter_help('ip')"></td>
<td><input type="checkbox" name="filter_host" value="1"<?php echo ($detail_filter_host?" checked=\"checked\"":""); ?>></td>
<td><?php echo $lang[$show]["host"]; ?>:</td>
<td><input type="text" name="filter_host_value" value="<?php echo ($detail_filter_host?$detail_filter_host:""); ?>"
size="25" maxlength="30" onkeyup="filter_help('host')"></td>
</tr><tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td><input type="checkbox" name="filter_browser" value="1"<?php echo ($detail_filter_browser?" checked=\"checked\"":""); ?>></td>
<td><?php echo $lang["default"]["browser"]; ?>:</td>
<td><input type="text" name="filter_browser_value" value="<?php echo ($detail_filter_browser?$detail_filter_browser:""); ?>"
size="25" maxlength="30" onkeyup="filter_help('browser')"></td>
<td><input type="checkbox" name="filter_os" value="1"<?php echo ($detail_filter_os?" checked=\"checked\"":""); ?>></td>
<td><?php echo $lang[$show]["os"]; ?>:</td>
<td><input type="text" name="filter_os_value" value="<?php echo ($detail_filter_os?$detail_filter_os:""); ?>"
size="25" maxlength="30" onkeyup="filter_help('os')"></td>
</tr><tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td><input type="checkbox" name="filter_referer" value="1"<?php echo ($detail_filter_referer?" checked=\"checked\"":""); ?>></td>
<td><?php echo $lang["default"]["referer"]; ?>:</td>
<td><input type="text" name="filter_referer_value" value="<?php echo ($detail_filter_referer?$detail_filter_referer:""); ?>"
size="25" maxlength="30" onkeyup="filter_help('referer')"></td>
<td><input type="checkbox" name="filter_language" value="1"<?php echo ($detail_filter_language?" checked=\"checked\"":""); ?>></td>
<td><?php echo $lang[$show]["language"]; ?>:</td>
<td><input type="text" name="filter_language_value" value="<?php echo ($detail_filter_language?$detail_filter_language:""); ?>"
size="25" maxlength="10" onkeyup="filter_help('language')"></td>
</tr><tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td><input type="checkbox" name="filter_fulltext" value="1"<?php echo ($detail_filter_fulltext?" checked=\"checked\"":""); ?>></td>
<td><?php echo $lang[$show]["fulltext"]; ?>:</td>
<td><input type="text" name="filter_fulltext_value" value="<?php echo ($detail_filter_fulltext?$detail_filter_fulltext:""); ?>"
size="25" maxlength="60" onkeyup="filter_help('fulltext')"></td>
<td><input type="checkbox" name="filter_bots" value="1"<?php echo ($detail_filter_bots?" checked=\"checked\"":""); ?>></td>
<td colspan="2"><?php echo $lang[$show]["hide"]; ?></td>
</tr></table></form></td>
</tr><tr><td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center"><?php } ?><table align="center" border="0" cellpadding="2" cellspacing="1" class="tback" summary="Logfile details">
<tr>
<td align="left" class="default" bgcolor="<?php echo $color["DEF"][1]; ?>">&nbsp;</td>
<td align="left" class="head" colspan="2"><?php echo $lang[$show]["date"]; ?>&nbsp;/&nbsp;<?php echo $lang[$show]["time"]; ?></td>
<td align="left" class="head"><?php echo $lang[$show]["ip"]; ?>&nbsp;/&nbsp;<?php echo $lang[$show]["host"]; ?>&nbsp;/&nbsp;<?php echo $lang["default"]["forward"]; ?></td>
<td align="left" class="head" colspan="2"><?php echo $lang["default"]["browser"]; ?>&nbsp;/&nbsp;<?php echo $lang["default"]["referer"]; ?></td>
<td align="center" class="head"><?php echo $lang[$show]["language"]; ?></td>
<td align="center" class="head"><?php echo $lang[$show]["screen"]; ?></td>
<td align="center" class="head"><?php echo $lang[$show]["colors"]; ?></td>
<td align="right" class="head" colspan="2"><?php echo $lang[$show]["os"]; ?></td>
</tr><?php
  if($detail_filter) $_GET["b"] = $log_entries;
  for ($i=$_GET["a"]; $i<$_GET["b"]; $i++) {
    if(!isset($data[$i])) {
      break;
    }
    if($data[$i]["OS_KEY"]==$family["os"]["name"]["BOT"]) {
      $tmp_name = $lang["default"]["crawler"];
      $row_color = $color["BOT"][($i&1?2:1)];
    } elseif($data[$i]["OS_KEY"]==$family["os"]["name"]["UKN"]) {
      $tmp_name = $lang["default"]["unknown"];
      $row_color = $color["UKN"][($i&1?2:1)];
    } else {
      $tmp_name = $data[$i]["OS_NAME"];
      $row_color = $color["DEF"][($i&1?2:1)];
    } 
    if($data[$i]["B_KEY"]==$family["browser"]["name"]["UKN"]) $row_color = $color["UKN"][($i&1?2:1)];
    $browser_name = ($data[$i]["B_KEY"]==$family["browser"]["name"]["BOT"]?$lang["default"]["crawler"]:($data[$i]["B_KEY"]==$unknown_text?$lang["default"]["unknown"]:$data[$i]["B_NAME"]));
    $browser_image = $family["browser"]["icon"][$data[$i]["B_KEY"]];
    $browser_text = $lang["default"]["browser"].": ";
    $os_name = $lang[$show]["os"].": ".$tmp_name;
    if($browser_name == $lang["default"]["crawler"]) {
      $browser_text = "";
      $os_name = $tmp_name;
      if($tmp_robot = ident_robot($data[$i]["AGENT"])) {
	$browser_name = $tmp_robot[1];
	$browser_image = $pic["dir"]."i_bot".($tmp_robot[0]?"_".$tmp_robot[0]:"").".png";
	$os_name .= ": ".$browser_name;
      }
    }
    $browser_text .= $browser_name;
    $tmp_referer = ($data[$i]["REFERER"]==$unknown_text?"---":$data[$i]["REFERER"]);
    $tmp_referer = (strlen($tmp_referer)>84?substr($tmp_referer,0,81)."<span class=\"log\">...</span>":$tmp_referer);

    if(strstr($data[$i]["HOST"], ".")) {
      $tmp_host = explode(".", trim($data[$i]["HOST"]));
      $tmp_country = array_pop($tmp_host);
      $tmp_country = (preg_match("/[0-9]/",$tmp_country) || $tmp_country ==""?$unknown_text:strtolower($tmp_country)); 
    } else $tmp_country = $unknown_text;
    $tmp_country_array = ident_country($tmp_country);
    $tmp_country = ($tmp_country_array["flag"]?"<img src=\"".$ident_country_files.$tmp_country_array["flag"]."\" height=\"10\" width=\"16\" title=\"".$lang[$show]["domain"].": ".$lang[$show]["host"]." ".$tmp_country.": ".($tmp_country_array["text"]?$tmp_country_array["text"]:"")."\" alt=\"".$lang[$show]["domain"].": ".$lang[$show]["host"]." ".$tmp_country.": ".($tmp_country_array["text"]?$tmp_country_array["text"]:"")."\"\n onmouseover=\"window.status='".$lang[$show]["domain"].": ".$lang[$show]["host"]." ".$tmp_country.": ".($tmp_country_array["text"]?$tmp_country_array["text"]:"")."'; return true;\"\n onmouseout=\"window.status=''; return true;\">":"---");
    $tmp_ip2c = ip2country($data[$i]["IP"]);
    $tmp_country_array = ident_country($tmp_ip2c);
    $tmp_ip2c = ($tmp_country_array["flag"]?"<img src=\"".$ident_country_files.$tmp_country_array["flag"]."\" height=\"10\" width=\"16\"\n title=\"".$lang[$show]["domain"].": ".$lang["ip2c"]["title_detail"]." ".$data[$i]["IP"].": ".($tmp_country_array["text"]?$tmp_country_array["text"]."":"")."\" alt=\"".$lang[$show]["domain"].": ".$lang["ip2c"]["title_detail"]." ".$data[$i]["IP"].": ".($tmp_country_array["text"]?$tmp_country_array["text"]."":"")."\"\n onmouseover=\"window.status='".$lang[$show]["domain"].": ".$lang["ip2c"]["title_detail"]." ".$data[$i]["IP"].": ".($tmp_country_array["text"]?$tmp_country_array["text"]:"")."'; return true;\"\n onmouseout=\"window.status=''; return true;\">":"&nbsp;");
    if($data[$i]["LANGUAGE"]!="---") {
      $tmp_language_array = ident_language($data[$i]["LANGUAGE"]);
      $tmp_language = ($tmp_language_array["flag"]?"<img src=\"".$ident_country_files.$tmp_language_array["flag"]."\" height=\"10\" width=\"16\" title=\"".$lang["default"]["languages"]." ".$data[$i]["LANGUAGE"].": ".($tmp_language_array["text"]?$tmp_language_array["text"]:"")."\" alt=\"".$lang["default"]["languages"]." ".$data[$i]["LANGUAGE"].": ".($tmp_language_array["text"]?$tmp_language_array["text"]:"")."\"\n onmouseover=\"window.status='".$lang["default"]["languages"]." ".$data[$i]["LANGUAGE"].": ".($tmp_language_array["text"]?$tmp_language_array["text"]:"")."'; return true;\"\n onmouseout=\"window.status=''; return true;\">":$data[$i]["LANGUAGE"]);
    } else $tmp_language = 0;
    $tmp_agent = $data[$i]["AGENT"];
    if(strstr($tmp_agent, "http://")) {
      $tmp_work = preg_match("/(^.*)(http:\/\/.*)([;\)]{1}.*)/",$tmp_agent, $regs);
      if(isset($regs[1]) && isset($regs[2]) && isset($regs[3])) {
	if(strpos($regs[2],";")) {
	  $tmp_regs = explode(";",$regs[2]);
	  $regs[2] = $tmp_regs[0];
	  $regs[3] = ";".$tmp_regs[1].$regs[3];
	}
	$tmp_agent = str_replace(" ","&nbsp;",$regs[1])."<a href=\"".$regs[2]."\" target=\"_blank\" class=\"detail\">".str_replace(" ","&nbsp;",$regs[2])."</a>".str_replace(" ","&nbsp;",$regs[3]);
      }
    } elseif(strlen($tmp_agent) < 90) $tmp_agent = str_replace(" ","&nbsp;",$tmp_agent); ?>
<tr bgcolor="<?php echo $row_color; ?>">
<td rowspan="3" align="right" valign="top" class="head"><?php echo ($i+1+$line_number); ?><br><i><span class="default"><?php echo ($i+1); ?></span></i></td> 
<td class="default" align="left" valign="top" colspan="2"><?php echo convert_date($data[$i]["DATE"],$lang_format); ?></td>
<td class="default" align="left" valign="top"><?php echo $data[$i]["IP"]; ?></td>
<td class="default" align="left" colspan="7"><?php echo $tmp_agent; ?></td>
</tr><tr bgcolor="<?php echo $row_color; ?>">
<td class="default" align="left" colspan="2"><?php echo $data[$i]["TIME"]; ?></td>
<td class="default" align="left"><?php echo $data[$i]["HOST"]; ?></td>
<td class="default" rowspan="2" align="center"><img src="<?php echo $browser_image; ?>" height="14" width="14" alt="<?php echo $browser_name; ?>" title="<?php echo $browser_name; ?>"
 onmouseover="window.status='<?php echo $browser_text; ?>'; return true;"
 onmouseout="window.status=''; return true;"></td>
<td class="default" align="left" colspan="5"><?php if($tmp_referer != "---") { ?><a href="<?php echo $data[$i]["REFERER"]; ?>" target="_blank" class="detail"><?php } echo $tmp_referer; if ($tmp_referer != "---") { ?></a><?php } ; ?></td>
<td class="default" align="left" rowspan="2"><img src="<?php echo $family["os"]["icon"][$data[$i]["OS_KEY"]]; ?>" width="14" height="14" alt="<?php echo $os_name; ?>" title="<?php echo $os_name; ?>"
 onmouseover="window.status='<?php echo $os_name; ?>'; return true;"
 onmouseout="window.status=''; return true;"></td>
</tr><tr bgcolor="<?php echo $row_color; ?>">
<td class="default" align="center"><?php echo $tmp_country; ?></td>
<td class="default" align="center"><?php echo $tmp_ip2c; ?></td>
<td class="default" align="left"><?php echo $data[$i]["FORWARD"]; ?></td>
<td class="default" align="left"><?php echo $browser_name; ?></td>
<td class="default" align="left"><?php echo ($tmp_language?$tmp_language." ":"").$data[$i]["LANGUAGE"]; ?></td>
<td class="default" align="center"><?php echo $data[$i]["SCREEN"]; ?></td>
<td class="default" align="center"><?php echo $data[$i]["COLORS"]; ?></td>
<td class="default" align="right"><?php echo $tmp_name; ?></td>
</tr><?php } ?></table><?php if($detail_filter && $log_entries==0) echo "<b>".$lang["error"]["no_matches"]."</b>"; ?>
<table border="0" cellspacing="4" cellpadding="0" width="100%" bgcolor="<?php echo $color["DEF"][2]; ?>" summary="Logfile navigation"><tr>
<td align="left" width="33%"><?php echo $lang[$show]["view"].": ".($_GET["a"]+1)." ".$lang[$show]["to"]." ".$_GET["b"]; ?></td>
<td align="center" width="33%"><table border="0" cellspacing="1" cellpadding="3" class="tback" summary="Logfile navigation buttons"><tr><?php if($detail_filter && !$detail_filter_show_not) { ?>
<td bgcolor="<?php echo $color["DEF"][1]; ?>"><a class="link" href="<?php echo $_SERVER["PHP_SELF"]."?show=details&amp;a=0&amp;b=".$detail_length."&amp;id=".$id; ?>"><?php echo $lang[$show]["remove_filter"]; ?></a></td>
<?php } else { ?>
<td width="30" align="left" bgcolor="<?php echo $color["DEF"][1]; ?>"><?php if(!isset($_GET["filter"]) && $log_entries > $detail_length && ($_GET["a"] > 0 || $_GET["b"] == $log_entries)) { ?><a class="link" href="<?php echo $_SERVER["PHP_SELF"]; ?>?show=details&amp;a=0&amp;b=<?php echo $detail_length.($detail_filter_bots?"&amp;filter_bots=1":"").($login_require?"&amp;id=".$id:""); ?>"
 onmouseover="window.status='<?php echo $lang[$show]["first"]; ?>'; return true;"
 onmouseout="window.status=''; return true;"><?php echo $navi_start; ?></a><?php
} else echo "&nbsp;"; ?></td>
<td width="30" align="center" bgcolor="<?php echo $color["DEF"][1]; ?>"><?php if($_GET["a"] > 0) { ?><a class="link" href="<?php echo $_SERVER["PHP_SELF"]; ?>?show=details&amp;a=<?php echo $tmp = (($_GET["a"]-$detail_length) < 0?0:($_GET["a"]-$detail_length)); ?>&amp;b=<?php echo ($tmp != 0 ?$_GET["a"]:$detail_length).($detail_filter_bots?"&amp;filter_bots=1":"").($login_require?"&amp;id=".$id:""); ?>"
 onmouseover="window.status='<?php echo $lang[$show]["prev"]; ?>'; return true;"
 onmouseout="window.status=''; return true;"><?php echo $navi_prev; ?></a><?php
} else echo "&nbsp;"; ?></td>
<td width="100" align="center" bgcolor="<?php echo $color["DEF"][1]; ?>"><?php if($_GET["a"] != 0 || $_GET["b"] != $log_entries) { ?><a class="link" href="<?php echo $_SERVER["PHP_SELF"]; ?>?show=details&amp;a=0&amp;b=<?php echo $log_entries.($detail_filter_bots?"&amp;filter_bots=1":"").($login_require?"&amp;id=".$id:""); ?>"
 onmouseover="window.status='<?php echo $lang[$show]["all"]; ?>'; return true;"
 onmouseout="window.status=''; return true;"><?php echo $lang[$show]["all"]; ?></a><?php
} else echo "&nbsp;"; ?></td>
<td width="30" align="center" bgcolor="<?php echo $color["DEF"][1]; ?>"><?php if($_GET["b"] < $log_entries)  { ?><a class="link" href="<?php echo $_SERVER["PHP_SELF"]; ?>?show=details&amp;a=<?php echo $_GET["b"]; ?>&amp;b=<?php echo ($_GET["b"]+$detail_length > $log_entries?$log_entries:($_GET["b"]+$detail_length)).($detail_filter_bots?"&amp;filter_bots=1":"").($login_require?"&amp;id=".$id:""); ?>"
 onmouseover="window.status='<?php echo $lang[$show]["next"]; ?>'; return true;"
 onmouseout="window.status=''; return true;"><?php echo $navi_next; ?></a><?php
} else echo "&nbsp;"; ?></td>
<td width="30" align="right" bgcolor="<?php echo $color["DEF"][1]; ?>"><?php if(!isset($_GET["filter"]) && $log_entries > $detail_length && ($_GET["b"] < $log_entries || $_GET["a"] == 0))  { ?><a class="link" href="<?php echo $_SERVER["PHP_SELF"]; ?>?show=details&amp;a=<?php echo ($log_entries-$detail_length < $_GET["b"]?($_GET["b"]==$log_entries?$log_entries-$detail_length:$_GET["b"]):$log_entries-$detail_length); ?>&amp;b=<?php echo $log_entries.($detail_filter_bots?"&amp;filter_bots=1":"").($login_require?"&amp;id=".$id:""); ?>"
 onmouseover="window.status='<?php echo $lang[$show]["last"]; ?>'; return true;"
 onmouseout="window.status=''; return true;"><?php echo $navi_end; ?></a><?php
} else echo "&nbsp;"; ?></td><?php } ?>
</tr></table></td>
<td align="right" width="33%"><?php echo $lang["default"]["total"]; ?>: <?php echo $log_entries; ?></td>
</tr></table></td>
</tr></table>
<?php
  if(!$detail_filter_show_not) {
    back_button(($admin?$_SERVER["PHP_SELF"]. "?show=logfile":""));
  } else {
    back_button("javascript:self.close();", "close");
  }

// --- show hits by day / hits by month ---------------------------------------------------------------
} elseif($show == "day" || $show == "month") {
  $tmp_array = array_keys($stat_details);
  $tmp_date = explode("-",$tmp_array[1]);
  if($show == "day") {
    show_header($lang[$show]["title_detail"]." ".$lang["month"]["m".$tmp_date[1]]." ".$tmp_date[0], true, 1, "end", $show."&amp;mb=".$_GET["mb"]."&amp;me=".$_GET["me"]);
  } else {
    show_header($lang[$show]["title_detail"]." ".$_GET["year"], true, 1, "end", $show."&amp;year=".$_GET["year"]);
  }
  ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="Hits statistics"><tr><td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<table align="center" border="0" cellpadding="1" cellspacing="1" class="tback" width="<?php echo ($show=="day"?620:370); ?>" summary="Hits statistics">
<tr><td bgcolor="<?php echo $color["DEF"][2]; ?>" width="1"><img src="<?php echo $pic["fake"]; ?>" alt="" height="1" width="<?php echo ($show=="day"?15:25); ?>" align="top"></td><?php
  $maxi = max($stat_details);
  $default_skale = 25;
  $skale = $default_skale;
  $max_height = 250;
  while(($skale*$maxi)>$max_height) {
    $skale=$skale/2;
  }
  $i = 0;
  foreach($stat_details as $date => $hits) {
    if($show == "day") {
      $tmp_date = explode("-",$date);
      $tmp_day = date("w", mktime(0, 0, 0, $tmp_date[1], $tmp_date[2], $tmp_date[0]));    
      $tmp_color = ($tmp_day == 6 || $tmp_day == 0?"UKN":"DEF");
    } else $tmp_color = "DEF";
    echo "<td bgcolor=\"".$color[$tmp_color][($i&1?2:1)]."\" align=\"center\" class=\"default\">".$hits."</td>\n";
    $i++;
  }
  reset($stat_details); ?>
</tr><tr>
<td bgcolor="<?php echo $color["DEF"][2]; ?>" style="font-size: 8px;" valign="top" align="right"><?php echo ($default_skale/$skale*10); ?><img src="<?php echo $pic["fake"]; ?>" alt="" height="<?php echo ($max_height/2)-2; ?>" width="1" align="top"></td>
<?php
  $i=0;
  $bots_total=0;
  $hits_total=0;
  foreach($stat_details as $date => $hits) {
    if($show == "day") {
      // --- choose backgroundcolor ---
      if(date("Y-m-d") == $date) $tmp_color = "BOT";
      elseif($hits == $maxi && $hits != 0) $tmp_color = "MAX";
      else {    
        $tmp_date = explode("-",$date);
        $tmp_day = date("w", mktime(0, 0, 0, $tmp_date[1], $tmp_date[2], $tmp_date[0]));
        $tmp_color = ($tmp_day == 6 || $tmp_day == 0?"UKN":"DEF");
      }
    } else {
      if($hits == $maxi && $hits != 0) $tmp_color = "MAX";
      elseif(date("m") == $date && (isset($_GET["year"]) && date("Y") == $_GET["year"])) $tmp_color = "BOT";
      else $tmp_color = "DEF";
    }
    $bots = (isset($stat_family[$date])?$stat_family[$date]:0);
    $img_height_1 = round((($hits-$bots)*$skale),0);
    $img_height_2 = round(($bots*$skale),0); ?>
<td rowspan="2" style="background-image: url(<?php echo $pic["v_t_back"]; ?>);" bgcolor="<?php echo $color[$tmp_color][($i&1?2:1)]; ?>" align="center" valign="bottom"<?php echo ($show == "month"?" width=\"8%\"":""); ?>
 onmouseover="window.status='<?php echo ($show=="day"?convert_date($date,$lang_format):$lang["month"]["m".$date])."   ".$lang["menu"]["visitors"].":".($hits-$bots)."   ".$lang["default"]["bots"].":".(isset($bots)?$bots:0)."   ".$lang["default"]["total"].":".(isset($hits)?$hits:0); ?>'; return true;"
 onmouseout="window.status=''; return true;"><?php
    if($img_height_1 || $img_height_2) {
      echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" summary=\"Hits by day / month\">";
      if($img_height_1) printf("<tr>\n<td><img src=\"%s\" height=\"%d\" width=\"10\" alt=\"%s: %3.0f\" title=\"%s: %3.0f\"></td>\n</tr>",$pic["v_bar_1"],$img_height_1,$lang["menu"]["visitors"],( $hits-$bots),$lang["menu"]["visitors"],( $hits-$bots));
      if($img_height_2) printf("<tr>\n<td><img src=\"%s\" height=\"%d\" width=\"10\" alt=\"%s: %3.0f\" title=\"%s: %3.0f\"></td>\n</tr>",$pic["v_bar_2"],$img_height_2,$lang["default"]["bots"],$bots,$lang["default"]["bots"],$bots);
      echo "</table>\n";
    } else echo "&nbsp;";
    echo "</td>\n";
    $bots_total += $bots;
    $hits_total += $hits;
    $i++;
  }
  $hits_total_div = ($hits_total?$hits_total:1);
//  echo $middle_hits = round(($hits_total_div/($show=="day"?30:12)),2);
//  echo $middle_hits = round((($hits_total-$bots_total)/($show=="day"?30:12)),2);
//  echo $middle_hits = round(($bots_total/($show=="day"?30:12)),2);
  reset($stat_details); ?>
</tr><tr><td bgcolor="<?php echo $color["DEF"][2]; ?>" style="font-size: 8px;" valign="top" align="right"><?php echo ($default_skale/$skale*5); ?><img src="<?php echo $pic["fake"]; ?>" alt="" height="<?php echo ($max_height/2)-2; ?>" width="1" align="top"></td>
</tr><tr>
<td bgcolor="<?php echo $color["DEF"][2]; ?>" rowspan="2"></td>
<?php
  foreach($stat_details as $date => $hits) {
    if($show == "day") $tmp_date = explode("-",$date);
    echo "<td class=\"head\" align=\"center\">".($show == "day"?$tmp_date[2]:$date)."</td>\n";
  } ?>
</tr><?php 
  if(!isset($_GET["print"])) {
    echo "<tr>\n";
    $i=0;
    foreach($stat_details as $date => $hits) {
      if($show == "day") {
	$tmp_date = explode("-",$date);
	$tmp_day = date("w", mktime(0, 0, 0, $tmp_date[1], $tmp_date[2], $tmp_date[0]));    
	$tmp_color = ($tmp_day == 6 || $tmp_day == 0?"UKN":"DEF");
      } else $tmp_color = "DEF";
      echo "<td bgcolor=\"".$color[$tmp_color][($i&1?2:1)]."\" align=\"center\">\n";
      if($hits) {
	echo "<a href=\"".$_SERVER["PHP_SELF"]."?show=details&amp;a=0&amp;b=".$log_entries."&amp;filter=".$show."&amp;value=".($show=="day"?$date:$_GET["year"]."-".$date).($explicit?"&amp;explicit=1":"").($id!=""?"&amp;id=".$id:"")."\" target=\"_blank\"><img src=\"".$pic["dir"]."n_filter.png\" width=\"11\" height=\"10\" border=\"0\" alt=\"".$lang["default"]["show_details"].": ".($show=="day"?convert_date($date,$lang_format):$lang["month"]["m".$date]." ".$_GET["year"])."\" title=\"".$lang["default"]["show_details"].": ".($show=="day"?convert_date($date,$lang_format):$lang["month"]["m".$date]." ".$_GET["year"])."\"\n onmouseover=\"window.status='".$lang["default"]["show_details"].": ".($show=="day"?convert_date($date,$lang_format):$lang["month"]["m".$date]." ".$_GET["year"])."'; return true;\"\n onmouseout=\"window.status=''; return true;\"></a>\n";
      } else echo "<img src=\"".$pic["dir"]."fake.png\" width=\"11\" height=\"10\" border=\"0\" alt=\"\">";
      echo "</td>\n";
      $i++;
    }
  echo "</tr>\n";
  } ?>
</table></td>
</tr><tr>
<td bgcolor="<?php echo $color["DEF"][2]; ?>"><?php
  // --- create links for older statistics ---
  if(isset($data[0]["DATE"])) {
    $tmp_date = explode("-",$data[0]["DATE"]);
    $month = 1;
    $year = $tmp_date[0];
    $tmp_start = $tmp_work = date("Y-m-d", mktime(0, 0, 0, $tmp_date[1], $tmp_date[2], $tmp_date[0]));
    $tmp_date_ymd = explode("-",$last_log_date);
    $tmp_end = date("Y-m-d",mktime(0, 0, 0, $tmp_date_ymd[1], 1, $tmp_date_ymd[0]));
    ?>
<table border="0" cellspacing="0" cellpadding="0" width="100%" summary="Hits navigation"><tr>
<td width="25%" align="left"><table align="center" border="0" cellpadding="1" cellspacing="1" class="tback" width="100%" summary="Hits navigation"><tr bgcolor="<?php echo $color["DEF"][2]; ?>">
<td align="left" width="10"><img src="<?php echo $pic["v_bar_1"]; ?>" height="11" width="10" alt="<?php echo $lang["menu"]["visitors"]; ?>" title="<?php echo $lang["menu"]["visitors"]; ?>"></td>
<td align="left"><?php echo $lang["menu"]["visitors"]; ?></td>
<td align="right"><?php echo ($hits_total-$bots_total); ?></td>
<td align="right"><?php printf("%3.2f",(($hits_total-$bots_total)/$hits_total_div)*100); ?>%</td>
</tr><tr bgcolor="<?php echo $color["DEF"][2]; ?>">
<td align="left" width="10"><img src="<?php echo $pic["v_bar_2"]; ?>" height="11" width="10" alt="<?php echo $lang["default"]["bots"]; ?>" title="<?php echo $lang["default"]["bots"]; ?>"></td>
<td align="left"><?php echo $lang["default"]["bots"]; ?></td>
<td align="right"><?php echo $bots_total; ?></td>
<td align="right"><?php printf("%3.2f",($bots_total/$hits_total_div)*100); ?>%</td>
</tr>
</table></td>
<td width="50%" align="center"><?php
    if(!isset($_GET["print"])) { ?>
<form method="post" action="<?php echo $_SERVER["PHP_SELF"]."?show=".$show.($login_require?"&amp;id=".$id:""); ?>" name="data">
<table align="center" border="0" cellpadding="1" cellspacing="1" class="tback" summary="Hits navigation">
<tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td width="30" align="center">
<?php
// --- hits by day navigation ---
      if($show == "day") {
        $date_tb = date("Y-m-d",mktime(0, 0, 0, $tmp_date[1], 1, $tmp_date[0]));
        $date_te = date("Y-m-d",mktime(0, 0, 0, $tmp_date[1], date("t",mktime(0, 0, 0, $tmp_date[1], 1, $tmp_date[0])), $tmp_date[0]));
        $tmp_array = explode("-",$_GET["mb"]);
        $date_pb = date("Y-m-d",mktime(0, 0, 0, ($tmp_array[1]-1), 1, $tmp_array[0]));
        $date_pe = date("Y-m-d",mktime(0, 0, 0, ($tmp_array[1]-1), date("t",mktime(0, 0, 0, ($tmp_array[1]-1), 1, $tmp_array[0])), $tmp_array[0]));
        $p_array = explode("-",$date_pb);
        if(substr($_GET["mb"],0,-3) != substr($tmp_start,0,-3)) {
          echo "<a href=\"".$_SERVER["PHP_SELF"]."?show=day&amp;mb=".$date_tb."&amp;me=".$date_te.($login_require?"&amp;id=".$id:"")."\" class=\"link\"\n";
          echo " onmouseover=\"window.status='".$lang["month"]["m".$tmp_date[1]]." ".$tmp_date[0]."'; return true;\"\n";
          echo " onmouseout=\"window.status=''; return true;\">".$navi_start."</a>";
        } else echo "&nbsp;"; ?></td>
<td width="30" align="center"><?php 
        if(substr($_GET["mb"],0,-3) != substr($tmp_start,0,-3)) {
	  echo "<a href=\"".$_SERVER["PHP_SELF"]."?show=day&amp;mb=".$date_pb."&amp;me=".$date_pe.($login_require?"&amp;id=".$id:"")."\" class=\"link\"\n";
	  echo " onmouseover=\"window.status='".$lang["month"]["m".$p_array[1]]." ".$p_array[0]."'; return true;\"\n";
	  echo " onmouseout=\"window.status=''; return true;\">".$navi_prev."</a>";
	} else echo "&nbsp;"; ?></td>
<td valign="middle"><select name="monthly" onchange="javascript: document.data.submit()">
<?php
        $i = 1;  
        while($tmp_work < $tmp_end) {
	  $tmp_work = date("Y-m-d", mktime(0, 0, 0, $month, date("t", mktime(0, 0, 0, $month, 1, $tmp_date[0])), $tmp_date[0]));
	  $date_mb = date("Y-m-d", mktime(0, 0, 0, $month, 1, $tmp_date[0]));
	  $date_me = date("Y-m-d", mktime(0, 0, 0, $month, date("t", mktime(0, 0, 0, $month, 1, $tmp_date[0])), $tmp_date[0]));
	  $tmp_array = explode("-",$date_mb);
	  if($tmp_work >= $tmp_start) {
	    echo "<option value=\"".$date_mb."|".$date_me."\"".($_GET["mb"]==$date_mb?"selected=\"selected\"":"").">".$tmp_array[1]." - ".$tmp_array[0]."</option>\n";
	  }
	  $month++;
	  $i++;
	} ?>
</select></td>
<td valign="middle"><input type="submit" value="<?php echo $lang["day"]["show"]; ?>"></td>
<td width="30" align="center"><?php
	$date_tb = date("Y-m-d",mktime(0, 0, 0, $tmp_date_ymd[1], 1, $tmp_date_ymd[0]));
	$date_te = date("Y-m-d",mktime(0, 0, 0, $tmp_date_ymd[1], date("t",mktime(0, 0, 0, $tmp_date_ymd[1], 1, $tmp_date_ymd[0])), $tmp_date_ymd[0]));
	$tmp_array = explode("-",$_GET["mb"]);
	$date_nb = date("Y-m-d",mktime(0, 0, 0, ($tmp_array[1]+1), 1, $tmp_array[0]));
	$date_ne = date("Y-m-d",mktime(0, 0, 0, ($tmp_array[1]+1), date("t",mktime(0, 0, 0, ($tmp_array[1]+1), 1, $tmp_array[0])), $tmp_array[0]));
	$n_array = explode("-",$date_nb);
	if(substr($_GET["me"],0,-3) != substr($tmp_end,0,-3)) {
	  echo "<a href=\"".$_SERVER["PHP_SELF"]."?show=day&amp;mb=".$date_nb."&amp;me=".$date_ne.($login_require?"&amp;id=".$id:"")."\" class=\"link\"\n";
	  echo " onmouseover=\"window.status='".$lang["month"]["m".$n_array[1]]." ".$n_array[0]."'; return true;\"\n";
	  echo " onmouseout=\"window.status=''; return true;\">".$navi_next."</a>";
	} else echo "&nbsp;"; ?></td>
<td width="30" align="center"><?php 
	if(substr($_GET["me"],0,-3) != substr($tmp_end,0,-3)) {
	  echo "<a href=\"".$_SERVER["PHP_SELF"]."?show=day&amp;mb=".$date_tb."&amp;me=".$date_te.($login_require?"&amp;id=".$id:"")."\" class=\"link\"\n";
	  echo " onmouseover=\"window.status='".$lang["month"]["m".$tmp_date_ymd[1]]." ".$tmp_date_ymd[0]."'; return true;\"\n";
	  echo " onmouseout=\"window.status=''; return true;\">".$navi_end."</a>";
	} else echo "&nbsp;"; 
      }
// --- hits by month navigation ---
      if($show == "month") { 
	$date_tb = date("Y",mktime(0, 0, 0, $tmp_date[1], 1, $tmp_date[0]));
	$date_pb = date("Y",mktime(0, 0, 0, $tmp_date[1], 1, $_GET["year"]-1));
	if($_GET["year"] != $date_tb) {
	  echo "<a href=\"".$_SERVER["PHP_SELF"]."?show=month&amp;year=".$date_tb.($login_require?"&amp;id=".$id:"")."\" class=\"link\"\n";
	  echo " onmouseover=\"window.status='".$date_tb."'; return true;\"\n";
	  echo " onmouseout=\"window.status=''; return true;\">".$navi_start."</a>";
	} else echo "&nbsp;"; ?></td>
<td width="30" align="center"><?php 
	if($_GET["year"] != $date_tb) {
	  echo "<a href=\"".$_SERVER["PHP_SELF"]."?show=month&amp;year=".$date_pb.($login_require?"&amp;id=".$id:"")."\" class=\"link\"\n";
	  echo " onmouseover=\"window.status='".$date_pb."'; return true;\"\n";
	  echo " onmouseout=\"window.status=''; return true;\">".$navi_prev."</a>";
	} else echo "&nbsp;";
	?></td>
<td valign="middle"><select name="yearly" onchange="javascript: document.data.submit()">
<?php
	while($tmp_work < $tmp_end) {
	  $tmp_work = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
	  $tmp_array = explode("-",$tmp_work);
	  if($tmp_work >= $tmp_start) {
	    echo "<option value=\"".$tmp_array[0]."\"".($_GET["year"]==$tmp_array[0]?"selected=\"selected\"":"").">".$tmp_array[0]."</option>\n";
	  }
	  $year++;
	} ?>
</select></td>
<td valign="middle"><input type="submit" value="<?php echo $lang["day"]["show"]; ?>"></td>
<td width="30" align="center"><?php 
	$date_te = date("Y",mktime(0, 0, 0, $tmp_date[1], 1, $tmp_date_ymd[0]));
	$date_ne = date("Y",mktime(0, 0, 0, $tmp_date[1], 1, $_GET["year"]+1));
	if($_GET["year"] != $date_te) {
	  echo "<a href=\"".$_SERVER["PHP_SELF"]."?show=month&amp;year=".$date_ne.($login_require?"&amp;id=".$id:"")."\" class=\"link\"\n";
	  echo " onmouseover=\"window.status='".$date_ne."'; return true;\"\n";
	  echo " onmouseout=\"window.status=''; return true;\">".$navi_next."</a>";
	} else echo "&nbsp;"; ?></td>
<td width="30" align="center"><?php 
	if($_GET["year"] != $date_te) {
	  echo "<a href=\"".$_SERVER["PHP_SELF"]."?show=month&amp;year=".$date_te.($login_require?"&amp;id=".$id:"")."\" class=\"link\"\n";
	  echo " onmouseover=\"window.status='".$date_te."'; return true;\"\n";
	  echo " onmouseout=\"window.status=''; return true;\">".$navi_end."</a>";
	} else echo "&nbsp;"; 
      } ?>
</td></tr></table></form><?php
    } ?></td>
<td width="25%" align="right"><table align="center" border="0" cellpadding="1" cellspacing="1" class="tback" width="100%" summary="Hits navigation">
<tr bgcolor="<?php echo $color["DEF"][2]; ?>">
<td align="left"><?php echo $lang["default"]["total"]; ?></td>
<td align="right"><?php echo $hits_total; ?></td>
</tr><tr bgcolor="<?php echo $color["DEF"][2]; ?>">
<td align="left"><?php echo $lang["day"]["skale"]; ?></td>
<td align="right">1:<?php echo ($default_skale/$skale); ?></td>
</tr>
</table></td>
</tr></table>
<?php 
  } else { 
    echo $lang["error"]["logfile"];
  } ?></td>
</tr></table>
<?php
  back_button();

// --- show User / Pass overview --------------------------------------------------------------------
} elseif($show == "userpass" && $admin) {
  show_header($lang[$show]["title_detail"], true, 1, "end", $show); ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="Userpass overview"><tr><td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<?php
  if($known_count > 0) { ?>
<table align="center" border="0" cellpadding="1" cellspacing="1" class="tback" width="100%" summary="Userpass overview"><tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>" width="15">&nbsp;</td>
<td class="head" width="285"><?php echo $lang[$show]["host"]; ?></td>
<td class="head" width="100"><?php echo $lang[$show]["user"]; ?></td>
<td class="head" width="100"><?php echo $lang[$show]["pass"]; ?></td>
</tr><?php
    for($i=0;$i<$known_count;$i++) { 
      ?><tr bgcolor="<?php echo $color["DEF"][($i&1?2:1)]; ?>">
<td class="head" align="right"><?php echo ($i+1); ?></td>
<td><?php echo "<a href=".$stat_details[$i]["url"]." target=\"_blank\">".$stat_details[$i]["host"]."</a></td>\n";
echo "<td>".$stat_details[$i]["user"]."</td>\n<td>".$stat_details[$i]["pass"]."</td>\n</tr>";
    } ?><tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>">&nbsp;</td>
<td class="head" colspan="3" align="left"><?php echo $lang[$show]["count"]; ?>: <?php echo ($known_count); ?></td>
</tr></table>
<?php
} else echo $lang[$show]["nothing"]; ?></td>
</tr>
</table>
<?php
  back_button();

// --- show os / referer / language  -----------------------------------------------------------
} elseif($show == "os" || $show == "languages") {
  show_header($lang[$show]["title_family"], true, 1, 2, $show);
  show_detailed_stats($show);
  echo "<br>";
  $stat_details = $stat_family;
  show_header($lang[$show]["title_detail"], true, 2, "end", $show);
  show_detailed_stats($show,1);
  back_button();

// --- show browser / screen / colordepth statistics ----------------------------------------
} elseif($show == "browser" || $show == "screen" || $show == "referer") {
  show_header($lang[$show]["title_family"], true, 1, 2, $show);
  show_detailed_stats($show);
  echo "<br>";
  $stat_details = $stat_family;
  show_header($lang[$show]["title_detail"], true, 2, 3, $show);
  show_detailed_stats($show, ($show == "referer"?0:1));
  echo "<br>";
  if($show == "referer") $do_not_show = true;
  $stat_details = $stat_extra;
  show_header($lang[$show]["title_extra"], true, 3, "end", $show);
  show_detailed_stats($show, false, true);
  back_button();

// --- show ip2country  / forwarder / provider / countrystatistics -----------------------------------------------------
} elseif($show == "ip2c" || $show == "forward" || $show == "provider" || $show == "domain") {
  show_header($lang[$show]["title_detail"], true, 1, "end", $show);
  show_detailed_stats($show);
  back_button(); 

// --- show logfile-menu ------------------------------------------------------------------------------
} elseif($show == "logfile" && $admin) { 
  show_header($lang[$show]["title_detail"], true, 1, "end"); ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="Admin menu">
<tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center" width="140">
<?php if($log_entries > 0) {
    $lm = $lang["menu"]["since"]." ".convert_date($first_log,$lang_format);
  } else $lm=$lang["menu"]["no_entries"]; ?>
<b><u><?php echo $lang["menu"]["entries"]; ?>:</u></b><br /><span class="count"><?php echo $log_entries; ?></span><br><?php echo $lm; ?></td>
<td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center" width="270">
<table border="0" cellspacing="1" cellpadding="1" align="center" summary="Admin menu">
<?php show_menu_point("view", $_SERVER["PHP_SELF"]."?show=details&amp;a=0&amp;b=".$detail_length, $lang["logfile"]["view"], $lang["logfile"]["view"], 1);
show_menu_point("maintain", $_SERVER["PHP_SELF"]."?show=maintain", $lang["logfile"]["repair"], $lang["logfile"]["repair"], 2);
show_menu_point("download", $modules_dir."download.php?d=1", $lang["logfile"]["download"], $lang["logfile"]["download"], 3);
show_menu_point("dellast", $_SERVER["PHP_SELF"]."?show=dellast", $lang["logfile"]["dellast"], $lang["logfile"]["dellast"], 2, $lang["logfile"]["dellast_confirm"]);
show_menu_point("dellog", $_SERVER["PHP_SELF"]."?show=dellog", $lang["logfile"]["delete"], $lang["logfile"]["delete"], 1, $lang["logfile"]["delete_confirm"]); ?>
</table></td>
<td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center" width="140"><?php
  printf ("<b><u>%s:</u></b><br>%3.2f KB\n",$lang["logfile"]["size"],filesize($log_file)/1000);?><br>&nbsp;<br>
<b><u><?php echo $lang["logfile"]["last_dl"]; ?>:</u></b><br><?php
  if(file_exists($dl_file)) {
    $lines = file($dl_file);
    echo convert_date($lines[0],$lang_format);
  } else echo $lang["default"]["unknown"];
?></td>
</tr><tr>
<td colspan="3" bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<table border="0" cellspacing="1" cellpadding="2" class="tback" summary="Admin menu last log">
<tr bgcolor="<?php echo $color["DEF"][2]; ?>">
<td rowspan="2" valign="top"><b><u><?php echo $lang["logfile"]["last_log"]; ?>:</u></b></td>
<td><?php echo ($log_entries > 0 && isset($last_log_date)?(trim($last_log_date)?convert_date($last_log_date,$lang_format):$lang["error"]["error"]):$lang["error"]["no_entries"]); ?></td>
<?php $tmp = explode("|",$last_log_entry);
  if(isset($tmp[3])) {
    $tmp_os = ident_os($tmp[3]);
    $tmp_browser = ident_browser($tmp[3]); 
  } // --- check if lasr entry is from yourself ---
  $self = (isset($tmp[1]) && getenv('REMOTE_ADDR') == $tmp[1]?true:false);
  $self = ($self && isset($tmp_os) && ($tmp_os == ident_os(getenv('HTTP_USER_AGENT'))) == $tmp[1]?true:false);
  $self = ($self && isset($tmp_browser) && ($tmp_browser == ident_browser(getenv('HTTP_USER_AGENT'))) == $tmp[1]?true:false); ?>
<td><?php echo (isset($tmp[1])?$tmp[1]:$lang["error"]["error"]); ?></td>
<td><?php
  if(isset($tmp_os)){
    echo "<img src=\"".$family["os"]["icon"][$tmp_os[0]]."\" width=\"14\" height=\"14\" alt=\"".$tmp_os[1]."\" title=\"".$tmp_os[1]."\"></td>\n";
    echo "<td bgcolor=\"".$color["DEF"][2]."\">".$tmp_os[1];
  } else echo $lang["error"]["error"];
?></td>
<td rowspan="2"><?php echo ($self?"OK":"<b> ! </b>"); ?></td>
</tr><tr bgcolor="<?php echo $color["DEF"][2]; ?>">
<td><?php echo $last_log_time; ?></td>
<td><?php echo (isset($tmp[2])?$tmp[2]:$lang["error"]["error"]); ?></td>
<td><?php 
  if(isset($tmp_browser)){
    echo "<img src=\"".$family["browser"]["icon"][$tmp_browser[0]]."\" width=\"14\" height=\"14\" alt=\"".$family["browser"]["name"][$tmp_browser[0]]."\" title=\"".$family["browser"]["name"][$tmp_browser[0]]."\"></td>\n";
    echo "<td>".$tmp_browser[1];
  } else echo $lang["error"]["error"];
?></td>
</tr>
</table></td>
</tr>
</table><?php
  back_button();

// --- show query overview --------------------------------------------------------------------------
} elseif($show == "search") { 
  show_header($lang["default"][$show], true, 1, 2, $show); ?>
<table border="0" cellspacing="2" cellpadding="3" align="center" width="100%" summary="query overview"><tr><td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<?php
  if($known_count > 0) {
    show_detailed_stats($show,false,true); ?>
</td></tr></table>
<br><?php
    show_header($lang[$show]["title"], true, 2, 3, $show); ?>
<table border="0" cellspacing="2" cellpadding="3" align="center" width="100%" summary="query overview"><tr><td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<table align="center" border="0" cellpadding="2" cellspacing="1" class="tback" summary="query overview"><tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>">&nbsp;</td>
<td class="head"><?php echo $lang["details"]["host"]; ?></td>
<?php if(!isset($_GET["print"])) { ?><td class="head" width="16">&nbsp;</td><?php } ?>
<td class="head"><?php echo $lang[$show]["query"]; ?></td>
</tr>
<?php
    $i = 1;
    foreach($stat_extra as $key => $value) {
      $tmp_search = ident_searchengine($value);
      echo "<tr bgcolor=\"".$color["DEF"][($i&1?1:2)]."\">\n<td class=\"head\" align=\"right\">".$i."</td>\n";
      echo "<td class=\"default\"><a href=\"http://".$value."\" class=\"default\" target=\"_blank\">".$value."</a></td>\n";
      if(!isset($_GET["print"])) {
        echo "<td class=\"default\" align=\"center\">".(isset($ident_search[$tmp_search])?"<img src=\"".$ident_search[$tmp_search]."\" width=\"14\" height=\"14\" alt=\"".$value."\" title=\"".$value."\">":"&nbsp;")."</td>\n";
      }
      echo "<td class=\"default\"><a href=\"http://".$value.$search_path[$key]."?".$key."\" class=\"default\" target=\"_blank\">".$key."</a></td>\n</tr>";
      $i++;
    } ?><tr>
<td bgcolor="<?php echo $color["DEF"][1]; ?>">&nbsp;</td>
<td class="head"<?php if(!isset($_GET["print"])) echo "colspan=\"2\""; ?>><?php echo $lang["default"]["total"]; ?></td>
<td class="head" align="right"><?php echo ($i-1); ?></td>
</tr></table><?php
  } else echo $lang["error"]["no_entries"]; ?>
</td></tr></table><?php
  echo "<br>";
  show_header($lang["default"]["bots"], true, 3, "end", $show);
  if($bot_count > 0) {
    $known_count = $bot_count;
    $stat_details = $stat_family;
    show_detailed_stats($show,true,true);
  } else {
    echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"3\" width=\"100%\"><tr bgcolor=\"".$color["DEF"][1]."\">\n";
    echo "<td align=\"center\">".$lang["error"]["no_entries"]."</td>\n</tr></table>";
  }
  back_button();

// --- show nothing but a button --------------------------------------------------------------------
} elseif($show == "nothing") { ?>
</table>
<?php show_hr(); ?>
<input type="button" value="<?php echo $lang["default"]["close"]; ?>"  onclick="self.close()">
<?php

// --- wrong show value ------------------------------------------------------------------------------
} else {
  if($show != "error") {
    $error_string[$show] = $lang["error"]["wrong_show"];  
    $show = "error";
  }
}

// --- show error messages --------------------------------------------------------------------------
if($show == "error") {
  show_header($lang[$show]["title_detail"]); ?>
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center" summary="Errormessages"><tr><td bgcolor="<?php echo $color["DEF"][1]; ?>" align="center">
<p class="header"><?php echo $lang[$show]["error"]; ?></p>
<table align="center" border="0" cellpadding="1" cellspacing="1" class="tback" summary="Errormessages"><?php
  $i = 1;
  foreach($error_string as $value => $key) { ?>
<tr>
<td class="head" align="right" width="25"><?php echo $i; ?>.</td>
<td bgcolor="<?php echo $color["DEF"][($i&1?2:1)]; ?>" align="left" width="80%"><?php echo $key.": ".$value; ?></td>
</tr>
<?php
    $i++;
  } ?>
</table><br>
</td></tr></table><?php
  if($leave["code"] != "" && $leave["text"] != "") back_button($leave["code"], $leave["text"], $leave["logout"]);
  else show_hr();
}

// --- benchmarking -----------------------------------------------------------------------------------
if(isset($benchmark) && $benchmark && !isset($_GET["print"])) {  
  $bench_c = microtime();
  $bench_a_a = explode(" ",$bench_a);
  $bench_a_b = explode(" ",$bench_b);
  $bench_a_c = explode(" ",$bench_c); ?>
<br><table border="0" cellspacing="1" cellpadding="2" class="tback" summary="Benchmarks">
<tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td colspan="2"><?php echo $lang["benchmark"]["title"].": ".(isset($i2c_cnt)&&$i2c_cnt>0?"&nbsp; ".$i2c_cnt:""); ?></td>
<td align="right"><?php echo $lang["benchmark"]["reading"]; ?>:</td>
<td align="right"><?php printf("%4.2f ms",(($bench_a_b[1] + $bench_a_b[0]) - ($bench_a_a[1] + $bench_a_a[0]))*1000); ?></td>
</tr><tr bgcolor="<?php echo $color["DEF"][1]; ?>">
<td align="right"><?php echo $lang["benchmark"]["execution"]; ?>:</td>
<td align="right"><?php printf("%2.3f s",($bench_a_c[1] + $bench_a_c[0]) - ($bench_a_a[1] + $bench_a_a[0])); ?></td>
<td align="right"><?php echo $lang["benchmark"]["output"]; ?>:</td>
<td align="right"><?php printf("%4.2f ms",(($bench_a_c[1] + $bench_a_c[0]) - ($bench_a_b[1] + $bench_a_b[0]))*1000); ?></td>
</tr>
</table><?php 
} 

// --- final outputs -------------------------------------------------------------------------------------- ?>
<a name="statistics_end"></a>
<?php if($show != "error") { ?>
<script language="JAVASCRIPT" type="text/javascript">window.defaultStatus = "<?php echo $lang["menu"]["visitors"].":".$counter_value."   ".$lang["menu"]["entries"].":".$log_entries."   ".$lang["logfile"]["last_log"].":".($log_entries > 0?(trim($last_log_date != "")?convert_date($last_log_date,$lang_format):$lang["error"]["error"]):$lang["error"]["no_entries"]); ?>";</script><?php 
}
// --- last part of document ---
show_document("bottom"); ?>