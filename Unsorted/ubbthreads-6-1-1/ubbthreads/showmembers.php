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
   require ("languages/${$config['cookieprefix']."w3t_language"}/showmembers.php");

// Define some vars
   $StatusS = "";
   $HomepageS = "";
   $TotalS = "";
   $sorticon = ""; 
   $ICQS = "";
   $RegedS = "";
   $pages = "";
// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_TimeOffset");         

	if (!$config['userlist']) { exit; }
   if (($config['userlist'] == 2) && (!$user['U_Username'])) { exit; }


// --------------------
// Set the default sort
   if (empty($sb)) { $sb = 1; };

// --------------------------------------------------
// Grab the total number of users out of the database
   $query = "
      SELECT COUNT(*)
      FROM  {$config['tbprefix']}Users
      WHERE U_Approved='yes'
   "; 
   $sth = $dbh -> do_query($query);
   list($totalusers) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// ----------------------------------------
// Get ready to show how it is being sorted
   $sortuser  = 1;
   $sortstatus = 3;
   $sorthome   = 5;
   $sorttotal  = 7;
   $sorticq    = 9;
   $sortreged  = 11;

// ---------------------------------------------------------------------
// A multi-dimensional array to show the sort order and set the sql sort
   $Sorting = array(
      0 => array(0=>"blah", 1=>"blah"),
      1 => array(0=>"<img src=\"{$config['images']}/ascend.gif\" border=\"0\" alt=\"\" />", 1=>  "U_Username ASC"),
      2 => array(0=>"<img src=\"{$config['images']}/descend.gif\" border=\"0\" alt=\"\" />", 1=> "U_Username DESC"),
      3 => array(0=>"<img src=\"{$config['images']}/ascend.gif\" border=\"0\" alt=\"\" />", 1=>  "U_Status ASC"),
      4 => array(0=>"<img src=\"{$config['images']}/descend.gif\" border=\"0\" alt=\"\" />", 1=> "U_Status DESC"),
      5 => array(0=>"<img src=\"{$config['images']}/ascend.gif\" border=\"0\" alt=\"\" />", 1=>  "U_Homepage ASC"),
      6 => array(0=>"<img src=\"{$config['images']}/descend.gif\" border=\"0\" alt=\"\" />", 1=> "U_Homepage DESC"),
      7 => array(0=>"<img src=\"{$config['images']}/ascend.gif\" border=\"0\" alt=\"\" />", 1=>  "U_TotalPosts ASC"),
      8 => array(0=>"<img src=\"{$config['images']}/descend.gif\" border=\"0\" alt=\"\" />", 1=> "U_TotalPosts DESC"),
      9 => array(0=>"<img src=\"{$config['images']}/ascend.gif\" border=\"0\" alt=\"\" />", 1=>  "U_Extra1 ASC"),
      10 => array(0=>"<img src=\"{$config['images']}/descend.gif\" border=\"0\" alt=\"\" />", 1=> "U_Extra1 DESC"),
      11 => array(0=>"<img src=\"{$config['images']}/ascend.gif\" border=\"0\" alt=\"\" />", 1=>  "U_Registered ASC"),
      12 => array(0=>"<img src=\"{$config['images']}/descend.gif\" border=\"0\" alt=\"\" />", 1=> "U_Registered DESC")
    );

// ------------------------------------------
// Get ready to show how this is being sorted
   switch($sb) {

      case 1:
         $UserS = $Sorting['1']['0'];
         $sortuser = 2;
         break;
      case 2:
         $UserS = $Sorting['2']['0'];
         $sortuser = 1;
         break;
      case 3:
         $StatusS = $Sorting['3']['0'];
         $sortstatus = 4;
         break;
      case 4:
         $StatusS = $Sorting['4']['0'];
         $sortstatus = 3;
         break;
      case 5:
         $HomepageS = $Sorting['5']['0'];
         $sorthome = 6;
         break;
      case 6:
         $HomepageS = $Sorting['6']['0'];
         $sorthome = 5;
         break;
      case 7:
         $TotalS = $Sorting['7']['0'];
         $sorttotal = 8;
         break;
      case 8:
         $TotalS = $Sorting['8']['0'];
         $sorttotal = 7;
         break;
      case 9:
         $ICQS = $Sorting['9']['0'];
         $sorticq = 10;
         break;
      case 10:
         $ICQS = $Sorting['10']['0'];
         $sorticq = 9;
         break;
      case 11:
         $RegedS = $Sorting['11']['0'];
         $sortreged = 12;
         break;
      case 12:
         $RegedS = $Sorting['12']['0'];
         $sortreged = 11;
         break;
   }

