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
  
// ----------------------------------------------------------------
// If we already have a username and password, go to the start page
   if ( (${$config['cookieprefix']."w3t_myid"} && ${$config['cookieprefix']."w3t_mysess"}) && (${$config['cookieprefix']."w3t_myid"} != "deleted") ){
      $html -> start_page($Cat);
      exit;
   }

// ----------------------------------
// Otherwise, give them the login box
   $html -> send_header($ubbt_lang['LOGIN_PROMPT'],$Cat,0,0,0,0);
   if ($config['tracking'] == "cookies") {
     $rememberme = "<input type=\"checkbox\" name=\"rememberme\" value=\"1\" class=\"formboxes\" /> {$ubbt_lang['REMEMBER_ME']}<br /><br />";
   }
	if (!$debug) {
   	include ("$thispath/templates/$tempstyle/login.tmpl");
	}

// Send the footer
   $html -> send_footer(); 

?>
