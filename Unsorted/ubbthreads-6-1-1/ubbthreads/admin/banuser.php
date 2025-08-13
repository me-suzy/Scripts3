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
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// ------------------------
// Send them a page 
   $html -> send_header ("Ban a User / Host",$Cat,0,$user);
   $html -> admin_table_header("Ban a User / Host");

   $html -> open_admin_table();

   echo "<tr><td class=\"lighttable\">";
   echo "Enter the Username or IP you want to ban.  If you ban a Username then that user will not be able to post any messages or send any private messages, although they will still be able to post messages as the Anonymous user.  If you ban a IP then anyone coming from that IP will not be able to post any messages or send any private messages, even as an Anonymous user.  Note that when you ban by an IP you may be banning a dynamic address that might affect more than one user.";
   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/dobanuser.php\">";
   echo "<input type=hidden = hidden name=Cat value=$Cat>";
   echo "<select name = \"Banby\" class=\"formboxes\">";
   echo "<option value=\"user\">Ban the following user</option>\n";
   echo "<option value=\"host\">Ban the following IP address</option>\n";
   echo "</select>";
   echo "<br><br>User or IP to ban<br> (When banning by IP you can use a % to match multiple digits or _ to match a single digit.  So you could ban a range or block of IP's with the following 10.1.2.%)<br>";
   echo "<input type=text name=Who class=\"formboxes\" value=\"$User\">";
   echo "<br><br>Reason<br>";
   echo "<input type=text name=Reason class=\"formboxes\">";
   echo "<br><br><input type=submit value=\"Submit\" class=\"buttons\">";
   echo "</form>"; 
 
   $html -> close_table();
   $html -> send_admin_footer();

?>
