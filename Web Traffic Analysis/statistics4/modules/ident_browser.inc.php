<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + ident_browser.inc.php - dentify browser from weblog-logfile for showlog
// +
// + Creation:		14.12.2004 - Daniel Sokoll
// + Last Update:	25.09.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$ident_browser_version = "2.2.6";
// --- browser family config ---
$family["browser"]["icon"]["BOT"] = $pic["dir"]."i_bot.png";			// browser Bots / Crawler
$family["browser"]["name"]["BOT"] = "BOT";
$family["browser"]["icon"]["IE"] = $pic["dir"]."i_browser_ie.png";		// browser Internet Explorer
$family["browser"]["name"]["IE"] = "Internet Explorer";
$family["browser"]["icon"]["FF"] = $pic["dir"]."i_browser_ff.png";		// browser FireFox
$family["browser"]["name"]["FF"] = "FireFox";
$family["browser"]["icon"]["MZ"] = $pic["dir"]."i_browser_mz.png";		// browser Mozilla
$family["browser"]["name"]["MZ"] = "Mozilla";
$family["browser"]["icon"]["NS"] = $pic["dir"]."i_browser_ns.png";		// browser Netscape Communicator
$family["browser"]["name"]["NS"] = "Netscape Communicator";
$family["browser"]["icon"]["N6"] = $pic["dir"]."i_browser_n6.png";		// browser Netscape 6+
$family["browser"]["name"]["N6"] = "Netscape";
$family["browser"]["icon"]["OP"] = $pic["dir"]."i_browser_op.png";		// browser Opera
$family["browser"]["name"]["OP"] = "Opera";
$family["browser"]["icon"]["KQ"] = $pic["dir"]."i_browser_kq.png";		// browser Konqueror
$family["browser"]["name"]["KQ"] = "Konqueror";
$family["browser"]["icon"]["GA"] = $pic["dir"]."i_browser_ga.png";		// browser Galleon
$family["browser"]["name"]["GA"] = "Galleon";
$family["browser"]["icon"]["WT"] = $pic["dir"]."i_browser_wt.png";		// browser Web TV
$family["browser"]["name"]["WT"] = "Web TV";
$family["browser"]["icon"]["AM"] = $pic["dir"]."i_browser_am.png";		// browser Amaya
$family["browser"]["name"]["AM"] = "Amaya";
$family["browser"]["icon"]["IC"] = $pic["dir"]."i_browser_ic.png";		// browser iCab
$family["browser"]["name"]["IC"] = "ICab";
$family["browser"]["icon"]["CU"] = $pic["dir"]."i_browser_cu.png";		// browser cURL
$family["browser"]["name"]["CU"] = "CURL";
$family["browser"]["icon"]["LY"] = $pic["dir"]."i_browser_ly.png";		// browser Lynx
$family["browser"]["name"]["LY"] = "Lynx";
$family["browser"]["icon"]["SF"] = $pic["dir"]."i_browser_sf.png";		// browser Safari
$family["browser"]["name"]["SF"] = "Safari";
$family["browser"]["icon"]["CH"] = $pic["dir"]."i_browser_ch.png";		// browser Chimera
$family["browser"]["name"]["CH"] = "Chimera";
$family["browser"]["icon"]["CA"] = $pic["dir"]."i_browser_ch.png";		// browser Camino
$family["browser"]["name"]["CA"] = "Camino";
$family["browser"]["icon"]["KM"] = $pic["dir"]."i_browser_km.png";		// browser K-Meleon
$family["browser"]["name"]["KM"] = "K-Meleon";
$family["browser"]["icon"]["MC"] = $pic["dir"]."i_browser_mc.png";		// browser Mosaic
$family["browser"]["name"]["MC"] = "Mosaic";
$family["browser"]["icon"]["LI"] = $pic["dir"]."i_browser_li.png";		// browser Links
$family["browser"]["name"]["LI"] = "Links";
$family["browser"]["icon"]["OW"] = $pic["dir"]."i_browser_ow.png";	// browser OmniWeb
$family["browser"]["name"]["OW"] = "OmniWeb";
$family["browser"]["icon"]["IB"] = $pic["dir"]."i_browser_ib.png";		// browser IBrowse
$family["browser"]["name"]["IB"] = "IBrowse";
$family["browser"]["icon"]["AW"] = $pic["dir"]."i_browser_aw.png";	// browser AWeb
$family["browser"]["name"]["AW"] = "AWeb";
$family["browser"]["icon"]["AV"] = $pic["dir"]."i_browser_av.png";		// browser Voyager
$family["browser"]["name"]["AV"] = "Voyager";
$family["browser"]["icon"]["NP"] = $pic["dir"]."i_browser_np.png";		// browser NetPositive
$family["browser"]["name"]["NP"] = "NetPositive";
$family["browser"]["icon"]["EL"] = $pic["dir"]."i_browser_el.png";		// browser ELinks
$family["browser"]["name"]["EL"] = "ELinks";
$family["browser"]["icon"]["GO"] = $pic["dir"]."i_browser_go.png";		// browser AvantGo
$family["browser"]["name"]["GO"] = "AvantGo";
$family["browser"]["icon"]["EP"] = $pic["dir"]."i_browser_ep.png";		// browser Epiphany
$family["browser"]["name"]["EP"] = "Epiphany";
$family["browser"]["icon"]["W3"] = $pic["dir"]."i_browser_w3.png";	// browser W3M
$family["browser"]["name"]["W3"] = "W3M";
$family["browser"]["icon"]["DI"] = $pic["dir"]."i_browser_di.png";		// browser Dillo
$family["browser"]["name"]["DI"] = "Dillo";
$family["browser"]["icon"]["AB"] = $pic["dir"]."i_browser_ab.png";		// browser ABrowse
$family["browser"]["name"]["AB"] = "ABrowse";
$family["browser"]["icon"]["ANT"] = $pic["dir"]."i_browser_ant.png";	// browser ANTFresco
$family["browser"]["name"]["ANT"] = "ANTFresco";
$family["browser"]["icon"]["AOL"] = $pic["dir"]."i_search_aol.png";		// browser AOL
$family["browser"]["name"]["AOL"] = "AOL";
$family["browser"]["icon"]["PH"] = $pic["dir"]."i_browser_ph.png";		// browser Phoenix
$family["browser"]["name"]["PH"] = "Phoenix";
$family["browser"]["icon"]["UKN"] = $pic["dir"]."i_unknown.png";		// browser Unknown
$family["browser"]["name"]["UKN"] = $unknown_text;

