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
   require ("languages/${$config['cookieprefix']."w3t_language"}/adduser.php");   
   $html = new html;
   $mailer  = new mailer;

   $Username = $Loginname;
   $Password = $Loginpass; 

// --------------------------------------------------------
// If we are checking age, we make sure they are old enough
   if ( ($config['checkage']) && ($p != "y") ){

      if (${$config['cookieprefix']."ubbt_dob"}) {
        list ($month,$day,$year) = split("/",${$config['cookieprefix']."ubbt_dob"});
      }
      if (!checkdate($month,$day,$year)) {
         $html -> not_right($ubbt_lang['INVALID_DATE'],$Cat);
      }
      $currday = date("d");
      $currmon = date("m");
      $curryea = date("Y");
      $years = $curryea - $year;
      if ($years < 13) {
         $nopass = 1;
      }
      if ($years == 13) {
        if ($currmon < $month) {
          $nopass = 1;
        }
        if ( ($currmon == $month) && ($currday < $day) ) {
          $nopass = 1;
        }
      }
      if ($nopass) {
          setcookie("{$config['cookieprefix']}ubbt_pass","invalid",time()+30758400,"{$config['cookiepath']}");
          setcookie("{$config['cookieprefix']}ubbt_dob","$month/$day/$year",time()+30758400,"{$config['cookiepath']}");
         if ($config['under13']) {
            $html -> not_right($ubbt_lang['TOOYOUNG'],$Cat);
         }
         else {
            $html -> not_right($ubbt_lang['UNDER13'],$Cat);
         }
      }
      else {
          setcookie("{$config['cookieprefix']}ubbt_pass","ok",time()+30758400,"{$config['cookiepath']}");
          setcookie("{$config['cookieprefix']}ubbt_dob","$month/$day/$year",time()+30758400,"{$config['cookiepath']}");
          header("Location: {$config['phpurl']}/newuser.php?Cat=$Cat&p=y&PHPSESSID=$PHPSESSID");
      }
   }

// ----------------------------------
// No special characters
   if (!$config['specialc']) { 
      if (!preg_match("/^\w+$/",$Username)) {
         $html -> not_right($ubbt_lang['BAD_UNAME'],$Cat);
      } 
   }
   else {
   // ----------------------------------
   // No html characters in the username
      if ( (stristr($Username,"&nbsp;")) || ( strstr($Username,"<")) || (strstr($Username,">")) || (preg_match("/^ /",$Username)) || (preg_match("/ $/",$Username)) ) {
         $html -> not_right($ubbt_lang['BAD_UNAME'],$Cat);
      }  
   }

// ------------------------------------------------------------
// If all required info is not filled in, then we can't proceed 
   if((!$Username)||(!$Email)||($agree != "yes")){
      $html -> not_right($ubbt_lang['ALL_FIELDS'],$Cat);
   }

// -------------------------------------------------------------
// If the Username isn't the proper length then we can't proceed  
   if((strlen($Username) >16) || (strlen($Username) < 3)) {
      $html -> not_right($ubbt_lang['LONG_NAME'],$Cat);
   }

// ---------------------- 
// Check the email format 
   if (!eregi("^[0-9a-z]([-_.+]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\.[a-wyz][a-z](g|l|m|pa|t|u|v|z|fo)?$", $Email)) {
     $html -> not_right($ubbt_lang['BAD_FORMAT'],$Cat);
   }

// --------------------------------------
// Let's see if the email domain is valid
   $bademails = file ("{$config['path']}/filters/bademail");
   while (list($linenum,$line) = each($bademails)) {
      $line = chop($line);
      if ( (preg_match("/^\n/",$line)) || (preg_match("/^\r/",$line)) || (preg_match("/^#/",$line)) ) {
         continue; 
      }
      if (stristr($Email,$line)) {
         $html -> not_right("{$ubbt_lang['BAD_EMAIL']} $_.",$Cat);
      }
   } 

// --------------------------------------------
// Check to make sure the Username is available
   $Username_q = addslashes($Username);
   $query = " 
     SELECT U_Username
     FROM   {$config['tbprefix']}Users
     WHERE  U_Username = '$Username_q'
	  OR		U_LoginName = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);
   list ($check) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// ----------------------------------------------------------
