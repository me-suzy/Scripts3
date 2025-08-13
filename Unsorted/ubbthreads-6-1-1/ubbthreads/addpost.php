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
   require ("languages/${$config['cookieprefix']."w3t_language"}/addpost.php");

// Define the default language varialbe, required for PHP3
   $Language = ${$config['cookieprefix']."w3t_language"};

// Make sure this isn't being called via GET
   if ($GLOBALS['REQUEST_METHOD'] == "GET") {
      exit; 
   }


   $userob = new user;
   if ($Reged == "y") {
      $user = $userob -> authenticate("U_Username, U_Password,U_Signature,U_Picture,U_Groups,U_Display,U_Language,U_Totalposts,U_Title,U_Color,U_TempRead,U_TextCols,U_TextRows,U_EReplies,U_Preview,U_Number");
		$Username = $user['U_Username'];
      $Username_q = addslashes($Username); 
      $posterid = $user['U_Number'];
		$postername = $Username;
   }
   else {
      $Username_q = addslashes($postername);
      $posterid = "1";
   }

// -----------------
// Get the user info
   $IP = find_environmental('REMOTE_ADDR');  
   $bancheck = $Username;
   if (!$bancheck) { $bancheck = $IP; }

// See if they are banned
   $userob -> check_ban($bancheck);

  
   $html = new html;

// ------------------
// Check the referer
   if (!$config['disablerefer']) {
      $html -> check_refer($Cat);
   }

// --------------------------------------------------------------
// Let's make sure they are supposed to be looking at this board
   $whichperm = "Bo_Write_Perm";
   if ($Parent) {
     $whichperm = "Bo_Reply_Perm";
   }
   if (!$user['U_Groups']) { $user['U_Groups'] = "-4-"; }
   $Grouparray = split("-",$user['U_Groups']);
   $gsize = sizeof($Grouparray);
   $groupquery = "AND (";
   $g = 0;
   for ($i=0; $i<$gsize;$i++) {
      if (!preg_match("/[0-9]/",$Grouparray[$i])) { continue; };
      $g++;
      if ($g > 1) {
         $groupquery .= " OR ";
      }
      $groupquery .= "$whichperm LIKE '%-$Grouparray[$i]-%'";
   }
   $groupquery .= ")";  

// -------------------------------------
// Make sure there is a subject and body
   $Subject = rtrim($Subject);
   if ( (preg_match("/^\s*$/",$Subject)) || ($Body == "") || ($postername == "") ) {
      $html -> not_right($ubbt_lang['ALL_FIELDS'],$Cat);
   }

// ------------------------------------------------------
// Add a space to the end of $Body, for auto-url encoding
   $Body .= " ";

// -------------------------------------------------------------------
// If we are dealing with a registered user, then we get the info here 

// --------------------------
// Assign some default values
   $TextCols = $user['U_TextCols'];
   $TextRows = $user['U_TextRows'];
   if (!$TextCols) { $TextCols = $theme['TextCols']; }
   if (!$TextRows) { $TextRows = $theme['TextRows']; }

   $Email = $user['U_EReplies'];
   $Preview = $user['U_Preview'];
   if (!$Preview) { $Preview = $config['Preview']; }

   if ($Email == "On") {
      $Eselected = "checked=\"checked\"";
   }
   if ( ($Preview == "on") || ($Preview) ) {
      $Pselected = "checked=\"checked\"";
   }


