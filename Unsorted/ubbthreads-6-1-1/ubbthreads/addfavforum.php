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

// -----------------------------------------
// require the language file for this script
  require "{$config['path']}/languages/${$config['cookieprefix']."w3t_language"}/addfavforum.php";


// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Favorites,U_Groups");
   $html = new html;

   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// ------------------------------------------------------
// Let's find out what Groups we are dealing with
   $Board_q = addslashes($Board);
   $Groups = $user['U_Groups'];
   if (!$Groups) { $Groups = "-4-"; }

// -------------------------------------------------------------------
// We need to format a SQL query to see what boards this user can view
   $Grouparray = split("-",$Groups);
   $gsize = sizeof($Grouparray);
   $groupquery = "AND (";
   $g = 0;
   for ($i=0; $i<$gsize;$i++) {
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
    SELECT Bo_Title,Bo_Number
    FROM   {$config['tbprefix']}Boards
    WHERE  Bo_Keyword = '$Board_q'
    $groupquery
   ";
   $sth = $dbh -> do_query($query);
   list($title,$fnumber) = $dbh -> fetch_array($sth);

   if (!$title) {
      $html -> not_right($ubbt_lang['NO_PERM'],$Cat);
   } 

// -------------------------
// We have an 83 forum limit
   if (strlen($user['U_Favorites'] == 249)) {
      $html -> not_right($ubbt_lang['TOO_MANY'],$Cat);
   }

// -----------------------------------------------
// Let's make sure this  isn't already in our list 
   if (stristr($user['U_Favorites'],"-$fnumber-") ) {
      $html -> not_right($ubbt_lang['DUP_ENTRY'],$Cat);
   }
   else {	

   // ------------------------------------------------------
   // Insert the details into the database
      $user['U_Favorites'] .="$fnumber-";
      $Owner_q = addslashes($user['U_Username']);
      $query = "
         UPDATE {$config['tbprefix']}Users
         SET    U_Favorites = '{$user['U_Favorites']}'
         WHERE  U_Username  = '$Owner_q'
      "; 
      $dbh -> do_query($query);
   }

// ------------------------------------------
// Give confirmation and return to the thread
   $html -> send_header($ubbt_lang['FORUM_IN'],$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board\" />",$user);
   
// --------------------
// Include the template
   if (!$debug) {
   	include ("$thispath/templates/$tempstyle/addfavforum.tmpl");
   }
   $html -> send_footer(); 

?>
