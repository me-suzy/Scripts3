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
   require ("languages/${$config['cookieprefix']."w3t_language"}/modifypost.php");


// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_TextCols,U_TextRows");
   $Username = $user['U_Username'];
   $html = new html;

// --------------------------
// Grab the board information
   $Board_q = addslashes($Board);
   $query = "
     SELECT Bo_HTML,Bo_Markup,Bo_SpecialHeader,Bo_StyleSheet
     FROM   {$config['tbprefix']}Boards
     WHERE  Bo_Keyword = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);
   list($HTML,$Markup,$fheader,$fstyle) = $dbh -> fetch_array($sth); 
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

// ------------------------------------------
// IF we are changing this post do this block
	$Number = addslashes($Number);
	if ( ($peditchange ) && ($preview) ) {

	// --------------------------------------
	// Let's see if we have a file attachment
		$query = "
			SELECT B_File
			FROM {$config['tbprefix']}Posts
			WHERE B_Number='$Number'
		";
		$sth = $dbh -> do_query($query);
		list($filename) = $dbh -> fetch_array($sth);

	// ----------------------------------------------------------------
	// If we are allowing files and we have a file attached to this post
	// then we need a multipart form handler.  We also need to setup the
	// text/option to delete/overwrite the attachment.
		if ($filename) {
			$attachhtml = "
{$ubbt_lang['HASATTACH']}<br />
<input type=\"checkbox\" name=\"deleteattach\" class=\"formboxes\" /> {$ubbt_lang['DELETEATTACH']}
<br /><br />
			";
		}
		else {
			$attachhtml = "{$ubbt_lang['NO_ATTACH']}<br />";
		}

      if ( ($config['files']) && (ini_get(file_uploads)) )  {
         $formhandler = "<form method=\"post\" enctype='multipart/form-data' action=\"{$config['phpurl']}/modifypost.php\">";
			$attachhtml .= "
<input type=file name=\"userfile\" accept=\"*\" class=\"formboxes\" size=\"60\" />
<br /><br />
			";
		}
      else {
         $formhandler = "<form method=\"post\" action =\"{$config['phpurl']}/modifypost.php\">";
      }


      if (!$user['U_Username']) {
         $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
      }

   // --------------------------
   // Assign some default values
      $TextCols = $user['U_TextCols'];
      $TextRows = $user['U_TextRows'];
      if (!$TextCols) { $TextCols = $theme['TextCols']; }
      if (!$TextRows) { $TextRows = $theme['TextRows']; }
 

   // ------------------------------
   // Assign the variables we need
      $Body = str_replace("&quot;","\"",$Body);
      $PrintBody    = $Body;
      $PrintSubject = $Subject;
      $FormBody     = $Body;
      $FormSubject  = $Subject;

   // -------------------------------------
   // Make sure there is a subject and body
      $Subject = rtrim($Subject);
      if ( (preg_match("/^\s*$/",$Subject)) || ($Body == "") ) {
         $html -> not_right($ubbt_lang['ALL_FIELDS'],$Cat);
      }

   // --------------------------------------
   // Display certain & characters correctly
      $PrintSubject = str_replace("&","&amp;",$PrintSubject);
      $PrintBody = str_replace("&","&amp;",$PrintBody);

   // ---------------------------------
   // Get rid of < and > in the subject
      $PrintSubject = str_replace("<","&lt;",$PrintSubject);
      $PrintSubject = str_replace(">","&gt;",$PrintSubject);

   // -----------------------------------------------------
   // If HTML is off then we get rid of < and > in the body
      if ( ($convert != "html") && ($convert != "both") ){
         $PrintBody = str_replace("<","&lt;",$PrintBody);
         $PrintBody = str_replace(">","&gt;",$PrintBody);
      }
      else {
         $PrintBody = preg_replace("/<!--(.|\n)*-->/","",$PrintBody);
      }

   // If Markup is on then convert it
      if ( ($Markup == "On") && ( ($convert == "markup") || ($convert == "both") ) ) {
         $PrintBody = $html -> do_markup($PrintBody);
      }

	   $PrintBody = preg_replace("/(\r\n|\r|\n)/i","<br />",$PrintBody);


   // ---------------------------
   // Now we send them their page
      $html -> send_header($ubbt_lang['PREV_MODS'],$Cat,0,$user);
 
      $FormSubject = $html -> form_encode($FormSubject);
      $FormBody    = $html -> form_encode($FormBody);
      $FormSubject = str_replace("&","&amp;",$FormSubject);
      $FormBody = str_replace("&","&amp;",$FormBody);

   // ----------------------------
   // Allow them to edit some more
      $Body = str_replace("&","&amp;",$Body);
      $Subject = str_replace("&","&amp;",$Subject);
      $Body = str_replace("\"","&quot;",$Body);
      $Subject = str_replace("\"","&quot;",$Subject);
      $Body = str_replace("<","&lt;",$Body);

		if (!$debug) {
      	include("$thispath/templates/$tempstyle/previewedit_preview.tmpl");
		}
      $html -> send_footer();
		exit();

   } 
