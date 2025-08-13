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

// ------------------------
// Send them a page 
  $html -> send_header ("Revoke moderator privileges.",$Cat,0,$user);
  $html -> admin_table_header("Revoke moderator privileges.");


// --------------------------
// Get all the moderators 
   $query = "
    SELECT U_Username
    FROM   {$config['tbprefix']}Users 
    WHERE  U_Status = 'Moderator'
    ORDER  BY U_Username
   ";
   $sth = $dbh -> do_query($query);
   $html -> open_admin_table();

   echo "<tr><td class=\"lighttable\">";
   echo "The following users have moderator privileges, but are not assigned to a forum.  If you wish to return any of these users to User status, select their Username and then click 'Revoke Rights'.";
   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/dorevokemod.php\">";
   echo "<INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>";
   echo "<select name = \"Modname\" size = 1 class=\"formboxes\">";
   while ( list($Mod) = $dbh -> fetch_array($sth)){
   
      $Username_q = addslashes($Mod);
      $query = "
       SELECT U_Status
       FROM   {$config['tbprefix']}Users
       WHERE  U_Username = '$Username_q'
      "; 
      $sti = $dbh -> do_query($query);
      list($check) = $dbh -> fetch_array($sti);
      $dbh -> finish_sth($sti);
      if ($check == "Administrator") { continue; }
      $query = "
       SELECT Mod_Username
       FROM  {$config['tbprefix']}Moderators
       WHERE Mod_Username = '$Username_q'
      ";
      $sti = $dbh -> do_query($query);
      list($check) = $dbh -> fetch_array($sti);
      $dbh -> finish_sth($sti);
      if ($check) { continue; }
      echo "<option>$Mod</option>\n";
   }
   $dbh -> finish_sth($sth);
   echo "</select>";
   echo "<br><br>";
   echo "<input type=submit value=\"Revoke Rights\" class=\"buttons\">";
   echo "</form>"; 

   $html -> close_table();
   $html -> send_admin_footer();

?>
