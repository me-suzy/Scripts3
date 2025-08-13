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
   require ("languages/${$config['cookieprefix']."w3t_language"}/dosearch.php");
   require ("imagesizes.php");

// -----------------
// Get the user info
   $userob = new user;
   $html = new html;
   $user = $userob -> authenticate("U_Groups,U_TimeOffset,U_Display");
   $Username = $user['U_Username'];

// --------------------------------------------------
// Figure  out if we link to showflat or showthreaded
   $mode = $user['U_Display'];
   if (!$mode) {
      $mode = $theme['postlist'];
   }
   $linker = "show$mode";

   if ($Searchpage < 0) {
      $html -> not_right($ubbt_lang['NO_MORE'],$Cat);
   }
   if (!$Match) { $Match = "Or"; }
   if (!$Searchpage) { $Searchpage = 0; }
   if (!$Limit) { $Limit = 25;}
   $iconsize = $images['icons'];


// ------------------------------------------------------
// Escape any % signs as these are special SQL characters
   $Words = str_replace("%","\%",$Words);

   $printwords = htmlspecialchars($Words);

// ---------------------------
// URL Encode the Search stuff
   $URLWords = rawurlencode($Words);
   $URLMatch = rawurlencode($Match);
   $URLForum = rawurlencode($Forum);

// ----------------------------------------------------------------
// Calculate the timestamp we are going to be using as our baseline
   $time = $html -> get_date();
   if ($Old == "1day") {
      $time = $time -86400;
   }
   elseif ($Old == "2days") {
      $time = $time - (86400 * 2);
   }
   elseif ($Old == "1week") {
      $time = $time - (86400 * 7);
   }
   elseif ($Old == "2weeks") {
      $time = $time - (86400 * 14);
   }
   elseif ($Old == "3weeks") {
      $time = $time - (86400 * 21);
   }
   elseif ($Old == "1month") {
      $time = $time - (86400 *28);
   }
   elseif ($Old == "3months") {
      $time = $time - (86400 * 74); 
   }
   elseif ($Old == "6months") {
      $time = $time - (86400 * 168);
   }
   elseif ($Old == "1year") {
      $time = $time - (86400 * 336);
   }
   elseif ($Old == "allposts") {
      $time = ""; 
   }

// ---------------
// Send the header 
   $html -> send_header("{$ubbt_lang['SEARCH_RES']}: $printwords",$Cat,0,$user);

