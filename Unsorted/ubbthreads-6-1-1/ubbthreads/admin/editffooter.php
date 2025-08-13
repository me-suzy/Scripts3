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

   $keyword_q = addslashes($ffooter);
   $query = "
      SELECT Bo_Title
      FROM   {$config['tbprefix']}Boards
      WHERE  Bo_Keyword='$keyword_q'
   ";
   $sth = $dbh -> do_query($query);
   list($title) = $dbh -> fetch_array($sth);

// ------------------------
// Send them a page 
   $html -> send_header ("Edit Forum Footer",$Cat,0,$user);
   $html -> admin_table_header("Edit Forum Footer");
   $html -> open_admin_table();

   echo "
    <TR><TD class=\"lighttable\">
    Edit the special footer for '$title'.
   ";

   echo "
    <p>

    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doeditffooter.php\">
    <INPUT TYPE=HIDDEN NAME=ffooter value=\"$ffooter\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <TEXTAREA NAME=body COLS=80 ROWS=20 class=formboxes>";

   $footername = "footer_".$ffooter.".php";

   $footerfile = @file("{$config['path']}/includes/$footername");
   if($footerfile) {
      $printer = implode(" ",$footerfile);
      echo "$printer";
   }

   echo "</textarea>
    <br><br>
    <input type=submit value=\"Update footer\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();

?>
