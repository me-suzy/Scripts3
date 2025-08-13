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

// -----------------------------------------------
// Grab the current Categories 
   $query = "
    SELECT DISTINCT Cat_Title 
    FROM {$config['tbprefix']}Category 
   ";
   $sth = $dbh -> do_query($query);

// ------------------------------------------------------------------------
// Now we change the sort order but we need to do some error checking along
// the way.
   while ( list($Keyword) = $dbh -> fetch_array($sth)) {
      $Keyword_q = addslashes($Keyword);
      $Keyword = preg_replace("/\W/","",$Keyword);
      if ($HTTP_POST_VARS[$Keyword] < 1) {
         $html -> not_right("The lowest number you can assign is 1. $Keyword $FORM{$Keyword}",$Cat);
      }
      if ( ereg("\.",$HTTP_POST_VARS[$Keyword])) {
         $html -> not_right("Umm, how about entering a whole number.",$Cat);
      }
      if ($HTTP_POST_VARS[$Keyword] > $Total) {
         $html -> not_right("The highest number you can assign is $Total.",$Cat);
      }
    
      $check = ",$HTTP_POST_VARS[$Keyword],";
      if (ereg($check,$assigned)) {
         $html -> not_right("You cannot assign the same number to more than 1 forum or category.",$Cat);
      }
      $assigned = $assigned.",$check,";
      $Number    = $HTTP_POST_VARS[$Keyword];
      $query = "
        SELECT Cat_Number
        FROM  {$config['tbprefix']}Category
        WHERE Cat_Title = '$Keyword_q'
      ";
      $stt = $dbh -> do_query($query);           
      list($Oldnumber) = $stt -> fetchrow_array;
      $dbh -> finish_sth($stt);
      $query = "
        UPDATE {$config['tbprefix']}Category 
        SET    Cat_Number = '$Number'
        WHERE  Cat_Title  = '$Keyword_q'
      ";
      $dbh -> do_query($query);

      $query = "
        UPDATE {$config['tbprefix']}Boards 
        SET    Bo_Cat = '$Number'
        WHERE  Bo_CatName = '$Keyword_q'
      ";
      $dbh -> do_query($query);
   }
   $dbh -> finish_sth($sth);

// ------------------------
// Send them a confirmation
   $html -> send_header ("Order has been changed.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Order has been changed.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The order has been changed in the database.  You will now be returned to the main admin page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
