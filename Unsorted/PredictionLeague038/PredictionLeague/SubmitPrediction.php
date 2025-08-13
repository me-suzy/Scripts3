<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : SubmitPrediction.php
 * Desc  : 
 *       : 
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
require "dbaseFunctions.php";
require "security.php";

session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

function SubmitPrediction() {
  global $HTTP_POST_VARS;
  global $dbasePredictionData;

  $user = $HTTP_POST_VARS["USER"];
  $date = $HTTP_POST_VARS["DATE"];
  $hometeam = $HTTP_POST_VARS["HOMETEAM"];
  $awayteam = $HTTP_POST_VARS["AWAYTEAM"];
  $homescore = $HTTP_POST_VARS["HOMESCORE"];
  $awayscore = $HTTP_POST_VARS["AWAYSCORE"];

  // Check the date. Make sure the URL isn't spoofed,
  if (CompareDatetime($date) < 0) {
    // Spoofed or delayed prediction. 
  } else {
    $link = OpenConnection();
    if ($link == FALSE) {
      echo "Unable to connect to the dbase";
      return;
    }
    $query = "REPLACE INTO $dbasePredictionData SET username = \"$user\", matchdate=\"$date\", hometeam=\"$hometeam\", awayteam=\"$awayteam\", homescore=\"$homescore\", awayscore=\"$awayscore\"";
    $result = mysql_query($query)
      or die("Query failed: $query");

    CloseConnection($link);
  }
  GetUserPredictions($user);
}
?>

<html>
<head>
<title>
<?php echo "Results\n"?>
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
<td valign="top">
<?php include "menus.php"?>
</td>
<td valign="top">
<table width="500">
<?php
  /*******************************************************
  * Submit the results.
  *******************************************************/
  SubmitPrediction();
?>
</table>
</td>
  <!-- Right Column -->
  <td class="RIGHTCOL" align="RIGHT">
    <!-- Show the login panel if required -->
    <?php require "LoginPanel.php" ?>

    <!-- Show the Prediction stats for the next match -->
    <?php ShowPredictionStatsForNextMatch(); ?>
    
    <!-- Competition Prize -->
    <?php require "Prize.html"?>
  </td>
</tr>
</table>

</body>
</html>
