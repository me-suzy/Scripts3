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
   $html -> send_header ("Add new post icon",$Cat,0,$user);
   $html -> admin_table_header("<b>Add a new post icon</b>");
   $html -> open_admin_table();
   echo "
    <tr class=\"darktable\">
    <td colspan=\"3\">
This form will allow you to upload a new post icon.  These icons are used next to the post.  You must have 2 icons to do this (one for posts that have not been read yet and one for posts that have been read). The difference in color is how users can tell if a post is unread.  Example (<img src=\"{$config['images']}/icons/smile.gif\" /> = read and <img src=\"{$config['images']}/newicons/smile.gif\" /> = unread).  Note your {$config['imagepath']}/icons and {$config['imagepath']}/newicons directories will need to be writeable by the web server for this to work.
    </td>
    </tr>
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doaddicon.php\" enctype=\"multipart/form-data\">
    <INPUT TYPE=\"HIDDEN\" NAME=\"Cat\" VALUE=\"$Cat\">
    <tr class=\"lighttable\">
    <td>
		Icon for read posts (15x15 pixels)<br />
      <input type=\"file\" name=\"readpost\" accept=\"*\" size=\"20\" class=\"formboxes\" />
		<br><br>
		Icon for unread posts (15x15 pixels)<br />
		<input type=\"file\" name=\"unreadpost\" accept=\"*\" size=\"20\" class=\"formboxes\" />
    </td>
    </tr>
      <tr class=\"darktable\">
        <td colspan=\"3\">
          <input type=\"submit\" value=\"Add new Icon\" class=\"buttons\" />
   ";
   $html -> close_table();
   echo"</form>";

   $html -> send_admin_footer();

?>
