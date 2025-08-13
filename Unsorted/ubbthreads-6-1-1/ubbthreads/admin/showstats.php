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
   require ("../main.inc.php");


// ------------------------------------------------------------------
// Lets find out the total number of groups and users in those groups 
// Function for showing user/group stats
   function userstats($Cat="") {

      global $config,$dbh;
      $html = new html;
      $html -> open_admin_table();
      echo "
      <tr class=\"darktable\">
        <td>
          Group
        </td>
        <td>
          Total # of users in this group
        </td>
      </tr>
      ";

   // ----------------------
   // Grab all of the groups
      $query = "
      SELECT G_Name,G_Id
      FROM   {$config['tbprefix']}Groups
      ";
      $sth = $dbh -> do_query($query);

   // ------------------------------------------------------------------------
   // Cycle through the groups and find out how many users are in those groups
      while ( list($gname,$gid) = $dbh -> fetch_array($sth)) {

         $query = "
           SELECT COUNT(*) 
           FROM  {$config['tbprefix']}Users
           WHERE U_Groups LIKE '%-$gid-%'
         ";
         $sti = $dbh -> do_query($query);
         list($rows) = $dbh -> fetch_array($sti);
         echo "<tr class=\"lighttable\"><td>";
         echo "$gname</td><td>$rows</td></tr>";
      }
      $html -> close_table();

   // -----------------------------
   // Show some new user statistics
      echo "<p>";
      $html -> open_admin_table();

   // ------------------------------------------------------------------
   // Find out how many users have registered within the last 24h/7d/31d
      $lastday = $html -> get_date() - (86400);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Users
         WHERE  U_Registered > $lastday
      ";
      $sth = $dbh -> do_query($query);
      list($lastday) = $dbh -> fetch_array($sth);
      $lastweek = $html -> get_date() - (86400 * 7);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Users
         WHERE  U_Registered > $lastweek
      ";
      $sth = $dbh -> do_query($query);
      list($lastweek) = $dbh -> fetch_array($sth);

      $lastmonth = $html -> get_date() - (86400 * 31);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Users
         WHERE  U_Registered > $lastmonth
      ";
      $sth = $dbh -> do_query($query);
      list($lastmonth) = $dbh -> fetch_array($sth);

      echo "
         <tr><td class=\"darktable\" width=40%>
           New Users in past 24 hours
         </td><td class=\"lighttable\" width=60%>
           $lastday 
         </td></tr>
         <tr><td class=\"darktable\" width=40%>
           New Users in past 7 days 
         </td><td class=\"lighttable\" width=60%>
           $lastweek 
         </td></tr>
         <tr><td class=\"darktable\" width=40%>
           New Users in past month 
         </td><td class=\"lighttable\" width=60%>
           $lastmonth 
      ";
 
      $html -> close_table();
      echo "<p>&nbsp;";

   // ------------------------------------------------------------------
   // Find out how many users have logged inn the last 24h/7d/31d
      $lastday = $html -> get_date() - (86400);
      $query = "
        SELECT COUNT(*)
         FROM   {$config['tbprefix']}Users
         WHERE  U_Laston > $lastday
      ";
      $sth = $dbh -> do_query($query);
      list($lastday) = $dbh -> fetch_array($sth);
      $lastweek = $html -> get_date() - (86400 * 7);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Users
         WHERE  U_Laston > $lastweek
      ";
      $sth = $dbh -> do_query($query);
      list($lastweek) = $dbh -> fetch_array($sth);
      $lastmonth = $html -> get_date() - (86400 * 31);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Users
         WHERE  U_Laston > $lastmonth
      ";
      $sth = $dbh -> do_query($query);
      list($lastmonth) = $dbh -> fetch_array($sth);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Users
         WHERE  U_Laston = U_Registered 
      ";
      $sth = $dbh -> do_query($query);
      list($never) = $dbh -> fetch_array($sth);

      $html -> open_admin_table();
      echo "
      <tr><td class=\"darktable\" width=40%>
        Users that have logged in the past 24 hours
      </td><td class=\"lighttable\" width=60%>
        $lastday 
      </td></tr>
      <tr><td class=\"darktable\" width=40%>
        Users that have logged in the past 7 days 
      </td><td class=\"lighttable\" width=60%>
        $lastweek 
      </td></tr>
      <tr><td class=\"darktable\" width=40%>
        Users that have logged in the past month 
      </td><td class=\"lighttable\" width=60%>
        $lastmonth 
      </td></tr>
      <tr><td class=\"darktable\" width=40%>
        Users that have never logged in 
      </td><td class=\"lighttable\" width=60%>
        $never
      ";
 
      $html -> close_table();
    
    
      $html -> send_admin_footer();

   }


