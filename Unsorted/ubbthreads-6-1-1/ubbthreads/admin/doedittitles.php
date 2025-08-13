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

   $fd = fopen("{$config['path']}/filters/usertitles","w");
   for ( $i=1; $i<= 20; $i++) {
      $thisnumber = "number$i";
      $thistitle = "title$i";
      if ($HTTP_POST_VARS[$thistitle]) {
         fwrite ($fd,"$HTTP_POST_VARS[$thisnumber]	$HTTP_POST_VARS[$thistitle]\n");
      }
    }
    fclose($fd);

// ----------------------------------------------------------------------
// If they checked the update box, then we need to update the user titles
   if ($doupdate) {
      for ( $i=1; $i<= 20; $i++) {
         $thisnumber = "number$i";
         $thistitle = "title$i";
         $next = $i+1;
         $nextnumber = "number$next";
         $clause="";
         if ($i ==1) {
            $clause = "WHERE U_TotalPosts < $HTTP_POST_VARS[$nextnumber]";
         }
         elseif (!$HTTP_POST_VARS[$thisnumber]) { continue; }
         else {
            $clause = "WHERE U_TotalPosts >= $HTTP_POST_VARS[$thisnumber] AND U_TotalPosts < $HTTP_POST_VARS[$nextnumber]";
         }
         if (!$HTTP_POST_VARS[$nextnumber]) {
            $clause = "WHERE U_TotalPosts >= $HTTP_POST_VARS[$thisnumber]";
         }

         $updatedtitle = $HTTP_POST_VARS[$thistitle];
         $query = "
            UPDATE {$config['tbprefix']}Users 
            SET    U_Title = '$updatedtitle'
            $clause
         ";
         $dbh -> do_query($query);
      }

   }

// ------------------------
// Send them a confirmation
   $html -> send_header ("User titles  have been updated",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("User titles have been updated.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The user titles have been updated.  Titles will take effect when users surpass the checkpoints set.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
