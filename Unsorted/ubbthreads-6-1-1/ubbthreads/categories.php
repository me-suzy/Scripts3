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
   require ("languages/${$config['cookieprefix']."w3t_language"}/categories.php");

// ------------------------------
// Define any necessary variables
   $catonly     = "";
   $notapproved = "";
   $showna      = "";
   $shownew     = "";
   $shownewt    = "";

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Groups, U_TimeOffset, U_FrontPage"); 
   $Username = $user['U_Username'];

// ---------------
// send the header
   $html = new html;
   $html -> send_header($config['title'],$Cat,0,$user);

// ------------------------------------------------------------------------
// If they are logged in then we check their groups, otherwise they
// get set to the guest group 
   $Groups = $user['U_Groups'];
   if (!$Groups) {
     $Groups = "-4-";
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
 
// -----------------------------------------------------------------
// If we have a Cat variable then we need to set it up for the query
   if ($Cat) {
     $pattern = ",";
     $replace = " OR Cat_Number = ";
     $catonly = preg_replace($pattern,$replace,$Cat);
     $catonly = "WHERE Cat_Number = " . $catonly;
   }

// ----------------------------------------------------------------------
// Lets see how many total registered users there are but only if we want
// to display this information
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
   $EUsername = rawurlencode($newusername);
   $newuser = "{$ubbt_lang['NEW_MEM']} <a href=\"{$config['phpurl']}/showprofile.php?User=$EUsername&What=categories\">{$newusername['0']}</a>!";
   $dbh -> finish_sth($sth);

// Must define welcome message here for the template since it is different
// if the user isn't logged in.
   if ($user['U_Username']) {
      $welcomemessage = "{$ubbt_lang['INTRO_SUB']}, {$user['U_Username']}";
   }
   else {
      $welcomemessage = $ubbt_lang['WEL_PRIV_NO'];
   }

// --------------------------
// Get the list of categories
   $query = "
     SELECT DISTINCT Cat_Title,Cat_Number,Cat_Description
     FROM   {$config['tbprefix']}Category
     $catonly
     ORDER BY Cat_Number
   ";
   $categories = $dbh -> do_query($query);
   $j = 0; 

// --------------------------------------------------------------
// We need to format a SQL query to see what boards this user can
// view
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
   $forumtotal=0;
   $threadtotal=0;
   $posttotal=0;
   $shownew=0;
   $x = 0;
   while (list ($CatTitle,$CatNumber,$CatDescription) = $dbh -> fetch_array($categories)) {

     $catrow[$x]['CatNumber'] = $CatNumber;
     $catrow[$x]['CatTitle']  = $CatTitle;
     $catrow[$x]['CatDescription'] = $CatDescription;

  // ---------------------------------------
  // Grab all of the boards in this category
     $query = "
      SELECT Bo_Keyword,Bo_Total,Bo_Last,Bo_Threads,Bo_Moderated,Bo_Moderators
      FROM   {$config['tbprefix']}Boards
      WHERE  Bo_Cat     = '$CatNumber'
      $groupquery
     ";
     $boards = $dbh -> do_query($query);

  // ---------------------------------------------
  // Now cycle through the boards in this category
     $firstpass = 0;
     while (list($Keyword,$Total,$Last,$Threads,$Moderated,$modlist) = $dbh -> fetch_array($boards)) {
      // ---------------------------------------------------------------
      // If they are a normal user then they can only see approved posts
         $Viewable = "And B_Approved = 'yes'";
         if ($user['U_Status'] == "Administrator") {
            $Viewable = "";
         }
         if ($user['U_Status'] == "Moderator") {
            if (preg_match("/(,|$){$user['U_Username']}(,|^)/i",$modlist)) {
               $Viewable = "";
            }
         }

      // --------------------------------------------------
      // Check if the are new posts since they last visited
         if ($Username) {
            $Username_q  = addslashes($Username);
            $Board_q     = addslashes($Keyword);
            $query = "
              SELECT L_Last 
              FROM   {$config['tbprefix']}Last  
              WHERE  L_Username = '$Username_q'
              AND    L_Board    = '$Board_q'
            ";
            $sti = $dbh -> do_query($query);
            list($checker) = $dbh -> fetch_array($sti);
            $dbh -> finish_sth($sti);
            if (!$checker) {
               $checker = 0;
            }

         // --------------------------------------------------------------
         // Let's see how many new posts there are.  We have to do some
         // tricky AND/OR operations because we don't want sticky posts to
         // be counted as new if they aren't.
            $newposts;
            $newthreads;

            $query = "
              SELECT COUNT(*), SUM(B_Topic)
              FROM   {$config['tbprefix']}Posts
              WHERE  (B_Posted > $checker AND B_Posted <> 4294967295)
              $Viewable
              AND    B_Board = '$Board_q'
            ";
            $newp1 = $dbh -> do_query($query);
            $newposts1 = $dbh -> fetch_array($newp1);
            $dbh -> finish_sth($newposts1);
            $newthreads1 = $newposts1[1];
            $newposts1 = $newposts1[0];

            $query = "
              SELECT COUNT(*), SUM(B_Topic)
              FROM   {$config['tbprefix']}Posts
              WHERE (B_Posted = 4294967295 AND B_Sticky > $checker)
              $Viewable
              AND    B_Board = '$Board_q'
            ";
            $newp2 = $dbh -> do_query($query);
            $newposts2 = $dbh -> fetch_array($newp2);
            $dbh -> finish_sth($newpost2);
            $newthreads2 = $newposts2[1];
            $newposts2 = $newposts2[0];
         
          // now add the results
            $newposts = $newposts1 + $newposts2;
            $newthreads = $newthreads1 + $newthreads2;



            if ( ($checker < $Last) && ($Username) ) {
               $shownew = $shownew + $newposts;
            }

         // -----------------------------------------------------------
         // If we have new posts let's see how many new threads we have
         // merged with query above
         
            if ( ($checker < $Last) && ($Username) ) {
               $shownewt = $shownewt + $newthreads;
	       	} 

         // --------------------------------------
         // Let's see how many NA posts there are
            if ( ($Moderated == "yes") && ($Viewable == "") ) {
               $query = "
                 SELECT COUNT(*)
                 FROM   {$config['tbprefix']}Posts 
                 WHERE  B_Approved = 'no' 
                 AND    B_Board    = '$Board_q'
               ";
               $notapp = $dbh -> do_query($query);
               list($notapproved) = $dbh -> fetch_array($notapp); 
               $dbh -> finish_sth($notapp);
            }
            $showna = $showna + $notapproved;
         }
         $catrow[$x]['threadtotal'] = $catrow[$x]['threadtotal']+$Threads;
         $catrow[$x]['forumtotal']++;
         $catrow[$x]['posttotal'] = $catrow[$x]['posttotal'] + $Total;

      }
      $dbh -> finish_sth($boards);

      if ($shownew) {
         $shownew = "<br /><font class=\"new\">($shownew {$ubbt_lang['NEW_TEXT']})</font>";
      } else {
         $shownew = "";
      }
      $catrow[$x]['shownew'] = $shownew;

      if ($shownewt) {
         $shownewt = "<br /><font class=\"new\">($shownewt {$ubbt_lang['NEW_TEXT']})</font>";
      } else {
         $shownewt = "";
      }
      $catrow[$x]['shownewt'] = $shownewt;

      if ($showna) {
         $showna = " <font class=\"new\">($showna {$ubbt_lang['NOT_APPROVED']})</font>";
      }
      else {
        $showna = "";
      }
      $catrow[$x]['showna'] = $showna;

      $forumtotal = 0;
      $threadtotal = 0;
      $posttotal = 0;
      $shownew = "";
      $shownewt = "";
      $showna = "";
      $x++;
    }

   $dbh -> finish_sth($categories);

// -----------------------
// Grab the total # online
   $query = "
      SELECT O_Type,COUNT(*)
      FROM   {$config['tbprefix']}Online
      GROUP BY O_Type
   ";
   $sth = $dbh -> do_query($query);
   while(list ($type,$count) = $dbh -> fetch_array($sth)) {
      ${$type} = $count;
   }
   if (!$a) {$a = "0"; }
   if (!$r) {$r = "0"; }

   $catsize = sizeof($catrow);
	if (!$debug) {
   	include ("$thispath/templates/$tempstyle/categories.tmpl");
	}
   $html -> send_footer();

?>
