<?php 
/*********************************************************
 * Author: John Astill (c) 2002
 * Date  : 9th December 2001
 * File  : SortFunctions.php
 ********************************************************/

 /////////////////////////////////////////////////////////
 // Class for holding the results/stats for each entrant.
 /////////////////////////////////////////////////////////
 class GameStats {

   // Username of entrant
   var $username;

   // The number of exact results predicted.
   var $won;

   // The number of correct winning team predictions.
   var $drawn;

   // The number of entries where the result is incorrect.
   var $lost;

   // The number of correct score per team predictions. The value is the sum
   // of the individual scores.
   var $for;

   // The number of goals incorrectly predicted.
   var $against;

   // Goal difference.
   var $diff;

   // Points
   var $points;

   // Predictions
   var $predictions;

   /************************************************************
    * Constructor used for creating a reset gameStat for a user.
    * @param user the name of the entrant.
    ************************************************************/
   function GameStats($user) {

     $this->predictions = 0;
     $this->username = $user;
     $this->won = 0;
     $this->drawn = 0;
     $this->lost = 0;
     $this->for = 0;
     $this->against = 0;
     $this->diff = 0;
     $this->points = 0;
   }

   /************************************************************
    * Function used to calculate points and goal differences.
    * The scoring is determined as follows:
    *   For an exact prediction
    *    o CORRECT_SCORE_POINTS points
    *    o for is incremented by the number of goals scored
    *      by each team.
    *   For a correct prediction
    *    o CORRECT_RESULT_POINTS points
    *    o for is incremented by the number of goals scored
    *      by each team that is predicted correctly.
    *    o against is incremented by the number of goals 
    *      incorrectly predicted. e.g if a result is 1-1 and
    *      the predicted score was 2-1, against would be incremented
    *      by 1.
    *   For an incorrect prediction
    *    o 0 points
    *    o for is incremented by the number of goals scored
    *      by each team that is predicted correctly.
    *    o against is incremented by the number of goals 
    *      incorrectly predicted. e.g if a result is 1-1 and
    *      the predicted score was 2-1, against would be incremented
    *      by 1.
    * @param predHome   The predicted home number of goals
    * @param predAway   The predicted away number of goals
    * @param actualHome The actual home number of goals
    * @param actualAway The actual away number of goals
    ************************************************************/
   function UpdateStats($predHome, $predAway, $actualHome, $actualAway) {
     // Increment the number of predictions.
     $this->predictions++;

     // Determine if the correct score is predicted.
     if ($predHome == $actualHome && $predAway == $actualAway) {
       $this->won += 1;
       $this->points += CORRECT_SCORE_POINTS;
       $this->for += $predHome + $predAway;
       $this->diff = $this->for - $this->against;
       return;
     }

     // Determine if the correct result is predicted. i.e. the correct
     // winning team or draw.
     if (($predHome > $predAway) && ($actualHome > $actualAway) ||
         ($predHome < $predAway) && ($actualHome < $actualAway) ||
         ($predHome == $predAway) && ($actualHome == $actualAway)) {
       $this->points += CORRECT_RESULT_POINTS;
       $this->drawn += 1;
     } else {
       $this->lost += 1;
     }

     // Calculate the for and against values.
     if ($predHome == $actualHome) {
       $this->for += $predHome;
     } else {
       if ($predHome > $actualHome) {
         $this->against += $predHome - $actualHome;
       } else {
         $this->against += $actualHome - $predHome;
       }
     }

     if ($predAway == $actualAway) {
       $this->for += $predAway;
     } else {
       if ($predAway > $actualAway) {
         $this->against += $predAway - $actualAway;
       } else {
         $this->against += $actualAway - $predAway;
       }
     }
     $this->diff = $this->for - $this->against;
   }

 }
