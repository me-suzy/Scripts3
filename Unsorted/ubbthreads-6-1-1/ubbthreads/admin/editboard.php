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


// -----------------------------------------------
// Grab the current boards
   $query = "
    SELECT Bo_Title,Bo_Number,Bo_Keyword,Bo_Cat,Bo_Sorter,Bo_CatName
    FROM   {$config['tbprefix']}Boards
    ORDER BY Bo_Cat,Bo_Sorter
   ";
   $sth = $dbh -> do_query($query);

// ------------------------
// Send them a page 
   $html -> send_header ("Edit a forum",$Cat,0,$user);
   $html -> admin_table_header("Edit a forum");
   $html -> open_admin_table();

   echo "<tr><td class=\"lighttable\">";
   echo "Choose a forum from the list below that you would like to edit.<br />";

   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/viewboard.php\">";
   echo "<INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>";
   echo "<select name=\"Number\" class=\"formboxes\">";
   while ( list($Title,$Number,$Keyword,$Catnum,$Sorter,$Name) = $dbh -> fetch_array($sth)) {

   // ---------------------------------
   // Check if they moderate this board
      if ( $user['U_Status'] == "Moderator") {
         $Username_q = addslashes($user['U_Username']);
         $Board_q    = addslashes($Keyword);
         $query = "
           SELECT Mod_Board
           FROM   {$config['tbprefix']}Moderators
           WHERE  Mod_Username = '$Username_q'
           AND    Mod_Board    = '$Board_q'
         ";
         $sti = $dbh -> do_query($query);
         list($check) = $dbh -> fetch_array($sti);
         $dbh -> finish_sth($sti);
         if (!$check) {
           continue;
         }   
      }
      if ($initialcat != $Name) {
         echo "<option value=\"category\">*$Name -----";
         $initialcat = $Name;
      }
      echo "<option value=$Number>&nbsp;&nbsp;&nbsp;$Title</option>";  
   }
   $dbh -> finish_sth($sth);

   echo "</select>";
   echo "<br><br>";
   echo "<input type=submit value=\"Change this forum\" class=\"buttons\">";


// -------------------------------------------------------------------------
// If this is an admin then they get the option of deleting the board as well.
   if ($user['U_Status'] == 'Administrator') {
      echo "<input type=submit name=option value =\"Delete this forum\" class=\"buttons\">";
   }  
   echo "</form>";

   $html -> close_table();
   $html -> send_admin_footer();

?>