// -----------------------------------------------------------------------
// If this is from an unregistered user, we need to make sure the username
// isn't registered, it doesn't contain any special characters, and that it
// is the proper length.  But not if we already previewed the post.  No
// need to do it twice.
   if (!$frompreview) {

      if ( ($Reged == "n") && ($postername != $ubbt_lang['ANON_TEXT']) ) {
         $query = "
            SELECT U_Username
            FROM   {$config['tbprefix']}Users
            WHERE  U_Username = '$Username_q'
				OR		 U_LoginName = '$Username_q'
         ";
         $sth = $dbh -> do_query($query);
         $Usercheck = $dbh -> fetch_array($sth);
         if ($Usercheck) {
            $html -> not_right($ubbt_lang['NAME_TAKEN'],$Cat);
         }

      // ----------------------------------
      // No html characters in the username
         if ( (strstr($postername,"&nbsp;")) || ( strstr($postername,"<")) || (strstr($postername,">")) || (preg_match("/^ /",$postername)) || (preg_match("/ $/",$postername)) ) { 
            $html -> not_right($ubbt_lang['BAD_UNAME'],$Cat);
         }
      // -----------------------------------------------------
      // Make sure the username is between 3 and 16 characters
         if ( (strlen($postername) < 3) || (strlen($postername) > 16) ) {
           $html -> not_right($ubbt_lang['LONG_NAME'],$Cat);
         }

      // -------------------------------------
      // Lets see if it is a reserved Username
         $badnames = @file("{$config['path']}/filters/badnames");
         if (!is_array($badnames)) {
            $badnames = @file("{$config['phpurl']}/filters/badnames");
         }
         while (list($linenum,$line) = each($badnames)) {
            if (preg_match("/^#/",$line)) {
               continue;
            }
            chop ($line);
            if (strtolower($postername) == strtolower($line)) {
               $html -> not_right($ubbt_lang['USER_EXISTS'],$Cat);
            }
         }
      } 
   }
   if (($Reged == "y") and (!$user['U_Username']) ) {
      $html -> not_right($ubbt_lang['NO_AUTH'],$Cat);
   }


// ------------------------------------
// Grab the necessary board information
   $Groups = $user['U_Groups'];
   if (!$Groups) { $Groups = "-4-"; }

// -------------------------------------
// Lets see if it is a reserved Username
   $badnames = @file ("{$config['path']}/filters/badnames");
   if (!is_array($badnames)) {
      $badnames = @file ("{$config['phpurl']}/filters/badnames");
   }
   while (list($linenum,$line) = each($badnames)) {
      if (preg_match("/^#/",$line)) {
         continue;
      }
      chop ($line);
      if (strtolower($Username) == strtolower($line)) {
         $html -> not_right($ubbt_lang['USER_EXISTS'],$Cat);
      }
   } 

   $Board_q = addslashes($Board);
   $query = "
      SELECT Bo_Title,
             Bo_HTML,
             Bo_Markup,
             Bo_Moderated,
             Bo_Number,
             Bo_Read_Perm,
	     Bo_Moderators,
             Bo_SpecialHeader,
	     Bo_StyleSheet
      FROM   {$config['tbprefix']}Boards
      WHERE  Bo_Keyword = '$Board_q'
      $groupquery
   ";
   $sth = $dbh -> do_query($query);
   list($Title,$HTML,$Markup,$Moderated,$BoNumber,$ReadPerm,$modlist,$fheader,$fstyle) = $dbh -> fetch_array($sth); 

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

// -------------------------------------------------------
// If we didn't get any results, then they can't post here 
   if (!$Title){
       $html -> not_right($ubbt_lang['READ_PERM'],$Cat);
   }

// -------------------------------------------------------------------------
// Lets see if this post is locked or not if we don't already have a PStatus
// Also find out if this is a Sticky thread.
	$Main = addslashes($Main);
   if (!$PStatus) {
      $PStatus = "O";
      if ($Parent) {
         $query = "
            SELECT B_Status, B_Sticky, B_Subject
            FROM   {$config['tbprefix']}Posts 
            WHERE  B_Number = '$Main'
         ";
         $sth = $dbh -> do_query($query);
         list($PStatus,$Sticky,$RealSubject) = $dbh -> fetch_array($sth); 
      }
   }

// ---------------------------------------------------------------------------
// If this thread is locked and they are not a admin or mod they can't proceed
   if ( ($PStatus == "C") && ($user['U_Status'] != "Administrator") && ($user['U_Status'] != "Moderator") ){
      $html -> not_right($ubbt_lang['LOCKED'],$Cat);
   }

// -----------------------------------------------------------
// If username is anonymous then we set the mail variable to 0
   if ($Reged == "n") { 
      $posterid = "1";
   }

// ------------------------------------
// Setup the subject and body variables
   $PrintSubject = $Subject;
   $PrintBody    = $Body;
   $FormSubject  = $Subject;
   $FormBody     = $Body;

// --------------------------------------
// Display certain & characters correctly
   $PrintSubject = str_replace("&","&amp;",$PrintSubject);
   $PrintBody = str_replace("&","&amp;",$PrintBody);

