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
   require ("languages/${$config['cookieprefix']."w3t_language"}/postlist.php");
   require ("imagesizes.php");

// Define any necessary variables
   $activethread = "";
   $collapsestart = "";
   $collapsestop  = "";
   $d1 = "";
   $d2 = "";
   $w1 = "";
   $w2 = "";
   $w3 = "";
   $m1 = "";
   $m3 = "";
   $m1 = "";
   $m3 = "";
   $m6 = "";
   $y1 = "";
   $SubjectS = "";
   $PosterS  = "";
   $sortview = "";
   $ViewsS   = "";
   $RepliesS = "";
   $RatingS  = "";
   if(empty($sort)) {
      $sort = "";
   }

// ---------------------
// SHOW REPLIES function
   function show_replies($Cat="",$Mother="",$Board="",$indent="",$unread="",$read="",$color="",$page="",$view="",$sb="",$sort="",$Viewable="",$offset="",$C="",$mode="",$o="",$currentkey="") {

      global $config,$dbh,$theme,$images,$ubbt_lang,$tempstyle,$thispath,$repliescode,$tree,$z,$postrow;

      $UserStatus = "";
      $html = new html;

      $indent++;

      $parentkeys = array_keys($tree[$currentkey]);
      for ($x=0; $x<$tree[$currentkey]['children'];$x++) {

         $z++;

         $PNumber = $tree[$currentkey][$parentkeys[$x]]['Number'];
         $Posted  = $tree[$currentkey][$parentkeys[$x]]['Posted'];
         $Username  = $tree[$currentkey][$parentkeys[$x]]['Username'];
         $Subject = $tree[$currentkey][$parentkeys[$x]]['Subject'];
         $Open    = $tree[$currentkey][$parentkeys[$x]]['Status'];
         $Approved= $tree[$currentkey][$parentkeys[$x]]['Approved'];
         $icon    = $tree[$currentkey][$parentkeys[$x]]['Icon'];
         $Color   = $tree[$currentkey][$parentkeys[$x]]['Color'];
         $PostStatus  = $tree[$currentkey][$parentkeys[$x]]['Status'];
			$UserStatus = $tree[$currentkey][$parentkeys[$x]]['UserStatus'];
         $Reged   = $tree[$parentkeys[$x]]['Reged'];
         if (!$icon) { $icon = "book.gif"; }
         $indentsize = $indent * 13;
         $time = $html -> convert_time($Posted,$offset);
         $alt    = ".";
         $thisone = ",$PNumber,";

      // ----------------
      // Folder or icon?
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
         
         if ($Reged == "n") {
      // ------------------------------------------------------------------
      // If we aren't allowing anon users to choose their own username then
      // we display the basic anon user in the user's selected lang
            if (!$config['anonnames']) {
               $Username = $ubbt_lang['ANON_TEXT'];
            }
         }
         else {
            $EUsername = rawurlencode($Username);
            $PUsername = $Username;
            if ($Color) {
               $PUsername = "<font color=\"$Color\">$PUsername</font>";
            }

         // ---------------------------------------------------------
         // We need to know if this was made by an admin or moderator
            if ($UserStatus == "A") {
               $UserStatus = "<img src=\"{$config['images']}/adm.gif\" alt=\"{$ubbt_lang['USER_ADMIN']}\" border=\"0\" />";
            }
            elseif ($UserStatus == "M") {
               $UserStatus = "<img src=\"{$config['images']}/mod.gif\" alt=\"{$ubbt_lang['USER_MOD']}\" border=\"0\" />";
            }   
            else {
               $UserStatus = "";
            }
            $Username = "<a href=\"{$config['phpurl']}/showprofile.php?Cat=$Cat&amp;User=$EUsername&amp;Board=$Board&amp;what=ubbthreads&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">$PUsername</a>";
         }

      // Setup the postrow array
         $postrow[$z]['color'] = $color;
         $postrow[$z]['indentsize'] = $indentsize;
         $postrow[$z]['folder']     = $folder;
         $postrow[$z]['icon']       = $icon;
         $postrow[$z]['imagesize']  = $imagesize;
         $postrow[$z]['Number']     = $PNumber;
         $postrow[$z]['Subject']    = $Subject;
         $postrow[$z]['Username']   = $Username;
         $postrow[$z]['UserStatus'] = $UserStatus;
         $postrow[$z]['Views']      = "&nbsp;";
         $postrow[$z]['Replies']    = "&nbsp;";
         $postrow[$z]['time']       = $time;
         $postrow[$z]['pageprint']  = "";
      // --------------------
      // alternate the colors
         $color = $html -> switch_colors($color);

         if (isset($tree[$PNumber]['children'])) {
            $color = show_replies($Cat,$PNumber,$Board,$indent,$unread,$read,$color,$page,$view,$sb,$sort,$Viewable,$offset,$C,$mode,$o,$PNumber);
         }

      }

   $indent--;
   return $color;

  }

