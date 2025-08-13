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
   require ("languages/${$config['cookieprefix']."w3t_language"}/newreply.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_EReplies,U_TextCols,U_TextRows,U_Preview,U_Groups");
   $Username = $user['U_Username'];

   if (!${$config['cookieprefix']."w3t_language"}) {
      ${$config['cookieprefix']."w3t_language"}= $config['language'];
   }

// ------------------
// Check for any bans
   $html = new html;
   $userob -> check_ban($user['U_Username'],$Cat);

// ------------------
// Let's get the groups 
   if (!$user['U_Groups']) {
      $user['U_Groups'] = "-4-";
   }

// -----------------------------------------------------
// Let's find out if they get the default preview or not.
   $Preview = $user['U_Preview'];
   if (!$Preview) {
      $Preview = $config['preview'];
   }

   if ($user['U_EReplies'] == "On"){
      $Eselected = "checked=\"checked\"";
   }
   if ( ($Preview == 1) || ($Preview == "on") ){
      $Pselected = "checked=\"checked\"";
   }

   $TextCols = $user['U_TextCols'];
   $TextRows = $user['U_TextRows'];
   if (!$TextCols) { $TextCols = $theme['TextCols']; }
   if (!$TextRows) { $TextRows = $theme['TextRows']; }

// -----------------
// Get the post info
   $Board_q = addslashes($Board);
	$Number = addslashes($Number);
   $query = " 
      SELECT B_Username,B_Main,B_Subject,B_Body,B_Approved
      FROM  {$config['tbprefix']}Posts 
      WHERE B_Number = '$Number'
      AND   B_Board  = '$Board_q'
   "; 
   $sth = $dbh -> do_query($query);

// ---------------
// AAssign the stuff
   list($ResUsername,$Main,$Subject,$Body,$Approved) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);
	if (!$Subject) {
		exit;
	}


// -----------------------------------------------------
// Make sure we only put one Re: in front of the subject
   if (!preg_match("/^Re:/",$Subject)){
      $Subject = "Re: ".$Subject;
   }

// ----------------------
// Convert "'s to &quot;
   $Subject = str_replace("\"","&quot;",$Subject);
   $Body    = str_replace("\"","&quot;",$Body);

// --------------------------------------------------------------
// Let's make sure they are supposed to be making replies here 
   $Grouparray = split("-",$user['U_Groups']);
   $gsize = sizeof($Grouparray);
   $groupquery = "AND (";
   $g = 0;
   for ($i=0; $i<=$gsize;$i++) {
      if (!preg_match("/[0-9]/",$Grouparray[$i])) { continue; };
      $g++;
      if ($g > 1) {
         $groupquery .= " OR ";
      }
      $groupquery .= "Bo_Reply_Perm LIKE '%-$Grouparray[$i]-%'";
   }
   $groupquery .= ")"; 

// ------------------
// Get the board info
   $query =  "
     SELECT Bo_Title,Bo_HTML,Bo_Markup,Bo_Read_Perm,Bo_SpecialHeader,Bo_StyleSheet
     FROM  {$config['tbprefix']}Boards
     WHERE Bo_Keyword = '$Board_q'
     $groupquery
  ";
   $sth = $dbh -> do_query($query);

// ----------------
// Assign the stuff  
   list($Title,$HTML,$Markup,$ReadPerm,$fheader,$fstyle) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

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

// ----------------------------------------------------------
// Find out if they are supposed to be replying on this board
   if (!$Title) {
      $html -> not_right($ubbt_lang['READ_PERM'],$Cat);
   }
   if (${$config['cookieprefix']."ubbt_pass"} == "invalid") {
      if (!$config['under13']) {
         $html -> not_right($ubbt_lang['UNDER13'],$Cat);
      } else {
        $html -> not_right($ubbt_lang['NO_COPPA'],$Cat);
      }
   }

// ------------------------------------------------------
// IF This post isn't approved yet, you can't reply to it
   if ($Approved == "no") {
      $html -> not_right($ubbt_lang['NOT_APP'],$Cat);
   }

// ---------------
// Send the header
   $Extra = $Board . "_SEP_" .$Number. "_SEP_" .$Subject;
   $html -> send_header("{$ubbt_lang['REPLY_HEAD']} ($Title)",$Cat,0,$user,$Extra,$ReadPerm);

// -------------------------------
// Check if HTML is enabled or not
   if($HTML == "Off") {
      $htmlstatus = " {$ubbt_lang['NO_HTML']}";
   }
   else {
      $htmlstatus = " {$ubbt_lang['YES_HTML']}";
   }

