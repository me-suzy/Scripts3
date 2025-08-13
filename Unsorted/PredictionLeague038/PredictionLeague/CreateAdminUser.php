<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 10th May 2002
 * File  : CreateAdminUser.php
 * Desc  : Create the required tables entries for a new user.
 ********************************************************/
require "SystemVars.php";
require "dbaseFunctions.php";
require "security.php";

// Create the session and save the user data.
session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();
session_register("User");
session_register("ErrorCode");

$username = $HTTP_POST_VARS["USER"];
$password = $HTTP_POST_VARS["PASSWORD"];
$email = $adminEmailAddr;
$icon = $defaulticon;
$encryptpass = $password;

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

/* Entry Point */
if (TRUE == doesUserExist($username)) {
  ErrorRedir("User $username already exists, please choose another name","CreateNewUser.php");
}

// Make sure there is a username
if ($username == "") {
  ErrorRedir("Username required, please choose a name","CreateNewUser.php");
}

// Make sure there is a password
if ($password == "") {
  ErrorRedir("Password required, please choose a name","CreateNewUser.php");
}

// Connect to the host.
$link = OpenConnection();

$todaysDate = date("Y-m-d");
$query = "INSERT INTO $dbaseUserData (username,password,email,icon,usertype,since) values (\"$username\",\"$encryptpass\",\"$email\",\"$icon\",\"4\",\"$todaysDate\")";
$result = mysql_query($query)
  or die("Query failed: $query");

CloseConnection($link);

// Email the administrator the new user
$text = "New User created.\nUser = $username\nPassword = $password\nEmail = $email\nIcon = $icon\nSent to $adminEmailAddr\nVersion = ".VERSION;
mail($adminEmailAddr, "$PredictionLeagueTitle New User",$text,"From: $email");
$text = "Hi $username,\n\nWelcome the the $PredictionLeagueTitle. Thank you for joining us.\n\nPlease check your details are correct. If you need to change anything please log into the Prediction League and modify your profile.\n\nPassword = $password\nEmail = $email.\n\nGood Luck\n\n$PLHome";
mail($email, "Welcome to the $PredictionLeagueTitle",$text,"From: $email");

// Now that the user has been created, log them in.
login($username,$password);
?>
