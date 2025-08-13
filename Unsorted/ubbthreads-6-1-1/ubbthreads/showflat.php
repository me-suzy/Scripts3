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
   require ("languages/${$config['cookieprefix']."w3t_language"}/showflat.php");
   require ("imagesizes.php");

// Define some variables
   $PNumber = "";
   if(empty($Search)) {
      $Search = "";
   }
   $notread  = "";
   $filelink = "";

// ----------------------------------------------------------------
// Grab the cookie or session to mark the posts as read or unread
   if(empty(${$config['cookieprefix']."w3t_visit"})) {
      ${$config['cookieprefix']."w3t_visit"} = "";
   }
   $piece['0'] = "";
   preg_match("/-$Board=(.*?)-/",${$config['cookieprefix']."w3t_visit"},$piece); 
   if (isset($piece['1'])) {
      $unread = $piece['1'];
   }



// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Display, U_Groups, U_PostsPer, U_Post_Format, U_PicturePosts, U_FlatPosts, U_TempRead, U_TimeOffset,U_ShowSigs");
   $Username = $user['U_Username'];

   $html = new html;

// -------------------------------------------------------------
// If we didn't get a board or number then we give them an error
   if (!$Board) {
      $html -> not_right($ubbt_lang['NO_B_INFO'],$Cat);
   }

// ------------------------------------------------------------
// Update the last visit to this board 
   $Username_q = addslashes($Username);
   $LastViewed = "$Board"."Last";
   if ( ($Username) && (!strstr(${$config['cookieprefix']."w3t_visit"},"-$Board=") ) ) {
      $query = "
        SELECT L_Last
        FROM   {$config['tbprefix']}Last
        WHERE  L_Username = '$Username_q'
        AND    L_Board    = '$Board_q'
      ";
      $sth = $dbh -> do_query($query);
      list($oldlast) = $dbh -> fetch_array($sth);
      $dbh -> finish_sth($sth);
  
   // ---------------------------------------
   // Set a cookie, or register a session var
      if ($config['tracking'] == "sessions") {
         session_register("{$config['cookieprefix']}w3t_visit");
         ${$config['cookieprefix']."w3t_visit"} .= "-$Board=$oldlast-";
      }
      else {
         ${$config['cookieprefix']."w3t_visit"} .= "-$Board=$oldlast-";
         setcookie("{$config['cookieprefix']}w3t_visit",${$config['cookieprefix']."w3t_visit"},0);
      }
      $unread = $oldlast;
   }

// ----------------------------------------------
// Set the default of viewing pictures with posts
   $PicturePosts = $user['U_PicturePosts'];
   if (!$PicturePosts) {
      $PicturePosts = $theme['PicturePosts']; 
   }

// ----------------------------------------
// Need to figure out the active thread age
   $activethread = "";
   if ( ($o) && ($o != "all") ) {
     $time = $html -> get_date();
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

// -----------------------------------------
// Grab the main post number for this thread
	$Number = addslashes($Number);
   $query = "
      SELECT B_Main
      FROM   {$config['tbprefix']}Posts
      WHERE  B_Number = '$Number'
		AND    B_Board = '$Board'
   ";
   $sth = $dbh -> do_query($query);
   list ($current) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);


// ------------------------------------------------
// Keep the current thread number for page linking
   $pagelinker = $current;

// -------------------------------------------------------------
// If we didn't find the main post, then this post doesn't exist
   if (!$current) {
      $html -> not_right($ubbt_lang['POST_PROB'],$Cat);
   }
   
// ---------------------------
// Grab some forum information
   $Board_q = addslashes($Board);
   $query = "
     SELECT Bo_Title,Bo_Write_Perm,Bo_CatName,Bo_Cat,Bo_Reply_Perm,Bo_Read_Perm,Bo_Moderators,Bo_Markup,Bo_HTML,Bo_SpecialHeader,Bo_StyleSheet
     FROM   {$config['tbprefix']}Boards
     WHERE  Bo_Keyword = '$Board_q'
     $groupquery
   ";
   $sth = $dbh -> do_query($query);
   list($title,$CanWrite,$CatName,$CatNumber,$CanReply,$ReadPerm,$moderatorlist,$Markup,$HTML,$fheader,$fstyle) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if (!$title) {
      $html -> not_right($ubbt_lang['BAD_GROUP'],$Cat);
   }

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
      $html -> not_right($ubbt_lang['POST_PROB'],$Cat);
   }

