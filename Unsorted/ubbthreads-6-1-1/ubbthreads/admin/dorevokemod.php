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

// ------------------------------
// Grab this users current groups
   $Username_q = addslashes($Modname);
   $query = "
    SELECT U_Groups
    FROM   {$config['tbprefix']}Users
    WHERE  U_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);
   list($Groups) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// --------------------------------------------------
// Update this user's status and groups
   $Groups = str_replace("-2-","-",$Groups);
   $Groups_q = addslashes($Groups);
   $user_q = "User";
   $query = "
    UPDATE {$config['tbprefix']}Users 
    SET   U_Status   = '$user_q',
          U_Color    = '',
          U_Groups   = '$Groups_q'
    WHERE U_Username = '$Username_q'     
   ";
   $dbh -> do_query($query);

// ---------------------------------------------------------------
// Send the user a message letting them know they have been demoted 

   $Sender  = $user['U_Username'];
   $To      = $Modname;
   $Subject = "Your privileges";
   $mess    = '';
   $mess    = "Your Moderator privileges have been revoked.  You have been returned to user status.";
   $html -> send_message('$Sender','$To','$Subject','$mess');

// ------------------------------------
// Now inform all other Admins and mods
   $Sender  = $user['U_Username'];
   $Subject = "Moderator demoted";
   $mess    = "$Sender has revoked moderator privileges from $Modname.";
   $html -> send_message('$Sender','','$Subject','$mess','A_M_GROUP');
   
// -----------------------------------------
// Now delete them from the moderators group
   $query = "
       SELECT Bo_Moderators,Bo_Keyword
       FROM   {$config['tbprefix']}Boards
   ";
   $sth = $dbh -> do_query($query);
   while(list($mods,$board) = $dbh -> fetch_array($sth)) {
      $newmods = str_replace(",$Modname,",",",$mods);
      $query = "
            UPDATE {$config['tbprefix']}Boards
            SET    Bo_Moderators = '$newmods'
            WHERE  Bo_Keyword = '$board'
      ";
      $dbh -> do_query($query);
   }
   $query = "
    DELETE FROM {$config['tbprefix']}Moderators
    WHERE Mod_Username = '$Username_q'
   ";
   $dbh -> do_query($query);

// ------------------------
// Send them a confirmation
   $html -> send_header ("Moderator privileges revoked.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Moderator privileges revoked.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "Moderator privileges revoked.  You will now be returned to the main administration page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
