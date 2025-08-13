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
   $user = $userob -> authenticate("U_TimeOffset,U_FlatPosts,U_Display,U_TempRead");
   $Username = $user['U_Username'];
   $html = new html;

// -----------------------------------------
// require the language file for this script
   require "languages/${$config['cookieprefix']."w3t_language"}/viewfavorites.php";

   $display     = $user['U_Display'];
   $flatposts   = $user['U_FlatPost'];
   $read        = $user['U_TempRead'];
   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// --------------------------------------------------
// Get ready to grab the selected type of threads 
   $html -> send_header("$header",$Cat,0,$user);
   $username_q = addslashes($Username);

   $Viewable = "AND {$config['tbprefix']}Posts.B_Approved='yes'";
   if ( ($user['U_Status'] == "Administrator") || ($user['U_Status'] == "Moderator") ) {
      $Viewable = "";
   }

   $query = "
    SELECT {$config['tbprefix']}Posts.B_Last_Post,{$config['tbprefix']}Favorites.F_Thread,{$config['tbprefix']}Posts.B_Subject,{$config['tbprefix']}Favorites.F_Number,{$config['tbprefix']}Posts.B_Board,{$config['tbprefix']}Favorites.F_LastPost,{$config['tbprefix']}Posts.B_Username
    FROM   {$config['tbprefix']}Posts,{$config['tbprefix']}Favorites
    WHERE  {$config['tbprefix']}Favorites.F_Owner = '$username_q'
    AND    {$config['tbprefix']}Favorites.F_Type  = 'f'
    AND    {$config['tbprefix']}Favorites.F_Thread = {$config['tbprefix']}Posts.B_Number
    ORDER BY {$config['tbprefix']}Posts.B_Last_Post
   ";
   $sth = $dbh -> do_query($query);
   $any=0;


   $counter=0;
   $i=0;
   while ( list($RealLast,$Thread,$Subject,$Number,$Board,$LastPost,$poster) = $dbh -> fetch_array($sth)) {

      $newmarker = "";
      $pagejump = 1;
      $postmarker = "";
      $partnumber = "";
      $piece['0']   = "";
      preg_match("/-$Board=(.*?)-/","{$config['cookieprefix']}w3t_visit",$piece);
      $unread = $piece['1'];
      list($unread,$visit) = split("-",$unread);
      if (!$unread) { $unread = $last{$Board}; }

      if ( ($RealLast > $LastPost) && ($RealLast > $unread) ) {
      // ------------------------------------------------------------
      // If we don't have a last visit from their cookie then we need
      // to grab their last visit to this board
         if (!$unread) {
            $Board_q = addslashes($Board);
            $query = "
              SELECT L_Last
              FROM   {$config['tbprefix']}Last
              WHERE  L_Username = '$username_q'
              AND    L_Board    = '$Board_q'
            ";
            $sti = $dbh -> do_query($query);
            list($last) = $dbh -> fetch_array($sti);
            $last[$Board] = $last;
            $unread = $last;
         }
        if ($RealLast > $unread) { 
        // ----------------------------------------------------
        // It looks like there are some new messages in here so
        // Now we need to grab all of the replies in this thread
           $Board_q = addslashes($Board);
           $query = "
             SELECT B_Number,B_Parent,B_Posted
             FROM   {$config['tbprefix']}Posts
             WHERE  B_Main  = '$Thread'
             AND    B_Board = '$Board_q'
             AND    B_Number <> '$Number'
             $Viewable
             ORDER BY B_Posted
           ";
           $sti = $dbh -> do_query($query); 
           $replydata = $dbh -> fetch_array($sti);
           $replies   = $dbh -> total_rows($sti);
           $new = '';
           $newreplies = 0;
           $cycle = 0;
           for ( $i=0; $i<$replies;$i++) {
              $cycle++;
              if ($cycle == $flatposts) {
                 $pagejump++;
                 $cycle = 0;
              }
              list($No,$Pa,$Po) = $dbh -> fetch_array($sti);
              $checkthis = ",$No,";
              if ( ($Po > $unread) && (!stristr($read,"$checkthis")) )  {
                  $newmarker = "<img align=\"absmiddle\" src=\"{$config['images']}/new.gif\" alt=\"{$ubbt_lang['NEW_TEXT']}\" />";
                  if (!$postmarker) {
                     $postmarker = "#Post$No";
                     $partnumber = $pagejump;
                  }
               }
            }
         }
      }

      $RealLast = $html -> convert_time($RealLast,$user['U_Offset']);

      $topic[$i]['Number'] = $Number;
      $topic[$i]['Board'] = $Board;
      $topic[$i]['Thread'] = $Thread;
      $topic[$i]['pagejump'] = $pagejump;
      $topic[$i]['postmarker'] = $postmarker;
      $topic[$i]['Subject'] = $Subject;
      $topic[$i]['newmarker'] = $newmarker;
      $topic[$i]['poster'] = $poster;
      $topic[$i]['RealLast'] = $RealLast;
      $topic[$i]['checkbox'] = "E$i";
      $i++;
   }
   $favsize = sizeof($topic);
   include("$thispath/templates/$tempstyle/viewfavorites.tmpl");



// ----------------
// send the footer
  $sth -> finish;
  $html -> send_footer();

?>
