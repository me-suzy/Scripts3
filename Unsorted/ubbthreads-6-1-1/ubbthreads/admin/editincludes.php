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
   $html -> send_header ("Edit $include",$Cat,0,$user);
   $html -> admin_table_header("Edit $include");
   $html -> open_admin_table();

   echo "
    <TR><TD class=\"lighttable\">
    Use the box below to edit the $include file.  You may use standard HTML in the includes.  
   ";

   if ($include == "closedforums") {
     echo "This message will appear to your users when the forums are closed.";
   }
   elseif ($include == "header-insert") {
     echo "This text will be inserted into the HEAD area of all generated pages, so you can use this for META tags, javascript, etc.";
   }
   elseif ($include == "coppainsert") {
      echo "This insert is displayed on the <a href=\"{$config['phpurl']}/coppaform.php\">COPPA form</a>, beneath the <i>Instructions for Parent or Guardian</i> line, if you are doing age verification for new users.  You can put in your mailing address/fax number/, etc.  Anything that parents/guardians might need to know.";
   }
   elseif ($include == "boardrules") {
      echo "These rules will appear on the newuser screen if you have them turned on in the config.";
   }
   elseif ($include == "privacy") {
      echo "A link to this text will appear in the footer if you have it turned on in the config.";
   }


   echo "
    <p>

    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doeditinclude.php\">
    <INPUT TYPE=HIDDEN NAME=include value=\"$include\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <TEXTAREA NAME=body COLS=80 ROWS=20 class=formboxes>";

   $headerfile = file("{$config['path']}/includes/$include.php");
   while (list($linenum,$line) = each($headerfile)) {
      echo "$line";
   }

   echo "</textarea>
    <br><br>
    <input type=submit value=\"Update $include\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();

?>
