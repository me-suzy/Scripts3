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
   require ("main.inc.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $Username = $user['U_Username'];

   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// -------------------------------
// Make sure they own this message
   $Username_q = addslashes($Username);

// ---------------------------------------------
// Track the total number of delete new messages
   $Total = 0;

// ---------------------------
// Delete all selected messages
   for($i=0;$i<=$total;$i++){
      $name = "box$i";
      if("$HTTP_POST_VARS[$name]"){
      
         $number = "$HTTP_POST_VARS[$name]";
         $number = str_replace("-NEW","",$number);
         $query = " 
            DELETE FROM {$config['tbprefix']}Messages 
            WHERE       M_Number = '$number'
            AND         M_Username = '$Username_q'
         "; 
         $rc = $dbh -> do_query($query);

      // -----------------------------------------------------------------
      // If the value of this number has a -NEW at the end of it then they
      // are deleting an unread message.  But we need to make sure this
      // was successfully(sp?) deleted.
         if ( (strstr("$HTTP_POST_VARS[$name]","-NEW")) && ($rc) ){
            $Total++;
         }
      }
   }

// -------------------------------
// Update the number of unread PMs
   $query = " 
     UPDATE {$config['tbprefix']}Users
     SET    U_Privates = U_Privates - $Total
     WHERE  U_Username = '$Username_q'
   ";
   $dbh -> do_query($query); 

// -------------------------------
// Send em back to the start page
   header("Location: {$config['phpurl']}/viewmessages.php?Cat=$Cat&box=$box&PHPSESSID=$PHPSESSID");
