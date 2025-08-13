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
   $mailer = new mailer;

// -----------------------------
// Make sure they should be here
   if ($user['U_Status'] != 'Administrator'){
      $html -> not_right ("You must be logged in, and be a valid administrator to access this.",$Cat);
   }

// --------------------------------------------------------
// Lets cycle through the list and approve/delete the proper users 
   for ($i=0;$i<$totalusers;$i++) {
      $domail = 0;

   // Grab the options for each row
      $check = "newuser$i";
      $check2 = "reason$i";
      $usernum = $HTTP_POST_VARS[$check];
      $reason  = $HTTP_POST_VARS[$check2];
      list($option,$number) = split("-",$usernum);

   // Grab  the username/email for  this user
      $query = "
         SELECT U_Username,U_Email
         FROM   {$config['tbprefix']}Users
         WHERE  U_Number='$number'
      ";
      $sth = $dbh -> do_query($query);
      list($username,$email) = $dbh -> fetch_array($sth);

   // Approving user, approve and set up email
      if ($option == "approve") {
         $query = "
            UPDATE {$config['tbprefix']}Users
            SET    U_Approved='yes'
            WHERE  U_Number='$number'
         ";
         $dbh -> do_query($query);
         $subject = "{$config['title']} registration approved";
         $msg     = "Your registration of '$username' at {$config['phpurl']}/ubbthreads.php has been approved.";
         $domail = 1;
      }

   // Deleting user, delete (only setup email if reason specified)
      if ($option == "delete") {
         $query = "
            DELETE FROM {$config['tbprefix']}Users
            WHERE  U_Number='$number'
         ";
         $dbh -> do_query($query);
         if ($reason) {
           $subject = "{$config['title']} registration denied";
           $msg     = "Your registration of '$username' at {$config['phpurl']}/ubbthreads.php has been denied.  The reason for this is below.\n\n$reason";
           $domail = 1;
         }
      }

    // Now send email if necessary
      if ($domail) {
         $to      = $email;
         $header = $mailer -> headers();
         mail("$to","$subject","$msg",$header);
      }
   }

// ------------------------
// Send them a page 
   $html -> send_header ("Users approved/deleted.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Posts approved/deleted.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"onbody\">";  
   echo "All checked users have been approved or deleted and notified.  You will now be returned to the main administration page.";
   echo "</TD></TR></TABLE>";
   $html -> send_admin_footer();


?>
