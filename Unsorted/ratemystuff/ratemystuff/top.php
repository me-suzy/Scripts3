<?php
#
# FILE: getimage.php
# DATE: Updated 05/31/03, Orig 01/10/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Loads the "top" template for viewing the top 10 best or top
# 10 worst rated pictures, replaces any tokens, and displays the page. You
# must pass a parameter named "sort" with a value of "best" or "worst" when
# linking to this script, e.g.
#
# <a href="top.php?sort=best">10 Best Rated Pics</a> 
# <a href="top.php?sort=worst">10 Worst Rated Pics</a>
#

require_once('common.php');

#Load the Top template
$template = @implode('', @file('templates/top.html'));

#Should we show best or worst?
if($_GET[sort] == 'best'){
  $ascdesc = 'DESC';
}
else{
  $ascdesc = 'ASC';
}

#Get the 10 best or worst photos
$result = mysql_query("SELECT * FROM pictures WHERE active=1 ORDER BY score $ascdesc LIMIT 10", $db);

$num = 1;
while($myrow = mysql_fetch_array($result)){
  #Determine whether this picture is local or remote
  $imgsrc = ($myrow[filename] == '') ? $myrow[url] : "$imageurl/$myrow[filename]";

  #Replace tokens for this image
  $template = str_replace("%id$num%", "<a href=\"vote.php?tag=$myrow[textid]\">" . sprintf("%06d", $myrow[id]) . "</a>", $template);
  $template = str_replace("%score$num%", sprintf("%.01f", $myrow[score]), $template);
  $template = str_replace("%imgsrc$num%", $imgsrc, $template);
  $template = str_replace("%adddate$num%", date("Y-m-d H:i", (int)$myrow[adddate]), $template);
  $template = str_replace("%votelink$num%", "vote.php?tag=$myrow[textid]", $template);
  $template = str_replace("%numvotes$num%", $myrow[numvotes], $template);
  $num++;
}

#Replace tokens in the rest of the template
$template = str_replace('%sort%', ($_GET[sort] == 'best') ? 'best' : 'worst', $template);
$template = str_replace('%imageurl%', $imageurl, $template);
$template = str_replace('%header%', getheader(), $template);
$template = str_replace('%footer%', getfooter(), $template);
$template = str_replace('%loginform%', getloginform(), $template);
$numrandom = preg_match_all("/%random%/", $template, $result);
if($numrandom > 0){
  $randoms = array();
  for($i=0; $i<$numrandom; $i++)
    array_push($randoms, getrandom());
  $templatearray = explode('%random%', $template);
  for($i=0; $i<count($templatearray); $i++){
    $templatearray[$i] .= $randoms[$i];
  }
  $template = implode('', $templatearray);
}
$template = str_replace('%lastrate%', getlast(), $template);
$template = str_replace('%top5%', top5(), $template);

echo $template;

?>