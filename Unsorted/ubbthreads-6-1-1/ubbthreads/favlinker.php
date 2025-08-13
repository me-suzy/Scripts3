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
   $user = $userob -> authenticate("U_Display");
   $html = new html;

// -----------------------------------------
// require the language file for this script
   require "languages/${$config['cookieprefix']."w3t_language"}/addfav.php";
   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// -----------------------------------------------------------------------
// Grab the last_post date of this thread so we can update the favorites
// table 
   $Board = $F_Board;
	$Thread = addslashes($Thread);
   $query = "
    SELECT B_Last_Post
    FROM   {$config['tbprefix']}Posts
    WHERE  B_Number = '$Thread' 
   ";
   $sth = $dbh -> do_query($query);
   list($lastpost) = $dbh -> fetch_array($sth);

   $query = "
    UPDATE  {$config['tbprefix']}Favorites
    SET     F_LastPost = '$lastpost'
    WHERE   F_Number  = '$Entry'
   ";
   $dbh -> do_query($query);

// -------------------------------------
// Update their last visit to that forum
   $now = $html -> get_date();
   $Username_q = addslashes($user['U_Username']);
   $Board_q    = addslashes($Board);
   $query = "
    UPDATE {$config['tbprefix']}Last
    SET    L_Last = $now
    WHERE  L_Username = '$Username_q'
    AND    L_Board    = '$Board_q'
   ";
   $dbh -> do_query($query);
   
// ----------------------------------------------------------------------------
// If display is threaded then we need to link to the proper new post, if there
// is one
   if ( ($postmarker) && ($user['U_Display'] == "threaded") ) {
      $Thread = $postmarker;
   }

// ---------------------------
// Now send them to the thread
   header("Location: {$config['phpurl']}/show{$user['U_Display']}.php?Cat=$Cat&Board=$Board&Number=$Thread&fpart=$partnumber$postmarker&PHPSESSID=$PHPSESSID");


?>
