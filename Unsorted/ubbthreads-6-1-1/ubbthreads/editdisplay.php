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
   require ("languages/${$config['cookieprefix']."w3t_language"}/editdisplay.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $Username = $user['U_Username'];
   $Password = $user['U_Password'];

   $html = new html;
   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// -----------------------------------------
// Get the current profile for this username
   $Username_q = addslashes($Username);
   $query = "
     SELECT U_Bio,U_Sort,U_Display,U_View,U_PostsPer,U_TextCols,U_TextRows,U_StyleSheet,U_Post_Format,U_Preview,U_PictureView,U_PicturePosts,U_Language,U_FlatPosts,U_TimeOffset,U_FrontPage,U_ActiveThread,U_StartPage,U_ShowSigs
     FROM  {$config['tbprefix']}Users
     WHERE U_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);

// --------------------------------
// Make sure we found this Username
   list($Bio,$Sort,$Display,$View,$PostsPer,$TextCols,$TextRows,$StyleSheet,$Post_Format,$Preview,$PictureView,$PicturePost,$chosenlanguage,$FlatPosts,$TimeOffset,$FrontPage,$activethreads,$startpage,$sigview) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if (!$Display){
      $html -> not_right("{$ubbt_lang['NO_PROF']} '$Username'",$Cat);
   }

// -------------------------------
// What is the default front page?
   $mainpage = "ubbthreads";
   if ($config['catsonly']) {
      $mainpage = "categories";
   }
   if (!$FrontPage) { $FrontPage = $mainpage; }
   if ($FrontPage == "categories") {
      $categories = "selected=\"selected\"";
   }
   else {
      $ubbthreads = "selected=\"selected\"";
   }

// --------------------------
// Assign the retrieved data
   if (!$TextCols) { $TextCols = $theme['TextCols']; }
   if (!$TextRows) { $TextRows = $theme['TextRows']; }
   if (!$TimeOffset) { $TimeOffset = 0; }

// -------------------------
// Give a default stylesheet
   if (!$StyleSheet) { $StyleSheet = $theme['stylesheet']; }

// ------------------------------------------------
// Figure out their default aged threads to display
   if (!$activethreads) {
      $activethreads = $config['activethreads'];
   }
  if ($activethreads == "1") {
    $d1 = "selected=\"selected\"";
  } elseif ($activethreads == "2") {
    $d2 = "selected=\"selected\"";
  } elseif ($activethreads == "7") {
    $w1 = "selected=\"selected\"";
  } elseif ($activethreads == "14") {
    $w2 = "selected=\"selected\"";
  } elseif ($activethreads == "21") {
    $w3 = "selected=\"selected\"";
  } elseif ($activethreads == "31") {
    $m1 = "selected=\"selected\"";
  } elseif ($activethreads == "93") {
    $m3 = "selected=\"selected\"";
  } elseif ($activethreads == "186") {
    $m6 = "selected=\"selected\"";
  } elseif ($activethreads == "365") {
    $y1 = "selected=\"selected\"";
  } elseif ($activethreads == "999") {
    $default = "selected=\"selected\"";
  } else {
    $allofthem = "selected=\"selected\"";
  }

// ---------------------------
// Where do they want to start
   if ($startpage == "mi") {
      $mi = "selected=\"selected\"";
   }
   else {
      $cp = "selected=\"selected\"";
   }

// -----------------------------------------------
// Find out if they already have a sort preference
   if($Sort == 1) { $DS = "selected=\"selected\""; } 
   if($Sort == 2) { $AS = "selected=\"selected\""; }
   if($Sort == 3) { $DP = "selected=\"selected\""; } 
   if($Sort == 4) { $AP = "selected=\"selected\""; }
   if($Sort == 5) { $DD = "selected=\"selected\""; }
   if($Sort == 6) { $AD = "selected=\"selected\""; }

// --------------------------------------------------
// Find out if they already have a display preference
   if($Display == "flat")     { $flat = "selected=\"selected\""; };
   if($Display == "threaded") { $threaded = "selected=\"selected\""; }

