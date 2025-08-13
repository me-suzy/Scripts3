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
   require ("languages/${$config['cookieprefix']."w3t_language"}/changedisplay.php");

// Make sure this isn't being called via GET
   if ($GLOBALS['REQUEST_METHOD'] == "GET") {
      exit;
   }

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $Username = $user['U_Username'];

   $html = new html;
   if (!$user['U_Username']){
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// --------------------------------------------------------------
// If Posts per is less than 1 or greater than 99, can't proceed
   if( ($PostsPer < 1) || ($PostsPer > 99) ) {
      $html -> not_right($ubbt_lang['POSTS_PER'],$Cat);
   }

// -------------------------------------------------------------
// If FlatPosts is less than 1 or greater than 99, can't proceed
   if ( ($FlatPosts < 1) || ($FlatPosts > 99) ) {
      $html -> not_right($ubbt_lang['FLAT_BAD'],$Cat);
   }

// -------------------------------------------------------------
// If textcols is less than 20 or greater than 200 can't proceed
   if ( ($TextCols < 20) || ($TextCols > 200) ) {
      $html -> not_right($ubbt_lang['TEXTAREA_NO'],$Cat);
   }

// -----------------------------------------------------------
// If textrows is less than 5 or greater than 50 can't proceed
   if ( ($TextRows < 5) || ($TextRows > 50) ) {
      $html -> not_right($ubbt_lang['TEXTAREA_NO'],$Cat);
   }

// -----------------------
// Format the query words
   $ubbt_language_q   = addslashes($chosenlanguage);
   $Username_q   = addslashes($Username);
   $Display_q    = addslashes($display);
   $View_q       = addslashes($view);
   $StyleSheet_q = addslashes($StyleSheet);
   $Post_Format_q= addslashes($Post_Format);
   $Preview_q    = addslashes($Preview);
   $PictureView_q = addslashes($PictureView);
   $PicturePost_q = addslashes($PicturePost);
   $TimeOffset_q  = addslashes($timeoffset);
   $FrontPage_q   = addslashes($FrontPage);
   $sort_order_q  = addslashes($sort_order);
   $PostsPer_q    = addslashes($PostsPer);
   $TextCols_q    = addslashes($TextCols);
   $TextRows_q    = addslashes($TextRows);
   $FlatPosts_q   = addslashes($FlatPosts);
   $StartPage_q   = addslashes($StartPage);
   $activethreads_q = addslashes($activethreads);
   $ShowSigs_q    = addslashes($ShowSigs);

// --------------------------
// Update the User's profile
   $query = "
    UPDATE {$config['tbprefix']}Users
    SET U_Sort       = '$sort_order_q',
    U_Display        = '$Display_q',
    U_View           = '$View_q',
    U_PostsPer       = '$PostsPer_q',
    U_TextCols       = '$TextCols_q',
    U_TextRows       = '$TextRows_q',
    U_StyleSheet     = '$StyleSheet_q',
    U_Post_Format    = '$Post_Format_q',
    U_Preview        = '$Preview_q',
    U_PictureView    = '$PictureView_q',
    U_PicturePosts   = '$PicturePost_q',
    U_Language       = '$ubbt_language_q',
    U_FlatPosts      = '$FlatPosts_q',
    U_TimeOffset     = '$TimeOffset_q',
    U_FrontPage      = '$FrontPage_q',
    U_ActiveThread   = '$activethreads_q',
    U_StartPage      = '$StartPage_q',
    U_ShowSigs       = '$ShowSigs_q'
    WHERE U_Username = '$Username_q'
   ";
   $dbh -> do_query($query);

// ----------------------------
// Update their language cookie
	if ($config['cookieexp'] > 31536000) {
		$config['cookieexp'] = 31536000;
	}
	setcookie("{$config['cookieprefix']}w3t_language","$chosenlanguage",time()+$config['cookieexp'],"{$config['cookiepath']}");

// ---------------------------------------------------
// Send them to their start page with the confirmation
   $html -> start_page($Cat,"","",$chosenlanguage);
