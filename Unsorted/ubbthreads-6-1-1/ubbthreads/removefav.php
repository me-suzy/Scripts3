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
   require ("./main.inc.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $html = new html;

// -----------------------------------------
// Delete this entry from the favorites table
   $Username_q = addslashes($user['U_Username']);

   for ( $i=0; $i < $total; $i++) {
      $value = "E$i";
      if ($HTTP_POST_VARS[$value]) {
         $query = "
            DELETE FROM {$config['tbprefix']}Favorites
            WHERE  F_Owner = '$Username_q'
            AND    F_Number = '$HTTP_POST_VARS[$value]' 
         ";
         $dbh -> do_query($query);
      }
   }

   if ($view == "all") {
      $which;
      if ($type == "r") {
         $which ="viewreminders.php";
      }
      else {
         $which = "viewfavorites.php";
      }
      header ("Location: {$config['phpurl']}/$which?Cat=$Cat&PHPSESSID=$PHPSESSID");
   }
   else {
      $html -> start_page($Cat); 
   }

?>
