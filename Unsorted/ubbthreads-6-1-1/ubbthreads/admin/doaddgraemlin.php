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

// ------------------------------------
// Make sure there is a code to type in
   if (!$code) {
      $html -> not_right("You must provide a ubbcode for the smiley.",$Cat);
   }

// -----------------------
// Get the uploaded images
   $graemlin_name = $HTTP_POST_FILES['graemlin']['name'];
   $graemlin_temp = $HTTP_POST_FILES['graemlin']['tmp_name'];


   if (file_exists("{$config['imagepath']}/graemlins/$graemlin_name")) {
      @unlink($graemlin_temp);
      $html -> not_right("There is already a graemlin with the name of $graemlin_name in the {$config['imagepath']}/graemlins directory.",$Cat);
   }

   $check = @move_uploaded_file($graemlin_temp, "{$config['imagepath']}/graemlins/$graemlin_name");
   if (!$check) {
      $html -> not_right("Couldn't move $graemlin_name into the {$config['imagepath']}/graemlins directory.  Check your permissions and try again.",$Cat);
   }

// --------------------------
// Now add it to the database
	$code = addslashes($code);
	$smiley = addslashes($smiley);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
		VALUES ('$code','$smiley','$graemlin_name')
	";
	$dbh -> do_query($query);
	
// ------------------------
// Send them a confirmation
   $html -> send_header ("New graemlin added",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"10;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("New graemlin added");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo " The new graemlin has been added and should be available for selection when creating a new post.\n";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
