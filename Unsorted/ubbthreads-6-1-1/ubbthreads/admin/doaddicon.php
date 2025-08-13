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
// Get the uploaded images
   $readpost_name = $HTTP_POST_FILES['readpost']['name'];
   $readpost_temp = $HTTP_POST_FILES['readpost']['tmp_name'];
   $unreadpost_name = $HTTP_POST_FILES['unreadpost']['name'];
   $unreadpost_temp = $HTTP_POST_FILES['unreadpost']['tmp_name'];

	$readpost_name = str_replace(" ","",$readpost_name);
	$unreadpost_name = $readpost_name;

   if (file_exists("{$config['imagepath']}/icons/$readpost_name")) {
      @unlink($readpost_temp);
      @unlink($unreadpost_temp);
      $html -> not_right("There is already an icon with the name of $readpost_name in the {$config['imagepath']}/icons directory.",$Cat);
   }

   if (file_exists("{$config['imagepath']}/newicons/$readpost_name")) {
      @unlink($readpost_temp);
      @unlink($unreadpost_temp);
      $html -> not_right("There is already an icon with the name of $unreadpost_name in the {$config['imagepath']}/newicons directory.",$Cat);
   }

	if ((!$readpost_temp) || (!$unreadpost_temp) ) {
		$html -> not_right("Both icons not uploaded.",$Cat);
	}
	
   $check = @move_uploaded_file($readpost_temp, "{$config['imagepath']}/icons/$readpost_name");
   if (!$check) {
      $html -> not_right("Couldn't move $readpost_name into the {$config['imagepath']}/icons directory.  Check your permissions and try again.",$Cat);
   }
	
   $check = @move_uploaded_file($unreadpost_temp, "{$config['imagepath']}/newicons/$unreadpost_name");
   if (!$check) {
		@unlink("{$config['imagepath']}/icons/$readpost_name");
      $html -> not_right("Couldn't move $unreadpost_name into the {$config['imagepath']}/newicons directory.  Check your permissions and try again.",$Cat);
   }
// ------------------------
// Send them a confirmation
   $html -> send_header ("New icon added",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"10;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("New icon added");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo " The new icon has been added and should be available for selection when creating a new post.\n";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
