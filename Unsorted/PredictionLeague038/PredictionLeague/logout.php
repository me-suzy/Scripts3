<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : logout.php
 * Desc  : Log the current user out. Removes any
 *       : session info.
 ********************************************************/
  include "SystemVars.php";

  // Logout erases the session info
  session_set_cookie_params(60*60*24*7,"/$baseDirName/");
  session_start();

  $User = 0;

  session_unregister("User");
  session_unset();
  session_destroy();

  // Delete the session cookie
  setcookie(session_name());

  /* Redirect browser to PHP web site */
  header("Location: PredictionIndex.php"); 
  /* Make sure that code below does not get executed when we redirect. */
  exit; 
?>
