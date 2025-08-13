<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : ShowMyPredictions.php
 * Desc  : Display the predictions for the currently logged
 *       : in user. If the cookie does not exist for the
 *       : user,show them a login page.
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
require "dbaseFunctions.php";
require "security.php";
require "LogFunctions.php";

$user = $HTTP_GET_VARS["user"];

echo "<!-- Dumping sessin data -->\n";
while (list($key,$val) = each($_SESSION)) {
  echo "<!-- $key : $val -->\n";
}


// Access the session data.
session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

$userid = $User->userid;

// Make sure that the session data is registered.
if (!session_is_registered("User")) {
  // Problem time, the session isn't registering.
  $errmsg = "Unable to get $user session variable User in ShowMyPredictions.php. session_name=".session_name()." sessionid=".session_id()."\n";
  LogMsg($errmsg);

  // Let me know there is a problem
  mail("john@predictionfootball.com",$PredictionLeagueTitle." Error",$errmsg);

  // Go back to login page
  //header("Location: PredictionIndex.php"); 
  $userid = $user;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      <?php echo "My Predictions for $userid\n"?>
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>

  <body class="MAIN">
    <table class="MAINTB" width="800">
      <tr>
        <td colspan="3" align="center">
          <!-- Header Row -->
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
        <td valign="top" class="LEFTCOL">
          <?php include "menus.php"?>
        </td>
        <td valign="top" class="CENTERCOL">
          <table width="500">
            <?php
              GetUserPredictions($User->userid);
            ?>
          </table>
        </td>
        <td valign="top" class="RIGHTCOL">
          <!-- Show the Prediction stats for the next match -->
          <?php ShowPredictionStatsForNextMatch(); ?>
          
          <!-- Competition Prize -->
          <?php require "Prize.html"?>
        </td>
      </tr>
    </table>
  </body>
</html>


