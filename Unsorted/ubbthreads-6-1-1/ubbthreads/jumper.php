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
   require ("languages/${$config['cookieprefix']."w3t_language"}/jumper.php"); 

   if (!$board){
      $html = new html;
      $html -> not_right("{$ubbt_lang['NO_JUMP']}",$Cat);
   }
   if (preg_match("/^-CATJUMP-/",$board) ) {
      $piece['0'] = "";
      preg_match("/-CATJUMP-(.+)/",$board,$piece);
      header("Location: {$config['phpurl']}/ubbthreads.php?Cat=$Cat&C={$piece['1']}&PHPSESSID=$PHPSESSID");
      exit;
   } else {
      header("Location: {$config['phpurl']}/postlist.php?Cat=$Cat&Board=$board&PHPSESSID=$PHPSESSID");
   }

