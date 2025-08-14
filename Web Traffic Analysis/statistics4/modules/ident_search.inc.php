<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + ident_search.inc.php - identify searchengine from weblog-logfile for showlog
// +
// + Creation:		06.04.2005 - Daniel Sokoll
// + Last Update:	26.09.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$ident_search_version = "2.0.1";
$ident_search["About"] = $pic["dir"]."i_bot_about.png";
$ident_search["Acoon"] = $pic["dir"]."i_bot_acoon.png";
$ident_search["Alexa"] = $pic["dir"]."i_bot_alexa.png";
$ident_search["Altavista"] = $pic["dir"]."i_bot_altavista.png";
$ident_search["AllTheWeb"] = $pic["dir"]."i_search_alltheweb.png";
$ident_search["Aonde"] = $pic["dir"]."i_bot_aonde.png";
$ident_search["AOL"] = $pic["dir"]."i_search_aol.png";
$ident_search["Amfibi"] = $pic["dir"]."i_bot_amfibi.png";
$ident_search["AskJeeves"] = $pic["dir"]."i_bot_askjeeves.png";
$ident_search["CometSearch"] = $pic["dir"]."i_bot_comet.png";
$ident_search["Dir"] = $pic["dir"]."i_bot_dir.png";
$ident_search["Dogpile"] = $pic["dir"]."i_search_dogpile.png";
$ident_search["DMOZ"] = $pic["dir"]."i_bot_dmoz.png";
$ident_search["Earthlink"] = $pic["dir"]."i_search_earthlink.png";
$ident_search["Entireweb"] = $pic["dir"]."i_bot_entireweb.png";
$ident_search["Envolk"] = $pic["dir"]."i_bot_envolk.png";
$ident_search["Euroseek"] = $pic["dir"]."i_bot_euroseek.png";
$ident_search["Excite"] = $pic["dir"]."i_bot_excite.png";
$ident_search["Fireball"] = $pic["dir"]."i_search_fireball.png";
$ident_search["Gigablast"] = $pic["dir"]."i_bot_gigabot.png";
$ident_search["Goforit"] = $pic["dir"]."i_bot_goforit.png";
$ident_search["Google"] = $pic["dir"]."i_bot_google.png";
$ident_search["HotBot"] = $pic["dir"]."i_search_hotbot.png";
$ident_search["Ixquick"] = $pic["dir"]."i_search_ixquick.png";
$ident_search["LookSmart"] = $pic["dir"]."i_bot_looksmart.png";
$ident_search["Lycos"] = $pic["dir"]."i_bot_lycos.png";
$ident_search["Mamma"] = $pic["dir"]."i_search_mamma.png";
$ident_search["Metacrawler"] = $pic["dir"]."i_search_metacrawler.png";
$ident_search["Metafinder"] = $pic["dir"]."i_search_metafinder.png";
$ident_search["Mozdex"] = $pic["dir"]."i_bot_mozdex.png";
$ident_search["MSN"] = $pic["dir"]."i_bot_msn.png";
$ident_search["Netscape"] = $pic["dir"]."i_browser_n6.png";
$ident_search["Overture"] = $pic["dir"]."i_bot_overture.png";
$ident_search["Picsearch"] = $pic["dir"]."i_bot_picsearch.png";
$ident_search["Regiochannel"] = $pic["dir"]."i_bot_regiochannel.png";
$ident_search["Search.com"] = $pic["dir"]."i_search_searchcom.png";
$ident_search["Searchalot"] = $pic["dir"]."i_bot_seekport.png";
$ident_search["SearchScout"] = $pic["dir"]."i_search_searchscout.png";
$ident_search["Seekport"] = $pic["dir"]."i_bot_seekport.png";
$ident_search["Sharelook"] = $pic["dir"]."i_search_sharelook.png";
$ident_search["Singingfish"] = $pic["dir"]."i_bot_singingfish.png";
$ident_search["T-Online"] = $pic["dir"]."i_search_t-online.png";
$ident_search["Teoma"] = $pic["dir"]."i_bot_askjeeves.png";
$ident_search["Tiscali"] = $pic["dir"]."i_search_tiscali.png";
$ident_search["Vivisimo"] = $pic["dir"]."i_search_vivisimo.png";
$ident_search["Voila"] = $pic["dir"]."i_bot_voila.png";
$ident_search["Web.com"] = $pic["dir"]."i_search_webcom.png";
$ident_search["Web.de"] = $pic["dir"]."i_search_web.png";
$ident_search["Walhello"] = $pic["dir"]."i_bot_walhello.png";
$ident_search["WebCrawler"] = $pic["dir"]."i_search_webcrawler.png";
$ident_search["Websearch"] = $pic["dir"]."i_search_websearch.png";
$ident_search["WiseNet"] = $pic["dir"]."i_bot_zyborg.png";
$ident_search["WWWeasel"] = $pic["dir"]."i_bot_wwweasel.png";
$ident_search["Yahoo"] = $pic["dir"]."i_bot_yahoo.png";

