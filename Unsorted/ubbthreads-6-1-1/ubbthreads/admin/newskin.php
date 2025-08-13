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
   $html -> send_header ("Create a new stylesheet",$Cat,0,$user);
   $html -> admin_table_header("Create a new stylesheet");
   $html -> open_admin_table();

   echo "
    <TR><TD class=\"lighttable\">
    Use the box below to create the new stylesheet.  This uses {$config['stylepath']}/template.css for the template.<p>

    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/donewskin.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <TEXTAREA NAME=body COLS=\"60\" ROWS=\"20\" class=formboxes>";

   $skinfile = file("{$config['stylepath']}/template.css");
   while (list($linenum,$line) = each($skinfile)) {
      echo "$line";
   }
    
   echo "
    </textarea>
    <br><br>
    Choose a filename with a .css extension (no spaces):<br>
    <input type=text name=stylesheet value=\".css\" class=formboxes>

    <br><br>
    Test this stylesheet:<br>
    <input type=checkbox name=testit value=yes class=\"formboxes\"> Yes, set this to be  stylesheet.
  
    <br><br>
    <input type=submit value=\"Add this stylesheet\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();


?>
