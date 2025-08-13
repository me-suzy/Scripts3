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
   $fd = fopen("{$config['stylepath']}/$stylesheet","w");
   fwrite($fd,$body);
   fclose($fd);

// --------------------------------------------------
// If we want to test this stylesheet, update it here
   if ($testit) {
      $tempsheet = $stylesheet;
      $tempsheet = eregi_replace(".css","",$tempsheet);
      $user['U_StyleSheet'] = $tempsheet;

      $tempsheet = addslashes($tempsheet);
      $username_q = addslashes($user['U_Username']);

      $query = "
         UPDATE {$config['tbprefix']}Users
         SET U_Stylesheet = '$tempsheet'
         WHERE U_Username = '$username_q'
      ";
      $dbh -> do_query($query);

   }

 
// ------------------------
// Send them a confirmation
   $html -> send_header ("The stylesheet, $stylesheet, has been updated",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("The stylesheet has been updated.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The stylesheet file, $stylesheet, has been updated";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
