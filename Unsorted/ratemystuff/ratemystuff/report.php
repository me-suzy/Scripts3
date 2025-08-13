<?php
#
# FILE: report.php
# DATE: 01/10/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Processes visitors' reports of images they think are offensive.
# The reports will then appear in the adminstrative menu.
#

require_once("common.php");

#Did we get a tag (textid)?
if(!$_GET[tag]){
  die("You can't call this script directly, you have to click the report link from a specific rating page.");
}

#Did we get a _valid_ tag (textid)?
$result = mysql_query("SELECT textid FROM pictures WHERE textid='$_GET[tag]'", $db);
if(mysql_num_rows($result) < 1){
  #Invalid tag provided
  die("Sorry, $_GET[tag] is not a valid picture ID.");
}

#Drop this into the reports table
$time = time();
$result = mysql_query("INSERT INTO reports SET textid='$_GET[tag]', ipaddr='$_SERVER[REMOTE_ADDR]', date=$time", $db);

#Load the template
$template = @implode("", @file("templates/report.html"));
$template = str_replace("%tag%", $_GET[tag], $template);
echo $template;
?>