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
   require ("languages/${$config['cookieprefix']."w3t_language"}/ubbthreads.php");

// ------------------------------
// Define any necessary variables
   $spacer      = "";
   $showthreads = "";
   $showposts   = "";
   $showna      = "";

   $userob = new user;
   $user = $userob -> authenticate("U_Groups, U_TimeOffset,U_Display");
   $Username = $user['U_Username'];

   $mode = $user['U_Display'];
   if (!$mode) {
      $mode = $theme['postlist'];
   }
   $linker = "show$mode";

// ------------------
// Send a html header
   $html = new html;
   $html -> send_header($config['title'],$Cat,0,$user);

// --------------------------------------------------------------------
// If they are logged in then we check their groups, otherwise they get
// set to the guest group

   $Groups = $user['U_Groups'];
   if (!$Groups) {
     $Groups = "-4-";
   }

// -----------------------------------------------------------------
// If we have a Cat variable then we need to set it up for the query
   if ($Cat) {
     $pattern = ",";
     $replace = " OR Cat_Number = ";
     $catonly = str_replace($pattern,$replace,$Cat);
     $catonly = "WHERE Cat_Number = " . $catonly;
   }

// ------------------------------------
// Are we just looking at one category?
   $catsonly = $user['U_FrontPage'];
   $main     = "ubbthreads";
   if ($config['catsonly']) {
      $main = "categories";
   }
   if (!$catsonly) {
      $catsonly = $main;
   }
   if ( ($C) && ($catsonly == "categories") ) {
      $catonly = "WHERE Cat_Number = '$C'";
   }

// --------------------------------------------------------------------------
// Let's see how many total registered users there are but only if we want to
// display this information
   $sth = $dbh->do_query("SELECT COUNT(*) FROM {$config['tbprefix']}Users WHERE U_Approved='yes'");
   list($registered) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// -------------------------------------------------------------------------
// let's grab the name of the most recently registered user
   $query = "
	SELECT U_Username 
	FROM {$config['tbprefix']}Users 
        WHERE U_Approved='yes'
	ORDER BY U_Number DESC LIMIT 0,1
   ";
   $sth = $dbh -> do_query($query);
   list($newusername) =  $dbh -> fetch_array($sth);
   $encnewusername = rawurlencode($newusername);
   $dbh -> finish_sth($sth); 


// Must define welcome message here for the template since it is different
// if the user isn't logged in.
   if ($user['U_Username']) {
      $welcomemessage = "{$ubbt_lang['INTRO_SUB']} {$user['U_Username']}";
   }
   else {
      $welcomemessage = $ubbt_lang['WEL_PRIV_NO'];
   }

// -----------------------------------------
// Get this user's last visit to each forum
   if ($user['U_Username']) {
      $Username_q = addslashes($Username);
      $query = "
         SELECT L_Board,L_Last
         FROM   {$config['tbprefix']}Last
         WHERE  L_Username = '$Username_q'
      ";
      $sth = $dbh -> do_query($query);
      while(list($Board,$Last) = $dbh -> fetch_array($sth)) {
         $Lastvisit[$Board] = $Last;
      }

   }

// -----------------------
// Grab the total # online
   $date = $html -> get_date();
	$LastOn = $date - 600;
   $query = "
      SELECT O_Type,COUNT(*)
      FROM   {$config['tbprefix']}Online
		WHERE O_Last > '$LastOn'
      GROUP BY O_Type
   ";
   $sth = $dbh -> do_query($query);
   while(list ($type,$count) = $dbh -> fetch_array($sth)) {
      ${$type} = $count;
   }
   if (!$a) { $a = "0"; }
   if (!$r) { $r = "0"; }

// --------------------------
// Get the list of categories 
   $query = "
     SELECT DISTINCT Cat_Title,Cat_Number
     FROM   {$config['tbprefix']}Category
     $catonly
     ORDER BY Cat_Number
   ";
   $categories = $dbh -> do_query($query);
   $j = 0;


 // -------------------------------------------------------------------
 // We need to format a SQL query to see what boards this user can view
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


