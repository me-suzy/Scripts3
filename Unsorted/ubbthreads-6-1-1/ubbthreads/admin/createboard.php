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

// ------------------------------------------
// Give them a form so they can create a board
   $html -> send_header("Create a forum",$Cat,0,$user);
   $html -> admin_table_header("Create a forum");
   $html -> open_admin_table();
   echo "
     <tr class=\"lighttable\"><td>
     Fill in the following information to create a forum. 
     <br><br>
     <form method=POST action = \"{$config['phpurl']}/admin/docreate.php\">
     <INPUT TYPE=HIDDEN NAME=Cat VALUE=\"$Cat\">
     Forum Title<br>
     <input type=text name = Title class=\"formboxes\">
     <br><br>
     Keyword (Used for the name of the sql table - NO SPECIAL CHARACTERS)<br>
     <input type=text name = Keyword class=\"formboxes\">
     <br><br>
     Description<br>
     <textarea name=Description cols=60 rows=5 wrap=soft class=\"formboxes\"></textarea>
     <br><br>
     Allow HTML<br>
     <input type=radio name=HTML value=On class=\"formboxes\"> Yes
     <input type=radio name=HTML value=Off class=\"formboxes\"> No
     <br><br>
     Allow Markup<br>
     <input type=radio name=Markup value=On class=\"formboxes\"> Yes
     <input type=radio name=Markup value=Off class=\"formboxes\"> No
     <br><br>
     Expire threads after how many days (0 - Never Expire)<br>
     <input type=text name=Expires value=0 size=3 class=\"formboxes\">
     <br><br>
     Do you want this forum to use real moderation (posts must be approved by admin or moderator)?<br>
     <input type=radio name=Moderated value=\"yes\" class=\"formboxes\"> Yes
     <input type=radio name=Moderated value=\"no\" class=\"formboxes\"> No
     <br><br>
     What category do you want to place this forum in<br>
     <select name=Category class=\"formboxes\">
    
   ";

   $query = "
     SELECT DISTINCT Cat_Title,Cat_Number 
     FROM            {$config['tbprefix']}Category 
     ORDER BY        Cat_Title
   ";
   $sth = $dbh -> do_query($query);
   while (list($Catn,$Catnum) = $dbh -> fetch_array($sth) ) {
      $catname = "$Catn"."-PARSER-"."$Catnum";
      echo "<option value=\"$catname\">$Catn";
   }
   $dbh -> finish_sth($sth);

   echo "
    </select>

    <p>
    Default aged threads to be displayed for this forum:<br>
      <select name=ThreadAge class=formboxes>
        <option value=1>Active in the last  day
        <option value=2>Active in the last 2 days
        <option value=7>Active in the last week
        <option value=14>Active in the last 2 weeks
        <option value=21>Active in the last 3 weeks
        <option value=31>Active in the last month
        <option value=93>Active in the last 3 months
        <option value=186>Active in the last 6 months
        <option value=365>Active in the last year
        <option value=0 selected>Show all threads
      </select>

    <p>Use a unique header/footer for this forum?  (If no, then it will use the header.php and footer.php files)<br />
    <select name=header class=formboxes>
      <option value=1>Yes</option>
      <option value=0 SELECTED>No</option>
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
      if ($StyleSheet == $style) {
         $extra = "selected";
      }
      echo "<option value=\"$style\" $extra>$desc";
   }
   echo "
    </select>
    <p>
    What read/write access do you want to setup for your groups?  The highest privileges will apply to members of multiple groups.<br>
   ";
   
// --------------------------------
// Now let's list all of the groups
   $query = "
      SELECT G_Name, G_Id
      FROM   {$config['tbprefix']}Groups
   ";
   $sth = $dbh -> do_query($query);
   echo "
      <TABLE BORDER=1>
      <TR><TD CLASS=darktable width=15%>
        No access
      </TD><TD class=darktable width=15%>
        Read only
      </TD><TD class=darktable width=15%>
        Read and reply only
      </TD><TD class=darktable width=15%>
        Read and write
      </td><td class=darktable width=55%>
        Group Name
      </td></tr>
   ";

// --------------------------------------------------------
// Setup the default groups that have read and write access
   $Groups = "-1-2-3-4-";
   $WGroups = "-1-2-3-4-";
   $RGroups = "-1-2-3-4-";

   while (list($Name,$Id) = $dbh -> fetch_array($sth)) {
      echo "<TR class=\"lighttable\">";
      echo "<TD class=\"lighttable\">";
      $none = "";
      $read = "";
      $write = "";
      $reply = "";
      $checky = "-$Id-";
      if (ereg("$checky",$WGroups)) {
         $write = "CHECKED";
      }
      if ( (ereg("$checky",$RGroups)) && (!$write)) {
         $reply = "CHECKED";
      }
      if ( (ereg("$checky",$Groups)) && (!$write) && (!$reply) ) {
         $read = "CHECKED";
      }
      if ( (!$write) && (!$read) ) {
         $none = "CHECKED";
      }

      echo "<INPUT TYPE=RADIO NAME=\"GROUP$Id\" $none VALUE=\"none\" class=\"formboxes\"></TD><TD class=lighttable> ";
      echo "<INPUT TYPE=RADIO NAME=\"GROUP$Id\" $read VALUE=\"read\" class=\"formboxes\"></TD><TD class=lighttable> ";
      echo "<INPUT TYPE=RADIO NAME=\"GROUP$Id\" $reply VALUE=\"reply\" class=\"formboxes\"></TD><TD class=lighttable> ";
      echo "<INPUT TYPE=RADIO NAME=\"GROUP$Id\" $write VALUE=\"write\" class=\"formboxes\"></TD>";
      echo "<TD CLASS=lighttable>$Name</TD></TR>";
   }
   
   echo " 
    </TABLE>
    <br><br>
    <input type=submit value=\"Submit\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();

// --------------
// Send the footer
   $html -> send_admin_footer();

?>
