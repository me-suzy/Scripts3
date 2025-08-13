<?php
#
# FILE: user.php
# DATE: Updated 05/31/03, Orig 01/10/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Handles new user signup and image uploading, as well as
# new user logins to check their rating/stats. Your "Submit Picture" link
# must pass this script a parameter named "func" with value "newuser", e.g.
#
# <a href="user.php?func=newuser">Upload Your Pic</a>
#

require_once('common.php');

#Should we display the signup page?
if($_GET[func] == 'newuser'){
  #Load the signup template
  $template = @implode('', @file('templates/newuser.html'));

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
  $template = str_replace('%top5%', "top5()", $template);
  $template = str_replace('%username%', "$_GET[username]", $template);
  $template = str_replace('%email%', "$_GET[email]", $template);
  $template = str_replace('%errormessage%', "$_GET[error]", $template);

  #Display the signup page
  echo $template;
  exit;
}

#Should we try to create a new account?
if($_POST[action] == 'createaccount'){
  if(!($_POST[username] && $_POST[email] && $_POST[password1] && $_POST[password2])){
    #Required fields missing
    error("You did not fill out all of the fields!");
    exit;
  }

  #Check for valid email address
  if(!eregi("^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$", $_POST[email])){
    #Invalid email address
    error('Invalid email address.');
    exit;
  }
 
  #Make sure the email address is not banned
  $result = mysql_query("SELECT * FROM bannedemails WHERE email='$_POST[email]'", $db);
  if(mysql_num_rows($result) > 0){
    #Banned email address
    error('That email address has been banned from signing up.');
    exit;
  }

  #Make sure the IP address is not banned
  $result = mysql_query("SELECT * FROM bannedips WHERE ipaddr='$_SERVER[REMOTE_ADDR]'", $db);
  if(mysql_num_rows($result) > 0){
    #Banned IP address
    error('Your IP address has been banned from signing up.');
    exit;
  }

  #Check for shady characters in the username
  if(ereg("[ \#&\.!]", $_POST[username])){
    #Invalid username
    error('Username cannot contain spaces or punctuation, please stick to letters and numbers only.');
    exit;
  }
  
  #Check for unique username
  $result = mysql_query("SELECT username FROM users WHERE username='$_POST[username]'", $db);
  if(mysql_num_rows($result) > 0){
    #Username already exists
    error('Sorry, but someone else has already taken that username. Please pick another one.');
    exit;
  }

  #Check for matching passwords
  if($_POST[password1] != $_POST[password2]){  
    #Passwords didn't match
    error('The passwords you entered did not match.');
    exit;
  }

  #Check for an image file
  if(is_uploaded_file($_FILES["newimage"]["tmp_name"]) && ($_FILES["newimage"]["size"] > 0)){
    #Was this a graphics file or something bogus?
    $extension = substr($_FILES["newimage"]["name"], -3);
    if(strcasecmp($extension, "jpg") != 0){
      #Not a JPG file
      error('We accept JPG image files only. It looks like the file you uploaded was not a JPG image.');
      exit;
    }
    else{
      #File looks good, copy it to the image root
      $unique = uniqid('');
      $newname = "img$unique";
      $newpath = "$imagedir/$newname.jpg";
      move_uploaded_file($_FILES["newimage"]["tmp_name"], $newpath);
    }
  }
  else if(!$_FILES["newimage"]["name"])
    $err = "Upload error, it appears that you did not select a file to upload.";
  else
    $err = "Upload error, please try again with a smaller file.";

  if($err){
    #Upload problem
    error($err);
    exit;
  }

  #Strip any HTML from the user's bio and chop it to 1KB max
  $bio = substr(strip_tags($_POST[bio]), 0, 1024);

  #Determine this user's HookUp preference
  $hookup = ($_POST[hookup] == 1) ? 1 : 0;

  #Now add this user's account
  $time = time();
  $password = crypt($_POST[password1], 'fu');
  $result = mysql_query("INSERT INTO users SET username='$_POST[username]', password='$password', bio='$bio', hookup=$hookup, email='$_POST[email]', created=$time, lastip='$_SERVER[REMOTE_ADDR]'", $db);
  $result = mysql_query("INSERT INTO pictures SET owner='$_POST[username]', filename='$newname.jpg', textid='$unique', adddate=$time, score=5.0, numvotes=0, sumvotes=0, lastvote=$time", $db);

  #Load the user page template
  $template = @implode('', @file('templates/userpage.html'));

  #Make needed calculations
  $result = mysql_query("SELECT * FROM pictures WHERE owner='$_POST[username]'", $db);
  $myrow = mysql_fetch_array($result);
  $result = mysql_query("SELECT COUNT(id) FROM pictures WHERE active=1", $db);
  $statrow = mysql_fetch_array($result);
  $totalusers = $statrow[0]; 
  $result = mysql_query("SELECT COUNT(id) FROM pictures WHERE active=1 AND score < 5", $db);
  $statrow = mysql_fetch_array($result);
  $lowerusers = $statrow[0];
  $lowerrate = sprintf("%.01f", 100 * ($lowerusers / $totalusers));

  #Load the "your rank" page template
  $yourrank = @implode('', @file('templates/yourrank.html'));
  $yourrank = str_replace('%imageurl%', $imageurl, $yourrank);
  $yourrank = str_replace('%percentile%', ($myrow[score] * 10), $yourrank);

  #Replace tokens
  $template = str_replace('%score%', sprintf("%.01f", $myrow[score]), $template);
  $template = str_replace('%numvotes%', $myrow[numvotes], $template);
  $template = str_replace('%lowerrate%', $lowerrate, $template);
  $template = str_replace('%welcomemessage%', '', $template);
  $template = str_replace('%tag%', $myrow[textid], $template);
  $template = str_replace('%header%', getheader(), $template);
  $template = str_replace('%footer%', getfooter(), $template);
  if($hookup){
    $template = str_replace('id="hookup"', 'id="hookup" CHECKED', $template);
  }
  $template = str_replace('%email%', "$_POST[email]", $template);
  $template = str_replace('%bio%', stripslashes($bio), $template);
  $template = str_replace('%user%', $_POST[username], $template);
  $template = str_replace('%pass%', md5($password), $template);
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
  $template = str_replace('%username%', $_POST[username], $template);
  $template = str_replace('%yourrank%', $yourrank, $template);
  $template = str_replace('%linkurl%', "http://$_SERVER[HTTP_HOST]" . str_replace("user.php", "vote.php", $_SERVER[SCRIPT_NAME]) . "?tag=$myrow[textid]", $template);

  #Display the user page
  echo $template;

  exit;
}

