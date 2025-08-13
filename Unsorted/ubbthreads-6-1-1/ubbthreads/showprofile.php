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
   require ("languages/${$config['cookieprefix']."w3t_language"}/showprofile.php"); 

// ---------------------
// Grab this user's info
   $userob = new user;
   $thisuser = $userob -> authenticate("U_PictureView,U_Number,U_TimeOffset");
   $Username = $thisuser['U_Username'];

   $html = new html;

// -------------------------------------------------------------------------
// If we are allowing the view of pictures, let's see if this person wants to
// see it
   $Pictureview = $theme['PictureView'];
   if ($theme['PictureView']) {
      if ($thisuser['U_PictureView'] == "on") {
         $PictureView = $thisuser['U_PictureView'];
      }
   }


// ------------------------------
// Grab the profile for this user
   $profileuser = $User;
   $Username_q = addslashes($User);
  
   $query = " 
     SELECT U_Username,U_Fakeemail,U_Name,U_Totalposts,U_Homepage,U_Occupation,U_Hobbies,U_Location,U_Bio,U_Extra1,U_Extra2,U_Extra3,U_Extra4,U_Extra5,U_Registered,U_Picture,U_Title,U_Status,U_Number,U_Rating,U_Rates,U_Picwidth,U_Picheight
     FROM   {$config['tbprefix']}Users
     WHERE  U_Username= '$Username_q'
   ";
   $sth = $dbh -> do_query($query);

// ----------------
// Assign the stuff
   $User = rawurldecode($User);
   list ($CheckUser,$Fakeemail,$Name,$Totalposts,$Homepage,$Occupation,$Hobbies,$Location,$Bio,$ICQ,$Extra2,$Extra3,$Extra4,$Extra5,$Registered,$Picture,$Title,$Userstatus,$UNumber,$Rating,$Rates,$width,$height) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   // ----------------------------------
// Figure out the # of stars they get
   if ($Rates) {
      $stars = $Rating / $Rates;
      $stars = intval($stars);
   }
   $Rating = "";
   if ($stars) {
      for ($x=1;$x<=$stars;$x++) {
         $Rating .= "<img src=\"{$config['images']}/star.gif\" title= \"$Rates {$ubbt_lang['T_RATES']}\" alt=\"*\" />";
      }
   }

// ----------------------------------------
// Let's see if we already rated this user
   $thisuser_q = addslashes($thisuser['U_Username']);
   $query = "
      SELECT R_Rating
      FROM   {$config['tbprefix']}Ratings
      WHERE  R_What = '$Username_q'
      AND    R_Rater   = '$thisuser_q'
      AND    R_Type    = 'u'
   ";
   $sti = $dbh -> do_query($query);
   list($myrating) = $dbh -> fetch_array($sti);

// ----------------------------------
// Figure out what we need to display
   if (!$myrating) {
      $ratinghtml = <<<EOF
<form method="post" action="{$config['phpurl']}/dorateuser.php">
<input type="hidden" name="Ratee" value="$User" />
<input type="hidden" name="Cat" value="$Cat" />
<input type="hidden" name="Board" value="$Board" />
<input type="hidden" name="Number" value="$Number" />
<input type="hidden" name="what" value="$what" />
<input type="hidden" name="page" value="$page" />
<input type="hidden" name="view" value="$view" />
<input type="hidden" name="sb" value="$sb" />
<input type="hidden" name="o" value="$o" />
{$ubbt_lang['RATE_USER']}
<select name="rating" class="formboxes">
<option value="1">{$ubbt_lang['STAR1']}</option>
<option value="2">{$ubbt_lang['STAR2']}</option>
<option value="3">{$ubbt_lang['STAR3']}</option>
<option value="4">{$ubbt_lang['STAR4']}</option>
<option value="5">{$ubbt_lang['STAR5']}</option>
</select>
<input type="submit" name="dorate" value="{$ubbt_lang['DORATE']}" class="buttons
" />
</form>
EOF;
   } else {
      $ratinghtml = "{$ubbt_lang['YOURATED']} $myrating.";
   } 

   if (strtolower($User) == strtolower($thisuser['U_Username'])) {
      $ratinghtml = "&nbsp;";
   }
   
   if ( (!$Picture) || ($Picture == "http://") ){
      $Picture ="{$config['images']}/nopicture.gif";
   }

