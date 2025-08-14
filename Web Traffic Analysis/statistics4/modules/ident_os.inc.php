<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + ident_os.inc.php - identify os from weblog-logfile for showlog
// +
// + Creation:		14.12.2004 - Daniel Sokoll
// + Last Update:	26.09.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$ident_os_version = "2.4.0";
// --- bot and unknown ---
$family["os"]["icon"]["BOT"] = $pic["dir"]."i_bot.png";
$family["os"]["name"]["BOT"] = "BOT";
$family["os"]["fkey"]["BOT"] = "BOT";
$family["os"]["icon"]["UKN"] = $pic["dir"]."i_unknown.png";
$family["os"]["name"]["UKN"] = $unknown_text;
$family["os"]["fkey"]["UKN"] = "UKN";
// --- Windows family ---
$family["os"]["icon"]["WIN"] = $pic["dir"]."i_os_win.png";
$family["os"]["name"]["WIN"] = "Windows";
$family["os"]["fkey"]["WIN"] = "WIN";
$family["os"]["icon"]["WXP"] = $pic["dir"]."i_os_wxp.png";
$family["os"]["name"]["WXP"] = "Windows";
$family["os"]["fkey"]["WXP"] = "WIN";
// --- Linux family ---
$family["os"]["icon"]["LIN"] = $pic["dir"]."i_os_linux.png";
$family["os"]["name"]["LIN"] = "Linux";
$family["os"]["fkey"]["LIN"] = "LIN";
// --- BSD family
$family["os"]["icon"]["BSD"] = $pic["dir"]."i_os_bsd.png";
$family["os"]["name"]["BSD"] = "BSD";
$family["os"]["fkey"]["BSD"] = "BSD";
$family["os"]["icon"]["FBSD"] = $pic["dir"]."i_os_freebsd.png";
$family["os"]["name"]["FBSD"] = "FreeBSD";
$family["os"]["fkey"]["FBSD"] = "BSD";
$family["os"]["icon"]["NBSD"] = $pic["dir"]."i_os_netbsd.png";
$family["os"]["name"]["NBSD"] = "NetBSD";
$family["os"]["fkey"]["NBSD"] = "BSD";
$family["os"]["icon"]["OBSD"] = $pic["dir"]."i_os_openbsd.png";
$family["os"]["name"]["OBSD"] = "OpenBSD";
$family["os"]["fkey"]["OBSD"] = "BSD";
$family["os"]["icon"]["BOS"] = $pic["dir"]."i_os_beo.png";
$family["os"]["name"]["BOS"] = "BeOS";
$family["os"]["fkey"]["BOS"] = "BSD";
// -- Macintosh family ---
$family["os"]["icon"]["MAC"] = $pic["dir"]."i_os_mac.png";
$family["os"]["name"]["MAC"] = "Macintosh";
$family["os"]["fkey"]["MAC"] = "MAC";
$family["os"]["icon"]["OSX"] = $pic["dir"]."i_os_osx.png";
$family["os"]["name"]["OSX"] = "Macintosh OS X";
$family["os"]["fkey"]["OSX"] = "MAC";
$family["os"]["icon"]["DRW"] = $pic["dir"]."i_os_drw.png";
$family["os"]["name"]["DRW"] = "Macintosh Darwin";
$family["os"]["fkey"]["DRW"] = "MAC";
$family["os"]["icon"]["PPC"] = $pic["dir"]."i_os_macppc.png";
$family["os"]["name"]["PPC"] = "Macintosh PPC";
$family["os"]["fkey"]["PPC"] = "MAC";
// --- Unix ---
$family["os"]["icon"]["UNX"] = $pic["dir"]."i_os_unix.png";	// Unix
$family["os"]["name"]["UNX"] = "Unix";
$family["os"]["fkey"]["UNX"] = "UNX";
$family["os"]["icon"]["SOS"] = $pic["dir"]."i_os_sos.png";  	//SunOS
$family["os"]["name"]["SOS"] = "Sun OS";
$family["os"]["fkey"]["SOS"] = "UNX";
$family["os"]["icon"]["AIX"] = $pic["dir"]."i_os_aix.png";  	//AIX
$family["os"]["name"]["AIX"] = "AIX";
$family["os"]["fkey"]["AIX"] = "UNX";
$family["os"]["icon"]["IRI"] = $pic["dir"]."i_os_iri.png";  	//IRIX
$family["os"]["name"]["IRI"] = "IRIX";
$family["os"]["fkey"]["IRI"] = "UNX";
$family["os"]["icon"]["HPX"] = $pic["dir"]."i_os_hpx.png";  	//HP-UX
$family["os"]["name"]["HPX"] = "HP-UX";
$family["os"]["fkey"]["HPX"] = "UNX";
$family["os"]["icon"]["CRY"] = $pic["dir"]."i_os_cry.png";  	// CrayOS
$family["os"]["name"]["CRY"] = "Cray OS";
$family["os"]["fkey"]["CRY"] = "UNX";
$family["os"]["icon"]["SCO"] = $pic["dir"]."i_os_sco.png";  	// Unixware
$family["os"]["name"]["SCO"] = "Unixware";
$family["os"]["fkey"]["SCO"] = "UNX";
// --- other ---
$family["os"]["icon"]["OS2"] = $pic["dir"]."i_os_os2.png";	// OS2
$family["os"]["name"]["OS2"] = "OS / 2";
$family["os"]["fkey"]["OS2"] = "OS2";
$family["os"]["icon"]["GEO"] = $pic["dir"]."i_os_geo.png";  	// Geos
$family["os"]["name"]["GEO"] = "Geos";
$family["os"]["fkey"]["GEO"] = "GEO";
$family["os"]["icon"]["SYM"] = $pic["dir"]."i_os_sym.png";  	// SymbianOS
$family["os"]["name"]["SYM"] = "Symbian OS";
$family["os"]["fkey"]["SYM"] = "SYM";
$family["os"]["icon"]["ATH"] = $pic["dir"]."i_os_ath.png";  	// AtheOS
$family["os"]["name"]["ATH"] = "AtheOS";
$family["os"]["fkey"]["ATH"] = "ATH";
$family["os"]["icon"]["QNX"] = $pic["dir"]."i_os_qnx.png";  	// QNX Photon
$family["os"]["name"]["QNX"] = "QNX Photon";
$family["os"]["fkey"]["QNX"] = "QNX";
$family["os"]["icon"]["RSQ"] = $pic["dir"]."i_os_rsq.png";  	// Risc OS
$family["os"]["name"]["RSQ"] = "Risc OS";
$family["os"]["fkey"]["RSQ"] = "RSQ";
$family["os"]["icon"]["PLM"] = $pic["dir"]."i_os_plm.png";  	// Palm OS
$family["os"]["name"]["PLM"] = "Palm OS";
$family["os"]["fkey"]["PLM"] = "PLM";
$family["os"]["icon"]["AMI"] = $pic["dir"]."i_os_ami.png";	// Amiga OS
$family["os"]["name"]["AMI"] = "Amiga OS";
$family["os"]["fkey"]["AMI"] = "AMI";
$family["os"]["icon"]["ATA"] = $pic["dir"]."i_os_ata.png";	// Atari OS
$family["os"]["name"]["ATA"] = "Atari OS";
$family["os"]["fkey"]["ATA"] = "ATA";

