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

// ----------------------------------------------------------------------
// If we are not logged in, then present a log on form, otherwise present
// a menu of options.

   if( ($user['U_Status'] != 'Administrator') && ($user['U_Status'] != 'Moderator') ) {
      $html -> not_right ("You must be logged in, and be a valid administrator o
r moderator to access this.",$Cat);
   }

// ------------------------------------------
// They are an admin so give them the options!

   $html -> send_header ("Main Window",$Cat,0,$user);
   $html -> admin_table_header("Welcome, {$user['U_Username']}");
   $html -> open_admin_table();
   echo <<<EOF
     <tr><td class=lighttable>
	Welcome to the admin area.  You may use the menu on the left to manage your board.
EOF;
   $html -> close_table();
   $html -> send_admin_footer();

?>
