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
   if ( ($user['U_Status'] != 'Administrator') && ($user['U_Status'] != "Moderator") ){
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// -----------------------------------
// Find out what groups they belong to
   $Username_q = addslashes($User);
   $query = "
    SELECT U_Groups
    FROM   {$config['tbprefix']}Users
    WHERE  U_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);
   list($Groups) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// -------------------------------
// Now let's get all of the groups
   $query = "
    SELECT G_Name, G_Id
    FROM   {$config['tbprefix']}Groups
   ";
   $sth = $dbh -> do_query($query);

// --------------------------------------------------------------------
// Now we chug through the groups.  If it was checked, we add them to
// the group.  If it was unchecked we remove them from it.
   $total = 0;
   while ( list($Name,$Id) = $dbh -> fetch_array($sth)) {
      $total++;
      if (($total < 5) && ($total != 3)) { continue;  }
      if ($HTTP_POST_VARS[$Id]) {
         if (!ereg("-$Id-",$Groups)) {
            $Groups .= "$Id-";
         }
      }
      else {
         $Groups = preg_replace("/-$Id-/","-",$Groups);
      }
   }
   $dbh -> finish_sth($sth);

// -----------------------
// Update the users groups
   $Groups_q = addslashes($Groups);
   $query = "
    UPDATE {$config['tbprefix']}Users
    SET    U_Groups = '$Groups_q'
    WHERE  U_Username = '$Username_q' 
   ";
   $dbh -> do_query($query);

// ---------------------
// Send the confirmation
   $html -> send_header("This users groups have been changed.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("This users groups have been changed.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";
   echo "This users groups have been changed. You will now be returned to the main administration page.";
   echo "</span></TD></TR></TABLE>";
 
// ----------------
// send the footer
   $html -> send_admin_footer();

?>
