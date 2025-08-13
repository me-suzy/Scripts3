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

// ------------------------
// Send them a page 
   $html -> send_header ("Edit filter: $filter",$Cat,0,$user);
   $html -> admin_table_header("Edit filter: $filter");
   $html -> open_admin_table();

   echo "
    <TR><TD class=\"lighttable\">
   ";
 
// --------------------------------
// Give a description of the filter
   if ($filter == "bademail") {
      echo "This filter holds a list of email addresses or domains that are not allowed to register usernames. ";
   }
   elseif ($filter == "badnames") {
      echo "This filter holds a list of names that cannot be registered on the forums. ";
   }
   elseif ($filter == "badwords") {
      echo "This filter holds a list of words that will be replaced by the \$config['censored'] value, if you have censoring turned on. ";
   }

   echo "
    Make sure you only have one entry per line.<p>

    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doeditfilter.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <INPUT TYPE=HIDDEN NAME=filter VALUE=\"$filter\">

    <TEXTAREA NAME=body COLS=60 ROWS=10 class=formboxes>";

   $filterfile = file("{$config['path']}/filters/$filter");
   while(list($linenum,$line) = each($filterfile)) {
      echo "$line";
   }

   echo "</textarea>
     <br><br>
     <input type=submit value=\"Update $filter\" class=\"buttons\">
     </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();

?>
