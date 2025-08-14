<?php

/*======================================================================*\
|| #################################################################### ||
||  Program Name         : VirtuaNews Pro                                 
||  Release Version      : 1.0.3                                          
||  Program Author       : VirtuaSystems                                  
||  Supplied by          : Ravish                                         
||  Nullified by         : WTN Team                                       
||  Distribution         : via WebForum, ForumRU and associated file dumps
|| #################################################################### ||
\*======================================================================*/

error_reporting(7);

require("includes/getglobals.php");

if (isset($showqueries) == 0) {
  $showqueries = 0;
}

if ($showqueries) {
  $pagestarttime = microtime();
}

require("admin/config.php");
require("includes/db_".strtolower(trim($dbservertype)).".php");
require("includes/functions.php");

unset($db_query_count);
unset($db_query_arr);
$inadmin = 0;

$db = vn_connect();

if (!@include("static/options.php")) {
  include("includes/writefunctions.php");
  saveoptions();
}

$cat_arr = getcat_arr();
$theme_arr = getthemearr();
$timeoffset = $timeoffset * 3600;

if ($nocacheheaders) {
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
}

unset($pagesetid);

unset($servertoobusy);

if (intval($loadlimit) > 0) {

  $loadpath = "/proc/loadavg";

  if(file_exists($loadpath)) {
    $filelink = @fopen($loadpath,"r");
    $loadavg = @fread($filelink,6);
    @fclose($filelink);
  } else {
    $loadavg = "";
  }

  $loadavg = explode(" ",$loadavg);
  if (trim($loadavg[0]) > $loadlimit) {
    $servertoobusy = 1;
  }
}

$location = basename($PHP_SELF)."?".$QUERY_STRING;
$location = str_replace("&","&amp;",$location);
$location = xss_clean($location);

if (!$servertoobusy) {
  include("includes/sessions.php");
}

$cat_name = $cat_arr[$catid][name];

if (substr_count($location,"themeid=")) {

  $location = preg_replace("/([^\"]*)themeid=([^\"]*)&amp;([^\"]*)/i","\\1&amp;\\3",$location);
  $location = preg_replace("/([^\"]*)themeid=([^\"]*)$/i","\\1",$location);
  $location = preg_replace("/([^\"]*)&amp;&amp;([^\"]*)/i","\\1&amp;\\2",$location);

  if (substr($location,-5) == "&amp;") {
    $location = substr($location,0,-5);
  }
}

$location = str_replace("'","\\'",$location);

if ($cat_arr[$catid][forcetheme] == 0) {
  if (isset($HTTP_COOKIE_VARS[theme_.$catid])) {
    $themeid = $HTTP_COOKIE_VARS[theme_.$catid];
  }

  if (isset($HTTP_GET_VARS[themeid])) {
    $themeid = $HTTP_GET_VARS[themeid];
  }

  if (!isset($theme_arr[$themeid])) {
    $themeid = $cat_arr[$catid][defaulttheme];
  }

  if (!$theme_arr[$themeid][allowselect] & !$staffid) {
    $themeid = $cat_arr[$catid][defaulttheme];
  }

} else {
  $themeid = $cat_arr[$catid][defaulttheme];
}

$pagesetid = $theme_arr[$themeid][pagesetid];

$stylevar = getstylevars($theme_arr[$themeid][stylesetid]);

if (!$siteopen & !$staffid) {
  standarderror("site_closed");
}

if ($servertoobusy) {
  $defaultcategory = $defaultcat_loggedout;
  standarderror("server_busy");
}

updatecookie("theme_$catid",$themeid);

if ($loggedin) {
  eval("\$welcometext = \"".returnpagebit("misc_welcometext_logged_in")."\";");
} else {
  eval("\$welcometext = \"".returnpagebit("misc_welcometext_logged_out")."\";");
}

if ($cat_arr[$catid][forcetheme] == 0) {

  foreach ($theme_arr as $tid => $info) {
    if ($info[allowselect] | $staffid) {
      $theme[id] = $tid;
      $theme[name] = $info[title];
      $selected = iif($themeid == $theme[id]," selected=\"selected\"","");
      eval("\$selector_options .= \"".returnpagebit("misc_theme_selector_option")."\";");
    }
  }

  eval("\$theme_selector = \"".returnpagebit("misc_theme_selector")."\";");
}

if ($cat_arr[$catid][showforumstats] & !empty($use_forum)) {
  $forumstats = returnforumstats();
}

if ($cat_arr[$catid][showsitestats]) {
  eval("\$sitestats = \"".returnpagebit("misc_stats_site")."\";");
}

if ($cat_arr[$catid][showpoll] && ($cat_arr[$catid][pollid] != 0)) {

  if (isset($votedpoll[$cat_arr[$catid][pollid]])) {
    include("static/polls/results_".$catid."_".$pagesetid.".php");
  } else {
    if ($loggedin) {
      if (query_first("SELECT userid FROM news_pollvote WHERE (userid = $userid) AND (pollid = " . $cat_arr[$catid][pollid] . ")")) {
        include("static/polls/results_".$catid."_".$pagesetid.".php");
      } else {
        include("static/polls/options_".$catid."_".$pagesetid.".php");
      }
    } else {
      if ($pollanonvote) {
        include("static/polls/options_".$catid."_".$pagesetid.".php");
      } else {
        include("static/polls/results_".$catid."_".$pagesetid.".php");
      }
    }
  }
}

$textareawidth = gettextareawidth();
$category_nav = getcat_nav();
$sitejump = makesitejump($catid);
checknewsprogram();

/*======================================================================*\
|| ####################################################################
|| # File: global.php
|| ####################################################################
\*======================================================================*/

?>