// -------------------------------
// Now we need to format our query
   $Forum_q = addslashes($Forum);
   if ( ($Forum != "All_Forums") && (!preg_match("/^CatSearch/",$Forum) ) ){
      $which = " AND B_Board = '$Forum_q'";
   }
   else {
   // ----------------------------------------
   // This get's complicated and quite ugly :)
   // If theya re searching all forums then we only want to get results
   // from forums that they have read access to
      if (!$user['U_Groups']) {
         $user['U_Groups'] = "-4-";
      }

   // --------------------------------------------------------------
   // Let's make sure they are supposed to be looking at this board
      $Grouparray = split("-",$user['U_Groups']);
      $gsize = sizeof($Grouparray);
      $groupquery = "WHERE (";
      $g = 0;
      for ($i=0; $i<=$gsize;$i++) {
         if (!preg_match("/[0-9]/",$Grouparray[$i])) { continue; };
         $g++;
         if ($g > 1) {
            $groupquery .= " OR ";
         }
         $groupquery .= "Bo_Read_Perm LIKE '%-$Grouparray[$i]-%'";
      }
      $groupquery .= ")";  

   // --------------------------------------------------
   // If we are searching a category we need to add that
      $piece['0'] = "";
      if (preg_match("/^CatSearch-(.*)/",$Forum,$piece)) {
        $groupquery .= " AND (Bo_Cat = {$piece['1']})";
      }
      $query2 = "
         SELECT Bo_Keyword
         FROM   {$config['tbprefix']}Boards
         $groupquery
      ";
      $sth = $dbh -> do_query($query2);
      $i = 0;
      while (list($thisboard) = $dbh -> fetch_array($sth)) {
         $thisboard_q = addslashes($thisboard);
         $i++;
         if ($i > 1) { $which .= " OR "; }
         $which .= "(B_Board = '$thisboard_q')";
      }
      $dbh -> finish_sth($sth);
      if ($which) {
         $which = "AND ( $which )";
      }
   }

   if ($Cat) {
      $pattern = ",";
      $replace = " OR Bo_Cat = ";
      $thiscat = str_replace($pattern,$replace,$Cat);
      $catonly = "AND (Bo_Cat = $thiscat )";
   }

   $query ="
      SELECT t1.B_Number,t1.B_Main,t1.B_Username,t1.B_Subject,t1.B_Posted,t1.B_Board,t1.B_Color,t1.B_Sticky,t1.B_Reged,t1.B_Icon,t1.B_UStatus,t2.Bo_Title,t2.Bo_Read_Perm,t2.Bo_Cat,t1.B_Status
      FROM   {$config['tbprefix']}Posts AS t1,
             {$config['tbprefix']}Boards AS t2
      WHERE B_Approved = 'yes'
      AND   t1.B_Board = t2.Bo_Keyword
      $catonly
   ";



   if (($Match == "Or") && ($Words)) {
      $All = split(" +",$Words);
      $size = sizeof($All);;
      for ($i=0; $i < $size; $i++) { 
         $All[$i] = "%".$All[$i]."%"; 
         $All[$i] = addslashes($All[$i]);
         $All[$i] = "'$All[$i]'";
      }
      $Words = join(" OR B_Body Like ",$All);
      $query = $query."AND ( (B_Body Like $Words)";
      $Words = join(" OR B_Subject LIKE ",$All);
      $query = $query."OR (B_Subject LIKE $Words) )";
   }
   elseif (($Match == "And") && ($Words)){
      $All = split(" +",$Words);
      $size = sizeof($All);
      for ($i=0; $i < $size; $i++) { 
         $All[$i] = "%".$All[$i]."%";
         $All[$i] = addslashes($All[$i]);
         $All[$i] = "'$All[$i]'";
      }
      $Words = join(" And B_Body Like ",$All);
      $query = $query."AND ( (B_Body Like $Words)";
      $Words = join(" And B_Subject LIKE ",$All);
      $query = $query."OR (B_Subject LIKE $Words) )";
   }
   elseif ($Match == "Username") {
      $Words_q = addslashes($Words);
      $query = $query."AND B_Username = '$Words_q'";
      $query = $query."\nAND B_Reged = 'y'\n";
   }
   else {
      $WordsLike = "%"."$Words"."%";
      $Words_q = addslashes($WordsLike);
      $query = $query."AND ( (B_Body Like '$Words_q')";
      $query = $query."OR (B_Subject LIKE '$Words_q') )";
   }

   if ($time) {
      $query = $query."\nAND B_Posted > $time\n";
   }
   if (!$Words) {
      $query .= " AND B_Posted < 4294967294\n";
   }
   $query = $query."$which";
   $query = $query."\nORDER BY B_Posted DESC\n";
   $GrabLimit = $Limit +1;
   if ($Searchpage == 0) {
      $Totalgrab = "LIMIT $GrabLimit";
   } else {
      $Startat = $Searchpage * $Limit + $Searchpage;
      $Totalgrab = "LIMIT $Startat,$GrabLimit";      
   }

// Query is dependent upon the limit function
   $startpost = 0;
   $endpage   = 1;
    
   $query = $query. "\n$Totalgrab"; 

// --------------------------------
// Done with query format

// ---------------------
// Now execute the query
   $sth = $dbh -> do_query($query);
   $rows = $dbh -> total_rows($sth);

