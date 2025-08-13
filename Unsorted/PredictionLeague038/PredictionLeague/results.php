<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : results.php
 * Desc  : Display the results for all the games.
 ********************************************************/
?>

<?php
include "dbaseFunctions.php";

function GetResults() {
  global $dbaseMatchData;

  $link = OpenConnection();
  if ($link == FALSE) {
    echo "Unable to connect to the dbase";
    return;
  }

  $user = "jastill"; //TODO cookies etc.
  
  $userquery = "SELECT * FROM $dbaseMatchData";
  $userresult = mysql_query($userquery)
      or die("Query failed: $userquery");

  // Diisplay the username as a header.
  echo "<tr>";
  echo "<td class=\"TBLHEAD\" colspan=\"6\" align=\"center\"><font class=\"TBLHEAD\">Fixtures/Results</font></td>";
  echo "</tr>";
  echo "<tr>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Date</font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Home</font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
  echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Away</font></td>";
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
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"ShowPredictionsForDate.php?date=$date\">$date</a></font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$hometeam</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$homescore</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayscore</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayteam</font></td>";
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
<?php include "menus.php"; ?>
</td>
<td valign="top">
<table width="500">
<?php
  GetResults();
?>
</table>
</td>
</tr>
</table>

</body>
</html>


