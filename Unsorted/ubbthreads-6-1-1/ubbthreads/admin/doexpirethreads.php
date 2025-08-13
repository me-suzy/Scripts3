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

// --------------------------------------
// If this is a single thread, we do this
   if ($Option == "single") {
      $Total = 1;
      $HTTP_POST_VARS['1'] = $Number;
   }

// --------------------------------------------------------
// Lets cycle through the list and close the proper threads
   $totalexpired = 0;
   $totalna = 0;
   $totalt = 0;
   for ( $i=1;$i<=$Total;$i++){
      $Closed_q = "K";
      $Keyword_q = addslashes($Keyword);

      if( $HTTP_POST_VARS[$i] ) {
         $totalt++; // Thread counter

      // ---------------------------------------------------------------------
      // Find out if there are any files to delete and if the post is approved
         $query = "
           SELECT B_Number
           FROM  {$config['tbprefix']}Posts 
           WHERE B_Main = '$HTTP_POST_VARS[$i]' 
           AND   B_Board = '$Keyword_q'
         ";
         $sti = $dbh -> do_query($query);
         while ( list($checkee) = $dbh -> fetch_array($sti)) {
            $query = "
             SELECT B_File,B_Approved,B_Poll
             FROM {$config['tbprefix']}Posts 
             WHERE B_Number = '$checkee'
             AND   B_Board  = '$Keyword_q'
            ";
            $stk = $dbh -> do_query($query);
            list($filename,$Approved,$Poll) = $dbh -> fetch_array($stk); 
            $dbh -> finish_sth($stk);
            if ($filename) {
               @unlink("{$config['files']}/$filename");
            }

         // ------------------------------------------
         // If we have a poll for this post, delete it
            if ($Poll) {
               $Poll = addslashes($Poll);
               $query = "
                 DELETE FROM {$config['tbprefix']}Polls
                 WHERE  P_Name = '$Poll'
               ";
               $dbh -> do_query($query);
               $query = "
                 DELETE FROM {$config['tbprefix']}PollData
                 WHERE  P_Name = '$Poll'
               ";
               $dbh -> do_query($query);
            }

            if ($Approved == "no") {
               $totalna++;
            }
         }
         $dbh -> finish_sth($sti);

         $query = "
            SELECT COUNT(*) FROM {$config['tbprefix']}Posts
            WHERE  B_Main = '$HTTP_POST_VARS[$i]'
         ";
         $stm = $dbh -> do_query($query);
         list($rc) = $dbh -> fetch_array($stm);
         $dbh -> finish_sth($stm); 

         $query = "
           DELETE FROM {$config['tbprefix']}Posts 
           WHERE B_Main = '$HTTP_POST_VARS[$i]'
         ";
         $dbh -> do_query($query);
         $totalexpired = $totalexpired + ($rc - $totalna);
      }

   }

// --------------------------------------------------------------
// Need to find out what the new last post is for this board
   $query = "
         SELECT B_Username,B_Main,B_Number
         FROM {$config['tbprefix']}Posts
         WHERE B_Board = '$Keyword_q'
         AND   B_Approved = 'yes'
         ORDER BY B_Number DESC
         LIMIT 1
   ";
   $sth = $dbh -> do_query($query);
   list ($lastuser,$lastmain,$lastnumber) = $dbh -> fetch_array($sth);
   $lastuser = addslashes($lastuser);

// ----------------------------------------------
// Now we update the board to set the total posts
   $Keyword_q = addslashes($Keyword);
   $lastuser  = addslashes($lastuser);
   $query = "
    UPDATE {$config['tbprefix']}Boards
    SET Bo_Total   = Bo_Total - $totalexpired,
        Bo_Threads = Bo_Threads - $totalt,
        Bo_Poster  = '$lastuser',
        Bo_LastNumber = '$lastnumber',
        Bo_LastMain   = '$lastmain'
    WHERE Bo_Keyword = '$Keyword_q'
   ";
   $dbh -> do_query($query);
 
// ------------------------
// Send them a page 
   if (!$from) {
      $html -> send_header ("Threads expired.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   }
   else {
     $html -> send_header ("Threads expired.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&Board=$Keyword&page=$page&view=$view&sb=$sb&o=$o\">",$user);
   }
   $html -> admin_table_header("Threads expired.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "All threads you marked to expire have been purged from the database.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
