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

// ------------------------------------------
// Give them a form so they can create a board
   $html -> send_header("Create a category",$Cat,0,$user);
   $html -> admin_table_header("Create a category");
   $html -> open_admin_table();

   echo "
    <tr><td class=\"lighttable\">
    Enter the name of the category you want to create ( up to 120 characters ).  You will then be able to add any new or existing forum into the category.
    <br><br>
    <form method=POST action = \"{$config['phpurl']}/admin/docreatecat.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=\"$Cat\">
    Category Title<br>
    <input type=text size=60 name = Title class=\"formboxes\">
    <br><br>
    Category Description<br>
    <input type=text size=60 name = Description class=\"formboxes\">
    <br><br>
    <input type=submit value=\"Submit\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();

// --------------
// Send the footer
   $html -> send_admin_footer();

?>