include_once("ident_bot.inc.php");

function ident_browser($work) {
  global $unknown_text, $family;

  $work = " ".strtolower($work);
// --- test for Bots or crawler ---
  if(ident_bot($work)) return array(0 => $family["browser"]["name"]["BOT"], 1 => "");

// --- test for Opera ---
  if(strstr($work, "opera")) {
    preg_match("/(opera)([ \/])([0-9]{1,2}.[0-9]{1,3}){0,1}/",$work,$regs);
    return array(0 => "OP", 1 => $family["browser"]["name"]["OP"]." ".$regs[3]);
  }
// --- test for Internet Explorer ---
  if(strpos($work, "msie")) {
    $val = explode(" ",strstr($work,"msie"));
    $ver = explode(";", $val[1]);
    $tmp = explode(".", $ver[0]);
    if(strlen($tmp[1]) == 2) {
      if(substr($tmp[1],1,1) == 0) $tmp[1] = substr($tmp[1],0,1);
    }
    $ver[0] = implode(".",$tmp);
    return array(0 => "IE", 1 => $family["browser"]["name"]["IE"]." ".$ver[0]);
  } 
  if(preg_match("/mspie|pocket/",$work)) {
    $val = explode(" ",strstr($agent,"mspie"));
    if (strpos($work, "mspie")) $ver = $val[1];
    else {
      $val = explode("/",$work);
      $ver = $val[1];
    }
    return array(0 => "IE", 1 => $family["browser"]["name"]["IE"]." ".$ver);
  } 
  if (strpos($work, "mozilla") && strstr($work, "/3.01")) {
    return array(0 => "IE", 1 => $family["browser"]["name"]["IE"]." "."3.01");
  }
// --- test for Firebird/Firefox ---
  if(preg_match("/(fire)(fox|bird)|phoenix/",$work)) {
    $val = explode("/",strstr($work,"fire"));
    $val = explode(" ",$val[1]);
    return array(0 => "FF", 1 => $family["browser"]["name"]["FF"]." ".$val[0]);
  }
// --- test for Safari ---
  if(strpos($work, "safari") || strpos($work, "applewebkit")) {
    $val = explode(" ",strstr($work,"safari"));
    $val = explode("/",$val[0]);
    $val[1] = (isset($val[1])?$val[1]:$val[0]);
    return array(0 => "SF", 1 => $family["browser"]["name"]["SF"]." ".$val[1]);
  }
// --- test for WebTV ---
  if(strpos($work, "webtv")) {
    $val = explode("/",strstr($work,"webtv"));
    return array(0 => "WT", 1 => $family["browser"]["name"]["WT"]." ".$val[1]);
  }
// --- test for Lynx ---
  if(strstr($work, "lynx")) {
    preg_match("/(lynx)\/([0-9]{1,2}.[0-9]{1,2}.[0-9]{1,2})/",$work,$regs);
    return array(0 => "LY", 1 => $family["browser"]["name"]["LY"]." ".$regs[2]);
  }
// --- test for ELinks ---
  if(strpos($work, "elinks")) {
    preg_match("/(elinks) \(([0-9]{1,2}.[0-9]{1,2}.[0-9]{1,2})/",$work,$regs);
    return array(0 => "EL", 1 => $family["browser"]["name"]["EL"]." ".$regs[2]);
  }
// --- test for Links ---
  if(strpos($work, "links")) {
    preg_match("/(links)\/([0-9]{1,2}.[0-9]{1,2}.[0-9]{1,2})/",$work,$regs);
    return array(0 => "LI", 1 => $family["browser"]["name"]["LI"]." ".$regs[2]);
  }
// --- test for Amaya ---
  if(strstr($work, "amaya")) {
    preg_match("/(amaya)\/([0-9]{1,2}.[0-9]{1,2}.[0-9]{1,2})/",$work,$regs);
    return array(0 => "AM", 1 => $family["browser"]["name"]["AM"]." ".$regs[2]);
  }
// --- test for Galeon ---
  if(strpos($work, "galeon")) {
    $val = explode(" ",strstr($work,"galeon"));
    $val = explode("/",$val[0]);
    return array(0 => "GA", 1 => $family["browser"]["name"]["GA"]." ".$val[1]);
  }
// --- test for Konqueror ---
  if(strpos($work, "konqueror")) {
    preg_match("/(konqueror)\/([0-9]{1,2}.[0-9]{0,3}.[0-9]{0,2})/",$work,$regs);
    return array(0 => "KQ", 1 => $family["browser"]["name"]["KQ"]." ".preg_replace("/;/", "", $regs[2]));
  }
// --- test for iCab ---
  if(strstr($work, "icab")) {
    $val = explode(" ",strstr($work,"icab"));
    return array(0 => "IC", 1 => $family["browser"]["name"]["IC"]." ".$val[1]);
  }
// --- test for Chimera ---
  if(strpos($work, "chimera")) {
    $val = explode("/",strstr($work,"chimera"));
    return array(0 => "CH", 1 => $family["browser"]["name"]["CH"]." ".(isset($val[1])?$val[1]:""));
  }
// --- test for Camino ---
  if(strpos($work, "camino")) {
    $val = explode("/",strstr($work,"camino"));
    return array(0 => "CA", 1 => $family["browser"]["name"]["CA"]." ".(isset($val[1])?$val[1]:""));
  }
// --- test for K-Meleon ---
  if(strpos($work, "k-meleon")) {
    $val = explode("/",strstr($work,"k-meleon"));
    return array(0 => "KM", 1 => $family["browser"]["name"]["KM"]." ".(isset($val[1])?$val[1]:""));
  }
// --- test for Mosaic ---
  if(strpos($work, "mosaic")) {
    $val = explode("/",strstr($work,"mosaic"));
    return array(0 => "MC", 1 => $family["browser"]["name"]["MC"]." ".(isset($val[1])?$val[1]:""));
  }
// --- test for OmniWeb ---
  if(strpos($work, "omniweb")) {
    $val = explode("/",strstr($work,"omniweb"));
    return array(0 => "OW", 1 => $family["browser"]["name"]["OW"]." ".(isset($val[1])?$val[1]:""));
  }
// --- test for IBrowse ---
  if(strpos($work, "ibrowse")) {
    $val = explode("/",strstr($work,"ibrowse"));
    return array(0 => "IB", 1 => $family["browser"]["name"]["IB"]." ".(isset($val[1])?$val[1]:""));
  }
// --- test for AWeb ---
  if(strpos($work, "amiga-aweb")) {
    $val = explode("/",strstr($work,"amiga-aweb"));
    return array(0 => "AW", 1 => $family["browser"]["name"]["AW"]." ".(isset($val[1])?$val[1]:""));
  }
// --- test for Voyager ---
  if(strpos($work, "amigavoyager")) {
    $val = explode("/",strstr($work,"amigavoyager"));
    return array(0 => "AV", 1 => $family["browser"]["name"]["AV"]." ".(isset($val[1])?$val[1]:""));
  }
// --- test for NetPositive ---
  if(strpos($work, "netpositive")) {
    $val = explode("/",strstr($work,"netpositive"));
    return array(0 => "NP", 1 => $family["browser"]["name"]["NP"]." ".(isset($val[1])?$val[1]:""));
  }
// --- test for cURL-Loader ---
  if (strpos($work, "curl")) {
    $val = explode(" ",$work);
    $val = explode("/",$val[0]);
    return array(0 => "CU", 1 => $family["browser"]["name"]["CU"]." ".(isset($val[1])?$val[1]:""));
  }
// --- test for Epiphany ---
  if(strstr($work, "epiphany")) {
    preg_match("/epiphany\/([0-9.]+)/",$work,$regs);
    return array(0 => "EP", 1 => $family["browser"]["name"]["EP"]." ".$regs[1]);
  }
// --- test for W3M ---
  if(strstr($work, "w3m")) {
    preg_match("/w3m\/([0-9.]+)?/",$work,$regs);
    return array(0 => "W3", 1 => $family["browser"]["name"]["W3"]." ".$regs[1]);
  }
// --- test for Netscape-Browsers ---
  if(strpos($work, "netscape")) {
    $val = explode("/",$work);
    $val = explode(" ",$val[3]);
    return array(0 => "N6", 1 => $family["browser"]["name"]["N6"]." ".$val[0]);
  }
// --- test for Mozilla and Netscape Comminicator ---
  if(strpos($work, "mozilla")) {
    // -- test for Mozilla --
    if(preg_match("/rv:[0-9].[0-9][a-b]?.?[0-9]?/",$work,$val)) {
      $ver = explode(")", str_replace("rv:","",$val[0]));
      return array(0 => "MZ", 1 => $family["browser"]["name"]["MZ"]." ".str_replace("rv:","",$ver[0]));
    }
    // -- test for Netscape-Communicator --
    if(preg_match("/\[[a-zA-Z]{1,2}\]/",$work)) {
      $val = explode(" ",$work);
      preg_match("/[1-4].[0-9]{1,2}/", substr($val[0],8), $ver);
      return array(0 => "NS", 1 => $family["browser"]["name"]["NS"]." ".(isset($ver[0])?$ver[0]:""));
    }
    if(preg_match("/\/[34]./",$work)) {
      $val = explode(" ",$work);
      $val = explode("/",$val[0]);
      return array(0 => "NS", 1 => $family["browser"]["name"]["NS"]." ".(isset($val[1])?$val[1]:""));
    }
  }
// --- test for historicals ---
  if(strpos($work, "microsoft internet explorer")) {
    $var = strstr($work, "/");
    if (preg_match("/308|425|426|474|0b1/", $var)){
      $ver = "1.5";
    } else $ver = "1.0";
    return array(0 => "IE", 1 => $family["browser"]["name"]["IE"]." ".$ver);
  }
// --- test for Avant Go ---
  if (strpos($work, "avantgo")) {
    $val = explode(" ",strstr($work,"avantgo"));
    return array(0 => "GO", 1 => $family["browser"]["name"]["GO"]." ".(isset($val[0])?$val[0]:""));
  }
// --- test for Dillo ---
  if (strpos($work, "dillo")) {
    if(preg_match("/dillo/([0-9.]{1,10})/", $work, $version)) $ver =" ".$version[1];
    else $ver = "";
    return array(0 => "DI", 1 => $family["browser"]["name"]["DI"]." ".$ver);
  }
// --- test for ABrowse ---
  if (strpos($work, "abrowse")) {
    if(preg_match("/abrowse[ \/\-]([0-9.]{1,10})/", $work, $version)) $ver =" ".$version[1];
    else $ver = "";
    return array(0 => "AB", 1 => $family["browser"]["name"]["AB"]." ".$ver);
  }
// --- test for ANTFresco ---
  if (strpos($work, "antfresco")) {
    if(preg_match("/antfresco[ \/]([0-9.]{1,10})/", $work, $version)) $ver =" ".$version[1];
    else $ver = "";
    return array(0 => "ANT", 1 => $family["browser"]["name"]["ANT"]." ".$ver);
  }
// --- test for AOLbrowser ---
  if (strpos($work, "aol")) {
    if(preg_match("/aol[ \/\-]([0-9.]{1,10})/", $work, $version)) $ver =" ".$version[1];
    else $ver = "";
    return array(0 => "AOL", 1 => $family["browser"]["name"]["AOL"]." ".$ver);
  }
// --- test for Phoenix ---
  if (strpos($work, "phoenix")) {
    if(preg_match("/phoenix/([0-9.+]{1,10})/", $work, $version)) $ver =" ".$version[1];
    else $ver = "";
    return array(0 => "PH", 1 => $family["browser"]["name"]["PH"]." ".$ver);
  }

// --- Unknown ---
  return array(0 => "UKN", 1 => false);
} ?>