include_once("ident_bot.inc.php");

function ident_os($work) {
  global $knownbot,$unknown_text,$family;

  $work = " ".strtolower($work);
  $os_version = "";
// --- check for Bot / Crawler ---
  if(ident_bot($work)) return array(0 => "BOT", 1 => "");

// --- check for Windows ---
  if (strpos($work, "win")) {
    if(strpos($work, " nt")) {
      if(strpos($work, "nt 5.1")) return array(0 => "WXP", 1 => $family["os"]["name"]["WXP"]." XP");
      elseif(strpos($work, "nt 5.2")) return array(0 => "WXP", 1 => $family["os"]["name"]["WXP"]." 2003");
      elseif(strpos($work, "nt 5.0")) return array(0 => "WIN", 1 => $family["os"]["name"]["WIN"]." 2000");
      elseif(strpos($work, "nt 6.0")) return array(0 => "WXP", 1 => $family["os"]["name"]["WXP"]." Longhorn");
      else return array(0 => "WIN", 1 => $family["os"]["name"]["WIN"]." NT");
    } 
    if (strpos($work, "98")) return array(0 => "WIN", 1 => $family["os"]["name"]["WIN"]." 98");
    if(preg_match("/200[035]/",$work)) {
      if(strpos($work, "2000")) return array(0 => "WIN", 1 => $family["os"]["name"]["WIN"]." 2000");
      if (strpos($work, "2003")) return array(0 => "WXP", 1 => $family["os"]["name"]["WXP"]." 2003");
      if (strpos($work, "2005")) return array(0 => "WXP", 1 => $family["os"]["name"]["WXP"]." Longhorn");
    } 
    if (preg_match("/millennium|9x| me/",$work)) return array(0 => "WIN", 1 => $family["os"]["name"]["WIN"]." ME");
    if (strpos($work, " ce")) return array(0 => "WIN", 1 => $family["os"]["name"]["WIN"]." CE");
    if(strpos($work,"win64")) return array(0 => "WXP", 1 => $family["os"]["name"]["WIN"]." XP x64");
    if (strpos($work, " xp")) return array(0 => "WXP", 1 => $family["os"]["name"]["WXP"]." XP");
    if(strpos($work,"winnt")) return array(0 => "WIN", 1 => $family["os"]["name"]["WIN"]." NT");
    if (preg_match("/95|32/",$work)) return array(0 => "WIN", 1 => $family["os"]["name"]["WIN"]." 95");
    if (preg_match("/win[\/ ][13][6]?/i",$work)) return array(0 => "WIN", 1 => $family["os"]["name"]["WIN"]." 3.x");
  } 
  if (strpos($work, "microsoft") && strstr($work, "pocket")) return array(0 => "WIN", 1 => $family["os"]["name"]["WIN"]." CE");

// --- check for Macintosh ---
  if (preg_match("/powerpc|ppc|mac|68000|68k|cyberdog|apple|camino|darwin/",$work)) {
    if(strpos($work, "darwin")) return array(0 => "DRW", 1 => $family["os"]["name"]["DRW"]);
    elseif(strpos($work, "os x")) return array(0 => "OSX", 1 => $family["os"]["name"]["OSX"]);
    elseif(preg_match("/mac(_power|intosh.+p)pc/",$work)) return array(0 => "PPC", 1 => $family["os"]["name"]["PPC"]);
    else return array(0 => "MAC", 1 => $family["os"]["name"]["MAC"]." Mac OS");
  }
// --- check for SunOS ---
  if (strpos($work, "sunos")) return array(0 => "SOS", 1 => $family["os"]["name"]["SOS"]);
// --- check for OS2 ---
  if (preg_match("/os[\/]?2/",$work)) return array(0 => "OS2", 1 => $family["os"]["name"]["OS2"]);
// --- check for Unix ---
  if (strpos($work, "unix")) return array(0 => "UNX", 1 => $family["os"]["name"]["UNX"]);
// --- check for BeOS ---
  if (strpos($work, "beos")) return array(0 => "BOS", 1 => $family["os"]["name"]["BOS"]);
// --- check for AIX ---
  if (strpos($work, "aix")) return array(0 => "AIX", 1 => $family["os"]["name"]["AIX"]);
// --- check for IRIX ---
  if (strpos($work, "irix")) return array(0 => "IRI", 1 => $family["os"]["name"]["IRI"]);
// --- check for HP-UX ---
  if (strpos($work, "hp-ux")) return array(0 => "HPX", 1 => $family["os"]["name"]["HPX"]);
// --- check for FreeBSD / NetBSD / OpenBSD ---
  if(strpos($work, "bsd")) {
    if(strpos($work, "open")) return array(0 => "OBSD", 1 => $family["os"]["name"]["OBSD"]);
    elseif(strpos($work, "net")) return array(0 => "NBSD", 1 => $family["os"]["name"]["NBSD"]);
    elseif(strpos($work, "free")) return array(0 => "FBSD", 1 => $family["os"]["name"]["FBSD"]);
    else return array(0 => "BSD", 1 => $family["os"]["name"]["BSD"]);
  }
// --- check for Linux ---
  if (preg_match("/gentoo|linux|x11/",$work)) {
    if(preg_match("/linux ([0-9].[0-9]{1,2}.[0-9]{1,2})/",$work,$version)) $os_version =" ".$version[1];
    return array(0 => "LIN", 1 => $family["os"]["name"]["LIN"].$os_version);
  }
// --- check for Geos ---
  if (strpos($work, "geos")) return array(0 => "GEO", 1 => $family["os"]["name"]["GEO"]);
// --- check for Amiga OS ---
  if (strpos($work, "amiga")) return array(0 => "AMI", 1 => $family["os"]["name"]["AMI"]);
// --- check for Atari OS ---
  if (strpos($work, "atari")) return array(0 => "ATA", 1 => $family["os"]["name"]["ATA"]);
// --- check for AtheOS ---
  if (strpos($work, "atheos")) return array(0 => "ATH", 1 => $family["os"]["name"]["ATH"]);
// --- check for QNX Photon ---
  if (strpos($work, "photon")) return array(0 => "QNX", 1 => $family["os"]["name"]["QNX"]);
// --- check for Cray OS ---
  if (strpos($work, "crayos")) return array(0 => "CRY", 1 => $family["os"]["name"]["CRY"]);
// --- check for Symbian OS ---
  if (strpos($work, "symbianos")) {
    $val = explode("/",strstr($work,"symbianos"));
    return array(0 => "SYM", 1 => $family["os"]["name"]["SYM"]." ".(isset($val[1])?$val[1]:""));
  }
// --- check for Unixware ---
  if (strpos($work,"unixware")) {
    if(preg_match("/unixware[ /]?([0-9.]{1,10})/", $work, $version)) $os_version =" ".$version[1];
    return array(0 => "SCO", 1 => $family["os"]["name"]["SCO"]." ".$os_version);
  }
// --- check for Risk OS ---
  if (strpos($work,"risc")) {
    if(preg_match("/risc[ \-]?os[ /]?([0-9.]{1,10})/", $work, $version)) $os_version =" ".$version[1];
    return array(0 => "RSQ", 1 => $family["os"]["name"]["RSQ"]." ".$os_version);
  }
// --- check for Palm OS ---
  if (strpos($work,"palm")) {
    if(preg_match("/palm[ \-]?(source|os)[ \/]?([0-9.]{1,10})/", $work, $version)) $os_version =" ".$version[2];
    return array(0 => "PLM", 1 => $family["os"]["name"]["PLM"]." ".$os_version);
  }

// --- return Unknown OS ---
  return array(0 => "UKN", 1 => $family["os"]["name"]["UKN"]);
} ?>