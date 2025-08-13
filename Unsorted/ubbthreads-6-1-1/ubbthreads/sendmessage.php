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
   require ("languages/${$config['cookieprefix']."w3t_language"}/sendmessage.php");

// Make sure this isn't being called via GET
   if ($GLOBALS['REQUEST_METHOD'] == "GET") {
      exit;
   }

// --------------------
// Assign the variables
   if ($AddressBook != $ubbt_lang['CHOOSE_ADD']) {
    $User = $AddressBook;
   }

   $RawSubject = $Subject;
   $RawBody    = $Message;

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Language,U_TextCols,U_TextRows"); 
   $Username = $user['U_Username'];

// --------------------
// Assign some defaults
   $html = new html;
   $TextCols = $user['U_TextCols'];
   $TextRows = $user['U_TextRows'];
   if (!$TextCols) { $TextCols = $theme['TextCols']; }
   if (!$TextRows) { $TextRows = $theme['TextRows']; }

   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// ------------------------------------------------------
// If they seelcted delete the name from the address book
   if ($textdelete) {
      if ($AddressBook != $ubbt_lang['CHOOSE_ADD']) {
         $Username_q = addslashes($user['U_Username']);
         $Deleted    = addslashes($AddressBook);
         $query = " 
            DELETE FROM {$config['tbprefix']}AddressBook
            WHERE  Add_Owner = '$Username_q'
            AND    Add_Member = '$Deleted'
         ";
         $dbh -> do_query($query); 
      }
      header("Location: {$config['phpurl']}/sendprivate.php?Cat=$Cat&PHPSESSID=$PHPSESSID");
      exit();
   }

// -------------------------------------
// Make sure there is a subject and body
   if ( (preg_match("/^\s*$/",$Subject)) || ($Message == "") ) {
      $html -> not_right($ubbt_lang['ALL_FIELDS'],$Cat);
   } 
   

// ----------------------------------------------
// Check to see if the username is in our database
   $Username_q = addslashes($User);
   $query = " 
     SELECT U_Username,U_Email,U_Notify, U_Language, U_AcceptPriv
     FROM   {$config['tbprefix']}Users
     WHERE  U_Username = '$Username_q'
   "; 
   $sth = $dbh -> do_query($query);
   list ($Username,$Email,$Notify,$Language,$AcceptPriv) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// -------------------------------
// Check if they want this message
   if ($AcceptPriv == "no") {
      $html -> not_right($ubbt_lang['NO_PRIVATE'],$Cat);
   }

// ----------------------------------------------
// We didn't find that Username, so let them know
   if (!$Username){
      $html -> not_right($ubbt_lang['NO_RECORD'],$Cat);
   }


// ------------
// Get the time
   $date = $html -> get_date();

// ------------------------------
// Get rid of HTML in the subject
   $Subject = str_replace(">","&gt;",$Subject);
   $Subject = str_replace("<","&lt;",$Subject);
   $Subject = str_replace("\"","&quot;",$Subject);

// ---------------------------
// Get rid of HTML in the body
   $Message = str_replace(">","&gt;",$Message);
   $Message = str_replace("<","&lt;",$Message);
   $Message = str_replace("&lt;br&gt;","<br />",$Message);
   $Message = str_replace("\"","&quot;",$Message);

   $FormBody = $Message;
   $FormSubject = $Subject;

// ------------------
// Markup the Message
   $Message = $html -> do_markup($Message);
   $PrevMess =str_replace("\n","<br />",$Message);

// --------------------------------------------------------------
// Now if we are doing a preview of the post, we do this
   if ($preview) {
      $html -> send_header("{$ubbt_lang['MESS_PREV']} $User",$Cat,0,$user);

   // -------------------------------
   // Allow them to make some changes
      $RawBody = str_replace("<br />","\n",$RawBody);
      $RawSubject =str_replace("\"","&quot;",$RawSubject);

		if (!$debug) {
      	include("$thispath/templates/$tempstyle/sendmessage.tmpl");
		}
      $html -> send_footer();
      exit();
   } 

// ------------------------------------------------------
// Insert the message into the database marked as N - New
   $Username_q = addslashes($User);
   $Subject_q   = addslashes($Subject);
   $Sender_q   = addslashes($user['U_Username']);
   $Message = str_replace("\n","<br />",$Message);
   $Message_q  = addslashes($Message);

   if ($receipt == "yes") {
      $Status_q = "C";
   }
   else {
      $Status_q = "N";
   }

// --------------------------
// Insert it into the database
// SQL INSERT depends  on AUTO_INCREMENT or SEQUENCE
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

// ----------------------------------------------------
// Increment the recipients total number of unread pm's
   $query = "
      UPDATE {$config['tbprefix']}Users
      SET    U_Privates = U_Privates + 1
      WHERE  U_Username = '$Username_q'
   "; 
   $dbh -> do_query($query);

