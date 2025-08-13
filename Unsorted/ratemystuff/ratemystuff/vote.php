<?php
#
# FILE: vote.php
# DATE: Updated 05/31/03, Orig 01/10/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Checks votes for validity and adds them to the database.
#

require_once('common.php');

#If the form was posted, process the vote and exit
if($_POST[vote]){
  #Make sure we got a tag, voteid, and vote value
  if(!($_POST[tag] && $_POST[id] && $_POST[vote])){
    #Invalid vote, redirect to the vote page
    header('Location: vote.php');
    exit;
  }
 
  #Make sure the vote provided was valid
  if((!is_numeric($_POST[vote])) || ($_POST[vote] < 1) || ($_POST[vote] > 10)){
    #Invalid vote, redirect to the vote page
    header('Location: vote.php');
    exit;
  }

  #Make sure the tag provided was valid 
  $result = mysql_query("SELECT id, score, numvotes, sumvotes FROM pictures WHERE textid='$_POST[tag]' AND active=1", $db);
  if(mysql_num_rows($result) < 1){
    #Invalid vote, redirect to the vote page
    header('Location: vote.php');
    exit;
  }
  $myrow = mysql_fetch_array($result);

  #Make sure this user hasn't voted on this image already
  $stuffproof = mysql_query("SELECT COUNT(*) FROM ratings WHERE ip='$_SERVER[REMOTE_ADDR]' AND picnum=$myrow[id]", $db);
  $proofrow = mysql_fetch_array($stuffproof);
  if($proofrow[0] > 0){
    #This user already voted on this picture, redirect to the vote page
    header('Location: vote.php');
    exit;
  } 

  #Calculate the new score and update the record
  $time = time();
  $numvotes = $myrow[numvotes] + 1;
  $sumvotes = $myrow[sumvotes] + $_POST[vote];
  $score = (float)$sumvotes / $numvotes;
  $result = mysql_query("UPDATE pictures SET score=$score, numvotes=$numvotes, sumvotes=$sumvotes, lastvote=$time WHERE textid='$_POST[tag]'", $db);

  #Drop this vote into the ratings table
  $result = mysql_query("INSERT INTO ratings SET textid='$_POST[id]', picnum=$myrow[id], score=$_POST[vote], date=$time, ip='$_SERVER[REMOTE_ADDR]'", $db);

  $prevtag = $_POST[tag];

  #Choose the next picture to display at random
  $result = mysql_query("SELECT textid, filename, url, score, numvotes, lastvote, owner FROM pictures WHERE active=1 AND textid<>'$prevtag' ORDER BY RAND() LIMIT 1", $db);
  $myrow = mysql_fetch_array($result);
  $tag = $myrow[textid];

  $result = mysql_query("SELECT hookup FROM users WHERE username='$myrow[owner]'", $db);
  $userrow = mysql_fetch_array($result);

  #Load the rank form template (1-10 scale)
  $rankform = @implode('', @file('templates/rankform.html'));
  $rankform = str_replace('%tag%', $tag, $rankform);
  $rankform = str_replace('%id%', md5(uniqid('')), $rankform);
  $rankform = str_replace('%imageurl%', $imageurl, $rankform);

  if($userrow[hookup] == 1){
    #Load the hookup page template
    $hookup = @implode('', @file('templates/hookupform.html'));
    $hookup = str_replace('%tag%', $myrow[textid], $hookup);
  }
  else{
    $hookup = '';
  }

  #Load the voting page template
  $template = @implode('', @file('templates/votepage.html'));
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
  $template = str_replace('%lastrate%', getlast($prevtag, $_POST[vote]), $template);
  $template = str_replace('%mainpage%', 'mainpage', $template);
  $template = str_replace('%top5%', top5(), $template);
  $template = str_replace('%hookup%', $hookup, $template);
  $template = str_replace('%rankform%', $rankform, $template);
  $template = str_replace('%tag%', $tag, $template);
  $template = str_replace('%linkurl%', "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]?tag=$tag", $template);

  #Display the page
  echo $template;

  exit;
}

#Otherwise we want to display a ballot
if(!$_GET[tag]){
  #No tag (textid) was provided, we'll pick a random one
  $result = mysql_query("SELECT textid, filename, url, score, numvotes, lastvote, owner FROM pictures WHERE active=1 ORDER BY RAND() LIMIT 1", $db);
}
else{
  #A tag was provided, look up that record
  $result = mysql_query("SELECT filename, url, score, numvotes, lastvote, owner FROM pictures WHERE textid='$_GET[tag]' AND active=1");
}
if(mysql_num_rows($result) < 1){
  #An invalid tag (textid) was provided. Redirect to this page with
  #no tag argument, which will fetch a random one.
  header('Location: vote.php');
  exit;
}
$myrow = mysql_fetch_array($result);
$result = mysql_query("SELECT hookup FROM users WHERE username='$myrow[owner]'", $db);
$userrow = mysql_fetch_array($result);

$tag = ($_GET[tag]) ? $_GET[tag] : $myrow[textid];

#Load the rank form template (1-10 scale)
$rankform = @implode('', @file('templates/rankform.html'));
$rankform = str_replace('%tag%', $tag, $rankform);
$rankform = str_replace('%id%', md5(uniqid('')), $rankform);
$rankform = str_replace('%imageurl%', $imageurl, $rankform);

if($userrow[hookup] == 1){
  #Load the hookup page template
  $hookup = @implode('', @file('templates/hookupform.html'));
  $hookup = str_replace('%tag%', ($_GET[tag] != '') ? $_GET[tag] : $myrow[textid], $hookup);
}
else{
  $hookup = '';
}

#Load the voting page template
$template = @implode('', @file('templates/votepage.html'));
$template = str_replace('%header%', getheader(), $template);
$template = str_replace('%footer%', getfooter(), $template);
$template = str_replace('%loginform%', getloginform(), $template);
$template = str_replace('%hookup%', $hookup, $template);
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
$template = str_replace('%mainpage%', "mainpage", $template);
$template = str_replace('%top5%', top5(), $template);
$template = str_replace('%rankform%', $rankform, $template);
$template = str_replace('%tag%', $tag, $template);
$template = str_replace('%linkurl%', "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]?tag=$tag", $template);

#Display the page
echo $template;
?>