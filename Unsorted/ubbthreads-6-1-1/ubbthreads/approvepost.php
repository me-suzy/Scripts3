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
   require ("languages/${$config['cookieprefix']."w3t_language"}/approvepost.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Language");
   $html = new html;

   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// --------------------------
// Check if they are an Admin
   if (($user['U_Status'] != 'Administrator') && ($user['U_Status'] != 'Moderator')) {
      $html -> not_right($ubbt_lang['NO_ADMOD'],$Cat);
   }

// ---------------------------------
// Check if they moderate this board
   $Username_q = addslashes($user['U_Username']);
   $Board_q    = addslashes($Board);
   $query = "
      SELECT Mod_Board
      FROM   {$config['tbprefix']}Moderators
      WHERE  Mod_Username = '$Username_q'
      AND    Mod_Board    = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);
   list($check) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);
   if ( ($user['U_Status'] != 'Administrator') && (!$check) ) {
      $html -> not_right($ubbt_lang['NO_ADMOD'],$Cat);
   }

// -------------------
// Update the database
   $Approved_q = addslashes("yes");
	$Number = addslashes($Number);
   $query = " 
      UPDATE {$config['tbprefix']}Posts 
      SET    B_Approved = '$Approved_q' 
      WHERE  B_Number = '$Number'
      AND    B_Board  = '$Board_q'
   ";
   $dbh -> do_query($query); 

// -----------------------------------------------------------
// If this is a reply then we need to check it's parent and see
// if it should be mailed to the starting post's owner
// Also, set the number of replies to +1
   $query = "
      SELECT B_Username,B_Parent,B_Subject,B_Body,B_Main
      FROM  {$config['tbprefix']}Posts 
      WHERE B_Number = '$Number'
      AND   B_Board  = '$Board_q'
   ";
   $sth = $dbh -> do_query($query); 
   list($Username,$Parent,$Subject,$Body,$Main) = $dbh -> fetch_array($sth); 
   $dbh -> finish_sth($sth);

// ----------------------------
// Bump the total posts up by 1
   $date    = $html -> get_date();
   $Username_q = addslashes($Username);

// ---------------------------------------------------------
// If this is a new thread, we bump up the thread total by 1
   if ($Main == $Number) {
      $extra = ",Bo_Threads = Bo_Threads + 1";
   }

// -------------------------------------------
// Need to update the last post on this board
   $query = "
         SELECT B_Main,B_Number
         FROM {$config['tbprefix']}Posts
         WHERE B_Board = '$Board_q'
         AND   B_Approved = 'yes'
         ORDER BY B_Number DESC
         LIMIT 1
   ";
   $sth = $dbh -> do_query($query);
   list ($lastmain,$lastnumber) = $dbh -> fetch_array($sth);
   $lastuser = addslashes($lastuser);

   $query = "
      UPDATE {$config['tbprefix']}Boards
      SET   Bo_Total = Bo_Total + 1,
            Bo_Poster = '$Username_q',
            Bo_Last = $date,
            Bo_LastMain = $lastmain,
            Bo_LastNumber = $lastnumber
            $extra
      WHERE Bo_Keyword = '$Board_q'
   ";
   $dbh -> do_query($query);


   if ($Main != $Number) {
      $query = " 
         UPDATE {$config['tbprefix']}Posts
         SET    B_Replies = B_Replies +1
         WHERE  B_Number  = '$Main'
      "; 
      $dbh -> do_query($query);
   }

   $query = " 
    SELECT B_Username,B_Subject,B_Mail
    FROM  {$config['tbprefix']}Posts 
    WHERE B_Number = '$Parent'
    AND   B_Board  = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);
   list($MailUser,$PostSubject,$Checkmail) = $dbh -> fetch_array($sth); 
   $dbh -> finish_sth($sth);

// -------------------------------------------------
// If Mail was set then we send an email to that user
   if ( ($Checkmail == 1) ) {

      $MailUser_q = addslashes($MailUser);

      $query = " 
         SELECT U_Email, U_Language,U_EmailFormat
         FROM  {$config['tbprefix']}Users
         WHERE U_Username = '$MailUser_q'
      ";
      $sth = $dbh -> do_query($query);
      list($Mailto,$Language,$emailformat) = $dbh -> fetch_array($sth); 
      $dbh -> finish_sth($sth);

   // -------------------------------------------
   // We need to make sure this user still exists
      if ($Mailto){

         if (!$Language) { $Language = $config['language'];}
         require "{$config['path']}/languages/$Language/approve.php";

         $EmailBody = $Body;
      // If we are sending HTML email then we need the full path
      // to images.
         if ($emailformat == "HTML") {
            $EmailBody = str_replace("\"{$config['images']}","\"{$config['imageurl']}",$EmailBody);
            $msg     = "$postername {$ubbt_lang['REPLY_BOD']}<br><br><a href=\"{$config['phpurl']}/showthreaded.php?Cat=$Cat&Board=$Board&Number=$Mnumber\">{$config['phpurl']}/showthreaded.php?Cat=$Cat&Board=$Board&Number=$Mnumber</a><br><br>$EmailBody";
         }
         else {
            $EmailBody = str_replace("<br />","\n",$EmailBody);
            $msg     = "$postername {$ubbt_lang['REPLY_BOD']}$newline{$config['phpurl']}/showthreaded.php?Cat=$Cat&Board=$Board&Number=$Mnumber$newline$newline$EmailBody";
         }


         $to = $Mailto;
         $mailer = new mailer;
         $header = $mailer -> headers($emailformat);
         $subject = $ubbt_lang['REPLY_SUB'];
         $msg     = "$Username {$ubbt_lang['REPLY_BOD']}\n{$config['phpurl']}/showthreaded.php?Cat=$Cat&Board=$Board&Number=$Mnumber\n\n$EmailBody";

         $mailsend = mail("$to","$subject",$msg,$header);

      // --------------------------------------------------
      // Now, we need to switch back to this users language
         if (!$user['U_Language']) { $user['U_Language'] = $config['language']; }
         require "{$config['path']}/languages/{$user['U_U_Language']}/addprove.php";
      }
   
   }

// --------------------------
// Send the confirmation page

   $html -> send_header($ubbt_lang['APPR_HEAD'],$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\" />",$user);

	if (!$debug) {
   	include ("$thispath/templates/$tempstyle/approvepost.tmpl");
	}
   $html -> send_footer();
