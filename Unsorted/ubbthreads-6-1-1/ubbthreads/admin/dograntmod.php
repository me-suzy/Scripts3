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

// -------------------------
// Grab their current groups
   $Username_q = addslashes($NewMod);
   $query = "
    SELECT U_Groups
    FROM   {$config['tbprefix']}Users
    WHERE  U_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);
   list($groups) = $dbh -> fetch_array($sth);
   if ( !ereg("-2-",$groups) ){
      $groups .="2-";
      $groups_q = addslashes($groups);
      $updategroups = ", U_Groups = '$groups_q'";
   }

// --------------------------------------------------
// Update this user's status
 
   $newstat    = addslashes("Moderator");
   $newcolor   = addslashes($theme['modcolor']);
   $query = "
    UPDATE {$config['tbprefix']}Users 
    SET    U_Status   = '$newstat',
           U_Color    = '$newcolor'
	   $updategroups
    WHERE  U_Username = '$Username_q'     
   ";
   $dbh -> do_query($query);

// ----------------------------------------------------------------
// Let's let all admins and moderators know about the new moderator 
   $Sender  = $user['U_Username'];
   $Subject = "New Moderator";
   $mess    = "$Sender has given Moderator privileges to $NewMod.";
   $html -> send_message($Sender,"",$Subject,$mess,"A_M_GROUP");

 
// ---------------------------------------------------------------
// Send the user a message letting them know they have mod privs

   $Sender  = $user['U_Username'];
   $To      = $NewMod;
   $Subject = "New Moderator";
   $mess    = "You have been granted Moderator privileges.  Please do not abuse this or these privileges may be revoked.";
   $html -> send_message($Sender,$To,$Subject,$mess); 

// ------------------------
// Send them a confirmation
   $html -> send_header ("Moderator privileges granted.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Moderator privileges granted.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "Moderator privileges granted.  You will now be returned to the main administration page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
