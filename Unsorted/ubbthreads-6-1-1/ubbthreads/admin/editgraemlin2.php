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

// ----------------------------
// Grab the graemlin properties
	$query = "
		SELECT G_Code,G_Smiley,G_Image
		FROM   {$config['tbprefix']}Graemlins
		WHERE  G_Number='$graemlin'
	";
	$sth = $dbh -> do_query($query);
	list($code,$smiley,$image) = $dbh -> fetch_array($sth);

// ----------------------------------
// Display page to add a new post icon
   $html -> send_header ("Edit a graemlin",$Cat,0,$user);
   $html -> admin_table_header("<b>Edit a graemlin</b>");
   $html -> open_admin_table();
   echo "
    <tr class=\"darktable\">
    <td colspan=\"3\">
This form allows you to change the markup properties for the following graemlin: <img src=\"{$config['images']}/graemlins/$image\">
    </td>
    </tr>
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doeditgraemlin.php\">
    <INPUT TYPE=\"HIDDEN\" NAME=\"Cat\" VALUE=\"$Cat\">
	 <input type=\"hidden\" name=\"graemlin\" value=\"$graemlin\">
    <tr class=\"lighttable\">
    <td>
		Markup string.  This can be hardcoded, like \"smile\" so users can type :smile: or [smile] to add the smile graemlin to their post.  You can also support multiple languages by using the \"\$ubbt_lang['ICON_NAME']\" format.  If you choose this format you'll need to add the text strings into generic.php language files in each language directory.<br />
		<input type=\"text\" name=\"code\" class=\"formboxes\" value=\"$code\" />
		<br /><br />
		Smiley code.  This allows users to type something like <b>:)</b> and have that converted into the graemlin.  This is not required.<br>
		<input type=\"text\" name=\"smiley\" class=\"formboxes\" value=\"$smiley\" />
    </td>
    </tr>
      <tr class=\"darktable\">
        <td colspan=\"3\">
          <input type=\"submit\" value=\"Edit graemlin\" class=\"buttons\" />
   ";
   $html -> close_table();
   echo"</form>";

   $html -> send_admin_footer();

?>
