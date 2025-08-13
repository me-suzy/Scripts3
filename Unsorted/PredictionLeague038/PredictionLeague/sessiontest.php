<?php
///////////////////////////////////////////////////////////////////
//Author: John Astill (c) 2002
//Desc  : Write a values for the User session data
//      : and test that it still has data in the next page.
///////////////////////////////////////////////////////////////////
  require "SystemVars.php";
  require "SortFunctions.php";

  session_start();
  session_register("User");

  $User = new User();
  $User->loggedIn = FALSE;
  $User->usertype = 1;
  $User->userid = "TestSession";
  $User->emailaddr = "support@predictionfootball.com";

  forward("sessiontestresults.php");
?>
