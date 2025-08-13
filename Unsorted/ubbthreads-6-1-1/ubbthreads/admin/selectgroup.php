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
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// ------------------------------
// What group are we working with 
   $html -> send_header("Which group do you want to work with?",$Cat,0,$user);
   $html -> admin_table_header("Which group do you want to work with?");
   $html -> open_admin_table();
   echo "
     <TR><TD CLASS=\"lighttable\">
     <FORM METHOD=POST ACTION=\"{$config['phpurl']}/admin/selectusers.php\">
     <INPUT TYPE=HIDDEN NAME=Cat value=\"$Cat\">
     Choose which group you want to manipulate.
     </p><p>
   ";

// --------------------------------
// Now let's list all of the groups
   $query = "
    SELECT G_Name, G_Id
    FROM   {$config['tbprefix']}Groups
   ";
   $sth = $dbh -> do_query($query);


   echo "<SELECT NAME=Group class=\"formboxes\">";

   while ( list($Name,$Id) = $dbh -> fetch_array($sth)) {
      if ( ($Id < 5) && ($Id != 3)) { continue; }
      echo "<OPTION value=$Id>$Name";
   } 

   echo "
     </SELECT>
     <br><br>
     <input type=submit name=option value=\"Submit\" class=\"buttons\">
     </FORM>
   ";

  $html -> close_table();

// ----------------
// send the footer
   $html -> send_admin_footer();

?>
