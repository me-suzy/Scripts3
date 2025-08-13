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
   require "languages/${$config['cookieprefix']."w3t_language"}/domailthread.php";

   if (!$config['mailpost']) {
      $html -> not_right("DISABLED",$Cat);
   }

// -------------------------------------------------------------
// If we didn't get a board or number then we give them an error
   if (!$Board) {
      $html -> not_right($ubbt_lang['NO_AUTH'],$Cat);
   }

// ------------------------------------------------------
// Let's find out what Groups we are dealing with
   $Board_q = addslashes($Board);
   $Groups = $user['U_Groups'];
   if (!$Groups) { $Groups = "-4-"; }

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
   list ($title,$CanWrite,$CatName,$CatNumber) = $dbh -> fetch_array($sth);

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
   if ($realname) {
      $echoname = $realname;
   }
  
// --------------------------
// Replace break tags with \n
   $newline = "\n";
   if (stristr(PHP_OS,"win")) {
      $newline = "\r\n";
   }

   $tbody = str_replace("<br />","$newline",$tbody);

   $mailbody = "$echoname {$ubbt_lang['START']} '$tsubject' {$ubbt_lang['START2']}$newline$newline";
   $mailbody .= "{$ubbt_lang['NOTE_FROM']} $echoname:$newline$newline";
   $mailbody .= "$Notes$newline$newline";
   $mailbody .= "{$ubbt_lang['POST_BELOW']}$newline$newline";
   $mailbody .="$tbody$newline$newline";
   $mailbody .="{$ubbt_lang['TO_READ']}$newline{$config['phpurl']}/showthreaded.php?Board=$Board&Number=$Number";

   $mailer = new mailer;
   $header = $mailer -> headers();
   $subject = $tsubject;
   $mailsend = mail("$Email","$subject","$mailbody",$header);

// --------------------------------------
// Give confirmation and return to thread
   $html -> send_header($ubbt_lang['EMAIL_SENT'],$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/$what.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;fpart=$fpart&amp;vc=$vc&amp;o=$o\" />",$user);

	if (!$debug) {
   	include("$thispath/templates/$tempstyle/domailthread.tmpl");
	}

   $html -> send_footer();


?>
