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
   $user = $userob -> authenticate("U_Groups");
   $Username = $user['U_Username'];
   $html = new html;

// -----------------------------------------
// require the language file for this script
   require "languages/${$config['cookieprefix']."w3t_language"}/togglesub.php";

   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// ----------------------------------------------------------------
// If they are logged in then we check their groups, otherwise they
// get set to the guest group
   $Groups = $user['U_Groups'];
   if (!$Groups) {
      $Groups = "-4-";
   }

// --------------------------------------------------------------
// Let's make sure they are supposed to be looking at this board
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
   $Board_q = addslashes($Board);
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

// ----------------------------------------
// Grab the last_post number for this board 
   $query = "
    SELECT B_Number
    FROM   {$config['tbprefix']}Posts
    WHERE  B_Board = '$Board_q'
    ORDER BY B_Number DESC
   ";
   $sth = $dbh -> do_query($query);
   list($lastpost) = $dbh -> fetch_array($sth);
   if (!$lastpost) { $lastpost = '1'; }

// -----------------------------------------------
// Let's make sure this  isn't already in our list 
   $Owner_q = addslashes($user['U_Username']);
   $query = "
    SELECT S_Username 
    FROM   {$config['tbprefix']}Subscribe
    WHERE  S_Username = '$Owner_q'
    AND    S_Board    = '$Board_q' 
   ";
   $sth = $dbh -> do_query($query);

// ---------------------------------------------------------
// We didn't find an entry so we subscribe them to the board
   $Username_q = addslashes($Username);
   if (!$dbh -> fetch_array($sth)) {	

   // ------------------------------------------------------
   // Insert the subscription into the database
      $query = "
         INSERT INTO {$config['tbprefix']}Subscribe
         (S_Username,S_Board,S_Last)
         VALUES
         ('$Username_q','$Board_q','$lastpost')
      "; 
      $dbh -> do_query($query); 
      $which = "subscribe";
   }

// -------------------------------------------
// We did find an entry so we unsubscribe them
   else {
      $query = "
       DELETE FROM {$config['tbprefix']}Subscribe
       WHERE  S_Username = '$Username_q'
       AND    S_Board    = '$Board_q'
      ";
      $dbh -> do_query($query);
      $which = "unsubscribe";
   }

// ------------------------------------------
// Give confirmation and return to the board 
   if ($which == "subscribe") {
      $whichhead = $ubbt_lang['HEADER'];
      $whichbody = $ubbt_lang['BODY'];
   }
   else {
      $whichhead = $ubbt_lang['UN_HEADER'];
      $whichbody = $ubbt_lang['UN_BODY'];
   }
  
   $html -> send_header("$whichhead",$Cat,"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&Board=$Board&o=$o\">",$user);
	if (!$debug) {
	   include("$thispath/templates/$tempstyle/togglesub.tmpl");
	}
   $html -> send_footer(); 

?>
