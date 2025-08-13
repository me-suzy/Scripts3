<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 31st December
 * File  : ChangePassword.php
 * Desc  : Change the users given password.
 ********************************************************/
require "SystemVars.php";
require "dbaseFunctions.php";
require "security.php";

session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

 $user = $HTTP_POST_VARS["USERID"];
 $password = $HTTP_POST_VARS["PWD1"];


 $link = OpenConnection();

 $query = "UPDATE $dbaseUserData set password=\"$password\" where username=\"$user\"";
 
 $result = mysql_query($query)
   or die("Query failed: $query");

 CloseConnection($link);

 if ($result == TRUE) {
   $User->pwd = $password;
   // The password has changed, update the stats info in the session
   // data and forward to the PredictionIndex.
   header("Location: PredictionIndex.php"); 
   /* Make sure that code below does not get executed when we redirect. */
   exit; 
 } else {
   // Go back to sending page.
   header("Location: ShowMyProfile.php"); 
   /* Make sure that code below does not get executed when we redirect. */
   exit; 
 }
?>
