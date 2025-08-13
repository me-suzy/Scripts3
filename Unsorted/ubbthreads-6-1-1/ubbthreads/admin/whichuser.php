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

// ------------------------
// Send them a page 
   $html -> send_header ("Ban a User / Host",$Cat,0,$user);
   $html -> admin_table_header("Ban a User / Host");

   $html -> open_admin_table();

   echo "<tr><td class=\"lighttable\">";
   echo "Enter the Username that you want to delete posts for.  Then choose what type of delete you want to perform.";
   echo "<FORM METHOD = POST action =\"{$config['phpurl']}/admin/dodeleteuserpost.php\">";
   echo "<input type=hidden = hidden name=Cat value=$Cat>";
   echo "<input type=text name=Who class=\"formboxes\" value=\"$User\">";
   echo "<p>";
   echo "<input type=radio name=\"deletetype\" value=\"safe\" checked class=\"formboxes\"> Delete all posts made by this user.  If there are replies to any of their posts, set them to \"Post deleted by {$user['U_Username']}\".  Delete all posts that have no replies to them.<br>";
   echo "<input type=radio name=\"deletetype\" value=\"badboy\" class=\"formboxes\"> Delete all posts made by this user.  If there are any replies, or sub replies to these posts then delete them as well.  This basically removes any trace of this user from the forums.";
   echo "<br><br><input type=submit value=\"Submit\" class=\"buttons\">";
   echo "</form>"; 

   $html -> close_table();
   $html -> send_admin_footer();

?>
