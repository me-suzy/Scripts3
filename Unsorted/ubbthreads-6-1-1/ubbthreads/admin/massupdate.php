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


// --------------------------------------------
// Get the users in array and update the groups
   $array = $HTTP_POST_VARS['nobelong'];
   $nobelong = split("-MULTI-",$array);
   $arraysize = sizeof($nobelong);
   for ( $i=0; $i < $arraysize; $i++) {
      if ( (!$nobelong[$i]) || ($nobelong[$i] == "-spacer-") ) { continue; }
      $Username_q = addslashes($nobelong[$i]);
      $query = "
        SELECT U_Groups
        FROM   {$config['tbprefix']}Users
        WHERE  U_Username = '$Username_q'
      ";
      $sth = $dbh -> do_query($query);
      list($CurrentG) = $dbh -> fetch_array($sth);
      if (ereg("-$Group-",$CurrentG)) {
         $CurrentG = ereg_replace("-$Group-","-",$CurrentG);
         $CurrentG = addslashes($CurrentG);
         $query = "
            UPDATE {$config['tbprefix']}Users
            SET    U_Groups = '$CurrentG'
            WHERE  U_Username = '$Username_q'
         ";
         $dbh -> do_query($query);
      }
   }

   $array = $HTTP_POST_VARS['belong'];
   $belong = split("-MULTI-",$array);
   $arraysize = sizeof($belong);
   for ( $i=0; $i < $arraysize; $i++) {
      if ( (!$belong[$i]) || ($belong[$i] == "-spacer-") ){ continue; }
      $Username_q = addslashes($belong[$i]);
      $query = "
         SELECT U_Groups
         FROM   {$config['tbprefix']}Users
         WHERE  U_Username = '$Username_q'
      ";
      $sth = $dbh -> do_query($query);
      list($CurrentG) = $dbh -> fetch_array($sth);
      if (!ereg("-$Group-",$CurrentG)) {
         $CurrentG .= "$Group-";
         $CurrentG = addslashes($CurrentG);
         $query = "
            UPDATE {$config['tbprefix']}Users
            SET    U_Groups = '$CurrentG'
            WHERE  U_Username = '$Username_q'
         ";
         $dbh -> do_query($query);
      }
   }

// ------------------------
// Send them a confirmation
   $html -> send_header ("Group changed.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Users belonging to this group have been changed.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The users in this group have been changed.  You will now be returned to the main administration page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
