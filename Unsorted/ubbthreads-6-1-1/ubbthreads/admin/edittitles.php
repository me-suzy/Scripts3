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
   $html -> send_header ("Edit titles",$Cat,0,$user);
   $html -> admin_table_header("Edit titles");
   $html -> open_admin_table();

   echo "
    <TR><TD class=\"lighttable\">
    Use the boxes below to set the number of posts that corresponds to a new usertitle.  The first title is used for new users.

    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doedittitles.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>

    <table border=0 width=50%>
    <tr><td width=20%># of Posts
    </td><td width=80%>User title
    </td></tr>

   ";

   $titlefile = file("{$config['path']}/filters/usertitles");
   $i = 1;

// ---------------------------
// List out the current titles
   while (list($linenum,$line) = each($titlefile)) {
      $line = chop($line);
      list($number,$title) = split("\t",$line);
      echo "
         <tr><td>
           <input type=text size=5 class=formboxes value=\"$number\" name=\"number$i\"> 
         </td><td>
         <input type=text class=formboxes value=\"$title\" name=\"title$i\">
         </td></tr>
      ";
      $i++;
   }

// --------------------------------------------
// We want to give boxes for 20 possible titles
   for ( $j=$i; $j<=20; $j++) {
      echo "
        <tr><td>
        <input type=text size=5 class=formboxes name=\"number$j\"> 
        </td><td>
        <input type=text class=formboxes name=\"title$j\">
        </td></tr>
      ";
   }
 
   echo "
    </table>
    <br><br>
    <input type=checkbox name=\"doupdate\" value=\"yes\"> Check this box if you want to update existing users with these new titles. (WARNING!  Any custom titles will be overwritten by doing this.)
    <br><br>
    <input type=submit value=\"Update titles\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();

?>
