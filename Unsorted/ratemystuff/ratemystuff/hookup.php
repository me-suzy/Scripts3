<?php
#
# FILE: hookup.php
# DATE: 05/31/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Handles all aspects of the HookUp system.
# Cookies are NOT used, the username and md5 hash of the
# user's crypted password are passed from page to page.
# This ensures maximum compatibility but sacrifices a bit
# of convenience.
#

require_once('common.php');

if($_REQUEST[act] == 'read'){
  #Someone wants to load up a HookUp message and possibly reply to it
  if(!$_REQUEST[tag]){
    error('No message ID was specified. If you are trying to load a HookUp message you received email about, make sure that the URL to the message is all on one line. You may want to copy it out of the email and paste it into your browser.');
  }
  $result = mysql_query("SELECT * FROM hookups WHERE textid='$_REQUEST[tag]'", $db);
  if(mysql_num_rows($result) < 1){
    error('An invalid message ID was specified. If you are trying to load a HookUp message you received email about, make sure that the URL to the message is all on one line. You may want to copy it out of the email and paste it into your browser.');
  }

  #Look up the Sender and Recipient
  $myrow = mysql_fetch_array($result);
  $result = mysql_query("SELECT * FROM users WHERE username='$myrow[sender]'", $db);
  if(mysql_num_rows($result) < 1){
    error('Sorry, but the sender of this message is no longer a user at our site. You cannot read or reply to this message.');
  }
  $senderrow = mysql_fetch_array($result);

  if($senderrow[hookup] != 1){
    error('Sorry, but the sender of this message is no longer accepting HookUp messages. You cannot read or reply to this message.');
  }

  $result = mysql_query("SELECT textid FROM pictures WHERE owner='$senderrow[username]'", $db);
  if(mysql_num_rows($result) < 1){
    error('Sorry, but the sender of this message is no longer a user at our site. You cannot read or reply to this message.');
  }
  $senderpicrow = mysql_fetch_array($result);

  $result = mysql_query("SELECT * FROM users WHERE username='$myrow[recipient]'", $db);
  if(mysql_num_rows($result) < 1){
    error('Sorry, but the recipient of this message is no longer a user at our site. You cannot read or reply to this message.');
  }
  $reciprow = mysql_fetch_array($result);
  $result = mysql_query("SELECT textid FROM pictures WHERE owner='$reciprow[username]'", $db);
  if(mysql_num_rows($result) < 1){
    error('Sorry, but the recipient of this message is no longer a user at our site. You cannot read or reply to this message.');
  }
  $recippicrow = mysql_fetch_array($result);

  #Load the Reply template
  $template = @implode('', @file('templates/hookup_reply.html'));
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

  $template = str_replace('%message%', stripslashes(nl2br($myrow[message])), $template);
  $template = str_replace('%top5%', top5(), $template);
  $template = str_replace('%tag%', $_REQUEST[tag], $template);
  $template = str_replace('%linkurl%', "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]?tag=$tag", $template);
  $template = str_replace('%lastrate%', '', $template);
  $template = str_replace('%sendtag%', $senderpicrow[textid], $template);
  $template = str_replace('%reciptag%', $recippicrow[textid], $template);

  echo $template;

  exit;
}

