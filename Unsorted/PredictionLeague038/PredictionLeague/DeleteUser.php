<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : DeleteUser.php
 * Desc  : Delete the given user from the user table
 *       : and predictions from the prediction table.
 ********************************************************/
require "SystemVars.php";
require "dbaseFunctions.php";
require "LogFunctions.php";
require "SortFunctions.php";
require "security.php";

// Create the session and save the user data.
session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

$username = $HTTP_GET_VARS["user"];

// Make sure the user trying to perform the delete is 
// an admin or above.
if (!CheckAdmin($User->usertype)) {
  // Forward to somewhere
  LogMsg("Attempt to remove user $username by User $User->userid with insufficient permissions from $REMOTE_ADDR:$REMOTE_PORT.");
  forward("PredictionIndex.php");
}

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
if (FALSE == doesUserExist($username)) {
  ErrorRedir("User $username does not exist","ShowUsers.php");
}

// Make sure there is a username
if ($username == "") {
  ErrorRedir("Username required, please choose a name","CreateNewUser.php");
}

// Connect to the host.
$link = OpenConnection();

// Delete from the UserData table
$query = "DELETE FROM $dbaseUserData where username=\"$username\"";
$result = mysql_query($query)
  or die("Query failed: $query");

// Delete from the PredictionData table
$query = "DELETE FROM $dbasePredictionData where username=\"$username\"";
$result = mysql_query($query)
  or die("Query failed: $query");

// Close the connection to the database
CloseConnection($link);

// Email the administrator the new user
$text = "User deleted.\nUser = $username\nPassword = $password\nEmail = $email\nIcon = $icon\nSent to $adminEmailAddr.\nVersion = ".VERSION;
@mail($adminEmailAddr, "$PredictionLeagueTitle New User",$text,"From: $email");

// Log the action
LogMsg($text);

// Go back to show users page.
forward("ShowUsers.php");
?>
