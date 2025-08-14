<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + ident_bot.inc.php - dentify browser from weblog-logfile for showlog
// +
// + Creation:		06.06.2004 - Daniel Sokoll
// + Last Update:	30.09.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$ident_bot_version = "2.0.1";
$ident_bot["Picsearch Bot"] = $pic["dir"]."i_bot_picsearch.png";
$ident_bot["GoForIt Bot"] = $pic["dir"]."i_bot_goforit.png";
$ident_bot["Links SQL"] = $pic["dir"]."i_bot_linksql.png";
$ident_bot["Alexa Bot"] = $pic["dir"]."i_bot_alexa.png";
$ident_bot["Lycos Bot"] = $pic["dir"]."i_bot_lycos.png";
$ident_bot["DMOZ Crawler"] = $pic["dir"]."i_bot_dmoz.png";
$ident_bot["Looksmart Bot"] = $pic["dir"]."i_bot_looksmart.png";
$ident_bot["Euroseek Bot"] = $pic["dir"]."i_bot_euroseek.png";
$ident_bot["Aonde Spider"] = $pic["dir"]."i_bot_aonde.png";
$ident_bot["Cometsearch Bot"] = $pic["dir"]."i_bot_comet.png";
$ident_bot["Ask Jeeves Bot"] = $pic["dir"]."i_bot_askjeeves.png";
$ident_bot["Yahoo Bot"] = $pic["dir"]."i_bot_yahoo.png";
$ident_bot["Entireweb Bot"] = $pic["dir"]."i_bot_entireweb.png";
$ident_bot["Exite Bot"] = $pic["dir"]."i_bot_excite.png";
$ident_bot["Overture Bot"] = $pic["dir"]."i_bot_overture.png";
$ident_bot["Acoon Spider"] = $pic["dir"]."i_bot_acoon.png";
$ident_bot["MSN Bot"] = $pic["dir"]."i_bot_msn.png";
$ident_bot["Voila Bot"] = $pic["dir"]."i_bot_voila.png";
$ident_bot["Seekbot"] = $pic["dir"]."i_bot_seekport.png";
$ident_bot["Xenu Link Sleuth"] = $pic["dir"]."i_bot_xenu.png";
$ident_bot["Updated Crawler"] = $pic["dir"]."i_bot_updated.png";
$ident_bot["Grub Robot"] = $pic["dir"]."i_bot_grub.png";
$ident_bot["LOOP Bot"] = $pic["dir"]."i_bot_loop.png";
$ident_bot["HooWWWer"] = $pic["dir"]."i_bot_hoowwwer.png";
$ident_bot["Turnitin Bot"] = $pic["dir"]."i_bot_turnitin.png";
$ident_bot["Amfibi Bot"] = $pic["dir"]."i_bot_amfibi.png";
$ident_bot["Walhello Bot"] = $pic["dir"]."i_bot_walhello.png";
$ident_bot["WiseNut Zyborg"] = $pic["dir"]."i_bot_zyborg.png";
$ident_bot["LWP::Simple"] = $pic["dir"]."i_bot_internet.png";
$ident_bot["Altavista Bot"] = $pic["dir"]."i_bot_altavista.png";
$ident_bot["About Libby"] = $pic["dir"]."i_bot_about.png";
$ident_bot["Dr.Watson"] = $pic["dir"]."i_bot_addy.png";
$ident_bot["Googlebot"] = $pic["dir"]."i_bot_google.png";
$ident_bot["Infoseek Bot"] = $pic["dir"]."i_bot_infoseek.png";
$ident_bot["Inktomi Bot"] = $pic["dir"]."i_bot_inktomi.png";
$ident_bot["WWWeasel"] = $pic["dir"]."i_bot_wwweasel.png";
$ident_bot["Wget"] = $pic["dir"]."i_bot_wget.png";
$ident_bot["Pompos"] = $pic["dir"]."i_bot_dir.png";
$ident_bot["Asterias"] = $pic["dir"]."i_bot_singingfish.png";
$ident_bot["Nutch"] = $pic["dir"]."i_bot_nutch.png";
$ident_bot["Regiochannel Bot"] = $pic["dir"]."i_bot_regiochannel.png";
$ident_bot["Gigabot"] = $pic["dir"]."i_bot_gigabot.png";
$ident_bot["W3C Engine"] = $pic["dir"]."i_bot_w3c.png";
$ident_bot["Mozdex"] = $pic["dir"]."i_bot_mozdex.png";
$ident_bot["Envolk"] = $pic["dir"]."i_bot_envolk.png";

