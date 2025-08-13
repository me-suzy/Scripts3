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

   $Username_q = addslashes($OldAdmin);

// ------------------------------------------------------------
// Check if this was the first user added and grab their groups
   $query = "
    SELECT U_Number,U_Groups
    FROM  {$config['tbprefix']}Users
    WHERE U_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);
   list($Number,$Groups) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if ($Number == 1) {
      $html -> not_right("You cannot revoke Admin rights from the first user added.",$Cat);
   }


// -----------------------------------------------------
// Check to see if this User is a moderator of any board
   $query = "
    SELECT Mod_Username 
    FROM   {$config['tbprefix']}Moderators 
    WHERE  Mod_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);
   list($checkee)= $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if ($checkee) {
      $Status  = "Moderator";
      $newstat = "Moderator";
   }
   else {
      $Status = "User";
      $newstat = "User";
   }

// ------------------------------------------
// Update this user's status and their groups
   $Groups_q = str_replace("-1-","-",$Groups);
   $query = "
    UPDATE {$config['tbprefix']}Users 
    SET   U_Status   = '$newstat',
          U_Color    = '$newcolor',
          U_Groups   = '$Groups_q'
    WHERE U_Username = '$Username_q'     
   ";
   $dbh -> do_query($query);

// ---------------------------------------------------------------
// Send the user a message letting them know they have no admin privs
   $Sender  = $user['U_Username'];
   $To      = $OldAdmin;
   $Subject = "Your privileges";
   $mess    = '';
   if ($Status == "Moderator"){
      $mess    = "Your Administrative privileges have been revoked.  You have been returned to moderator status.";
   }
   else {
      $mess = "Your Administrative privileges have been revoked.  You have been returned to user status.";
   }
   $html -> send_message($Sender,$To,$Subject,$mess);


// ------------------------------------
// Now inform all other Admins and mods
   $Sender  = $user['U_Username'];
   $Subject = "Administrator demoted";
   $mess    = "$Sender has revoked Administrative privileges from $OldAdmin.";
   $html -> send_message($Sender,"",$Subject,$mess,"A_M_GROUP");
   

// ------------------------
// Send them a confirmation
   $html -> send_header ("Admin privileges revoked.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Admin privileges revoked.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "Admin privileges revoked.  You will now be returned to the main administration page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
