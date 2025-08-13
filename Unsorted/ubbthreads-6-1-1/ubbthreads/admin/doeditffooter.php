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

// ------------------
// Write out the file
   $include = "footer_".$ffooter.".php";

   $fd = @fopen("{$config['path']}/includes/$include","w");

   if (!$fd) {
      $html -> not_right("We cannot create a file called '$include' in your includes directory.  Please check the permissions on this folder and try again.",$Cat);
   }
   else {
      fwrite($fd,$body);
      fclose($fd);
   }

// ------------------------
// Send them a confirmation
   $ffooter_q = addslashes($ffooter);
   $query = "
      SELECT Bo_Title
      FROM   {$config['tbprefix']}Boards
      WHERE  Bo_Keyword='$ffooter_q'
   ";
   $sth = $dbh -> do_query($query);
   list($title) = $dbh -> fetch_array($sth);

   $html -> send_header ("The special footer has been updated",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Special footer updated.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The special footer for '$title' has been updated";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
