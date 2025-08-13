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

// ---------------------------------
// Make sure they are should be here
   if ($user['U_Status'] != 'Administrator'){
      $html -> not_right ("You must be logged in, and be a valid administrator to access this.",$Cat);
   }

// --------------------------------------------------
// Update this user's status if they are not an admin
 
   $newstat    = "Moderator";
   $Username_q = addslashes($User);
   $Keyword_q  = addslashes($Keyword);
   $checkstat  = "Administrator";
   $newcolor   = addslashes($theme['modcolor']);
   $query = "
    UPDATE {$config['tbprefix']}Users 
    SET    U_Status   = '$newstat',
           U_Color    = '$newcolor'
    WHERE  U_Username = '$Username_q' 
    AND U_Status      <> '$checkstat'     
   ";
   $dbh -> do_query($query);

   $boards = split("-MULTI-",$assignmod);
   $arraysize = sizeof($boards);
   for ($i=0; $i<$arraysize; $i++) {

   // --------------------
   // Grab the boards info
      $Number = $boards[$i];
      $query = "
         SELECT Bo_Title, Bo_Keyword, Bo_Number, Bo_Moderators
         FROM   {$config['tbprefix']}Boards
         WHERE  Bo_Number = $Number
      ";
      $sth = $dbh -> do_query($query);
      list ($Title,$Keyword,$Number,$moderators) = $dbh -> fetch_array($sth);
      $Keyword_q = addslashes($Keyword);

   // -----------------------------------------
   // Now update the board to set the moderator
      if (!$moderators) {
         $moderators = ",$User,";
      }
      else {
         $moderators .= "$User,";
      }

      $newmoderators = addslashes($moderators);
      $query = "
         SELECT Mod_Username
         FROM  {$config['tbprefix']}Moderators
        WHERE Mod_Username = '$Username_q'
        AND   Mod_Board    = '$Keyword_q'
      ";
      $sth = $dbh -> do_query($query);
      list($check) = $dbh -> fetch_array($sth);
      $dbh -> finish_sth($sth);

      if (!$check) {
         $query = "
            INSERT INTO {$config['tbprefix']}Moderators
            (Mod_Username,Mod_Board)
            VALUES
            ('$Username_q','$Keyword_q')
         ";
         $dbh -> do_query($query);
         $query = "
            UPDATE {$config['tbprefix']}Boards
            SET Bo_Moderators = '$newmoderators'
            WHERE Bo_Keyword = '$Keyword_q'
         ";
         $dbh -> do_query($query);
      }
      $allboards .=" '$Title',";
   }
   $allboards = preg_replace("/,$/","",$allboards);

// ------------------------------------------
// Find out what groups they are currently in
   $query = "
    SELECT U_Groups
    FROM   {$config['tbprefix']}Users
    WHERE  U_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);
   list($groups) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if (!ereg("-2-",$groups)) {
      $updategroups = $groups . "2-";
      $updategroups_q = addslashes($updategroups);
      $query = "
         UPDATE {$config['tbprefix']}Users
         SET    U_Groups = '$updategroups_q'
         WHERE  U_Username = '$Username_q'
      ";
      $dbh -> do_query($query);
   }

// --------------------------------
// Inform all admins and moderators 
   $Sender  = $user['U_Username'];
   $Subject = "A new moderator";
   $mess    = "$Sender has given Moderator privileges to $User for the the following forums: $allboards.";
   $html -> send_message($Sender,"",$Subject,$mess,"A_M_GROUP");

   $Sender  = $user['U_Username'];
   $To      = $User;
   $Subject = "A new moderator";
   $mess    = "You have been assigned to be the moderator of the forums: $allboards.";
   $html -> send_message($Sender,$To,$Subject,$mess);    
  

// ------------------------
// Send them a confirmation
   $html -> send_header ("The moderator has been assigned",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("The moderator has been assigned");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "$User has been assigned to moderate the forums: $allboards.  You will now be returned to the main admin page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
