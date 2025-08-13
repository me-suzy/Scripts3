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
   $user = $userob -> authenticate("U_Display,U_FlatPosts");
   $html = new html;

// -----------------------------------------
// require the language file for this script
   require "languages/${$config['cookieprefix']."w3t_language"}/addfav.php";

// --------------------
// Assign the variables
   $Board     = $F_Board;
   $Number    = addslashes($Thread);
   $main      = addslashes($Main);

  if (!$user['U_Username']) {
     $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
  }
  if (!$user['U_FlatPosts']) { $user['U_FlatPosts'] = $theme['flatposts']; }

// ---------------------------------------------------------------------
// Grab all replies in this thread until we get to the proper postnumber
// if we are viewing in flat mode
   $pagejump = 1;
   $postmarker = "";
   $partnumber = "";
   $cycle = 0;

   if ($user['U_Display'] == "flat") {
      $query = "
         SELECT B_Number,B_Posted
         FROM  {$config['tbprefix']}Posts
         WHERE B_Main = '$main'
         ORDER BY B_Posted
      ";
      $sth = $dbh -> do_query($query);      
      while (list($currnumber) = $dbh -> fetch_array($sth)) {
         $cycle++;
         if ($cycle == $user['U_FlatPosts']) {
            $pagejump++;
            $cycle = 0;
         }
         if ($currnumber == $Number) {
            $postmarker = "#Post$currnumber";
            $partnumber = $pagejump;
            break; 
         }
      }
   }

// -------------------------------------
// Update their last visit to that forum
   $now = $html -> get_date();
   $Username_q = addslashes($user['U_Username']);
   $Board_q    = addslashes($Board);
   $query = "
    UPDATE {$config['tbprefix']}Last
    SET    L_Last = '$now'
    WHERE  L_Username = '$Username_q'
    AND    L_Board    = '$Board_q'
   ";
   $dbh -> do_query($query);
  
   
// -------------------------
// Now send them to the post 
  header ("Location: {$config['phpurl']}/show{$user['U_Display']}.php?Cat=$Cat&Board=$Board&Number=$Number&PHPSESSID=$PHPSESSID&fpart=$partnumber$postmarker");


?>
