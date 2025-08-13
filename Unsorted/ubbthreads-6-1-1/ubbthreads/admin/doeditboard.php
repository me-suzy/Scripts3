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

// -------------------------------
// Grab the keyword for this board
   $query = "
    SELECT Bo_Keyword
    FROM  {$config['tbprefix']}Boards
    WHERE Bo_Number = '$Number'
   ";
   $sth = $dbh -> do_query($query);
   list($Keyword) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

// ----------------
// Update the board
   list($Cattemp,$Catnum) = split("-PARSER-",$Category);
   $Title_q       = addslashes($Title);
   $Description_q = addslashes($Description);
   $HTML_q        = addslashes($HTML);
   $Markup_q      = addslashes($Markup);
   $Moderated_q   = addslashes($Moderated);
   $CatName_q     = addslashes($Cattemp);
   $stylesheet    = addslashes($stylesheet);

// --------------------------
// Make sure we hafe a Catnum
	$extra = "";
	if ($Catnum) {
		$extra = ",Bo_Cat = $Catnum, Bo_CatName = '$CatName_q'";
	}


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
         $CanReply .= "$Id-";
      }
      if ($HTTP_POST_VARS[$which] == "reply") {
         $CanRead .= "$Id-";
         $CanReply .= "$Id-";
      }
   }

   $query = "
    UPDATE {$config['tbprefix']}Boards
    SET Bo_Title='$Title_q', Bo_Description='$Description_q', Bo_HTML='$HTML_q', Bo_Expire = '$Expires', Bo_Markup = '$Markup_q', Bo_Moderated = '$Moderated_q', Bo_Read_Perm = '$CanRead',Bo_Write_Perm='$CanWrite',Bo_Reply_Perm='$CanReply',Bo_ThreadAge='$ThreadAge',Bo_SpecialHeader='$header', Bo_StyleSheet='$stylesheet' $extra
    WHERE Bo_Number = '$Number'
   ";
   $dbh -> do_query($query);

// ------------------------
// Send them a confirmation
   $html -> send_header ("The forum has been changed.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("The forum has been changed.");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
   echo "The forum has been changed.  You will now be returned to the main administration page.";
   echo "</span></TD></TR></TABLE>";
   $html -> send_admin_footer();

?>