// ------------------------------------------------------------------
// Get rid of < and > in the subject because we don't want them using
// HTML in the subject line
   $PrintSubject = str_replace("<","&lt;",$PrintSubject);
   $PrintSubject = str_replace(">","&gt;",$PrintSubject);

// -------------------------------------------------------------
// If HTML is off then we need to get rid of < and > in the body
   if ( ($convert != "html") && ($convert !="both") ) {
      $PrintBody = str_replace("<","&lt;",$PrintBody);
      $PrintBody = str_replace(">","&gt;",$PrintBody);
   }
   else {
// No server side includes
      $PrintBody = preg_replace("/<!--(.|\n)*-->/","",$PrintBody);
   }

// --------------------------------
// Get rid of HTML in the signature
   $user['U_Signature'] = str_replace("&lt;br&gt;","<br />",$user['U_Signature']);

// -----------------------------
// Always markup the signature
   $printsig = $user['U_Signature'];

// -----------------------
// Add the sig to the post
   $PrintBody = "$PrintBody<br /><br />$printsig";

// ---------------------------------------------------
// Let's make sure they only have 1 poll in their post
   if (preg_match("/\[pollstart\](.*)\[pollstart\]/i",$PrintBody)) {
      $html -> not_right($ubbt_lang['TWO_POLLS'],$Cat);
   }

// -------------------------------------------
// If Markup is on the we format the markup code
   if ($Markup == "On" && ( ($convert == "markup") || ($convert == "both") ) ){
      $PrintBody = $html -> do_markup($PrintBody);
   }

// ---------------
// Add break tags
   $PrintBody = preg_replace("/(\r\n|\r|\n)/i","<br />",$PrintBody);

// -----------------------------------------------------
// Now if we are doing a preview of the post, we do this
   if ($preview) {
      $Extra = $Board ."_SEP_" .$Parent. "_SEP_" .$RealSubject; 
      $html -> send_header($ubbt_lang['PREV_POST'],$Cat,0,$user,$Extra,$ReadPerm);

      $FormSubject = $html -> form_encode($FormSubject);
      $FormBody    = $html -> form_encode($FormBody);
      $FormSubject = str_replace("&","&amp;",$FormSubject);
      $FormBody = str_replace("&","&amp;",$FormBody);


   // -----------------------------------------------------------------------
   // If we are allowing file attachments and we are dealing with a Mozilla 4+
   // Browser then we need a multipart/form-data form 
      if ( ($config['files']) && (ini_get(file_uploads)) ) {
         $template['form1'] = "<form method=\"post\" enctype='multipart/form-data' action=\"{$config['phpurl']}/addpost.php\">";
         $template['form2'] = "<form method=\"post\" enctype='multipart/form-data' action=\"{$config['phpurl']}/addpost.php\" name=\"replier\">";
      }
      else {
         $template['form1'] = "<form method=\"post\" action =\"{$config['phpurl']}/addpost.php\">";
         $template['form2'] = "<form method=\"post\" action =\"{$config['phpurl']}/addpost.php\" name=\"replier\">";
      }


   // -----------------------------------------------------------------------
   // Is there a poll in the post? If so, show them a preview of how it looks
      if ( ( ( ($user['U_Status'] == "Administrator") || ($user['U_Status'] == "Moderator") )  || ($config['allowpolls']) ) && (preg_match("/[pollstart]/",$PrintBody)) ){
			if ($convert == "both" || $convert == "markup") {
            $PrintBody = preg_replace("/\[pollstart\]/i","",$PrintBody);
            $PrintBody = preg_replace("/\[polltitle=(.*?)\]/i","<p><b>\\1</b><br />",$PrintBody);
            $PrintBody = preg_replace("/\[polloption=(.*?)\]/i","<INPUT TYPE=RADIO NAME=option VALUE=\"\\1\"> \\1",$PrintBody); 
            $PrintBody = preg_replace("/\[pollstop\]/i","<INPUT TYPE=Button NAME=Submit VALUE=\"{$ubbt_lang['SUB_VOTE']}\">",$PrintBody);
         }
      } 

   // ------------------------------------------------
   // If we are using icons then we need to preview it
      if (!$Icon) { $Icon = "book.gif"; }
      $iconurl = "<img src=\"{$config['images']}/icons/$Icon\" alt=\"*\" />";

   // -------------------------------------------------------------------------
   // If we are allowing file attachments and we are dealing with a Mozilla 4+
   // browser then we let them attach files.  Also we need to make sure they
   // are not posting as Anon.
      if(($config['files']) && ($Reged == "y")) {
         $template['attachfile'] = "<br /><br />{$ubbt_lang['YES_FILE2']}";
         $template['fileinput'] ="<input type=\"file\" name=\"userfile\" accept=\"*\" class=\"formboxes\" size=\"60\" />";
      } 

   // ----------------------------
   // Allow them to edit some more
      $Body = str_replace("&","&amp;",$Body);
      $Subject = str_replace("&","&amp;",$Subject);
      $Subject = str_replace("\"","&quot;",$Subject);
      $Body = str_replace("\"","&quot;",$Body);
      $Body = str_replace("<","&lt;",$Body);
      $Body = str_replace(">","&gt;",$Body);

   // --------------------------------------------------------
   // If we are using post icons then they can change the icon
      $template['useicons'] = $html -> icon_select($Icon);
      $instant_ubbcode = $html -> instant_ubbcode();

      if ($postername != $ubbt_lang['ANON_TEXT']) {
           $template['emailoption'] ="<input type=\"checkbox\" name=\"mail\" value=\"1\" $Eselected class=\"formboxes\" />{$ubbt_lang['DO_EMAIL']}<br /><br />";
      }

      if ( ($config['files']) && ($Reged == "y") ) {
         $template['canattach'] = " {$ubbt_lang['CAN_ATTACH']}";
      }

   // -----------------------------------
   // Include the addpost_preview template
		if (!$debug) {
      	include ("$thispath/templates/$tempstyle/addpost_preview.tmpl");
		}
      $html -> send_footer();
      exit;
   }



