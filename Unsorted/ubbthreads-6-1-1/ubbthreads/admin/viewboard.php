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

// ########################################################################
// Verify that they want to delete this board
// ########################################################################

function verify_delete($Number="", $Status="", $Cat="") {

   global $config, $dbh;
   $html = new html;

// ---------------------------
// Make sure they are an admin
   if ($Status != 'Administrator') {
      $html -> not_right("You must be an Admin to delete a forum or category.",$Cat);
   }

   $html -> send_header("Delete this forum",$Cat,0,$user);
   $html -> admin_table_header("Delete this forum");

// ---------------------------
// Grab the title of the board
   $query = "
    SELECT Bo_Title,Bo_Keyword,Bo_Description
    FROM  {$config['tbprefix']}Boards
    WHERE Bo_Number = '$Number'
   ";
   $sth = $dbh -> do_query($query);
   list($Title,$Keyword,$Description) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);


   $html -> open_admin_table();
   echo "<tr><td class=\"lighttable\">";
   echo "You have chosen to delete the following forum:<p>";
   echo "<b>$Title</b><br>";
   echo "$Description";
   echo "<p>If you are sure you want to delete this forum, then select the 'confirm delete' button below.<br><br>";
   echo "<form method=post action = \"{$config['phpurl']}/admin/deleteboard.php\">\n";
   echo "<input type=hidden name=Cat value=\"$Cat\">";
   echo "<input type=hidden name=Number value=$Number>\n";
   echo "<input type=hidden name=Keyword value=\"$Keyword\">\n";
   echo "<input type=submit value = \"Confirm delete\" class=\"buttons\">\n";
   echo "</form>";

   $html -> close_table();
   $html -> send_admin_footer();
   exit;
}

// ##################################################################
// End of function
// ##################################################################



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

// Make sure they didn't choose a category:
   if ($Number == "category") {
      $html -> not_right("You must choose a forum, not a category.",$Cat);
   }

// -------------------------------------------------------------------
// If they chose to delete this board then we go to the verify section
   if ($option == "Delete this forum"){
      verify_delete($Number,$user['U_Status'],$Cat);
   }

// -----------------------------------------------
// Grab the board info 
   $query = "
    SELECT Bo_Title,Bo_Keyword,Bo_Description,Bo_HTML,Bo_Expire,Bo_Markup,Bo_Moderated,Bo_CatName,Bo_Cat,Bo_Read_Perm,Bo_Write_Perm,Bo_ThreadAge,Bo_Reply_Perm,Bo_SpecialHeader,Bo_StyleSheet
    FROM  {$config['tbprefix']}Boards
    WHERE Bo_Number = '$Number'
   ";
   $sth = $dbh -> do_query($query);

   list($Title,$Keyword,$Description,$HTML,$Expires,$Markup,$Moderated,$Cattemp,$Catnum,$Groups,$WGroups,$ThreadAge,$RGroups,$header,$stylesheet) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if ($stylesheet == "usedefault") {
      $defselected = "selected";
   }

   if ($HTML == "On") {
      $HTMLOn = "checked";
   }
   else {
      $HTMLOff = "checked";
   }

   if ($Markup == "On") {
      $MarkupOn = "checked";
   }
   else {
      $MarkupOff = "checked";
   }

   if ($Moderated == "yes") {
      $ModeratedOn = "checked";
   }
   else {
      $ModeratedOff = "checked";
   }

   if ($header) {
      $unique = "SELECTED";
   }
   else {
      $nounique = "SELECTED";
   }

   if ($ThreadAge == "1") {
      $d1 = "SELECTED";
   } elseif ($ThreadAge == "2") {
      $d2 = "SELECTED";
   } elseif ($ThreadAge == "7") {
      $w1 = "SELECTED";
   } elseif ($ThreadAge == "14") {
      $w2 = "SELECTED";
   } elseif ($ThreadAge == "21") {
      $w3 = "SELECTED";
   } elseif ($ThreadAge == "31") {
      $m1 = "SELECTED";
   } elseif ($ThreadAge == "93") {
      $m3 = "SELECTED";
   } elseif ($ThreadAge == "186") {
      $m6 = "SELECTED";
   } elseif ($ThreadAge == "365") {
      $y1 = "SELECTED";
   } else {
      $allofthem = "SELECTED";
   }