// -------------------------------------------------
// Otherwise we are deleting the post so do this sub
   elseif ($peditdelete) {

      if (!$user['U_Username']) {
         $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
      }

   // --------------------
   // Send them their page 
      $html -> send_header($ubbt_lang['PEDIT_DELETE'],$Cat,0,$user);
		if (!$debug) {
      	include("$thispath/templates/$tempstyle/previewedit_delete.tmpl");
		}
      $html -> send_footer();
		exit();
   }

// -------------------------------------------------
// Otherwise we are approving the post so do this sub
   elseif ($peditapprove) {

      if (!$user['U_Username'] ) {
         $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
      }

   // --------------------
   // Send them their page 
      $html -> send_header($ubbt_lang['PEDIT_APPROVE'],$Cat,0,$user);
		if (!$debug) {
      	include("$thispath/templates/$tempstyle/previewedit_approve.tmpl");
		}
      $html -> send_footer();
		exit();

   }

// ----------------------------------------
// Otherwise we are making this post sticky 
   elseif ($makesticky) {

      if (!$user['U_Username'] ) {
         $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
      }

   // --------------------
   // Send them their page 
      $html -> send_header($ubbt_lang['MAKE_STICKY'],$Cat,0,$user);
		if (!$debug) {
      	include("$thispath/templates/$tempstyle/previewedit_makesticky.tmpl");
		}
      $html -> send_footer();
		exit();

   }

// --------------------------------------------
// Otherwise we are returning to a normal date 
   elseif ($retnorm) {
   
      if (!$user['U_Username'] ) {
         $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
       }

   // --------------------
   // Send them their page 
      $html -> send_header($ubbt_lang['RET_NORM'],$Cat,0,$user);
		if (!$debug) {
      	include("$thispath/templates/$tempstyle/previewedit_makeunsticky.tmpl");
		}
      $html -> send_footer();
		exit();

   }

// ---------------------------------------------------------------------------
// For security purposes we need to verify that this is user made this post or
// if they are an admin or a moderator for this board.
   $Status = $user['U_Status'];

// -----------------------------------
// Get the post info from the database
   $Board_q = addslashes($Board);
   $query = "
     SELECT B_Username,B_File
     FROM  {$config['tbprefix']}Posts
     WHERE B_Number = '$Number'
     AND   B_Board  = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);     

// -------------------------
// Assign the retrieved data
   list($Postedby,$file) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// ---------------------------------
// Check if they moderate this board
   $Username_q = addslashes($Username);
   $query = "
      SELECT Mod_Board
      FROM   {$config['tbprefix']}Moderators
      WHERE  Mod_Username = '$Username_q'
      AND    Mod_Board    = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);
   list($check) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if ( (strtolower($Username) != strtolower($Postedby)) && ($Status != "Administrator") && (!$check) ) {
      $html -> not_right($ubbt_lang['NO_EDIT'],$Cat);
   }                                                

// -------------------------------------
// Make sure there is a subject and body
   if ( (preg_match("/^\s*$/",$Subject)) || ($Body == "") ) {
      $html -> not_right($ubbt_lang['ALL_FIELDS'],$Cat);
   }

// --------------------------
// Grab the board information
   $Board_q = addslashes($Board);

   $query = "
    SELECT Bo_HTML,Bo_Markup,Bo_SpecialHeader,Bo_StyleSheet
    FROM   {$config['tbprefix']}Boards
    WHERE  Bo_Keyword = '$Board_q'
   ";
   $sth = $dbh -> do_query($query);
   list($HTML,$Markup,$fheader,$fstyle) = $dbh -> fetch_array($sth); 
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

// ----------------------------------------
// Unencode some of the special characters
   $Subject = $html -> form_decode($Subject);
   $Body    = $html -> form_decode($Body);

// --------------------------------------
// Display certain & characters correctly
   $Body = str_replace("&quot;","\"",$Body);
   $Subject = str_replace("&quot;","\"",$Subject);
   $Subject = str_replace("&","&amp;",$Subject);
   $Body = str_replace("&","&amp;",$Body);