// If sql returned a row then that username is already in use.
   if($check) {
      $html -> not_right($ubbt_lang['USER_EXISTS'],$Cat);
   }

// -------------------------------------
// Lets see if it is a reserved Username
   $badnames = file ("{$config['path']}/filters/badnames");
   while (list($linenum,$line) = each($badnames)) {
      if (preg_match("/^#/",$line)) {
         continue;
      }
      $line = chop ($line);
      if (strtolower($Username) == strtolower($line)) {
         $html -> not_right($ubbt_lang['USER_EXISTS'],$Cat);
      }
   }

// ---------------------------------------------------------------------
// If we do not allow multiple usernames for the same email address then
// we need to see if this email address is in the database
   if (!$config['multiuser'] ) {
      $Email_q = addslashes($Email);
      $query = " 
        SELECT U_Email
        FROM   {$config['tbprefix']}Users
        WHERE U_Email = '$Email_q'
        OR U_RegEmail = '$Email_q'
      "; 
      $sth = $dbh -> do_query($query);
      list($emailcheck) = $dbh -> fetch_array($sth);
      $dbh -> finish_sth($sth);
      if($emailcheck){
         $html -> not_right($ubbt_lang['NO_MULTI'],$Cat);
      }
   } 


// --------------------------------------
// Check to see if this is the first user
   $query = " 
    SELECT COUNT(U_Number)
    FROM   {$config['tbprefix']}Users
   ";
   $sth = $dbh -> do_query($query);
   list ($firstuser) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);
   $Groups = $config['newusergroup'];

// ---------------------------------------
// Are we requiring registration approval?
   if ($config['userreg']) {
      $approved = 'no';
   }
   else {
      $approved = 'yes';
   }

// -------------------------------------------------------
// If this is the first user, then status is Administrator
// otherwise they are just get normal user status.
   $Color = " ";
   if ($firstuser < 2){
      $Status = "Administrator";
      $Groups .= "1-";
		$approved = "yes";
      $Color  = $theme['admincolor'];
      if (!$config['emailaddy']) {
         $config['emailaddy'] = $Email;
      }
   } else {
      $Status = "User";
   } 

// ---------------------
// Set the default title
   $titles = file ("{$config['path']}/filters/usertitles");
   list($post,$utitle) = split("\t",$titles['0']);
   $utitle = str_replace("\n","",$utitle);
   $utitle = chop($utitle);
   $Title_q = addslashes($utitle);

// ------------
// Get the date
   $date = $html -> get_date();

// ------------------------------
// Put the user into the database
   $Groups_q   = addslashes($Groups);
   $Status_q   = addslashes($Status);
   $Username_q    = addslashes($Username);
   $Email_q    = addslashes($Email);
   $Display_q  = addslashes($theme['postlist']);
   $View_q     = addslashes($theme['threaded']); 
   $EReplies_q = addslashes("Off");
   $PFormat    = addslashes($theme['post_format']);
   $Color_q    = addslashes($Color);

// -------------------------------
// Grab the registering IP address
   $ip = find_environmental('REMOTE_ADDR');
   $ip_q = addslashes($ip);

   if ($Password) {	
      if ($Password != $Verify) {
         $html -> not_right($ubbt_lang['PASS_MATCH'], $Cat);
      }
      if((strlen($Password) > 21) || (strlen($Password) < 4)) {
         $html -> not_right($ubbt_lang['PASS_TOO_LONG'], $Cat);
      }
      if (preg_match("/^\W+$/",$Password)) {
         $html -> not_right($ubbt_lang['ILL_PASS'], $Cat);
      }
      $pass = $Password;  
   }
// xxx pw SFL  Done.


// --------------------------------------------------------------------
// Now we need to generate a random password if they didn't specify one
   $passset = array('a','b','c','d','e','f','g','h','i','j','k','m','n','o','p','q','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','2','3','4','5','6','7','8','9');
   $chars = sizeof($passset);

   if (!$pass) {
      $a = time();
      mt_srand($a);
      for ($i=0; $i < 6; $i++) {
         $randnum = intval(mt_rand(0,56))    ;
         $pass .= $passset[$randnum];
      }                                            
   }

