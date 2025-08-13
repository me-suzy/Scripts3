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

// ----------------------------------
// Grab the current available version
   if (@fopen("http://www.infopop.com/ubbthreads_version/threads_version.html","r")) {
      $ubbtfile = file("http://www.infopop.com/ubbthreads_version/threads_version.html");
      while(list($linenum,$line) = each($ubbtfile)) {
        if (ereg("^Version",$line)) {
          list($junk,$version) = split(": ",$line);
        }
        if (ereg("^Release",$line)) {
          list($junk,$release) = split(": ",$line);
        }
      }
   }
   else {
      $version = "Unable to retrieve.";
      $release = "Unable to retrieve.";
   }
   

// ------------------------
// Send them a page 
   $html -> send_header ("UBB.threads Information.",$Cat,0,$user);
   $html -> admin_table_header("UBB.threads Information");
   $html -> open_admin_table();
   echo "<tr class=darktable width=25% nowrap>
           <td>
             Running Version
           </td>
           <td class=lighttable width=75%>
             $VERSION
           </td>
         </tr>
         <tr class=darktable>
           <td>
             Current Release
           </td>
           <td class=lighttable>
             $version 
           </td>
         </tr>
         <tr class=darktable>
           <td>
             Released on 
           </td>
           <td class=lighttable>
             $release
   ";

   $html -> close_table();
   $html -> send_admin_footer();

?>
