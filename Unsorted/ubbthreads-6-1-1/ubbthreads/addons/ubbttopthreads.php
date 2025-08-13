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
   $path = "/home/httpd/html/wwwthreads.com/php";

  // Top threads by "views" or by "replies"
   $topthreads =	"replies";

  // Post age threshold.  How many days old can a thread be and still make
  // the list
   $threadage =	7;

  // Set this variable to the total number of threads that you wish to show
   $totalthreads = 	10;

  // This varialbe will affect the way the thread is listed.  Set this to none
  // and it will only show the thread Subject, with a link pointing to the 
  // item on the board.  Set it to "full" and it will show the body of the
  // item below the subject.  Set it to a number and it will show
  // that many characters of the body with the subject clickable to read the
  // full item.
   $threadbody = 	'none';

  // The url to your UBB.threads installation
   $forumurl = 	"http://www.wwwthreads.com/php";

  // Include the date when the news was posted
   $includedate = 	"no";

  // Include who it was posted by
   $includewho = 	"no";

  // You may need to change the unshift line to the path of your UBB.threads
  // installation

// -----------------------------
// End of the variables section
   require ("$path/main.inc.php");

// -------------------
// Setup the variables
   $html = new html;
   $date = $html -> get_date();
   $date = $date - ($threadage * 86400);

   $sort;
   if ($topthreads == "views") {
      $sort = "B_Counter";
   }
   else {
      $sort = "B_Replies";
   }

// --------------------------
// Let's grab the news items
   $query = "
    SELECT B_Number,B_Posted,B_Last_Post,B_Username,B_Subject,B_Body,B_Main,B_Replies,B_Counter,B_Board
    FROM  {$config['tbprefix']}Posts 
    WHERE B_Number = B_Main
    AND   B_Last_Post > '$date' 
    ORDER BY $sort DESC
    LIMIT $totalthreads
   ";
   $sth = $dbh -> do_query($query);

// ---------------------------
// echo out the starting html
   echo "<DL>";

// -----------------------
// Cycle through the posts
   while ( list($Number,$Posted,$Last,$Username,$Subject,$Body,$Main,$Replies,$Counter,$Keyword) = $dbh -> fetch_array($sth)) {

   // ---------------------------------
   // Set up the variables for echoing
      $date = $html -> convert_time($Posted);
      if ($threadbody == "none") {
         $Body = "";
         $Subject = "<a href=\"$forumurl/showflat.php?Board=$Keyword&Number=$Number\">$Subject</a>";
      }
      if (ereg("[0123456789]",$threadbody)) {
         $Body =substr($Body,0,$threadbody);
         $Body .="...";
         $Subject = "<a href=\"$forumurl/showflat.php?Board=$Keyword&Number=$Number\">$Subject</a>";
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
   echo "</DL>";
   $dbh -> finish_sth($sth);

?>