// -----------------------------------------------------------
// We are going to show some stats on the boards here.
// First we need to find out when the very first post was made.
   function boardstats($Cat="") {

      global $config,$dbh;
      $html = new html;
   // -----------------------------
   // Push the groups into an array 
      $query = "
         SELECT G_Name,G_Id
         FROM   {$config['tbprefix']}Groups
         ORDER BY G_Id
      ";
      $sth = $dbh -> do_query($query);
      while ( list($gname,$gid) = $dbh -> fetch_array($sth)) {
         $garray[$gid] = $gname;
      }

   // ----------------------------
   // Grab all the forums and info
      $query = "
         SELECT Bo_Title,Bo_Description,Bo_Keyword,Bo_Total,Bo_Last,Bo_HTML,Bo_Created,Bo_Expire,Bo_Markup,Bo_Moderated,Bo_Read_Perm,Bo_Write_Perm
         FROM  {$config['tbprefix']}Boards
         ORDER BY Bo_Cat,Bo_Sorter
      ";
      $sth = $dbh -> do_query($query);


   // -------------------------
   // Cycle through the boards
      while ( list($Title,$Description,$Keyword,$totals,$Last,$HTML,$Created,$Expire,$Markup,$Moderated,$CanRead,$CanWrite) = $dbh -> fetch_array($sth)){

         $time = $html -> get_date();
         $days = ( intval( ($time - $Created) /86400));
         if ($days < 1) { 
            $avg = "No data yet"; 
         }
         else {
            $avg = ( intval(100*($totals / $days)) / 100.0 );
         }
         echo "<p>&nbsp;";
         $html -> open_admin_table();
         echo "<tr class=darktable><td><b>$Title</b></td></tr>";
         echo "<tr class=lighttable><td>\n";
         $Createdon = $html -> convert_time($Created);
         $Last      = $html -> convert_time($Last);

         $CanRead = split("-",$CanRead);
         $thissize = sizeof($CanRead);
         $Read = "";
         for ( $i=1;$i<$thissize;$i++) {
            if ($CanRead[$i]) {
               $Read .= $garray[$CanRead[$i]] .",";
            }
         }
         $Read = preg_replace("/,$/","",$Read);

         $CanWrite = split("-",$CanWrite);
         $thissize = sizeof($CanWrite);
         $Write = "";
         for ( $i=1;$i<$thissize;$i++) {
            if ($CanWrite[$i]) {
               $Write .= $garray[$CanWrite[$i]] .",";
            }
         }
         $Write = preg_replace("/,$/","",$Write);
 
         $html -> open_admin_table();
         echo "
           <tr>
           <td class=darktable width=20%>
            Created on
           </td>
           <td class=lighttable width=80%>
            $Createdon
           </td>
           </tr>
           <tr>
           <td class=darktable>
            Total Posts 
           </td>
           <td class=lighttable>
            $totals
           </td>
           </tr>
           <tr>
           <td class=darktable>
            Avg posts per day 
           </td>
           <td class=lighttable>
            $avg 
           </td>
           </tr>
           <tr>
           <td class=darktable>
            Last Post 
           </td>
           <td class=lighttable>
            $Last
           </td>
           </tr>
           <tr>
           <td class=darktable>
            Full Moderation 
           </td>
           <td class=lighttable>
            $Moderated
           </td>
           </tr>
           <tr>
           <td class=darktable>
            Forum Moderators 
           </td>
           <td class=lighttable>
         ";

      // -------------------------
      // Grab the forum moderators
         $Keyword_q = addslashes($Keyword);
         $query = "
            SELECT Mod_Username
            FROM   {$config['tbprefix']}Moderators
            WHERE  Mod_Board = '$Keyword_q'
         ";
         $sti = $dbh -> do_query($query);
         $moderators = "";
         while ( list($moder) = $dbh -> fetch_array($sti)) {
            $emoder = rawurlencode($moder);
            $moder = "<a href=\"{$config['phpurl']}/showprofile.php?Cat=$Cat&User=$emoder\">$moder</a>";
            $moderators .= " $moder,";
         }
         $moderators = preg_replace("/,$/","",$moderators);
         if (!$moderators) {
            $query = "
               SELECT U_Username
               FROM   {$config['tbprefix']}Users
               WHERE  U_Number = 2
            ";
            $sti = $dbh -> do_query($query);
            list ($moderators) = $dbh -> fetch_array($sti);
            $emoder = rawurlencode($moderators);
            $moderators = "<a href=\"{$config['phpurl']}/showprofile.php?Cat=$Cat&User=$emoder\">$moderators</a>";
         }

         echo "
             $moderators
             </td>
           </tr>
           <tr>
             <td class=darktable>
               HTML allowed 
             </td>
             <td class=lighttable>
               $HTML
             </td>
           </tr>
           <tr>
             <td class=darktable>
               Markup allowed 
             </td>
             <td class=lighttable>
               $Markup
             </td>
           </tr>
           <tr>
             <td class=darktable>
               Read Groups 
             </td>
             <td class=lighttable>
               $Read 
             </td>
           </tr>
           <tr>
             <td class=darktable>
               Write Groups 
             </td>
             <td class=lighttable>
               $Write 
         ";
         $html -> close_table(); 
         $html -> close_table(); 
       
      }

      $sth -> finish;
      $html -> send_admin_footer();
   }


