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

// -------------------------------------------------
// If we didn't get a keyword, then we don't move it
   if (!$Keyword) {
      $html -> not_right("No forum was selected to move this thread to.");
   }
   if ($Keyword == "category") {
      $html -> not_right("You need to choose a forum, not a category.");
   }
	if (!$number) {
		$html -> not_right("Unfortunately we didn't receive a post number to be moved.");
	}

// ---------------------------------------------------------------------
// If there are any unapproved posts in this thread then we can't move it
   $query = "
    SELECT B_Approved 
    FROM   {$config['tbprefix']}Posts
    WHERE  B_Main = $number
   ";
   $sth = $dbh -> do_query($query);
   while ( $check = $dbh -> fetch_array($sth)) {
      if ($check == "no") {
         $dbh -> finish_sth($sth);
         $html -> not_right("You cannot move a thread that has unapproved posts in it.",$Cat);
      }
   }
   $dbh -> finish_sth($sth);

// --------------------------------
// Move the thread to the new board 
   $Keyword_q  = addslashes($Keyword);
   $oldboard_q = addslashes($oldboard);
   $query = "
    SELECT COUNT(*)
    FROM   {$config['tbprefix']}Posts
    WHERE  B_Board = '$oldboard_q'
    AND    B_Main  = '$number'
   ";
   $sth = $dbh -> do_query($query);
   list($moved) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   $query = "
    UPDATE {$config['tbprefix']}Posts 
    SET    B_Board  = '$Keyword_q'
    WHERE  B_Main   = '$number'
    AND    B_Board  = '$oldboard_q'
   ";
   $dbh -> do_query($query);

// ---------------------------------------------------------
// Grab the time that the last reply was made on this thread
   $query = "
    SELECT B_Last_Post
    FROM   {$config['tbprefix']}Posts
    WHERE  B_Number = '$number'
   ";
   $sth = $dbh -> do_query($query);
   list ($lastpost) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// Update the Bo_Last field for the board this thread is getting moved to
// as long as the new time is greater than the old time
   $query = "
    UPDATE {$config['tbprefix']}Boards
    SET    Bo_Last = $lastpost
    WHERE  Bo_Keyword = '$Keyword_q'
    AND    Bo_Last < $lastpost
   ";
   $dbh -> do_query($query);
 
// ------------------------------------------------------------
// Now update the number of posts on both boards and other info
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
    SET    Bo_Total = Bo_Total - $moved,
           Bo_Threads = Bo_Threads - 1,
           Bo_Poster = '$lastuser',
           Bo_LastMain = '$lastmain',
           Bo_LastNumber = '$lastnumber'
    WHERE  Bo_Keyword = '$oldboard_q'
   ";
   $dbh -> do_query($query);

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
    SET    Bo_Total = Bo_Total + $moved,
           Bo_Threads = Bo_Threads + 1,
           Bo_Poster = '$lastuser',
           Bo_LastMain = '$lastmain',
           Bo_LastNumber = '$lastnumber'
    WHERE  Bo_Keyword = '$Keyword_q'
   ";
   $dbh -> do_query($query);

// ------------------------
// Send them a page 
   $html -> send_header ("Thread moved",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&Board=$Keyword&view=$view&sb=$sb&o=$o\">",$user);
   $html -> admin_table_header("Thread moved");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The thread has been moved to the specified forum.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();	

?>
