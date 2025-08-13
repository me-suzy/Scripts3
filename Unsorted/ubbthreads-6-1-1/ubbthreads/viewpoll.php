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
   require ("languages/${$config['cookieprefix']."w3t_language"}/viewpoll.php");

// -----------------
// Get the user info

   $userob = new user;
   $user = $userob -> authenticate();            

// ------------------------
// Now show them their page 
   $html = new html;
   $html -> send_header($ubbt_lang['POLL_RES'],$Cat,0,$user);

// ------------------------------
// Grab the data from this poll 
   $Poll_q = addslashes($poll);
   $query = " 
     SELECT P_Option,P_Number,P_Title FROM {$config['tbprefix']}Polls
     WHERE P_Name = '$Poll_q'
   ";
   $sth = $dbh -> do_query($query); 
   $totalops = 0;

   while ( list ($Option,$PNumber,$Title) = $dbh -> fetch_array($sth) ) {
      $totalops++;
      $options[$PNumber]['0'] = $Option;
      $options[$PNumber]['1'] = 0;
      $options[$PNumber]['2'] = $Title;
   }
   $dbh -> finish_sth($sth);

   $query = " 
     SELECT COUNT(*),P_Number
     FROM {$config['tbprefix']}PollData
     WHERE  P_Name = '$Poll_q'
     GROUP BY P_Number
     ORDER BY P_Number
   "; 
   $sth = $dbh -> do_query($query);
   while (list ($Votes,$Which) = $dbh -> fetch_array($sth)) {
      $options[$Which]['1'] = $Votes;
      $totalvotes = $totalvotes + $Votes;
      if ($Votes > $maxvotes) {
         $maxvotes = $Votes;
      }
   }

// Loop through the results to figure out the percentages
   while(list($key,$value) = each($options)) {
     $division = $totalvotes * 1000;
     $thisone = $options[$key]['1'] * 1000;
     if ($division) {
        $per = ($thisone/$division);
     }
     list($crap,$percent) = split("\.",$per);
     if ($percent < 10) {
        $percent .= "0";
     }
     $percent = substr($percent,0,2);
     if ($per == 1) { $percent = "100"; }
     $options[$key]['3'] = $percent;
   } 
   $dbh -> finish_sth($sth);

// Need to setup a multiplier for the width of the images
   for ($i=10;$i<=100;$i=$i+10) {
     if ($maxvotes < $i) {
        $multiplier = intval((100 / $i));
        break;
     }
   }
   for ($i=1; $i<=$totalops; $i++) {
      $x = $i -1;
      $pollrow[$x]['width'] = $options[$i]['1'] * ($multiplier);
      $pollrow[$x]['option'] = $options[$i]['0'];
      $pollrow[$x]['votes']  = $options[$i]['1'];
      $pollrow[$x]['percent'] = $options[$i]['3'];
   }

   $pollsize = $totalops;
   include("$thispath/templates/$tempstyle/viewpoll.tmpl");

// ---------------
// Send the footer
   $html -> send_footer();

