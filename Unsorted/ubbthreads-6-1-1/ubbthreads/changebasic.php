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
   require ("languages/${$config['cookieprefix']."w3t_language"}/changebasic.php");

// Make sure this isn't being called via GET
   if ($GLOBALS['REQUEST_METHOD'] == "GET") {
      exit;
   }

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Number");                   
   $Username = $user['U_Username'];
   $Password = $user['U_Password'];

   $html = new html;
   if (!$user['U_Username']){
      $html -> not_right ($ubbt_lang['NO_AUTH'],$Cat);
   }

// -----------------------------------------------------------
// If the 2 given password do not match then we can't proceed  
   if($ChosenPassword != $Verify){
      $html -> not_right($ubbt_lang['PASS_MATCH'],$Cat);
   }

// --------------------------------------------------------
// If the password isn't the proper length we can't proceed  
   if ($ChosenPassword != $Password) {
      if((strlen($ChosenPassword)<4) || (strlen($ChosenPassword)>20)){
         $html -> not_right($ubbt_lang['PASS_TOO_LONG'],$Cat);
      }
   }

// -----------------------------------------------------
// If homepage is greater than 150 then we can't proceed
   if ( strlen($Homepage) > 150 ) {
      $html -> not_right($ubbt_lang['HOME_TOO_LONG'],$Cat); 
   }

// --------------------------------------------------------
// If Occupation is greater than 150 then we can't proceed
   if ( strlen($Occupation) > 150 ) {
      $html -> not_right($ubbt_lang['OCC_TOO_LONG'],$Cat);
   }  

// ----------------------------------------------------
// If hobbies is greater than 200 then we can't proceed
   if ( strlen($Hobbies) > 200 ) {
      $html -> not_right($ubbt_lang['HOBB_TOO_LONG'],$Cat);
   }

// -----------------------------------------------------
// If location is greater than 200 then we can't proceed
   if ( strlen($Location) > 200 ) {
      $html -> not_right($ubbt_lang['LOC_TOO_LONG'],$Cat);
   }

// -------------------------------------------------------------
// If signature is greater than the specified size, do not allow
   if ( strlen($Signature) > $config['Sig_length'] ) {
      $html -> not_right($ubbt_lang['SIG_TOO_LONG'],$Cat);
   }

// ------------------------------------------------
// If Bio is greater than 250, the we do not allow
   if ( strlen($Bio) > 250) {
      $html -> not_right($ubbt_lang['BIO_TOO_LONG'],$Cat);
   }

// ----------------------------------------------------------
// If picture doesn't end with gif or jpg then we disallow it
   if ( ($Picture) && ($Picture != "http://") && (!preg_match("/(png|jpg|gif)$/i",$Picture))) {
      $html -> not_right($ubbt_lang['BAD_PIC'],$Cat);
   }
   if ( ($Picture) && ($Picture != "http://") && ($Picture != "none") ) {
      $imagehw = @GetImageSize($Picture);
      $imagewidth = $imagehw[0];
      $imageheight = $imagehw[1];
		if ($imagewidth > $theme['PictureWidth'] or $imageheight > $theme['PictureHeight']) {   
			$imagewidth = $theme['PictureWidth'];   
			$imageheight = $theme['PictureHeight'];}
   	}

// --------------------------------------------------
// If we have a picture attachment then we handle it here
   if ( ($HTTP_POST_FILES['userfile']['name'] != "none") && ($HTTP_POST_FILES['userfile']['name']) ){
      if (!preg_match("/(png|jpg|gif)$/i",$HTTP_POST_FILES['userfile']['name'])) {
         $html -> not_right($ubbt_lang['BAD_PIC'],$Cat);
      }
   }
   if ( ($HTTP_POST_FILES['userfile']['size'] > $config['avatarsize']) ) {
      $html -> not_right($ubbt_lang['FILE_TOO_BIG'],$Cat);
   }
   if (($HTTP_POST_FILES['userfile']['tmp_name']) && ($HTTP_POST_FILES['userfile']['tmp_name'] != "none") ){
      $imagehw = GetImageSize($HTTP_POST_FILES['userfile']['tmp_name']);
      $imagewidth = $imagehw[0];
      $imageheight = $imagehw[1];
      if ($imagewidth > $theme['PictureWidth']) {
         $imagewidth = $theme['PictureWidth'];
      }
      if ($imageheight > $theme['PictureHeight']) {
         $imageheight = $theme['PictureHeight'];
      }
   }
   if ( ($HTTP_POST_FILES['userfile']['name'] != "none") && ($HTTP_POST_FILES['userfile']['name']) ){

   // Grab the filetype
      $piece['0'] = "";
      preg_match("/(\.gif|\.png|\.jpg)$/i",$HTTP_POST_FILES['userfile']['name'],$piece);
      $type = $piece['1'];
      $Picturefile = "{$user['U_Number']}$type";
		$Picturefile = strtolower($Picturefile);
      if ( ($HTTP_POST_FILES['userfile']['tmp_name'] != "none") && ($HTTP_POST_FILES['userfile']['tmp_name']) ){
         copy($HTTP_POST_FILES['userfile']['tmp_name'], "{$config['avatars']}/$Picturefile");
      }
      $Picture = "{$config['avatarurl']}/$Picturefile";
   }