// --------------------------------------------
// Markup is disabled, so we better let them know
   if($Markup == "Off"){
      $markupstatus = " {$ubbt_lang['NO_MARKUP']}";
   } 
   else {
      $markupstatus = " {$ubbt_lang['YES_MARKUP']} <a target=\"_new\" href=\"{$config['phpurl']}/faq_${$config['cookieprefix']."w3t_language"}.php?Cat=$Cat#html\">{$ubbt_lang['MARK_POSTS']}</a>.";
   }

// -------------------------------------------------------------------------
// If we are allowing anonymous users to choose a name to post under, we let
// them know here.
   if ( ($config['anonnames']) && (!$Username) ) {
      $choosename = " {$ubbt_lang['CHOOSE_NAME']}";
   }

// ----------------------------------------------------------------------
// If The Guest group can post here then we set the Username to Anonymous
// and we set the reged flag to "n";
   $Reged = 'y';
   if (!$Username) { 
      $postername = $ubbt_lang['ANON_TEXT']; 
      $Reged    = 'n';
   }
   else {
      $postername = $user['U_Username'];
   }

   if ( ($postername == $ubbt_lang['ANON_TEXT']) && ($config['anonnames']) ) {
      $postname = "<input type=\"text\" name=\"postername\" value=\"$postername\" class=\"formboxes\" />";
   }
   else {
      $postname = "<b>$postername</b>";
      $postname .= "<input type=\"hidden\" name=\"postername\" value=\"$postername\" />";
   }


   $iconselect = $html -> icon_select();
   $instant_ubbcode = $html -> instant_ubbcode();

// -------------------------------------
// What options do they have for posting
   if ( ($config['markupoption'] == 1) || ($user['U_Status'] == "Administrator") || ($user['U_Status'] == "Moderator") ) {
      $markupselect = "{$ubbt_lang['MAKE_POST']}<br />";
      $markupselect .= "<select name=\"convert\" class=\"formboxes\">";
      if ($Markup == "On") {
         $markupselect .= "<option value=\"markup\" selected=\"selected\">{$ubbt_lang['USE_MARKUP']}</option>";
      }
      if ( ($HTML == "On") || ($user['U_Status'] == "Administrator") || ($user['U_Status'] == "Moderator") ){
         $markupselect .= "<option value=\"html\">{$ubbt_lang['USE_HTML']}</option>";
      }
      if ( ( ($HTML == "On") && ($Markup == "On") ) || ( ($user['U_Status'] == "Administrator") || ($user['U_Status'] == "Moderator") ) ) {
         $markupselect .= "<option value=\"both\">{$ubbt_lang['USE_BOTH']}</option>";
      }
      if ( ($HTML == "Off") && ($user['U_Status'] != "Administrator") && ($user['U_Status'] != "Moderator") ) {
         $markupselect .= "<option value=\"none\">{$ubbt_lang['USE_NONE']}</option>";
      }
      else {
         $markupselect .= "<option value=\"none\">{$ubbt_lang['USE_NONE']}</option>";
      }
      $markupselect .=  "</select><br /><br />";
   }
// ------------------------------------
// No options, we use the board default
   else {
      if ( ($HTML == "Off") && ($Markup == "On") ) {
         $markupselect = "<input type=\"hidden\" name=\"convert\" value=\"markup\" />";
      }
      elseif ( ($HTML == "On") && ($Markup == "On") ) {
         $markupselect ="<input type=\"hidden\" name=\"convert\" value=\"both\" />";
      }
      elseif ( ($HTML == "Off") && ($Markup == "Off") ) {
         $markupselect ="<input type=\"hidden\" name=\"convert\" value=\"none\" />";
      }
      else {
         $markupselect = "<input type=\"hidden\" name=\"convert\" value=\"html\" />";
      }
   }

   if ($postername != $ubbt_lang['ANON_TEXT']) {
      $emailselect = "
         <input type=\"checkbox\" name=\"mail\" value=\"1\" $Eselected class=\"formboxes\" /> 
         {$ubbt_lang['DO_EMAIL']}
         <br /><br />
      "; 
   }

   $formmethod = "<form method=\"post\" action=\"{$config['phpurl']}/addpost.php\" name=\"replier\">";
   if ( ($config['files']) && ($Reged == "y") && (ini_get(file_uploads)) ) {
      $canattach = " {$ubbt_lang['CAN_ATTACH']}";
      $formmethod = "<form method=\"post\" enctype='multipart/form-data' action=\"{$config['phpurl']}/addpost.php\" name=\"replier\">";

   }

   $Body = str_replace("&quot;","\"",$Body);

	if (!$debug) {
   	include("$thispath/templates/$tempstyle/newreply.tmpl");
	}
   $html -> send_footer();

?>
