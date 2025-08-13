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
require "dbaseFunctions.php";
require "security.php";

session_set_cookie_params(60*60*24*7,"/$baseDirName/");
session_start();

function ShowTable() {
  global $dbasePredictionData;

  $link = OpenConnection();
  if ($link == FALSE) {
    echo "Unable to connect to the dbase";
    return;
  }
  
  $userquery = "SELECT * FROM PredictionData order by username";
  $userresult = mysql_query($userquery)
      or die("Query failed: $userquery");

  echo "<pre>";
  // Display the username as a header.
  // First loop. Used to get all the users.
  while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
    // For each user display all their predictions.
    // against the actual result.
    $username = $userline["username"];
    $matchdate = $userline["matchdate"];
    $hometeam = $userline["hometeam"];
    $awayteam = $userline["awayteam"];
    $homescore = $userline["homescore"];
    $awayscore = $userline["awayscore"];

    echo $username."\t";
    echo $matchdate."\t";
    echo $hometeam."\t";
    echo $awayteam."\t";
    echo $homescore."\t";
    echo $awayscore."\n";
  }
  echo "</pre>";

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

<body class="MAIN">

<?php
  ShowTable();
?>

</body>
</html>
