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
   require ("languages/${$config['cookieprefix']."w3t_language"}/dopoll.php");

// ----------------------------------
// Make sure they voted for something
   $html = new html;
   if (!$option) {
      $html -> not_right($ubbt_lang['NO_VOTE'],$Cat);
   }

// -----------------------------------------------------------------------------
// If we are only allowed registered users to vote, then we need to authenticate
   if (!$config['whovote']) {

   // -----------------
   // Get the user info
      $userob = new user;
      $user = $userob -> authenticate();                                                                
      if (!$user['U_Username']) {
         $html -> not_right($ubbt_lang['POLL_REGED'],$Cat);
      }
   }
   $IP = find_environmental('REMOTE_ADDR');

// --------------------------------------------------------------------------
// If we allow all to vote, we check if the IP has voted, otherwise we check
// for the username
   if ($config['whovote']) {
      $checker = addslashes($IP);
   }
   else {
      $checker = addslashes($user['U_Username']);
   }
 
   $pollname_q = addslashes($pollname);

   $query = "
     SELECT P_IP
     FROM   {$config['tbprefix']}PollData
     WHERE  P_IP = '$checker'
     AND    P_Name = '$pollname_q'
   ";
   $sth = $dbh ->  do_query($query);
   list($check) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if ($check) {
      if ($config['whovote']) {
         $html -> not_right($ubbt_lang['POLL_IP'],$Cat);
      }
      else {
         $html -> not_right($ubbt_lang['POLL_USER'],$Cat);
      }
   }

   $query = "
      INSERT INTO {$config['tbprefix']}PollData
      (P_Name,P_Number,P_IP)
      VALUES ('$pollname_q','$option','$checker')
   ";
   $dbh -> do_query($query);

// We want to return them to the results, so we need to hack some stuff
// out of the referer variable
   $ref = find_environmental('HTTP_REFERER');
   $script['0'] = "";
   preg_match("/show(.*).php/",$ref,$script);
   $prog = "show" . $script['1'];

// -----------------------------------------------------
// If we didn't get the program out, we just return them
   if (!$prog) {
      $html -> not_right("Your vote has been counted, but we were unable to extract the Referer variable from your browser.  Please use your back button to return to the poll.",$Cat);
   }
   else {  
      $ref = preg_replace("/show(.*).php/","viewpoll.php",$ref);
      $ref .="&what=$prog&vc=1&poll=$pollname&PHPSESSID=$PHPSESSID";
  }

  header("Location: $ref");

