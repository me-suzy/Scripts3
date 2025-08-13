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

// ------------------------
// Send them a page 
   $html -> send_header ("Remove a moderator",$Cat,0,$user);
   $html -> admin_table_header("Remove a moderator");


// --------------------------
// Get all the moderators 
   $query = "
    SELECT DISTINCT Mod_Username
    FROM   {$config['tbprefix']}Moderators 
    ORDER  BY Mod_Username
   ";
   $sth = $dbh -> do_query($query);

   $html -> open_admin_table();
   echo "<tr><td class=\"lighttable\">";
   echo "The following users are active moderators of 1 or more forums.  To remove them from a forum's moderator list click on their name to see which forums they moderate.";
   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/removemod2.php\">";
   echo "<INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>";
   echo "<select name = \"Moderator\" size = 1 class=\"formboxes\">";
   $Number;

   while ( list($Moderator) = $dbh -> fetch_array($sth)){
      echo "<option>$Moderator</option>\n";
   }
   $dbh -> finish_sth($sth);

   echo "</select>";
   echo "<br><br>";
   echo "<input type=submit value=\"Submit\" class=\"buttons\">";
   echo "</form>"; 

   $html -> close_table();
   $html -> send_admin_footer();

?>
