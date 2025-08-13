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
   $html -> send_header ("Edit Forum Footers",$Cat,0,$user);
   $html -> admin_table_header("Edit Forum Footers");
   $html -> open_admin_table();

   echo "
    <TR><TD class=\"lighttable\">
    Any forums that you chose to have special footers will show up in this dropdown list.  If your forum doesn't show then you will need to <a href=\"{$config['phpurl']}/admin/editboard.php?Cat=\">Edit that particular forum</a> and turn the option on.
   ";

   echo "
    <p>

    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/editffooter.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <SELECT NAME=ffooter class=formboxes>
   ";

   $query = "
      SELECT Bo_Keyword,Bo_Title
      FROM   {$config['tbprefix']}Boards
      WHERE  Bo_SpecialHeader = '1'
   ";
   $sth = $dbh -> do_query($query);
   while(list($keyword,$title) = $dbh -> fetch_array($sth)) {
      echo "<option value=$keyword>$title</option>";
   }
   echo "</select> 
    <br><br>
    <input type=submit value=\"Edit this forum's special footer\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();

?>
