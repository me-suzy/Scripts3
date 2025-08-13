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
   $user = $userob -> authenticate("U_Groups,U_Email");
   $html = new html;

// -----------------------------------------
// require the language file for this script
   require "languages/${$config['cookieprefix']."w3t_language"}/donotifymod.php";


// -------------------------------------------------------------
// If we didn't get a board or number then we give them an error
   if (!$Board) {
      $html -> not_right($ubbt_lang['NO_B_INFO'],$Cat);
   }

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
   for ($i=0; $i<$gsize;$i++) {
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

// -----------------------------------------------------------------------------
// Once and a while if people try to just put a number into the url,lets trap it
   if (!$Number) {
      $html -> not_right($ubbt_lang['POST_PROB'],$Cat);
   }
	$Number = addslashes($Number);

// ------------------------------------------
// Grab the main subject for this thread 
   $query = "
      SELECT B_Subject,B_Body
      FROM   {$config['tbprefix']}Posts 
      WHERE  B_Number = '$Number'
      AND    B_Board  = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);
   list($tsubject,$tbody) = $dbh -> fetch_array($sth);

// --------------------------------------
// Setup a variable to hold the mail body 
   $echoname = $user['U_Username'];
  
// --------------------------
// Replace break tags with \n
   $tbody = str_replace("<br />","\n","$tbody");
 
   $mailbody = "$echoname {$ubbt_lang['NOTIF_HEAD']}: '$tsubject' \n\n";
   $mailbody .="$tbody\n\n";

// -----------------------------
// Grab the moderators to notify
   $query = "
    SELECT Mod_Username
    FROM   {$config['tbprefix']}Moderators
    WHERE  Mod_Board = '$Board_q'
   ";
   $ismod=0;
   $sth = $dbh -> do_query($query);

   $mailer = new mailer;
   $subject = $tsubject;
   while ( list($Mod) = $dbh -> fetch_array($sth)) {

   // -----------------------------------------
   // Grab the email address for this moderator
      $Mod_q = addslashes($Mod);
      $query = "
         SELECT U_Email,U_EmailFormat 
         FROM   {$config['tbprefix']}Users 
         WHERE  U_Username = '$Mod_q' 
      ";
      $sti = $dbh -> do_query($query);
      list($Email,$emailformat) = $dbh -> fetch_array($sti);
		if ($emailformat == "HTML") {
      	$thismessage = str_replace("\"{$config['images']}","\"{$config['imageurl']}",$mailbody);
			$thismessage = str_replace("\n","<br />",$thismessage);
         $thismessage .= "{$ubbt_lang['TO_READ']}\n<a href=\"{$config['phpurl']}/showthreaded.php?Board=$Board&Number=$Number\">{$config['phpurl']}/showthreaded.php?Board=$Board&Number=$Number</a>";
		}
		else {
			$thismessage = $mailbody;
  			$thismessage .="{$ubbt_lang['TO_READ']}\n{$config['phpurl']}/showthreaded.php?Board=$Board&Number=$Number";
		}

      $header = $mailer -> headers("$emailformat",$user['U_Email']);
      $mailsend = mail("$Email","$subject","$thismessage",$header);

   // ---------------------------
   // Set the moderated flag to 1
    $ismod = 1;
  }

// ------------------------------------------------------------------------
// If the ismod flag isn't set to 1 then we didn't notify a moderator so we
// send the notification to the main admin
   if (!$ismod) {
      $query = "
         SELECT U_Email 
         FROM   {$config['tbprefix']}Users
         WHERE  U_Number = 2
      ";
      $sth = $dbh -> do_query($query);
      list($Email) = $dbh -> fetch_array($sth);
      $mailsend = mail("$Email","$subject","$mailbody",$header);
   
   }

// -------------------------------------------------------------------
// Update the table so we know the moderator has already been notified
   $query = "
    INSERT INTO {$config['tbprefix']}ModNotify
    (M_Number)
    VALUES 
    ('$Number')
   ";
   $dbh -> do_query($query);
  
// --------------------------------------
// Give confirmation and return to thread
   $html -> send_header($ubbt_lang['NOTIFY_SENT'],$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/$what.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;fpart=$fpart&amp;vc=$vc&amp;o=$o\" />",$user);

	if (!$debug) {
   	include ("$thispath/templates/$tempstyle/donotifymod.tmpl");
	}
   $html -> send_footer();


?>
