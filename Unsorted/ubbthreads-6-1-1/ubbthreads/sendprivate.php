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
   require ("languages/${$config['cookieprefix']."w3t_language"}/sendprivate.php");         

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_TextCols,U_TextRows,U_Preview");  
   $Username =  $user['U_Username'];

// --------------------------------------------------------------------
// If they haven't logged in yet then they cant send a message 
   $html = new html;
   if (!$user['U_Username']) {
      $html -> not_right($ubbt_lang['NO_LOGGED'],$Cat);
   }	
	if (!$config['private']) {
		$html -> not_right($ubbt_lang['NOPRIVS'],$Cat);
	}

// -----------------------------------------------
// Find out if they get the default preview or not
   $Preview = $user['U_Preview'];
   if (!$Preview) { $Preview = $config['Preview']; }

   if ( ($Preview == 1) || ($Preview == "on") ){
      $Pselected = "checked = \"checked\"";
   }

   $TextCols = $user['U_TextCols'];
   $TextRows = $user['U_TextRows'];

   if (!$TextCols) { $TextCols = $theme['TextCols']; }
   if (!$TextRows) { $TextRows = $theme['TextRows']; }

// ------------------
// Check for any bans
   $userob -> check_ban($user['U_Username'],$Cat);


// -------------------------------------------------
// Find out if this user is taking private messages
   $User = rawurldecode($User);
   $User_q = addslashes($User);
   if ($User) {
      $query = " 
        SELECT U_AcceptPriv
        FROM   {$config['tbprefix']}Users
        WHERE  U_Username = '$User_q'
      ";
      $sth = $dbh -> do_query($query);
      list($AcceptPriv) = $dbh -> fetch_array($sth); 
      $dbh -> finish_sth($sth);

      if ( ($user['U_Status'] != "Administrator") && ($AcceptPriv == "no") ) {
         $html -> not_right($ubbt_lang['NO_PRIVATE'],$Cat);
      }
   }

// --------------------------------------------------------------
// Give them a form to fill out so they can send a private message
   $html -> send_header("{$ubbt_lang['PRIV_HEAD']} $User",$Cat,0,$user);

    
// -----------------------------
// List the address book members
   $Username_q = addslashes($Username);
   $query = " 
    SELECT Add_Member
    FROM   {$config['tbprefix']}AddressBook
    WHERE  Add_Owner = '$Username_q'
    ORDER BY Add_Member
   ";
   $sth = $dbh -> do_query($query);
   
   while (list ($Item) = $dbh -> fetch_array($sth)) {
      $options .= "<option value=\"$Item\">$Item</option>";
   }
   $dbh -> finish_sth($sth);

	if (!$debug) {
   	include("$thispath/templates/$tempstyle/sendprivate.tmpl");
	}
   $html -> send_footer();

