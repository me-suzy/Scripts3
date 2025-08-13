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
   $user = $userob -> authenticate("U_TimeOffset, U_FlatPosts, U_Display, U_TempRead");
   $Username = $User['U_Username'];

   $html = new html;

// -----------------------------------------
// require the language file for this script
   require "languages/${$config['cookieprefix']."w3t_language"}/viewfavorites.php";

   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// --------------------------------------------------
// Get ready to grab the selected type of threads 
   $header = $ubbt_lang['VIEW_REM'];
   $html -> send_header("$header",$Cat,0,$user);
   $username_q = addslashes($user['U_Username']);

   $Viewable = "AND {$config['tbprefix']}Posts.B_Approved='yes'";
   if ( ($user['U_Status'] == "Administrator") || ($user['U_Status'] == "Moderator") ) {
      $Viewable = "";
   }

   $query = "
    SELECT {$config['tbprefix']}Favorites.F_Thread,{$config['tbprefix']}Posts.B_Subject,{$config['tbprefix']}Favorites.F_Number,{$config['tbprefix']}Posts.B_Board,{$config['tbprefix']}Favorites.F_LastPost,{$config['tbprefix']}Posts.B_Username,{$config['tbprefix']}Posts.B_Main
    FROM   {$config['tbprefix']}Posts,{$config['tbprefix']}Favorites
    WHERE  {$config['tbprefix']}Favorites.F_Owner = '$username_q'
    AND    {$config['tbprefix']}Favorites.F_Type  = 'r'
    AND    {$config['tbprefix']}Favorites.F_Thread = {$config['tbprefix']}Posts.B_Number
    ORDER BY {$config['tbprefix']}Posts.B_Last_Post
   ";
   $sth = $dbh -> do_query($query);
   $any=0;

   $x=0;
   while ( list($postrow[$x]['Thread'],$postrow[$x]['Subject'],$postrow[$x]['Number'],$postrow[$x]['Board'],$postrow[$x]['LastPost'],$postrow[$x]['poster'],$postrow[$x]['main']) = $dbh -> fetch_array($sth)) {
      $x++;
   }
   $topicsize = $x;
   include("$thispath/templates/$tempstyle/viewreminders.tmpl");

// ----------------
// send the footer
   $html -> send_footer();

?>
