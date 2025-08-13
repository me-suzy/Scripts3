<?php
#
# FILE: common.php
# DATE: Updated 05/31/03, Orig 01/10/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Contains the common functions shared across all scripts:
# getrandom(), getheader(), getfooter(), getloginform(), and getlast().
#

require_once('config.php');

function getrandom(){
  global $imageurl, $db;
  #Select a random picture from the database and return the HTML
  #required to display it.

  $result = mysql_query("SELECT textid, filename, url, score, numvotes FROM pictures WHERE active=1 ORDER BY RAND() LIMIT 1", $db);
  $myrow = mysql_fetch_array($result);

  #Load the random picture template
  $template = @implode('', @file('templates/random.html'));

  #Determine whether this picture is local or remote
  $imgsrc = ($myrow[filename] == '') ? $myrow[url] : "$imageurl/$myrow[filename]";

  #Replace any tokens
  $template = str_replace('%score%', sprintf("%.01f", $myrow[score]), $template);
  $template = str_replace('%numvotes%', $myrow[numvotes], $template);
  $template = str_replace('%votelink%', "vote.php?tag=$myrow[textid]", $template);
  $template = str_replace('%imageurl%', $imageurl, $template);
  $template = str_replace('%loginform%', getloginform(), $template);
  $template = str_replace('%imgsrc%', $imgsrc, $template);
 
  return $template;
}

function getheader(){
  global $imageurl;
  #Get the header and replace any tokens inside

  $template = @implode('', @file('templates/header.html'));
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
  $template = str_replace('%imageurl%', $imageurl, $template);
  $template = str_replace('%loginform%', getloginform(), $template);

  return $template;
}

function getfooter(){
  global $imageurl;
  #Get the header and replace any tokens inside

  $template = @implode('', @file('templates/footer.html'));
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
  $template = str_replace('%imageurl%', $imageurl, $template);
  $template = str_replace('%loginform%', getloginform(), $template);

  return $template;
}

function getloginform(){
  global $imageurl;
  #Get the login form and replace any tokens inside

  $template = @implode('', @file('templates/loginform.html'));
  $template = str_replace('%imageurl%', $imageurl, $template);

  return $template;
}

function top5(){
  global $imageurl, $db;
  #Get the top 5 ranked pictures and return the HTML code to display them
  $result = mysql_query("SELECT textid, filename, url, score, numvotes FROM pictures WHERE active=1 ORDER BY score DESC LIMIT 5", $db);
  
  #Load the top5 template
  $template = @implode('', @file('templates/top5.html'));
  
  $num = 1;
  while($myrow = mysql_fetch_array($result)){
    #Determine whether this picture is local or remote
    $imgsrc = ($myrow[filename] == '') ? $myrow[url] : "$imageurl/$myrow[filename]";

    #Replace tokens for this image
    $template = str_replace("%score$num%", sprintf("%.01f", $myrow[score]), $template);
    $template = str_replace("%imgsrc$num%", $imgsrc, $template);
    $template = str_replace("%votelink$num%", "vote.php?tag=$myrow[textid]", $template);
    $template = str_replace("%numvotes$num%", $myrow[numvotes], $template);
    $num++;
  }

  #Replace tokens in the rest of the template
  $template = str_replace('%imageurl%', $imageurl, $template);

  return $template;
}

function getlast($tag = 0, $yourvote = 0){
  global $imageurl, $db;
  #Determine the new stats for the "Last Rate" and return the
  #HTML required to display it

  if($tag){
    $result = mysql_query("SELECT id, textid, filename, url, score, numvotes FROM pictures WHERE textid='$tag' AND active=1", $db);
    $myrow = mysql_fetch_array($result);

    #Determine whether this picture is local or remote
    $imgsrc = ($myrow[filename] == '') ? $myrow[url] : $imageurl . "/$myrow[filename]";

    #Load the last rate template
    $template = @implode('', @file('templates/lastrate.html'));

    #Replace any tokens
    $template = str_replace('%score%', sprintf("%.01f", $myrow[score]), $template);
    $template = str_replace('%numvotes%', $myrow[numvotes], $template);
    $template = str_replace('%yourvote%', $yourvote, $template);
    $template = str_replace('%imageurl%', $imageurl, $template);
    $template = str_replace('%imgsrc%', $imgsrc, $template);
  }
  else{
    #This visitor hasn't voted yet, display the "You haven't voted" info
    $template = @implode('', @file('templates/lastrate_none.html'));
  }
  return $template;
}
?>