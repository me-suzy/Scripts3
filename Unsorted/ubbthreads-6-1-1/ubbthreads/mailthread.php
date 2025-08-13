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
   require ("./main.inc.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Email,U_Groups,U_TextCols,U_TextRows");
   $Username = $user['U_Username'];
   $html = new html;

// -----------------------------------------
// require the language file for this script
   require "languages/${$config['cookieprefix']."w3t_language"}/mailthread.php";

   if (!$config['mailpost']) {
      $html -> not_right("DISABLED",$Cat);
   }
   
   $ubbt_language = ${$config['cookieprefix']."w3t_language"};

   $TextCols = $user['U_TextCols'];
   $TextRows = $user['U_TextRows'];
   if (!$TextCols) { $TextCols = $theme['TextCols']; }
   if (!$TextRows) { $TextRows = $theme['TextRows']; }

   if (!$Username) {
      $html -> not_right("SORRY!<br />This feature is only available if you<br /> Login with a Registered Username.",$Cat);
   }

// -----------------------------------------------------------------------------
// Once and a while if people try to just put a number into the url,lets trap it
   if (!$Number) {
      $html -> not_right("There was a problem looking up the Post in our database.  Please try again.");
    }

// ------------------------------------------------------
// Let's find out what Groups we are dealing with
   $Board_q = addslashes($Board);
   $Groups = $user['U_Groups'];
   if (!$Groups) { $Groups = "-4-"; }

// --------------------------------------------------------------
// We need to format a SQL query to see what boards this user can
// view
   $Grouparray = split("-",$Groups);
   $gsize = sizeof($Grouparray);
   $groupquery = "AND (";
   $g = 0;
   for ($i=0; $i<=$gsize;$i++) {
      if (!preg_match("/[0-9]/",$Grouparray[$i])) { continue; };
      $g++;
      if ($g > 1) {
         $groupquery .= " OR ";
      }
      $groupquery .= "Bo_Read_Perm LIKE '%-$Grouparray[$i]-%'";
   }
   $groupquery .= ")";


// --------------------------------------------
// Let's find out if they should be here or not
   $query = "
    SELECT Bo_Title,Bo_Write_Perm,Bo_CatName,Bo_Cat
    FROM   {$config['tbprefix']}Boards
    WHERE  Bo_Keyword = '$Board_q'
    $groupquery
   ";
   $sth = $dbh -> do_query($query);
   list($title,$CanWrite,$CatName,$CatNumber) = $dbh -> fetch_array($sth);

   if (!$title) {
      $html -> not_right($ubbt_lang['BAD_GROUP'],$Cat);
   }


// ---------------
// Send the header
   $html -> send_header($ubbt_lang['MAIL_FRIEND'],$Cat,0,$user);
	if (!$debug) {
   	include("$thispath/templates/$tempstyle/mailthread.tmpl");
	}
   $html -> send_footer();

?>
