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
   require ("languages/${$config['cookieprefix']."w3t_language"}/editemail.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Groups");
   $Username = $user['U_Username'];
   $Password = $user['U_Password'];

   $html = new html;
   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// -----------------------------------------
// Get the current profile for this username
   $Username_q = addslashes($user['U_Username']);
   $query = " 
    SELECT U_Username,U_EReplies,U_Notify,U_AdminEmails,U_EmailFormat
    FROM  {$config['tbprefix']}Users
    WHERE U_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);

// --------------------------------
// Make sure we found this Username
   list($CheckUser,$EReplies,$Notify,$adminemail,$emailformat) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if (!$CheckUser){
      $html -> not_right("{$ubbt_lang['NO_PROF']} '{$user['U_Username']}'",$Cat);
   }

// ---------------------------------
// Set the default for Email replies

   if($EReplies == "Off") { 
      $noereplies = "checked=\"checked\""; 
   }
   else {
      $ereplies = "checked=\"checked\"";
   }

// ------------------------------------------------
// Set the default for private message notification
   if ($Notify == "On") {
      $donotify = "checked=\"checked\"";
   }
   else {
      $nonotify = "checked=\"checked\"";
   }

// --------------------------------
// Set the default for admin emails
	if ($adminemail == "Off") {
		$optout = "checked=\"checked\"";
	}
	else {
		$optin = "checked=\"checked\"";
	}

// ------------------------------------
// Receive emails in plain text or HTML
	if ($emailformat == "HTML") {
		$htmlformat = "checked=\"checked\"";
	}
	else {
		$plaintext = "checked=\"checked\"";
	}


// ---------------------------------------------------------
// Ok, we found the profile, now lets put it all onto a page
   $html -> send_header("{$ubbt_lang['EMAIL_HEAD']} {$user['U_Username']}",$Cat,0,$user);

// ------------------------------------------------------------------
// if we are allowing subscribes then we need to put that on the page
   if ($config['subscriptions']) {
      if ($Cat) {
         $Catchooser = "AND Bo_Cat in ($Cat)";
      }

   // ------------------------------------------------------
   // We need to know what boards this user can subscribe to
      $Grouparray = split("-",$user['U_Groups']);
      $gsize = sizeof($Grouparray);
      $groupquery = "(";
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

   // -----------------------------------------
   // Grab the keyword and titles of all boards
      $query = "
        SELECT Bo_Title,Bo_Keyword,Bo_Cat
        FROM  {$config['tbprefix']}Boards
        WHERE $groupquery 
        $Catchooser 
        ORDER BY Bo_Title
      ";

      $sth = $dbh -> do_query($query);
      $editsubscriptions = "{$ubbt_lang['SUB_STAT']}<br /><br />";
      $editsubscriptions .= "{$ubbt_lang['SUB_SHOW']}<br /><br />";

$editsubscriptions .= "<table width=\"{$theme['tablewidth']}\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
<tr>
<td class=\"tableborders\">
<table cellpadding=\"{$theme['cellpadding']}\" cellspacing=\"{$theme['cellspacing']}\" width=\"100%\" border=\"0\">
<tr>
<td class=\"tdheader\">Board</td><td class=\"tdheader\" align=\"center\">{$ubbt_lang['TEXT_S']}</td><td class=\"tdheader\" align=\"center\">{$ubbt_lang['TEXT_U']}</td><td class=\"tdheader\">&nbsp;&nbsp;</td>
<td class=\"tdheader\">Board</td><td class=\"tdheader\" align=\"center\">{$ubbt_lang['TEXT_S']}</td><td class=\"tdheader\" align=\"center\">{$ubbt_lang['TEXT_U']}</td></tr>
      ";
      $cycle = 0;
      $color = "darktable";
      $rows = 0;
      while (list($Title,$Keyword) = $dbh -> fetch_array($sth) ) {
         $rows++;
         if ($cycle == 0) {
            $editsubscriptions .= "<tr class=\"$color\">";
         }
    
      // ------------------------------------
      // check if they are already subscribed
         $Username_q = addslashes($user['U_Username']);
         $Keyword_q  = addslashes($Keyword);

         $query = "
           SELECT S_Username
           FROM  {$config['tbprefix']}Subscribe
           WHERE S_Username = '$Username_q' 
           AND   S_Board = '$Keyword_q'
         ";
         $sti = $dbh -> do_query($query) ;
         list($check) = $dbh -> fetch_array($sti);
         if ($check) { 
            $Sub = "checked=\"checked\""; 
            $Nosub = "";
         }
         else {
            $Nosub = "checked=\"checked\"";
            $Sub = "";
         }
      
         $editsubscriptions .= "<td>$Title</td>";
         $Word = "$Keyword"."--SUB--YES";
         $editsubscriptions .="<td align=\"center\"><input type=\"radio\" name=\"$rows\" value = \"$Word\" $Sub class=\"formboxes\" /></td>\n";
         $Word = "$Keyword"."--SUB--NO";
         $editsubscriptions .= "<td align=\"center\"><input type=\"radio\" name=\"$rows\" value = \"$Word\" $Nosub class=\"formboxes\" /></td>\n";
         $cycle++;
         if ($cycle == 1) {
            $editsubscriptions .= "<td class=\"tdheader\"></td>";
         }
         if ($cycle == 2) {
            $editsubscriptions .= "</tr>";
            $cycle = 0;
            if ($color == "darktable") {
               $color = "lighttable";
            }
            else {
               $color = "darktable";
            }
         }    
      }
      $dbh -> finish_sth($sth);
      if ($cycle == 1) {
         $editsubscriptions .= "<td></td><td></td><td></td>";
      }
      $editsubscriptions .= "</table></td></tr></table>";
      $editsubscriptions .= "<br />";
      $editsubscriptions .= "<input type=\"hidden\" name = \"Totalsubs\" value = \"$rows\" />";
   }

	if (!$debug) {
   	include ("$thispath/templates/$tempstyle/editemail.tmpl");
	}
   $html -> send_footer();

?>