// -----------------------------------------------------------------------
// Message status function
   function messagestats($Cat="") {
   
      global $config,$dbh;
      $html = new html; 
      $html -> open_admin_table();
      echo "
      <tr class=\"darktable\">
        <td>
          Private message statistics.  This only counts messages in the system.  It does not give stats on deleted private messages.
      ";

      $html -> close_table();
      echo "<br>";
   // ---------------------------------------------------
   // Get some info on the private messages in the system
      $query = "
      SELECT M_Status,count(*) 
      FROM {$config['tbprefix']}Messages 
      group by M_Status
     ";
     $sth = $dbh -> do_query($query);
     $html -> open_admin_table();
     while ( list($Status,$total) = $dbh -> fetch_array($sth)) {
        $allpms = $allpms + $total;
        if (!$Status) {
           $Status = "Read but not replied to";
        }
        elseif ($Status == "C") {
           $Status = "Unread with a \"Read Receipt\" request";
        }
        elseif ($Status == "N") {
           $Status = "Unread";
        }
        elseif ($Status == "R") {
           $Status = "Read and replied to";
        }
        elseif ($Status == "X") {
           $Status = "Carbon Copies";
        }
        echo "
        <tr class=lighttable><td class=darktable width=40%>
          $Status
        </td><td class=lighttable width=60%>
          $total
        </td></tr>
        ";
      }

      echo "</table></td></tr></table>";
   // ---------------------------------
   // How many are within the last day/week/month
      $lastday = $html -> get_date() - 86400;
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Messages
         WHERE  M_Sent > $lastday
      ";
      $sth = $dbh -> do_query($query);
      list($lastday) = $dbh -> fetch_array($sth);
      $lastweek = $html -> get_date() - (86400 * 7);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Messages
         WHERE  M_Sent > $lastweek
      ";
      $sth = $dbh -> do_query($query);
      list($lastweek) = $dbh -> fetch_array($sth);
      $lastmonth = $html -> get_date() -  (86400 * 31);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Messages
         WHERE  M_Sent > $lastmonth
      ";
      $sth = $dbh -> do_query($query);
      list($lastmonth) = $dbh -> fetch_array($sth);
      echo "<br>";
      $html -> open_admin_table();
      echo "
      <tr><td class=darktable width=40%>
        Sent in the last 24 hours 
      </td><td class=lighttable width=60%>
        $lastday
      </td></tr> 
      <tr><td class=darktable>
        Sent in the last week 
      </td><td class=lighttable>
        $lastweek
      </td></tr> 
      <tr><td class=darktable>
        Sent in the last month
      </td><td class=lighttable>
        $lastmonth
      </td></tr> 
      <tr><td class=darktable>
        Total PMs in system
      </td><td class=lighttable>
        $allpms
      ";
      $html -> close_table();
      $html -> send_admin_footer();

   }

