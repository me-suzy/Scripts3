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

// ----------------------------------
// Find out what boards they moderate 
 
   $Username_q = addslashes($Moderator);
   $query = "
    SELECT Mod_Board
    FROM   {$config['tbprefix']}Moderators
    WHERE  Mod_Username = '$Username_q' 
   ";
   $sth = $dbh -> do_query($query);

// Give them a list
   $html -> send_header("Remove a moderator",$Cat,0,$user);
   $html -> admin_table_header("Remove a moderator");
   $html -> open_admin_table();

   echo "
     <TR><TD class=\"lighttable\">
     This user moderates the following forums.  Place a checkmark next to the forums that you do not want them to moderate any longer and then click submit.
     <FORM METHOD=POST ACTION=\"{$config['phpurl']}/admin/doremovemod.php\">
     <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
     <INPUT TYPE=HIDDEN NAME=Moderator VALUE=\"$Moderator\">
     <p>
   ";
   while ( list($Board) = $dbh -> fetch_array($sth)) {
      $query = "
         SELECT Bo_Title 
         FROM   {$config['tbprefix']}Boards
         WHERE  Bo_Keyword = '$Board' 
      ";
      $sti = $dbh -> do_query($query);
      list($Title) = $dbh -> fetch_array($sti);
      echo "<INPUT TYPE=CHECKBOX NAME=\"$Board\" class=\"formbuttons\"> $Title<br>";
   }
   $dbh -> finish_sth($sth);

   echo "<input type=submit value=\"Submit\" class=\"buttons\">";
   echo "</form>";

   $html -> close_table();
   $html -> send_admin_footer();


?>
