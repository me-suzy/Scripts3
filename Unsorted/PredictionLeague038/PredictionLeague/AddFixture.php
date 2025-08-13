<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 3rd January 2002
 * File  : AddFixture.php
 *       : DATETIME format YYYY-MM-DD HH:MM:SS
 *********************************************************/
  require "SystemVars.php";
  require "dbaseFunctions.php";
  require "LogFunctions.php";  
  require "security.php";

  // Create the session and save the user data.
  session_set_cookie_params(60*60*24*7,"/$baseDirName/");
  session_start();

  // Make sure the user trying to add the fixture is admin
  // an admin or above.
  if (!CheckAdmin($User->usertype)) {
    // Forward to somewhere
    LogMsg("Attempt to add Fixture by User $User->userid with insufficient permissions from $REMOTE_ADDR:$REMOTE_PORT.");
    forward("PredictionIndex.php");
  }

  $date = $HTTP_POST_VARS["DATE"];
  $time = $HTTP_POST_VARS["TIME"];
  if ($time == "") {
    $time = "00:00:00";
  }
  $datetime="$date $time";
  $hometeam = $HTTP_POST_VARS["HOMETEAM"];
  $awayteam = $HTTP_POST_VARS["AWAYTEAM"];

  // If the post is a success, go to the AdminEnterResult page.
  $link = OpenConnection();
  $query = "insert into $dbaseMatchData (matchdate,hometeam,awayteam) VALUES (\"$datetime\",\"$hometeam\",\"$awayteam\")";
  $result = mysql_query($query)
      or die("Query failed: $query");

  // Log the addition
  LogMsg("Added fixture $date $time $hometeam $awayteam.\n$query");

  CloseConnection($link);

  /* Redirect browser to PHP web site */
  header("Location: AdminEnterFixture.php"); 
  /* Make sure that code below does not get executed when we redirect. */
  exit; 
?>
