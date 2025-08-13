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

   if (!$Who) {
      $html -> not_right("You must select someone/something to ban");
   }

// -------------------------------------
// Let's see if they are already banned
   $Reason_q   = addslashes($Reason);
   $Who_q      = addslashes($Who);

   if ($Banby == "user") {
      $Selector = "B_Username";
      $Username_q = $Who_q;
      $Hostname_q = addslashes("NULL");
   }
   else {
      $Selector = "B_Hostname";
      $Username_q = addslashes("NULL");
      $Hostname_q = $Who_q;
   }

   $query = "
    SELECT B_Username,B_Hostname,B_Reason
    FROM  {$config['tbprefix']}Banned
    WHERE $Selector = '$Who_q'
   ";
   $sth = $dbh -> do_query($query);
   list($Usercheck,$Hostcheck,$Reasonquote) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if ( ($Usercheck) || ($Hostcheck) ){
      $html -> not_right("This $Selector has already been banned.  The reason was: $Reasonquote",$Cat);
   }

   $query = "
     SELECT U_Status
     FROM {$config['tbprefix']}Users
     WHERE U_Username = '$Who_q'
   ";
   $sth = $dbh -> do_query($query);
   list($Userstatus) = $dbh -> fetch_array($sth);
   if ( ($Userstatus == "Administrator") || ($Userstatus == "Moderator") ) {
      $html -> not_right("You cannot ban an admin or moderator!");
   }
// ----------------------------------------------
// They weren't already banned, so let's ban them.
   $query = "
      INSERT INTO {$config['tbprefix']}Banned
      (B_Username,B_Hostname,B_Reason)
      VALUES ('$Username_q','$Hostname_q','$Reason_q')
   ";
   $dbh -> do_query($query);

// ----------------------------------------------
// If we banned a user, lets send them a message.
   if ($Selector == "Username") {
      $Selector = str_replace("B_","",$Selector);
      $Sender  = $user['U_Username'];
      $To      = $Who;
      $Subject = "You have been banned";
      $mess    = "You have been banned from making any new posts or sending any private messages.  The reason for this ban was: $Reason";

      $html -> send_message($Sender,$To,$Subject,$mess);
   }

// ---------------------------
// Lets inform the admin group
   $Selector = str_replace("B_","",$Selector);
   $Sender  = $user['U_Username'];
   $Subject = "Banned $Selector";
   $mess    = "$Sender has banned the $Selector '$Who'.  The Reason for this ban was: $Reason";

   $html -> send_message($Sender,"",$Subject,$mess,"ADMIN_GROUP");


// ------------------------
// Send them a page 
   $html -> send_header ("$Selector has been banned",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("$Selector has been banned");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The $Selector '$Who' has been banned.  You will now be returned to the main administration page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
