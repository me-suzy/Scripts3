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
// List out the templates
   $path = "{$config['path']}/templates/default";
   $dir = opendir($path);
   $i=0;
   while ($file = readdir($dir)) {
      if ($file == "." || $file == "..") { continue; }
      $template[$i] = $file;
      $i++;
   }
   sort ($template);
   closedir($dir);
   $templatesize = sizeof($template);

   
// ------------------------
// Send them a page 
  $html -> send_header ("Choose a template to edit",$Cat,0,$user);
  $html -> admin_table_header("Choose a template to  edit");
  $html -> open_admin_table();

  echo "
    <TR><TD class=\"darktable\" colspan=\"2\">
    Select the template that you wish to edit.  All possible HTML is stored in the templates below.  However because some things are dynamic and depend on the user looking at them there are a few places where some HTML is still in the scripts themselves and a variable holds the HTML.
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/edittemplate.php\" name=\"myForm\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>

    </td></tr>
    <tr><td class=\"lighttable\" width=\"50%\">

      <select name=\"template\" class=formboxes size=15>
   ";

   for ( $i=0;$i<$templatesize;$i++) {
      echo "<option>$template[$i]</option>";
   }

   echo "
     </select>
     </td></tr>
     <tr><td class=lighttable colspan=2 align=center>
     <input type=submit value=\"Edit selected template\">
   ";

   $html -> close_table();
   $html -> send_admin_footer();

?>
