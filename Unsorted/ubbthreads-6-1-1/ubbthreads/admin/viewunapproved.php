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

// ----------------------------
// Make sure they chose a forum
  if ($Keyword == "category") {
    $html -> not_right("You must choose a forum, not a category.");
  }

// ----------------------------------------
// Grab the unapproved posts for this board 
   $Keyword_q = addslashes($Keyword);
   $query = "
    SELECT B_Number,B_Subject,B_Username,B_Posted 
    FROM  {$config['tbprefix']}Posts 
    WHERE B_Approved = 'no'
    AND   B_Board  = '$Keyword_q'
    ORDER BY B_Posted DESC
  ";
   $sth = $dbh -> do_query($query);

// ------------------------
// Send them a page 
   $html -> send_header ("Approve/Delete Posts",$Cat,0,$user);
   $html -> admin_table_header("Approve/Delete Posts");
   $html -> open_admin_table();

   echo "
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doapproveposts.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <input type=hidden name=Keyword value=$Keyword>

    <TR><TD class=\"lighttable\" colspan=5>
    Place a checkmark next to all posts that you wish to approve or delete.
    </TD></TR>
    <tr><td class=\"tdheader\">Approve</td> 
    <td class=\"tdheader\">Delete</td>
    <td width=40% class=\"tdheader\">Subject</td>
    <td width=20% class=\"tdheader\">Posted by</td>
    <td width=20% class=\"tdheader\">Posted on</td></tr>
  
   ";
   $color = "lighttable";
   $Total = 0;
   while (list($Number,$Subject,$Username,$Posted) = $dbh -> fetch_array($sth)){
      $Total++; 
      $Posted = $html -> convert_time($Posted,$user['U_TimeOffset']);

      echo "
         <tr><td class=\"$color\">
         <input type=radio name=Thread$Total value=\"APPROVE-$Number\" class=\"formboxes\">
         </td><td class=\"$color\">
         <input type=radio name=Thread$Total value=\"DELETE-$Number\" class=\"formboxes\">
         </td><td class=\"$color\">
         <a href=\"{$config['phpurl']}/showthreaded.php?Cat=$Cat&Board=$Keyword&Number=$Number\">$Subject</a>
         </td><td class=\"$color\">
         $Username
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
   echo "<TR><TD COLSPAN=5 CLASS=cleartable>";
   echo "<input type=hidden name=Total value=$Total>";
   echo"<input type=submit name=option value = \"Approve/Delete all checked posts\" class=\"buttons\">";
   $html -> close_table();
   echo"</form>";
   $html -> send_admin_footer();

?>