// ----------------------------
// Cycle through the categories
   $x = 0;
   while ( $row = $dbh -> fetch_array($categories)) {

      $CatNumber = $row['1'];

   // This variable lets us know if there were any boards in this category
   // that were visible to the user.  As soon as we come across a board that
   // they can see, it gets set to "yes"
      $table_done = "no";
      $isboards  = 0;

   // -------------------------------------------
   // Now get the list of boards in this category
      $query = "
        SELECT Bo_Title,Bo_Description,Bo_Keyword,Bo_Total,Bo_Last,Bo_Number,Bo_Moderated,Bo_Read_Perm,Bo_Write_Perm,Bo_Threads,Bo_Sorter,Bo_Poster,Bo_Moderators,Bo_LastMain,Bo_LastNumber
        FROM   {$config['tbprefix']}Boards
        WHERE  Bo_Cat = $CatNumber
        $groupquery
        ORDER BY Bo_Sorter
      ";
      $boards = $dbh -> do_query($query);

   // ---------------------------------------------
   // Now cycle through the boards in this category
      $firstpass = 0;
      $y = 0;
      while ( $boardrows = $dbh -> fetch_array($boards)) {
         list($Title,$Description,$Keyword,$Total,$Last,$Number,$Moderated,$ReadPerm,$WritePerm,$Threads,$Sorter,$Poster,$modlist,$lastmain,$lastnumber) = $boardrows;
         $Board_q = addslashes($Keyword);

      // We have boards in this category
         $isboards = 1;
      // -------------------------------------------------
      // Set the new markers to blank on each pass through
         $shownew = "";
         $shownewt= "";
         $showposts = "";
         $showthreads = "";
			$showna = "";
			$notapproved = "";

         if (!$firstpass) {
            if ($j != 0) { $spacer = "<br />"; }
            $catrow[$x]['spacer'] = $spacer; 
            $j = 1;
            $firstpass = 1;
         }
         $time = "";
         if (!$Threads) { $Threads = 0; }

      // ------------------------------------------------------------------
      // If Last = 0 then this is a new board so set last post to New Board
         if (!$Last) {
            $time = $ubbt_lang['NEW_BOARD'];
         }
         else {
            $time = $html -> convert_time($Last,$user['U_TimeOffset']); // Do a conversion;
         }


      // ---------------------------------------------------------------
      // If they are a normal user then they can only see approved posts
         $Viewable = "AND B_Approved = 'yes'";
         if ($user['U_Status'] == "Administrator") {
            $Viewable = "";
         }
         elseif ($user['U_Status'] == "Moderator") {
            if (preg_match("/(,|^){$user['U_Username']}(,|$)/i",$modlist)) {
               $Viewable="";
            }
         }

         $checker = $Lastvisit[$Keyword];
         if (!$checker) { $checker = 0; }

      // --------------------------
      // Which folder do we display
         $boardfolder = "nonewposts.gif";
         if ( ($checker < $Last) ){
            $boardfolder = "newposts.gif";
         }

      // -------------------------------------
      // Let's see how many NA posts there are
         $notapproved="";
         if ( ($Moderated == "yes") && ($Viewable == "") ) {
            $query = "
              SELECT COUNT(*)
              FROM   {$config['tbprefix']}Posts
              WHERE  B_Approved = 'no'
              AND    B_Board    = '$Board_q'
            ";
            $nap = $dbh -> do_query($query);
            $notapproved = $dbh -> fetch_array($nap);
            $notapproved = $notapproved['0'];
            $dbh -> finish_sth($nap);
			}
        	if ($notapproved) {
           	$showna = " <font class=\"new\">($notapproved {$ubbt_lang['NOT_APPROVED']})</font>";
				$boardfolder = "newposts.gif";
        	}
        	else {
           	$showna = "";
        	}

      // ---------------------------------------------------------------
      // If we are tracking the number of new posts, then we need to do
      // some extra queries.
         if ( ($config['newcounter'] == 2 ) && ($Username) && ($boardfolder == "newposts.gif") ) {
            if (!$checker) { $checker = "0"; }

			// --------------------------------------------------------------
         // Let's see how many new posts there are.  We have to do some
         // tricky AND/OR operations because we don't want sticky posts to
         // be counted as new if they are not
            $query = "
              SELECT COUNT(*), SUM(B_Topic)
              FROM   {$config['tbprefix']}Posts
              WHERE  ( (B_Posted > $checker AND B_Posted <> 4294967295)
              OR (B_Posted = 4294967295 AND B_Sticky > $checker) )
              $Viewable
              AND    B_Board = '$Board_q'
            ";
            $newp1 = $dbh -> do_query($query);
            $newposts1 = $dbh -> fetch_array($newp1);
            $dbh -> finish_sth($newposts1);
            $newthreads = $newposts1[1];
            $newposts = $newposts1[0];



         	if ( ($checker < $Last) && ($user['U_Username']) && ($newthreads) ) {
            	$showthreads = " <font class=\"new\">($newthreads)</font>";
					$boardfolder = "newposts.gif";
         	}
         	if ( ($checker < $Last) && ($user['U_Username']) && ($newposts) ) {
            	$showposts = " <font class=\"new\">($newposts)</font>";
					$boardfolder = "newposts.gif";
         	}

				if (!$newthreads && !$newposts && !$notapproved) {
					$boardfolder = "nonewposts.gif";
				}
         }
			

      // ----------------
      // SHow moderators?
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

      // If we have a last post for this board we link to it
         $lastpost = ""; 
         if ($Poster) {
            $lastpost = "<a href=\"{$config['phpurl']}/$linker.php?Cat=$Cat&amp;Board=$Keyword&amp;Number=$lastnumber&amp;Main=$lastmain#Post$lastnumber\">{$ubbt_lang['TEXT_BY']} $Poster</a>"; 
         }    
         $forum[$x][$y]['boardfolder'] = $boardfolder;
         $forum[$x][$y]['Keyword'] = $Keyword;
         $forum[$x][$y]['Title'] = $Title;
         $forum[$x][$y]['Description'] = $Description; 
         $forum[$x][$y]['Threads'] = $Threads;
         $forum[$x][$y]['showthreads'] = $showthreads;
         $forum[$x][$y]['Total'] = $Total;
         $forum[$x][$y]['showposts'] = $showposts;
         $forum[$x][$y]['showna'] = $showna;
         $forum[$x][$y]['time'] = $time;
         $forum[$x][$y]['lastpost'] = $lastpost;
         $forum[$x][$y]['modlist'] = $modlist;
         $y++;
      }
      $forumsize[$x] = sizeof($forum[$x]);
      $dbh -> finish_sth($boards);

   // If we had boards in this category then we add to the array
      if ($isboards) {
         $catrow[$x]['CatTitle']  = $row['0'];
         $x++;
      }

   }
   $catsize = sizeof($catrow);
   $dbh -> finish_sth($categories);

// ---------------------------
// Show them their time offset
   $date = $html -> get_date();
   $time = $html -> convert_time($date,$user['U_TimeOffset']);

   if ($user['U_Status']) {
      $edittimelinkstart = "<a href=\"{$config['phpurl']}/editdisplay.php?Cat=$Cat#offset\">";
      $edittimelinkstop = "</a>";
   }


	if (!$debug) {
   	include("$thispath/templates/$tempstyle/ubbthreads.tmpl");
	}

// Send the footer
  $html -> send_footer();
?>

