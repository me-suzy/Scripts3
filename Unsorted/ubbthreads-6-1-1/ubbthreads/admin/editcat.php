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

// -----------------------------------------------
// Grab the current categories 
   $query = "
    SELECT DISTINCT Cat_Title,Cat_Number
    FROM {$config['tbprefix']}Category 
   ";
   $sth = $dbh -> do_query($query);

// ------------------------
// Send them a page 
   $html -> send_header ("Edit a category.",$Cat,0,$user);
   $html -> admin_table_header("Edit a category.");
   $html -> open_admin_table();

   echo "<tr><td class=\"lighttable\">";
   echo "Choose a category from the list below that you would like to edit.";

   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/viewcat.php\">";
   echo "<INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>";
   echo "<select name=\"Number\" class=\"formboxes\">";
   while ( list($Title,$Number) = $dbh -> fetch_array($sth)) {
      echo "<option value=$Number>$Title</option>";  
   }
   $dbh -> finish_sth($sth);

   echo "</select>";
   echo "<br><br>";
   echo "<input type=submit value=\"Edit this category\" class=\"buttons\">";
   echo "<input type=submit name=option value =\"Delete this category\" class=\"buttons\">";
   echo "</form>";

   $html -> close_table();
   $html -> send_admin_footer();

?>
