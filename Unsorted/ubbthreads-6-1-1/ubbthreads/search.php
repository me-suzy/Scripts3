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
   require ("languages/${$config['cookieprefix']."w3t_language"}/search.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Groups");                       
   if (!$user['U_Groups']) {
      $user['U_Groups'] = "-4-";
   }

// ------------------------------------------------------------------------
// Let's figure out if they were on a forum, so we can default to searching
// that forum
   $referer = find_environmental ("HTTP_REFERER");
   $piece['0'] = "";
   preg_match("/Board=(.*)/",$referer,$piece);
   $referer = $piece['1'];
   list ($board,$crap) = split("&",$referer);

// ---------------------
// Give them the search page
   $html = new html;
   $html -> send_header($ubbt_lang['TEXT_SEARCH'],$Cat,0,$user);

// ---------------
// Grab the forums

// -----------------------------------------------
// If we have a Cat variable we only search those
   if ($Cat) {
      $pattern = ",";
      $replace = " OR Bo_Cat = ";
      $thiscat = str_replace($pattern,$replace,$Cat);
      $catonly = "AND (Bo_Cat = $thiscat )";
   }

// --------------------------------------------------------------
// We need to format a portion of the SQL query depending on what
// groups this user is in
   $Grouparray = split("-",$user['U_Groups']);
   $gsize = sizeof($Grouparray);
   $g = 0;
   for ($i=0; $i<=$gsize; $i++) {
      if (!preg_match("/[0-9]/",$Grouparray[$i])) { continue; }
      $g++;
      if ($g > 1) {
         $groupquery .= " OR ";
      }
      $groupquery .= "Bo_Read_Perm LIKE '%-$Grouparray[$i]-%'";
   }
  
   $query = "
    SELECT Bo_Title,Bo_Keyword,Bo_Cat,Bo_Sorter,Bo_CatName
    FROM   {$config['tbprefix']}Boards
    WHERE  ($groupquery)
    $catonly
    ORDER BY Bo_Cat,Bo_Sorter
   ";
   $sth = $dbh -> do_query($query);
   
// -----------------------------------
// Assign the forums to the select box
   while (list($Title,$Keyword,$BoCat,$Sorter,$Name) = $dbh -> fetch_array($sth)) {
      $selected = "";
      if ($Keyword == $board) {
         $selected = "selected=\"selected\"";
      }
      if ($initialcat != $Name) {
         $options .= "<option value=\"CatSearch-$BoCat\">*$Name -----</option>";
         $initialcat = $Name;
      } 
      $options .= "<option value=\"$Keyword\" $selected>&nbsp;&nbsp;&nbsp;$Title</option>";
   }
   $dbh -> finish_sth($sth);

	if (!$debug) {
   	include("$thispath/templates/$tempstyle/search.tmpl");
	}
   $html -> send_footer();
