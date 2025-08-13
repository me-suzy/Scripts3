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
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// ------------------------------------------ 
// If this is just a single thread we do this
   $Board_q = addslashes($Keyword);
   $Closed_q = "C";
   if ($Option == "single") {
      $query = "
        UPDATE {$config['tbprefix']}Posts
        SET    B_Status = '$Closed_q'
        WHERE  B_Main   = '$Number'
      ";
      $dbh -> do_query($query);
   }
   else {
   // --------------------------------------------------------
   // Lets cycle through the list and close the proper threads
      for ( $i=1;$i<=$Total;$i++){
         if( $HTTP_POST_VARS[$i] ) {
            $query = "
              UPDATE {$config['tbprefix']}Posts 
              SET    B_Status = '$Closed_q'
              WHERE  B_Main   = '$HTTP_POST_VARS[$i]'
            ";
            $dbh -> do_query($query);
         }
      }
   }

// ----------------------------------------------------------------------------
// Send them a page  ( If they came from a thread we return them to the postlist
// screen, otherwise we return them to the admin section
   if (!$from) {
      $html -> send_header ("Threads closed",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   }
   else {
      $html -> send_header ("Threads closed",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&Board=$Keyword&page=$page&view=$view&sb=$sb&o=$o\">",$user);
   }

   $html -> admin_table_header("Threads closed");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "All of the threads that you checked have been marked as closed.  You will be returned to the forum shortly.";
   echo "</TD></TR></TABLE>";
   $html -> send_admin_footer();	

?>
