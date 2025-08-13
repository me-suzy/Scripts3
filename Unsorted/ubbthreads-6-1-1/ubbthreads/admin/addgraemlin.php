<?
/*
# UBBThreads, Version 6
# Official Release Date for UBBThreads Version6: 06/05/2002

# First version of UBBThreads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBBThreads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBBThreads, we at Infopop Corporation
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

// ----------------------------------
// Display page to add a new post icon
   $html -> send_header ("Add new graemlin",$Cat,0,$user);
   $html -> admin_table_header("<b>Add a new graemlin</b>");
   $html -> open_admin_table();
   echo "
    <tr class=\"darktable\">
    <td colspan=\"3\">
This form will allow you to upload and add a new instant graemlin.  These can be used within posts. Note: Your {$config['imagepath']}/graemlins directory will need to be writeable by the webserver for this to work.
    </td>
    </tr>
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doaddgraemlin.php\" enctype=\"multipart/form-data\">
    <INPUT TYPE=\"HIDDEN\" NAME=\"Cat\" VALUE=\"$Cat\">
    <tr class=\"lighttable\">
    <td>
		Graemlin image<br />
      <input type=\"file\" name=\"graemlin\" accept=\"*\" size=\"20\" class=\"formboxes\" />
		<br><br>
		Markup string.  This can be hardcoded, like \"smile\" so users can type :smile: or [smile] to add the smile graemlin to their post.  You can also support multiple languages by using the \"\$ubbt_lang['ICON_NAME']\" format.  If you choose this format you'll need to add the text strings into generic.php language files in each language directory.<br />
		<input type=\"text\" name=\"code\" class=\"formboxes\" />
		<br /><br />
		Smiley code.  This allows users to type something like <b>:)</b> and have that converted into the graemlin.  This is not required.<br>
		<input type=\"text\" name=\"smiley\" class=\"formboxes\" />
    </td>
    </tr>
      <tr class=\"darktable\">
        <td colspan=\"3\">
          <input type=\"submit\" value=\"Add graemlin\" class=\"buttons\" />
   ";
   $html -> close_table();
   echo"</form>";

   $html -> send_admin_footer();

?>
