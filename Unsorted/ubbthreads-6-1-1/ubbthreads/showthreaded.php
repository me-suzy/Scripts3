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
   require ("languages/${$config['cookieprefix']."w3t_language"}/showthreaded.php");
   require ("imagesizes.php");

// Define some vars
   if (empty($Main)) {
      $Main = "";
   }
   $activethread = "";
   $PNumber = "";
   $Subject = "";
   $Posted = "";
   $Approved = "";
   $alttext = "";
   $notread = "";
   $newimage = "";
   $fpart = "";
   $filelink = "";
   $Parentlink = "";
   if (empty($vc)) {
      $vc = "";
   }
// ---------------------
// SHOW REPLIES FUNCTION
   function show_replies($Cat="",$Board="",$current="",$Number="",$page="",$view="",$sb="",$indent="",$color="",$unread="",$Viewable="",$read="",$offset="",$o="",$currentkey="") {

      global $config,$dbh,$theme,$images,$ubbt_lang, $thispath,$tempstyle,$replycode,$tree,$z,$postrow,$moderatorlist;
      $html = new html;

      $parentkeys = array_keys($tree[$currentkey]);
      $indent++;
      for ($x=0; $x<$tree[$currentkey]['children'];$x++) {
         $PNumber = $tree[$currentkey][$parentkeys[$x]]['Number'];
         $Posted  = $tree[$currentkey][$parentkeys[$x]]['Posted'];
         $Poster  = $tree[$currentkey][$parentkeys[$x]]['Username'];
         $Subject = $tree[$currentkey][$parentkeys[$x]]['Subject'];
         $Open    = $tree[$currentkey][$parentkeys[$x]]['Open'];
         $Approved= $tree[$currentkey][$parentkeys[$x]]['Approved'];
         $Icon    = $tree[$currentkey][$parentkeys[$x]]['Icon'];
         $Color   = $tree[$currentkey][$parentkeys[$x]]['Color'];
         $PostStatus  = $tree[$currentkey][$parentkeys[$x]]['Status'];
         $Reged   = $tree[$currentkey][$parentkeys[$x]]['Reged'];
         if (!$Icon) { $Icon = "book.gif"; }

         $indentsize = 13 * $indent;

         $time = $html -> convert_time($Posted,$offset);
         $alt    = ".";
         $thisone = ",$PNumber,";

         $imagesize = $images['icons'];
         if ( ($Posted >= $unread) && (!strstr($read,$thisone ) ) ) {
            $alt = "*";
            $folder = "newicons";
         }
         else {
            $alt = "*";
            $folder = "icons";
         }
         if ($Open == "C") {
            $Icon = "lock.gif";                       
         }

      // ---------------------------------------
      // If it isn't approved we need to mark it
         if ($Approved == "no") {
            $Subject = "({$ubbt_lang['NOT_APPROVED']}) $Subject";
         }
         if ($Number != $PNumber) { 
            $Subjectlinkstart = "<a href=\"{$config['phpurl']}/showthreaded.php?Cat=$Cat&amp;Board=$Board&amp;Number=$PNumber&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;vc=1\">";
            $Subjectlinkstop = "</a>";
         }
         else {
            $Subjectlinkstart = "";
            $Subjectlinkstop  = "";
         }
         $UserStatus = "";

         if ($Reged == "n") {
      // ------------------------------------------------------------------
      // If we aren't allowing anon users to choose their own username then
      // we display the basic anon user in the user's selected lang  
            if (!$config['anonnames']) {
               $Username = $ubbt_lang['ANON_TEXT'];
            } else {
               $Username = $Poster;
            }
         }
         else {
            $EPoster = rawurlencode($Poster);
            $PPoster = $Poster;

         // ---------------------------------------------------------
         // We need to know if this was made by an admin or moderator
            if ($PostStatus == "A") {
               $UserStatus = "<img src=\"{$config['images']}/adm.gif\" alt=\"{$ubbt_lang['USER_ADMIN']}\" border=\"0\" />";
            }
            elseif ( ($PostStatus == "M") && (stristr($moderatorlist,$PPoster)) ) {
               $UserStatus = "<img src=\"{$config['images']}/mod.gif\" alt=\"{$ubbt_lang['USER_MOD']}\" border=\"0\" />";
            }   
            if ($Color) {
               $PPoster = "<font color=\"$Color\">$PPoster</font>";
            }

            $Username = "<a href=\"{$config['phpurl']}/showprofile.php?Cat=$Cat&amp;User=$EPoster&amp;Number=$Number&amp;Board=$Board&amp;what=showthreaded&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">$PPoster</a>";
         }

     
         $postrow[$z]['color'] = $color;
         $postrow[$z]['indentsize'] = $indentsize;
         $postrow[$z]['folder'] = $folder;
         $postrow[$z]['icon'] = $Icon;
         $postrow[$z]['Subjectlinkstart'] = $Subjectlinkstart;
         $postrow[$z]['Subject'] = $Subject;
         $postrow[$z]['Subjectlinkstop'] = $Subjectlinkstop;
         $postrow[$z]['Username'] = $Username;
         $postrow[$z]['UserStatus'] = $UserStatus;
         $postrow[$z]['time'] = $time;
         $z++; 

      // --------------------
      // alternate the colors
         $color = $html -> switch_colors($color);

         if (isset($tree[$PNumber]['children'])) {
            $color = show_replies($Cat,$Board,$PNumber,$Number,$page,$view,$sb,$indent,$color,$unread,$Viewable,$read,$offset,$o,$PNumber);
         }

      }   

      $indent--;
      return $color;

   }

