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

// -----------------
// Check for a value
   if (!$days) {
      $html -> not_right("You must supply a value in the days field.",$Cat);
   }

// ---------------------------------------------
// Now lets convert the number of days to seconds
   $seconds = $days * 86400;

// ------------------------------------
// Now grab the current date in seconds
   $date = $html -> get_date();

// -----------------------------------
// Now we figure the actual purge time
   $purge = $date - $seconds;

// -----------------------
// Now we delete the messages 
   $query = " 
    SELECT COUNT(*) FROM {$config['tbprefix']}Messages
    WHERE       M_Sent <= $purge
   ";
   $sth = $dbh -> do_query($query);
   list($deleted) = $dbh ->fetch_array($sth);
   if ($deleted < 1) { $deleted = 0; }

   $query = "
		DELETE FROM {$config['tbprefix']}Messages
		WHERE M_Sent <= $purge
	";
	$dbh -> do_query($query);

// ------------------------
// Send them a page
   $html -> send_header ("Deletion finished",$Cat,0,$user);
   $html -> admin_table_header("Deletion finished");

   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";
   echo "A total of $deleted private messages were deleted from the database.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
