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

// --------------------------------------
// Delete the board from the boards table 
   $Keyword_q      = addslashes($Keyword);
   $query = "
    DELETE FROM {$config['tbprefix']}Boards
    WHERE Bo_Number = '$Number'
   ";
   $dbh -> do_query($query);


// --------------------------------------
// Delete the board from the subscribe table 
   $query = "
    DELETE FROM {$config['tbprefix']}Subscribe 
    WHERE  S_Board = '$Keyword_q'
   ";
   $dbh -> do_query($query);

// -----------------------------------------------------------
// Delete all posts for this board from the {$config['tbprefix']}Posts database 
   $query = "
    DELETE FROM {$config['tbprefix']}Posts
    WHERE       B_Board = '$Keyword_q'
   ";
   $dbh -> do_query($query);

// ---------------------------------------------------------------
// Delete all entries in {$config['tbprefix']}Last that relate to this board 
   $query = "
    DELETE FROM {$config['tbprefix']}Last
    WHERE       L_Board = '$Keyword_q'
   ";
   $dbh -> do_query($query);

// --------------------------------------------------------------------
// Delete the entries in the moderator table associated with this board
   $query = "
    DELETE FROM {$config['tbprefix']}Moderators
    WHERE Mod_Board = '$Keyword_q'
   ";
   $dbh -> do_query($query);

// ------------------------
// Send them a confirmation
   $html -> send_header ("The forum has been deleted.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("The forum has been deleted.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><SPAN CLASS=\"onbody\">";  
   echo "The forum has been deleted.  All database entries associated with this forum have been purged as well.";
   echo "</SPAN></TD></TR></TABLE>";
   $html -> send_admin_footer();
 
?>