// ---------------------------------------------------------
// Now everything below here is for actually adding the post

// ---------------------------------------
// Unencode some of the special characters
   $Subject = $html -> form_decode($Subject);
   $Body    = $html -> form_decode($Body);

// --------------------------------------
// Display certain & characters correctly
   $Subject = str_replace("&","&amp;",$Subject);
   $Body = str_replace("&","&amp;",$Body);

// ------------------------------------------------------------------
// Get rid of < and > in the subject because we don't want them using
// HTML in the subject line
   $Subject = str_replace("<","&lt;",$Subject);
   $Subject = str_replace(">","&gt;",$Subject);

// -------------------------------------------------------------
// If HTML is off then we need to get rid of < and > in the body
   if ( ($convert != "html") && ($convert !="both") ) {
      $Body = str_replace("<","&lt;",$Body);
      $Body = str_replace(">","&gt;",$Body);
   }
   else {
// No server side includes
      $Body = preg_replace("/<!--(.|\n)*-->/","",$Body);
   }

// -------------------------------------------
// If Markup is on the we format the markup code
   if ( ($Markup == "On") && ( ($convert == "markup") || ($convert == "both") ) ){
      $Body = $html -> do_markup($Body);
   }

// ---------------
// Add break tags
   $Body = preg_replace("/(\r\n|\r|\n)/i","<br />",$Body);

// ---------------------------------------------------
// Let's make sure they only have 1 poll in their post
   if (preg_match("/\[pollstart\](.*)\[pollstart\]/i",$Body)) {
      $html -> not_right($ubbt_lang['TWO_POLLS'],$Cat);
   }


// --------------------------------------
// Let's see if we want this type of file
   if ( ($HTTP_POST_FILES['userfile']['name'] != "none") && ($HTTP_POST_FILES['userfile']['name']) ){
      if (preg_match("/\.(php|php3|php4|cgi|pl|exe|bat|reg)$/i",$HTTP_POST_FILES['userfile']['name'])) {
	$html -> not_right("{$ubbt_lang['FILESALLOWED']}: {$config['allowfiles']}",$Cat);
      }
      $checkfile = str_replace(",","|",$config['allowfiles']);
      if (!preg_match("/($checkfile)$/i",$HTTP_POST_FILES['userfile']['name'])) {
         $html -> not_right("{$ubbt_lang['FILESALLOWED']}: {$config['allowfiles']}",$Cat);
      }
   }
   if ( ($HTTP_POST_FILES['userfile']['size'] > $config['filesize']) ) {
      $html -> not_right($ubbt_lang['FILE_TOO_BIG'],$Cat);
   }

