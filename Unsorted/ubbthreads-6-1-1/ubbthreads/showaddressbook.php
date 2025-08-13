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

   require ("languages/${$config['cookieprefix']."w3t_language"}/showaddressbook.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_TimeOffset");
   $html = new html;

// --------------------
// Set the default sort
   if (!$sb) { $sb = 1; }

// ----------------------------------------------------
// Grab all entries from the address book for this user
   $Username_q = addslashes($user['U_Username']);

   $query = "
    SELECT Add_Member  
    FROM  {$config['tbprefix']}AddressBook
    WHERE ADD_Owner = '$Username_q'
    ORDER BY Add_Member
   "; 
   $sth = $dbh -> do_query($query);

// Lets give the start of the page 
   $html -> send_header($ubbt_lang['YOUR_ADDRESS'],$Cat,0,$user);

// ----------------------------------------------------------------
// Cycle through the users
   $color = "lighttable";
   $x=0;
   while ( list($Member) = $dbh -> fetch_array($sth)){
      $EUsername = rawurlencode($Member);
      $userrow[$x]['EUsername'] = $EUsername;
      $userrow[$x]['Member'] = $Member;
      $userrow[$x]['color'] = $color;
      $x++;      
      $color = $html -> switch_colors($color);
   }

   $userrowsize = sizeof($userrow);
	if (!$debug) {
   	include("$thispath/templates/$tempstyle/showaddressbook.tmpl"); 
	}

   $html -> send_footer();

?>
