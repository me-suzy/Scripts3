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
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// ------------------------
// Send them a page
   $html -> send_header ("Select number of days",$Cat,0,$user);
   $html -> admin_table_header("Select number of days");
   $html -> open_admin_table();

   echo "<tr><td class=\"lighttable\">";
   echo "Please enter the number of days you wish to use as the deletion marker.  Any private messages older than this number will be deleted.  If a user has unread private messages this will throw off their total number until the next time they visit and check their private messages.";

   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/confirmpurgemessages.php\">";
   echo "<INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>";
   echo "Number of days ";
   echo "<input type=text name=\"days\" size=3 maxlength=5 class=\"formboxes\">\n";

   echo "<br><br>";
   echo "<input type=submit value=\"Submit\" class=\"buttons\">";
   echo "</form>";
   $html -> close_table();
   $html -> send_admin_footer();

?>