/*************************************************************
* END OF CLASS
*************************************************************/

   /******************************************************
   * Compare the two classes passed.
   * The order of attributes compared is as follows:
   *   Points
   *   Goal Difference
   *   Games Played
   * @param a
   * @param b
   * @return <0 if a < b
   *          0
   *         >0
   ******************************************************/
   function compare($a, $b) {
     $lessthan = -1;
     $morethan = 1;

     if ($a->points < $b->points) {
       return $morethan;
     }
     if ($a->points > $b->points) {
       return $lessthan;
     }

     // Points must be equal

     if ($a->diff < $b->diff) {
       return $morethan;
     }
     if ($a->diff > $b->diff) {
       return $lessthan;
     }

     // Goal diff must be equal

     if ($a->predictions < $b->predictions) {
       return $morethan;
     }
     if ($a->predictions > $b->predictions) {
       return $lessthan;
     }

     // a draw
     return 0;
   }

  /////////////////////////////////////////////
  // Sorting functions for the prediction data.
  // These functions also cause the output to
  // be written.
  /////////////////////////////////////////////
  
  /////////////////////////////////////////////
  // Sort By absloute score
  // For each username calculate the cumulative score
  // Sort into the required order. The order is the 
  // following: Points,GD,GP
  // Display the data
  /////////////////////////////////////////////
  function ShowTablesBasedOnScore() {
    // Allow access to the global table name.
    global $dbaseUserData, $dbasePredictionData, $dbaseMatchData, $HTTP_GET_VARS;
    $page = $HTTP_GET_VARS["page"];

    // Update the league right after the game has started. The null result value is chekced for.
    $todaysdate = date("Y-m-d H:i:s");

    // Array for sorting the table based on points scored
    $points;

    // Array holding users
    $gameStats;
    
    // Array for holding new user.
    $newUsers;

    // Connecting, selecting database
    $link = OpenConnection();
    if ($link == FALSE) {
      echo "Unable to connect to dbase.";
      return;
    }

    // Time or a nested Select. This should eventually
    // be replaced with a join.
    // Performing SQL query
    $userquery = "SELECT * FROM $dbaseUserData";
    $userresult = mysql_query($userquery, $link)
        or die("Query failed: $userquery");

    // Use the number of users when splitting the display.
    $numUsers = mysql_num_rows($userresult);
    
    // First loop. Used to get all the users.
    while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
      // For each user, find all their predictions, and compare the prediction 
      // against the actual result.
      $user = $userline["username"];
      $predquery = "SELECT * FROM $dbasePredictionData where username = \"$user\" and matchdate<=\"$todaysdate\"";
      $predresult = mysql_query($predquery, $link)
          or die("Query failed: $predquery");

      $gameStats[$user] = new GameStats($user);
      
      // Getting the individual predictions from the database
      while ($predline = mysql_fetch_array($predresult, MYSQL_BOTH)) {
        reset ($predline);
        $reshometeam = $predline["hometeam"];
        $resawayteam = $predline["awayteam"];
        $predictedHome = $predline["homescore"];
        $predictedAway = $predline["awayscore"];

        // Compare the actual result against predicted and Create the points Scored.
        $actualquery = "SELECT homescore, awayscore FROM $dbaseMatchData where matchdate = \"".$predline["matchdate"]."\" and hometeam=\"$reshometeam\" and awayteam=\"$resawayteam\" and homescore is not null";
        $actualresult = mysql_query($actualquery, $link)
            or die("Query failed: $actualquery");
        
        $rows = mysql_num_rows($actualresult);
        if ($rows > 0) {
          $matchdata = mysql_fetch_array($actualresult,MYSQL_ASSOC);
          $actualHome = $matchdata["homescore"];
          $actualAway = $matchdata["awayscore"];
          $gameStats[$user]->UpdateStats($predictedHome,
                                         $predictedAway,
                                         $actualHome,
                                         $actualAway);
        }
        mysql_free_result($actualresult);
      }
    }

    // Closing connection
    CloseConnection($link);

    // Sort in descending order. Keep the keys intact.
    // The compare can be a class method in PHP 4.1.1, but will not work with 4.0.3
    uasort($gameStats, "compare");

