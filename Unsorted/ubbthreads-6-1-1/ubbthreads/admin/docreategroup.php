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

// ---------------------------------------------
// Check to make sure all info has been filled in
   if(!$Title){
      $html -> not_right("All of the required fields are not filled in.",$Cat);
   }

// -----------------------------------------
// Make sure the group doesn't already exist
   $Title_q = addslashes($Title);
   $query = "
    SELECT G_Name 
    FROM {$config['tbprefix']}Groups 
    WHERE G_Name = '$Title_q'
   ";
   $sth = $dbh -> do_query($query);
   list($check) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if ($check) {
      $html -> not_right("That group name already exists.",$Cat);
   }

// -----------------------------------------
// Make sure we don't already have 99 groups
   $query = "
    SELECT COUNT(*)
    FROM {$config['tbprefix']}Groups
   ";
   $sth = $dbh -> do_query($query);
   list($num) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if ($num == "99") {
      $html -> not_right("You can only have 99 groups.");
   }

// -------------------------------
// Put the group into the database
   $cols = "(G_Name)";
   if ($config['dbtype'] == 'postgres') {
      $extra = ",nextval('group_seq')";
      $cols = "(G_Name,G_Id)";
   } 
   $query = "
    INSERT INTO {$config['tbprefix']}Groups $cols VALUES ('$Title_q'$extra)
   ";
   $dbh -> do_query($query);


// ------------------------
// Send them a confirmation
   $html -> send_header ("The group has been created.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("The group has been created.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo " The group has been created. You will now be returned to the main administration page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