// -------------------------------------------------------------
// Now lets let them know they got a private message if they chose 
// to be notified

   if ($Notify == "On"){

   // -----------------------------
   // Switch to the proper language
      if (!$Language) {
         $Language = $config['language'];
      }
      if ($Language) {
        require "languages/$Language/sendmessage.php";
      }
      $mailer = new mailer;
      $header = $mailer -> headers();
      $subject = $ubbt_lang['PRIV_SUB'];
      $msg     = $ubbt_lang['PRIV_BODY_R'];
      $mailsend = mail("$Email","$subject","$msg",$header);

    // -----------
    // Switch back
      if (!$user['U_Language']) {
         $user['U_Language'] = $config['language'];
      }
      if ($user['U_Language']) {
        require "languages/{$user['U_Language']}/sendmessage.php";
      }

   }

// ----------------------------------------------------------------
// Insert a copy into the database marked as X - "Xtra" Carbon Copy
// Hack by Gerrit Jahn February 2000
   if ($ccopy) {
      $To_q       = addslashes($user['U_Username']);
      $Status_q   = "X";
      //Subject_q  = addslashes($Subject);
      $Sender_q   = addslashes($User);
      //Message_q  = addslashes($Message);

   // SQL INSERT DEPENDS ON AUTO_INCREMENT OR SEQUENCE
      if ($config['dbtype'] == "mysql") {
         $query = "
            INSERT INTO {$config['tbprefix']}Messages
            (M_Username,M_Status,M_Subject,M_Sender,M_Message,M_Sent)
            VALUES ('$To_q', '$Status_q', '$Subject_q', '$Sender_q', '$Message_q', $date)
         ";
         $dbh -> do_query($query);
      }
      else {
         $seq = "nextval('M_seq')";
         $query = "
            INSERT INTO {$config['tbprefix']}Messages
            (M_Username,M_Status,M_Subject,M_Sender,M_Message,M_Sent)
            VALUES ('$seq','$To_q', '$Status_q', '$Subject_q', '$Sender_q', '$Message_q', $date)
         ";
         $dbh -> do_query($query);
      }
   }

// ------------------------------------------------------
// Add the Recipient to AddressBook\
   if (($AddBook) && ($User != "")){
      $Owner       = $user['U_Username'];
      $Member      = $User;
      $Owner_q     = addslashes($Owner);
      $Member_q    = addslashes($Member);   

   // ----------------------------------------------
   // Check to see if the Recipient is already our AddressBook
      $query = " 
         SELECT Add_Owner,Add_Member
         FROM   {$config['tbprefix']}AddressBook
         WHERE  Add_Owner = '$Owner_q' AND Add_Member = '$Member_q'
      "; 
      $sth = $dbh -> do_query($query);
      list($Check) = $dbh -> fetch_array($sth);
      $dbh -> finish_sth($sth);

   // ----------------------------------------------
   // This user does not take private messages (Admin override)
   // or They already have that user
      if ((($user['U_Status'] != "Administrator") && ($AcceptPriv == "no")) || (!$Check)) {

      // ------------------------------------------------------
      // Insert the details into the database
         $query = "
            INSERT INTO {$config['tbprefix']}AddressBook
            (Add_Owner, Add_Member)
            VALUES ('$Owner_q', '$Member_q')
         "; 
         $dbh -> do_query($query);
      } 
  }
 

// -------------------------------------------------------
// Give them a sucess message and return them to the forum
   if ( ($what == "showthreaded") || ($what == "showflat") ) {

      $html -> send_header($ubbt_lang['MESS_SENT'],$Cat,"<meta http-equiv=\"Refresh\" CONTENT=\"5;url={$config['phpurl']}/$what.php?Cat=$Cat&Board=$Board&Number=$Number&page=$page&view=$view&sb=$sb&o=$o&fpart=$fpart&vc=$vc\" />",$user);

   } 
   elseif ($what == "foruminfo") {
      $html -> send_header("{$ubbt_lang['MESS_SENT']}",$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/foruminfo.php?Cat=$Cat&Board=$Board\" />",$user);
   }
   elseif ($what == "login") {
      $html -> start_page($Cat); 
      exit();
   }
   elseif ($what == "online") {
      $html -> send_header($ubbt_lang['MESS_SENT'],$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/online.php?Cat=$Cat\" />",$user);
   }
   elseif ($what == "ubbthreads"){
      $html -> send_header($ubbt_lang['MESS_SENT'],$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&Board=$Board&page=$page&view=$view&sb=$sb&o=$o\" />",$user);
   }
   else {
      $main = "ubbthreads";
      if ($config['catsonly']) {
         $main = "categories";
      }
      if (!$user['U_FrontPage']) {
         $user['U_FrontPage'] = $main;
      }
      $html -> send_header($ubbt_lang['MESS_SENT'],$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/{$user['U_FrontPage']}.php?Cat=$Cat\" />",$user);
   }    
	if (!$debug) {
   	include("$thispath/templates/$tempstyle/sendmessage_confirm.tmpl");
	}

// ---------------
// Send the footer
   $html -> send_footer();
