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

// -----------------
// Check for a value
	if (!$inactivity) {
		$html -> not_right("You must supply a value in the inactivity field.",$Cat);
	}
// ---------------------------------------------
// Now lets convert the number of days to seconds
   $seconds = $inactivity * 86400;

// ------------------------------------
// Now grab the current date in seconds
   $date = $html -> get_date();

// -----------------------------------
// Now we figure the actual purge time
   $purge = $date - $seconds;

// -----------------------
// Now we delete the users
   $query = " 
    SELECT U_Username
    FROM   {$config['tbprefix']}Users
    WHERE  U_Laston <= $purge
    AND    U_Number > 2
   "; 
   $sth = $dbh -> do_query($query);
   $usercount = 0;
   while (list ($DelUser) = $dbh -> fetch_array($sth)) {
      $usercount++;
      $Username_q = addslashes($DelUser);
 
   // UPDATE ALL OLD POSTS TO THE PLACEHOLDER USER
      $query = "
        UPDATE {$config['tbprefix']}Posts
        SET    B_PosterId = 1
        WHERE  B_Username='$Username_q'
      ";
      $dbh -> do_query($query);
 
   // -------------------------------
   // Delete from the Subscribe table
      $query = " 
        DELETE FROM {$config['tbprefix']}Subscribe
        WHERE  S_Username = '$Username_q'
      ";
      $dbh -> do_query($query);

   // --------------------------------
   // Delete them from the Users table
      $query = " 
         DELETE FROM {$config['tbprefix']}Users
         WHERE  U_Username = '$Username_q'
      "; 
      $dbh -> do_query($query);

   // --------------------------------
   // Delete them from the Last table
      $query = " 
         DELETE FROM {$config['tbprefix']}Last
         WHERE  L_Username = '$Username_q'
      "; 
      $dbh -> do_query($query);

   // ----------------------------------------------
   // Delete any private messages for this user   
      $query = "    
         DELETE FROM {$config['tbprefix']}Messages    
         WHERE M_Username = '$Username_q'   
      ";   
      $dbh -> do_query($query);

    // ----------------------------------------------
    // Delete  them from the moderators table   
      $query = "
        SELECT Bo_Moderators,Bo_Keyword 
        FROM   {$config['tbprefix']}Boards
        WHERE  Bo_Moderators LIKE '%,$Username_q,%'
      ";
      $sti = $dbh -> do_query($query);
      while(list($mods,$modboard) = $dbh -> fetch_array($sti)) {
         $modboard = addslashes($modboard);
         $newmods = str_replace(",$User,",",",$mods);
         $newmods = addslashes($newmods);
         $query = "
            UPDATE {$config['tbprefix']}Boards
            SET    Bo_Moderators = '$newmods'
            WHERE  Bo_Keyword = '$modboard'
         ";
         $dbh -> do_query($query);
      }

      $query = "    
         DELETE FROM {$config['tbprefix']}Moderators
         WHERE Mod_Username = '$Username_q'   
      ";   
      $dbh -> do_query($query);

   }
   $dbh -> finish_sth($sth);

// ------------------------
// Send them a page
   $html -> send_header ("Deletion finished",$Cat,0,$user);
   $html -> admin_table_header("Deletion finished");

   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";
   echo "A total of $usercount users were deleted from the database.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();