// ------------------------------------------------------------
// If we are showing thread views, we need to update the counter
   if (empty($vc)) {
      $query = "
         UPDATE {$config['tbprefix']}Posts
         SET    B_Counter = B_Counter + 1
         WHERE  B_Main = '$current'
      ";
      $dbh -> do_query($query);
   }

// ----------------------------------------------------
// Mark this message as read appending it to the others
   $read = $user['U_TempRead'];
   $Totaldisplay = $user['U_FlatPosts'];
   if (!$Totaldisplay) { $Totaldisplay = $theme['flatposts']; }

// -----------------------------------------------------------
// Check if there are any replies in this thread, and how many
   $query = "
     SELECT B_Replies,B_Counter,B_Last_Post,B_Subject,B_Rating,B_Rates,B_RealRating
     FROM   {$config['tbprefix']}Posts
     WHERE  B_Number = '$current'
   ";
   $sth = $dbh -> do_query($query);
   list($checkreplies,$count,$posted,$tsubject,$Rating,$Rates,$stars) = $dbh -> fetch_array($sth);
   $length = $checkreplies + 1;

   $ThreadRating = "";
   if ($stars) {
      for ($x=1;$x<=$stars;$x++) {
         $ThreadRating .= "<img src=\"{$config['images']}/star.gif\" title= \"$Rates {$ubbt_lang['T_RATES']}\" alt=\"*\" />";
      }
   }


// ------------------------------------
// Let's see if they rated this thread
   $username_q = addslashes($user['U_Username']);
   $query = "
      SELECT R_Rating
      FROM   {$config['tbprefix']}Ratings
      WHERE  R_What = '$current'
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
<input type="hidden" name="Main" value="$current" />
<input type="hidden" name="what" value="showflat" />
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

// If we didn't get a fpart then we need to figure out what fpart
// this post is on
   if ( (empty($fpart)) && ($length > $Totaldisplay) ) {
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Posts
         WHERE  B_Main='$current'
         AND    B_Number<='$Number'
      ";
      $sti = $dbh -> do_query($query);
      list($totalreplies) = $dbh -> fetch_array($sti);
      $fpart = intval($totalreplies / $Totaldisplay) + 1;
   }
   if (!$fpart) { $fpart = 1; }

   if ($config['newcounter']) {

      $check = ",$Number,";
      if ( (!strstr($read,$check)) && ($posted > $unread) ) {
         $read = $read . ",$Number,";
      }
   
   // -----------------------------------------------------------
   // Check if there are any replies in this thread, and how many
      $query = "
        SELECT B_Number
        FROM   {$config['tbprefix']}Posts
        WHERE  B_Main = '$current'
      ";
      $sth = $dbh -> do_query($query);
      $checkreplies = $dbh -> total_rows($sth);

   // -------------------------------------------------------------------
   // We need to mark every post on this page as read and assign the total
   // number of posts to the lenght variable for later use.  Also we need
   // to make sure that we are only marking posts as read that appear on
   // this page
      for ($i=0; $i < $checkreplies; $i++) {
         if ( ($fpart != "all") && ($i >= ($Totaldisplay * $fpart) ) ) {
            break;
         }
         list($Marknumber) = $dbh -> fetch_array($sth);
         $check = ",$Marknumber,";
         if ( (!stristr($read,$check)) && ($posted > $unread) ) {
            $notread[$Marknumber] = "true";
            $read = $read . ",$Marknumber,";
         }
      }
      $dbh -> finish_sth($sth);

   // ----------------------------------------------------------
   // Now we need to update their TempRead field in the database
      $read_q     = addslashes($read);
      $query = "
         UPDATE {$config['tbprefix']}Users
         SET    U_TempRead = '$read_q'
         WHERE  U_Username = '$Username_q'
      ";
      $dbh -> do_query($query);
   }
   
// --------------------------
// Give the start of the page   
   $Extra = $Board ."_SEP_".$current."_SEP_" .$tsubject;
   $html -> send_header("$tsubject",$Cat,0,$user,$Extra,$ReadPerm);

// --------------------
// Give the jumper box
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

