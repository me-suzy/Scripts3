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
   require ("languages/${$config['cookieprefix']."w3t_language"}/editpost.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_TextCols, U_TextRows, U_Preview");  
   $Username = $user['U_Username'];

   $TextCols = $user['U_TextCols'];
   $TextRows = $user['U_TextRows'];
   if (!$TextCols) { $TextCols = $theme['TextCols']; }
   if (!$TextRows) { $TextRows = $theme['TextRows']; }

// --------------------
// Authenticate the user
   $html = new html;
   if (!$user['U_Username']){
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// ------------------
// Check for any bans
   $userob -> check_ban($user['U_Username'],$Cat);

// ---------------------------------------------------------------------------
// For security purposes we need to verify that this is user made this post or 
// if they are an admin or a moderator for this board.
   $Status = $user['U_Status'];

// -----------------------------------
// Get the post info from the database
   $Board_q = addslashes($Board);
	$Number = addslashes($Number);
   $query = "
     SELECT B_Username,B_Subject,B_Body,B_Approved,B_Kept,B_Status,B_Main,B_Sticky,B_Posted,B_Icon,B_Poll,B_Convert
     FROM  {$config['tbprefix']}Posts 
     WHERE B_Number = '$Number'
     AND   B_Board  = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);

// -------------------------
// Assign the retrieved data
   list($Postedby,$Subject,$Body,$Approved,$TKept,$TStatus,$Main,$Sticky,$Posted,$Icon,$Poll,$Convert) = $dbh -> fetch_array($sth); 

// ---------------------------------
// Check if they moderate this board
   $Username_q = addslashes($Username);
   $query = "
      SELECT Bo_Moderators,Bo_SpecialHeader,Bo_StyleSheet 
      FROM   {$config['tbprefix']}Boards
      WHERE  Bo_Keyword    = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);
   list($modlist,$fheader,$fstyle) = $dbh -> fetch_array($sth);
   if (stristr($modlist,",$Username,")) {
       $check = "true";
   }
   if ( (!check)  && ($Status != "Administrator")  ) {
      $html -> not_right($ubbt_lang['NO_EDIT'],$Cat);
   } 

// -------------------------------------------------
// Here we need to figure out what stylesheet to use
   $mystyle = $user['U_StyleSheet'];
   if (!$mystyle) { $mystyle = "usedefault"; }
   if ($mystyle == "usedefault") {
      $mystyle = $fstyle;
      if ($mystyle == "usedefault") {
         $mystyle = $theme['stylesheet'];
      }
   }
// fstyle will now be a global variable to use in send_header
   $fstyle = $mystyle;

// ------------------------------------------------
// Make the sure the edittime value has not expired
   $expired    = $config['edittime'] * 3600;
   $current    = $html -> get_date();
   if ( ($current - $Posted > $expired) && ( ($Status != "Administrator") && (!$check) ) ){
      $html -> not_right($ubbt_lang['EDITTIME'],$Cat);
   }

// -------------------------------------------------------------------------
// Well everything checked out, doesn't look like a hacker so let's let them
// edit the post. 
   $html -> send_header($ubbt_lang['PEDIT_HEAD'],$Cat,0,$user);

// --------------------
// Undo the markup code
   if ( ($Convert == "markup") || ($Convert == "both") ) {
      $Body = $html -> undo_markup($Body);
   }
// --------------------------
// Get rid of the line breaks
   $Body = str_replace("<br />","\n",$Body);
   $Body = str_replace("<br />","\n",$Body);


// -----------------
// Change " to &quot;
   $Subject = str_replace("\"","&quot;",$Subject);

// -----------------------------------------------------
// Let's find out if they get the default preview or not.
   $Preview = $user['U_Preview'];
   if (!$Preview) { $Preview = $config['Preview']; }
   if ( ($Preview == "on") || ($Preview == "1") ) {
      $PSelected = "checked=\"checked\"";
   }

   $iconselect = $html -> icon_select($Icon);
   $instant_ubbcode = $html -> instant_ubbcode(); 
   if ( $Username == $Postedby ) {
      $markeditselect = "<br /><br /><input type=\"checkbox\" value=\"1\" name=\"markedit\" checked=\"checked\" class=\"formboxes\" /> {$ubbt_lang['MARK_EDIT']}<br /><br />";
   }
   else {
      $markeditselect = "<input type=\"hidden\" value=\"1\" name=\"markedit\" class=\"formboxes\" />";
   }
   
   if ($Approved == "no") {
      $approvebutton = "<input type=\"submit\" name=\"peditapprove\" value=\"{$ubbt_lang['PEDIT_APPROVE']}\" class=\"buttons\" />";
   }
   else {
      $approvebutton = "&nbsp;";
   }
   if ( ( ($check) || ($user['U_Status'] == "Administrator") ) && ($Number == $Main) && ($Sticky) ) {
      $stickybutton = "<input type=\"submit\" name=\"retnorm\" value=\"{$ubbt_lang['RET_NORM']}\" class=\"buttons\" />";
   }
   elseif ( ( ( ($user['U_Status'] == "Moderator") && $check) || ($user['U_Status'] == "Administrator") ) && ($Number == $Main) && (!$Sticky) ) {
      $stickybutton = "<input type=\"submit\" name=\"makesticky\" value=\"{$ubbt_lang['MAKE_STICKY']}\" class=\"buttons\" />";
   }
   else {
      $stickybutton = "&nbsp;";
   }

// ---------------------------------------------------------------
// If there is a poll in this post, then they cannot edit the post
   if (!$Poll) {
		if (!$debug) {
      	include("$thispath/templates/$tempstyle/editpost_nopoll.tmpl");
		}
   }
   else {
		if (!$debug) {
      	include("$thispath/templates/$tempstyle/editpost_withpoll.tmpl");
		}
   }



   if ( ($user['U_Status'] == "Administrator") || ( ($user['U_Status'] == "Moderator") && $check ) ){
		if (!$debug) {
      	include("$thispath/templates/$tempstyle/editpost_manage.tmpl");
		}
      if ($Number != $Main) {
			if (!$debug) {
         	include("$thispath/templates/$tempstyle/editpost_manage_move.tmpl");
			}
      }
      if ($TStatus == "C") {
			if (!$debug) {
         	include("$thispath/templates/$tempstyle/editpost_manage_open.tmpl");
			}
      }
      else {
			if (!$debug) {
         	include("$thispath/templates/$tempstyle/editpost_manage_close.tmpl");
			}
      }

      if ($TKept == "K") {
			if (!$debug) {
         	include("$thispath/templates/$tempstyle/editpost_manage_unkeep.tmpl");
			}
      }
      else {
			if (!$debug) {
         	include("$thispath/templates/$tempstyle/editpost_manage_keep.tmpl");
			}
      }
 
		if (!$debug) { 
      	include("$thispath/templates/$tempstyle/editpost_manage_finish.tmpl");
		}
         
   }

   $html -> send_footer(); 
?>
