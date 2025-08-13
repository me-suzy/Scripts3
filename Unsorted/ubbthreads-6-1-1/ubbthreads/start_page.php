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
   require ("languages/${$config['cookieprefix']."w3t_language"}/start_page.php");


// ########################################################################
// mail_password function - Mails a password to a given email address
// ########################################################################

function mail_password($Username="",$Email,$Cat="") {

   global $dbh, $config, $theme, $ubbt_lang, $thispath, $tempstyle;

   $ip = find_environmental('REMOTE_ADDR');
   
// --------------------------------------
// Make sure we can get the email address
   $Username_q = addslashes($Username);
   $Email_q = addslashes($Email);
   if ($Username) {
      $extra = "WHERE U_LoginName = '$Username_q'";
   }
   else {
      $extra = "WHERE U_Email = '$Email_q'";
   }
   $query = "
     SELECT U_LoginName, U_Email
     FROM   {$config['tbprefix']}Users
     $extra
   ";
   $sth = $dbh -> do_query($query);
   list($Username,$Email) = $dbh -> fetch_array($sth);
	if (!$Username_q && !$Email_q) {
		$Username = "";
		$Email = "";
	}
   $dbh -> finish_sth($sth);

// --------------------------------------------------------------
// If we found an email address then we mail the password to them
   if ($Email) {
   
   // -------------------
   // Generate a password
      $a = time();
      mt_srand($a);
      $passset = array('a','b','c','d','e','f','g','h','i','j','k','m','n','o','p','q','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','2','3','4','5','6','7','8','9');
      $chars = sizeof($passset);
      for ($i=0; $i < 6; $i++) {
         $randnum = intval(mt_rand(0,$chars))    ;
         $pass .= $passset[$randnum];
      }

   // ----------------------------
   // Now let's crypt the password
      $crypt = md5($pass);

   // -----------------------------
   // Now let's update the database
      $Username_q = addslashes($Username);
      $crypt_q    = addslashes($crypt);
      $query = "
        UPDATE {$config['tbprefix']}Users
        SET    U_TempPass = '$crypt_q'
        WHERE  U_LoginName = '$Username_q'
      ";
      $dbh -> do_query($query);

   // -----------------------
   // Now we mail it to them
      $to      = $Email;
      $mailer  = new mailer;
      $header  = $mailer -> headers();
      $subject = $ubbt_lang['PASS_REQ_SUB'];
      $msg     = "{$ubbt_lang['PASS_REQ_BOD1']} '$ip' {$ubbt_lang['PASS_REQ_BOD2']} '$Username' {$ubbt_lang['PASS_REQ_BOD3']} $pass";
      $mailsend = mail("$to","$subject","$msg",$header);


   // ---------------------------
   // Let them know it was mailed 
      $html = new html;
      $html -> send_header($ubbt_lang['PASS_MAILED'],$Cat,0,0,0,0);
		if (!$debug) {
	      include("$thispath/templates/$tempstyle/start_page_passmailed.tmpl");
		}
      $html -> send_footer();
      exit();

   }
   else {
   
   // --------------------------------------------------------------
   // We couldn't find the email address so we need to let them know
      $html = new html;
      $html -> not_right("{$ubbt_lang['NO_EMAIL']} $Username",$Cat);

   }

}

// END OF THE mail_password function


// -------------------
// Where are we going?
   $Username = $Loginname;
   $Password = $Loginpass;
   if ($buttforgot) {
      mail_password($Username,$Email,$Cat);
   }
   elseif ($buttlogin) {
      $html = new html;
      $html -> do_login ($Cat,$Username,$Password,$rememberme);
   }
   else {
      $html = new html;
      $html -> not_right($ubbt_lang['ALL_FIELDS'], $Cat);
   }

?>
