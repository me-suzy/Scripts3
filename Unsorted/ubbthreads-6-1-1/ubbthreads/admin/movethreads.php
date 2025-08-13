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
   $user = $userob -> authenticate("U_Groups");
   $html = new html;

// -----------------------------
// Make sure they should be here
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// -----------------------------------------------------------------------
// Setup a group query so they only get a list of boards they can write to
    $Grouparray = split("-",$user['U_Groups']);
    $gsize = sizeof($Grouparray);
    $groupquery = "WHERE (";
    $g = 0;
    for ($i=0; $i<$gsize;$i++) {
       if (!preg_match("/[0-9]/",$Grouparray[$i])) { continue; };
       $g++;
       if ($g > 1) {
          $groupquery .= " OR ";
       }
       $groupquery .= "Bo_Write_Perm LIKE '%-$Grouparray[$i]-%'";
    }
    $groupquery .= ")";


// -----------------------------------------------
// Grab the current boards
   $query = "
    SELECT Bo_Title,Bo_Keyword,Bo_Cat,Bo_Sorter,Bo_CatName
    FROM   {$config['tbprefix']}Boards
    $groupquery
    ORDER BY Bo_Cat,Bo_Sorter
   ";
   $sth = $dbh -> do_query($query);


// ------------------------
// Send them a page 
   $html -> send_header ("Choose a forum.",$Cat,0,$user);
   $html -> admin_table_header("Choose a forum.");
   $html -> open_admin_table();
   echo "<tr><td class=\"lighttable\">";
   echo "Choose which forum you would like to move this thread to.";

   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/domovethreads.php\">";
   echo "<INPUT TYPE=HIDDEN NAME=Cat VALUE=\"$Cat\">";
   echo "<INPUT TYPE=HIDDEN NAME=page VALUE=\"$page\">";
   echo "<INPUT TYPE=HIDDEN NAME=view VALUE=\"$view\">";
   echo "<INPUT TYPE=HIDDEN NAME=sb   VALUE=\"$sb\">";
   echo "<input type=hidden name=o value=\"$o\">";
   echo "<br /><select name=\"Keyword\" class=\"formboxes\">";
   while (  list($Title,$Board,$Catnum,$Sorter,$Name) = $dbh -> fetch_array($sth)) {

      if ($initialcat != $Name) {
         echo "<option value=\"category\">*$Name -----";
         $initialcat = $Name;
      }
      echo "<option value=$Board>&nbsp;&nbsp;&nbsp;$Title</option>";  
     
   }
   $dbh -> finish_sth($sth);
   echo "</select>";
   echo "<br><br>";
   echo "<INPUT TYPE=HIDDEN NAME=\"oldboard\" value=\"$Keyword\">";
   echo "<input type=hidden name=\"number\" value=\"$Number\">";
   echo "<input type=submit value=\"Submit\" class=\"buttons\">";
 
   $html -> close_table();
   $html -> send_admin_footer();

?>
