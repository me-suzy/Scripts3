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
   $user = $userob -> authenticate("U_TextCols,U_TextRows");
   $html = new html;

// -----------------------------
// Make sure they should be here
   if ($user['U_Status'] != 'Administrator'){
      $html -> not_right ("You must be logged in, and be a valid administrator to access this.",$Cat);
   }

// -----------------
// Give them a page 
   $html -> send_header("Send email to all users",$Cat,0,$user);
   $html -> admin_table_header("Send email to all users");
   $html -> open_admin_table();

   echo "
     <tr><td class=\"lighttable\">
     Fill out the form below to send an email to all users.  Remember to use this sparingly for 2 reasons.  First, if it is not extremely important then many users may consider it spam.  Second, if you have a large user base it can take a long time for the server to send all of the emails.
     <form method=POST action =\"{$config['phpurl']}/admin/dosendemail.php\">
     <br /><br />
     Subject<br>
     <input type=hidden name=Cat value=\"$Cat\">
     <input type=text name = Subject class=\"formboxes\">
     <p>
     Who do you want to receive this email? (Multiple groups may be selected)
     <br>
     <select name=whatgroups[] size=5 multiple class=formboxes>
   ";

// ------------------------------------------------
// Now get the list of groups that they can send to
   $query = "
      SELECT G_Id, G_Name
      FROM   {$config['tbprefix']}Groups
      ORDER BY G_Id
   ";
   $sth = $dbh -> do_query($query);
   echo "<option value=\"sendtoall\" selected> All registered users";
   while (list($Gid,$Gname) = $dbh -> fetch_array($sth)) {
      if ($Gname == "Guests") { continue; }
      echo "<option value=$Gid>$Gname Group";
   }
   echo "
      </select>
     <p>
     Body<br>
     <textarea cols=\"{$user['U_TextCols']}\" rows=\"{$user['U_TextRows']}\" wrap=\"soft\" name=\"Body\" class=\"formboxes\"></textarea>
     <p> 
	  All emails are sent via BCC.  Some mail servers require at least one email address in the To field.  If your mail server requires this (you get errors or the emails are not being sent out) then specify an email here.  Multiple emails will be sent to this address depending on the number of users you have.<br>
	  <input type=text name=bogus class=formboxes>
	  <p>
     <input type=submit value = \"Send email\" class=\"buttons\">
     </form>
   ";

   $html -> close_table();
// -------------
// Send a footer
   $html -> send_admin_footer();

?>
