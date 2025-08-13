<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 19th January 2002
 * File  : HelpIndex.php
 ********************************************************/
  require "SystemVars.php";
  require "SortFunctions.php";
  require "dbaseFunctions.php";
  require "security.php";

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
      Prediction League Help
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
        <td class="CENTERCOL">
          <!-- Central Column -->
          <table>
          <!-- Creating a new user -->
          <tr>
          <td align="CENTER" class="TBLHEAD">
          <font class="HELPHEAD">
          <a name="NEWUSER">
          Creating a new user
          </font>
          </td>
          </tr>
          <tr>
          <td align="LEFT" class="TBLROW">
          <font class="HELPROW">
          To be able to participate in the Prediction League you need to create a user profile. This is created by selecting the <b>New User</b> link from the right hand column. Please make sure that cookies are enabled for your browser. If cookies are disabled, you will be forwarded to this page.
          <br> <br>
          Select a User ID to identify yourself in the prediction league. This can be anything you like as long as it is not already taken by another user.
          <br> <br>
          Enter a password (twice). This is to ensure no-one else can change your predictions.
          <br> <br>
          You <b>must</b> enter a valid email address to join the prediction league. This is used to have your password mailed to should you forget it.
          </font>
          </td>
          </tr>
          <!-- End Creating a new user -->
          <!-- Cookies -->
          <tr>
          <td align="CENTER" class="TBLHEAD">
          <font class="HELPHEAD">
          <a name="COOKIES">
          Cookies
          </font>
          </td>
          </tr>
          <tr>
          <td align="LEFT" class="TBLROW">
          <font class="HELPROW">
          Cookies <b>must</b> be enabled to be able to use the Prediction League.
          <br><br>
          Common problems encountered when not using cookies include:
          <ul>
          <li>Seeing this page when trying to log in.
          <li>Being asked for a User when trying to enter a prediction.
          <li>Icon not showing in left of screen.
          <li>You tried to create a new user and were redirected to this page. You need to enable cookies on your browser.
          </ul>
          To enable cookies in Internet Explorer 6, follow the menu path Tools->Internet Options. Select the Privacy tab. Set the privacy setting to Medium or lower. Press OK. Some users have experienced problems with the medium setting. You may need to go lower.<br>
          To enable cookies in Netscape 6, follow the menu path Edit->Preferences. Select the Privacy &amp; tab, then select cookies. Select Enable Cookies for the originating site only, or enable all cookies. Press OK.<br>

          </font>
          </td>
          </tr>
          <!-- End Cookies -->
          <!-- Scoring -->
          <tr>
          <td align="CENTER" class="TBLHEAD">
          <font class="HELPHEAD">
          <a name="SCORING">
          Scoring
          </font>
          </td>
          </tr>
          <tr>
          <td align="LEFT" class="TBLROW">
          <font class="HELPROW">
          Points are awared as follows:
          <ul>
          <li>3 Points for a correct score. i.e. you predict 3-1 and the result is 3-1.
          <li>1 Point for a correct result. You predict 1-1, the score is 2-2.
          </ul>
          Goal Difference
          <ul>
          <li>For each correct teams number of goals you score, you receive that number of +goals. e.g. you predicit 2 and the team scores 2, you get +2 goals.
          <li>For each incorrect number of goals you predict per team, you lose the difference in goals. e.g. you predict 3 and the team scores 2, you get -1 goals.
          </ul>
          </font>
          </td>
          </tr>
          <!-- End Scoring -->
          </table>
          
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

