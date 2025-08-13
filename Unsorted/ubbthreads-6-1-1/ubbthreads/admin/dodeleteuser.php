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

// ---------------------------------
// Make sure they are should be here
   if ($user['U_Status'] != 'Administrator'){
      $html -> not_right ("You must be logged in, and be a valid administrator to access this.",$Cat);
   }

// -----------------------------------
// Make sure this isn't the first user
   $User_q = addslashes($User);
   $query = " 
    SELECT U_Number
    FROM  {$config['tbprefix']}Users
    WHERE U_Username = '$User_q'
   ";
   $sth = $dbh -> do_query($query); 
   list($Number) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if ($Number <= '2') {
      $html -> not_right("You cannot view or edit the first Administrator",$Cat);
   }  

// --------------------------------------
// Delete the user from the users table 
   $query = " 
    DELETE FROM {$config['tbprefix']}Users 
    WHERE U_Username = '$User_q' 
   "; 
   $dbh -> do_query($query);

// Update all old posts to the placeholder user
   $query = "
    UPDATE {$config['tbprefix']}Posts
    SET   B_PosterId = 1
    WHERE B_Username = '$User_q'
   ";
   $dbh -> do_query($query);

// ----------------------------------------
// Delete all entries in the Subscribe table
   $query = " 
    DELETE FROM {$config['tbprefix']}Subscribe 
    WHERE S_Username = '$User_q' 
   ";
   $dbh -> do_query($query);

// ----------------------------------------------
// Delete any private messages for this user
   $query = " 
    DELETE FROM {$config['tbprefix']}Messages
    WHERE M_Username = '$User_q' 
   ";
   $dbh -> do_query($query); 

// ----------------------------------------------------------------------
// Grab all the boards so we can get rid of the entries for this board in
// the individual board last viewed table
   $query = " 
      DELETE FROM {$config['tbprefix']}Last 
      WHERE L_Username = '$User_q'
   ";
   $dbh -> do_query($query); 

// ------------------------------------------
// Delete this user from the moderators table
   $query = "
      SELECT Bo_Moderators,Bo_Keyword
      FROM   {$config['tbprefix']}Boards
      WHERE  Bo_Moderators LIKE '%,$User_q,%'
   ";
   $sth = $dbh -> do_query($query);
   while(list($mods,$modboard) = $dbh -> fetch_array($sth)) {
     $newmods = str_replace(",$User,",",",$mods);
     $newmods = addslashes($newmods);
     $modboard = addslashes($modboard);
     $query = "
      UPDATE {$config['tbprefix']}Boards
      SET    Bo_Moderators = '$newmods'
      WHERE  Bo_Keyword = '$modboard'
     ";
     $dbh -> do_query($query);
   }
   $query = "
     DELETE FROM {$config['tbprefix']}Moderators
     WHERE  Mod_Username = '$User_q'
   ";
   $dbh -> do_query($query);

// ------------------------
// Send them a confirmation
   $html -> send_header ("The user has been deleted.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("The user has been deleted.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The user has been deleted.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();
