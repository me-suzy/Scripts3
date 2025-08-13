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

// -----------------------------------------------
// Grab the current boards
   $query = " 
    SELECT Bo_Title,Bo_Number,Bo_Cat,Bo_Sorter,Bo_CatName
    FROM   {$config['tbprefix']}Boards
    ORDER BY Bo_Cat,Bo_Sorter
   "; 
   $sth = $dbh -> do_query($query);

// ------------------------
// Send them a page 
   $html -> send_header ("Assign a moderator",$Cat,0,$user);
   $html -> admin_table_header("Assign a moderator");
   $html -> open_admin_table();

   echo "<TR><TD CLASS=\"lighttable\">";
   echo "Choose a forum or forums from the list below.  You will then be presented with the user search to find the user that you want to add to the moderator list of this forum(s). (Categories are displayed to make finding the right forum easier, but you must assign moderators to forums not categories.)<br clear=all>";
   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/selectusers.php\">";
   echo "<input type=hidden name=Cat value=$Cat>";
   echo "<input type=hidden name=screen value=assignmod>";
   echo "<select name=\"assignmod[]\" class=\"formboxes\" multiple size=5>";
   while ( list($Title,$Number,$Catnum,$Sorter,$Name) = $dbh -> fetch_array($sth))  {
      if ($initialcat != $Name) {
         echo "<option value=\"category\">*$Name -----";
         $initialcat = $Name;
      }
      echo "<option value=$Number>&nbsp;&nbsp;&nbsp;$Title</option>";  
   }
   $dbh -> finish_sth($sth);
   echo "</select>";
   echo "<br><br>";
   echo "<input type=submit value=\"Submit\" class=\"buttons\">";
   echo "</form>";  

   $html -> close_table();
   $html -> send_admin_footer();

?>