// ---------------------------------
// get rid of < and > in the subject
   $Subject = str_replace("<","&lt;",$Subject);
   $Subject = str_replace(">","&gt;",$Subject);

// -------------------------------------------------------------
// If HTML is off then we need to get rid of < and > in the body
   if ( ($convert != "html") && ($convert != "both") ) {
         $Body = str_replace("<","&lt;",$Body);
         $Body = str_replace(">","&gt;",$Body);
   }
   else {
      $Body = preg_replace("/<!--(.|\n)*-->/","",$Body);
   }

   if ( ($Markup == "On") && ( ($convert == "markup") || ($convert == "both") ) ) {
      $Body = $html -> do_markup($Body);
   }

   $Body = preg_replace("/(\r\n|\r|\n)/i","<br />",$Body);


// -----------------------------
// Are we marking this as edited
   $editdate = "";
   if ( $markedit ) {
      $editdate = $html -> get_date();
   } 

// --------------------------------------
// Let's see if we want this type of file
   if ( ($HTTP_POST_FILES['userfile']['name'] != "none") && ($HTTP_POST_FILES['userfile']['name'])){
      if (preg_match("/(.php|.php3|.php4|.cgi|.pl|exe|.bat|.reg)$/i",$HTTP_POST_FILES['userfile']['name'])) {
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

// --------------------------------------------------
// If we have a file attachment then we handle it here
   if ( ($HTTP_POST_FILES['userfile']['name'] != "none") && ($HTTP_POST_FILES['userfile']['name'])
 ){
      $FileName = "$Number-{$HTTP_POST_FILES['userfile']['name']}";
      move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'], "{$config['files']}/$FileName");
		$FileName = addslashes($FileName);
		$extra = ",B_File = '$FileName'";
   	if ($file) {
      	unlink("{$config['files']}/$file");
   	}
	}

// ---------------------------------------------------------------------
// Substitute any filters/badwords with the $config[censored] variable
   $words[0] = "";
   if ($config['censored']) {
      $badwords = file ("{$config['path']}/filters/badwords");
      while (list($linenum,$line) = each($badwords) ) {
         $line = chop($line);
         if ( (strstr($line,"^\r")) || (strstr($line,"^\n")) ) {
            continue;
         }
         $words[count($words)] = $line;
         // PHP4 ONLY
         // array_push ($words, $line);
      }
      $badwords = join("|", $words);
      $badwords = preg_replace("/^\|/","",$badwords);

      $Subject = preg_replace("/\b($badwords)\b/i",$config['censored'],$Subject);
      $Body = preg_replace("/\b($badwords)\b/i",$config['censored'],$Body);
   }

// -------------------------------
// Are we deleting the attachment?
	if ($deleteattach) {
		$extra = ",B_File=''";
   	if ($file) {
      	unlink("{$config['files']}/$file");
   	}
	}

// -------------------
// Update the database
   $Username_q = addslashes($user['U_Username']);
   $Icon_q    = addslashes($Icon);
   $Subject_q = addslashes($Subject);
   $Body_q    = addslashes($Body);
   $Body_q    = str_replace("\n","<br />",$Body_q);
   $editdate_q = addslashes($editdate);

   $query = "
      UPDATE {$config['tbprefix']}Posts 
      SET    B_Subject = '$Subject_q', 
             B_Body    = '$Body_q',
             B_Icon    = '$Icon_q',
             B_LastEdit= '$editdate_q',
             B_LastEditBy = '$Username_q'
				 $extra
      WHERE  B_Number  = '$Number'
      AND    B_Board   = '$Board_q'
   ";
   $dbh -> do_query($query);

// ----------------------------------------------
// Make sure they get refeshed to the proper page 
   if( ($what == "showthreaded") || ($what == "showflat") ) {

      $html -> send_header("{$ubbt_lang['MODIF_HEAD']}",$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/$what.php?Cat=$Cat&amp;Board=$Board&amp;Number=$Number&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o&amp;fpart=$fpart&amp;vc=$vc\" />",$user);

   } else {

      $html -> send_header("{$ubbt_lang['MODIF_HEAD']}",$Cat,"<meta http-equiv=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&amp;Board=$Board&amp;page=$page&amp;view=$view&amp;sb=$sb&amp;o=$o\" />",$user);

   }

	if (!$debug) {
   	include("$thispath/templates/$tempstyle/modifypost_confirm.tmpl");
	}
   $html -> send_footer();

?>
