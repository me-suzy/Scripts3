<?php
/*********************************************************
 * Author: John Astill
 * Date  : 31st December
 * File  : PostResult.php
 * Desc  : Post a new result
 ********************************************************/
  require "SystemVars.php";
  require "dbaseFunctions.php";

  $date = $HTTP_POST_VARS["DATE"];
  $hometeam = $HTTP_POST_VARS["HOMETEAM"];
  $awayteam = $HTTP_POST_VARS["AWAYTEAM"];
  $homescore = $HTTP_POST_VARS["HOMESCORE"];
  $awayscore = $HTTP_POST_VARS["AWAYSCORE"];
  // If the post is a success, go to the AdminEnterResult page.

  $link = OpenConnection();
  $query = "update $dbaseMatchData SET homescore=\"$homescore\", awayscore=\"$awayscore\" where matchdate = \"$date\" and hometeam=\"$hometeam\" and awayteam=\"$awayteam\"";
  $result = mysql_query($query)
      or die("Query failed: $query");

  CloseConnection($link);

  /* Redirect browser to PHP web site */
  header("Location: AdminEnterResult.php"); 
  exit; 
?>
