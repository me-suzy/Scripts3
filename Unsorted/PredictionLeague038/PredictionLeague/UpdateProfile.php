<?php
/*********************************************************
 * Author: John Astill
 * Date  : 23rd January 2002
 * File  : UpdateProfile.php
 * Desc  : Update the users profile information.
 ********************************************************/
require "SystemVars.php";
require "dbaseFunctions.php";
require "security.php";

// Create the session and save the user data.
session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

$email = $HTTP_POST_VARS["EMAIL"];
$user = $HTTP_POST_VARS["USER"];

/**
 * Determine if the given user exists.
 * @param user the user to look for.
 */
function doesUserExist($user) {
  global $dbaseUserData;

  $link = OpenConnection();
  if ($link == FALSE) {
    ErrorNotify("Unable to open connection");
    exit;
  }

  $query = "SELECT username from $dbaseUserData where username=\"$user\"";
  $result = mysql_query($query)
    or die("Unable to perform query: $query");

  if ($result == FALSE) {
    ErrorNotify("Query Failed : $query");
    CloseConnection($link);
    return TRUE;
  }
  
  // If we get >0 rows, the username already exists.
  $numrows = mysql_num_rows($result);

  CloseConnection($link);

  if ($numrows == 0) {
    return FALSE;
  }

  return TRUE;
} 

if (FALSE == doesUserExist($user)) {
  ErrorRedir("User $user does not exist","ShowMyProfile.php");
}

// Make sure there is a username
if ($user == "") {
  ErrorRedir("Username required, please choose a name","ShowMyProfile.php");
}

// Connect to the host.
$link = OpenConnection();
if ($link == FALSE) {
  ErrorRedir("Unable to open connection","ShowMyProfile.php");
}

$query = "update $dbaseUserData set email=\"$email\" where username=\"$user\"";
$result = mysql_query($query)
  or die("Query failed: $query");

$User->emailaddr = $email;

CloseConnection($link);

// Return to the create user page, but set an error message.
// As REFERER will not be set by all browsers, return to the 
// page that should have sent us here.
header("Location: ShowMyProfile.php"); 
/* Make sure that code below does not get executed when we redirect. */
exit; 
?>
