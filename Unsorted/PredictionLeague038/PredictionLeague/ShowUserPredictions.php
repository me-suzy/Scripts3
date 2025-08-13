<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : results.php
 * Desc  : Display the predictions for the currently logged
 *       : in user. If the cookie does not exist, check
 *       : the GET parameters. If no user can be found
 *       : prompt for one.
 *       : If the user is found, then display their data.
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
require "dbaseFunctions.php";
require "security.php";

session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();
?>

<html>
<head>
<title>
<?php echo "Results\n"?>
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
<?php require "menus.php"?>
</td>
<td class="CENTERCOL">
<table width="500">
<?php
  /*******************************************************
  * Check the user id and password from the cookie.
  *******************************************************/
  $user = $HTTP_GET_VARS["user"];
  ShowUserPredictions($user);
?>
</table>
</td>
<td class="RIGHTCOL">
  <!-- Show the Prediction stats for the next match -->
  <?php ShowPredictionStatsForNextMatch(); ?>
  
  <!-- Competition Prize -->
  <?php require "Prize.html"?>
</td>
</tr>
</table>

</body>
</html>