function ident_searchengine($work) {
  $work = " ".strtolower($work);

  if(strpos($work, "google")) return("Google");
  if(strpos($work, "altavista")) return("Altavista");
  if(strpos($work, "yahoo")) return("Yahoo");
  if(strpos($work, "webcrawler")) return("WebCrawler");
  if(strpos($work, "wisenut")) return("WiseNet");
  if(strpos($work, "alltheweb")) return("AllTheWeb");
  if(strpos($work, "aol")) return("AOL");
  if(strpos($work, "lycos")) return("Lycos");
  if(strpos($work, "ask")) return("AskJeeves");
  if(strpos($work, "excite")) return("Excite");
  if(strpos($work, "msn")) return("MSN");
  if(strpos($work, "teoma")) return("Teoma");
  if(strpos($work, "alexa")) return("Alexa");
  if(strpos($work, "dmoz")) return("DMOZ");
  if(strpos($work, "overture")) return("Overture");
  if(strpos($work, "about")) return("About");
  if(strpos($work, "looksmart")) return("LookSmart");
  if(strpos($work, "t-online")) return("T-Online");
  if(strpos($work, "vivisimo")) return("Vivisimo");
  if(strpos($work, "fireball")) return("Fireball");
  if(strpos($work, "netscape")) return("Netscape");
  if(strpos($work, "metafinder.de")) return("Metafinder");
  if(strpos($work, "suche.web.de")) return("Web.de");
  if(strpos($work, "www.web.com")) return("Web.com");
  if(strpos($work, "acoon")) return("Acoon");
  if(strpos($work, "euroseek")) return("Euroseek");
  if(strpos($work, "dogpile")) return("Dogpile");
  if(strpos($work, "cometsystems")) return("CometSearch");
  if(strpos($work, "dir.com")) return("Dir");
  if(strpos($work, "earthlink")) return("Earthlink");
  if(strpos($work, "ixquick")) return("Ixquick");
  if(strpos($work, "mamma")) return("Mamma");
  if(strpos($work, "metacrawler")) return("Metacrawler");
  if(strpos($work, "monstercrawler")) return("Monstercrawler");
  if(strpos($work, "searchalot")) return("Searchalot");
  if(strpos($work, "searchscout")) return("SearchScout");
  if(strpos($work, "sharelook")) return("Sharelook");
  if(strpos($work, "tiscali")) return("Tiscali");
  if(strpos($work, "voila")) return("Voila");
  if(strpos($work, "entireweb")) return("Entireweb");
  if(strpos($work, "wedoo")) return("Wedoo");
  if(strpos($work, "amfibi")) return("Amfibi");
  if(strpos($work, "goforit")) return("Goforit");
  if(strpos($work, "picsearch")) return("Picsearch");
  if(strpos($work, "seekport")) return("Seekport");
  if(strpos($work, "walhello")) return("Walhello");
  if(strpos($work, "myway") || strpos($work, "mywebsearch")) return("MyWebSearch");
  if(strpos($work, "websearch")) return("Websearch");
  if(strpos($work, "wwweasel")) return("WWWeasel");
  if(strpos($work, "aonde")) return("Aonde");
  if(strpos($work, "singingfish")) return("Singingfish");
  if(strpos($work, "gigablast")) return("Gigablast");
  if(strpos($work, "regiochannel")) return("Regiochannel");
  if(strpos($work, "hotbot.com")) return("HotBot");
  if(strpos($work, "mozdex")) return("Mozdex");
  if(strpos($work, "envolk")) return("Envolk");
  if(strpos($work, "search.com") == 1 || strpos($work, "search.com") == 5) return("Search.com");

  return(false);
}
?>