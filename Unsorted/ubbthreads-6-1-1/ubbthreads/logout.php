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

// --------------------------------
// require the proper language file
   require "languages/${$config['cookieprefix']."w3t_language"}/login.php";

// Send a html header
   $html = new html;

   $userob = new user;
   $user = $userob -> authenticate("U_Groups, U_TimeOffset,U_Display");

// --------------------------------------------------------
// Delete their cookie and remove them from the online page
   $Username_q = addslashes($user['U_Username']);
   $query = "
     DELETE FROM {$config['tbprefix']}Online
     WHERE  O_Username = '$Username_q'
   ";
   $dbh -> do_query($query);

// -------------------------------------------
// Get rid of the values in the TempRead field
   $query = "
     UPDATE {$config['tbprefix']}Users
     SET    U_TempRead = ''
     WHERE  U_Username = '$Username_q'
   ";
   $dbh -> do_query($query);

// ----------------------------------
// get rid of their session or cookie
   if ($config['tracking'] == "sessions") {
      session_destroy();
   }
   else {
      setcookie("{$config['cookieprefix']}w3t_myid","", time() - 86400,"{$config['cookiepath']}");
      setcookie("{$config['cookieprefix']}w3t_mysess","", time() - 86400,"{$config['cookiepath']}");
      setcookie("{$config['cookieprefix']}w3t_visit","", time() - 86400);
      setcookie("{$config['cookieprefix']}w3t_key","", time() - 86400,"{$config['cookiepath']}");
   }

// ----------------------------------
// Give them a new login box 
   $html -> send_header($ubbt_lang['LOGIN_PROMPT'],$Cat,0,$user,0,0,1);
   if ($config['tracking'] == "cookies") {
     $rememberme = "<input type=\"checkbox\" name=\"rememberme\" value=\"1\" class=\"formboxes\" /> {$ubbt_lang['REMEMBER_ME']}<br /><br />";
   }
	if (!$debug) {
   	include ("$thispath/templates/$tempstyle/login.tmpl");
	}

// Send the footer
   $html -> send_footer(); 
