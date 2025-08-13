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
   $user = $userob -> authenticate("U_Groups,U_Favorites");
   $Username = $user['U_Username'];
   $html = new html;

// -----------------------------------------
// require the language file for this script
  require "languages/${$config['cookieprefix']."w3t_language"}/addfavforum.php";

  if (!$user['U_Username']) {
     $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
  }

// -----------------------------
// Get rid of this from our list 
   $user['U_Favorites'] = str_replace("-$Entry-","-",$user['U_Favorites']);

// -------------------
// Update the database
   $Owner_q = addslashes($Username);
   $query = "
    UPDATE {$config['tbprefix']}Users
    SET    U_Favorites = '{$user['U_Favorites']}'
    WHERE  U_Username  = '$Owner_q'
   "; 
   $dbh -> do_query($query);

// ------------------------------------------
// Return to the control panel
   $html -> start_page($Cat);

?>
