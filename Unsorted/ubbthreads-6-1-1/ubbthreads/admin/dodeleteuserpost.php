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

// ----------------------
// Check replies function
   function check_replies($postnum="",$deleted="",$Board="",$Main="") {

      global $dbh;

   // --------------------------------------------------
   // query to see if there are any replies to this post
      $query = "
         SELECT B_Number,B_Main,B_Board
         FROM   {$config['tbprefix']}Posts
         WHERE  B_Parent = $postnum
      ";
      $sth = $dbh -> do_query($query);
      $followups = $dbh -> total_rows($sth);

      if ($followups) {
         for ( $x=0; $x<$followups; $x++) {

            list ($postcheck,$Main,$Board) = $dbh -> fetch_array($sth);
            if ($postcheck) {
               $deleted = check_replies($postcheck,$deleted,$Board,$Main);
            }
            if (!ereg("$postnum",$deleted)) {
            // -------------------------------
            // No replies, let's get rid of it
               $query = "
                 DELETE FROM {$config['tbprefix']}Posts
                 WHERE  B_Number = $postnum
               ";
               $dbh -> do_query($query);
    
            // -------------------------------------
            // Update the total posts for this board
               $Keyword_q = addslashes($Board);
               $query = "
                 UPDATE {$config['tbprefix']}Boards
                 SET    Bo_Total = Bo_Total - 1
                 WHERE  Bo_Keyword = '$Keyword_q'
               ";
               $dbh -> do_query($query);
    
            // ----------------------------------------------------------------
            // Update the main post in this thread if needed to set the proper 
            // number of replies 
               if ($Main != $postcheck) {
                  $query = "
                    UPDATE {$config['tbprefix']}Posts
                    SET    B_Replies = B_Replies - 1
                    WHERE  B_Number  = $Main
                  ";
                  $dbh -> do_query($query);
               }
               $deleted .= "-$postnum-";
            }
         }
      }
      else {
         if (!ereg("$postnum",$deleted)) {

         // ---------------------------------
         // Let's see if this is a main topic
            $query = "
              SELECT B_Topic
              FROM   {$config['tbprefix']}Posts
              WHERE  B_Number = $postnum
            ";
            $sti = $dbh -> do_query($query);
            list($topic) = $dbh -> fetch_array($sti);
      
         // -------------------------------
         // No replies, let's get rid of it
            $query = "
               DELETE FROM {$config['tbprefix']}Posts
               WHERE  B_Number = $postnum
            ";
            $dbh -> do_query($query);
    
         // -------------------------------------
         // Update the total posts for this board
         // If $topic is 1 we need to decrease the thread total as well
            $extra = "";
            if ($topic) {
              $extra = ", Bo_Threads = Bo_Threads - 1";
            }
            $Keyword_q = addslashes($Board);
            $query = "
               UPDATE {$config['tbprefix']}Boards
               SET    Bo_Total = Bo_Total - 1
               $extra
               WHERE  Bo_Keyword = '$Keyword_q'
            ";
            $dbh -> do_query($query);
  
         // ----------------------------------------------------------------
         // Update the main post in this thread if needed to set the proper 
         // number of replies 
            if ($Main != $postnum) {
               $query = "
                 UPDATE {$config['tbprefix']}Posts
                 SET    B_Replies = B_Replies - 1
                 WHERE  B_Number  = $Main
               ";
               $dbh -> do_query($query);
            }
            $deleted .= "-$postnum-";
         }
      }

      return $deleted;
   }


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

// ----------------------------------------------
// Ok, first we grab all posts made by this user
   $Who_q = addslashes($Who);
   $query = "
    SELECT B_Number,B_Main,B_Board
    FROM   {$config['tbprefix']}Posts
    WHERE  B_Username = '$Who_q'
    ORDER  BY B_Number
   ";
   $sth = $dbh -> do_query($query);

   while ( list($Number,$Main,$Board) = $dbh -> fetch_array($sth)) {

   // -------------------------------------------------------------------------
   // If we are doing a safe delete then we only fully delete the post if there
   // are no replies to it
      if ($deletetype == "safe") { 
         $query = "
           SELECT B_Number
           FROM   {$config['tbprefix']}Posts
           WHERE  B_Parent = $Number
         ";
         $sti = $dbh -> do_query($query);
         list($check) = $dbh -> fetch_array($sti);
    
         if (!$check) {

         // ---------------------------------
         // Let's see if this is a main topic
            $query = "
              SELECT B_Topic
              FROM   {$config['tbprefix']}Posts
              WHERE  B_Number = $Number
            ";
            $sti = $dbh -> do_query($query);
            list($topic) = $dbh -> fetch_array($sti);

         // -------------------------------
         // No replies, let's get rid of it
            $query = "
               DELETE FROM {$config['tbprefix']}Posts
               WHERE  B_Number = $Number
            ";
            $dbh -> do_query($query);

         // -------------------------------------
         // Update the total posts for this board
         // If $topic is set to 1 then we decrement the thread total as well
            if ($topic) {
               $extra = ",Bo_Threads = Bo_Threads - 1";
            }
            $Keyword_q = addslashes($Board);
            $query = "
               UPDATE {$config['tbprefix']}Boards
               SET    Bo_Total = Bo_Total - 1
               $extra
               WHERE  Bo_Keyword = '$Keyword_q'
            ";
            $dbh -> do_query($query);

         // -------------------------------------------------------------------
         // Update the main post in this thread if needed to set the proper 
         // number of replies 
            if ($Main != $Number) {
               $query = "
                 UPDATE {$config['tbprefix']}Posts
                 SET    B_Replies = B_Replies - 1
                 WHERE  B_Number  = $Main
               ";
               $dbh -> do_query($query);
            }

         } else {
         // ----------------------------------------
         // There are replies, so we can't delete it
            $Subject_q = addslashes("Post deleted by {$user['U_Username']}");
            $query = "
               UPDATE {$config['tbprefix']}Posts
               SET    B_Subject = '$Subject_q',
                      B_Body    = '',
                      B_File    = ''
               WHERE  B_Number = $Number
            ";  
            $dbh -> do_query($query);
         }

      } else {
      // -------------------------------------------------------
      // Otherwise we don't want any trace of this user, at all!

      // -------------------------------------------------
      // First get rid of any and all replies to this post
         $deleted = check_replies($Number,$deleted,$Board,$Main); 
      }
   }

// ------------------------
// Send them a page 
   $html -> send_header ("Posts deleted",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Posts deleted");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The posts made by this user have been deleted.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
