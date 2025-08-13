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


// -------------------------------------------------
// Variables Section - Edit these to suit your needs

  // Path to your main.inc.php script
   $path = "";

  // Set this variable to the Keyword of the board you want to use for your
  // news items
   $newsboard = 	'announcements';

  // Set this variable to the total number of news items that you wish to show
   $totalnews = 	10;

  // This varialbe will affect the way the news is listed.  Set this to none
  // and it will only show the News Subject, with a link pointing to the news
  // item on the board.  Set it to "full" and it will show the body of the
  // news item below the subject.  Set it to a number and it will show
  // that many characters of the body with the subject clickable to read the
  // full news item.
   $newsbody = 	'none';

  // The url to your UBB.threads installation
   $forumurl = 	"";

  // Include the date when the news was posted
   $includedate = 	"no";

  // Include who it was posted by
   $includewho = 	"no";


// Require the library
   require ("$path/main.inc.php");

// --------------------------
// Let's grab the news items
   $Newsboard_q = addslashes($newsboard);
   $query = "
    SELECT B_Number,B_Posted,B_Last_Post,B_Username,B_Subject,B_Body,B_Main
    FROM  {$config['tbprefix']}Posts 
    WHERE B_Number = B_Main
    AND   B_Board  = '$Newsboard_q'
    AND   B_Approved = 'yes'
    ORDER BY B_Last_Post DESC
    LIMIT $totalnews 
   ";
   $sth = $dbh -> do_query($query);

// ---------------------------
// echo out the starting html
   $html = new html;

   echo "<DL>";

// -----------------------
// Cycle through the posts
   while ( list($Number,$Posted,$Last,$Username,$Subject,$Body,$Main) = $dbh -> fetch_array($sth)) {

  // ---------------------------------
  // Set up the variables for echoing
     $date = $html -> convert_time($Posted);
     if ($newsbody == "none") {
        $Body = "";
        $Subject = "<a href=\"$forumurl/showflat.php?Board=$newsboard&Number=$Number\">$Subject</a>";
     }
     if (ereg("[0123456789]",$newsbody)) {
        $Body =substr($Body,0,$newsbody);
        $Body .="...";
        $Subject = "<a href=\"$forumurl/showflat.php?Board=$newsboard&Number=$Number\">$Subject</a>";
     }
     if ($includedate == "yes") {
        $date = "<br>Posted on $date";
     }
     else {
        $date = "";
     }
     if ($includewho == "yes") {
        $Username = "<br>Posted by $Username";
     }
     else {
        $Username = "";
     }

  // -------------------------------
  // The actual HTML that is put out
     echo "
        <DT><b>$Subject</b>
        $date
        $Username
        <DD>$Body
     ";

  // ---------------
  // End of the HTML

   }
   echo "<p align=right><i><a href=\"$forumurl/postlist.php?action=list&Board=$newsboard\">...More News</a></i>";
   echo "</DL>";
   $dbh -> finish_sth($sth);

?>