// ------------------------
// Send them a page 
   $html -> send_header ("Edit this forum",$Cat,0,$user);
   $html -> admin_table_header("Edit this forum");
   $html -> open_admin_table();

   echo "
    <TR><TD class=\"lighttable\">
    You can edit any of the information below.<p>
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doeditboard.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <input type=hidden name=Number value=$Number>
    Board Title<br>
    <input type=text name = Title value = \"$Title\" class=\"formboxes\">
    <br><br>

    Description<br>
    <textarea name=Description cols=60 rows=5 wrap=soft class=\"formboxes\">$Description</textarea>
    <br><br>
    
    Allow HTML<br>
    <input type=radio name=HTML value=On $HTMLOn class=\"formboxes\"> Yes 
    <input type=radio name=HTML value=Off $HTMLOff class=\"formboxes\"> No 
    <br><br>

    Allow Markup<br>
    <input type=radio name=Markup value=On $MarkupOn class=\"formboxes\"> Yes 
    <input type=radio name=Markup value=Off $MarkupOff class=\"formboxes\"> No 
    <br><br>

    Expire threads after how many days (0 - Never Expire)<br>
    <input type=text name=Expires value = $Expires size=3 class=\"formboxes\">
    <br><br>

    Do you want this forum to use real moderation (posts must be approved by admin or moderator)?<br>
    <input type=radio name=Moderated value=\"yes\" $ModeratedOn class=\"formboxes\"> Yes 
    <input type=radio name=Moderated value=\"no\" $ModeratedOff class=\"formboxes\"> No 
    <br><br>  
    What category do you want to place this forum in<br>
    <select name=Category class=\"formboxes\">
   ";


   $query = "
      SELECT DISTINCT Cat_Title,Cat_Number FROM {$config['tbprefix']}Category ORDER BY Cat_Title
   ";
   $sth = $dbh -> do_query($query);
   while ( list($Catlist,$Catnumlist) = $dbh -> fetch_array($sth)) {
      $catname = "$Catlist"."-PARSER-"."$Catnumlist";
      $thisisit = "";
      if ($Catlist == $Cattemp) {
        $thisisit = "SELECTED";
      }
      echo "<option value=\"$catname\" $thisisit>$Catlist";
   }
   $dbh -> finish_sth($sth);


   echo "
    </select>

    <p>
    Default aged threads to be displayed for this forum:<br>
    <select name=ThreadAge class=formboxes>
      <option value=1 $d1>Active in the last  day
      <option value=2 $d2>Active in the last 2 days
      <option value=7 $w1>Active in the last week
      <option value=14 $w2>Active in the last 2 weeks
      <option value=21 $w3>Active in the last 3 weeks
      <option value=31 $m1>Active in the last month
      <option value=93 $m3>Active in the last 3 months
      <option value=186 $m6>Active in the last 6 months
      <option value=365 $y1>Active in the last year
      <option value=0 $allofthem>Show all threads
    </select>

    <p>Use a unique header/footer for this forum?  (If no, then it will use the header.php and footer.php files)<br />
    <select name=header class=formboxes>
      <option value=1 $unique>Yes</option>
      <option value=0 $nounique>No</option>
    </select> 

    <p>What stylesheet do you want to use for this forum?<br>
    <select name=\"stylesheet\" class=formboxes>
    <option value=\"usedefault\" $defselected>Use default</option>
  ";

// ------------------------
// List out the stylesheets
   $stylenames = split(",",$theme['availablestyles']);
   $stylesize = sizeof($stylenames);
   for ( $i=0;$i<$stylesize;$i++) {
      list($style,$desc) = split(":",$stylenames[$i]);
      $style = preg_replace("/^\s+/","",$style);
      $stylenames[$style] = $desc;
      $extra = "";
      if ($stylesheet == $style) {
         $extra = "selected";
      }
      echo "<option value=\"$style\" $extra>$desc";
   }
   echo "</select>";

   echo "
    <input type=hidden name=\"CurrentCat\" value=\"$Cattemp\">
    <br><br>
    What read/write access do you want to setup for your groups?  The highest privileges will apply to members of multiple groups.<br>
   ";

// --------------------------
// List out all of the groups
   $query = "
      SELECT G_Name, G_Id
      FROM   {$config['tbprefix']}Groups
   ";
   $sth = $dbh -> do_query($query);
   echo "
    <TABLE border=1>
    <TR><TD class=darktable width=15%>
      No access
    </TD><TD class=darktable width=15%>
      Read only
    </TD><TD class=darktable width=15%>
      Read and reply only 
    </TD><TD class=darktable width=15%>
      Read and write
    </TD><TD class=darktable width=40%>
      Group Name
    </TD></TR>
   ";
   while (list($Name,$Id) = $dbh -> fetch_array($sth)) {
      echo "<TR CLASS=\"lighttable\"><TD CLASS=\"lighttable\">";
      $none = "";
      $read = "";
      $write = "";
      $reply = "";
      $checky = "-$Id-";
      if (ereg("$checky",$WGroups)) {
         $write = "CHECKED";
      }
      if ( (ereg("$checky",$RGroups)) && (!$write) ) {
         $reply = "CHECKED";
      }
      if ( (ereg("$checky",$Groups)) && (!$write) && (!$reply) ) {
         $read = "CHECKED";
      }
      if ( (!$write) && (!$read) ) {
         $none = "CHECKED";
      }
      echo "<INPUT TYPE=RADIO NAME=\"GROUP$Id\" $none VALUE=\"none\" class=\"formboxes\"></TD><TD class=lighttable> ";
      echo "<INPUT TYPE=RADIO NAME=\"GROUP$Id\" $read VALUE=\"read\" class=\"formboxes\"></TD><td class=lighttable> ";
      echo "<INPUT TYPE=RADIO NAME=\"GROUP$Id\" $reply VALUE=\"reply\" class=\"formboxes\"></TD><td class=lighttable> ";
      echo "<INPUT TYPE=RADIO NAME=\"GROUP$Id\" $write VALUE=\"write\" class=\"formboxes\"></TD> ";
      echo "<TD class=lighttable>$Name</TD></TR>";
   }

   echo"
    </TABLE>
    <input type=submit value = \"Submit\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();


?>
