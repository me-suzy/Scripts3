<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 9th December
 * File  : PredictionIndex.php
 ********************************************************/
  require "SystemVars.php";
  require "SortFunctions.php";
  require "dbaseFunctions.php";
  require "LogFunctions.php";
  require "security.php";

  /******************************************************
  * Attempt to set a cookie. If the user wants to create
  * a login, the cookie will be read. If it fails, then
  * we cannot use sessions.
  ******************************************************/
  if (!setcookie("CookieTest","Prediction",time()+7200, "/$baseDirName/")) {
    LogMsg("Unable to set cookie test cookie");
  }

  /*******************************************************
  * Check the user id and password
  *******************************************************/
  session_set_cookie_params(60*60*24*7,"/$baseDirName/");
  session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
    <?php echo $PredictionLeagueTitle ?>
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>
  <body class="MAIN">
    <table class="MAINTB" border="0">
      <!-- Top banner, will include news -->
      <tr>
        <td colspan="3" align="center">
          <?php echo $HeaderRow ?>
        </td>
      </tr>
      <!-- Display the next game -->
      <tr>
        <td colspan="3" align="center" class="TBLROW">
          <font class="TBLROW">
            <?php echo getNextGame() ?>
          </font>
        </td>
      </tr>
      <tr>
        <!-- Left column -->
        <td class="LEFTCOL">
          <!-- Show the login panel if required -->
          <?php require "LoginPanel.php" ?>

          <!-- Menu -->
          <?php require "menus.php"?>
          <!-- End Menu -->
        </td>
        <!-- Central Column -->
        <td align="LEFT" class="CENTERCOL">
          <!-- Central Column -->
          <?php
            // It looks like the functions cannot access global type data.
            ShowTablesBasedOnScore();
          ?>
        </td>
        <!-- Right Column -->
        <td class="RIGHTCOL" align="RIGHT">
          <!-- Show the Prediction stats for the next match -->
          <?php ShowPredictionStatsForNextMatch(); ?>
          
          <!-- Competition Prize -->
          <?php require "Prize.html"?>
        </td>
      </tr>
    </table>
  </body>
</html>

