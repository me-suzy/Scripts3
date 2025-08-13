<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : login.php
 * Desc  : Create the current users session data.
 ********************************************************/
  require "SystemVars.php";
  require "dbaseFunctions.php";
  require "security.php";
  require "LogFunctions.php";

  /*******************************************
  * Validate the users login. If it is 
  * correct log them in and forward to the
  * Main Page.
  *******************************************/
  $userid = $HTTP_POST_VARS["LOGIN"];
  $pwd = $HTTP_POST_VARS["PWD"];

  /*******************************************
  * Check the cookie value.
  * If this cookie does not exist, then it is
  * likely that the user does not have cookies
  * enabled.
  *******************************************/
  if ($HTTP_COOKIE_VARS["CookieTest"] != "Prediction") {
    LogMsg("Not able to read test cookie when login $userid");

    // Offer the user the option of logging in, but warn that they 
    // appear to have cookies disabled, and this may cause problems.
    // TODO
    // Forward to the help page
    header("Location: HelpIndex.php#COOKIES"); 
  } else {
    login($userid, $pwd);
  }
?>