// -----------------------------
// Show the profile if it exists
   $columns = 2;
   if ($CheckUser) {

      if ($Homepage) {
			$Homepage = str_replace("http://","",$Homepage);
         $Homepage = "<a href=\"http://$Homepage\" target=\"new\">http://$Homepage</a>";
      }
      if ( (preg_match("/[0-9]/",$ICQ) ) && ($config['ICQ_Status']) ){
         $icqimage = "<img src=\"http://online.mirabilis.com/scripts/online.dll?icq=$ICQ&amp;img=1\" alt=\"\" />";
      }
      if ($config['extra2']) {
         $extra2 = " 
           </td></tr><tr><td valign=\"top\" class=\"darktable\">
           {$config['extra2']}
           </td><td>
           $Extra2&nbsp;
         "; 
      }  
      if ($config['extra3']) {
         $extra3 = " 
           </td></tr><tr><td valign=\"top\" class=\"darktable\">
           {$config['extra3']}
           </td><td>
           $Extra3&nbsp;
         "; 
      }  
      if ($config['extra4']) {
         $extra4 = " 
           </td></tr><tr><td valign=\"top\" class=\"darktable\">
           {$config['extra4']}
           </td><td>
           $Extra4&nbsp;
         "; 
      }  
      if ($config['extra5']) {
         $extra5 = " 
           </td></tr><tr><td valign=\"top\" class=\"darktable\">
           {$config['extra5']}
           </td><td>
           $Extra5&nbsp;
         "; 
      }  

      $date = $html -> convert_time($Registered,$thisuser['U_TimeOffset']);

   // --------------------------------------------------------------
   // If this is an admin or moderator they get some special options
      $encoded = rawurlencode($User);
      if ($thisuser['U_Status'] == "Administrator") {
         if ( ( ($thisuser['U_Number'] == 2) && ($UNumber == 2) ) || ( $UNumber != 2) ){
            $useredit = "<img src=\"{$config['images']}/editicon.gif\" align=\"top\" alt=\"\" /> ";
            $useredit .= "<a href=\"{$config['phpurl']}/admin/login.php?Cat=$Cat&amp;User=$encoded&amp;option=option\">{$ubbt_lang['EDIT_T_U']}</a> | ";
         }
      }
      if ($thisuser['U_Status'] == "Moderator") {
         if ( ($config['modedit']) && ($Userstatus == "User") ) {
            $useredit = "<img src=\"{$config['images']}/editicon.gif\" align=\"top\" alt=\"\" /> ";
            $useredit .= "<a href=\"{$config['phpurl']}/admin/login.php?Cat=$Cat&amp;User=$encoded&amp;option=oneuser\">{$ubbt_lang['EDIT_T_U']}</a> | ";
         }
      }

   // ---------------------------------------------------
   // Only show the address book link for logged in users
      if ($thisuser['U_Username']) {
         $addresslinkstart = " <a href=\"{$config['phpurl']}/addaddress.php?Cat=$Cat&amp;User=$encoded&amp;Board=$Board&amp;Number=$Number&amp;what=$what&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
         $addresslinkstop = "</a>"; 
      }

      if ( ($config['private']) && ($thisuser['U_Username']) ){
         $privlinkstart = "<a href=\"{$config['phpurl']}/sendprivate.php?Cat=$Cat&amp;User=$encoded&amp;Board=$Board&amp;Number=$Number&amp;what=$what&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$fpart&amp;vc=$vc\">"; 
         $privlinkstop = "</a>";
      }

// ----------------
// They don't exist
   } else {
      $html -> not_right($ubbt_lang['NO_LONGER'],$Cat);
   }

// ------------------------
// Now show them their page 
   $html -> send_header("{$ubbt_lang['PROF_FOR']} $User",$Cat,0,$thisuser);

// -------------------------------------------------------------------
// If we came from a post then give them a link back to that post
   if ( ($what == "showthreaded") || ($what == "showflat") ){
      $forumlinkstart = "<a href=\"{$config['phpurl']}/$what.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$fpart&amp;vc=$vc\">";
      $forumlinkstop = "</a>";
   }

// If we came from the main page then give them a link back
   elseif( ($what == "ubbthreads") || ($what == "categories") ){
      $forumlinkstart = "<a href=\"{$config['phpurl']}/$what.php?Cat=$Cat\">";
      $forumlinkstop = "</a>";
   }

// ----------------------------------------------------------------
// If they are coming from the online page then we go back to there.
   elseif ($what == "online") {
      $forumlinkstart = "<a href=\"{$config['phpurl']}/online.php?Cat=$Cat\">";
      $forumlinkstop = "</a>";
   } 

// ----------------------------------------------------------------
// If they are coming from the search page then we go back to there.
   elseif ($what == "search") {
      $Words = rawurlencode($Words);
      $Forum = rawurlencode($Forum);
      $Match = rawurlencode($Match);
      $forumlinkstart = "<a href=\"{$config['phpurl']}/dosearch.php?Cat=$Cat&amp;Forum=$Forum&amp;Words=$Words&amp;Match=$Match&amp;Searchpage=$Searchpage&amp;Limit=$Limit&amp;Old=$Old\">";
      $forumlinkstop = "</a>";
   }

// -----------------------------------------------------------------
// If they are coming from a private message then we return to there
   elseif ($what == "viewmessage") {
      $forumlinkstart = "<a href=\"{$config['phpurl']}/viewmessage.php?Cat=$Cat&amp;message=$Message&amp;box=$box\">";
      $forumlinkstop = "</a>";
   }

// ---------------------------------------------------------------
// If they are coming from the member list then we return to there
   elseif ($what == "showmembers") {
      $forumlinkstart = "<a href=\"{$config['phpurl']}/showmembers.php?Cat=$Cat&amp;page=$page\">";
      $forumlinkstop = "</a>";
   }

// -----------------------------------------
// Otherwise they came from their login page
   elseif ($what == "login") {
      $forumlinkstart = "<a href=\"{$config['phpurl']}/login.php?Cat=$Cat\">";
      $forumlinkstop = "</a>";
   }

// --------------------------------------------------------------
// Otherwise they get returned to somewhere specified by an addon
   elseif ($what == "addon") {
      $forumlinkstart = "<a href=\"$returnpage\">";
      $forumlinkstop = "</a>";
   }

// --------------------------------------------------------
// If we didn't then give them a link back to all the posts
   else {
      if ($Board) {
         $forumlinkstart = "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
         $forumlinkstop = "</a>";
      } else {
         $forumlinkstart = "<a href=\"{$config['phpurl']}/ubbthreads.php?Cat=$Cat\">";
         $forumlinkstop = "</a>";
      }
   }

	if (!$debug) {
	   include("$thispath/templates/$tempstyle/showprofile.tmpl");
	}

// ---------------
// Send the footer
   $html -> send_footer();