// -------------------------------
// Lets give the start of the page 
   $html = new html;
   $html -> send_header($config['title'],$Cat,0,$user);

// -------------------------------------
// Here we grab the users for this page
   if($page == 1) {
      $Totalgrab =  25;
   }else{
      $Startat = (($page-1) * 25);
      $Totalgrab  = "$Startat, 25";
   }

   $limitit = "LIMIT $Totalgrab";

   $query = " 
     SELECT U_Username,U_Registered,U_Extra1,U_Homepage,U_TotalPosts,U_Status
     FROM  {$config['tbprefix']}Users
     WHERE   U_Approved = 'yes'
     AND     U_Number <> 1
     ORDER BY " .$Sorting[$sb]['1']. " 
     $limitit 
   ";
   $sth = $dbh -> do_query($query);
   $total = $dbh -> total_rows($sth);
   $newpage = 0;

// -----------------------
// Set the first row color
   $color = "lighttable";

// ----------------------------------------------------------------
// Cycle through the users
   for($i=0;$i<$total;$i++){

      list ($Username,$Reged,$ICQ,$Home,$Posts,$Status) = $dbh -> fetch_array($sth);

      if ($Home) {
         $Home = "<a href=\"http://$Home\" target=\"new\">$Home</a>";
      }
      if ( (preg_match("/[0-9]/",$ICQ)) && ($config['ICQ_Status']) ){
         $ICQ = "<img src=\"http://online.mirabilis.com/scripts/online.dll?icq=$ICQ&amp;img=1\" />";
      }  
      $Reged = $html -> convert_time($Reged,$user['U_TimeOffset']);
      $EUsername = rawurlencode($Username);

      if ($Status == "Administrator") {
			$Status = $ubbt_lang['USER_ADMIN'];
		}
		elseif ($Status == "Moderator") {
			$Status = $ubbt_lang['USER_MOD'];
		}
		elseif ($Status == "User") {
			$Status = $ubbt_lang['USER_USER'];
		}
      $userrow[$i]['color'] = $color;
      $userrow[$i]['EUsername'] = $EUsername;
      $userrow[$i]['Username'] = $Username;
      $userrow[$i]['Status'] = $Status;
      $userrow[$i]['Home'] = $Home;
      $userrow[$i]['Posts'] = $Posts;
      $userrow[$i]['ICQ'] = $ICQ;
      $userrow[$i]['Reged'] = $Reged;
 
      $color = $html -> switch_colors($color);
   }
   $dbh -> finish_sth($sth);

// ---------------------------------------
// Print out the page jumper at the bottom
   $TotalPages = ($totalusers / 25) - 1;
   $TotalPages;
   list ($TotalP,$Leftover) = split("\.",$TotalPages);
   $TotalP++;
   if ($Leftover > 0) { $TotalP++; }
   $Startpage = $page - 10;
   $Endpage   = $page + 10;
   if ($Startpage < 0) {
      $Endpage   = $Endpage - $Startpage;
      $Startpage = 0;
   }
   if ($Endpage > $TotalP) {
      $Endpage = $TotalP;
      $Startpage = $Endpage - 20;
   }
   if ($Startpage < 1) { $Startpage = 1; }
   if ($Startpage > 1) {
      $prev = $page -1;
      $pages .= "<a href=\"{$config['phpurl']}/showmembers.php?Cat=$Cat&amp;sb=$sb&amp;page=$prev\"><<</a>";
   }
   if ($totalusers > 25) {
      for ($i=$Startpage; $i<= $Endpage; $i++) {
         if ($i == $page) {
	    $pages .= "$i ";
         }
         else {
           $pages .= "<a href=\"{$config['phpurl']}/showmembers.php?Cat=$Cat&amp;sb=$sb&amp;page=$i\">$i</a> ";
         }
      }
   }
   if ($Endpage < $TotalP) {
      $next = $page + 1;
      $pages .= "<a href=\"{$config['phpurl']}/showmembers.php?Cat=$Cat&amp;sb=$sb&amp;page=$next\">>></a>";
   }

  $userrowsize = sizeof($userrow);
	if (!$debug) {
	  include("$thispath/templates/$tempstyle/showmembers.tmpl");
	}
  $html -> send_footer();
