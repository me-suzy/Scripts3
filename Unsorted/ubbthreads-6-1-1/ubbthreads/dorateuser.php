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
   require "{$config['path']}/languages/${$config['cookieprefix']."w3t_language"}/dorateuser.php";

// Make sure this isn't being called via GET
   if ($GLOBALS['REQUEST_METHOD'] == "GET") {
      exit;
   }

// --------------------
// Authenticate the user
   $userob = new user;
   $html = new html;
   $user     = $userob -> authenticate("U_Groups");
   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }
   $userob -> check_ban($user['U_Username'],$Cat);

// -------------------
// Can't rate yourself
   if ($user['U_Username'] == $Ratee) {
      $html -> not_right($ubbt_lang['CANTRATE'],$Cat);
   }

// ---------------------------------------------------
// Let's find out if they have rated this user already 
   $Ratee_q = addslashes($Ratee);
   $user_q = addslashes($user['U_Username']);
   $query = "
    SELECT R_What
    FROM   {$config['tbprefix']}Ratings
    WHERE  R_What = '$Ratee_q'
    AND    R_Rater    = '$user_q' 
    AND    R_Type     = 'u'
   ";
   $sth = $dbh -> do_query($query);
   list($check) = $dbh -> fetch_array($sth);

   if (!$check) {	

   // ------------------------------
   // Grab the current rating/rates
      $query = "
         SELECT U_Rating,U_Rates
         FROM   {$config['tbprefix']}Users
         WHERE  U_Username = '$Ratee_q'
      ";
      $sth = $dbh -> do_query($query);
      list ($crating,$crates) = $dbh -> fetch_array($sth);
      $crating = $crating + $rating;
      $crates  = $crates + 1;
      $stars = $crating / $crates;
      $stars = intval($stars);

   // ------------------------------------------------------
   // Insert the details into the database
      $query = "
         INSERT INTO {$config['tbprefix']}Ratings
         (R_What,R_Rater,R_Rating,R_Type)
         VALUES ('$Ratee_q', '$user_q', '$rating','u')
      "; 
      $dbh -> do_query($query);
      $query = "
         UPDATE {$config['tbprefix']}Users
         SET    U_Rating = U_Rating + $rating,
                U_Rates  = U_Rates  + 1,
                U_RealRating = '$stars'
         WHERE  U_Username = '$Ratee_q'
      ";
      $dbh -> do_query($query);
   }
   else {
   // ---------------------
   // Already rated
      $html -> not_right($ubbt_lang['NOMORERATE'],$Cat);
   }

// ------------------------------------------
// Give confirmation and return to the thread
   $urluser = rawurlencode($Ratee);
   $html -> send_header($ubbt_lang['THANKS'],$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/showprofile.php?Cat=$Cat&amp;User=$urluser&amp;Board=$Board&amp;Number=$Number&amp;what=$what&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\" />",$user);
   include("$thispath/templates/$tempstyle/dorateuser.tmpl");
   $html -> send_footer(); 


?>
