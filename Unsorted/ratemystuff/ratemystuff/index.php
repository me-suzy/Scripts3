<?php
#
# FILE: index.php
# DATE: Updated 05/31/03, Orig 01/10/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Loads the index template, replaces any tokens within it,
# and displays it to the visitor.
#

require_once('common.php');

#Load the index template
$template = @implode('', @file('templates/index.html'));

#Replace any tokens
$template = str_replace('%header%', getheader(), $template);
$template = str_replace('%footer%', getfooter(), $template);
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
$template = str_replace('%loginform%', getloginform(), $template);
$template = str_replace('%top5%', top5(), $template);

#Display the index
echo $template;
?>