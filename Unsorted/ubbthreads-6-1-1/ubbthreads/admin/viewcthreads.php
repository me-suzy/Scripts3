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
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// -----------------------------------------------
// Grab the board info 
   $Closed_q = "C";
   $Keyword_q = addslashes($Keyword);
   $query = "
    SELECT B_Number,B_Main,B_Subject,B_Username,B_Posted,B_Status 
    FROM  {$config['tbprefix']}Posts 
    WHERE B_Topic = 1 AND B_Status = '$Closed_q'
    AND   B_Board = '$Keyword_q'
    ORDER BY B_Posted DESC
   ";
   $sth = $dbh -> do_query($query);

// ------------------------
// Send them a page 
   $html -> send_header ("Open threads",$Cat,0,$user);
   $html -> admin_table_header("Open threads");
   $html -> open_admin_table();

   echo "
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doopenthreads.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <input type=hidden name=Keyword value=$Keyword>

    <tr><td colspan=4 class=\"lighttable\">
    Place a checkmark by any threads that you wish to reopen.
    </td></tr>
    <tr><td class=\"tdheader\">Open</td> 
    <td class=\"tdheader\">Subject</td>
    <td class=\"tdheader\">Posted by</td>
    <td class=\"tdheader\">Posted on</td></tr>
   ";
   $color = "lighttable";
   while ( list($Number,$Main,$Subject,$Poster,$Posted) = $dbh -> fetch_array($sth)){
      $Total++;
      $Posted = $html -> convert_time($Posted,$user['U_TimeOffset']);

      echo "
        <tr><td class=\"$color\">
        <input type=checkbox name=$Total value=\"$Number\" class=\"formboxes\">
        </td><td class=\"$color\">
        <a href=\"{$config['phpurl']}/showthreaded.php?Cat=$Cat&Board=$Keyword&Number=$Number\">$Subject</a> 
        </td><td class=\"$color\">
        $Poster
        </td><td class=\"$color\">
        $Posted
        </td></tr>
      ";
      if ($color == "lighttable"){
         $color = "darktable";
      }
      else {
         $color = "lighttable";
      }
   }
   $dbh -> finish_sth($sth);

   echo"<tr><td colspan=4 class=\"lighttable\">";
   echo "<input type=hidden name=Total value=$Total>";
   echo"<input type=submit value = \"Submit\" class=\"buttons\">";
   echo"</form>";

   $html -> close_table();
   $html -> send_admin_footer();

?>
