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

   if ($option == "Unban this username"){
      $Selector = "B_Username";
      $Delete     = $Unban;
      $Delete_q   = addslashes($Delete);
   }
   else {
      $Selector = "B_Hostname";
      $Delete     = $Hostname;
      $Delete_q   = addslashes($Delete);
   }
   $query = "
    DELETE FROM {$config['tbprefix']}Banned
    WHERE $Selector = '$Delete_q' 
   ";
   $dbh -> do_query($query);

// ----------------------------------------------
// If we unbanned a user, lets send them a message.
   if ($Selector == "Username") {

      $Sender  = $user['U_Username'];
      $To      = $Unban;
      $Subject = "You have been unbanned.";
      $mess    = "You have been unbanned and are now free to make posts or send private messages again.";

      $html -> send_message($Sender,$To,$Subject,$mess);

   }


// -----------------------------------
// Now lets inform all mods and admins
   $Selector = str_replace("B_","",$Selector);
   $Sender  = $user['U_Username'];
   $Subject = "$Selector unbanned.";
   $mess    = "$Sender has unbanned the $Selector '$Delete'";
   $html -> send_message($Sender,'',$Subject,$mess,'A_M_GROUP');


// ------------------------
// Send them a page 
   $html -> send_header ("$Selector unbanned.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("$Selector unbanned.");

   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "$Selector unbanned.  You will now be returned to the main administration page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
