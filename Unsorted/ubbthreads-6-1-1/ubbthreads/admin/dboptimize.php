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
      $html -> not_right ("You must be logged in, and be a valid administrator access this.",$Cat);
   }

// ------------------------
// Send them a page
   $html -> send_header ("Confirm table optimization",$Cat,0,$user);
   $html -> admin_table_header("Confirm table optimization");
   $html -> open_admin_table();

   echo "<tr><td class=\"lighttable\">";
   echo "This will optimize a couple of the key tables such as your user and post tables.  Make sure you close your forums before running this!";
   echo "<br /><br />";
   echo "<a href=\"{$config['phpurl']}/admin/dbdooptimize.php?Cat=$Cat\">Proceed with optimizing</a>.";

   $html -> close_table();
   $html -> send_admin_footer();
