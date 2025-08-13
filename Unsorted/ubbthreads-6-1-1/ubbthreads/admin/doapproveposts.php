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

// ---------------------------------
// Escape out any special characters
   $Keyword_q = addslashes($Keyword);


// --------------------------------------------------------
// Lets cycle through the list and approve/delete the proper threads
   $totalapproved = 0;
   $threadtotal   = 0;

// --------------------------------------------------------------
// Cycle through everything 
   for ( $i=1;$i<=$Total;$i++){
    
      $check    = "Thread$i";

      if( $HTTP_POST_VARS[$check] ) {

      // ----------------------------------------------
      // Let's see if this is an approval or a deletion
         list($option,$Number) = split("-",$HTTP_POST_VARS[$check]);

         if ($option == "APPROVE") {
         // ---------------------------------
         // We need to see if this is a reply
            $query = "
               SELECT B_Main,B_Posted
               FROM   {$config['tbprefix']}Posts
               WHERE  B_Number = $Number
               AND    B_Board  = '$Keyword_q'
            ";
            $sth = $dbh -> do_query($query);
            list($Main,$PostedOn) = $dbh -> fetch_array($sth);

         // ---------------------------------------------
         // If this is a reply we bump up the reply total
            if ($Main != $Number) {
               $query = "
                  UPDATE {$config['tbprefix']}Posts
                  SET    B_Replies = B_Replies + 1
                  WHERE  B_Number  = $Main
               ";
               $dbh -> do_query($query);
            }

         // -------------------------------------------------------
         // If this isn't a reply then we bump the threadtotal by 1
            if ($Main == $Number) {
               $threadtotal++;
            }

         // ------------------------
         // Set the post to approved
            $query = "
               UPDATE {$config['tbprefix']}Posts 
               SET    B_Approved = 'yes'
               WHERE  B_Number = $Number
               AND    B_Board  = '$Keyword_q'
            ";
            $rc = $dbh -> do_query ($query);
            $totalapproved++;

         // Grab some info
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
 
            $query = "
              UPDATE {$config['tbprefix']}Boards
              SET    Bo_Poster = '$lastuser',
	             Bo_LastMain = '$lastmain',
                     Bo_LastNumber = '$lastnumber', 
                     Bo_Last = '$PostedOn'
              WHERE  Bo_Keyword = '$Keyword_q'
            ";
            $dbh -> do_query($query); 

         }
         else {

         // -------------------------------------------------  
         // Looks like they are deleting it. so let's do that 
            $query = "
               SELECT B_File,B_Poll
               FROM {$config['tbprefix']}Posts 
               WHERE B_Number = $Number
               AND   B_Board  = '$Keyword_q'
            ";
            $stk = $dbh -> do_query($query);
            list($filename,$Poll) = $dbh -> fetch_array($stk);
            if ($filename) {
               unlink("{$config['files']}/$filename");
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

            $query = "
               DELETE FROM {$config['tbprefix']}Posts 
               WHERE B_Number = $Number 
               AND   B_Board  = '$Keyword_q'
            ";
            $dbh -> do_query($query);
         }
      }
   }
 
// ----------------------------------------------
// Now we update the board to set the total posts
   $query = "
    UPDATE {$config['tbprefix']}Boards
    SET Bo_Total = Bo_Total + $totalapproved,
        Bo_Threads = Bo_Threads + $threadtotal
    WHERE Bo_Keyword = '$Keyword_q'
   ";
   $dbh -> do_query ($query);

// ------------------------
// Send them a page 
   $html -> send_header ("Posts approved/deleted.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Posts approved/deleted.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"onbody\">";  
   echo "All checked posts have been approved or deleted.  You will now be returned to the main administration page.";
   echo "</TD></TR></TABLE>";
   $html -> send_admin_footer();


?>
