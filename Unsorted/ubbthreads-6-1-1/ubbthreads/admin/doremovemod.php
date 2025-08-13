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

// ----------------------------------
// Find out what boards they moderate 
 
   $Username_q = addslashes($Moderator);
   $query = "
    SELECT Mod_Board
    FROM   {$config['tbprefix']}Moderators
    WHERE  Mod_Username = '$Username_q' 
   ";
   $sth = $dbh -> do_query($query);

   while ( list($Board) = $dbh -> fetch_array($sth)) {
      if ($HTTP_POST_VARS[$Board]) {
         $Board_q    = addslashes($Board);
         $query = "
            SELECT Bo_Moderators
            FROM   {$config['tbprefix']}Boards
            WHERE  Bo_Keyword = '$Board_q'
         ";
         $sti = $dbh -> do_query($query);
         list($mods) = $dbh -> fetch_array($sti);
         $newmods = str_replace(",$Moderator,",",",$mods);
         $query = "
            UPDATE {$config['tbprefix']}Boards
            SET    Bo_Moderators = '$newmods'
            WHERE  Bo_Keyword = '$Board_q'
         ";
         $dbh -> do_query($query);

         $query = "
           DELETE FROM {$config['tbprefix']}Moderators
           WHERE Mod_Username = '$Username_q'
           AND   Mod_Board    = '$Board_q'
         ";
       $dbh -> do_query($query);
      }
   }
   $dbh -> finish_sth($sth);

   $html -> send_header ("Moderator privileges revoked.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Moderator privileges revoked.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";
   echo "Moderator privileges revoked.  You will now be returned to the main administration page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer(); 

?>