if($_POST[action] == 'SENDREPLY'){
  #Someone is sending a reply to a HookUp message
  if(!$_POST[hookupid]){
    #Fields not filled out
    error('For some reason, the ID of the message you are replying to was not sent to our server. Please try again later.');
    exit;
  }
  if(!$_POST[message]){
    #Fields not filled out
    error('You didn\'t enter a message to send to this user.');
    exit;
  }

  #Look up the message
  $result = mysql_query("SELECT * FROM hookups WHERE textid='$_POST[hookupid]'", $db);
  if(mysql_num_rows($result) < 1){
    error('For some reason, the ID of the message you are replying to was invalid. An administrator may have deleted the message.');
  }
  $myrow = mysql_fetch_array($result);




  #Look up the Sender and Recipient
  $result = mysql_query("SELECT * FROM users WHERE username='$myrow[sender]'", $db);
  if(mysql_num_rows($result) < 1){
    error('Sorry, but the sender of this message is no longer a user at our site. You cannot read or reply to this message.');
  }
  $senderrow = mysql_fetch_array($result);

  if($senderrow[hookup] != 1){
    error('Sorry, but the sender of this message is no longer accepting HookUp messages. You cannot read or reply to this message.');
  }
  $result = mysql_query("SELECT textid FROM pictures WHERE owner='$senderrow[username]'", $db);
  if(mysql_num_rows($result) < 1){
    error('Sorry, but the sender of this message is no longer a user at our site. You cannot read or reply to this message.');
  }
  $senderpicrow = mysql_fetch_array($result);
  $result = mysql_query("SELECT * FROM users WHERE username='$myrow[recipient]'", $db);
  if(mysql_num_rows($result) < 1){
    error('Sorry, but the recipient of this message is no longer a user at our site. You cannot read or reply to this message.');
  }
  $reciprow = mysql_fetch_array($result);
  $result = mysql_query("SELECT textid FROM pictures WHERE owner='$reciprow[username]'", $db);
  if(mysql_num_rows($result) < 1){
    error('Sorry, but the recipient of this message is no longer a user at our site. You cannot read or reply to this message.');
  }
  $recippicrow = mysql_fetch_array($result);

  #Drop the reply in the HookUps table
  $time = time();
  $message = strip_tags($_POST[message]);
  $textid = uniqid('') . '_' . rand(11111,99999);
  $result = mysql_query("INSERT INTO hookups SET sender='$reciprow[username]', recipient='$senderrow[username]', ipaddr='$_SERVER[REMOTE_ADDR]', date=$time, message='$message', textid='$textid'", $db);
  if(mysql_affected_rows($db) < 1){
    error('We\'re sorry, but there was a database error while attempting to store your HookUp reply. We apologize for the inconvenience, please try again later.');
  }

  #Send the message to the recipient
  $message = stripslashes($message);
  $mailbody = @implode('', file('templates/hookup_mail_reply.txt'));
  $mailbody = str_replace('%sender%', "$baseurl/vote.php?tag=$recippicrow[textid]", $mailbody);
  $mailbody = str_replace('%reply%', "$baseurl/hookup.php?act=read&tag=$textid", $mailbody);
  $mailbody = str_replace('%message%', wordwrap($message), $mailbody);
  mail($senderrow[email], $replyhookupsubject, $mailbody, "From: $hookupemail");

  generic('Congratulations, your HookUp message has been sent! The user will receive an anonymous email with the details, and if they choose to reply, you\'ll receive an email, too.');

  exit;
}

#Did someone post the initial Hookup form?
if($_POST[action] == 'GETHOOKUP'){
  if(!($_POST[username] && $_POST[password])){
    #Fields not filled out
    error('You entered an invalid username. If you do not have an account, you should make one before you can login.');
    exit;
  }

  if(!$_POST[recipient]){
    #Fields not filled out
    error('You didn\'t specify a recipient for your message.');
    exit;
  }

  #Is this a valid account?
  $result = mysql_query("SELECT * FROM users WHERE username='$_POST[username]'", $db);
  if(mysql_num_rows($result) < 1){
    #Invalid username
    error('You entered an invalid username. If you do not have an account, you should make one before you can login.');
    exit;
  }
  else{
    $myrow = mysql_fetch_array($result);
    $userrow = $myrow;
    if($myrow[active] == 0){
      #Banned email address
      error('DISABLED ACCOUNT. The administrator has banned this account from logging in.');
      exit;
    }
  }

  #Is the password valid?
  if(crypt($_POST[password], "fu") != $myrow[password]){
    error('INVALID PASSWORD. If you do not have an account, you should make one before you can login.');
    exit;
  }

  #Is the recipient accepting HookUps?
  $result = mysql_query("SELECT filename, url, score, numvotes, lastvote, owner FROM pictures WHERE textid='$_POST[recipient]' AND active=1");
  if(mysql_num_rows($result) < 1){
    #An invalid tag (textid) was provided.
    error('You specified an invalid user to get the HookUp with!');
    exit;
  }
  $myrow = mysql_fetch_array($result);
  $result = mysql_query("SELECT * FROM users WHERE username='$myrow[owner]'", $db);
  $reciprow = mysql_fetch_array($result);
  if($reciprow [hookup] == 0){
    error('The user you\'re trying to HookUp with isn\'t accepting HookUps!');
  }

  $result = mysql_query("SELECT textid FROM pictures WHERE owner='$userrow[username]'", $db);
  if(mysql_num_rows($result) < 1){
    error('NO IMAGE: You can\'t send a HookUp unless you have your own picture!');
    exit;
  }
  $senderrow = mysql_fetch_array($result);

  #Display the Send Message form
  $template = @implode('', @file('templates/hookup_send.html'));
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

  $template = str_replace('%bio%', $userrow[bio], $template);
  $template = str_replace('%top5%', top5(), $template);
  $template = str_replace('%tag%', $tag, $template);
  $template = str_replace('%linkurl%', "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]?tag=$tag", $template);
  $template = str_replace('%lastrate%', '', $template);
  $template = str_replace('%sendtag%', $senderrow[textid], $template);
  $template = str_replace('%reciptag%', $_POST[recipient], $template);
  $template = str_replace('%recipient%', $_POST[recipient], $template);
  $template = str_replace('%user%', $userrow[username], $template);
  $template = str_replace('%pass%', md5($userrow[password]), $template);

  echo $template;

  exit;
}