// --------------------------------
// END OF THE SHOW_REPLIES FUNCTION



// -----------------
// Get the user info
   $html = new html;
   $userob = new user;
   $user = $userob -> authenticate("U_Display, U_Groups, U_Sort, U_View, U_PostsPer, U_TempRead, U_FlatPosts, U_TimeOffset,U_ActiveThread");
   $Username = $user['U_Username'];

// --------------------------------
// Get some default display options
   if (!$user['U_FlatPosts']) {
      $user['U_FlatPosts'] = $theme['flatposts'];
   }

// Postrow array key holder
   $z=0;

// --------------------------------------
// Do we link to showthreaded or showflat
   $mode = $user['U_Display'];
   if (!$mode) {
      $mode = $theme['postlist'];
   }
   $mode = "show$mode";

// ----------------------------------------------------------------
// If they are logged in then we check their groups, otherwise they
// get set to the guest group
   $Groups = $user['U_Groups'];
   if (!$Groups) {
      $Groups = "-4-";
   }

// --------------------------
// Set the default sort order
   if ( (empty($sb)) && ($user['U_Sort']) ) {
      $sb = $user['U_Sort'];
   }
   elseif( (empty($sb)) && (!$user['U_Sort']) ) {
      $sb = $theme['sort'];
   }

// -------------------------------------------------------
// If $view isn't set then it gets set to the default view
   if ( (empty($view)) && ($user['U_View']) ) {
      $view = $user['U_View'];
   }
   elseif ( (empty($view)) && (!$user['U_View']) ) {
      $view = $theme['threaded'];
   }

