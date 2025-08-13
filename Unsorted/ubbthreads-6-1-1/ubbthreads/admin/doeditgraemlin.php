<?
/*
# UBBThreads, Version 6
# Official Release Date for UBBThreads Version6: 06/05/2002

# First version of UBBThreads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBBThreads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBBThreads, we at Infopop Corporation
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

// -----------------------
// Now update the database
	$code = addslashes($code);
	$smiley = addslashes($smiley);

	$query = "
		UPDATE {$config['tbprefix']}Graemlins
		SET G_Code='$code',
			 G_Smiley='$smiley'
		WHERE G_Number='$graemlin'
	";
	$dbh -> do_query($query);
	
// ------------------------
// Send them a confirmation
   $html -> send_header ("Graemlin updated",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"10;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Graemlin updated");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo " The graemlin has been updated and should be available for selection when creating a new post.\n";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
