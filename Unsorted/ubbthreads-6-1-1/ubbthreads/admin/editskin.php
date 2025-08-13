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
  $html -> send_header ("Choose the stylesheet to edit",$Cat,0,$user);
  $html -> admin_table_header("Choose the stylesheet to edit");
  $html -> open_admin_table();

  echo "
    <TR><TD class=\"lighttable\">
    The following stylesheets were found in your {$config['stylepath']} directory. Please choose which one you would like to edit. <p>

    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/editskin2.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>

  ";

// ------------------------
// List out the stylesheets
   $dir = opendir($config['stylepath']);
   $i=0;
   while ($file = readdir($dir)) {
      if ($file == "." || $file == "..") { continue; }
      $i++;
      $styles[$i] = $file;
   }
   sort ($styles);
   closedir($dir);
   $stylesize = sizeof($styles);

   echo "
      Stylesheet to edit:<br>
      <select name=\"stylesheet\" class=formboxes>
   ";

   for ( $i=0;$i<=$stylesize;$i++) {
      if (!preg_match("/\.css$/",$styles[$i])) { continue; }
      echo "<option>$styles[$i]";
   }
   echo "</select>";

   echo "
    <br><br>
    <input type=submit value=\"Edit this stylesheet\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();

?>
