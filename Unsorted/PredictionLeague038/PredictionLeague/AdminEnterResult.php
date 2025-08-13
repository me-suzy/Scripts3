<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 9th December
 * File  : AdminEnterFixture.php
 * This page allows an administrator to add
 * extra fixtures to the prediction league.
 * The current contents of the fixture list will
 * also be displayed.
 ********************************************************/
require "SystemVars.php";
require "SortFunctions.php";
require "dbaseFunctions.php";
require "security.php";

session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

/*******************************************************
* Function to create an indexed array from the database
* table holding the fixtures.
*******************************************************/
function GetCurrentFixtures() {
  // Array holding the current fixtures.
  global $dbaseMatchData;

  $currFixtures;

  $link = OpenConnection();
  if ($link == FALSE) {
    echo "Unable to connect to the dbase ";
    return;
  }
   
  $matchquery = "SELECT * FROM $dbaseMatchData order by matchdate desc";
  $matchresult = mysql_query($matchquery)
     or die("Query failed: $matchquery");
?>
  <table width="500">
  <tr>
  <td align="center" class="TBLHEAD" colspan="6"><font class="TBLHEAD">Current Fixtures</font></td>
  </tr>
  <tr>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD">Date</font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD">Home</font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD">Away</font></td>
<?php
  while ($matchdata = mysql_fetch_array($matchresult,MYSQL_ASSOC)) {
    $matchdate = $matchdata["matchdate"];
    $hometeam = $matchdata["hometeam"];
    $awayteam = $matchdata["awayteam"];
    echo "<tr>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">";
    echo "<a href=\"AdminPostResult.php?matchdate=$matchdate&hometeam=".EncodeParam($hometeam)."&awayteam=".EncodeParam($awayteam)."\">";
    echo $matchdate;
    echo "</a></font></td>";
    echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">".$hometeam."</font></td>";
    echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">".$matchdata["homescore"]."</font></td>";
    echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">".$matchdata["awayscore"]."</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">".$awayteam."</font></td>";
    echo "</tr>";
  }
  echo "</table>";

  CloseConnection($link);
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>
Result Administration
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
<td valign="top">
<?php 
  include "menus.php";
?>
</td>
<td valign="TOP">
  <?php GetCurrentFixtures(); ?>
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
