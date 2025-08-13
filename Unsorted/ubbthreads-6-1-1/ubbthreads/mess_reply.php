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
   require ("languages/${$config['cookieprefix']."w3t_language"}/mess_reply.php");

// Make sure this isn't being called via GET
   if ($GLOBALS['REQUEST_METHOD'] == "GET") {
      exit;
   }

// Define the language variable - Required for PHP3
   $Language = ${$config['cookieprefix']."w3t_language"};

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Preview,U_TextCols,U_TextRows");
   $Username = $user['U_Username'];

// --------------------
// Assign the variables
   $RawSubject = $Subject;
   $RawBody = $Message;

// --------------------------
// Get rid of the line breaks
   $Rawbody = str_replace("<br />","\n",$Rawbody);

// ---------------------------------------
// Get rid of HTML in the subject and body
   $Subject = str_replace(">","&gt;",$Subject);
   $Subject = str_replace("<","&lt;",$Subject);
   $Subject = str_replace("\"","&quot;",$Subject);
   $Message = str_replace(">","&gt;",$Message);
   $Message = str_replace("<","&lt;",$Message);
   $Message = str_replace("&lt;br&gt;","<br />",$Message);
   $Message = str_replace("\"","&quot;",$Message);

// ------------
// Get the date
   $html = new html;
   $date = $html -> get_date();

// -------------------------------------
// Make sure there is a default language
   if (!$Language) {
      $Language = $config['language'];
   }

   if (!$Username) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// -------------------------------------
// Make sure there is a subject and body
   if ( (preg_match("/^\s*$/",$Subject)) || ($Message == "") ) {
      $html -> not_right($ubbt_lang['ALL_FIELDS'],$Cat);
   }   

// -----------------------------------------------
// Find out if they get the default preview or not
   $Preview = $user['U_Preview'];
   if (!$Preview) { $Preview = $config['Preview']; }

   if ( ($Preview) || ($Preview == "on") ) {
      $Pselected = "checked=\"checked\"";
   }

// ----------------------
// Grab their preferences
   $TextCols = $user['U_TextCols'];
   $TextRows = $user['U_TextRows'];
   if (!$TextCols) { $TextCols = $theme['TextCols']; }
   if (!$TextRows) { $TextRows = $theme['TextRows']; }

// ------------------
// Markup the Message and Subject
   $FormMessage = $Message;
   $Message = $html -> do_markup($Message);
   
// ####################################################################
// preview_mess - Preview reply to this message
// ####################################################################

   if ($preview){

      if (!preg_match("/^Re:/",$Subject)){
         $Subject = "Re: $Subject";
      }

      $FormMessage = str_replace("\"","&quot;",$FormMessage);
      $FormSubject = $Subject;
      $FormSubject = str_replace("\"","&quot;",$FormSubject);
      $Message = str_replace("\n","<br />",$Message);

   // Allow them to make some changes
      $RawBody = str_replace("<br />","\n",$RawBody);
      $RawSubject =str_replace("\"","&quot;",$RawSubject);

      $html -> send_header("{$ubbt_lang['REPLY_PREV']} $Sendto",$Cat,0,$user);
		if (!$debug) {
      	include("$thispath/templates/$tempstyle/mess_reply.tmpl");
		}
      $html -> send_footer();

      exit();

   }

// --------------------------------------------------------
// Put the message into the database and mark it as N - New
   if ($receipt == "yes") {
      $Status_q   = "C";
   } 
   else {
      $Status_q   = "N";
   }
   $Message = str_replace("\n","<br />",$Message);
   $Username_q = addslashes($Username);
   $Subject_q  = addslashes($Subject);
   $Sendto_q   = addslashes($Sendto);
   $Message_q  = addslashes($Message);


// ------------------------------------------------
// Insert the message into the database
// DEPND ON AUTO_INCREMENT OR SEQUENCE
   $query =  "
         INSERT INTO {$config['tbprefix']}Messages
         (M_Username,M_Status,M_Subject,M_Sender,M_Message,M_Sent)
         VALUES ('$Sendto_q','$Status_q','$Subject_q','$Username_q','$Message_q',$date)
   ";
   $dbh -> do_query($query);

// ------------------------------
// Set the message to R - Replied
   $Status_q = "R";
	$Number = addslashes($Number);
 
   $query = " 
     UPDATE {$config['tbprefix']}Messages
     SET    M_Status = '$Status_q'
     WHERE  M_Number = '$Number'
   "; 
   $dbh -> do_query($query); 

// ----------------------------------------------------
// Increment the recipients total number of unread pm's
   $query = " 
     UPDATE {$config['tbprefix']}Users
     SET    U_Privates = U_Privates + 1
     WHERE  U_Username = '$Sendto_q'
   "; 
   $dbh -> do_query($query);

// --------------------------------------------------------------------------
// Now lets grab the email address and see if they want to have a notification
// that they received a private message
   $query = "
     SELECT U_Email,U_Notify,U_Language
     FROM   {$config['tbprefix']}Users
     WHERE  U_Username = '$Sendto_q'
   "; 
   $sth = $dbh -> do_query($query);
   list($Email,$Notify,$Language) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if ($Notify == "On"){

   // -----------------------------
   // Switch to the proper language
      if (!$Language) {
         $Language = $config['language'];
      }
      if ($Language) {
        require "{$config['path']}/languages/$Language/sendmessage.php";
      }
      $mailer = new mailer;
      $header = $mailer -> headers();
      $subject = $ubbt_lang['PRIV_SUB'];
      $msg     = $ubbt_lang['PRIV_BODY_R'];
      $mailsend = mail("$Email","$subject","$msg",$header);

   // -----------
   // Switch back
      if ($Language) {
         require "{$config['path']}/languages/$Language/sendmessage.php";
      }
   }                                    

// ----------------------------------------------------------------
// Insert a copy into the database marked as X - "Xtra" Carbon Copy
// Hack by Gerrit Jahn February 2000
   if ($ccopy) {
      $To_q       = addslashes($user['U_Username']);
      $Status_q   = "X";
      $Subject_q  = addslashes($Subject);
      $Sendto_q   = addslashes($Sendto);
      $Message_q  = addslashes($Message);

   // --------------------------------------------
   // INSERT DEPENDS ON AUTO_INCREMENT OR SEQUENCE
      if ($config['dbtype'] == "mysql") {
         $query = "
            INSERT INTO {$config['tbprefix']}Messages
            (M_Username,M_Status,M_Subject,M_Sender,M_Message,M_Sent)
            VALUES ('$To_q', '$Status_q', '$Subject_q', '$Sendto_q', '$Message_q', $date)
         ";
         $dbh -> do_query($query);
      }                      
      else {
         $seq = "nextval('M_seq')";
         $query = "
            INSERT INTO {$config['tbprefix']}Messages
            (M_Username,M_Status,M_Subject,M_Sender,M_Message,M_Sent)
            VALUES ('$seq','$To_q', '$Status_q', '$Subject_q', '$Sendto_q', '$Message_q', $date)
         ";
         $dbh -> do_query($query);
      }                      
   }

// ---------------------------
// Return to their start page
   header ("Location: {$config['phpurl']}/viewmessages.php?Cat=$Cat&box=$box&PHPSESSID=$PHPSESSID");