// ---------------------------------------------------------------------
// Now we calculate which posts to grab for this page.  We want to grab
// one from the previous page and one from the next page so we know what
// the previous and nexts posts will be
// But only if we didn't come from the search engine
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
         SELECT B_Number
         FROM  {$config['tbprefix']}Posts
         WHERE B_Topic = '1'
         AND   B_Board  = '$Board_q'
         $activethread
         $Viewable
         ORDER BY $sort_by
         $limit
      ";
      $sth = $dbh -> do_query($query);
      $total = $dbh -> total_rows($sth);
		$nextoption = "greynext.gif";
      $prevoption = "greyprevious.gif";
      $linktext = $ubbt_lang['INDEX_ICON'];
      $newpage = 0;
  
   // In case we are linking to an old post, we need to force the link
   // to the first page in postlist.php
      $currentlinkstart = "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
      $currentlinkstop = "</a>";
 
   
      for ($i = 0; $i < $total; $i++) {
   
         $OldNumber = $PNumber;
         list ($PNumber) = $dbh -> fetch_array($sth);
         if (!$length) { $length = $Rlength; }


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
            $prevlinkstart = "<a href=\"{$config['phpurl']}/showflat.php?Cat=$Cat&amp;Board=$Board&amp;Number=$OldNumber&amp;page=$whichpage&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
            $prevoption = "previous.gif";
            $prevlinkstop = "</a>";
            $previous = 1;
   
         }
   
      // -----------------------------------------------------------------------
      // If we are on the current thread then we give the link for all threads
      // unless they came from the search engine, then we give them a link back
      // to that
         if ($PNumber == $current) {
   
            $currentlinkstart = "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
            $currentlinkstop = "</a>";
            $alttext = $ubbt_lang['ALL_THREADS'];
   
         // ---------------------------
         // Now we grab the next thread
            $i++;
            list ($PNumber) = $dbh -> fetch_array($sth);
   
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
   
               $nextlinkstart = "<a href=\"{$config['phpurl']}/showflat.php?Cat=$Cat&amp;Board=$Board&amp;Number=$PNumber&amp;page=$whichpage&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
               $nextoption = "next.gif";
               $nextlinkstop = "</a>";
            }
				else {
					$nextoption = "greynext.gif";
				}
	
   
         // --------------------------------------------------------------------
         // If we got here then we are listing the original thread starting post
         // so we set original to 1
            $original = 1;
            break;
         }
      }
   } else {
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
   $dbh -> finish_sth($sth);

// -------------------------------------------
// Let's see how many posts are in this thread
   $pages = $length / $Totaldisplay;
   list ($pages,$decimal) = split("\.",$pages);
   if ($decimal > 0) { $pages++; }
   if ($pages > 1) {
      $pageprint = "{$ubbt_lang['FLAT_PAGES']}";
      $StartPage = $fpart - 10;
      $EndPage   = $fpart + 10;
      if ($StartPage < 1) {
         $EndPage = $EndPage - $StartPage;
         $StartPage = 1;
      }
      if ($EndPage > $pages) {
         $EndPage = $pages;
         $StartPage = $EndPage - 20;
      }
      if ($StartPage < 1) { $StartPage = 1; }
      if ($StartPage > 1) {
         $prev = $StartPage - 1;
         $pageprint .= "<a href=\"{$config['phpurl']}/showflat.php?Cat=$Cat&amp;Board=$Board&amp;Number=$pagelinker&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$prev&amp;vc=1\"><<</a> ";
      }
      for ($i=$StartPage; $i<=$EndPage; $i++) {
         if ($i == $fpart) {
            $pageprint .= "$i | ";
         }
         else {
            $pageprint .= "<a href=\"{$config['phpurl']}/showflat.php?Cat=$Cat&amp;Board=$Board&amp;Number=$pagelinker&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$i&amp;vc=1\">$i</a> | ";
         }
      }
      if ($EndPage < $page) {
         $next = $EndPage + 1;
         $pageprint .= "<a href=\"{$config['phpurl']}/showflat.php?Cat=$Cat&amp;Board=$Board&amp;Number=$pagelinker&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$next&amp;vc=1\">>></a> ";
      }
      if ($fpart == "all") {
         $pageprint .= "({$ubbt_lang['SHOW_ALL_F']})";
      } 
      else {
         $pageprint .= "(<a href=\"{$config['phpurl']}/showflat.php?Cat=$Cat&amp;Board=$Board&amp;Number=$pagelinker&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=all&amp;vc=1\">{$ubbt_lang['SHOW_ALL_F']}</a>)";
      }
   }

   if (!$pageprint) {
      $pageprint = "{$ubbt_lang['FLAT_PAGES']} 1";
   }
// -------------------------------------------------
// Only certain options for users that are logged in
   if ($user['U_Username']) {
      $addfavoption = "
<a href=\"{$config['phpurl']}/addfav.php?Cat=$Cat&amp;Board=$Board&amp;main=$current&amp;type=favorite&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;vc=1&amp;fpart=$fpart&amp;what=showflat\">{$ubbt_lang['ADD_FAV']}</a><br />
     ";
   }

// -----------------------------------------------------------------
// We need to know how many posts of this thread to display per page
   if ($fpart <= 1) {
      $Totalgrab = $Totaldisplay;
   }
   else {
      $Startat = $Totaldisplay * ($fpart - 1);
      $Totalgrab = "$Startat, $Totaldisplay";
   }
   $Limit = "LIMIT $Totalgrab";
   if ($fpart == "all") {
      $Limit = "";
   }

   $threadnumber = $Number;

// -----------------------
// Cycle through the posts
   $query = "
      SELECT t1.B_Number,t1.B_Username,t1.B_Posted,t1.B_IP,t1.B_Subject,t1.B_Body,t1.B_File,t1.B_Status,t1.B_Approved,t2.U_Picture,t1.B_Reged,t2.U_Title,t1.B_Sticky,t2.U_Color,t1.B_Icon,t1.B_Poll,t1.B_ParentUser,t1.B_Parent,t2.U_Status,t2.U_Signature,t1.B_LastEdit,t1.B_LastEditBy,t2.U_Location,t2.U_TotalPosts,t2.U_Registered,t2.U_Rating,t2.U_Rates,t2.U_RealRating,t2.U_PicWidth,t2.U_PicHeight,t2.U_Number,t1.B_FileCounter
      FROM  {$config['tbprefix']}Posts AS t1,
            {$config['tbprefix']}Users AS t2 
      WHERE t1.B_Main  = '$current'
      AND   t1.B_PosterId = t2.U_Number
      $Viewable
      ORDER BY B_Number
      $Limit 
   ";
   $sth = $dbh -> do_query($query);
   $totalthread = $dbh -> total_rows($sth);

   for ($i=0; $i <$totalthread; $i++) {

      list ($Number,$Username,$Posted,$IP,$Subject,$Body,$File,$Open,$Approved,$Picture,$Reged,$Title,$Sticky,$Color,$Icon,$Poll,$Parent,$ParentPost,$PostStatus,$Signature,$LastEdit,$LastEditBy,$Location,$TotalPosts,$Registered,$Rating,$Rates,$stars,$picwidth,$picheight,$usernum,$downloads) = $dbh -> fetch_array($sth);

      $rateimage = "";

      if ( ($Reged == 'y') && ($usernum != 1)){
         $postrow[$i]['Registered'] .= $html -> convert_time($Registered,$user['U_TimeOffset']);
			list ($one,$two,$three,$four) = split(" ",$postrow[$i]['Registered']);
			if ($theme['timeformat'] == "long") {
				$regedon = "$one $two $three $four";
			} else {
				$regedon = "$one";
			}
         $postrow[$i]['Registered'] = "{$ubbt_lang['REGED_ON']} $regedon"; 
         $postrow[$i]['TotalPosts'] = "{$ubbt_lang['POSTS_TEXT']}: $TotalPosts";
         if ($Location) {
            $postrow[$i]['Location'] = "{$ubbt_lang['USER_LOC']} $Location";
         }
         if ($Signature) {
            $Signature = str_replace("\n","<br />",$Signature);
            $Signature = "<br /><br />$Signature";
         }
         if ($user['U_ShowSigs'] == "no") {
            $Signature = "";
         }
         $postrow[$i]['Signature'] = $Signature;

      // ---------------------------------------------------------
      // We need to know if this was made by an admin or moderator
         $postrow[$i]['UserStatus'] = "";
         if ($PostStatus == "Administrator") {
            $postrow[$i]['UserStatus'] = "<img src=\"{$config['images']}/adm.gif\" alt=\"{$ubbt_lang['USER_ADMIN']}\" border=\"0\" />";
         }
         elseif ( ($PostStatus == "Moderator") && (stristr($moderatorlist,",$Username,")) ) {
            $postrow[$i]['UserStatus'] = "<img src=\"{$config['images']}/mod.gif\" alt=\"{$ubbt_lang['USER_MOD']}\" border=\"0\" />";
         }   

         if ($stars) {
            for ($x=1;$x<=$stars;$x++) {
               $rateimage .= "<img src=\"{$config['images']}/star.gif\" title= \"$Rates {$ubbt_lang['T_RATES']}\" alt=\"*\" />";
            }
            $postrow[$i]['Rating'] = $rateimage;
         }

         if ( ($Picture) && ($Picture != "http://") && ( ($PicturePosts == 1) || ($PicturePosts == "on") ) ) {
            $picsize = "";
            if ($picwidth && $picheight) {
                $picsize = "width=\"$picwidth\" height=\"$picheight\"";
            }
            else {
               $picsize = "width=\"{$theme['PictureWidth']}\" height=\"{$theme['PictureHeight']}\"";
            }
            $postrow[$i]['Picture'] = "<img src=\"$Picture\" alt=\"\" $picsize />";
         }
         else {
            $Picture = "";
         }

      }

      $PrintLastEdit = "";
      if ($LastEdit) {
         $LastEdit = $html -> convert_time($LastEdit,$user['U_TimeOffset']);
         $PrintLastEdit = "<br /><br /><font class=\"small\"><em>{$ubbt_lang['EDITED_BY']} $LastEditBy ($LastEdit)</em></font>";
      }
      $postrow[$i]['PrintLastEdit'] = $PrintLastEdit;

      $newimage = "";
      if ($Sticky) {
         $time = $html -> convert_time($Sticky,$user['U_TimeOffset']);
      } else {
         $time = $html -> convert_time($Posted,$user['U_TimeOffset']);
      }
      $postrow[$i]['time'] = $time;

      $EUsername = rawurlencode($Username);
      $PUsername = $Username;
      if ($Color) {
         $PUsername = "<font color=\"$Color\">$PUsername</font>";
      }

   // ------------------------------------------------------------------
   // If we came from the search engine then we bold the search keywords
      if ($Search == "true") {
          $searchwords = split(" +",$Words);
          $size = sizeof($searchwords);
          for ($x=0; $x<$size; $x++) {
             if (!$searchwords[$x]) { continue; }
             $Body = @str_replace($searchwords[$x],"<b><i>".$searchwords[$x]."</i></b>",$Body);
             $Body = preg_replace("/(<(a|img)\s*[^>]+)<b><i>($searchwords[$x])<\/i><\/b>([^>]*>)/i","\\1\\3\\4",$Body);
         }
      }
      if ( ($notread[$Number] == "true") && ($Posted > $unread) ) {
         $postrow[$i]['newimage'] = "<img alt=\"{$ubbt_lang['NEW_TEXT']}\" src=\"{$config['images']}/new.gif\" />";
      };

   // ------------------------------------------
   // Set both the reply and edit buttons to off
      $reply = "off";
      $edit  = "off";

   // -------------------
   // Can they post here?
      $gsize = sizeof($Grouparray);
      for ($y=0; $y <$gsize; $y++) {
         if ( (strstr($CanWrite,"-$Grouparray[$y]-") ) || (strstr($CanReply,"-$Grouparray[$y]-") ) ){
            $makepost = "yes";
            break;
         }
      }    

      if ( ($makepost == "yes") && ($Open != "C") ) {
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
      $postrow[$i]['Subject'] = $Subject;

   // ---------------------------------------------------------------
   // If this is a new post then we  need to set $folder to newicons
      if ($postrow[$i]['newimage']) {
         $folder = "newicons";
      } else {
         $folder = "icons";
      }

      if (!$Icon) {
         $Icon = "book.gif";
      }
      $postrow[$i]['folder'] = $folder;
      $postrow[$i]['Icon']   = $Icon;
      $postrow[$i]['folder'] = $folder;
      $postrow[$i]['Icon']   = $Icon;

      // --------------------------------------------------------------
      // If it is an anonymous post, don't give a link to their profile
         if ($Reged == "n") {
            if (!$config['anonnames']) {
               $Username = $ubbt_lang['ANON_TEXT'];
            }
            $Title = $ubbt_lang['UNREGED_USER'];
         }
         else {
            $Username = "<a href=\"{$config['phpurl']}/showprofile.php?Cat=$Cat&amp;User=$EUsername&amp;Number=$Number&amp;Board=$Board&amp;what=showflat&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$fpart&amp;vc=1\">$PUsername</a>";
         }
         $postrow[$i]['Username'] = $Username;
         $postrow[$i]['Title']    = $Title;

         if ( ($config['showip'] == 1) && $IP) {
            $postrow[$i]['IP'] = "($IP)";
         }

         elseif ( ($config['showip'] == 2) && ( ($user['U_Status'] == "Administrator") || ($ismod=='yes') ) && ($IP) ) {
            $postrow[$i]['IP'] = "($IP)";
         }
         elseif ( ($config['showip'] == 3) && ($user['U_Status'] == "Administrator") && ($IP) ) {
            $postrow[$i]['IP'] = "($IP)";
         }
         else {
         }
         if ($File) {
	    		$File = rawurlencode($File);
				if (!$downloads) { $downloads = "0"; }
            $postrow[$i]['filelink'] = "<a href=\"{$config['phpurl']}/download.php?Number=$Number\">{$ubbt_lang['FILE_ATTACH']}</a> ($downloads {$ubbt_lang['DOWNLOADS']})";
         }

         $Parentlink = "";
         if ($Parent) {
             $postrow[$i]['Parentlink'] = " <font class=\"small\">[<a href=\"{$config['phpurl']}/showthreaded.php?Cat=$Cat&amp;Board=$Board&amp;Number=$ParentPost&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;vc=1\" target=\"_new\">re: $Parent</a>]</font>";
         }
         $editlinkstart = "";
         $editlinkstop = "";
         $replylinkstart = "";
         $replylinkstop = "";
         if ($edit == "on") {
            $postrow[$i]['editlinkstart'] = "<a href=\"{$config['phpurl']}/editpost.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;what=showflat&amp;sb=$sb&amp;o=$o&amp;vc=1\">";
            $postrow[$i]['editlinkstop'] = "</a>";
         }
         if ($reply == "on") {
            $postrow[$i]['replylinkstart'] = "<a href=\"{$config['phpurl']}/newreply.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;what=showflat&amp;sb=$sb&amp;o=$o&amp;fpart=$fpart&amp;vc=1\">";
            $postrow[$i]['replylinkstop'] = "</a>";
         }

      // -----------------------------------------------------------------------
      // If there is a poll in this post, we need to give a link to view results
         if ($Poll) {
            $Body = str_replace("</form>","<br /><a href=\"{$config['phpurl']}/viewpoll.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;Board=$Board&amp;what=showflat&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$fpart&amp;vc=1&amp;poll=$Poll\">{$ubbt_lang['VIEW_POLL']}</a></form>",$Body);
            $Body = str_replace("\$ubbt_lang{'SUB_VOTE'}","{$ubbt_lang['SUB_VOTE']}",$Body);
         }    

         $postrow[$i]['Body'] = $Body;

      //  -------------------------------------------------
      // Only certain options for users that are logged in
         $addfavlinkstart = ""; $addfavlinkstop = "";
         $mailpostlink = "";
         $notifylinkstart = "";
         $notifylinkstop = "";
         if ($user['U_Username']) {
            $postrow[$i]['addfavlinkstart'] = "<a href=\"{$config['phpurl']}/addfav.php?Cat=$Cat&amp;Board=$Board&amp;main=$Number&amp;type=reminder&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;vc=1&amp;fpart=$fpart&amp;what=showflat\">";
            $postrow[$i]['addfavlinkstop'] = "</a>";


            if ($config['mailpost']) {
               $postrow[$i]['mailpostlink'] = "
<a href=\"{$config['phpurl']}/mailthread.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;vc=1&amp;fpart=$fpart&amp;what=showflat\"><img alt =\"{$ubbt_lang['EMAIL_POST']}\" src=\"{$config['images']}/sendbyemail.gif\" title=\"{$ubbt_lang['EMAIL_POST']}\" border=\"0\" /></a>
               ";
            }
            $postrow[$i]['notifylinkstart'] = "<a href=\"{$config['phpurl']}/notifymod.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;vc=1&amp;fpart=$fpart&amp;what=showflat\">";
            $postrow[$i]['notifylinkstop'] = "</a>";
         }

      // Now we can show the post
         $postrow[$i]['Number'] = $Number;
   }
   $postrowsize = $i;

   $dbh -> finish_sth($sth);

// finish out the page
	if (!$debug) {
   	include("$thispath/templates/$tempstyle/showflat.tmpl");
	}

// Send the footer
  $html -> send_footer();
?>