// ---------------------------------
// END OF THE SHOW_REPLIES FUNCTION


// --------------------------------------------------------------
// Grab the cookie or session to mark the posts as read or unread
   $piece['0'] = "";
   preg_match("/-$Board=(.*?)-/",${$config['cookieprefix']."w3t_visit"},$piece);
   $unread = $piece['1'];

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Display, U_Groups, U_PostsPer, U_Post_Format, U_PicturePosts, U_FlatPosts, U_TempRead, U_TimeOffset,U_ShowSigs");
   $Username = $user['U_Username'];

// -------------------------------------------------------------
// If we didn't get a board or number then we give them an error
   if (!$Board) {
      $html = new html;
      $html -> not_right($ubbt_lang['NO_B_INFO'],$Cat);
   }

// ----------------------------------------------
// Set the default of viewing pictures with posts
   $PicturePosts = $user['U_PicturePosts'];
   if (!$PicturePosts) {
      $PicturePosts = $theme['PicturePosts']; 
   }

// ----------------------------------------
// Need to figure out the active thread age
   if ( ($o) && ($o != "all") ) {
     $time = time();
     $time = $time - ($o * 86400);
     $activethread = "AND B_Last_Post > $time";
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
   for ($i=0; $i<$gsize;$i++) {
      if (!preg_match("/[0-9]/",$Grouparray[$i])) { continue; };
      $g++;
      if ($g > 1) {
         $groupquery .= " OR ";
      }
      $groupquery .= "Bo_Read_Perm LIKE '%-$Grouparray[$i]-%'";
   }
   $groupquery .= ")"; 

   $html = new html;

   $jumpbox = $html -> jump_box($Cat,$groupquery,$Board);

// Find out how many are browsing this forum
   $query = "
      SELECT O_Type,O_Extra,COUNT(*)
      FROM   {$config['tbprefix']}Online
      WHERE  O_Extra LIKE '$Board%'
      GROUP BY O_Type
   ";
   $sth = $dbh -> do_query($query);
   $a = "0";
   $r = "0";
   while (list($Type,$Extra,$onlinecount) = $dbh -> fetch_array($sth)) {
      ${$Type} = $onlinecount;
   }
   
// ---------------------------
// Grab some forum information
   $Board_q = addslashes($Board);
   $query = "
     SELECT Bo_Title,Bo_Write_Perm,Bo_CatName,Bo_Cat,Bo_Reply_Perm,Bo_Read_Perm,Bo_Moderators,Bo_HTML,Bo_Markup,Bo_Write_Perm,Bo_SpecialHeader,Bo_StyleSheet
     FROM   {$config['tbprefix']}Boards
     WHERE  Bo_Keyword = '$Board_q'
     $groupquery
   ";
   $sth = $dbh -> do_query($query);
   list($title,$CanWrite,$CatName,$CatNumber,$CanReply,$ReadPerm,$moderatorlist,$HTML,$Markup,$CanWrite,$fheader,$fstyle) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// -------------------------------------------------
// Here we need to figure out what stylesheet to use
   $mystyle = $user['U_StyleSheet'];
   if (!$mystyle) { $mystyle = "usedefault"; }
   if ($mystyle == "usedefault") {
      $mystyle = $fstyle;
      if ($mystyle == "usedefault") {
         $mystyle = $theme['stylesheet'];
      }
   }
// fstyle will now be a global variable to use in send_header
   $fstyle = $mystyle;

   // ----------------
   // SHow moderators?
      $modarray = split(",",$moderatorlist);
      $modsize = sizeof($modarray);
      $comma =0;
      $modlist = "";
      for ($i=0;$i<$modsize;$i++) {
         if ($modarray[$i]) {
            if ($comma) { $modlist .= ", "; }
            $EUsername = rawurlencode($modarray[$i]);
            $modlist .= "<a href=\"{$config['phpurl']}/showprofile.php?User=$EUsername&amp;What=ubbthreads\">$modarray[$i]</a>";
            $comma++;
         }
         else {
            $modlist .= "&nbsp;";
         }
      }

// Let's see if the can post or reply
   $replyperm = $ubbt_lang['CANTREPLY'];
   $writeperm = $ubbt_lang['CANTWRITE'];

   for ($i=0; $i<$gsize; $i++) {
      if (strstr($CanReply,"-$Grouparray[$i]-") ) {
         $replyperm = $ubbt_lang['CANREPLY'];
         $canreply = 1;
      }
      if (strstr($CanWrite,"-$Grouparray[$i]-") ) {
         $writeperm = $ubbt_lang['CANWRITE'];
         $canwrite = 1;
      }
      if (($canwrite) && ($canreply)) {
         break;
      }
   }
   if ($Markup == "On") {
      $ubbcode = "{$ubbt_lang['UBBCODE']} {$ubbt_lang['ENABLED']}";
   }
   else {
      $ubbcode = "{$ubbt_lang['UBBCODE']} {$ubbt_lang['DISABLED']}";
   }
   if ($HTML == "On") {
      $htmlcode = "{$ubbt_lang['HTMLIS']} {$ubbt_lang['ENABLED']}";
   }
   else {
      $htmlcode = "{$ubbt_lang['HTMLIS']} {$ubbt_lang['DISABLED']}";
   }


   if (!$title) {
      $html -> not_right($ubbt_lang['BAD_GROUP'],$Cat);
   }

// ---------------------------------------------------------------
// If they are a normal user then they can only see approved posts
   $ismod = "no";
   $Viewable = "AND B_Approved = 'yes'";
   if ($user['U_Status'] == "Administrator") {
      $Viewable = "";
   }
   if ($user['U_Status'] == "Moderator") {
   // Check if they moderate this board
      if (preg_match("/(,|^)$Username(,|$)/i",$moderatorlist)) {
         $Viewable = "";
         $ismod = "yes";
      }
   } 

// ----------------------------------------------------
// If we don't have a post number then we can't view it
   if (!$Number) {
      $html = new html;
      $html -> not_right($ubbt_lang['POST_PROB'],$Cat);
   }

// -----------------------------------------
// Grab the main post number for this thread
	$Number = addslashes($Number);
   $query = "
      SELECT B_Main, B_Last_Post,B_Subject,B_Rating,B_Rates,B_RealRating
      FROM   {$config['tbprefix']}Posts
      WHERE  B_Number = $Number
		AND    B_Board = '$Board'
   ";
   $sth = $dbh -> do_query($query);
   list ($current,$posted,$tsubject,$Rating,$Rates,$stars) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   $ThreadRating = "";
   if ($stars) {
      for ($x=1;$x<=$stars;$x++) {
         $ThreadRating .= "<img src=\"{$config['images']}/star.gif\" title= \"$Rates {$ubbt_lang['T_RATES']}\" alt=\"*\" />";
      }
   }

// ---------------------------------------
// Set the flat switch to the Main Number
   $Flat = $current;

// ------------------------------------
// Let's see if they rated this thread
   $username_q = addslashes($user['U_Username']);
   $query = "
      SELECT R_Rating
      FROM   {$config['tbprefix']}Ratings
      WHERE  R_What = '$Flat'
      AND    R_Rater = '$username_q'
      AND    R_Type  = 't'
   ";
   $sth = $dbh -> do_query($query);
   list($myrating) = $dbh -> fetch_array($sth);

// -----------------------------------
// Figure out what we need to display
   if (!$myrating) {
      $ratinghtml = <<<EOF
<form method="post" action="{$config['phpurl']}/doratethread.php">
<input type="hidden" name="Ratee" value="$User" />
<input type="hidden" name="Cat" value="$Cat" />
<input type="hidden" name="Board" value="$Board" />
<input type="hidden" name="Number" value="$Number" />
<input type="hidden" name="Main" value="$Flat" />
<input type="hidden" name="what" value="showthreaded" />
<input type="hidden" name="page" value="$page" />
<input type="hidden" name="view" value="$view" />
<input type="hidden" name="sb" value="$sb" />
<input type="hidden" name="o" value="$o" />
{$ubbt_lang['RATETHIS']}
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

// -------------------------------------------------------------
// If we didn't find the main post, then this post doesn't exist
   if (!$current) {
      $html -> not_right($ubbt_lang['POST_PROB'],$Cat);
   }

   if (!$vc) {
      $query = "
         UPDATE {$config['tbprefix']}Posts
         SET    B_Counter = B_Counter + 1
         WHERE  B_Main = $current
      ";
      $dbh -> do_query($query);
   }

// -------------------------------------------------------------------
// Mark this message as read, appending it to the others
   $read = $user['U_TempRead'];
   $check = ",$Number,";
   if ($config['newcounter']) {
      if ( (!strstr($read,$check)) && ($posted > $unread) ) {
         $read = $read . ",$Number,";
         $Username_q = addslashes($Username);
         $read_q     = addslashes($read);
         $query = "
            UPDATE {$config['tbprefix']}Users
            SET    U_TempRead = '$read_q'
            WHERE  U_Username = '$Username_q'
         ";
         $dbh -> do_query($query);
      }
   }

// --------------------------
// Give the start of the page   
   $Extra = $Board . "_SEP_" .$Number. "_SEP_" .$tsubject;
   $html -> send_header($tsubject,$Cat,0,$user,$Extra,$ReadPerm);

// --------------------------------------------------------------------
// If dateslip is on, we sort by Last_Post, otherwise we sort by Posted
   if (!$config['dateslip']) {
      $sort_opt = array(
         1 => 'B_Subject DESC',
         2 => 'B_Subject ASC',
         3 => 'B_Username DESC',
         4 => 'B_Username ASC',
         5 => 'B_Posted DESC',
         6 => 'B_Posted ASC'
      );
      $sort_by = $sort_opt[$sb];
      if (!$sort_by) { $sort_by = "B_Posted DESC"; }
   }
   else {    
      $sort_opt = array(
        1 => 'B_Subject DESC',
        2 => 'B_Subject ASC',
        3 => 'B_Username DESC',
        4 => 'B_Username ASC',
        5 => 'B_Last_Post DESC',
        6 => 'B_Last_Post ASC'
      );
      $sort_by = $sort_opt[$sb];
      if(!$sort_by) {$sort_by = "B_Last_Post DESC";} 
   }         

// -------------------------------------------------------------------------
// Grab all of the main posts from the database that are on the current page
// We also need to grab one from the page before and one from the next page
// just so we can check if there needs to be a previous or next thread link
// But we only need to do this if they aren't coming from the search page
   if ($Search != "true") {
      $PostsPer = $user['U_PostsPer'];
      if (!$PostsPer) { $PostsPer = $theme['postsperpage']; }
   
      if ($page < 1) {
         $Totalgrab = $PostsPer + 2;
         $Posts     = $PostsPer + 2;
      }
      else {
         $Startat   = $page * $PostsPer - 1;
         $Posts     = $PostsPer + 2;
         $Totalgrab = "$Startat, $Posts";
      }      
   
      $limit = "LIMIT $Totalgrab";
      $query = "
         SELECT B_Number,B_Main,B_Username,B_Subject,B_Posted,B_Approved
         FROM  {$config['tbprefix']}Posts
         WHERE B_Topic = 1
         AND   B_Board  = '$Board_q'
         $activethread
         $Viewable
         ORDER BY $sort_by
         $limit
      ";
      $sth = $dbh -> do_query($query);
      $total = $dbh -> total_rows($sth);
      $newpage = 0;
	   $prevoption = "greyprevious.gif";
  		$nextoption = "greynext.gif";
      $linktext = $ubbt_lang['INDEX_ICON'];

	// In case we are linking to an old post, we need to force the link
	// to the first page in postlist.php
      $currentlinkstart = "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
      $currentlinkstop = "</a>";
 
      for ($i = 0; $i < $total; $i++) {
   
         $OldNumber = $PNumber;
         $OldMain   = $Main;
         $OldUsername = $Username;
         $OldSubject  = $Subject;
         $OldPosted   = $Posted;
         $OldApproved = $Approved;
         list ($PNumber,$Main,$Username,$Subject,$Posted,$Approved) = $dbh -> fetch_array($sth);
      // -----------------------------------------------
      // Check to see if they get a previous link button
         if ( ( ($PNumber == $current) && ($page == 0) && ($i > 0) ) || ( ($PNumber == $current) && ($page != 0) ) ) {
     
            if ($i == 1) {
               $whichpage = $page - 1;
            }
            else {
               $whichpage = $page;
            }
            if ($whichpage < 0) { $whichpage = 0; }
            $prevlinkstart = "<a href=\"{$config['phpurl']}/showthreaded.php?Cat=$Cat&amp;Board=$Board&amp;Number=$OldNumber&amp;page=$whichpage&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
            $prevoption = "previous.gif";
            $prevlinkstop = "</a>";
            $previous = 1;
   
         }
   
      // -----------------------------------------------------------------------
      // If we are on the current thread then we give the link for all threads
         if ($PNumber == $current) {
   
            $currentlinkstart = "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
            $currentlinkstop = "</a>";
   
         // ---------------------------
         // Now we grab the next thread
            $i++;
            list ($PNumber,$Main,$Username,$Subject,$Posted,$Approved) = $dbh -> fetch_array($sth);
   
         // ---------------------------------------------------------------
         // If there is a next thread then give them a link to it otherwise
         // they get the greyed out image
            $nextoption = "next.gif";
            if ($PNumber) {
               if ( ($i == $total) && ($i > $PostsPer) ) {
                  $whichpage = $page + 1;
               } else {
                  $whichpage = $page;
               }
               $nextlinkstart = "<a href=\"{$config['phpurl']}/showthreaded.php?Cat=$Cat&amp;Board=$Board&amp;Number=$PNumber&amp;page=$whichpage&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
               $nextlinkstop = "</a>";
            }
   
         // --------------------------------------------------------------------
         // If we got here then we are listing the original thread starting post
         // so we set original to 1
            $original = 1;
            break;
         
         }
   
      }
      $dbh -> finish_sth($sth);
   }
   else {
   // ------------------------------------------------------
   // Otherwise we came from the search so we can only return
      $nextoption = "greynext.gif";
      $Words = rawurlencode($Words);
      $Forum = rawurlencode($Forum);
      $Match = rawurlencode($Match);
      $currentlinkstart = "<a href=\"{$config['phpurl']}/dosearch.php?Cat=$Cat&amp;Forum=$Forum&amp;Words=$Words&amp;Match=$Match&amp;Searchpage=$Searchpage&amp;Limit=$Limit&amp;Old=$Old\">";
      $currentlinkstop = "</a>";
      $linktext = $ubbt_lang['INDEX_ICON'];
      $prevoption = "greyprevious.gif";
   }

// --------------------------------------
// Give a link to switch to threaded mode
   if (!$Flat) {
      $Flat = $Number;
   }

// -------------------------------------------------
// Only certain options for users that are logged in
   if ($user['U_Username']) {
      $addfavoption = "
<a href=\"{$config['phpurl']}/addfav.php?Cat=$Cat&amp;Board=$Board&amp;main=$current&amp;type=favorite&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;vc=1&amp;what=showthreaded\">{$ubbt_lang['ADD_FAV']}</a>
     ";
   }


// -----------------------
// Grab the post 
   $query = "
      SELECT t1.B_Number,t1.B_Posted,t1.B_Username,t1.B_IP,t1.B_Subject,t1.B_Body,t1.B_File,t1.B_Status,t1.B_Approved,t2.U_Picture,t1.B_Reged,t2.U_Title,t1.B_Sticky,t2.U_Color,t1.B_Icon,t1.B_Poll,t1.B_ParentUser,t1.B_Parent,t2.U_Status,t2.U_Signature,t1.B_LastEdit,t1.B_LastEditBy,t2.U_Location,t2.U_TotalPosts,t2.U_Registered,t2.U_Rating,t2.U_Rates,t2.U_RealRating,t2.U_PicWidth,t2.U_PicHeight,t2.U_Number,t1.B_FileCounter
      FROM  {$config['tbprefix']}Posts AS t1,
            {$config['tbprefix']}Users AS t2
      WHERE t1.B_Number  = $Number
      AND   t1.B_PosterId = t2.U_Number
      $Viewable
   ";
   $sth = $dbh -> do_query($query);

   list ($Number,$Posted,$Username,$IP,$SubjectMain,$Body,$File,$Open,$Approved,$Picture,$Reged,$Title,$Sticky,$Color,$Icon,$Poll,$Parent,$ParentPost,$PostStatus,$Signature,$LastEdit,$LastEditBy,$Location,$TotalPosts,$Registered,$Rating,$Rates,$stars,$picwidth,$picheight,$usernum,$downloads) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   $Rating = "";

   if ( ($Reged == "y") && ($usernum != 1) ){
      $Registered = $html -> convert_time($Registered,$user['U_TimeOffset']);
      list ($one,$two,$three,$four) = split(" ",$Registered);
      if ($theme['timeformat'] == "long") {
         $Registered = "$one $two $three $four";
      } else {
         $Registered = "$one";
		}
      $Registered = "{$ubbt_lang['REGED_ON']} $Registered";
      if ($Location) {
         $Location = "{$ubbt_lang['USER_LOC']} $Location";
      }
      $TotalPosts = "{$ubbt_lang['POSTS_TEXT']}: $TotalPosts"; 
      if ($Signature) {
         $Signature = str_replace("\n","<br />",$Signature);
         $Signature = "<br /><br />$Signature";
      }
      if ($user['U_ShowSigs'] == "no") {
         $Signature = "";
      }
      if ($stars) {
         for ($x=1;$x<=$stars;$x++) {
            $Rating .= "<img src=\"{$config['images']}/star.gif\" title= \"$Rates {$ubbt_lang['T_RATES']}\" alt=\"*\" />";
         }
      }
   // ---------------------------------------------------------
   // We need to know if this was made by an admin or moderator
      $MainUserStatus = "";
      if ($PostStatus == "Administrator") {
         $MainUserStatus = "<img src=\"{$config['images']}/adm.gif\" alt=\"{$ubbt_lang['USER_ADMIN']}\" border=\"0\" />";
      }
      elseif ( ($PostStatus == "Moderator") && (stristr($moderatorlist,$Username)) ){
         $MainUserStatus = "<img src=\"{$config['images']}/mod.gif\" alt=\"{$ubbt_lang['USER_MOD']}\" border=\"0\" />";
      }   

      if ( ($Picture) && ($Picture != "http://") && ( ($PicturePosts == 1) || ($PicturePosts == "on") ) ) {
         $picsize = "";
         if ($picwidth && $picheight) {
             $picsize = "width=\"$picwidth\" height=\"$picheight\"";
        }
        else {
            $picsize = "width=\"{$theme['PictureWidth']}\" height=\"{$theme['PictureHeight']}\"";
        }

         $Picture = "<img src=\"$Picture\" alt=\"\" $picsize />";
      }
      else {
         $Picture = "";
      }
      $EUsername = rawurlencode($Username);
      $PUsername = $Username;
      if ($Color) {
         $PUsername = "<font color=\"$Color\">$PUsername</font>";
      }
   }
   else {
      $Picture = "";
      $Registered = "";
      $TotalPosts = "";
      $Location = "";
   }
   $PrintLastEdit = "";
   if ($LastEdit) {
      $LastEdit = $html -> convert_time($LastEdit,$user['U_TimeOffset']);
      $PrintLastEdit = "<br /><br /><font class=\"small\"><em>{$ubbt_lang['EDITED_BY']} $LastEditBy ($LastEdit)</em>";
   }

   if ($Sticky) {
      $timeMain = $html -> convert_time($Sticky,$user['U_TimeOffset']);
   } else {
      $timeMain = $html -> convert_time($Posted,$user['U_TimeOffset']);
   }


// ------------------------------------------------------------------
// If we came from the search engine then we bold the search keywords
   if ( ($Search == "true") && ($Words) ){
       $searchwords = split(" +",$Words);
       $size = sizeof($searchwords);
       for ($x=0; $x<$size; $x++) {
          $Body = str_replace($searchwords[$x],"<b><i>".$searchwords[$x]."</i></b>",$Body);
          $Body = preg_replace("/(<(a|img)\s*[^>]+)<b><i>($searchwords[$x])<\/i><\/b>([^>]*>)/i","\\1\\3\\4",$Body);
      }
   }
   if ( ($notread[$Number] == "true") && ($Posted > $unread) ) {
      $newimage = "<img alt=\"{$ubbt_lang['NEW_TEXT']}\" src=\"{$config['images']}/new.gif\" />";
   };

// ------------------------------------------
// Set both the reply and edit buttons to off
   $reply = "off";
   $edit  = "off";

// -------------------
// Can they post here?
   $gsize = sizeof($Grouparray);
   for ($y=0; $y <$gsize; $y++) {
      if ( (strstr($CanWrite,"-$Grouparray[$y]-") ) || (strstr($CanReply,"-$Grouparray[$y]-") ) ) {
         $makepost = "yes";
         break;
      }
   }    

   if ( ($makepost == "yes") && ($Open != "C") ){
      $reply = "on";
   }

// ---------------------------
// Do they get an edit button?
   if ($user['U_Username']) {
      if ( (strtolower($user['U_Username']) == strtolower("$Username")) || ($ismod == "yes") || ($user['U_Status'] == "Administrator") ) {
         $edit = "on";
      }
   }

   $post_format = $user['U_Post_Format'];
   if (!$post_format) { $post_format = $theme['post_format']; }

// -------------------------
// Mark it if it isn't approved
   if ($Approved == "no") {
      $Subject = "({$ubbt_lang['NOT_APPROVED']}) $Subject";
   }

// ---------------------------------------------------------------
// If this is a new post then we  need to set $folder to newicons
   if ($newimage) {
      $folder = "newicons";
   } else {
      $folder = "icons";
   }

   if (!$Icon) {
      $Icon = "book.gif";
   }

   // --------------------------------------------------------------
   // If it is an anonymous post, don't give a link to their profile
      if ($Reged == "n") {
         if (!$config['anonnames']) {
            $Username = $ubbt_lang['ANON_TEXT'];
         }
         $Title = $ubbt_lang['UNREGED_USER'];
      }
      else {
         $Username = "<a href=\"{$config['phpurl']}/showprofile.php?Cat=$Cat&amp;User=$EUsername&amp;Number=$Number&amp;Board=$Board&amp;what=showthreaded&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$fpart&amp;vc=1\">$PUsername</a>";
      }

      if ( ($config['showip'] == 1) && $IP) {
			$IP = "($IP)";
      }
      elseif ( ($config['showip'] == 2) && ( ($user['U_Status'] == "Administrator") || ($ismod == 'yes') ) && ($IP) ) {
			$IP = "($IP)";
      }
      elseif ( ($config['showip'] == 3) && ($user['U_Status'] == "Administrator") && ($IP) ) {
			$IP = "($IP)";
      }
      else {
        $IP = "";
      }

      if ($File) {
	 		$File = rawurlencode($File);
			if (!$downloads) {$downloads = 0; }
         $filelink = "<a href=\"{$config['phpurl']}/download.php?Number=$Number\">{$ubbt_lang['FILE_ATTACH']}</a> ($downloads {$ubbt_lang['DOWNLOADS']})<br />";
      }

      if ($Parent) {
         $Parentlink = " <font class=\"small\">[re: $Parent]</font>";
      }
      if ( ($edit == "on") || ($reply == "on") ) {
          if ($edit == "on") {
             $editlinkstart = "<a href=\"{$config['phpurl']}/editpost.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;what=showthreaded&amp;sb=$sb&amp;o=$o&amp;vc=1\">";
             $editlinkstop = "</a>";
          }
          if ($reply == "on") {
             $replylinkstart = "<a href=\"{$config['phpurl']}/newreply.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;what=showthreaded&amp;sb=$sb&amp;o=$o\">";
             $replylinkstop = "</a>";
          }
      }

   // -----------------------------------------------------------------------
   // If there is a poll in this post, we need to give a link to view results
      if ($Poll) {
         $Body = str_replace("</form>","<br /><a href=\"{$config['phpurl']}/viewpoll.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;Board=$Board&amp;what=showthreaded&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;vc=1&amp;poll=$Poll\">{$ubbt_lang['VIEW_POLL']}</a></form>",$Body);
         $Body = str_replace("\$ubbt_lang[SUB_VOTE]'}","{$ubbt_lang['SUB_VOTE']}",$Body);
      }  

   // -------------------------------------------------
   // Only certain options for users that are logged in
      if ($user['U_Username']) {
         $addfavlinkstart = "&nbsp;<a href=\"{$config['phpurl']}/addfav.php?Cat=$Cat&amp;Board=$Board&amp;main=$Number&amp;type=reminder&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;vc=1&amp;what=showthreaded\">";
         $addfavlinkstop = "</a>";
         if ($config['mailpost']) {
            $mailpostlink = "
<a href=\"{$config['phpurl']}/mailthread.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;what=showthreaded\"><img alt =\"{$ubbt_lang['EMAIL_POST']}\" src=\"{$config['images']}/sendbyemail.gif\" border=\"0\" title=\"{$ubbt_lang['EMAIL_POST']}\" /></a>
            ";
         }
         $notifylinkstart = "<a href=\"{$config['phpurl']}/notifymod.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&o=$o&amp;what=showthreaded\">";
         $notifylinkstop = "</a>";
      }


// --------------------------------------
// Are there any replies to this message?

      $color = "lighttable";
      $indent = 0;

   // -----------------------------------
   // List the first post for this thread
      $query = "
         SELECT B_Number,B_Parent,B_Posted,B_Username,B_Subject,B_Status,B_Approved,B_Icon,B_Color,B_Sticky,B_Reged,B_UStatus,B_Counter
         FROM {$config['tbprefix']}Posts
         WHERE  B_Number = $current
      ";
      $sth = $dbh -> do_query($query);
      list ($PNumber,$Parent,$Posted,$Username1,$Subject,$Locked,$Approved,$icon,$Color,$Sticky,$Reged,$PostStatus,$counter) = $dbh -> fetch_array($sth);
      $dbh -> finish_sth($sth);

      if (!$icon) {
         $icon = "book.gif";
      }
      
      if ($Sticky) {
         $time = $html -> convert_time($Sticky,$user['U_TimeOffset']);
      } else {
         $time = $html -> convert_time($Posted,$user['U_TimeOffset']);
      }

      $thisone = ",$PNumber,";
      $alt     = "."; 

      $imagesize = $images['icons'];
      if ( ($Posted > $unread) && (!strstr($read,$thisone ) ) ) {
         $alt = "*";
         $folder = "newicons";
      }
      else {                              
         $alt = "*";
         $folder = "icons";
      }
      if ($Open == "C") {
         $icon = "lock.gif";
      }
   // ---------------------------------------
   // If it isn't approved we need to mark it
      if ($Approved == "no") {
         $Subject = "({$ubbt_lang['NOT_APPROVED']}) $Subject";
      }

      if ($Number != $PNumber) {
         $Subjectlinkstart = "<a href=\"{$config['phpurl']}/showthreaded.php?Cat=$Cat&amp;Board=$Board&amp;Number=$PNumber&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
         $Subjectlinkstop = "</a>";  
      }

      if ($Reged == "n") {
   // ------------------------------------------------------------------
   // If we aren't allowing anon users to choose their own username then
   // we display the basic anon user in the user's selected lang
         if (!$config['anonnames']) {
            $Username1 = $ubbt_lang['ANON_TEXT'];
         }
      }
      else {
         $EUsername = rawurlencode($Username1);
         $PUsername = $Username1;
         if ($Color) {
            $PUsername = "<font color=\"$Color\">$PUsername</font>";
         }

      // ---------------------------------------------------------
      // We need to know if this was made by an admin or moderator
         $RowUserStatus = "";
         if ($PostStatus == "A") {
            $RowUserStatus = "<img src=\"{$config['images']}/adm.gif\" alt=\"{$ubbt_lang['USER_ADMIN']}\" border=\"0\" />";
         }
         elseif ($PostStatus == "M") {
            $RowUserStatus = "<img src=\"{$config['images']}/mod.gif\" alt=\"{$ubbt_lang['USER_MOD']}\" border=\"0\" />";
         }   

         $Username1 = "<a href=\"{$config['phpurl']}/showprofile.php?Cat=$Cat&amp;User=$EUsername&amp;Number=$Number&amp;Board=$Board&amp;what=showthreaded&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;vc=1\">$PUsername</a>";
      }               

   // alternate the colors
      $color = $html -> switch_colors($color);

   // --------------------------------------
   // Grab all of the replies in this thread
      $query = "
        SELECT B_Number,B_Parent,B_Posted,B_Username,B_Subject,B_Status,B_Approved,B_Icon,B_Reged,B_Color,B_UStatus
        FROM  {$config['tbprefix']}Posts
        WHERE B_Main=$current
        $Viewable
        ORDER BY B_Number DESC
      ";
      $sth = $dbh -> do_query($query);
      while(list($anumber,$aparent,$aposted,$ausername,$asubject,$aopen,$aapproved,$aicon,$areged,$acolor,$austatus) = $dbh -> fetch_array($sth)) {
         if ($aparent == "0") {
            continue;
         }
         $tree[$aparent][$anumber]['Posted'] = $aposted;
         $tree[$aparent][$anumber]['Username'] = $ausername;
         $tree[$aparent][$anumber]['Subject'] = $asubject;
         $tree[$aparent][$anumber]['Approved'] = $aapproved;
         $tree[$aparent][$anumber]['Open'] = $aopen;
         $tree[$aparent][$anumber]['Icon'] = $aicon;
         $tree[$aparent][$anumber]['Reged'] = $areged;
         $tree[$aparent][$anumber]['Color'] = $acolor;
         $tree[$aparent][$anumber]['Status'] = $austatus;
         $tree[$aparent][$anumber]['Number'] = $anumber;
         $istree = 1;
      }

    // Find out the number of replies to each parent
       if ($istree) {
          $parentkeys = array_keys($tree);
          $parentsize = sizeof($parentkeys);
    
          for($i=0;$i<$parentsize;$i++) {
            $childkeys = array_keys($tree[$parentkeys[$i]]);
            $childsize = sizeof($childkeys);
            $tree[$parentkeys[$i]]['children'] = $childsize;
          } 
             
          $z = 0; 
          $color = show_replies($Cat,$Board,$current,$Number,$page,$view,$sb,$indent,$color,$unread,$Viewable,$read,$user['U_TimeOffset'],$o,$current);
     }
    
  $postrowsize = sizeof($postrow); 
	$alttext = $ubbt_lang['ALL_THREADS'];

	if (!$debug) {
	  include("$thispath/templates/$tempstyle/showthreaded.tmpl");
	}

// Send the footer
  $html -> send_footer();


?>