?>
<table width="500">
<?php 
  if ($numUsers > USERS_PER_PAGE) {
?>
<tr>
  <td width="500" colspan="11" align="left" class="TBLROW">
    <table>
    <tr>
    <font class="TBLROW">
<?php
  // TODO create a nicer tabbed area
  for ($i=0,$j=0; $i<$numUsers; $i+=USERS_PER_PAGE,$j++) {
    
    echo "<td width=\"80\" class=\"TBLHEAD\">";
    echo "<a href=\"PredictionIndex.php?page=$j\">";
    echo $i+1;
    echo "-";
    echo $i+USERS_PER_PAGE;
    echo "</a>";
    echo "</td>";
  }
?>
    </font>
    </tr>
    </table>
  </td>
</tr>
<?php
  }
?>
<tr>
  <td width="500" colspan="11" align="center" class="TBLHEAD">
    <font class="TBLHEAD">Prediction League Standings <?php echo date("d M Y");?></font>
  </td>
</tr>
<tr>
  <td class="TBLPOS">
    <font class="TBLHEAD">Pos</font>
  </td>
  <td class="TBLUSER">
    <font class="TBLHEAD">User</font>
  </td>
  <td class="TBLPLAYED">
    <font class="TBLHEAD">P</font>
  </td>
  <td class="TBLWON">
    <font class="TBLHEAD">W</font>
  </td>
  <td class="TBLDRAWN">
    <font class="TBLHEAD">D</font>
  </td>
  <td class="TBLLOST">
    <font class="TBLHEAD">L</font>
  </td>
  <td class="TBLFOR">
    <font class="TBLHEAD">F</font>
  </td>
  <td class="TBLAGAINST">
    <font class="TBLHEAD">A</font>
  </td>
  <td class="TBLGD">
    <font class="TBLHEAD">GD</font>
  </td>
  <td class="TBLPTS">
    <font class="TBLHEAD"><b>Pts</b></font>
  </td>
</tr>

