<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 3rd January 2002
 * File  : DeleteFixture.php
 *       : Deletes the given fixture from the database.
 *********************************************************/
  require "SystemVars.php";
  require "dbaseFunctions.php";
  require "LogFunctions.php";
  require "SortFunctions.php";
  require "security.php";

  // Create the session and save the user data.
  session_set_cookie_params(60*60*24*7,"/$baseDirName/");
  session_start();

  // Make sure the user trying to perform the delete is 
  // an admin or above.
  if (!CheckAdmin($User->usertype)) {
    // Forward to somewhere
    LogMsg("Attempt to remove fixture by User $User->userid with insufficient permissions from address $REMOTE_ADDR:$REMOTE_PORT. Referrer $HTTP_REFERER");
    forward("PredictionIndex.php");
  }
  
  LogMsg("Attempt to remove fixture by User $User->userid from $REMOTE_ADDR:$REMOTE_PORT.");
  // First look for the post data.
  $date = $HTTP_POST_VARS["DATE"];
  if ($date == "") {
    $datetime = $HTTP_GET_VARS["matchdate"];
    $hometeam = $HTTP_GET_VARS["home"];
    $awayteam = $HTTP_GET_VARS["away"];
  } else {
    $time = $HTTP_POST_VARS["TIME"];
    if ($time == "") {
      $time = "00:00:00";
    }
    $datetime="$date $time";
    $hometeam = $HTTP_POST_VARS["HOMETEAM"];
    $awayteam = $HTTP_POST_VARS["AWAYTEAM"];
  }

  // If the post is a success, go to the AdminEnterResult page.
  $link = OpenConnection();
  
  // Remove the fixture from the match data.
  $query = "delete from $dbaseMatchData where matchdate=\"$datetime\" and hometeam=\"$hometeam\" and awayteam=\"$awayteam\"";
  $result = mysql_query($query)
      or die("Query failed removing from Match Data: $query");

  // Log the changes
  $rows = mysql_affected_rows();
  LogMsg("Removed fixture.\nUsed $query.\n$rows affected.");
  
  // Remove the fixture from the users prediction data
  $query = "delete from $dbasePredictionData where matchdate=\"$datetime\" and hometeam=\"$hometeam\" and awayteam=\"$awayteam\"";
  $result = mysql_query($query)
      or die("Query failed removing from Prediction Data: $query");

  // Log the changes
  $rows = mysql_affected_rows();
  LogMsg("Remove user prediction.\nUsed $query.\n$rows affected.");
  

  // Close the connection with the database.
  CloseConnection($link);

  /* Redirect browser to PHP web site */
  header("Location: AdminEnterFixture.php"); 
  /* Make sure that code below does not get executed when we redirect. */
  exit; 
?>
