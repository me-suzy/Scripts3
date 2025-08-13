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
   require ("../main.inc.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $html = new html;

// -----------------------------
// Make sure they should be here
   if ($user['U_Status'] != 'Administrator'){
      $html -> not_right ("You must be logged in, and be a valid administrator to access this.",$Cat);
   }

// We need to set the cookiepath on this to the root ubbthreads directory.
	if ($config['cookiepath'] != "/") {
		$config['cookiepath'] = "/";
	}
	$cookiepath = $config['cookiepath'];

// -------------------------
// Grab this password
   $Username_q = addslashes($Who);
   $query = "
    SELECT U_Language,U_Number,U_SessionId
    FROM   {$config['tbprefix']}Users
    WHERE  U_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);
   list($lang,$Uid,$oldsess) = $dbh -> fetch_array($sth);

   if (!$lang) {
      $lang = ${$config['cookieprefix']."w3t_language"};
   }
   ${$config['cookieprefix']."w3t_language"} = $lang;

// --------------------------------------
// Set a cookie or register a session var
// If we have a session id don't generate a new one because it will
// log the real user out if they are logged in
   if (!$oldsess) {
      srand((double)microtime()*1000000);
      $sessionid = md5(rand(0,32767));
      $newsess = addslashes($sessionid);
      $query = "
         UPDATE {$config['tbprefix']}Users
         SET U_SessionId = '$newsess'
         WHERE U_Username='$Username_q'
      ";
      $dbh -> do_query($query);
   }
   else {
      $sessionid = $oldsess;
   }

   if ($config['tracking'] == "sessions") {
      ${$config['cookieprefix']."w3t_myid"} = $Uid;
      ${$config['cookieprefix']."w3t_mysess"} = $sessionid;
   }
   else {
      if ($config['cookieexp'] > 31536000) {
         $config['cookieexp'] = 31536000;
      }
      setcookie("{$config['cookieprefix']}w3t_myid","$Uid",time()+$config['cookieexp'],"$cookiepath");
      setcookie("{$config['cookieprefix']}w3t_mysess","$sessionid","0","$cookiepath");
      setcookie("{$config['cookieprefix']}w3t_language","$ubbt_language",time()+$config['cookieexp'],"$cookiepath");
   }

   echo <<<EOF
<HTML>
<HEAD>
<META HTTP-EQUIV="Refresh" content="1;url={$config['phpurl']}/ubbthreads.php">
</HEAD>
<BODY>
</BODY>
</HTML>
EOF;


?>
