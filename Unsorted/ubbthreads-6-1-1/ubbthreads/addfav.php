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
   require "{$config['path']}/languages/${$config['cookieprefix']."w3t_language"}/addfav.php";

// --------------------
// Authenticate the user
   $userob = new user;
   $html = new html;
   $user     = $userob -> authenticate("U_Groups");
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
    SELECT Bo_Title
    FROM   {$config['tbprefix']}Boards
    WHERE  Bo_Keyword = '$Board_q'
    $groupquery
   ";
   $sth = $dbh -> do_query($query);
   list($title) = $dbh -> fetch_array($sth);

   if (!$title) {
      $html -> not_right($ubbt_lang['NO_PERM'],$Cat);
   } 

// -----------------------------------------------------------------------
// Grab the last_post date of this thread and make sure it is a main topic
	$main = addslashes($main);
   if ($type == "favorite") {
       $query = "
         SELECT B_Last_Post,B_Topic
         FROM   {$config['tbprefix']}Posts
         WHERE  B_Number = '$main'
       ";
       $sth = $dbh -> do_query($query);
       list($lastpost,$topic) = $dbh -> fetch_array($sth);
       if (!$topic) {
          $html -> not_right($ubbt_lang['MAIN_ONLY'],$Cat);
       }
   }

// -----------------------------------------------
// Let's make sure this  isn't already in our list 
   if ($type == "favorite") {
      $type = "f";
   }
   else {
      $type = "r";
   }
   $Owner_q = addslashes($user['U_Username']);
   $query = "
    SELECT F_Number 
    FROM   {$config['tbprefix']}Favorites
    WHERE  F_Owner  = '$Owner_q'
    AND    F_Thread = '$main'
    AND    F_Type   = '$type'
   ";
   $sth = $dbh -> do_query($query);
   list($check) = $dbh -> fetch_array($sth);
   if (!$check) {	

   // ------------------------------------------------------
   // Insert the details into the database
      $Subject_q = addslashes($Subject);
      $Board_q   = addslashes($Board);
      if (!$lastpost) { $lastpost = 0; }
      $query = "
         INSERT INTO {$config['tbprefix']}Favorites
         (F_Owner, F_Thread, F_Type, F_LastPost)
         VALUES ('$Owner_q', '$main', '$type', '$lastpost')
      "; 
      $dbh -> do_query($query);
   }
   else {
   // ---------------------
   // Thread already added
      $html -> not_right($ubbt_lang['DUP_ENTRY'],$Cat);
   }

// ------------------------------------------
// Give confirmation and return to the thread
   $html -> send_header($ubbt_lang['ENTRY_IN'],$Cat,"<meta http-equiv=\"Refresh\" content=\"15;url={$config['phpurl']}/$what.php?Cat=$Cat&amp;Board=$Board&amp;Number=$main&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;fpart=$fpart&amp;vc=$vc&amp;o=$o\" />",$user);
	if (!$debug) {
   	include("$thispath/templates/$tempstyle/addfav.tmpl");
	}
   $html -> send_footer(); 


?>
