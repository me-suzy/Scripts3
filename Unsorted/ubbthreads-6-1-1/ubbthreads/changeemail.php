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
   require ("languages/${$config['cookieprefix']."w3t_language"}/changeemail.php");

// Make sure this isn't being called via GET
   if ($GLOBALS['REQUEST_METHOD'] == "GET") {
      exit;
   }

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $Username = $user['U_Username'];

   $html = new html;
   if (!$user['U_Username']){
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// -----------------------
// Format the query words
   $Username_q   = addslashes($Username);
   $EReplies_q   = addslashes($EReplies);
   $Notify_q     = addslashes($Notify);
	$EmailFormat_q = addslashes($emailformat);
	$AdminEmails_q = addslashes($adminemails);

// --------------------------
// Update the User's profile
	$query = "
		UPDATE {$config['tbprefix']}Users
		SET U_EReplies   = '$EReplies_q',
		U_Notify         = '$Notify_q',
		U_AdminEmails    = '$AdminEmails_q',
		U_EmailFormat    = '$EmailFormat_q'
		WHERE U_Username = '$Username_q'
	";
   $dbh -> do_query($query);

// -----------------------------------------------------------------
// Now lets see if they are subscribing or unsubscribing to anything
   for ($i=1; $i<=$Totalsubs; $i++){

      $Board = $HTTP_POST_VARS[$i];
      list($Board,$Sub) = split("--SUB--",$Board);

   // --------------------------------------------------------------
   // If the board is unmarked then we need to unsubscribe this user
      if ($Sub == "NO") {
         $Board_q    = addslashes($Board);
         $Username_q = addslashes($Username);
         $query = " 
            DELETE FROM {$config['tbprefix']}Subscribe
            WHERE S_Username = '$Username_q' 
            AND   S_Board = '$Board_q'
         ";
         $dbh -> do_query($query);
      }

   // ---------------------------------------------------------
   // If they board is marked then we need to subscribe the user
   // but we need to make sure they aren't already subscribed 
      else {

         $Board_q    = addslashes($Board);
         $Username_q = addslashes($Username);
         $query = " 
            SELECT S_Username 
            FROM   {$config['tbprefix']}Subscribe
            WHERE  S_Username = '$Username_q' 
            AND    S_Board    = '$Board_q'
         "; 
         $sth = $dbh -> do_query($query);
         list($check) = $dbh -> fetch_array($sth); 
         $dbh -> finish_sth($sth);

      // ---------------------------------------------------------
      // They were not already subscribed so we now subscribe them
         if (!$check) {
         // ----------------------------------------
         // Find out what the current post number is
            $query = " 
               SELECT B_Number
               FROM   {$config['tbprefix']}Posts
               WHERE  B_Board = '$Board_q'
               ORDER  BY B_Number DESC
            ";
            $sth = $dbh -> do_query($query);
            list($Last) = $dbh -> fetch_array($sth); 
            $dbh -> finish_sth($sth);

            if ($Last < 1) { $Last = 1; }

         // ---------------------
         // Now we subscribe them
            $query = " 
               INSERT INTO {$config['tbprefix']}Subscribe
               (S_Username,S_Board,S_Last)
               VALUES ('$Username_q','$Board_q','$Last')
            "; 
            $dbh -> do_query($query); 
         }

      }

   }


// ---------------------------------------------------
// Send them to their start page with the confirmation
   $html = new html;
   $html -> start_page($Cat);
