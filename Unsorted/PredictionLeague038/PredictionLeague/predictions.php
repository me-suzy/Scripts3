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
?>

<?php
include "dbaseFunctions.php";
include "security.php";

function GetUserPredictions($user) {
  global $dbasePredictionData;

  $link = OpenConnection();
  if ($link == FALSE) {
    echo "Unable to connect to the dbase";
    return;
  }
  
  $userquery = "SELECT * FROM $dbasePredictionData WHERE username = \"$user\"";
  $userresult = mysql_query($userquery)
      or die("Query failed: $userquery");

  // Display the username as a header.
  echo "<tr>";
  echo "<td class=\"TBLHEAD\" colspan=\"100%\" align=\"center\"><font class=\"TBLHEAD\">Predictions [$user]</font></td>";
  echo "</tr>";
  echo "<tr>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Date</font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Home</font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Away</font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Points</font></td>";
  echo "</tr>";
  // First loop. Used to get all the users.
  while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
    // For each user display all their predictions.
    // against the actual result.
    $hometeam = $userline["hometeam"];
    $awayteam = $userline["awayteam"];
    $homescore = $userline["homescore"];
    $awayscore = $userline["awayscore"];
    $date = $userline["matchdate"];

    echo "<tr>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$date</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$hometeam</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$homescore</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayscore</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayteam</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"></font></td>";
    echo "</tr>";
  }

  CloseConnection($link);
}
?>

<html>
<head>
<title>
<?php echo "Results\n"?>
</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>

<body>

<table width="800">
<tr>
<td valign="top">
<?php include "menus.php"?>
</td>
<td valign="top">
<table width="500">
<?php
  /*******************************************************
  * Check the user id and password from the cookie.
  *******************************************************/
  $userid = $HTTP_COOKIE_VARS[$usernameCookie];
  $pwd = $HTTP_COOKIE_VARS[$passwordCookie];
  if (CheckUserLogin($userid, $pwd)) {
    GetUserPredictions($userid);
  }
?>
</table>
</td>
</tr>
<tr>
<td>
</td>
<td>
  <!-- Next Prediction Table -->
  <form method="POST" action="TODO">
  <table width="500">
  <tr>
  <td colspan="100%" align="center" class="TBLHEAD">
  <font class="TBLHEAD">
  Next Prediction
  </font>
  </td>
  </tr>
  <tr>
  <td class="TBLROW">
  <font class="TBLROW">
  <?php 
    //GetNextPrediction(); 
    $date = getdate();
    echo $date["year"]."-".$date["mon"]."-".$date["mday"]."\n";
  ?>
  </font>
  </td>
  <td class="TBLROW">
  <font class="TBLROW">
  <?php 
    //GetNextPrediction(); 
  ?>
  </font>
  </td>
  <td align="RIGHT" class="TBLROW">
  <font class="TBLROW">
  <?php 
    //GetNextPrediction(); 
  ?>
  <input type="submit" value="Predict" name="Predict">
  </font>
  </td>
  </tr>
  </table>
  </form>
</td>
</tr>
</table>

</body>
</html>


