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

// ------------
// Set Defaults.
   if (!$HTML) { $HTML = "Off"; }
   if (!$Markup) { $Markup = "Off"; }
   if (!$Moderated) { $Moderated = "no"; }

// ---------------------------------------------
// Check to make sure all info has been filled in
   if((!$Title)||(!$Keyword)||(!$Description)){
      $html -> not_right("All of the required fields are not filled in.",$Cat);
   }

// Check for special characters
   if (preg_match("/\W/",$Keyword)) {
      $html -> not_right("Keywords cannot have any special characters.",$Cat);
   }


// ---------------------
// Grab the current time
   $date = $html -> get_date();

// -------------------------------
// Put the board into the database
   $Title_q       = addslashes($Title);
   $Description_q = addslashes($Description);
   $Username_q    = addslashes($user['U_Username']);
   $HTML_q        = addslashes($HTML);
   $Markup_q      = addslashes($Markup);
   $Keyword_q     = addslashes($Keyword);
   list($Catn,$Catnum) = split("-PARSER-",$Category);
   $Category_q    = addslashes($Catn);
   $CatName       = addslashes($Catn);
   $Moderated_q   = addslashes($Moderated);
   $ThreadAge     = addslashes($ThreadAge);
   $stylesheet    = addslashes($stylesheet);

// -------------------------------
// Now let's get all of the groups
   $query = "
      SELECT G_Name,G_Id
      FROM   {$config['tbprefix']}Groups
   ";
   $sth = $dbh -> do_query($query);

// -------------------------------------
// Now we chug through all of the groups
   $total = 0;
   $CanRead = "-";
   $CanWrite = "-";
   $CanReply = "-";

   while (list($Name,$Id) = $dbh -> fetch_array($sth)) {
      $total++;
      $which = "GROUP$Id";
      if ($HTTP_POST_VARS[$which] == "read") {
         $CanRead .= "$Id-";
      }
      if ($HTTP_POST_VARS[$which] == "write") {
         $CanRead .= "$Id-";
         $CanWrite .= "$Id-";
         $CanReply .="$Id-";
      }
      if ($HTTP_POST_VARS[$which] == "reply") {
         $CanRead .= "$Id-";
         $CanReply .= "$Id-";
      }
   }

   $query = "
     INSERT INTO {$config['tbprefix']}Boards (Bo_Title,Bo_Description,Bo_Keyword,Bo_Total,Bo_Last,Bo_HTML,Bo_Created,Bo_Expire,Bo_Markup,Bo_Cat,Bo_Moderated,Bo_CatName,Bo_Read_Perm,Bo_Write_Perm,Bo_ThreadAge,Bo_Reply_Perm,Bo_Moderators,Bo_SpecialHeader,Bo_StyleSheet)
     VALUES ('$Title_q', '$Description_q', '$Keyword_q','0','0','$HTML_q','$date','$Expires','$Markup_q','$Catnum','$Moderated_q','$CatName','$CanRead','$CanWrite','$ThreadAge','$CanReply',',$Username_q,','$header','$stylesheet')
   ";
   $dbh -> do_query($query);

// --------------------------------
// Put it into the moderators table
   $query = "
    INSERT INTO {$config['tbprefix']}Moderators (Mod_Username,Mod_Board)
    VALUES ('$Username_q','$Keyword_q')
   ";
   $dbh -> do_query($query);

// ------------------------
// Send them a confirmation
   $html -> send_header ("The new forum has been created.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("The new forum has been created.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo " The new forum has been created and is now available for use by the users.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
