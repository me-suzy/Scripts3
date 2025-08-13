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
   require ("languages/${$config['cookieprefix']."w3t_language"}/mess_handler.php");


// ######################################################################
// delete_mess function - Delete this message
// ######################################################################

   function delete_mess($Username="",$Password="",$Number="",$Subject="",$Cat="",$box) {

      global $dbh, $config, $theme, $ubbt_lang, $PHPSESSID;

   // ------------------
   // Delete the Message
      $Username_q = addslashes($Username);
		$Number = addslashes($Number);
      $query = " 
         DELETE FROM {$config['tbprefix']}Messages
         WHERE        M_Username = '$Username_q'
         AND          M_Number   = '$Number'
      ";
      $dbh -> do_query($query); 

   // -------------------------------
   // Return them to their start page
      header ("Location: {$config['phpurl']}/viewmessages.php?Cat=$Cat&box=$box&PHPSESSID=$PHPSESSID");

   }

// --------------------------------
// END OF THE DELETE_MESS FUNCTION


// ####################################################################
// reply_mess function - Reply to this message
// ####################################################################

   function reply_mess($Username="",$Password="",$Number="",$Subject="",$Sendto="",$TextCols="",$TextRows="",$Pselected="",$user="",$Cat="",$box="") {

      global $dbh, $config, $theme, $ubbt_lang, $userob, $html, $thispath, $tempstyle;

   // ------------------------
   // Check if they are banned
      $userob -> check_ban($user['U_Username'],$Cat);


   // -------------------------------------------------
   // Find out if this user is taking private messages
      $Sendto = rawurldecode($Sendto);
      $Sendto_q = addslashes($Sendto);
      if ($Sendto) {
         $query = "
           SELECT U_AcceptPriv
           FROM   {$config['tbprefix']}Users
           WHERE  U_Username = '$Sendto_q'
         ";
         $sth = $dbh -> do_query($query);
         list($AcceptPriv) = $dbh -> fetch_array($sth);
         $dbh -> finish_sth($sth);
   
         if ( ($user['U_Status'] != "Administrator") && ($AcceptPriv == "no") ) {
            $html -> not_right($ubbt_lang['NO_PRIVATE'],$Cat);
         }
      }
 
      if (!$TextCols) { $TextCols = $theme['TextCols']; }
      if (!$TextRows) { $TextRows = $theme['TextRows']; }
   // -------------------------------------
   // Give them a form to reply to a message
      $html = new html;
      $html -> send_header("{$ubbt_lang['REPLY_HEAD']} $Sendto",$Cat,0,$user);
      if (!preg_match("/^Re:/",$Subject)){
         $Subject = "Re: $Subject";
      }
      $Subject = str_replace("\"","&quot;",$Subject);

   // --------------------------------
   // Show them what it is in reply to
      $Username_q = addslashes($Username);
		$Number = addslashes($Number);
      $query = " 
         SELECT M_Message
         FROM   {$config['tbprefix']}Messages
         WHERE  M_Number   = '$Number'
         AND    M_Username = '$Username_q'
      ";
      $sth = $dbh -> do_query($query);
      list($Message) = $dbh -> fetch_array($sth);

		if (!$debug) {
      	include("$thispath/templates/$tempstyle/mess_handler.tmpl");
		}
      $html -> send_footer();

   }

// -------------------------------
// END OF THE REPLY_MESS FUNCTION


// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Groups,U_EReplies,U_TextCols,U_TextRows,U_Preview");
   $Username = $user['U_Username'];

   $TextCols = $user['TextCols'];
   $TextRows = $user['TextRows'];
   if (!$TextCols) { $TextCols = $user['U_TextCols']; }
   if (!$TextRows) { $TextRows = $user['U_TextRows']; }

// -----------------------------------------------------
// Let's find out if they get the default preview or not.
   $Preview = $user['U_Preview'];
   if (!$Preview) { $Preview = $config['Preview']; }
   if ( ($Preview == 1) || ($Preview == "on") ){
      $Pselected = "checked=\"checked\"";
   }

// --------------------------------------------------------
// If they selected delete this messages, execute that sub
   if($deletemess){
      delete_mess($Username,$Password,$Number,$Subject,$Cat,$box);
   }

// --------------------------------------------------------
// If they selected reply to this message, execute that sub
   if($replymess){
      reply_mess($Username,$Password,$Number,$Subject,$Sendto,$TextCols,$TextRows,$Pselected,$user,$Cat,$box);
      exit();
   }


// ----------------------------------------------------------
// If they selected to return to start page, execute that sub
   if ($returnstart){
      header("Location: {$config['phpurl']}/viewmessages.php?Cat=$Cat&box=$box&PHPSESSID=$PHPSESSID");
      exit();
   }


?>
