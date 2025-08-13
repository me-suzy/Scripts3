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

// ------------------------------------------------------------------
// function for updating any replies in this branch
   function update_replies($number="",$moved="",$Keyword_q="",$total="",$newmain="",$lastposted="") {

      global $dbh, $config;

   // --------------------------------------------------
   // Query to see if there are any replies to this post
      $query = "
        SELECT B_Number,B_Main,B_Board,B_Posted
        FROM   {$config['tbprefix']}Posts
        WHERE  B_Parent = $number
      ";
      $sth = $dbh -> do_query($query);
      $followups = $dbh -> total_rows($sth);
      if ($followups) {
         for ( $x=0; $x<$followups; $x++) {
            list ($postcheck,$Main,$Board,$lastp) = $dbh -> fetch_array($sth);
            if ($lastp > $lastposted) {
               $lastposted = $lastp;
            }
            if ($postcheck) {
               list($moved,$total,$lastposted) = update_replies($postcheck,$moved,$Keyword_q,$total,$newmain,$lastposted);
            }
            if (!ereg("$number",$moved)) {
               $query = "
                UPDATE {$config['tbprefix']}Posts
                 SET    B_Main = $newmain,
                       B_Board = '$Keyword_q'
                WHERE  B_Number = $number
               ";
               $dbh -> do_query($query);
               $moved .= "-$number-";
               $total++;
            }
        }
     }
     else {
        if (!ereg("$number",$moved)) {
           $query = "
            UPDATE {$config['tbprefix']}Posts
            SET    B_Main = $newmain,
                   B_Board = '$Keyword_q'
            WHERE  B_Number = $number
           ";
           $dbh -> do_query($query);
           $moved .= "-$number-";
           $total++;
         }
      }

      return array($moved,$total,$lastposted);

   }
    
            

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

// -------------------------------------------------
// If we didn't get a keyword, then we don't move it
   if (!$Keyword) {
      $html -> not_right("No forum was selected to move this thread to.");
   }
   if ($Keyword == "category") {
      $html -> not_right("You need to choose a forum, not a category.");
   }

// ------------------------------------------------------------------
// Find out the current Main number for this post and current subject
   $query = "
    SELECT B_Main,B_Subject,B_Posted
    FROM   {$config['tbprefix']}Posts
    WHERE  B_Number = $number
   ";
   $sth = $dbh -> do_query($query);
   list($main,$subject,$lastposted) = $dbh -> fetch_array($sth);

// ---------------------------------------------------------------------
// If there are any unapproved posts in this thread then we can't move it
   $query = "
    SELECT B_Approved 
    FROM   {$config['tbprefix']}Posts
    WHERE  B_Main = $main
   ";
   $sth = $dbh -> do_query($query);
   while ( list($check) = $dbh -> fetch_array($sth)) {
      if ($check == "no") {
         $html -> not_right("You cannot move a branch of a thread that has unapproved posts in it.",$Cat);
      }
   }

// ------------------------------------------------------------------
// Move the initial post to the new board but if it has an Re: in it
// We need to get rid of that first
   $subject = preg_replace("/^Re:/","",$subject);

   $Keyword_q  = addslashes($Keyword);
   $oldboard_q = addslashes($oldboard);
   $subject_q  = addslashes($subject);
   $query = "
    UPDATE {$config['tbprefix']}Posts 
    SET    B_Board  = '$Keyword_q',
           B_Main   = '$number',
           B_Subject= '$subject_q',
			  B_ParentUser = '',
           B_Parent = '',
           B_Topic  = 1
    WHERE  B_Number   = $number
   ";
   $dbh -> do_query($query);

// ------------------------------------------------
// Now we need to change all of the replies as well
   list($moved,$total,$lastposted) = update_replies($number,$number,$Keyword_q,1,$number,$lastposted);

// Update the Bo_Last field for the board this thread is getting moved to
// as long as the new time is greater than the old time
   $query = " 
    UPDATE {$config['tbprefix']}Boards
    SET    Bo_Last = $lastposted
    WHERE  Bo_Keyword = '$Keyword_q'
    AND    Bo_Last < $lastposted
   ";
   $dbh -> do_query($query);

// ----------------------------------------------------------
// Update the last post for this new thread and total replies
   $totr = $total - 1;
   $query = " 
    UPDATE {$config['tbprefix']}Posts
    SET    B_Last_Post = $lastposted,
           B_Replies   = $totr
    WHERE  B_Number = $number
   ";
   $dbh -> do_query($query);

// ---------------------------------------------
// Now update the number of posts on both boards
// --------------------------------------------------------------
// If this is the last post for this board then we need to update
// the data for the main display
   $query = "
         SELECT B_Username,B_Main,B_Number
         FROM {$config['tbprefix']}Posts
         WHERE B_Board = '$oldboard_q'
         AND   B_Approved = 'yes'
         ORDER BY B_Number DESC
         LIMIT 1
   ";
   $sth = $dbh -> do_query($query);
   list ($lastuser,$lastmain,$lastnumber) = $dbh -> fetch_array($sth);
   $lastuser = addslashes($lastuser);

   $query = "
    UPDATE {$config['tbprefix']}Boards
    SET    Bo_Total = Bo_Total - $total,
           Bo_Poster  = '$lastuser',
           Bo_LastMain = '$lastmain',
           Bo_LastNumber = '$lastnumber'
    WHERE  Bo_Keyword = '$oldboard_q'
   ";
   $dbh -> do_query ($query);

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
    SET    Bo_Total = Bo_Total + $total,
           Bo_Threads = Bo_Threads + 1,
           Bo_Poster  = '$lastuser',
           Bo_LastMain = '$lastmain',
           Bo_LastNumber = '$lastnumber'
    WHERE  Bo_Keyword = '$Keyword_q'
   ";
   $dbh -> do_query ($query);

// ---------------------------------------------------------
// Now update the total number of replies for the old thread
   $query = "
    UPDATE {$config['tbprefix']}Posts
    SET    B_Replies = B_Replies - $total
    WHERE  B_Number = $main
   ";
   $dbh -> do_query($query);

// ------------------------
// Send them a page 
   $html -> send_header ("Posts moved",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&Board=$Keyword&view=$view&sb=$sb&o=$o\">",$user);
   $html -> admin_table_header("Posts moved");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The branch of posts have been moved to the specified forum.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();	



?>
