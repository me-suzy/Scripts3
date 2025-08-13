<?
/*
# UBB.threads, Version 6
# Official Release Date for UBB.threads Version6: 06/05/2002

# First version of UBB.threads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBB.threads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBB.threads, we at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/

// Require the library
   require ("../main.inc.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $html = new html;

// -----------------------------
// Make sure they should be here
   if ($user['U_Status'] != 'Administrator'){
      $html -> not_right ("You must be logged in, and be a valid administrator to access this.",$Cat);
   }

// ------------------------
// Send them a page 
   $html -> send_header ("SQL command results",$Cat,0,$user);
   $html -> admin_table_header("Results from your SQL command");
   $html -> open_admin_table();

   echo "<TR><TD CLASS=\"lighttable\">";
   echo "Here are the results of your command: \"$command\".<p>";

// ------------------------------------------------------------------
// If it was some type of a select command, we want to display things
// nicely
   if (preg_match("/SELECT|SHOW|EXPLAIN|DESCRIBE/i",$command)) {
      $sth = $dbh -> do_query($command);
      $numFields = $dbh -> num_fields($sth);
      $color = "lighttable";
      $html -> open_admin_table();
      echo "<tr class=\"darktable\">";
      for ( $i=0; $i<$numFields; $i++) {
          echo "<td>" . $dbh -> field_name($sth, $i) ."</td>";
      }
      echo "</tr>";
      while ( $vals = $dbh -> fetch_array($sth)) {
         echo "<tr class=$color>";
         $valsize = sizeof($vals);
         for ( $i=0; $i<$numFields; $i++) {
            echo "<td valign=top>$vals[$i]</td>";
         }
         echo "</tr>";
         $color = $html -> switch_colors($color);
      }
      echo "</tr></table>";
      echo "</td></tr></table>";
     
 
      $html -> open_admin_table();
      $color = "lighttable";
   }
   else {
      $sth = $dbh -> do_query($command);
      echo "$sth row(s) affected by your query.";
   }


   $html -> close_table();
   $html -> send_admin_footer();

?>
