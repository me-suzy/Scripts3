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
   $user = $userob -> authenticate("U_Display,U_Groups,U_PostsPer,U_PicturePosts,U_FlatPosts,U_TempRead,U_TimeOffset");
   $html = new html;

// -----------------------------------------
// require the language file for this script
   require "languages/${$config['cookieprefix']."w3t_language"}/showflat.php";

// -------------------------
// Reassign $main to $Number
   $Number = addslashes($main);
   $folder   = "icons";


// ---------------------------------------------------
// Grab the cookie to mark the posts as read or unread
   $piece['0'] = "";
   preg_match("/-$Board=(.*?)-/",${$config['cookieprefix']."w3t_visit"},$piece);
   $unread = $piece['1'];


// -------------------------------------------------------------
// If we didn't get a board or number then we give them an error
   if (!$Board) {
      $html -> not_right($ubbt_lang['NO_B_INFO'],$Cat);
   }

// ----------------------------------------------
// Set the default of viewing pictures with posts
   $PicturePosts = $user['U_PicturePosts'];
   if (!$PicturePosts) { $PicturePosts  = $theme['PicturePosts']; }

// ------------------------------------------------------
// Let's find out what Groups we are dealing with
   $Board_q = addslashes($Board);
   $Groups = $user['U_Groups'];
   if (!$Groups) {
      $Groups = "-4-";
   }

// --------------------------------------------------------------
// We need to format a SQL query to see what boards this user can
// view
   $Grouparray = split("-",$Groups);
   $gsize = sizeof($Grouparray);
   $groupquery = "AND (";
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


// --------------------------------------------
// Let's find out if they should be here or not 
   $query = "
    SELECT Bo_Title,Bo_Write_Perm,Bo_CatName,Bo_Cat
    FROM   {$config['tbprefix']}Boards
    WHERE  Bo_Keyword = '$Board_q'
    $groupquery
   "; 
   $sth = $dbh -> do_query($query);
   list($title,$CanWrite,$CatName,$CatNumber) = $dbh -> fetch_array($sth);

   if (!$title) {
      $html -> not_right($ubbt_lang['BAD_GROUP'],$Cat);
   }  


// ---------------------------------------------------------------
// If they are a normal user then they can only see approved posts
   $ismod = "no";	// By default they are not a moderator

   $Viewable = "And B_Approved = 'yes'";
   if ($user['U_Status'] == "Administrator") {
      $Viewable = "";
   }
   if ($user['U_Status'] == "Moderator") {
   // ---------------------------------
   // Check if they moderate this board
      $Username_q = addslashes($user['U_Username']);
      $Board_q    = addslashes($Board);
      $query = "
        SELECT Mod_Board
        FROM   {$config['tbprefix']}Moderators
        WHERE  Mod_Username = '$Username_q'
        AND    Mod_Board    = '$Board_q'
      ";
      $sth = $dbh -> do_query($query);
      list($check) = $dbh -> fetch_array($sth);
      if ($check) {
         $Viewable = "";
         $ismod    = "yes";
      } 
   }  

// -----------------------------------------------------------------------------
// Once and a while if people try to just put a number into the url, lets trap it
   if (!$Number) {
      $html -> not_right($ubbt_lang['POST_PROB'],$Cat);
   }

// ------------------------------------------
// Grab the main post number for this thread 
   $extra = "B_Number = '$Number'";
   $query = "
    SELECT B_Main,B_Last_Post,B_Counter,B_Subject,B_Number
    FROM   {$config['tbprefix']}Posts 
    WHERE  B_Number = '$Number'
   ";
   $sth = $dbh -> do_query($query);
   list($current,$posted,$counter,$tsubject) = $dbh -> fetch_array($sth);

// -------------------------------------------------------------
// If we didn't find the main post, then this post doesn't exist
   if (!$current) {
      $html -> not_right("The post you are looking for could not be found",$Cat);
   }

   if ($type == "thread") {
      $extra = "B_Main = $current";
   }

