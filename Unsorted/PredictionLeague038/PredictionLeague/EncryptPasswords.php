<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th October 2002
 * File  : EncryptPasswords.php
 * Desc  : Traverse the table encrypting all the passwords.
 ********************************************************/
  require "SystemVars.php";
  require "dbaseFunctions.php";
  require "LogFunctions.php";
  require "SortFunctions.php";
  require "security.php";

  // Create the session and save the user data.
  session_set_cookie_params(60*60*24*7,"/$baseDirName/");
  session_start();

  // Make sure the user trying to perform the delete is 
  // an admin or above.
  if (!CheckAdmin($User->usertype)) {
    // Forward to somewhere
    LogMsg("Attempt to encrypt passwords by User $User->userid with insufficient permissions from $REMOTE_ADDR:$REMOTE_PORT.");
    forward("PredictionIndex.php");
  }

  // Connect to the host.
  $link = OpenConnection();
  if ($link == FALSE) {
    echo "Unable to connect to dbase.";
    return;
  }

  $query = "SELECT username, password FROM $dbaseUserData";
  $result = mysql_query($query , $link)
              or die ("Unable to query passwords: $query");

  if ($result == FALSE) {
    echo "Unable to query the dbase with : $query.";
    return;
  }

  $numrows = mysql_num_rows($result);
  if ($numrows <= 0) {
    echo "Unable to query the dbase with : $query.";
    return;
  }

  while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $username = $line["username"];
    $password = $line["password"];
    $ep = md5($password);

    $query = "update $dbaseUserData set password=\"$ep\" where username=\"$username\"";
    $updresult = mysql_query($query)
               or die("Unable to set encrypted password for $username : $query");
               //echo "Set $username to $ep from $password<br>";
  }
  CloseConnection($link);
  
  forward("PredictionIndex.php");
?>
