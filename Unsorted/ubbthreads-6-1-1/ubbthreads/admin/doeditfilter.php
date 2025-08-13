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

// ----------------------------------
// Need to get rid of any blank lines
   $bodyparts = split("\n",$body);
   $bodysize = sizeof($bodyparts);
   $body = "";
   for ( $i=0; $i<$bodysize; $i++) {
      if ($bodyparts[$i]) {
         $body .= "$bodyparts[$i]\n";
       }
   }
   $body = chop($body);

// ----------------------
// Update the filter file
   $fd = fopen("{$config['path']}/filters/$filter","w");
   fwrite($fd,$body);
   fclose($fd);

// ------------------------
// Send them a confirmation
   $html -> send_header ("The filter, $filter, has been updated",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("The filter has been updated.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The filter, $filter, has been updated";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();


?>
