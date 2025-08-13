<?php
/*********************************************************
 * Author: John Astill (c) 2002
 * Date  : 3rd January 2002
 * File  : ModifyFixture.php
 *       : Modifies the given fixture in the database.
 *       : Makes sure both the Match table and Prediction
 *       : table are updated.
 *********************************************************/
  require "SystemVars.php";
  require "dbaseFunctions.php";
  require "LogFunctions.php";

  $date = $HTTP_POST_VARS["DATE"];
  $time = $HTTP_POST_VARS["TIME"];
  if ($time == "") {
    $time = "00:00:00";
  }
  $datetime="$date $time";
  $hometeam = $HTTP_POST_VARS["HOMETEAM"];
  $awayteam = $HTTP_POST_VARS["AWAYTEAM"];
 
  $olddate = $HTTP_POST_VARS["OLDDATE"];
  $oldhome = $HTTP_POST_VARS["OLDHOME"];
  $oldaway = $HTTP_POST_VARS["OLDAWAY"];

  // If the post is a success, go to the AdminEnterResult page.
  $link = OpenConnection();
  
  // Remove the fixture from the match data.
  $query = "update $dbaseMatchData set matchdate=\"$datetime\", hometeam=\"$hometeam\", awayteam=\"$awayteam\" where matchdate=\"$olddate\" and hometeam=\"$oldhome\" and awayteam=\"$oldaway\"";
  $result = mysql_query($query)
      or die("Query failed removing from Match Data: $query");

  // Log the changes
  $rows = mysql_affected_rows();
  LogMsg("Modified fixture.\nUsed $query.\n$rows affected.");
  
  // Remove the fixture from the users prediction data
  $query = "update $dbasePredictionData set matchdate=\"$datetime\", hometeam=\"$hometeam\", awayteam=\"$awayteam\" where matchdate=\"$olddate\" and hometeam=\"$oldhome\" and awayteam=\"$oldaway\"";
  $result = mysql_query($query)
      or die("Query failed removing from Prediction Data: $query");

  // Log the changes
  $rows = mysql_affected_rows();
  LogMsg("Modified user prediction.\nUsed $query.\n$rows affected.");
  

  // Close the connection with the database.
  CloseConnection($link);

  /* Redirect browser to PHP web site */
  header("Location: AdminEnterFixture.php"); 
  /* Make sure that code below does not get executed when we redirect. */
  exit; 
?>
