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
// Verify_delete function
// ########################################################################
function verify_delete ($Number="", $Status="", $Cat="") {

   $html = new html;

   global $config,$dbh;

// ---------------------------
// Make sure they are an admin
   if ($Status != 'Administrator') {
      $html -> not_right("You must be an Admin to delete a forum or category.",$Cat);
   }

   $html -> send_header("Delete this category",$Cat,0,$user);
   $html -> admin_table_header("Delete this category");


// ---------------------------
// Grab the title of the category
   $query = "
    SELECT Cat_Title
    FROM   {$config['tbprefix']}Category 
    WHERE  Cat_Number = '$Number'
   ";
   $sth = $dbh -> do_query($query);
   list($Title) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   $html -> open_admin_table();
   echo "<tr><td class=\"lighttable\">";
   echo "You have chosen to delete the following category:<p>";
   echo "<b>$Title</b><br>";
   echo "<p>If you are sure you want to delete this category, then select the 'confirm delete' below.<br><br>";
   echo "<form method=post action = \"{$config['phpurl']}/admin/deletecat.php\">\n";
   echo "<input type=hidden name=Cat value=\"$Cat\">\n";
   echo "<input type=hidden name=Number value=$Number>\n";
   echo "<input type=submit value = \"Confirm delete\" class=\"buttons\">\n";
   echo "</FORM>";
   $html -> close_table();
   $html -> send_admin_footer();
   exit();

}

// ########################################################################
// End of function
// ########################################################################



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

// -------------------------------------------------------------------
// If they chose to delete cat board then we go to the verify section
   if ($option == "Delete this category"){
      verify_delete($Number,$user['U_Status'],$Cat);
   }


// -----------------------------------------------
// Grab the cat info 
   $query = "
    SELECT Cat_Title,Cat_Description
    FROM   {$config['tbprefix']}Category 
    WHERE  Cat_Number = $Number
   ";
   $sth = $dbh -> do_query($query);
   list($Title,$Description) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   $Title = str_replace("\"","&quot;",$Title);
   $Description = str_replace("\"","&quot;",$Description);

// ------------------------
// Send them a page 
   $html -> send_header ("Edit this category",$Cat,0,$user);
   $html -> admin_table_header("Edit this category");
   $html -> open_admin_table();
   echo "
    <TR><TD class=\"lighttable\">
    You can edit the category name below.<p>
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doeditcat.php\">
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <input type=hidden name=Number value=$Number>
    Category Title<br>
    <input type=text name = Title size=60 value = \"$Title\" class=\"formboxes\">
    <br><br>
    Category Description<br>
    <input type=text name = Description size=60 class=\"formboxes\" value = \"$Description\">
    <br><br>
    <input type=submit value = \"Submit\" class=\"buttons\">
    </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();



?>
