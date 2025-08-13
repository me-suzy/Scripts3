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

// ------------------
// Get the forum info
   $Keyword_q  = addslashes($Board);
   $query = "
      SELECT Bo_SpecialHeader,Bo_StyleSheet
      FROM   {$config['tbprefix']}Boards
      WHERE  Bo_Keyword='$Keyword_q'
   ";
   $sth = $dbh -> do_query($query);
   list ($fheader,$fstyle) = $dbh -> fetch_array($sth);

// -------------------------------------------------
// Here we need to figure out what stylesheet to use
   $mystyle = $user['U_StyleSheet'];
   if (!$mystyle) { $mystyle = $theme['stylesheet']; }
   if ($mystyle == "usedefault") {
      $mystyle = $fstyle;
      if ($mystyle == "usedefault") {
         $mystyle = $theme['stylesheet'];
      }
   }
// fstyle will now be a global variable to use in send_header
   $fstyle = $mystyle;


// -----------------------------------------------------------------------
// Mark the B_Posted and B_Last_Post as sticky and update the sticky field 
   $query = "
    UPDATE {$config['tbprefix']}Posts 
    SET    B_Posted    = B_Sticky,
           B_Last_Post = B_Sticky,
           B_Sticky    = '0' 
    WHERE  B_Number    = '$Number'
    AND    B_Board     = '$Keyword_q'
   ";
   $dbh -> do_query($query);

// ------------------------
// Send them a page 
   $html -> send_header ("Post returned to normal",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/postlist.php?Cat=$Cat&Board=$Board&view=$view&sb=$sb&o=$o\">",$user);
   $html -> admin_table_header("Post returned to normal");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The selected post has been returned to it's normal date.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();	

?>
