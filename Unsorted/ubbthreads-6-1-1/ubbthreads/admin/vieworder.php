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
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// -----------------------------------------------
// Grab the current boards and display there order
   $Title_q = addslashes($Title);
   $query = "
    SELECT Bo_Title,Bo_Sorter,Bo_Keyword
    FROM  {$config['tbprefix']}Boards
    WHERE Bo_CatName = '$Title_q'
    ORDER BY Bo_Sorter 
   ";
   $sth = $dbh -> do_query($query);

// ------------------------
// Send them a confirmation
   $html -> send_header ("Change Forum order",$Cat,0,$user);

   $html -> admin_table_header("Change Forum order");
   $html -> open_admin_table();

   echo "<tr class=\"lighttable\"><td>";
   echo "Below you can change the order that the forums are displayed.  Make sure you assign each forum an individual number starting at 1.<p>";

   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/dochangeorder.php\">";
   echo "<INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>";
   while ( list($BoTitle,$Sorter,$Keyword) = $dbh -> fetch_array($sth)) {
      $Total++; 
      $Keyword_q = addslashes($Keyword);
      echo "<input type=text size=2 name=\"$Keyword\" value=$Total class=\"formboxes\"> $BoTitle<br>";
   }
   $dbh -> finish_sth($sth);

   echo "<br><br>";
   echo "<input type=hidden name=Total value=$Total>";
   echo "<input type=hidden name=Title value=\"$Title\">";
   echo "<input type=submit value=\"Submit\" class=\"buttons\">";
   echo "</form>";  

   $html -> close_table();
   $html -> send_admin_footer();

?>