// --------------------------------------------------------------------------
// Post stats function
   function poststats($Cat="") {
   
      global $config,$dbh;
      $html = new html; 
      $html -> open_admin_table();
      echo "
      <tr class=\"darktable\">
        <td>
          Post statistics.  This only counts posts in the system.  It does not give stats on deleted posts.
        </td>
      </tr>
      <tr class=lighttable>
        <td>
      ";

   // ---------------------------------
   // How many are within the last day/week/month
      $lastday = $html -> get_date() - 86400;
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Posts
         WHERE  B_Posted > $lastday
      ";
      $sth = $dbh -> do_query($query);
      list($lastday) = $dbh -> fetch_array($sth);
      $lastweek = $html -> get_date() - (86400 * 7);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Posts
         WHERE  B_Posted> $lastweek
      ";
      $sth = $dbh -> do_query($query);
      list($lastweek) = $dbh -> fetch_array($sth);
      $lastmonth = $html -> get_date() -  (86400 * 31);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Posts
         WHERE  B_Posted > $lastmonth
      ";
      $sth = $dbh -> do_query($query);
      list($lastmonth) = $dbh -> fetch_array($sth);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Posts
         WHERE  B_Topic = 1
      ";
      $sth = $dbh -> do_query($query);
      list($topics) = $dbh -> fetch_array($sth);
      $query = "
         SELECT COUNT(*)
         FROM   {$config['tbprefix']}Posts
         WHERE  B_Topic = 0
      ";
      $sth = $dbh -> do_query($query);
      list($replies) = $dbh -> fetch_array($sth);

      $html -> open_admin_table();

      echo "
      <tr><td class=darktable width=25%>
        Posted in the last 24 hours 
      </td><td class=lighttable width=75%>
        $lastday
      </td></tr> 
      <tr><td class=darktable>
        Posted in the last week 
      </td><td class=lighttable>
        $lastweek
      </td></tr> 
      <tr><td class=darktable>
        Posted in the last month
      </td><td class=lighttable>
        $lastmonth
      </td></tr>
      <tr><td class=darktable>
        Total # of topics 
      </td><td class=lighttable>
        $topics
      </td></tr>
      <tr><td class=darktable>
        Total # of replies
      </td><td class=lighttable>
        $replies
     ";
     $html -> close_table();
     $html -> close_table();
     $html -> send_admin_footer();

   }


// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $html = new html;

// -----------------------------
// Make sure they should be here
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// ------------------------
// Send them a page 
   $html -> send_header ("Stats for this site.",$Cat,0,$user);
   $html -> admin_table_header("Stats for this site.");

// Show them the proper stats if they chose an action
   if ($action == "users") {
      userstats($Cat);
   }
   elseif ($action == "boards") {
      boardstats($Cat);
   }
   elseif ($action == "messages") {
      messagestats($Cat);
   }
   elseif ($action == "posts") {
      poststats($Cat);
   }
  exit;

?>
