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
   require ("languages/${$config['cookieprefix']."w3t_language"}/viewmessage.php");

// Prepare language variable for later "requires" - necessary for PHP3
   $Language = ${$config['cookieprefix']."w3t_language"}; 

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_TimeOffset");
   $Username = $user['U_Username'];


   if (!$user['U_Username']) {
      $html = new html;
      $html -> not_right($ubbt_lang['NO_AUTH'],$Cat);
   }

// ----------------------------------------------------------------------
// If this is an unread private message then we decrease the total number
// of unread Privates by 1 before calling send header
   if ( ($status == "N") || ($status == "C") ) {
      $user['U_Privates']--;
   }

// ---------------------------------
// Get the message from the database
   $Username_q = addslashes($Username);
	$message = addslashes($message);
   $query = " 
     SELECT M_Subject,M_Sender,M_Message,M_Sent,M_Status
     FROM   {$config['tbprefix']}Messages
     WHERE  M_Username = '$Username_q'
     AND    M_Number = '$message'
   "; 
   $sth = $dbh -> do_query($query);

// ----------------
// Assign the stuff 
   list ($Subject,$Sendto,$Message,$When,$MStatus) = $dbh -> fetch_array($sth); 
   $dbh -> finish_sth($sth);

// ----------------
// Convert the time
   $html = new html;
   $time = $html -> convert_time($When,$user['U_TimeOffset']);

// ---------------------------
// Now show them their message
   $html -> send_header("$Subject",$Cat,0,$user);

   $User = rawurlencode($Sendto);
   if ($MStatus != "X") {
      $col1 = $ubbt_lang['TEXT_FROM'];
      $col2 = $ubbt_lang['TEXT_REC']; 
   }
   else {
      $col1 = $ubbt_lang['SENT_TO'];
      $col2 = $ubbt_lang['SENT'];
   } 

   if (($config['private']) && ($Subject != $ubbt_lang['READ_RECEIPT']) && ($MStatus != "X")) {
      $replymessagebutton = "<input type=\"submit\" name = \"replymess\" value = \"{$ubbt_lang['REPLY_MESS']}\" class=\"buttons\" />";
   }

   include("$thispath/templates/$tempstyle/viewmessage.tmpl");

// ------------------------------------------------------------------
// Get rid of the N or C this message, so they know its no longer new
   if (($MStatus != "X") && ($MStatus != "R")) {
      $Username_q = addslashes($Username);
      $Blank_q    = " ";

      $query = " 
        UPDATE {$config['tbprefix']}Messages
        SET    M_Status   = '$Blank_q'
        WHERE  M_Username = '$Username_q' 
        AND    M_Number   = '$message'
      ";
      $dbh -> do_query($query);
   }

// ----------------------------------------------------------------
// If this is a unread message then we decrease their PM field by 1
   if ( ($MStatus == "C") || ($MStatus == "N") ) {
      $query = " 
        UPDATE {$config['tbprefix']}Users
        SET    U_Privates = U_Privates -1
        WHERE  U_Username = '$Username_q'
      ";
      $dbh -> do_query($query);
   }
  
// ----------------------------------------------------------------
// Notify sender that message has been read (by Gerrit Jahn)
   if ($MStatus == "C") {
      $Username_q = addslashes($Sendto);
      $Sender_q   = addslashes($Username);
      $Status_q   = addslashes("N");
      $OrigSender = $Sender_q;

   // ----------------------------------------------
   // Check to see if the username is in our database
  
      $query = "
        SELECT U_Username,U_Email,U_Notify, U_Language
        FROM   {$config['tbprefix']}Users
        WHERE  U_Username = '$Username_q'
      ";
      $sth = $dbh -> do_query($query);

      list ($Thisuser,$Email,$Notify,$Language) = $dbh -> fetch_array($sth);
      $dbh -> finish_sth($sth);

   // -----------------------------
   // Switch to the proper language
      if (!$Language) {
         $Language = $config['language'];
      }
      require "languages/$Language/viewmessage.php";
   
      $Subject_q  = addslashes($ubbt_lang['READ_RECEIPT']);
      $Message_q  = addslashes("{$ubbt_lang['RR_YOUR_MESS']} <b>$OrigSender</b> {$ubbt_lang['ABOUT']} <b>'$Subject'</b> {$ubbt_lang['RR_READ']}");
      $date = $html -> get_date(); 


   // INSERT DEPENDS ON AUTO_INCREMENT OR SEQUENCE
      if ($config['dbtype'] == "mysql") {
         $query = "
            INSERT INTO {$config['tbprefix']}Messages
            (M_Username,M_Status,M_Subject,M_Sender,M_Message,M_Sent)
            VALUES ('$Username_q', '$Status_q', '$Subject_q', '$Sender_q', '$Message_q', $date)
         ";
         $dbh -> do_query($query);
      }
      else {
         $seq = "nextval('M_seq')"; 
         $query = "
            INSERT INTO {$config['tbprefix']}Messages
            (M_Username,M_Status,M_Subject,M_Sender,M_Message,M_Sent)
            VALUES ('$seq','$Username_q', '$Status_q', '$Subject_q', '$Sender_q', '$Message_q', $date)
         ";
         $dbh -> do_query($query);
      }

   // --------------------------------------------
   // Update this users total number of unread PMs
      $query = " 
        UPDATE {$config['tbprefix']}Users
        SET    U_Privates = U_Privates + 1
        WHERE  U_Username = '$Username_q'
      "; 
      $dbh -> do_query($query);
   
   // -------------------------------------------------------------
   // Now lets let them know they got a private message if they chose 
   // to be notified
  
      $mailer = new mailer; 
      if ($Notify == "On"){
         $header = $mailer -> headers();
         $subject = $ubbt_lang['RR_NOTIFY'];
         $msg     = "{$ubbt_lang['RR_YOUR_MESS']} $OrigSender {$ubbt_lang['ABOUT']} '$Subject' {$ubbt_lang['RR_READ']}";
         $mailsend = mail("$Email","$subject","$msg",$header);  
      }
   
   // ----------------------------------
   // Switch back to the proper language
      $Language = $user['U_Language'];
      if (!$user['U_Language']) {
         $Language = $config['language'];
      }
   
      require "languages/$Language/viewmessage.php";

   }

// -----------
// Send footer
   $html -> send_footer();


