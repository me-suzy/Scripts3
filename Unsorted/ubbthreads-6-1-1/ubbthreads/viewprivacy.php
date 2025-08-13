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
   require ("languages/${$config['cookieprefix']."w3t_language"}/coppaform.php");
   $html = new html;

   $date = date("M/d/Y");
   $html -> send_header($ubbt_lang['PRIVACY'],$Cat,0,0,0,0);
   $privacy = @file("{$config['path']}/includes/privacy.php");
   if (!is_array($privacy)) {
      $privacy = @file("{config['phpurl']}/includes/privacy.php");
   }
   if ($privacy) {
      while(list($linenum,$line) = each($privacy)) {
         $boardprivacy .= $line;
      }
   }
   include("$thispath/templates/$tempstyle/viewprivacy.tmpl");
   $html -> send_footer();
?>