<?php
    // Display the table for people with results.
    $count = 1;
    while ((list($key,$val) = each($gameStats))) { // TODO
      /* Minimum of one prediction */
      if ($val->predictions == 0) {
        $newUsers[$val->username] = $val->username;
        continue;
      }

      /* Only display the selected page */
      if (($count <= ($page*USERS_PER_PAGE)) ||
         ($count > (($page*USERS_PER_PAGE)+USERS_PER_PAGE))) {
         $count++;
         continue;
      }

     echo " <tr>\n";

     $class = "TBLROW";
     if ($count == 1) {
       $class="LEADER";
     }
?>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <?php echo $count?>
          </font>
        </td>
        <td class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <a href="ShowUserPredictions.php?user=<?php echo $val->username?>"><?php echo $val->username?></a>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <!-- Played -->
            <?php echo $val->predictions?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <!-- Won -->
            <?php echo $val->won; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <!-- Drawn -->
            <?php echo $val->drawn; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <!-- Lost -->
            <?php echo $val->lost; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <?php echo $val->for; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <?php echo $val->against; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <?php echo $val->diff; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <b>
              <?php echo $val->points?>
            </b>
          </font>
        </td>
      </tr>
<?php
    $count++;
    }
    echo "</table>";
    // Display a table of the new users. Uncomment the next line to 
    // show the users.
    //showNewUsers($newUsers);
  } // End of show tables based on score.

  ///////////////////////////////////////////
  // Display a table of the new users.
  ///////////////////////////////////////////
  function showNewUsers($newUsers) {
  ?>
<!-- New Users -->  
  <tr>
    <td class="TBLROW" colspan="10">
      <font class="TBLROW">
        One complete match prediction required before position is shown
      </font>
    </td>
  </tr>
</table>

<?php
    if (count($newUsers) > 0) {
?>
<!-- Display the new users in a table -->
<table border="0" width="100">
<tr>
  <td colspan="1" align="center" class="TBLHEAD">
    <font class="TBLHEAD">New Users</font>
  </td>
</tr>
<?php
      // Display the new users. i.e. those without a prediction.
      while (list($key,$val) = each($newUsers)) {
?>
  <tr>
    <td class="TBLROW">
      <font class="PRDROW">
        <a href="ShowUserPredictions.php?user=<?php echo $val?>"><?php echo $val?></a>
      </font>
    </td>
  </tr>
<?php
      }
 echo "</table>\n";
    }
  } // End of show new users

  /////////////////////////////////////////////
  // Display users predictions
  /////////////////////////////////////////////
  function DisplayUserPredictions($user) {
    // Allow access to the global table name.
    global $dbasePredictionData;
    
    // Connecting, selecting database
    $link = OpenConnection();
    if ($link == FALSE) {
      echo "Unable to connect to dbase";
      return;
    }

    print "<pre>\n";
    // Time or a nested Select. This should eventually
    // be replaced with a join.
    // Performing SQL query
    print "$user\n";
    $predquery = "SELECT * FROM $dbasePredictionData where username = \"$user\"";
    $predresult = mysql_query($predquery, $link)
        or die("Query failed: $predquery");

    // Printing results in HTML
    while ($predline = mysql_fetch_array($predresult, MYSQL_ASSOC)) {
        reset ($predline);
        print "\t".$predline["matchdate"]."\t".$predline["hometeam"].
              " ".$predline["homescore"]."-".$predline["awayscore"].
              " ".$predline["awayteam"]."\n";
    }
    print "\n";
    print "</pre>\n";

    // Closing connection
    CloseConnection($link);
  }

  /////////////////////////////////////////////
  // Display everyones predictions
  /////////////////////////////////////////////
  function DisplayAllPredictions() {
    // Allow access to the global table name.
    global $dbaseUserData, $dbasePredictionData;

    // Connecting, selecting database
    $link = OpenConnection();
    if ($link == FALSE) {
      echo "Unable to connect to the dbase";
      return;
    }

    print "<pre>\n";
    // Time or a nested Select. This should eventually
    // be replaced with a join.
    // Performing SQL query
    $userquery = "SELECT * FROM $dbaseUserData";
    $userresult = mysql_query($userquery, $link)
        or die("Query failed: $userquery");

    while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
    
      $user = $userline["username"];
      print "$user\n";
      $predquery = "SELECT * FROM $dbasePredictionData where username = \"$user\"";
      $predresult = mysql_query($predquery, $link)
          or die("Query failed: $predquery");

      // Printing results in HTML
      while ($predline = mysql_fetch_array($predresult, MYSQL_ASSOC)) {
          reset ($predline);
          print "\t".$predline["matchdate"]."\t".$predline["hometeam"].
                " ".$predline["homescore"]."-".$predline["awayscore"].
                " ".$predline["awayteam"]."\n";
      }
      print "\n";
    }
    print "</pre>\n";

    // Closing connection
    CloseConnection($link);
  }

  /**
   * Search the Match table for the next game.
   */
  function getNextGame() {
    global $dbaseMatchData;

    $todaysdate = date("Y-m-d H:i:s");
    $tz = date("T");
    
    // Search for the next date in the dbase.
    $link = OpenConnection();
    if ($link == FALSE) {
      ErrorNotify("Unable to open connection");
      exit;
    }

    // If the matches are ordered, then the first should be the next game.
    $query = "select * from $dbaseMatchData where matchdate>=\"$todaysdate\" and homescore is null order by matchdate";
    $result = mysql_query($query)
      or die("Query failed: $query");

    $count = mysql_num_rows($result);
    if ($count == 0) {
      $nextmatch = "<b>No matches scheduled</b>";
    } else {
      $line = mysql_fetch_array($result, MYSQL_ASSOC);
      $matchdate = $line["matchdate"];
      $textdate = convertDatetimeToScreenDate($matchdate);
      $hometeam = $line["hometeam"];
      $awayteam = $line["awayteam"];
      $nextmatch = "<b>Next Match: $hometeam v $awayteam <a href=\"ShowPredictionsForDate.php?date=$matchdate&home=$hometeam&away=$awayteam\">$textdate [$tz]</a></b>";
    }

    CloseConnection($link);

    return $nextmatch;
  }

  /***********************************************
   * Show the prediction statistics for the match
   * on the given date.
   * @param date the date of the match to show
   **********************************************/
  function ShowPredictionStatsForMatch($date, $home, $away) {
    global $dbasePredictionData;

    // Search for the next date in the dbase.
    $link = OpenConnection();
    if ($link == FALSE) {
      ErrorNotify("Unable to open connection");
      exit;
    }

    // If the matches are ordered, then the first should be the next game.
    $query = "select * from $dbasePredictionData where matchdate=\"$date\" and hometeam=\"$home\" and awayteam=\"$away\" order by matchdate";
    $result = mysql_query($query)
      or die("Query failed: $query");

    if (mysql_num_rows($result) == 0) {
      $nextmatch = "No match on $date";
      echo "<!-- No match on $date -->";
    } else {
      // Get the date of the next game.
      $line = mysql_fetch_array($result, MYSQL_ASSOC);
      $matchdate = $line["matchdate"];
      $hometeam = $line["hometeam"];
      $awayteam = $line["awayteam"];
      $homescore = $line["homescore"];
      $awayscore = $line["awayscore"];
      $results["$homescore-$awayscore"] += 1;
      $count = 1;

      // Loop through the rest of the results, just taking the next games results.
      while ($line  = mysql_fetch_array($result, MYSQL_ASSOC)) {
        if (($matchdate == $line["matchdate"]) &&
            ($hometeam == $line["hometeam"]) &&
            ($awayteam == $line["awayteam"])) {
          $homescore = $line["homescore"];
          $awayscore = $line["awayscore"];
          $results["$homescore-$awayscore"] += 1;
          $count++;
        }
      }

      echo "<table width=\"140\">\n";
      echo "<tr>\n";
      echo "<td align=\"CENTER\" class=\"TBLHEAD\" colspan=\"2\">\n";
      echo "<font class=\"TBLHEAD\">\n";
      //echo "<a href=\"\"><img border=\"0\" height=\"10\" width=\"10\" src=\"images/triangleleft001.gif\" alt=\"Previous Fixture\"></a>";
      echo " Predictions Stats ";
      //echo "<a href=\"\"><img onclick='nextfixture();' border=\"0\" height=\"10\" width=\"10\" src=\"images/triangleright001.gif\" alt=\"Next Fixture\"></a>";
      echo "<br><a href=\"ShowPredictionsForDate.php?date=$matchdate&home=$hometeam&away=$awayteam\" class=\"PRED\">";
      echo "$hometeam v $awayteam";
      echo "</a>";
      echo "</font>\n";
      echo "</td>\n";
      echo "</tr>\n";
      // Cycle through the array and print the results.
      while (list($key,$val) = each($results)) {
        echo "<tr>\n";
        echo "<td align=\"CENTER\" class=\"TBLROW\">\n";
        echo "<font class=\"TBLROW\">\n";
        echo $key;
        echo "</font>\n";
        echo "</td>\n";
        echo "<td width=\"100\" class=\"TBLROW\">\n";
        echo "<font class=\"TBLROW\">\n";
        $percentage = floor(($val*100)/$count);
        echo "<img width=\"$percentage\" height=\"10\" src=\"percentbar.gif\" alt=\"Percentage\"> ";
        echo $percentage."% [$val]";
        echo "</font>\n";
        echo "</td>\n";
        echo "</tr>\n";
      }
      echo "</table>\n";
    }

    CloseConnection($link);
  }

  /***********************************************
   * Show the prediction statistics for the match
   * on the given date.
   **********************************************/
  function ShowPredictionStatsForNextMatch() {
    global $dbasePredictionData;

    $todaysdate = date("Y-m-d H:i:s");
    
    // Search for the next date in the dbase.
    $link = OpenConnection();
    if ($link == FALSE) {
      ErrorNotify("Unable to open connection");
      exit;
    }

    // If the matches are ordered, then the first should be the next game. If there are two at the same time, only one is shown
    $query = "select * from $dbasePredictionData where matchdate>=\"$todaysdate\" order by matchdate";
    $result = mysql_query($query)
      or die("Query failed: $query");

    CloseConnection($link);

    if (mysql_num_rows($result) == 0) {
      $nextmatch = "No matches scheduled";
    } else {
      // Get the date of the next game.
      $line = mysql_fetch_array($result, MYSQL_ASSOC);
      $matchdate = $line["matchdate"];
      $hometeam = $line["hometeam"];
      $awayteam = $line["awayteam"];
      $homescore = $line["homescore"];
      $awayscore = $line["awayscore"];
      $results["$homescore-$awayscore"] += 1;
      $count = 1;

      ShowPredictionStatsForMatch($matchdate,$hometeam,$awayteam);
    }
  }

  /**********************************************************
   * Get and Display the user predictions.
   * Display the predictions from the users prediction table
   * entries and also any games from the MatchTable that
   * have no entry in the Users Prediction data.
   **********************************************************/
  function GetUserPredictions($user) {
    global $dbasePredictionData, $dbaseMatchData;

    $link = OpenConnection();
    if ($link == FALSE) {
      echo "Unable to connect to the dbase";
      return;
    }
    
    // At this point this is only used in the table header.
    $date = GetDateFromDatetime(date("Y-m-d"));
    
    // Select the fixtures from both tables. 
    $userquery = "SELECT $dbaseMatchData.matchdate,$dbaseMatchData.hometeam,$dbaseMatchData.awayteam,$dbaseMatchData.homescore,$dbaseMatchData.awayscore,$dbasePredictionData.homescore,$dbasePredictionData.awayscore FROM $dbaseMatchData left join $dbasePredictionData on $dbasePredictionData.matchdate=$dbaseMatchData.matchdate and $dbasePredictionData.hometeam=$dbaseMatchData.hometeam and $dbasePredictionData.awayteam=$dbaseMatchData.awayteam and (($dbaseMatchData.homescore is null and username is null) or username = \"$user\") order by $dbaseMatchData.matchdate desc";
    $userresult = mysql_query($userquery)
        or die("Query failed: $userquery");

    // Display the username as a header.
    echo "<tr>";
    echo "<td class=\"TBLHEAD\" colspan=\"7\" align=\"center\"><font class=\"TBLHEAD\">$user Predictions [$date]</font></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Date</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Home</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">F</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">&nbsp;</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">A</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Away</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Result</font></td>";
    echo "</tr>";
    // First loop. Used to get all the users.
    while ($userline = mysql_fetch_array($userresult, MYSQL_BOTH)) {
      // For each user display all their predictions.
      // against the actual result.
      //$hometeam = $userline["hometeam"];
      //$awayteam = $userline["awayteam"];
      $hometeam = $userline[1];
      $awayteam = $userline[2];
      $homeresult = $userline[3];
      $awayresult = $userline[4];
      $homescore = $userline[5];
      $awayscore = $userline[6];
      $date = $userline["matchdate"];
      $datestr = GetDateFromDateTime($date);
      $timestr = GetTimeFromDateTime($date);

      echo "<tr>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"ShowPredictionsForDate.php?date=$date&home=$hometeam&away=$awayteam\">$datestr</a> $timestr</font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$hometeam</font></td>";
      if ($homescore == null) {
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">?</font></td>";
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>";
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">?</font></td>";
      } else {
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$homescore</font></td>";
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>";
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayscore</font></td>";
      }
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayteam</font></td>";
      // Compare the dates, only allow unplayed matches to be changed 
      if (CompareDatetime($date) > 0) {
        echo "<td class=\"TBLROW\">";
          echo "<font class=\"TBLROW\">";
            $user = encodeParam($user);
            $hometeam = encodeParam($hometeam);
            $awayteam = encodeParam($awayteam);
            echo "<a href=\"EnterPrediction.php?user=$user&date=$date&home=$hometeam&hs=$homescore&away=$awayteam&as=$awayscore\">Predict</a>";
          echo "</font>";
        echo "</td>";
      } else {
        echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">$homeresult - $awayresult</font></td>";
      }
      echo "</tr>";
    }

    CloseConnection($link);
  }

  /**********************************************************
   * Display the user predictions.
   * Display the predictions from the users prediction table
   * entries and also any games from the MatchTable that
   * have no entry in the Users Prediction data.
   **********************************************************/
  function ShowUserPredictions($user) {
    global $dbasePredictionData;

    $link = OpenConnection();
    if ($link == FALSE) {
      echo "Unable to connect to the dbase";
      return;
    }
    
    $userquery = "SELECT * FROM $dbasePredictionData WHERE username = \"$user\" order by matchdate";
    $userresult = mysql_query($userquery)
        or die("Query failed: $userquery");

    // Display the username as a header.
    echo "<tr>";
    echo "<td class=\"TBLHEAD\" colspan=\"6\" align=\"center\"><font class=\"TBLHEAD\">Predictions [$user]</font></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Date</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Home</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">&nbsp;</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">&nbsp;</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">&nbsp;</font></td>";
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
      $datetext = GetDateFromDatetime($date);
      $time = GetTimeFromDatetime($date);

      echo "<tr>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"ShowPredictionsForDate.php?date=$date&home=$hometeam&away=$awayteam\">$datetext</a> $time</font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$hometeam</font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$homescore</font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayscore</font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayteam</font></td>";
      echo "</tr>";
    }

    CloseConnection($link);
  }

  /*****************************************************************
   * Get the predictions for the given date.
   * @param the date for the game in the same format as the dbase.
   *****************************************************************/
  function GetPredictionsForDate($date, $home, $away) {
    global $dbasePredictionData;

    $link = OpenConnection();
    if ($link == FALSE) {
      echo "Unable to connect to the dbase";
      return;
    }
    
    $userquery = "SELECT * FROM $dbasePredictionData WHERE matchdate=\"$date\" and hometeam=\"$home\" and awayteam=\"$away\"";
    $userresult = mysql_query($userquery)
        or die("Query failed: $userquery");

    // Display the username as a header.
    $datetext = convertDatetimeToScreenDate($date);
    echo "<table width=\"500\">";
    echo "<tr>";
    echo "<td class=\"TBLHEAD\" colspan=\"7\" align=\"center\"><font class=\"TBLHEAD\">Predictions [$datetext]</font></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td width=\"80\" align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Date</font></td>";
    echo "<td width=\"160\" align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">User</font></td>";
    echo "<td width=\"100\" align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Home</font></td>";
    echo "<td width=\"20\" align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
    echo "<td width=\"20\" align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
    echo "<td width=\"20\" align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
    echo "<td width=\"100\" align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Away</font></td>";
    echo "</tr>";
    // First loop. Used to get all the users.
    while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
      // For each user display all their predictions.
      // against the actual result.
      $user = $userline["username"];
      $hometeam = $userline["hometeam"];
      $awayteam = $userline["awayteam"];
      $homescore = $userline["homescore"];
      $awayscore = $userline["awayscore"];
      $date = $userline["matchdate"];
      // The date is in datetime format YYYY-MM-DD HH:MM:SS , pull off date
      $datetext = GetDateFromDatetime($date);

      echo "<tr>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$datetext</font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"ShowUserPredictions.php?user=$user\">$user</a></font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$hometeam</font></td>";
      echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">$homescore</font></td>";
      echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>";
      echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">$awayscore</font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayteam</font></td>";
      echo "</tr>";
    }
    echo "</table>";

    CloseConnection($link);
  }

  /****************************************************************
   * Get and display the results in a table format.
   ****************************************************************/
  function GetResults() {
    global $dbaseMatchData;

    // Create the connection to the database
    $link = OpenConnection();
    if ($link == FALSE) {
      echo "Unable to connect to the dbase";
      return;
    }
    
    $userquery = "SELECT * FROM $dbaseMatchData order by matchdate";
    $userresult = mysql_query($userquery)
        or die("Query failed: $userquery");

    // Display the username as a header.
    echo "<table width=\"500\">\n";
    echo "<tr>\n";
    echo "<td class=\"TBLHEAD\" colspan=\"6\" align=\"center\"><font class=\"TBLHEAD\">Fixtures/Results</font></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td width=\"80\" align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Date</font></td>\n";
    echo "<td width=\"160\" align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Home</font></td>\n";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLROW\">G</font></td>\n";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLROW\">v</font></td>\n";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLROW\">G</font></td>\n";
    echo "<td width=\"160\" align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">Away</font></td>\n";
    echo "</tr>\n";
    // First loop. Used to get all the users.
    while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
      // For each user display all their predictions.
      // against the actual result.
      $hometeam = $userline["hometeam"];
      $awayteam = $userline["awayteam"];
      $homescore = $userline["homescore"];
      if ($homescore == null) {
        $homescore = "&nbsp;";
      }
      $awayscore = $userline["awayscore"];
      if ($awayscore == null) {
        $awayscore = "&nbsp;";
      }
      $date = $userline["matchdate"];
      $datetext = GetDateFromDatetime($date);

      echo "<tr>\n";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"ShowPredictionsForDate.php?date=$date&home=$hometeam&away=$awayteam\">$datetext</a></font></td>\n";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$hometeam</font></td>\n";
      echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">$homescore</font></td>\n";
      echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>\n";
      echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">$awayscore</font></td>\n";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayteam</font></td>\n";
      echo "</tr>\n";
    }
    echo "</table>\n";

    CloseConnection($link);
  }

  /***********************************************************************
   * Encode parameters.
   * The parameters passed as GET values cannot contain spaces. This
   * must be replaced with a + .
   ***********************************************************************/
  function EncodeParam($param) {
    return str_replace(" ","+",$param);
  }

  function GetRawDateFromDatetime($datetime) {
    return substr($datetime,0,10);
  }

  function GetTimeFromDatetime($datetime) {
    return substr($datetime,11,8);
  }
  
  /*************************************************************************
   * Get the screen formatted date from the datetime
   ************************************************************************/
  function GetDateFromDatetime($datetime) {
    $Months = array('01'=>"Jan",
                    '02'=>"Feb",
                    '03'=>"Mar",
                    '04'=>"Apr",
                    '05'=>"May",
                    '06'=>"Jun",
                    '07'=>"Jul",
                    '08'=>"Aug",
                    '09'=>"Sep",
                    '10'=>"Oct",
                    '11'=>"Nov",
                    '12'=>"Dec");
    $day = substr($datetime,8,2);
    $month = substr($datetime,5,2);
    $month = $Months[$month];
    $year = substr($datetime,0,4);
    
    $date = "$day $month $year";

    return $date;
  }

  // Datetime format YYYY-MM-DD HH:MM:SS
  function convertDatetimeToScreenDate($datetime) {

    $Months = array('01'=>"Jan",
                    '02'=>"Feb",
                    '03'=>"Mar",
                    '04'=>"Apr",
                    '05'=>"May",
                    '06'=>"Jun",
                    '07'=>"Jul",
                    '08'=>"Aug",
                    '09'=>"Sep",
                    '10'=>"Oct",
                    '11'=>"Nov",
                    '12'=>"Dec");
    $day = substr($datetime,8,2);
    $month = substr($datetime,5,2);
    $month = $Months[$month];
    $year = substr($datetime,0,4);
    $hours = substr($datetime,11,2);
    $mins = substr($datetime,14,2);

    $date = "$day $month $year $hours:$mins";

    return $date;
  }

  /*****************************************************************
   * Forward to the given address
   * @url the address to go to.
   *****************************************************************/
  function forward($url) {
    /* Redirect browser */
    header("Location: $url"); 
    /* Make sure that code below does not get executed when we redirect. */
    exit; 
  }
?>
