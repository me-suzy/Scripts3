<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : SelectIcon.php
 * Desc  : Selects the icon the user wants.
 ********************************************************/
require "SystemVars.php";
require "dbaseFunctions.php";
require "security.php";

// Create the session and save the user data.
session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

$icon = "icons/".$HTTP_GET_VARS["icon"];
// Connect to the host.
$link = OpenConnection();

$query = "UPDATE $dbaseUserData set icon=\"$icon\" where username=\"$User->userid\"";
$result = mysql_query($query)
  or die("Query failed: $query");

CloseConnection($link);

$User->icon = $icon;

/* Redirect browser to PHP web site */
header("Location: UserSelectIcon.php"); 
/* Make sure that code below does not get executed when we redirect. */
exit; 
?>
