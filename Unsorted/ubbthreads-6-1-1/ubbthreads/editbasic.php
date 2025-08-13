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
   require ("languages/${$config['cookieprefix']."w3t_language"}/editbasic.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();                         
   $Username = $user['U_Username'];

   $html = new html;
   if (!$user['U_Username']) {
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// ----------------------------------------
// Get the current profile for this username
   $Username_q = addslashes($Username);
   $query = "
      SELECT U_Password,U_Email,U_Fakeemail,U_Name,U_Signature,U_Homepage,U_Occupation,U_Hobbies,U_Location,U_Bio,U_TextCols,U_TextRows,U_Extra1,U_Extra2,U_Extra3,U_Extra4,U_Extra5,U_Picture,U_Visible,U_AcceptPriv,U_OnlineFormat
      FROM  {$config['tbprefix']}Users
      WHERE U_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);


// --------------------------------
// Make sure we found this Username
   list($ChosenPassword,$Email,$Fakeemail,$Name,$Signature,$Homepage,$Occupation,$Hobbies,$Location,$Bio,$TextCols,$TextRows,$ICQ,$Extra2,$Extra3,$Extra4,$Extra5,$Picture,$Visible,$AcceptPriv,$OnlineFormat) = $dbh -> fetch_array($sth); 
   $dbh -> finish_sth($sth);

   if (!$ChosenPassword){
      $html -> not_right("{$ubbt_lang['NO_PROF']} '$Username'",$Cat);
   }

// --------------------------
// Assign the retrieved data
   if (!$TextCols) { $TextCols =$theme['TextCols']; }
   if (!$TextRows) { $TextRows =$theme['TextRows']; }

// ----------------------------
// Set the default for visibile
   if ($Visible == "yes") {
      $visibleyes = "checked =\"checked\"";
   }
   else {
      $visibleno = "checked=\"checked\"";
   }

// --------------------------------------------
// Set the defaults for extended online display
   if ($OnlineFormat == "no") {
     $OnlineFormatno = "checked=\"checked\"";
   }
   else {
     $OnlineFormatyes = "checked=\"checked\"";
   }

// ----------------------------------------------
// Set the default for accepting private messages
   if ($AcceptPriv == "yes") {
      $acceptyes = "checked=\"checked\"";
   }
   else {
      $acceptno = "checked=\"checked\"";
   } 

// ------------------------
// Convert returns to <br />s
   $Signature = str_replace("<br />","\n",$Signature);
   $Bio       = str_replace("<br />","\n",$Bio);

// --------------------------
// Undo any markup in the Bio
   $Bio = $html -> undo_markup($Bio);
	$Signature = $html -> undo_markup($Signature);

// ---------------------------
// Change the quotes to &quot;
   $ChosenPassword    = str_replace("\"","&quot;",$ChosenPassword);
   $Email       = str_replace("\"","&quot;",$Email);
   $Fakeemail   = str_replace("\"","&quot;",$Fakeemail);
   $Signature   = str_replace("\"","&quot;",$Signature);
   $Homepage    = str_replace("\"","&quot;",$Homepage);
   $Occupation  = str_replace("\"","&quot;",$Occupation);
   $Hobbies     = str_replace("\"","&quot;",$Hobbies);
   $Location    = str_replace("\"","&quot;",$Location);
   $Bio         = str_replace("\"","&quot;",$Bio);

// ---------------------------------------------------------
// Ok, we found the profile, now lets put it all onto a page
   $formmethod = "<form method=\"post\" action=\"{$config['phpurl']}/changebasic.php\">";
   if ($theme['PictureView']) {
      if ( ($config['avatars']) && (ini_get(file_uploads)) ){
         $formmethod = "<form method=\"post\" enctype='multipart/form-data' action=\"{$config['phpurl']}/changebasic.php\">";
         $pictureview = "
{$ubbt_lang['UPLOAD_PIC']}<br /> $Picture<br />
<input type=file name=\"userfile\" accept=\"*\" class=\"formboxes\" />
<br /><br />
         ";
      }
      if (!$Picture) { $Picture ="http://"; }
      $pictureview .= "
{$ubbt_lang['PROF_PIC']}<br />
<input type=\"text\" name=\"Picture\" value=\"$Picture\" size=\"50\" class=\"formboxes\" />
<br />
<br />
      ";
   }
   else {
      $pictureview = "<input type=\"hidden\" name=\"Picture\" value=\"$Picture\" class=\"formboxes\" />";
   }
  


   $html -> send_header("{$ubbt_lang['PROF_HEAD']} $Username",$Cat,0,$user);
   if ($config['extra1']) {
      $extra1 = "
{$ubbt_lang['PROF_ICQ']}
<br />
<input type=\"text\" name=\"ICQ\" value=\"$ICQ\" class=\"formboxes\" />
<br />
<br />
      ";
   }
   if ($config['extra2']) {
      $extra2 = "
{$config['extra2']}
<br />
<input type=\"text\" name=\"Extra2\" value=\"$Extra2\" class=\"formboxes\" />
<br />
<br />
      ";
   }
   if ($config['extra3']) {
      $extra3 = "
{$config['extra3']}
<br />
<input type=\"text\" name=\"Extra3\" value=\"$Extra3\" class=\"formboxes\" />
<br />
<br />
      ";
   }
   if ($config['extra4']) {
      $extra4 = "
{$config['extra4']}
<br />
<input type=\"text\" name=\"Extra4\" value=\"$Extra4\" class=\"formboxes\" />
<br />
<br />
      ";
   }
   if ($config['extra5']) {
      $extra5 = "
{$config['extra5']}
<br />
<input type=\"text\" name=\"Extra5\" value=\"$Extra5\" class=\"formboxes\" />
<br />
<br />
      ";
   }
   if ($config['private'] == "1") {
      $privates = "
{$ubbt_lang['PROF_PRIV']}<br />
<input type=\"radio\" name =\"AcceptPriv\" value=\"yes\" $acceptyes class=\"formboxes\" /> {$ubbt_lang['TEXT_YES']} 
<input type=\"radio\" name =\"AcceptPriv\" value=\"no\" $acceptno class=\"formboxes\" /> {$ubbt_lang['TEXT_NO']} 
<br />  
<br />
      ";
   }

	if (!$debug) {
   	include("$thispath/templates/$tempstyle/editbasic.tmpl");
	}
   $html -> send_footer();