// --- generate botlist ---
if(!isset($knownbot)) {
  if (file_exists($bot_file)) {
    $bot_fp = fopen($bot_file, 'r');
    for ($bot_no=0; ! feof($bot_fp); $bot_no++) {
      $knownbot[$bot_no]=strtolower(trim(fgets($bot_fp, 256)));
    }
    fclose($bot_fp);
  } else {
    $show = "error";
    $error_string[$bot_file] = $lang["error"]["not_found"];  
  }
  $botlist_date = array_pop($knownbot);
}

function ident_bot($work) { // --- check if botfile says it's a robot ---
  global $knownbot;

  $work = " ".strtolower($work);
  foreach($knownbot as $bot) {
    if(strpos($work, $bot)) return(true);
  }
  return(false);
}

function ident_robot($work) { // --- ident robot with name and icon ---
  $work = " ".strtolower($work);
  $bot_version = "";
  if(strpos($work, "google")) {
    if(preg_match("/googl(e|ebot)(-Image)?\/([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[3];
    return array(0 => "google", 1 => "Googlebot".$bot_version);
  }
  if(strpos($work, "psbot")) return array(0 => "picsearch", 1 => "Picsearch Bot");
  if(strpos($work, "goforit.com")) return array(0 => "goforit", 1 => "GoForIt Bot");
  if(strpos($work, "links sql")) return array(0 => "linksql", 1 => "Links SQL");
  if(strpos($work, "ia_archive")) return array(0 => "alexa", 1 => "Alexa Bot");
  if(strpos($work, "lycos_spider_")) return array(0 => "lycos", 1 => "Lycos Bot");
  if(strpos($work, "dmoz")) return array(0 => "dmoz", 1 => "DMOZ Crawler");
  if(strpos($work, "looksmart-sv-fw")) return array(0 => "looksmart", 1 => "Looksmart Bot");
  if(strpos($work, "arachnoidea")) return array(0 => "euroseek", 1 => "Euroseek Bot");
  if(strpos($work, "aonde-spider")) return array(0 => "aonde", 1 => "Aonde Spider");
  if(strpos($work, "cometsearch@cometsystems")) return array(0 => "comet", 1 => "Cometsearch Bot");
  if(strpos($work, "regiochannel")) return array(0 => "regiochannel", 1 => "Regiochannel Bot");
  if(strpos($work, "infoseek")) return array(0 => "infoseek", 1 => "Infoseek Bot");
  if(strpos($work, "slurp@inktomi.com")) return array(0 => "inktomi", 1 => "Inktomi Bot");
  if(strpos($work, "envolk[its]spider")) {
    if(preg_match("/envolk\[its\]spider[ \/]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "envolk", 1 => "Envolk".$bot_version);
  }
  if(strpos($work, "mozdex")) {
    if(preg_match("/mozdex[ \/]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "mozdex", 1 => "Modzex".$bot_version);
  }
  if(strpos($work, "asterias")) {
    if(preg_match("/asterias[ \/]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "singingfish", 1 => "Asterias".$bot_version);
  }
  if(strpos($work, "voilabot")) {
    if(preg_match("/voilabot[ \/]?[a-z ]*([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "voila", 1 => "Voilabot".$bot_version);
  }
  if(strpos($work, "hoowwwer")) {
    if(preg_match("/hoowwwer[ \/]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "hoowwwer", 1 => "HooWWWer".$bot_version);
  }
  if(strpos($work, "netresearchserver")) {
    if(preg_match("/netresearchserver[ \/]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "loop", 1 => "LOOPbot".$bot_version);
  }
  if(strpos($work, "wwweasel")) {
    if(preg_match("/wwweasel( robot)?[\/ ]v?([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[2];
    return array(0 => "wwweasel", 1 => "WWWeasel".$bot_version);
  }
  if(strpos($work, "pompos")) {
    if(preg_match("/pompos[ \/]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "dir", 1 => "Pompos".$bot_version);
  }
  if(strpos($work, "sidewinder")) {
    if(preg_match("/sidewinder[ \/]?([0-9a-z.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "infoseek", 1 => "Infoseek Bot".$bot_version);
  }
  if(strpos($work, "marvin")) {
    if(preg_match("/marvin[ \/]?([0-9a-z.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "infoseek", 1 => "Infoseek Marvin".$bot_version);
  }
  if(strpos($work, "amfibibot")) {
    if(preg_match("/amfibibot[\/ ]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "amfibi", 1 => "Amfibi Bot".$bot_version);
  }
  if(strpos($work, "turnitinbot")) {
    if(preg_match("/turnitinbot[ \/]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "turnitin", 1 => "Turnitin Bot".$bot_version);
  }
  if(strpos($work, "wget")) {
    if(preg_match("/wget[ \/]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "wget", 1 => "Wget".$bot_version);
  }
  if(strpos($work, "seekbot")) {
    if(preg_match("/seekbot[ \/]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "seekport", 1 => "Seekbot".$bot_version);
  }
  if(strpos($work, "libby")) {
    if(preg_match("/libby[_\/ ]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "about", 1 => "About Libby".$bot_version);
  }
  if(strpos($work, "appie")) {
    if(preg_match("/appie[ \/]([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "walhello", 1 => "Walhello Appie".$bot_version);
  }
  if(strpos($work, "nutch")) {
    if(preg_match("/nutch(org|cvs)?\/?([0-9.]+)/", $work, $version)) $bot_version = " ".$version[2];
    return array(0 => "nutch", 1 => "Dr.Nutch".$bot_version);
  }
  if(strpos($work, "gigabot")) {
    if(preg_match("/gigabot\/([0-9.]+)/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "gigabot", 1 => "Gigabot".$bot_version);
  }
  if(strpos($work, "irlbot")) {
    if(preg_match("/irlbot\/([0-9.]+) \(\+http:\/\/irl\.cs\.tamu\.edu\/crawler\)/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "irl", 1 => "IRLbot".$bot_version);
  }
  if(strpos($work, "w3c") && strpos($work, "check")) {
    return array(0 => "w3c", 1 => "W3C Linkchecker");
  }
  if(strpos($work, "w3c") && strpos($work, "validator")) {
    return array(0 => "w3c", 1 => "W3C Validator");
  }
  if(strpos($work, "watson") && strpos($work, "addy")) {
    if(preg_match("/watson[ \/]([0-9.]{1,10})(.*?)watson\.addy\.com/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "addy", 1 => "Dr.Watson".$bot_version);
  }
  if(strpos($work, "overture") && strpos($work, "webcrawler")) return array(0 => "overture", 1 => "Overture Bot");
  if(strpos($work, "ask") && strpos($work, "jeeves")) return array(0 => "askjeeves", 1 => "Ask Jeeves Bot");
  if(strpos($work, "speedy") && strpos($work, "spider")) return array(0 => "entireweb", 1 => "Entireweb Bot");
  if(strpos($work, "architext") && strpos($work, "spider")) return array(0 => "excite", 1 => "Exite Bot");
  if(strpos($work, "acoon") && strpos($work, "robot")) return array(0 => "acoon", 1 => "Acoon Spider");
  if(strpos($work, "grub") && strpos($work, "client")) {
    if(preg_match("/grub[ \\-]?client[ \/\-]{1,5}([0-9.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "grub", 1 => "Grub Robot ".$bot_version);
  }
  if(strpos($work, "xenu") && strpos($work, "link sleuth")) {
    if(preg_match("/xenu(&#039;s)? link sleuth[\/ ]([0-9a-z.]{1,10})/", $work, $version)) $bot_version = " ".$version[1];
    return array(0 => "xenu", 1 => "Xenu Link Sleuth".$bot_version);
  }
  // --- and finaly more complex bots ---
  if(preg_match("/yahoo(! ([a-z]{1,3} )?slurp;|-|feedseeker)/", $work)) return array(0 => "yahoo", 1 => "Yahoo Bot");
  if(preg_match("/msn(bot|ptc)[ \/]([0-9.]{1,10})/", $work, $version)) return array(0 => "msn", 1 => "MSNbot ".$version[2]);
  if(preg_match("/updated[\/]([0-9a-z.]{1,10})/", $work, $version)) return array(0 => "updated", 1 => "Updated Crawler ".$version[1]);
  if(preg_match("/(wise|zy)bo(rg|t)[ \/]([0-9.]{1,10})/", $work, $version)) return array(0 => "zyborg", 1 => "WiseNut ".$version[3]);
  if(preg_match("/lwp(-trivial|::simple)[ \/]([0-9.]{1,10})/", $work, $version)) return array(0 => "internet", 1 => "LWP::Simple ".$version[2]);
  if(preg_match("/scooter[ \/\-]*[a-z]*([0-9.]{1,10})/", $work, $version)) return array(0 => "altavista", 1 => "Altavista Bot ".$version[1]);

  return false;
} ?>