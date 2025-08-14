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

if (preg_match("/(admin\/option.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

$option_arr[general] = "General Options";
$option_arr[output] = "HTTP And Output Options";
$option_arr[user] = "User and Registration Options";
$option_arr[pm] = "Private Messaging Options";
$option_arr[format] = "Message Formatting Options";
$option_arr[stats] = "Stats Options";
$option_arr[ipbanning] = "IP Banning";
$option_arr[datetime] = "Date And Time Options";
$option_arr[recentpost] = "Recent Post Options";
$option_arr[news] = "News Post Options";
$option_arr[article] = "Article Options";
$option_arr[comment] = "Comment Options";
$option_arr[commentpost] = "Comment Posting Options";
$option_arr[commentedit] = "Comment Editing Options";
$option_arr[polls] = "Poll Options";
$option_arr[search] = "Search Options";
$option_arr[sitejump] = "Site Jump Options";

updateadminlog("set = $set");

switch ($action) {

case "option":

  if (!$option_arr[$set]) {
    $set = "general";
  }

  echohtmlheader();
  echoformheader("option_update","Update $option_arr[$set]");
  updatehiddenvar("set",$set);
  echotabledescription("You can use this page to edit the options for your site.  To edit each set of options please use the links to the left to navigate.");

  $getoptions = query("SELECT title,varname,value,description,code FROM news_option WHERE optiongroup = '$set' ORDER BY title");
  while ($options = fetch_array($getoptions)) {
    if ($options[varname] == "startpage") {
      $code = "<input type=\"radio\" name=\"option_$options[varname]\"  ".iif($options[value] == "news","checked","")." value=\"news\"> News Posts<br />
<input type=\"radio\" name=\"option_$options[varname]\" ".iif($options[value] == "cat","checked","")." value=\"cat\"> News Category Listing<br />\n
<input type=\"radio\" name=\"option_$options[varname]\" ".iif($options[value] == "custom","checked","")." value=\"custom\"> Custom Start Page<br />
<input type=\"radio\" name=\"option_$options[varname]\" ".iif($options[value] == "articles","checked","")." value=\"articles\"> Articles<br />\n
<input type=\"radio\" name=\"option_$options[varname]\" ".iif($options[value] == "search","checked","")." value=\"search\"> Search Page<br />\n
<input type=\"radio\" name=\"option_$options[varname]\" ".iif($options[value] == "archive","checked","")." value=\"archive\"> News Archive<br />\n";
      $moddata = getmoddata();
      while ($mod_arr = fetch_array($moddata)) {
        $code .= "<input type=\"radio\" name=\"option_$options[varname]\"  ".iif($options[value] == "mod_$mod_arr[name]","checked","")." value=\"mod_$mod_arr[name]\"> $mod_arr[text]<br />\n";
      }
      echotablerow("<b>$options[title]:</b><br />$options[description]",$code,"",65);
    } elseif (substr($options[varname],0,10) == "defaultcat") {
      echonewscatselect("<b>$options[title]:</b><br />$options[description]","option_$options[varname]",$options[value],1);
    } elseif ($options[code] == "") {
      echoinputcode("<b>$options[title]:</b><br />$options[description]","option_$options[varname]",$options[value],40,0,65);
    } elseif ($options[code] == "yesno") {
      echoyesnocode("<b>$options[title]:</b><br />$options[description]","option_$options[varname]",$options[value],"Yes","No",65);
    } elseif ($options[code] == "textarea") {
      echotextareacode("<b>$options[title]:</b><br />$options[description]","option_$options[varname]",$options[value],5,40,0,65);
    } else {
      eval ("\$code = \"$options[code]\";");
      echotablerow("<b>$options[title]:</b><br />$options[description]",$code,"",65);
    }
  }

  echoformfooter();
  echohtmlfooter();

break;

case "option_update":

  if (!$option_arr[$set]) {
    adminerror("Invalid Option Set","You have specified an invalid option set to edit.");
  }

  if ($set == "general") {
    if (!$cat_arr[$option_defaultcat_loggedout] | !$cat_arr[$option_defaultcat_loggedin] | !$cat_arr[$option_defaultcat_staff]) {
      adminerror("Invalid Default Category","You have specified an invalid default category.");
    }
  }

  $getoptions = query("SELECT id,varname FROM news_option WHERE (optiongroup = '$set')".iif($set == "vbb"," OR (varname = 'use_vbb')",""));
  while ($option_arr = fetch_array($getoptions)) {
    $varname = "option_$option_arr[varname]";
    query("UPDATE news_option SET value = '".$$varname."' WHERE id = $option_arr[id]");
  }

  saveoptions();
  writeallpages();
  echoadminredirect("admin.php?action=option&set=$set");

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/option.php
|| ####################################################################
\*======================================================================*/

?>