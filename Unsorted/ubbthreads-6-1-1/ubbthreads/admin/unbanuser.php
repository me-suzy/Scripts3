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
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// ------------------------
// Send them a page 
   $html -> send_header ("Unban User / IP",$Cat,0,$user);
   $html -> admin_table_header("Unban User / IP");
   $html -> open_admin_table();

   echo "<tr><td class=\"lighttable\">";
   echo "Select which Username or Hostname you want to unban.";
   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/dounbanuser.php\">";
   echo "<INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>";
 
// ---------------------
// Give the banned users
   $query = "
    SELECT B_Username
    FROM  {$config['tbprefix']}Banned
    WHERE B_Username <> 'NULL'
    AND B_Username <> ''
    ORDER BY B_Username
   ";
   $sth = $dbh -> do_query($query);

   echo "<select name = \"Unban\" class=\"formboxes\">";
   while ( list($Unban) = $dbh -> fetch_array($sth)){
      echo "<option>$Unban</option>";
   }
   $dbh -> finish_sth($sth);

   echo "</select>";

   echo "<input type=submit name=option value=\"Unban this username\" class=\"buttons\">";
   echo "<br><br>";


// -------------------------
// Give the banned hostnames
   $query = "
    SELECT B_Hostname 
    FROM  {$config['tbprefix']}Banned
    WHERE B_Hostname <> 'NULL'
    ORDER BY B_Hostname 
   ";
   $sth = $dbh -> do_query($query);

   echo "<select name = \"Hostname\" class=\"formboxes\">";

   while ( list($Hostname) = $dbh -> fetch_array($sth)){
      echo "<option>$Hostname</option>";
   }
   $dbh -> finish_sth($sth);

   echo "</select>";
   echo "<input type=submit name=option value=\"Unban this IP\" class=\"buttons\">";

   echo "</form>"; 

   $html -> close_table();
   $html -> send_admin_footer();


?>
