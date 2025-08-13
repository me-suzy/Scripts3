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
   $html -> send_header ("Edit template: $template",$Cat,0,$user);
   $html -> admin_table_header("Edit template: $template");
   $html -> open_admin_table();

   echo "
    <TR><TD class=\"lighttable\">
    Use the box below to edit this template.  Several things to note.  Textarea blocks have been renamed textareahtml (this prevents the textarea below from breaking.  These will be converted back to textarea when you submit.  Any php code get's commented out which you will notice.  The comments will also be removed when you submit.<p>

    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doedittemplate.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <INPUT TYPE=HIDDEN NAME=template VALUE=\"$template\">

    <TEXTAREA NAME=body COLS=100 ROWS=20 class=formboxes>";

   $tempfile = file("{$config['path']}/templates/default/$template");
   while (list($linenum,$line) = each($tempfile)) {
      $line = str_replace("&amp;","&amp;amp;",$line);
      if (ereg("UBBTPRINT",$line)) {
         $line = chop($line);
         $line = "<!-- $line -->\n";
      }
      if (ereg("UBBTREMARK",$line)) {
         $line = chop($line);
         $line = "<!-- $line -->\n";
      }
      $line = str_replace("textarea","textareahtml",$line);
      
      
      echo "$line";
   }

   echo "
    </textarea>
    <br><br>
    <input type=submit value=\"Update $template\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();

?>
