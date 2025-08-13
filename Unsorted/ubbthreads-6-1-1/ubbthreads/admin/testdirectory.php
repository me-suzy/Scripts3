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
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// ------------------------
// Send them a confirmation
  $html -> send_header ("Results",$Cat,"",$user);
  $html -> admin_table_header("Directory Results");
  echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  

  echo "<center><span class=small>Testing '$dirname'</span></center><br><br>";
  if (file_exists($dirname)) {
     echo "Directory Exists ";
     $check = @fopen("$dirname/test.file","w");
     if (!$check) {
        echo "<span class=standouttext>but is not writeable.</span>";
        $error = 1;
     }
     else {
        echo "and is writeable.";
     }
  }
  else {
     echo "<span class=standouttext>Directory does not exist!</span>";
     $error = 1;
  }
  if ($error) {
     echo "<br><br>Test failed.  Either the directory was not found or cannot be written to by the webserver.";
     if ($type=="sess") {  
        echo " If this cannot be corrected at this time set your tracking to cookies, otherwise nobody will be able to login.";
     }
  }
  else {
     echo "<br><br>Test passed.";
  }
  echo "</span></TD></TR></TABLE>";
  echo "</body></html>";
