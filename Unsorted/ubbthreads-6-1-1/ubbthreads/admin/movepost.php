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

   $number   = $Number;
   $oldboard = $Keyword;

// -----------------------------------------------
// Grab the current boards
   $query = "
    SELECT Bo_Title,Bo_Keyword,Bo_Cat,Bo_Sorter,Bo_CatName
    FROM   {$config['tbprefix']}Boards
    ORDER BY Bo_Cat,Bo_Sorter
   ";
   $sth = $dbh -> do_query($query);


// ------------------------
// Send them a page 
   $html -> send_header ("Choose a forum.",$Cat,0,$user);
   $html -> admin_table_header("Choose a forum.");
   $html -> open_admin_table();
   echo "<tr><td class=\"lighttable\">";
   echo "Choose which forum you would like to move this post and it's replies to.  This will turn into a new topic";

   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/domovepost.php\">";
   echo "<INPUT TYPE=HIDDEN NAME=Cat VALUE=\"$Cat\">";
   echo "<INPUT TYPE=HIDDEN NAME=page VALUE=\"$page\">";
   echo "<INPUT TYPE=HIDDEN NAME=view VALUE=\"$view\">";
   echo "<INPUT TYPE=HIDDEN NAME=sb   VALUE=\"$sb\">";
   echo "<input type=hidden name=o value=\"$o\">";
   echo "<br /><select name=\"Keyword\" class=\"formboxes\">";
   while (  list($Title,$Keyword,$Catnum,$Sorter,$Name) = $dbh -> fetch_array($sth)) {

   // ---------------------------------
   // Check if they moderate this board
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
      if ( ($user['U_Status'] != "Administrator") && (!$check) ) {
         continue; 
      }  

      if ($initialcat != $Name) {
         echo "<option value=\"category\">*$Name -----";
         $initialcat = $Name;
      } 
      echo "<option value=$Keyword>&nbsp;&nbsp;&nbsp;$Title</option>";  
     
   }
   echo "</select>";
   echo "<br><br>";
   echo "<INPUT TYPE=HIDDEN NAME=\"oldboard\" value=\"$oldboard\">";
   echo "<input type=hidden name=\"number\" value=\"$number\">";
   echo "<input type=submit value=\"Submit\" class=\"buttons\">";

   $html -> close_table();
   $html -> send_admin_footer();

?>