#Is a user trying to update their profile settings?
if($_POST[action] == 'UPDATE'){
  if(!($_POST[username] && $_POST[password])){
    #Fields not filled out
    error('Invalid username. If you do not have an account, you must create one before logging in.');
    exit;
  }

  #Is this a valid account?
  $result = mysql_query("SELECT * FROM users WHERE username='$_POST[username]'", $db);
  if(mysql_num_rows($result) < 1){
    #Invalid username
    error('Invalid username. If you do not have an account, you must create one before logging in.');
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

  #Does the password match?
  if($_POST[password] != md5($myrow[password])){
    error('Invalid password');
    exit;
  }

  #Strip any HTML from the user's bio and chop it to 1KB max
  $bio = substr(strip_tags($_POST[bio]), 0, 1024);

  #Determine this user's HookUp preference
  $hookup = ($_POST[hookup] == 1) ? 1 : 0;

  #Make the database updates
  $result = mysql_query("UPDATE users SET bio='$bio', hookup=$hookup, email='$_POST[email]' WHERE username='$_POST[username]'", $db);

  #Load the user page template
  $template = @implode('', @file('templates/userpage.html'));

  #Make needed calculations
  $result = mysql_query("SELECT * FROM users WHERE username='$_POST[username]'", $db);
  $userrow = mysql_fetch_array($result);
  $result = mysql_query("SELECT * FROM pictures WHERE owner='$_POST[username]'", $db);
  $myrow = mysql_fetch_array($result);
  $result = mysql_query("SELECT COUNT(id) FROM pictures WHERE active=1", $db);
  $statrow = mysql_fetch_array($result);
  $totalusers = $statrow[0]; 
  $result = mysql_query("SELECT COUNT(id) FROM pictures WHERE active=1 AND score < $myrow[score]", $db);
  $statrow = mysql_fetch_array($result);
  $lowerusers = $statrow[0];
  $lowerrate = sprintf("%.01f", 100 * ($lowerusers / $totalusers));

  #Load the "your rank" page template
  $yourrank = @implode('', @file('templates/yourrank.html'));
  $yourrank = str_replace('%imageurl%', $imageurl, $yourrank);
  $yourrank = str_replace('%percentile%', ($myrow[score] * 10), $yourrank);

  #Replace tokens
  $template = str_replace('%score%', sprintf("%.01f", $myrow[score]), $template);
  $template = str_replace('%numvotes%', $myrow[numvotes], $template);
  $template = str_replace('%lowerrate%', $lowerrate, $template);
  $template = str_replace('%welcomemessage%', '', $template);
  $template = str_replace('%tag%', $myrow[textid], $template);
  $template = str_replace('%header%', getheader(), $template);
  $template = str_replace('%footer%', getfooter(), $template);
  if($userrow[hookup]){
    $template = str_replace('id="hookup"', 'id="hookup" CHECKED', $template);
  }
  $template = str_replace('%email%', "$userrow[email]", $template);
  $template = str_replace('%bio%', stripslashes($userrow[bio]), $template);
  $template = str_replace('%user%', $userrow[username], $template);
  $template = str_replace('%pass%', md5($userrow[password]), $template);
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
  $template = str_replace('%username%', $_POST[username], $template);
  $template = str_replace('%yourrank%', $yourrank, $template);
  $template = str_replace('%linkurl%', "http://$_SERVER[HTTP_HOST]" . str_replace("user.php", "vote.php", $_SERVER[SCRIPT_NAME]) . "?tag=$myrow[textid]", $template);

  #Display the user page
  echo $template;

  exit;
}

#Is a user trying to login?
if($_POST[action] == 'USERLOGIN'){
  if(!($_POST[username] && $_POST[password])){
    #Fields not filled out
    error('Invalid username. If you do not have an account, you must create one before logging in.');
    exit;
  }

  #Is this a valid account?
  $result = mysql_query("SELECT * FROM users WHERE username='$_POST[username]'", $db);
  if(mysql_num_rows($result) < 1){
    #Invalid username
    error('Invalid username. If you do not have an account, you must create one before logging in.');
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
    error('Invalid Password.');
    exit;
  }

  #Load the user page template
  $template = @implode('', @file('templates/userpage.html'));

  #Make needed calculations
  $result = mysql_query("SELECT * FROM pictures WHERE owner='$_POST[username]'", $db);
  $myrow = mysql_fetch_array($result);
  $result = mysql_query("SELECT COUNT(id) FROM pictures WHERE active=1", $db);
  $statrow = mysql_fetch_array($result);
  $totalusers = $statrow[0]; 
  $result = mysql_query("SELECT COUNT(id) FROM pictures WHERE active=1 AND score < $myrow[score]", $db);
  $statrow = mysql_fetch_array($result);
  $lowerusers = $statrow[0];
  $lowerrate = sprintf("%.01f", 100 * ($lowerusers / $totalusers));

  #Load the "your rank" page template
  $yourrank = @implode('', @file('templates/yourrank.html'));
  $yourrank = str_replace("%imageurl%", $imageurl, $yourrank);
  $yourrank = str_replace("%percentile%", ($myrow[score] * 10), $yourrank);

  #Replace tokens
  $template = str_replace("%score%", sprintf("%.01f", $myrow[score]), $template);
  $template = str_replace("%numvotes%", $myrow[numvotes], $template);
  $template = str_replace("%lowerrate%", $lowerrate, $template);
  $template = str_replace("%welcomemessage%", "", $template);
  $template = str_replace("%tag%", $myrow[textid], $template);
  $template = str_replace("%header%", getheader(), $template);
  $template = str_replace("%footer%", getfooter(), $template);
  if($userrow[hookup]){
    $template = str_replace('id="hookup"', 'id="hookup" CHECKED', $template);
  }
  $template = str_replace('%email%', "$userrow[email]", $template);
  $template = str_replace('%bio%', stripslashes($userrow[bio]), $template);
  $template = str_replace('%user%', $userrow[username], $template);
  $template = str_replace('%pass%', md5($userrow[password]), $template);
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
  $template = str_replace('%username%', $_POST[username], $template);
  $template = str_replace('%yourrank%', $yourrank, $template);
  $template = str_replace('%linkurl%', "http://$_SERVER[HTTP_HOST]" . str_replace("user.php", "vote.php", $_SERVER[SCRIPT_NAME]) . "?tag=$myrow[textid]", $template);

  #Display the user page
  echo $template;
}


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


?>