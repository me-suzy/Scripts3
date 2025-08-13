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
   $user = $userob -> authenticate("U_Groups");
   $html = new html;

// -----------------------------
// Make sure they should be here
   if ( ($user['U_Status'] != 'Administrator') && ($user['U_Status'] != "Moderator") ) {
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// -----------------------------------
// Find out what groups they belong to
   $Username_q = addslashes($User);
   $query = "
    SELECT U_Groups
    FROM   {$config['tbprefix']}Users
    WHERE  U_Username = '$Username_q'
   ";

   $sth = $dbh -> do_query($query);
   list($Groups) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// ---------------------------------------------------------
// Ok, we found the profile, now lets put it all onto a page
   $html -> send_header("Change user's groups ($User)",$Cat,0,$user);
   $html -> admin_table_header("Change user's groups ($User)");
   $html -> open_admin_table();
   echo "
    <TR><TD CLASS=\"lighttable\">
    <FORM METHOD=POST ACTION=\"{$config['phpurl']}/admin/dochangeugroup.php\">
    <INPUT TYPE=HIDDEN NAME=Cat value=\"$Cat\">
    <INPUT TYPE=HIDDEN NAME=User value=\"$User\">
    Place a checkmark next to the groups that you want this user to belong to. 
    </p><p>
   ";

// --------------------------------
// Now let's list all of the groups
   $query = "
    SELECT G_Name, G_Id
    FROM   {$config['tbprefix']}Groups
   ";
   $sth = $dbh -> do_query($query);

// --------------------------------------------------------------------
// This gets somewhat confusing.  Basically we are echoing out a table
// with 3 colums.  When we are done listing the groups then we need
// to fill out the rest of the table with nbsp;
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\">";
   $row =0;
   while ( list($Name,$Id) = $dbh -> fetch_array($sth)) {
      if ( ($Id < 5) && ($Id != 3)) { continue; }
      if ($row == 0) { echo "<TR>"; }
      $row++;
      echo "<TD width=33% class=\"lighttable\">";
      $checky = "-$Id\-";
      $checked = "";
      if (ereg($checky,$Groups)) {
         $checked = "CHECKED";
      }
      if ( ($user['U_Status'] == "Moderator") && (!ereg("-$Id-",$user['U_Groups']))) {
         $hidden = "";
         if ($checked == "CHECKED") {
            $hidden = "VALUE=\"on\"";
         }
         echo "<input type=hidden name=\"$Id\" $hidden>";
         continue;
      }
      echo "<INPUT TYPE=CHECKBOX NAME=\"$Id\" $checked VALUE=\"on\" class=\"formboxes\"> ";
      echo "$Name";
      echo "</TD>";
      if ($row == 3) {
         echo "</TR>";
         $row = 0;
      }
   } 
   $dbh -> finish_sth($sth);

   if ($row > 0) {
      for ( $i=0; $i<(3-$row); $i++) {
         echo "<TD width=33% class=\"lighttable\">&nbsp;</TD>";
      }
      echo "</TR>";
   }
   echo "
      </TABLE>
      <input type=submit name=option value=\"Submit\" class=\"buttons\">
      </FORM>
   ";

   $html -> close_table();

// ----------------
// send the footer
   $html -> send_admin_footer();

?>
