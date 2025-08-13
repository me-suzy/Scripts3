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

// ---------------------------------
// Make sure they are should be here
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator o
r moderator to access this.",$Cat);
  } 

// --------------------------------------------------------------
// Make sure they chose a forum if they are assigning a moderator
   $choice = @implode(",", $HTTP_POST_VARS['assignmod']);
   $choice = ",$choice,";
   if (ereg(",category,",$choice)) {
      $html -> not_right("You need to choose a forum,  not a category.",$Cat);
   }
   if ( ($screen == "assignmod")&& (!$assignmod)) {
      $html -> not_right("You need to choose a forum.",$Cat);
   }
   

// -----------------------------------------------------
// Now we need to add a list of Groups to choose from...
   $query = " 
    SELECT G_Name,G_Id
    FROM {$config['tbprefix']}Groups
   "; 
   $sth = $dbh -> do_query($query);

// ----------------
// Send them a page

// -----------------------------------------------------------------------
// We want to include the selectusers javascript so we add a $user[U_java]
// variable that send_header will catch
   $user['java'] = "selectusers";
   $html -> send_header("Select users to view",$Cat,0,$user);
   $html -> admin_table_header("Select users to view");
   $html -> open_admin_table();

   echo "<tr><td class=\"darktable\">";
   echo "Use the form below to filter the users you want to view.  For example: If you are looking to see who currently has administrative privileges, Search for \"Group\" and then choose the \"Administrators\" group to search for.";
   echo "</td></tr><tr><td class=lighttable><br><br>";
  

// ------------------------------------------------------------------------
// If we are manipulating a group then we send them to showcurrentgroups.pl
// $assignmod is currently an array, we need to stuff this into one variable
   $arraysize = sizeof($HTTP_POST_VARS['assignmod']);
   for ($i=0; $i< $arraysize; $i++) {
      $mod .="{$HTTP_POST_VARS['assignmod'][$i]}-MULTI-";
   }
   $mod = preg_replace("/-MULTI-$/","",$mod);

   if ($Group) {
      echo "<FORM name=myForm METHOD=POST action =\"{$config['phpurl']}/admin/showcurrentgroup.php\">\n";
   }
   else {
      echo "<FORM name=myForm METHOD=POST action =\"{$config['phpurl']}/admin/showusers.php\">\n";
   }
   echo "<INPUT TYPE=HIDDEN NAME=Group VALUE=$Group>";
   echo "<INPUT TYPE=HIDDEN NAME=\"assignmod\" value=\"$mod\">";
   echo "<INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>";
   echo "<b>Search for </b>";
   echo "<SELECT NAME=\"Menu1\" size=1 onChange=\"insertProduct(document.myForm.Menu2,document.myForm.Menu1.options[document.myForm.Menu1.selectedIndex].value)\" class=\"formboxes\">\n";
   echo "   <option value=\"\" SELECTED>Choose a Search Category...</OPTION>\n";
   echo "   <OPTION VALUE=\"\">-------------------------------------</OPTION>\n";
   echo "   <OPTION VALUE=\"users\">Users</OPTION>\n";
   echo "   <OPTION VALUE=\"totposts\">Total Posts</OPTION>\n";
   echo "   <OPTION VALUE=\"domain\">Domain</OPTION>\n";
   echo "   <OPTION VALUE=\"email\">E-Mail</OPTION>\n";
   echo "   <OPTION VALUE=\"username\">Username</OPTION>\n";
   echo "   <OPTION VALUE=\"realusername\">Real Name</OPTION>\n";
   echo "   <OPTION VALUE=\"group\">Group</OPTION>\n";
   echo "   <OPTION VALUE=\"datereg\">Date Registered</OPTION>\n";
   echo "   <OPTION VALUE=\"laston\">Last On</OPTION>\n";
   echo "   <OPTION VALUE=\"regip\">Registration IP</OPTION>\n";
   echo "</select>";

   echo "&nbsp;&nbsp;&nbsp;  <b>With a value of: </b>";
   echo "<SELECT NAME=\"Menu2\" size=1 class=\"formboxes\">";
   echo "   <OPTION VALUE=\"\" SELECTED>Choose Search Value...</OPTION>\n";
   echo "   <OPTION VALUE=\"\">-------------------------------------</OPTION>\n";
   echo "   <OPTION VALUE=\"\">Please choose a Search category</OPTION>\n";
   echo "   <OPTION VALUE=\"\">first, then choose a Search Value.</OPTION>\n";
   echo "</select>";

   echo "<p><b>Username / Real Name / E-mail / Domain / IP:</b> <input type=text name=search_val size=30 class=\"formboxes\">\n";

   echo "<p>\n";

// now we send the list of Groups as a drop-down menu
   echo "<b>Or choose a Group to search for: &nbsp</b><F2><select name=\"Number\" class=\"formboxes\">";
   while ( list($Title,$Number) = $dbh -> fetch_array($sth)) {
      if ( $user['U_Status'] == "Moderator" && $Number <=2 ) { 
         continue;
      }
      echo "<option value=$Number>$Title</option>\n";
   }
   $dbh -> finish_sth($sth);

   echo "</select>\n";

// end of added lines

   echo "<br><br>\n";
// added by Matt Reinfeldt 11/29/99
   echo "<p><b>Sort by:</b> ";
   echo "<br><input type=radio name=Sort_key value=\"Username\" checked=yes class=\"formboxes\">Username\n";
   echo "<br><input type=radio name=Sort_key value=\"Total Posts - Ascending\" class=\"formboxes\">Total Posts - Ascending\n";
   echo "<br><input type=radio name=Sort_key value=\"Total Posts - Descending\" class=\"formboxes\">Total Posts - Descending\n";
   echo "<br><input type=radio name=Sort_key value=\"Date Registered - Ascending\" class=\"formboxes\">Date Registered - Ascending\n";
   echo "<br><input type=radio name=Sort_key value=\"Date Registered - Descending\" class=\"formboxes\">Date Registered - Descending\n";
   echo "<br><input type=radio name=Sort_key value=\"Last On - Ascending\" class=\"formboxes\">Last On - Ascending\n";
   echo "<br><input type=radio name=Sort_key value=\"Last On - Descending\" class=\"formboxes\">Last On - Descending<p><p>\n";

   echo "<input type=submit value=\"Submit\" class=\"buttons\">\n";
   echo "</form>\n";

   $html -> close_table();
   $html -> send_admin_footer();
