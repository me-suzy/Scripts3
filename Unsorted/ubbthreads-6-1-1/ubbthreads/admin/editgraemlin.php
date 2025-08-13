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
   $html -> send_header ("Edit a graemlin",$Cat,0,$user);
   $html -> admin_table_header("<b>Edit a graemlin</b>");
   $html -> open_admin_table();
   echo "
    <tr class=\"darktable\">
    <td colspan=\"3\">
The following graemlins are in the system.  You may choose any of these to edit which will allow you to change the markup properties of the graemlin.
    </td>
    </tr>
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/editgraemlin2.php\">
    <INPUT TYPE=\"HIDDEN\" NAME=\"Cat\" VALUE=\"$Cat\">
    <tr class=\"lighttable\">
    <td>
";

	 $query = "
		SELECT G_Number,G_Image
		FROM   {$config['tbprefix']}Graemlins
	 ";
	 $sth = $dbh -> do_query($query);
	 while (list($number,$image) = $dbh -> fetch_array($sth)) {
		echo "<input type=\"radio\" name=\"graemlin\" value=\"$number\"> <img src=\"{$config['images']}/graemlins/$image\" /> &nbsp; &nbsp; &nbsp;";
      $i++;
      if ($i==5) {
         echo "<br />";
         $i=0;
      }
	 }

	echo "
		</td>
		</tr>
      <tr class=\"darktable\">
        <td colspan=\"3\">
          <input type=\"submit\" value=\"Edit selected\" class=\"buttons\" />
   ";
   $html -> close_table();
   echo"</form>";

   $html -> send_admin_footer();

?>
