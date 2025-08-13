<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : results.php
 * Desc  : Display the results for all the games.
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
require "dbaseFunctions.php";
require "security.php";

// Access the session data.
session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>
<?php echo "Results\n"?>
</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>

<body class="MAIN">
  <table class="MAINTB">
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
      <td class="LEFTCOL">
        <?php require "LoginPanel.php"; ?>
        <?php include "menus.php"; ?>
      </td>
      <td class="CENTERCOL">
        <?php
          // Get a display of the current results.
          GetResults();
        ?>
      </td>
      <td class="RIGHTCOL">
        <!-- Show the Prediction stats for the next match -->
        <?php ShowPredictionStatsForNextMatch(); ?>
        
        <!-- Competition Prize -->
        <?php require "Prize.html" ?>
      </td>
    </tr>
  </table>

</body>
</html>