if($_POST[action] == 'SENDHOOKUP'){
  #A user is attempting to send an actual HookUp message
  if(!($_POST[username] && $_POST[password])){
    #Fields not filled out
    error('You entered an invalid username. If you do not have an account, you should make one before you can login.');
    exit;
  }

  if(!$_POST[recipient]){
    #Fields not filled out
    error('You didn\'t specify a recipient for your message.');
    exit;
  }

  if(!$_POST[message]){
    #Fields not filled out
    error('You didn\'t enter a message to send to this user.');
    exit;
  }

  #Is this a valid account?
  $result = mysql_query("SELECT * FROM users WHERE username='$_POST[username]'", $db);
  if(mysql_num_rows($result) < 1){
    #Invalid username
    error('You entered an invalid username. If you do not have an account, you should make one before you can login.');
    exit;
  }
  else{
    $myrow = mysql_fetch_array($result);
    $userrow = $myrow;
    if($myrow[active] == 0){
      #Banned email address
      error('DISABLED ACCOUNT. The administrator has banned this account from logging in.');
      exit;
    }
  }

  #Is the password valid?
  if($_POST[password] != md5($myrow[password])){
    error('INVALID PASSWORD. If you do not have an account, you should make one before you can login.');
    exit;
  }

  #Is the recipient accepting HookUps?
  $result = mysql_query("SELECT filename, url, score, numvotes, lastvote, owner FROM pictures WHERE textid='$_POST[recipient]' AND active=1");
  if(mysql_num_rows($result) < 1){
    #An invalid tag (textid) was provided.
    error('You specified an invalid user to get the HookUp with!');
    exit;
  }
  $myrow = mysql_fetch_array($result);
  $result = mysql_query("SELECT * FROM users WHERE username='$myrow[owner]'", $db);
  $reciprow = mysql_fetch_array($result);
  if($reciprow [hookup] == 0){
    error('The user you\'re trying to HookUp with isn\'t accepting HookUps!');
  }

  $result = mysql_query("SELECT textid FROM pictures WHERE owner='$userrow[username]'", $db);
  if(mysql_num_rows($result) < 1){
    error('NO IMAGE: You can\'t send a HookUp unless you have your own picture!');
    exit;
  }
  $senderrow = mysql_fetch_array($result);

  #Drop the message into the HookUps table
  $time = time();
  $message = strip_tags($_POST[message]);
  $textid = uniqid('') . '_' . rand(11111,99999);
  $result = mysql_query("INSERT INTO hookups SET sender='$userrow[username]', recipient='$reciprow[username]', ipaddr='$_SERVER[REMOTE_ADDR]', date=$time, message='$message', textid='$textid'", $db);
  if(mysql_affected_rows($db) < 1){
    error("We're sorry, but there was a database error while attempting to store your HookUp message. We apologize for the inconvenience, please try again later.");
  }

  #Send the message to the recipient
  $message = stripslashes($message);
  $mailbody = @implode('', file('templates/hookup_mail_new.txt'));
  $mailbody = str_replace('%sender%', "$baseurl/vote.php?tag=$senderrow[textid]", $mailbody);
  $mailbody = str_replace('%reply%', "$baseurl/hookup.php?act=read&tag=$textid", $mailbody);
  $mailbody = str_replace('%message%', wordwrap($message), $mailbody);
  mail($reciprow[email], $newhookupsubject, $mailbody, "From: $hookupemail");

  generic('Congratulations, your HookUp message has been sent! The user will receive an anonymous email with the details, and if they choose to reply, you\'ll receive an email, too.');

  exit;

}

#Otherwise we want to display a HookUp form
if(!$_GET[tag]){
  error("You didn't specify a user to get the HookUp with!");
}
else{
  #A tag was provided, look up that record
  $result = mysql_query("SELECT filename, url, score, numvotes, lastvote, owner FROM pictures WHERE textid='$_GET[tag]' AND active=1");
}
if(mysql_num_rows($result) < 1){
  #An invalid tag (textid) was provided.
  error('You specified an invalid user to get the HookUp with!');
  exit;
}
$myrow = mysql_fetch_array($result);
$result = mysql_query("SELECT * FROM users WHERE username='$myrow[owner]'", $db);
$userrow = mysql_fetch_array($result);
if($userrow[hookup] == 0){
  error('The user you\'re trying to HookUp with isn\'t accepting HookUps!');
}

$tag = ($_GET[tag]) ? $_GET[tag] : $myrow[textid];

#Load the hookup page template
$template = @implode('', @file('templates/hookup.html'));
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

$template = str_replace('%bio%', $userrow[bio], $template);
$template = str_replace('%top5%', top5(), $template);
$template = str_replace('%tag%', $tag, $template);
$template = str_replace('%linkurl%', "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]?tag=$tag", $template);

#Display the page
echo $template;

function error($msg){
  #Load the error template
  $template = @implode('', @file('templates/error.html'));
  
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
  $template = str_replace('%error%', $msg, $template);
  echo $template;
  exit;
}
function generic($msg){
  #Load the error template
  $template = @implode('', @file('templates/generic.html'));
  
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
  $template = str_replace('%message%', $msg, $template);
  echo $template;
  exit;
}

?>