// ----------------------------------------------
// IF we have some results lets make it look nice
   if($rows){

      $prevpage = $Searchpage - 1;
      if ($prevpage >= 0) {
         $prevlinkstart = "<a href=\"{$config['phpurl']}/dosearch.php?Cat=$Cat&amp;Forum=$URLForum&amp;Words=$URLWords&amp;Match=$URLMatch&amp;Searchpage=$prevpage&amp;Limit=$Limit&amp;Old=$Old\">";
         $prevlinkstop  = "</a>";
      }
      $nextpage = $Searchpage + 1;
      if ($rows > $Limit) {
         $nextlinkstart = "<a href=\"{$config['phpurl']}/dosearch.php?Cat=$Cat&amp;Forum=$URLForum&amp;Words=$URLWords&amp;Match=$URLMatch&amp;Searchpage=$nextpage&amp;Limit=$Limit&amp;Old=$Old\">";
         $nextlinkstop = "</a>";
      }

       $color = "lighttable";
 
   // ---------------------------------
   // Now lets cycle through the results
      $SearchForum = rawurlencode($Forum);
      for ($i=0;$i < $rows ;$i++){
         if ( ($i > 0) && ($i == $Limit * $endpage) ) {
            last;
         }
         list ($Number,$Main,$Username,$Subject,$Posted,$Forum,$Color,$Sticky,$Reged,$Icon,$PostStatus,$Title,$CanRead,$ThisCat,$Locked) = $dbh -> fetch_array($sth); 

      // ---------------------------------------------------------------------- 
      // We need to check and see if they have privileges for this forum.
         if (!$user['U_Groups']) { $user['U_Groups'] = "-4-"; }
         $Grouparray = split("-",$user['U_Groups']);
         $gsize = sizeof($Grouparray);
			$readable = "";
         for ($j=0; $j < $gsize; $j++) { 
				if (!$Grouparray[$j]) { continue; }
            if (strstr($CanRead,"-$Grouparray[$j]-") ) {
               $readable = "yes";
               break;
            }
         }
         if (!$readable) {
            continue; 
         }

         if (!$Icon) { $Icon = "book.gif"; }
			if ($Locked == "C") {
				$Locked = "lock.gif";
			}
         $SearchURLForum = rawurlencode($Forum);
         $searchrow[$i]['Forum'] = $SearchURLForum;
         $searchrow[$i]['Icon'] = $Icon;
         $searchrow[$i]['Number'] = $Number;
         $searchrow[$i]['Main']   = $Main;
         $searchrow[$i]['Subject'] = $Subject;
         $searchrow[$i]['Title']   = $Title;


      // ------------------------------------------------------------------
      // If we are viewing recent messages we only show from the proper cat
         if (!$Words) {
            if ($Cat) {
              $allcats = ",$Cat,";
                if (!strstr($allcats,",$ThisCat,")) {
                  continue;
                }
            }
         }

         $EUsername = rawurlencode($Username);
         $PUsername = $Username;
         if ($Color) {
            $PUsername = "<font color=\"$Color\">$PUsername</font>";
         }
      // ---------------------------------------------------
      // We need to know if this was made by an admin or mod
         $UserStatus = "";
         if ($PostStatus == "A") {
            $UserStatus = "<img src=\"{$config['images']}/adm.gif\" alt=\"{$ubbt_lang['USER_ADMIN']}\" border=0>";
         }
         elseif ($PostStatus == "M") {
            $UserStatus = "<img src=\"{$config['images']}/mod.gif\" alt=\"{$ubbt_lang['USER_MOD']}\" border=0>";
         }

         if ($Reged == "y") {
            $Userlink = "<a href=\"{$config['phpurl']}/showprofile.php?Cat=$Cat&amp;User=$EUsername&amp;what=search&amp;Forum=$SearchForum&amp;Words=$URLWords&amp;Match=$URLMatch&amp;Searchpage=$Searchpage&amp;Limit=$Limit&amp;Old=$Old\">$PUsername</a>";
         }
         else {
            $Userlink = "$PUsername";
         }
         $searchrow[$i]['Userlink'] = $Userlink;
         $searchrow[$i]['Userstatus'] = $Userstatus;

         $Posted = $html -> convert_time($Posted,$user['U_TimeOffset']);
         if ($Sticky) {
            $Posted = $html -> convert_time($Sticky,$user['U_TimeOffset']);
         }
         $searchrow[$i]['Posted'] = $Posted;

      // -------------------
      // alternate the colors
         $searchrow[$i]['color'] = $color;
         $color = $html -> switch_colors($color);
      }
      $resultsize = sizeof($searchrow);
		if (!$debug) {
      	include("$thispath/templates/$tempstyle/dosearch.tmpl");
		}

   // ----------------------------------------
   // We didn't find anything so let them know.
   } 
   else {
      $html -> not_right("{$ubbt_lang['NO_MATCH']}",$Cat,1);
   }
   $dbh -> finish_sth($sth);

   $html -> send_footer();