// -------------------------
// Check for real moderation
   if ($Moderated == 'no') {
      $Approved = "yes";
   } else {
      $Approved = "no";
   }
   if ($user['U_Status'] == "Administrator") {
      $Approved = "yes";
   }

// --------------------------------------------------------------------
// If they are in they are this boards moderator the post goes right in
   if ($user['U_Status'] == "Moderator") {
      if (preg_match("/(,|^)$Username(,|$)/i",$modlist)) {
         $Approved = "yes";
      }
   }

// ---------------------------------------------------------------------
// Substitute any filters/badwords with the $config[censored] variable
   $words[0] = "";
   if ($config['censored']) {
      $badwords = @file("{$config['path']}/filters/badwords");
      if (!is_array($badwords)) {
         $badwords = @file ("{$config['phpurl']}/filters/badwords");
      }
      while (list($linenum,$line) = each($badwords) ) {
         $line = chop($line);
         if ( (preg_match("/^\r/",$line)) || (preg_match("/^\n/",$line)) ) {
            continue; 
         }
         $islines = 1;
         $words[count($words)] = $line;
         // PHP4 ONLY
         // array_push ($words, $line);
      }
      if ($islines) {
         $badwords = join("|", $words);
         $badwords = preg_replace("/^\|/","",$badwords);
 
         $Subject = preg_replace("/\b($badwords)\b/i",$config['censored'],$Subject);
         $Body = preg_replace("/\b($badwords)\b/i",$config['censored'],$Body);
      }
   }

// -------------------------------------------------------------
// Is there a poll in the post?  If so, then we need to parse it
   if ( ( ( ($user['U_Status'] == "Administrator") || ($user['U_Status'] == "Moderator") )  || ($config['allowpolls']) ) && (preg_match("/\[pollstart\]/i",$Body)) ){
      if ($convert == "both" || $convert == "markup") {
         $polldate = $html -> get_date();
         $polloption = 1;
         $PollName = addslashes("$polldate{$user['U_Username']}");
   
         $piece['0'] = "";
         preg_match("/\[polltitle=(.*?)\]/i",$Body,$piece);
         $Body = preg_replace("/\[polltitle=(.*?)\]/i","<p><b>\\1</b>",$Body);
         $PollTitle = addslashes($piece['1']);
   
         preg_match_all("/\[polloption=(.*?)\]/i",$Body,$matches);
         for ($i=0; $i < count($matches['0']); $i++) {
            $Body = str_replace($matches['0'][$i],"<input type=\"radio\" name=\"option\" value=\"$polloption\" />" .$matches['1'][$i],$Body); 
            $optiontitle = addslashes($matches['1'][$i]);
            $query = "
              INSERT INTO {$config['tbprefix']}Polls
              (P_Name,P_Title,P_Number,P_Option)
              VALUES ('$PollName','$PollTitle','$polloption','$optiontitle')
            ";
            $dbh -> do_query($query);
            $polloption++;
         }
         $Body = preg_replace("/\[pollstop\]/i","<INPUT TYPE=Submit NAME=Submit VALUE=\"{$ubbt_lang['SUB_VOTE']}\" class=\"buttons\"></form>",$Body);
         $Body = preg_replace("/\[pollstart\]/i","<FORM METHOD=POST ACTION=\"{$config['phpurl']}/dopoll.php\"><INPUT TYPE=HIDDEN NAME=\"pollname\" VALUE=\"$polldate{$user['U_Username']}\">",$Body);
      } 
   }


// -------------------------  
// Quote everything properly 
   if (!$PStatus) { $PStatus = "O"; }
   if (!$replyto) { $replyto = " "; }
   $BodySig    = $Body;
   $BodySig    = addslashes($BodySig);
   $Subject_q  = addslashes($Subject);
   $Username_q = addslashes($postername);
   $Kept_q     = " ";
   $PStatus_q  = addslashes($PStatus);
   $Approved_q = addslashes($Approved);
   $Picture    = $user['U_Picture'];
   if (!$Picture) { $Picture = "http://"; }
   $Picture_q  = addslashes($Picture);
   $Icon_q     = addslashes($Icon);
   $Reged_q    = addslashes($Reged);
   $replyto_q  = addslashes($replyto);
   $convert_q  = addslashes($convert);