// -------------------------------
// Find out what language they use
   if (!$chosenlanguage) { $chosenlanguage = $config['language']; }

   if ($chosenlanguage == "english") { $english = "selected=\"selected\""; }
   if ($chosenlanguage == "big5") {$big5 = "selected=\"selected\""; }
   if ($chosenlanguage == "chinese") {$chinese = "selected=\"selected\""; }
   if ($chosenlanguage == "danish") {$danish = "selected=\"selected\""; }
   if ($chosenlanguage == "dutch") {$dutch = "selected=\"selected\""; }
   if ($chosenlanguage == "german") {$german = "selected=\"selected\""; }
   if ($chosenlanguage == "french")  { $french = "selected=\"selected\""; }
   if ($chosenlanguage == "hungarian") {$hungarian = "selected=\"selected\""; }
   if ($chosenlanguage == "italian") {$italian = "selected=\"selected\""; }
   if ($chosenlanguage == "norwegian") {$norwegian = "selected=\"selected\""; }
   if ($chosenlanguage == "polish") {$polish = "selected=\"selected\""; }
   if ($chosenlanguage == "portuguese") {$portuguese = "selected=\"selected\""; }
   if ($chosenlanguage == "romanian") {$romanian = "selected=\"selected\""; }
   if ($chosenlanguage == "russian") {$russian = "selected=\"selected\""; }
   if ($chosenlanguage == "spanish") {$spanish = "selected=\"selected\""; }
   if ($chosenlanguage == "swedish") {$swedish = "selected=\"selected\""; }

// ---------------------------------------
// Find out if they have a view preference
   if($View == "collapsed") { $collapsed = "selected=\"selected\""; }
   if($View == "expanded")  { $expanded  = "selected=\"selected\""; }

// ----------------------------------------------------------------
// Find out if they have a preference for total flat posts per page
   if (!$FlatPosts) { $FlatPosts = $theme['flatposts']; }
   if (!$PictureView) { $PictureView = $theme['PictureView']; }
  
// -------------------------------
// Find out if they preview or not
   if ($Preview != "off")  { 
      $previewon = "selected=\"selected\""; 
   }
   else {
      $previewoff = "selected=\"selected\""; 
   }

// ---------------------------------
// Set the default for picture views
   if ($PictureView == "on") {
      $yespictureview = "checked=\"checked\"";
   }
   else {
      $nopictureview = "checked=\"checked\"";
   }

// ---------------------------------
// Set the default for picture in posts 
   if ($PicturePost != "off") {
      $picturepost = "checked=\"checked\"";
   }
   else {
      $nopicturepost = "checked=\"checked\"";
   }

// ----------------------------
// Set the default for sigview
   if ($sigview == "no") {
      $nosigview = "checked=\"checked\"";
   }
   else {
      $yessigview = "checked=\"checked\"";
   }

// ---------------------------------------------------------
// Ok, we found the profile, now lets put it all onto a page
   $html -> send_header("{$ubbt_lang['DISP_HEAD']} $Username",$Cat,0,$user);

// -------------------------------
// Let's list out the style sheets
   if ($StyleSheet == "usedefault") {
      $defselected = "selected=\"selected\"";
   } 
   $stylesheets = "<option value=\"usedefault\" $defselected>{$ubbt_lang['DEFAULT_STYLE']}</option>";
   $styles = split(",",$theme['availablestyles']);
   $size = sizeof($styles);
   for ($i=0;$i<$size;$i++) {
       list($style,$desc) = split(":",$styles[$i]);
       $style = trim($style);
       $desc  = trim($desc);
       $extra = "";
       if ($StyleSheet == $style) {
          $extra = "selected=\"selected\"";
       }
       $stylesheets .= "<option value=\"$style\" $extra>$desc</option>";
   }

   $date = $html -> get_date();
   $time = $html -> convert_time($date);

   if ($theme['PictureView'] == 1) {
      $pictureview = "
         {$ubbt_lang['PROF_PICVIEW']}<br />
         <input type=\"radio\" name = \"PictureView\" value=\"on\" $yespictureview class=\"formboxes\" /> {$ubbt_lang['TEXT_YES']} 
         <input type=\"radio\" name =\"PictureView\" value=\"off\" $nopictureview class=\"formboxes\" /> {$ubbt_lang['TEXT_NO']} 
         <br /><br />
         {$ubbt_lang['VIEW_PICS']}<br />
         <input type=\"radio\" name = \"PicturePost\" value=\"on\" $picturepost class=\"formboxes\" /> {$ubbt_lang['TEXT_YES']} 
         <input type=\"radio\" name =\"PicturePost\" value=\"off\" $nopicturepost class=\"formboxes\" /> {$ubbt_lang['TEXT_NO']} 
        <br /><br />
      ";
   }
   else {
      $pictureview = "<input type=\"hidden\" name=\"PictureView\" value=$PictureView />";
   }

	if (!$debug) {
   	include("$thispath/templates/$tempstyle/editdisplay.tmpl");
	}
   $html -> send_footer();