// ----------------------------------------
// If $page isn't set then it defaults to 0
   if (empty($page)) {
      $page = 0;
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

// -------------------
// Grab the board info
   $Board_q = addslashes($Board);
   $query = "
      SELECT Bo_Title,Bo_Last,Bo_Read_Perm,Bo_Write_Perm,Bo_Moderated,Bo_Cat,Bo_CatName,Bo_ThreadAge,Bo_Moderators,Bo_Reply_Perm,Bo_HTML,Bo_Markup,Bo_SpecialHeader,Bo_StyleSheet
      FROM   {$config['tbprefix']}Boards
      WHERE  Bo_Keyword = '$Board_q'
      $groupquery
   ";
   $sth = $dbh -> do_query($query);
   $rows = $dbh -> fetch_array($sth);
   list($Title,$Last,$CanRead,$CanWrite,$Moderated,$C,$CatName,$ThreadAge,$modlist,$CanReply,$HTML,$Markup,$fheader,$fstyle) = $rows;
   $dbh -> finish_sth($rows);
   if (!$Title) {
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


// ---------------------
// Give them the jump box
   $jumpbox = $html -> jump_box($Cat,$groupquery,$Board);

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


// ---------------------------------------
// How old of threads should we be showing
   if (!isset($o)) {
      $o = $user['U_ActiveThread'];
      if (!$o) { $o = $ThreadAge; }
      if ($o === "999") {
         $o = $ThreadAge;
      }
   }
   if ( (!empty($o)) && ($o != "all") ) {
      $time = $html -> get_date();
      $time = $time - ($o * 86400);
      $activethread = "AND t1.B_Last_Post > $time";
   }
   if (empty($o)) {
      $o = "";
   }
  if ($o == "1") {
    $d1 = "selected=\"selected\"";
  } elseif ($o == 2) {
    $d2 = "selected=\"selected\"";
  } elseif ($o == 7) {
    $w1 = "selected=\"selected\"";
  } elseif ($o == 14) {
    $w2 = "selected=\"selected\"";
  } elseif ($o == 21) {
    $w3 = "selected=\"selected\"";
  } elseif ($o == 31) {
    $m1 = "selected=\"selected\"";
  } elseif ($o == 93) {
    $m3 = "selected=\"selected\"";
  } elseif ($o == 186) {
    $m6 = "selected=\"selected\"";
  } elseif ($o == 365) {
    $y1 = "selected=\"selected\"";
  } else {
    $allofthem = "selected=\"selected\"";
  }


// ------------------------------------------------------------
// Set up some stuff so we know what the last post they saw was
// and update this to the current last post of the board 
   $Username_q = addslashes($Username);
   $LastViewed = "$Board"."Last";
   if(empty(${$config['cookieprefix']."w3t_visit"})) {
      ${$config['cookieprefix']."w3t_visit"} = "";
   }
   if ( ($Username) && (!strstr(${$config['cookieprefix']."w3t_visit"},"-$Board=") ) ) {
      $currtime = $html -> get_date();
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
// Only update the database if they are logged in
   if ($Username) {
      $currtime = $html -> get_date();
      $query = "
         DELETE FROM {$config['tbprefix']}Last
         WHERE L_Username = '$Username_q'
         AND   L_Board    = '$Board_q'
      ";
      $dbh -> do_query($query);
      $query = "
        INSERT INTO {$config['tbprefix']}Last
        (L_Username,L_Last,L_Board)
        VALUES
        ('$Username_q',$currtime,'$Board_q')
      ";
      $dbh -> do_query($query);
   }

// -------------------------------------------------------------------
// If we don't already have the time that they last visited this forum
// then we need to get it out of the  cookie or session    
   if (empty($unread)) {
      $script['0'] = "";
      preg_match ("/-$Board=(.*?)-/",${$config['cookieprefix']."w3t_visit"},$script);
      if (isset($script['1'])) {
         $unread = $script['1'];
      }
   }
   $read = $user['U_TempRead'];

// -------------------------------------------------------------------
// If this is a non logged in user we pad the field so it doesn't show
// them all as new
   if (!$unread) {
      $unread = "4294967295";
   }

// ------------------
// Send a html header
   $html -> send_header($config['title'],$Cat,0,$user,$Board,$CanRead);

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
   while (list($Type,$Extra,$count) = $dbh -> fetch_array($sth)) {
      ${$Type} = $count;
   }       

// -------------------------
// Set up some sorting stuff
   if ($sb == 1) {
      $SubjectS = "<img src=\"{$config['images']}/descend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }
   elseif ($sb == 2) {
      $SubjectS = "<img src=\"{$config['images']}/ascend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }
   elseif ($sb == 3) {
      $PosterS = "<img src=\"{$config['images']}/descend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }
   elseif ($sb == 4) {
      $PosterS = "<img src=\"{$config['images']}/ascend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }
   elseif ($sb == 5) {
      $LastS = "<img src=\"{$config['images']}/descend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }
   elseif ($sb == 6) {
      $LastS = "<img src=\"{$config['images']}/ascend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }
   elseif ($sb == 7) {
      $ViewsS = "<img src=\"{$config['images']}/descend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }
   elseif ($sb == 8) {
      $ViewsS = "<img src=\"{$config['images']}/ascend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }
   elseif ($sb == 9) {
      $RepliesS = "<img src=\"{$config['images']}/descend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }
   elseif ($sb == 10) {
      $RepliesS = "<img src=\"{$config['images']}/ascend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }
	elseif ($sb == 11) {
		$RatingS = "<img src=\"{$config['images']}/descend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
	}
   elseif ($sb == 12) {
      $RatingS = "<img src=\"{$config['images']}/ascend.gif\" {$images['descend']} border=\"0\" alt=\"\" />";
   }


// ---------------------------------------------------------------------
// We need to check and see if they have write privileges for this forum
   $gsize = sizeof($Grouparray);
   for ($i=0; $i <$gsize; $i++) {
      if (strstr($CanWrite,"-$Grouparray[$i]-") ) {
         $makepost = "yes";
         break;
      }
   }
   if ($makepost == "yes") {
      $newpoststart = "<a href=\"{$config['phpurl']}/newpost.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
      $postoption = "newpost.gif";
      $newpoststop = "</a>";
   }
   else {
      $postoption = "greynewpost.gif";
   }

// ---------------------
// Set some sorting opts
   $sortsubject = 1;
   $sortposter  = 3;
   $sortdate    = 6;
   $sortviews   = 7;
   $sortreplies = 9;
   $sortrating  = 11;

// -----------------------------------------------
// Make sure we are giving the proper sort options
   if ($sb == 1) {
      $sortsubject = 2;
   } else {
      $sortsubject = 1;
   }
   if ($sb == 3) {
      $sortposter = 4;
   } else {
      $sortposter = 3;
   }
   if ($sb == 5) {
      $sortdate = 6;
   } else {
      $sortdate = 5;
   }
   if ($sb == 7) {
      $sortviews = 8;
   } else {
      $sortviews = 7;
   }
   if ($sb == 9) {
      $sortreplies = 10;
   } else {
      $sortreplies = 9;
   }
	if ($sb == 11) {
		$sortrating = 12;
	} else {
		$sortrating = 11;
	}


// --------------------------------------------------------------------
// If dateslip is on, we sort by Last_Post, otherwise we sort by Posted
   if (!$config['dateslip']) {
      $sort_opt = array(
         1 => 't1.B_Subject DESC',
         2 => 't1.B_Subject ASC',
         3 => 't1.B_Username DESC',
         4 => 't1.B_Username ASC',
         5 => 't1.B_Posted DESC',
         6 => 't1.B_Posted ASC',
         7 => 't1.B_Counter DESC',
         8 => 't1.B_Counter ASC',
         9 => 't1.B_Replies DESC',
        10 => 't1.B_Replies ASC',
		  11 => 't1.B_RealRating DESC',
		  12 => 't1.B_RealRating ASC'
      );                    
      $sort_by = $sort_opt[$sb];
   }
   else {
      $sort_opt = array(
         1 => 't1.B_Subject DESC',
         2 => 't1.B_Subject ASC',
         3 => 't1.B_Username DESC',
         4 => 't1.B_Username ASC',
         5 => 't1.B_Last_Post DESC',
         6 => 't1.B_Last_Post ASC',
         7 => 't1.B_Counter DESC',
         8 => 't1.B_Counter ASC',
         9 => 't1.B_Replies DESC',
        10 => 't1.B_Replies ASC',
		  11 => 't1.B_RealRating DESC',
		  12 => 't1.B_RealRating ASC'
      );   
      $sort_by = $sort_opt[$sb];
   }

// -----------------------------------------
// Find out how many posts to show per  page
   $PostsPer = $user['U_PostsPer'];
   if (!$PostsPer) {
      $PostsPer = $theme['postsperpage'];
   }

// ---------------------------------------------------------------------
// Now we calculate which posts to grab for this page.  We want to grab
// one from the previous page and one from the next page so we know what
// the previous and nexts posts will be
   if (!($page > 0)) {
      $Totalgrab = $PostsPer + 1;
      $Posts     = $PostsPer + 1;
   }
   else {
      $Startat   = $page * $PostsPer;
      $Posts     = $PostsPer + 1;
      $Totalgrab = "$Startat, $Posts";
   }

// ---------------------------------------------------------------
// If they are a normal user then they can only see approved posts
   $t1Viewable = "AND t1.B_Approved = 'yes'";
   $Viewable = "AND B_Approved = 'yes'";
   if ($user['U_Status'] == "Administrator") {
      $Viewable = "";
      $t1Viewable = "";
   }
   if ($user['U_Status'] == "Moderator") {
      if (preg_match("/(,|^)$Username(,|$)/i",$modlist)) {
         $Viewable = "";
         $t1Viewable = "";
      }
   }

// --------------------
// Setup the limit call
   $endpage = 1;
   $limit = "LIMIT $Totalgrab";
   $query = "
      SELECT t1.B_Number,t1.B_Parent,t1.B_Username,t1.B_Posted,t1.B_Last_Post,t1.B_Subject,t1.B_Main,t1.B_Status,t1.B_Approved,t1.B_Icon,t1.B_Reged,t1.B_Counter,t1.B_Sticky,t1.B_Replies,t1.B_Rating,t1.B_Rates,t1.B_RealRating,t2.U_Color,t2.U_Status,t1.B_PosterId,t1.B_File
      FROM   {$config['tbprefix']}Posts AS t1,
             {$config['tbprefix']}Users AS t2
      WHERE  t1.B_Topic = 1
      AND    t1.B_Board  = '$Board_q'
      AND    t1.B_PosterId = t2.U_Number
      $activethread
      $t1Viewable
      ORDER BY $sort_by
      $limit
   ";
   $sth = $dbh -> do_query($query);
   $nums = $dbh -> total_rows($sth);
// -------------------------------------------------------------------
// If $page is greater than 0 then we need a link to the previous page
   if ($page > 0) {
      $prev = $page - 1;
      $prevstart = "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$prev&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
      $prevoption = "previous.gif";
      $prevstop = "</a>";
   }
   else {
      $prevoption = "greyprevious.gif";
   }

// ----------------------------------------------------------------------
// If total parent posts is greater than $PostsPer*Page then we give them
// a link to the next page
   if ($nums >= ($PostsPer + 1) ) {
      $next = $page + 1;
      $nextstart = "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$next&amp;view=$view&amp;sb=$sb&amp;o=$o\">";
      $nextstop = "</a>";
      $nextoption = "next.gif";
   }
   else {
      $nextoption = "greynext.gif";
   }

// -------------------------------------------------------------
// If $postlist is threaded then we need to give them the proper
// button to expand or collapse the threads
   $expandstart = "";
   $expandstop  = "";
   if ($view == "collapsed") {
      $expandstart = "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=expanded&amp;sb=$sb&amp;o=$o\">";
      $expandstop = "</a>";
      $expandoption = "expand.gif";
      $collapseoption = "greycollapse.gif";
   }
   else {
      $collapsestart = "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=collapsed&amp;sb=$sb&amp;o=$o\">";
      $collapsestop = "</a>";
      $collapseoption = "collapse.gif";
      $expandoption = "greyexpand.gif";
   }

   if ( ($config['dateslip']) && ($view != "expanded") ){
      $datecolumn = $ubbt_lang['LAST_POST'];
   }
   else {
      $datecolumn = $ubbt_lang['POSTON_TEXT'];
   } 

// ----------------------------
// List the posts for this page
   $color = "lighttable";
   for ($i=0; $i<$nums;$i++) {

   // -------------------------------------------------------------
   // We need to break out of here if we are over $PostPer, we just
   // needed to know if there was  a next page or not   
      if ($i == ($PostsPer * $endpage) ) { break; }

      list($Number,$Parent,$Username,$Posted,$Last_Post,$Subject,$Main,$Open,$Approved,$icon,$Reged,$Counter,$Sticky,$Replies,$Rating,$Rates,$stars,$Color,$PostStatus,$posterid,$file) = $dbh -> fetch_array($sth);

   // -------------------------
   // Standard icon is the note
      if (!$icon) {
         $icon = "book.gif";
      }

   // ----------------------------------------------------------
   // If this post is sticky then we need to use the sticky date
      if ($Sticky) {
         $time = $html -> convert_time($Sticky,$user['U_TimeOffset']);
			$Subject = "<img src=\"{$config['images']}/sticky.gif\" alt=\"\" border=\"0\" $imagestyle />$Subject";
      } else {
         if ($view != "expanded") {
            $time = $html -> convert_time($Last_Post,$user['U_TimeOffset']);
         }
         else {
            $time = $html -> convert_time($Posted,$user['U_TimeOffset']);
         }
      }

	//	-----------------------------------------------------------
	// If there is a file attachment we mark it with the disk icon
		if ($file) {
			$Subject = "<img src=\"{$config['images']}/file.gif\" alt=\"\" border=\"0\" $imagestyle />$Subject";
		}
      $thisone = ",$Number,";
      $alt     = ".";
      $newthread = "";

      $imagesize = $images['icons'];
      if ($Sticky) {
         if ($Sticky <= $unread) {
            $folder = "icons";
            $alt    = ".";
         }
         else {
            $folder = "newicons";
            $alt    = "*";
            $newthread = 1;
         }
      }
      else {
         if ( ($Posted > $unread) && ( !strstr($read,$thisone) ) ) {
            $folder = "newicons";
            $alt    = "*";
            $newthread = 1;
         }
         else {
            $folder = "icons";
            $alt    = ".";
         }
      }
      if ($Open == "C") {
        $icon = "lock.gif";
      }       

   // -------------------------------------------------------------------
   // If we are doing flat posts or the view is collapsed we need to list
   // the number of replies
   // We also need to do this before printing out the initial folder/icon
   // color so we can let the user know there are new messages in the thread
      $printreplies = "";
      $pagejump = 1;
      $postmarker = "";
      $partnumber = "";

   // ------------------------------------------------------------------
   // We only want to show the number of new replies if it is configured
   // in the config file to do the extra queries needed
      $isset = 0;
      $newreplies = 0; 
      $replies = $Replies;
      $printreplies = "";
      $new="";
      $postrow[$i]['fpart'] = 1;
      if ( ($config['newcounter']) && ($Last_Post > $unread) ) {
         $query = "
            SELECT t1.B_Number,t1.B_Parent,t1.B_Posted
            FROM   {$config['tbprefix']}Posts AS t1
            WHERE  t1.B_Main = $Number
            $t1Viewable
            AND    t1.B_Number <> $Number
            ORDER BY t1.B_Posted
         ";
         $sti = $dbh -> do_query($query);
         $replies = $dbh -> total_rows($sti);
         $new = "no";

         $cycle = 0;
         for ($j=0; $j<$replies;$j++) {
            $cycle++;
            if ($cycle == $user['U_FlatPosts']) {
                $pagejump++;
                $cycle = 0;
            }
            list($No,$Pa,$Po) = $dbh -> fetch_array($sti);
            $checkthis = ",$No,";
            if ( ($Po > $unread) && ( !stristr($read,$checkthis) ) ){
               $new = "yes";
               $newreplies++;
               if (!$isset) {
                 if (!$newthread) {
                    $postrow[$i]['jumper'] = "#Post$No";
                    $postrow[$i]['fpart'] = $pagejump;
                    $isset = 1;
                 }
               }
            }
         }
         $dbh -> finish_sth($sti); 
         if ($replies < 1) { $replies = 0; }
     } 
     $printreplies = "$replies";
     if ($new == "yes") {
        $printreplies .= "<font class=\"new\"> <i>($newreplies)</i></font>";
     }
     if ( ($new == "yes") || ( ($Last_Post > $unread) && (!$config['newcounter']) ) ) { 
        if ($folder == "icons") {
            $folder = "newicons";
            $alt  = "*";
        }
     }
     else {
         $printreplies .= "&nbsp;&nbsp;&nbsp;";
     }
       
   // ------------------------------------------------------
   // If the post isn't approved we show it in the new color
      if ($Approved == "no") {
         $Subject = "({$ubbt_lang['NOT_APPROVED']}) $Subject";
      }

   // ----------------------------------------------------------------
   // If we are going to be viewing in flat mode, let's show all pages
      $pageprint = "";
      if ( ($mode == "showflat") && ($Replies >= $user['U_FlatPosts']) ) {
         $pages = $Replies;
         if ($Replies <= $user['U_FlatPosts']) { $pages= $pages + 1; }
         if ($user['U_FlatPosts']) {
            $pages = ($pages) / $user['U_FlatPosts'];
         }
         $pageprint = " <font class=\"small\">( {$ubbt_lang['PAGE_TEXT']} ";
         for ($prints = 1; $prints <= ($pages+1); $prints++) {
            $pageprint .= "<a href=\"{$config['phpurl']}/showflat.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$prints\">$prints</a> ";
         }
         $pageprint .= "<a href=\"{$config['phpurl']}/showflat.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=all\">{$ubbt_lang['TEXT_ALL']}</a> )</font>";
      }

      if ($Reged == "n") {
   // ------------------------------------------------------------------
   // If we aren't allowing anon users to choose their own username then
   // we display the basic anon user in the user's selected lang
         if (!$config['anonnames']) {
            $Username = $ubbt_lang['ANON_TEXT'];
         }
         $UserStatus = "";
      }
      else {
         $PUsername = $Username;
         $EUsername = rawurlencode($Username);
         if ($Color) {
            $PUsername = "<font color=\"$Color\">$PUsername</font>";
         }
      // ---------------------------------------------------------
      // We need to know if this was made by an admin or moderator
         $UserStatus = "";
         if ($PostStatus == "Administrator") {
            $UserStatus = "<img src=\"{$config['images']}/adm.gif\" alt=\"{$ubbt_lang['USER_ADMIN']}\" border=\"0\" />";
         }
         elseif (($PostStatus == "Moderator") && (stristr($modlist,",$Username,"))) {
            $UserStatus = "<img src=\"{$config['images']}/mod.gif\" alt=\"{$ubbt_lang['USER_MOD']}\" border=\"0\" />";
         }
         else {
            $UserStatus = "";
         } 
         $Username = "<a href=\"{$config['phpurl']}/showprofile.php?Cat=$Cat&amp;User=$EUsername&amp;Board=$Board&amp;what=ubbthreads&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">$PUsername</a>";
      }

     $ThreadRating = "&nbsp;";
     if ($stars) {
        $ThreadRating = "";
        for ($x=1;$x<=$stars;$x++) {
           $ThreadRating .= "<img src=\"{$config['images']}/star.gif\" title= \"$Rates {$ubbt_lang['T_RATES']}\" alt=\"*\" />";
        }
     }
     if (!$ThreadRating) { $ThreadRating = "&nbsp;"; }

   // Setup the postrow array
      $postrow[$z]['color'] = $color;
      $postrow[$z]['indentsize'] = "0";
      $postrow[$z]['folder']     = $folder;
      $postrow[$z]['icon']       = $icon;
      $postrow[$z]['imagesize']  = $imagesize;
      $postrow[$z]['Number']     = $Number;
      $postrow[$z]['Subject']    = $Subject;
      $postrow[$z]['Username']   = $Username;
      $postrow[$z]['UserStatus'] = $UserStatus;
      $postrow[$z]['Views']      = $Counter;
      $postrow[$z]['Replies']    = $printreplies;
      $postrow[$z]['time']       = $time;
      $postrow[$z]['pageprint']  = $pageprint;
      $postrow[$z]['Rating']     = $ThreadRating;
		$postrow[$z]['posterid']   = $posterid;

   // --------------------
   // alternate the colors
      $color = $html -> switch_colors($color);


   // ---------------------------------------------------------------
   // If we are not doing flat posts and the view is expanded then we
   // will list all the replies out in a threaded format
      $tree = array();
      if ($view == "expanded") {
         $query = "
           SELECT B_Number,B_Parent,B_Posted,B_Username,B_Subject,B_Status,B_Approved,B_Icon,B_Reged,B_Color,B_UStatus
           FROM  {$config['tbprefix']}Posts
           WHERE B_Main = $Number
           AND   B_Number <> $Number
           $Viewable
           ORDER BY B_Number Desc 
         ";
         $stj = $dbh -> do_query($query);
         $results = 0;
         while(list($anumber,$aparent,$aposted,$ausername,$asubject,$astatus,$aapproved,$aicon,$areged,$acolor,$austatus) = $dbh -> fetch_array($stj)) {
            if ($aparent == 0) {
               $aparent = $Number;
            }
            $results = 1;
            $tree[$aparent][$anumber]['Posted'] = $aposted;
            $tree[$aparent][$anumber]['Username'] = $ausername;
            $tree[$aparent][$anumber]['Subject'] = $asubject;
            $tree[$aparent][$anumber]['Approved'] = $aapproved;
            $tree[$aparent][$anumber]['Icon'] = $aicon;
            $tree[$aparent][$anumber]['Reged'] = $areged;
            $tree[$aparent][$anumber]['Color'] = $acolor;
            $tree[$aparent][$anumber]['UserStatus'] = $austatus;
            $tree[$aparent][$anumber]['Number'] = $anumber;
         }

         if ($results) {
         // Find out the number of replies to each parent
            $parentkeys = array_keys($tree);
            $parentsize = sizeof($parentkeys);
   
            for($x=0;$x<$parentsize;$x++) {
               $childkeys = array_keys($tree[$parentkeys[$x]]);
               $childsize = sizeof($childkeys);
               $tree[$parentkeys[$x]]['children'] = $childsize;
            }
            $color = show_replies($Cat,$Number,$Board,0,$unread,$read,$color,$page,$view,$sb,$sort,$Viewable,$user['U_TimeOffset'],$C,$mode,$o,$Number);
         }
      }
     $z++;
  }
  $dbh -> finish_sth($sth);

// -------------------------------------------
// Here is where we let them jump around pages
   $query = "
      SELECT COUNT(*)
      FROM   {$config['tbprefix']}Posts AS t1
      WHERE  t1.B_Topic = 1
      $activethread
      AND    t1.B_Board  = '$Board_q'
      $t1Viewable
   ";
   $sth = $dbh -> do_query($query);
   $rows = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   $Threads = $rows['0'];
 
   $TotalPages = $Threads / $PostsPer ;

   list($TotalP,$Leftover) = split("\.",$TotalPages);   
   if ($Leftover > 0) { $TotalP++; }
   if ($TotalP > 1) { $pagejumpers = "<font class=\"onbody\">{$ubbt_lang['PAGE_TEXT']}: "; }

   $Startpage = $page - 10;
   $Endpage   = $page + 10;

   if ($Startpage < 0) {
      $Endpage = $Endpage - $Startpage;
      $Startpage = 0;
   }
   if ($Endpage > $TotalP) {
      $Endpage = $TotalP;
      $Startpage = $Endpage - 20;
   }
   if ($Startpage < 0) { $Startpage = 0; }
   if ($Startpage > 0) {
      $prev = $page - 1;
      $pagejumpers .= "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$prev&amp;view=$view&amp;sb=$sb&amp;o=$o\"><<</a> ";
   }
   if ($Threads > $PostsPer) {
      for ($i = $Startpage; $i < $Endpage; $i++) {
         $printedpage = $i + 1;
         if ($i == $page) {
            $pagejumpers .= "$printedpage ";
         } else {
            $pagejumpers .= "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$i&amp;view=$view&amp;sb=$sb&amp;o=$o\">$printedpage</a> ";
         }
      }
   }

   if ($Endpage < $TotalP) {
      $next = $page + 1;
      $pagejumpers .= "<a href=\"{$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$next&amp;view=$view&amp;sb=$sb&amp;o=$o\">>></a> ";
   }
   if ($TotalP > 1) {
      $pagejumpers .= "</font>";
   }


// Only give the favorite board and subscribe links to users that have
// logged in
   if ($user['U_Username']) {
      $favoriteboardlink = "<a href=\"{$config['phpurl']}/addfavforum.php?Cat=$Cat&amp;Board=$Board\">{$ubbt_lang['FORUM_FAV']}</a><br />";
      if ($config['subscriptions']) {
        $subscribeboardlink = "<a href=\"{$config['phpurl']}/togglesub.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\">{$ubbt_lang['FORUM_SUB']}</a><br />";
      }
   }

   $postrowsize = sizeof($postrow);

// Moderators
         $modarray = split(",",$modlist);
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


	if (!$debug) {
   	include ("$thispath/templates/$tempstyle/postlist.tmpl"); 
	}

// Send the footer
     $html -> send_footer();

?>