// ----------------------------------------------------------------
// Now we need to see if this post has already been made, basically
// protects from spamming
   $query = "
      SELECT B_Number
      FROM   {$config['tbprefix']}Posts 
      WHERE  B_Username = '$Username_q' 
      AND    B_Subject  = '$Subject_q' 
      AND    B_Body     = '$BodySig'
      AND    B_Board    = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);
   list($B_Number) = $dbh -> fetch_array($sth);
   if ($B_Number) {
      $html -> not_right($ubbt_lang['NO_DUPS'],$Cat);
   } 

// --------------------
// Get the current time
   $date = $html -> get_date();

// --------------------------------------------------
// Set the default UserTitle variable to Unregistered
   $UserTitle_q = addslashes($ubbt_lang['UNREGED_USER']);

// ------------------------------------------------------------------------
// As long as the User didn't post Anonymously we need to update some stuff
   if ($Reged == "y") {

      $Username_q = addslashes($postername);

   // -----------------------------------------------------------
   // Update their entry that tracks the last post they have read
      $BoardLast = $Board."Last";    
      $query = "      
         UPDATE {$config['tbprefix']}Last 
         SET    L_Last     = '$date'
         WHERE  L_Username = '$Username_q'    
         AND    L_Board    = '$Board_q'
      ";    
      $dbh -> do_query($query);

   // ------------------------
   // Up their totalposts by 1 
      $Totalposts = $user['U_Totalposts'] + 1;
      $CurrTitle  = $user['U_Title'];
      $Color         = $user['U_Color'];
      if ($user['U_Status'] == "Administrator") {
         $UserStatus = "A";
      }
      elseif ($user['U_Status'] == "Moderator") {
         if (preg_match("/(,|^)$Username(,|$)/i",$modlist)) {
            $UserStatus = "M";
         }
      }

   // -------------------------------------------------------
   // Now we check and see if they get a new title but only if
   // they don't have a custom title
      $CurrTitle = str_replace("\n","",$CurrTitle);
      $NewTitle    = $CurrTitle;
      $UserTitle_q = addslashes($CurrTitle);

      $titles = @file("{$config['path']}/filters/usertitles");
      if (!is_array($titles)) {
         $titles = @file("{$config['phpurl']}/filters/usertitles");
      }
      while (list($linenum,$line) = each($titles) ) {
         list ($posts,$title) = split("\t",$line);
         $title = chop ($title);
         $title = str_replace("\n","",$title);
         $CurrTitle = chop ($CurrTitle);
         if ($CurrTitle == $title) {
            $okay = 1;
         }
         elseif ($Totalposts == $posts) {
             $NewTitle = $title;
             break; 
          }
      }
      if ( ($okay) && ($NewTitle != $CurrTitle) ){
         $UserTitle_q = addslashes($NewTitle);   
      }

   // --------------------------------------
   // Now update some stuff in their profile 
      $query = "
        UPDATE {$config['tbprefix']}Users
        SET    U_Totalposts = U_Totalposts + 1,
               U_Laston     = '$date',
               U_Title      = '$UserTitle_q'
        WHERE  U_Username   = '$Username_q'
      ";
      $dbh -> do_query($query);
   }

// -----------------------------------------------------
// If this isn't a reply post then $Parent gets set to 0
   if (!$Parent) { $Parent = 0; }

// ---------------------------------
// Insert the post into the database
   $IP_q = addslashes($IP);
   $MainTopic = "0";

// -------------------------------------------------
// If This is a main post we need to set Topic to 1
   if (!$Main) { 
      $Main = 0; 
      $MainTopic = 1;
   }
   if (!$Color) { $Color = " "; }
   $Color_q = addslashes($Color);

// ---------------------------------
// INSERT THE POST INTO THE DATABASE
   $query = "
         INSERT INTO {$config['tbprefix']}Posts (B_Board,B_Parent,B_Main,B_Posted,B_Last_Post,B_Username,B_IP,B_Subject,B_Body,B_Mail,B_Kept,B_Status,B_Approved,B_Icon,B_Reged,B_Poll,B_ParentUser,B_Replies,B_Topic,B_Convert,B_PosterId,B_Color,B_UStatus)
         VALUES ('$Board_q','$Parent','$Main','$date','$date','$Username_q','$IP_q','$Subject_q','$BodySig','$mail','$Kept_q','$PStatus_q','$Approved_q','$Icon_q','$Reged_q','$PollName','$replyto_q','0',$MainTopic,'$convert_q',$posterid,'$Color_q','$UserStatus')
   ";
   $dbh -> do_query($query);