// -------------------------------
// Now cycle through all the posts
   $query = "
    SELECT B_Number,B_Posted,B_Username,B_IP,B_Subject,B_Body,B_File,B_Status,B_Approved,B_Picture,B_Reged,B_UTitle,B_Sticky,B_Color,B_Icon,B_Poll,B_ParentUser,B_Parent,B_UStatus
    FROM  {$config['tbprefix']}Posts 
    WHERE $extra 
    $Viewable 
    ORDER BY B_Number 
   ";
   $sth = $dbh -> do_query($query);
   $totalthread = $dbh -> total_rows($sth);
   for ( $i = 0;$i< $totalthread;$i++){

      list ($Number,$Posted,$Username,$IP,$Subject,$Body,$File,$Open,$Approved,$Picture,$Reged,$Title,$Sticky,$Color,$Icon,$Poll,$Parent,$ParentPost,$PostStatus) = $dbh -> fetch_array($sth);

      $time = $html -> convert_time($Posted,$user['U_TimeOffset']);
      if ($Sticky) {
         $time = $html -> convert_time($Sticky,$user['U_TimeOffset']);
      }

      $EUsername = rawurlencode($Username);
      $PUsername = $Username;
      if ($Color) {
         $PUsername = "<font color=\"$Color\">$PUsername</font>";
      }

   // ---------------------------------------------------------
   // We need to know if this was made by an admin or moderator
      $UserStatus = "";
      if ($PostStatus == "A") {
         $UserStatus = "<img src=\"{$config['images']}/adm.gif\" alt=\"{$ubbt_lang['USER_ADMIN']}\" border=\"0\" />";
      }
      if ($PostStatus == "M") {
         $UserStatus = "<img src=\"{$config['images']}/mod.gif\" alt=\"{$ubbt_lang['USER_MOD']}\" border=\"0\" />";
      }

   // IF it isn't approved we need to mark it
      if ($Approved == "no") {
         $Subject = "({$ubbt_lang['NOT_APPROVED']}) $Subject";
      }

      $folder = "icons";
 
      $showicon = "";
      if (!$Icon) { $Icon ="book.gif"; }


   // ---------------------------------------------------------
   // If its an anonymous post, dont' give a link to the profile
      if($Reged == "n") {

      // -------------------------------------------------------------------
      // If we aren't allowing anonymous users to choose their own username 
      // then we display the basic Anonymous user in the user's selected 
      // language
         if(!$config['anonnames']) { 
            $Username = $ubbt_lang['ANON_TEXT'];
         }
         $Title = $ubbt_lang['UNREGED_USER'];

      } 
      else {
        $Username = "<a href=\"{$config['phpurl']}/showprofile.php?Cat=$Cat&amp;User=$EUsername&amp;Number=$Number&amp;Board=$Board&amp;what=showflat&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$fpart&amp;vc=1\">$PUsername</a>";
      }
      if ( ($config['showip'] == 1) && ($IP) ) {
      }
      elseif ( ($config['showip'] == 2) && ( ($user['U_Status'] == "Administrator") || ($user['U_Status'] == "Moderator") ) && ($IP) ) {
      }
      elseif ( ($config['showip'] == 3) && ($user['U_Status'] == "Administrator") && ($IP) ) {
      }
      else {
         $IP = "";
      }
      $fileurl = "";
      if ($File){
         $fileurl = "<a href=\"{$config['phpurl']}/download.php?Number=$Number\">{$ubbt_lang['FILE_ATTACH']}</a>";
      }
      $picture = "";
      if ( ($Picture) && ($Picture != "http://") && ( ($PicturePosts) || ($PicturePosts == 'on')) ) {
         $picture = "<img src=\"$Picture\" alt=\"\" />";
      }
     

      $postrow[$i]['Username'] = $Username;
      $postrow[$i]['UserStatus'] = $UserStatus;
      $postrow[$i]['Title']      = $Title;
      $postrow[$i]['time']       = $time;
      $postrow[$i]['IP']         = $IP;
      $postrow[$i]['fileurl']    = $fileurl;
      $postrow[$i]['picture']    = $picture;
      $postrow[$i]['folder']     = $folder;
      $postrow[$i]['Icon']       = $Icon;
      $postrow[$i]['Subject']    = $Subject;
      $postrow[$i]['Body']       = $Body;

   }

   $postrowsize = sizeof($postrow);
	if (!$debug) {
   	include("$thispath/templates/$tempstyle/printthread.tmpl");
	}

// ----------------
// Send the footer
   $html -> send_footer();


?>
