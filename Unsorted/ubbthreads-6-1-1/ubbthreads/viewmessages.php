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
   require ("./main.inc.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_TimeOffset, U_Privates");
   $Username = $user['U_Username'];

   $html = new html;

// -----------------------------------------
// require the language file for this script
   require "languages/${$config['cookieprefix']."w3t_language"}/viewmessages.php";

   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// ---------------------------------------------------------
// Ok, we found the profile, now lets put it all onto a page
   $html -> send_header("$ubbt_lang[$box]",$Cat,0,$user);

// --------------------------------
// Grab the proper private messages
   $field1 = $ubbt_lang['TEXT_FROM'];
   $field2 = $ubbt_lang['TEXT_REC'];
   if ($box == "received") {
      $extra = "<>";
   }
   else {
      $extra = "=";
      $field1 = $ubbt_lang['SENT_TO'];
      $field2 = $ubbt_lang['SENT'];
   }

// -------------------------
// Get any private messages.
   $username_q = addslashes($Username);
   $query = "
    SELECT M_Status, M_Subject, M_Sender, M_Sent, M_Number
    FROM   {$config['tbprefix']}Messages
    WHERE  M_Username = '$username_q'
    AND    M_Status $extra 'X'
    ORDER BY M_Sent DESC
   ";
   $sth = $dbh -> do_query($query);

   $i = 0;

// -------------------------------------------------------------------
// We need to keep track of the number of new private messages just in
// case this number ever gets dorked up
   $totalnew = 0;

   while (list($read, $subject, $sender, $when, $number) = $dbh -> fetch_array($sth)) {
      $senton = $html -> convert_time($when, $user['U_TimeOffset']);
      if ($read == "N") {
         $type = $ubbt_lang['TEXT_NEW'];
      }
      elseif ($read == "R") {
         $type = $ubbt_lang['TEXT_REPLIED'];
      }
      elseif ($read == "C") {
         $type = $ubbt_lang['TEXT_NEW'];
      }
      else {
         $type = "&nbsp;";
      }
      $encsender = rawurlencode($sender);

   // --------------------------------------------------------------------
   // If this is a new post (N or C) then we need to add this to the value
   // so delete.php knows that this was a new message
   // Also increment the total number of new privates messages by 1
      $formvalue = $number;
      if ( ($read == "N") || ($read == "C") ) {
         $formvalue = $number . "-NEW";
         $totalnew++;
      }
      $message[$i]['type'] = $type;
      $message[$i]['number'] = $number;
      $message[$i]['read']   = $read;
      $message[$i]['subject'] = $subject;
      $message[$i]['encsender'] = $encsender;
      $message[$i]['sender'] = $sender;
      $message[$i]['senton'] = $senton;
      $message[$i]['checkbox'] = "box$i";
      $message[$i]['formvalue'] = $formvalue;

      $i++;
   }

   $messagesize = sizeof($message);
   include("$thispath/templates/$tempstyle/viewmessages.tmpl");

// ------------------------------------------------------------------
// If the total number of new privates in their profile doesn't match
// the total we just counted, then update it
   if ($user['U_Privates'] != $totalnew) {
      $query = "
       UPDATE {$config['tbprefix']}Users
       SET    U_Privates = '$totalnew'
       WHERE  U_Username = '$username_q'
      ";
      $dbh -> do_query($query);
   }


// ----------------
// send the footer
   $html -> send_footer();

?>
