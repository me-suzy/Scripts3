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

// -----------------------------------------------
// Grab the group info 
   $query = "
    SELECT G_Name 
    FROM   {$config['tbprefix']}Groups 
    WHERE  G_Id = '$Number'
   ";
   $sth = $dbh -> do_query($query);
   list($Title) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// ------------------------
// Send them a page 
   $html -> send_header ("Rename this group.",$Cat,0,$user);
   $html -> admin_table_header("Rename this group.");
   $html -> open_admin_table();

   echo "
    <TR><TD class=\"lighttable\">
    Change the group name below to what you would like it to be.<p>
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doeditgroup.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <input type=hidden name=Number value=$Number>
    Name of this group.<br>
    <input type=text name = Title size=60 value = \"$Title\" class=\"formboxes\">
    <br><br>
    <input type=submit value = \"Submit\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();

?>