// Make sure we have a width/height
	if (!$imagewidth or !$imageheight) {   
		$imagewidth = $theme['PictureWidth'];   
		$imageheight = $theme['PictureHeight'];
	}

// -----------------------------------------------
// If this is a new password we need to encrypt it
   if ($ChosenPassword != $Password) {
      $ChosenPassword = md5($ChosenPassword);
   }

// --------------------------------
// Get rid of HTML in the signature
	$Signature = str_replace("<","&lt;",$Signature); 
	$Signature = str_replace(">","&gt;",$Signature); 
	$Signature = str_replace("&lt;br&gt;","<br />",$Signature); 

// ----------------------
// Check the email format
   if (!eregi("^[0-9a-z]([-_.+]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\.[a-wyz][a-z](g|l|m|pa|t|u|v|z|fo)?$", $Email)) {
     $html -> not_right($ubbt_lang['BAD_FORMAT'],$Cat);
   }

// ------------------------------------------------
// Strip out all of the <> and change to HTML codes
   $Email = str_replace("<","&lt;",$Email);
   $Fakeemail = str_replace("<","&lt;",$Fakeemail);
   $Name = str_replace("<","&lt;",$Name);
   $Homepage = str_replace("<","&lt;",$Homepage);
   $Occupation = str_replace("<","&lt;",$Occupation);
   $Hobbies = str_replace("<","&lt;",$Hobbies);
   $Location = str_replace("<","&lt;",$Location);
   $Bio = str_replace("<","&lt;",$Bio);
   $ICQ = str_replace("<","&lt;",$ICQ);
   $Picture = str_replace("<","&lt;",$Picture);
   $Extratwo = str_replace("<","&lt;",$Extra2);
   $Extrathr = str_replace("<","&lt;",$Extra3);
   $Extrafou = str_replace("<","&lt;",$Extra4);
   $Extrafiv = str_replace("<","&lt;",$Extra5);
   $Bio = str_replace("\n","<br />",$Bio);

// -----------------------
// Allow markup in the bio
   $Bio = $html -> do_markup($Bio);
	$Signature = $html -> do_markup($Signature);


// -----------------------
// Format the query words
   $Password_q   = addslashes($ChosenPassword);
   $Email_q      = addslashes($Email);
   $Fakeemail_q  = addslashes($Fakeemail);
   $Name_q       = addslashes($Name);
   $Signature_q  = addslashes($Signature);
   $Homepage_q   = addslashes($Homepage);
   $Occupation_q = addslashes($Occupation);
   $Hobbies_q    = addslashes($Hobbies);
   $Location_q   = addslashes($Location);
   $Bio_q        = addslashes($Bio);
   $Username_q   = addslashes($Username);
   $ICQ_q        = addslashes($ICQ);
   $Extra2_q	   = addslashes($Extratwo);
   $Extra3_q	   = addslashes($Extrathr);
   $Extra4_q	   = addslashes($Extrafou);
   $Extra5_q	   = addslashes($Extrafiv);
   $Picture_q    = addslashes($Picture);
   $Visible_q    = addslashes($Visible);
   $AcceptPriv_q = addslashes($AcceptPriv);
   $OnlineFormat_q = addslashes($OnlineFormat);

// --------------------------
// Update the User's profile
   $query  = "
    UPDATE {$config['tbprefix']}Users
    SET U_Password   = '$Password_q',
    U_Email          = '$Email_q',
    U_Fakeemail      = '$Fakeemail_q',
    U_Name           = '$Name_q',
    U_Signature      = '$Signature_q',
    U_Homepage       = '$Homepage_q',
    U_Occupation     = '$Occupation_q',
    U_Hobbies        = '$Hobbies_q',
    U_Location       = '$Location_q',
    U_Bio            = '$Bio_q',
    U_Extra1         = '$ICQ_q',
    U_Extra2	   = '$Extra2_q',
    U_Extra3	   = '$Extra3_q',
    U_Extra4	   = '$Extra4_q',
    U_Extra5	   = '$Extra5_q',
    U_Picture        = '$Picture_q',
    U_Visible	   = '$Visible_q',
    U_AcceptPriv     = '$AcceptPriv_q',
    U_OnlineFormat   = '$OnlineFormat_q',
    U_PicWidth       = '$imagewidth',
    U_PicHeight      = '$imageheight'
    WHERE U_Username = '$Username_q'
   "; 
   $dbh -> do_query($query); 

// ------------------------------------------------------
// If they are always rememberd we need to update the key
   if (( ${$config['cookieprefix']}."w3t_key") && ($Password != $ChosenPassword)){
      if ($config['cookieexp'] > 31536000) {
         $config['cookieexp'] = 31536000;
      }
      $autolog = md5("$Username$ChosenPassword");
      setcookie("{$config['cookieprefix']}w3t_key","$autolog",time()+$config['cookieexp'],"{$config['cookiepath']}");
   }

// ---------------------------------------------------
// Send them to their start page with the confirmation
   $html -> start_page($Cat);
