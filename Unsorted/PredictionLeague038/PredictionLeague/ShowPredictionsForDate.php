<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : ShowPredictionsForDate.php
 * Desc  : Display the predictions for the given date.
 *       : the GET parameters date contains the date.
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
require "dbaseFunctions.php";
require "security.php";

session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>
<?php
  $date = $HTTP_GET_VARS["date"];
  $home = $HTTP_GET_VARS["home"];
  $away = $HTTP_GET_VARS["away"];
  echo "Predictions for $date\n";
?>
</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>

<body class="MAIN">

<table width="800">
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
<?php require "LoginPanel.php"?>
<?php include "menus.php"?>
</td>
<td class="CENTERCOL">
<?php
  GetPredictionsForDate($date, $home, $away);
?>
</td>
<td class="RIGHTCOL">

<!-- Show the Prediction stats for the next match -->
<?php 
  ShowPredictionStatsForMatch($date, $home, $away); 
?>

<!-- Competition Prize -->
<?php require "Prize.html"?>
</td>
</tr>
</table>

</body>
</html>