// ----------------------------
// Now let's crypt the password
   $crypt = md5($pass);

// ----------------------------------
// Insert this user into the database
   $query = "
         INSERT INTO {$config['tbprefix']}Users (U_LoginName,U_Username,U_Password,U_Email,U_Totalposts,U_Laston,U_Status,U_Sort,U_Display,U_View,U_PostsPer,U_EReplies,U_Post_Format,U_Registered,U_RegEmail,U_RegIP,U_Groups,U_Title,U_Color,U_Privates,U_Approved)
         VALUES ('$Username_q','$Username_q','$crypt','$Email_q','0','$date','$Status_q','{$theme['sort']}','$Display_q','$View_q','{$theme['postsperpage']}','$EReplies_q','$PFormat','$date','$Email_q','$ip_q','$Groups_q','$Title_q','$Color_q','1','$approved')
   "; 
   $dbh -> do_query($query);

// --------------------------------
// Get the Administrator's Username
   $query = " 
      SELECT U_Username
      FROM   {$config['tbprefix']}Users
      WHERE  U_Number = 2 
   "; 
   $sth = $dbh -> do_query($query);

// ---------------------------------
// Set up some stuff for the message
   list($Sender)   = $dbh -> fetch_array($sth); 
   $dbh -> finish_sth($sth);
   $Sender_q   = addslashes ($Sender);
   $Messstat_q = "N";
   $Subject    = $ubbt_lang['INTRO_SUB2'];
   $Subject_q  = addslashes($Subject);
   $Message_q  = addslashes($ubbt_lang['WEL_MSG']);

// --------------------------------------
// Put the message into the Messages table
   $query = " 
         INSERT INTO {$config['tbprefix']}Messages (M_Username,M_Status,M_Subject,M_Sender,M_Message,M_Sent)
         VALUES ('$Username_q','$Messstat_q','$Subject_q','$Sender_q','$Message_q','$date')
      "; 
   $dbh -> do_query($query);
    

// -------------------------------------
// Now we need to mail them the password
   if ( ($Username) && ($Email) ) {
      $to      = $Email;
      $header  = $mailer -> headers();
      $subject = "{$ubbt_lang['PASS_SUB']} {$config['title']}";
      $msg     = "{$ubbt_lang['PASS_BODY1']} '$ip' {$ubbt_lang['PASS_BODY2']} \"$Username\".  {$ubbt_lang['PASS_BODY3']} \"$pass\". ";
      if ($config['userreg']) {
         $msg .= $ubbt_lang['NEEDAPPROVAL'];
      } 
      mail("$to","$subject","$msg",$header);

  }

// ---------------------------------------------------------------------
// Now if we are manually approving user registration we need to send an
// email to all admins
   if ($config['userreg']) {
      $query = "
         SELECT U_Email
         FROM   {$config['tbprefix']}Users
         WHERE  U_Status = 'Administrator'
      ";
      $sth = $dbh -> do_query($query);
      while(list($adminemail) = $dbh -> fetch_array($sth)) {
         if (!$adminemail) {
            continue;
         }
         $newuser = rawurlencode($Username);
         $to      = $adminemail;
         $header = $mailer -> headers();
         $subject = "{$ubbt_lang['NEWREG']} {$config['title']}";
         $msg     = "{$ubbt_lang['NEWREGBODY']} {$config['phpurl']}/admin/login.php?file=approveuser.php";
         mail("$to","$subject","$msg",$header);
      }
   }

// ------------------------
// Send them a confirmation
   if ($config['userreg']) {
      $extrahtml = $ubbt_lang['NEEDAPPROVAL'];
   }
   $html -> send_header($ubbt_lang['NEW_CONFIRM'],$Cat,0,0,0,0);
	if (!$debug) {
   	include ("$thispath/templates/$tempstyle/adduser.tmpl");
	}
   $html -> send_footer();