// ------------------------------------------------------------------
// Now we need to find out what the number of the post we entered was
   $query = "
      SELECT B_Number
      FROM   {$config['tbprefix']}Posts 
      WHERE  B_Username= '$Username_q'
      AND    B_Subject = '$Subject_q'
      AND    B_Body    = '$BodySig'
      AND    B_Board   = '$Board_q'
   ";
   $sth = $dbh ->do_query($query);
   list($Mnumber) = $dbh -> fetch_array($sth);  
 

// -------------------------------------------------------------------
// Now we need to update the Last_Post on the first post of this thread
// But only if it isn't sticky (always on top)
   if ( ($Main) && (!$Sticky) ) {

   // ---------------------------------------------------
   // We only update the replies if this post is approved
      if ($Approved == "yes") {
         $updatereplies = ", B_Replies = B_Replies + 1";
      }
      $query = "
        UPDATE {$config['tbprefix']}Posts 
        SET    B_Last_Post = '$date'
               $updatereplies
        WHERE  B_Main      = '$Main'
        AND    B_Board     = '$Board_q'
      ";
      $dbh -> do_query($query);
   }

// -------------------------------------------------------
// Otherwise just update the reply total if it is approved
   elseif ( ($Main) && ($Sticky) && ($Approved == "yes") ) {
      $query = "
        UPDATE {$config['tbprefix']}Posts
        SET    B_Replies = B_Replies + 1
        WHERE  B_Number    = '$Main'
        AND    B_Board   = '$Board_q'
      ";
      $dbh -> do_query($query);
   }

// --------------------------------------------------
// If we have a file attachment then we handle it here
   if ( ($HTTP_POST_FILES['userfile']['name'] != "none") && ($HTTP_POST_FILES['userfile']['name']) ){


      $FileName = "$Mnumber-{$HTTP_POST_FILES['userfile']['name']}";
      move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'], "{$config['files']}/$FileName");

   // ------------------------------------
   // Update the post to set the file name
      $Filename_q = addslashes($FileName);
      $query = "
        UPDATE {$config['tbprefix']}Posts 
        SET    B_File = '$Filename_q'
        WHERE  B_Number = '$Mnumber'
        AND    B_Board  = '$Board_q'
      ";
      $dbh -> do_query($query);
   }

// -------------------------------------------------------
// IF main isn't set then we need to set main to the number
// And designate this as a main topic
   if(!$Main) {
      $query = "
        UPDATE {$config['tbprefix']}Posts 
        SET    B_Main   = '$Mnumber'
        WHERE  B_Number = '$Mnumber'
      ";
      $dbh -> do_query($query);
   }

