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
   require ("main.inc.php");
   require ("languages/${$config['cookieprefix']."w3t_language"}/deletepost.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $Username = $user['U_Username'];
   $html = new html;

   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// ------------------
// Get the forum info
   $Board_q    = addslashes($Board);
   $query = "
      SELECT Bo_SpecialHeader,Bo_StyleSheet,Bo_Moderators,Bo_LastNumber
      FROM   {$config['tbprefix']}Boards
      WHERE  Bo_Keyword='$Board_q'
   ";
   $sth = $dbh -> do_query($query);
   list ($fheader,$fstyle,$modlist,$lastnumber) = $dbh -> fetch_array($sth);

// -------------------------------------------------
// Here we need to figure out what stylesheet to use
   $mystyle = $user['U_StyleSheet'];
   if (!$mystyle) { $mystyle = "usedefault"; }
   if ($mystyle == "usedefault") {
      $mystyle = $fstyle;
      if ($mystyle == "usedefault") {
         $mystyle = $theme['stylesheet'];
      }
   }
// fstyle will now be a global variable to use in send_header
   $fstyle = $mystyle;

// -----------------------------------
// Get the post info from the database
	$Number = addslashes($Number);
   $query = "
    SELECT B_Username,B_Subject,B_Body,B_Approved,B_File,B_Poll,B_Main
    FROM  {$config['tbprefix']}Posts 
    WHERE B_Number = '$Number'
    AND   B_Board  = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);

// -------------------------
// Assign the retrieved data
   list($Postedby,$Subject,$Body,$Approved,$File,$Poll,$Main) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// ----------------------------------------------------------------------------
// Check if they moderate this board or they are an admin or they made the post

   if ( ($user['U_Username'] != $Postedby) && ($user['U_Status'] != "Administrator") && (!stristr($modlist,",$Username,")) ) {
      $html -> not_right($ubbt_lang['NO_EDIT'],$Cat);
   } 

// ----------------------------------------------------------------
// Find out if there are any file attachments so we can delete them.
   if ($File) {
      unlink("{$config['files']}/$File");
   }


// ------------------------------------------------------------------------
// If there are no replies to this post then we delete it from the database
   $query = "
    SELECT B_Number
    FROM  {$config['tbprefix']}Posts 
    WHERE B_Parent = '$Number'
    AND   B_Board  = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);
   list($rows) = $dbh -> fetch_array($sth); 
   $dbh -> finish_sth($sth);

   if (!$rows) {
      $query = " 
        DELETE FROM {$config['tbprefix']}Posts 
        WHERE B_Number = '$Number'
        AND   B_Board  = '$Board_q'
      ";
      $dbh -> do_query($query);
   
   // ----------------------------------------------------------
   // If this post is approved then we bump the number down by 1
      if ($Approved == "yes"){ 

      // --------------------------------------------------------------
      // If this is the last post for this board then we need to update
      // the data for the main display
         $query = "
               SELECT B_Username,B_Main,B_Number
               FROM {$config['tbprefix']}Posts
               WHERE B_Board = '$Board_q'
               AND   B_Approved = 'yes'
               ORDER BY B_Number DESC
               LIMIT 1
         ";
         $sth = $dbh -> do_query($query);
         list ($lastuser,$lastmain,$lastnumber) = $dbh -> fetch_array($sth);
         $lastuser = addslashes($lastuser);

      // ----------------------------------------------------------------
      // If this is a main post then we reduce the number of threads by 1
         if ($Main == $Number) {
            $extra = ",Bo_Threads = Bo_Threads - 1";
         }
         $Board_q = addslashes($Board);
         $query = "
            UPDATE {$config['tbprefix']}Boards
            SET    Bo_Total   = Bo_Total - 1,
                   Bo_Poster = '$lastuser',
                   Bo_LastMain = '$lastmain',
                   Bo_LastNumber = '$lastnumber'
            $extra
            $postextra
            WHERE  Bo_Keyword = '$Board_q'
         "; 
         $dbh -> do_query($query);

     
      }

   // ------------------------------------------------------------
   // We also need to decrease the number of replies in this topic
      $query = " 
        UPDATE {$config['tbprefix']}Posts
        SET    B_Replies = B_Replies -1
        WHERE  B_Number  = '$Main'
      ";
      $dbh -> do_query($query); 

   }
   else {
 
   // ------------------------------------------------
   // Set the subject to deleted and the body to blank
      $Subject = "$Subject *DELETED*"; 
      $Body = "{$ubbt_lang['POST_DEL']} $Username";

   // -------------------
   // Update the database
      $Body_q    =  addslashes($Body);
      $Subject_q = addslashes($Subject);
      $File_q    = "";
      $query = " 
        UPDATE {$config['tbprefix']}Posts
        SET    B_Subject = '$Subject_q', 
               B_Body    = '$Body_q', 
               B_File    = '$File_q'
        WHERE  B_Number  = '$Number'
        AND    B_Board   = '$Board_q'
      "; 
      $dbh -> do_query($query);

   }

// -----------------------------------------------------------
// If there is a poll for this post, delete it and all results
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

   $html -> send_header($ubbt_lang['POST_DEL_HEAD'],$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\" />",$user);

	if (!$debug) {
   	include ("$thispath/templates/$tempstyle/deletepost.tmpl");
	}
   $html -> send_footer();

