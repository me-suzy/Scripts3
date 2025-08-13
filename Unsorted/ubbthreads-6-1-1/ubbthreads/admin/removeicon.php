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
   $html -> send_header ("Remove a post icon",$Cat,0,$user);
   $html -> admin_table_header("<b>Remove a post icon</b>");
   $html -> open_admin_table();
   echo "
    <tr class=\"darktable\">
    <td colspan=\"3\">
The following post icons are available for users to select when creating a post.  You can remove any of these.  The lock.gif and book.gif icons are used internally by the system so they may not be removed. Old posts will be changed to use the book.gif icon if the original icon for that post is deleted. (NOTE: If you were running UBB.threads prior to version 6.1 you shouldn't remove the stock icons as these might be used within posts.)
    </td>
    </tr>
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doremoveicon.php\">
    <INPUT TYPE=\"HIDDEN\" NAME=\"Cat\" VALUE=\"$Cat\">
    <tr class=\"lighttable\">
    <td>
		Current icons availalbe<br />
";

    $dir = opendir("{$config['imagepath']}/icons");
    $i=0;
    while( ($file = readdir($dir)) != false) {
      if ( ($file == ".") || ($file == "..") || ($file == "lock.gif") || ($file == "book.gif") ) {
          continue;
      }
		list($name,$ext) = split("\.",$file);
		echo "<input type=\"checkbox\" name=\"$name\" value=\"$ext\"> <img src=\"{$config['images']}/icons/$file\" /> &nbsp; &nbsp; &nbsp;";
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
          <input type=\"submit\" value=\"Remove checked\" class=\"buttons\" />
   ";
   $html -> close_table();
   echo"</form>";

   $html -> send_admin_footer();

?>