// -----------------------------------------------------------
// If this is a reply then we need to check it's parent and see
// if it should be mailed to the starting post's owner
   if ($Parent) {
      $query = "
        SELECT B_Username,
               B_Subject,
               B_Mail
        FROM   {$config['tbprefix']}Posts 
        WHERE  B_Number = '$Parent'
      ";
      $sth = $dbh -> do_query($query);
      list($MailUser,$PostSubject,$Checkmail) = $dbh -> fetch_array($sth); 

   // --------------------------------------------------------------------
   // If Mail was set then we send an email to that user and use the right
   // language that they specified in their profile
      if ( ($Approved == "yes") && ($Checkmail == 1) ) {

         $MailUser_q = addslashes($MailUser);

         $query = "
           SELECT U_Email,
                  U_Language,
						U_EmailFormat
           FROM   {$config['tbprefix']}Users
           WHERE  U_Username = '$MailUser_q'
         ";
         $sth = $dbh -> do_query($query);
         list($Mailto,$Language,$emailformat) = $dbh -> fetch_array($sth); 

      // -------------------------------------------
      // We need to make sure this user still exists
         if ($Mailto){

         // -----------------------------------------------------------------
         // Now if this user has a pre-selected language we use that language
         // for the message we send out
            if (!$Language) { $Language = $config['language'];}
            require "{$config['path']}/languages/$Language/addpost.php";

            $EmailBody = $Body;
			// If we are sending HTML email then we need the full path
			// to images.
            $newline = "\n";
            if (stristr(PHP_OS,"win")) {
               $newline = "\r\n";
            }
				if ($emailformat == "HTML") {
					$EmailBody = str_replace("\"{$config['images']}","\"{$config['imageurl']}",$EmailBody);
            	$msg     = "$postername {$ubbt_lang['REPLY_BOD']}<br><br><a href=\"{$config['phpurl']}/showthreaded.php?Cat=$Cat&Board=$Board&Number=$Mnumber\">{$config['phpurl']}/showthreaded.php?Cat=$Cat&Board=$Board&Number=$Mnumber</a><br><br>$EmailBody";
				}
				else {
            	$EmailBody = str_replace("<br />","\n",$EmailBody);
            	$msg     = "$postername {$ubbt_lang['REPLY_BOD']}$newline{$config['phpurl']}/showthreaded.php?Cat=$Cat&Board=$Board&Number=$Mnumber$newline$newline $EmailBody";
				}


            $to = $Mailto;
            $mailer = new mailer;
            $header = $mailer -> headers($emailformat); 
            $subject = $ubbt_lang['REPLY_SUB'];

            mail("$to","$subject",$msg,$header);

         // --------------------------------------------------
         // Now, we need to switch back to this users language
            $Language = $user['U_Language'];
            if (!$Language) { $Language = $config['language']; }
            require "{$config['path']}/languages/$Language/addpost.php";

         }
      }
   }


// ---------------------------------------------
// Update the total post if the post is Approved
   if ($Approved == "yes") {

   // -------------------------------------------
   // If main isn't set then this is a new thread
      if (!$Main) {
         $extra = ",Bo_Threads = Bo_Threads + 1";
         $Main = $Mnumber;
      }
      $Board_q = addslashes($Board);
      $query = "
        UPDATE {$config['tbprefix']}Boards 
        SET    Bo_Total   = Bo_Total + 1,
               Bo_Last    = $date,
               Bo_Poster  = '$Username_q',
	       Bo_LastNumber = '$Mnumber',
	       Bo_LastMain   = '$Main'
        $extra
        WHERE  Bo_Keyword = '$Board_q'
      ";
      $dbh -> do_query($query);
   }

// Now if there was a reminder set for this thread, we can remove the 
// reminder for this user
// Also, we need to update the activity flag for any favorites set for
// this thread
   if ($Main) {
      $query = " 
         DELETE FROM {$config['tbprefix']}Favorites
         WHERE  F_Thread = '$Parent'
         AND    F_Owner  = '$Username_q'
         AND    F_Type   = 'r'
      "; 
      $dbh -> do_query($query);
   }

// ----------------------
// Mark this post as read
   $read  = $user['U_TempRead'];
   $adder = ",$Mnumber,";
   $read     = $read.$adder;
   if ($Username_q) {
      $read_q = addslashes($read);
      $query = "
        UPDATE {$config['tbprefix']}Users
        SET    U_TempRead = '$read_q'
        WHERE  U_Username = '$Username_q'
      ";
      $dbh -> do_query($query);
   }

// -----------------------------
// Figure out where to send them
   if ($config['dateslip']) {
      $page = 0;
   }
   if( ($what == "showthreaded") || ($what == "showflat")) {

      $html -> send_header("{$ubbt_lang['POST_ENTERED']}",$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/$what.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Mnumber&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$fpart&amp;vc=$vc\" />",$user);

   } else {

      $html -> send_header("{$ubbt_lang['POST_ENTERED']}",$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\" />",$user);


   }


// ------------------------------------
// Now we give them a confirmation page
   if ($Approved == "no") {
		if (!$debug) {
      	include ("$thispath/templates/$tempstyle/addpost_moderated.tmpl");
		}
   }
   else {

      if (!$what) {
        $what = $user['U_Display'];
        if (!$what) { $what = $theme['postlist']; }
        $what = "show$what";
      }
		if (!$debug) {
      	include ("$thispath/templates/$tempstyle/addpost_confirm.tmpl");
		}
   }

   $html -> send_footer();
