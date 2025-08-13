<? 
/*
# UBB.threads, Version 6
# Official Release Date for UBB.threads Version6: NO DATE YET

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
   require ("../../main.inc.php");
   $html = new html;

// ----------------------------------------------------
// First we grab the board keywords and the expire time
   $query = " 
    SELECT Bo_Keyword,
           Bo_Expire
    FROM   {$config['tbprefix']}Boards
    WHERE  Bo_Expire <> 0
   ";
   $sth = $dbh -> do_query($query);

// ----------------------------------------------------------
// Now we cycle through all the boards that have an expire set
   while (list($Keyword,$Expires) = $dbh -> fetch_array($sth)) {
      $currtime   = $html -> get_date();
      $expiretime = $currtime - ($Expires * 86400);
      $Kept_q     = "K";
      $Keyword_q  = addslashes($Keyword);
      $query = " 
         SELECT B_Number,
                B_Main 
         FROM   {$config['tbprefix']}Posts 
         WHERE  B_Main      = B_Number
         AND    B_Kept      <> '$Kept_q'
         AND    B_Last_Post < '$expiretime'
         AND    B_Board     = '$Keyword_q'
      ";
      $sti = $dbh -> do_query($query);

   // -------------------------------------------------
   // Now we cycle through these threads and purge them
   // and remove them from the keywords table
      $Keyword_q = addslashes($Keyword);
      $totalexpired = 0;
      $totalthreads = 0;
      $Approved = 0;
      while( list($Mnumber,$Main) = $dbh -> fetch_array($sti)) {

   // ---------------------------------------------------------------------
   // Find out if there are any files to delete and if the post is approved
        $query = "
           SELECT B_Number
           FROM   {$config['tbprefix']}Posts 
           WHERE  B_Main  = '$Mnumber'
           AND    B_Board = '$Keyword_q'
        "; 
        $sta = $dbh -> do_query($query);
        while ( list($checkee) = $dbh -> fetch_array($sta)) {
           $query = " 
             SELECT B_File,
                    B_Approved,
                    B_Poll
             FROM   {$config['tbprefix']}Posts 
             WHERE  B_Number = '$checkee'
             AND    B_Board  = '$Keyword_q'
           "; 
           $stk = $dbh -> do_query($query);
           list($filename,$Approved,$Poll) = $dbh -> fetch_array($stk);
           if ($filename) {
              @unlink("{$config['files']}/$filename");
           }

        // ------------------------------------------
        // If we have a poll for this post, delete it
           if ($Poll) {
              $Poll = addslashes($Poll);
              $query = " 
                 DELETE FROM {$config['tbprefix']}Polls
                 WHERE  P_Name = '$Poll'
              "; 
              $dbh -> do_query($query);
              $query = " 
                 DELETE FROM {$config['tbprefix']}PollData
                 WHERE  P_Name = '$Poll'
              "; 
              $dbh -> do_query($query);
           }    

        }

        $query = " 
           SELECT COUNT(*) FROM {$config['tbprefix']}Posts 
           WHERE B_Main  = '$Mnumber'  
           AND   B_Board = '$Keyword_q'
           AND   B_Approved = 'yes'
        ";
        $stx = $dbh -> do_query($query);
        list($select) = $dbh -> fetch_array($stx);
        $query = " 
           DELETE FROM {$config['tbprefix']}Posts 
           WHERE B_Main  = '$Mnumber'  
           AND   B_Board = '$Keyword_q'
        ";
        $rc = $dbh -> do_query($query);
        $totalexpired = $totalexpired + ($select);
        $totalthreads++;
     }

  // ---------------------------------------------
  // Now we update the board to set the total posts
     $Keyword_q = addslashes($Keyword);

     $query = " 
      UPDATE {$config['tbprefix']}Boards
      SET Bo_Total = Bo_Total - $totalexpired,
          Bo_Threads = Bo_Threads - $totalthreads
      WHERE Bo_Keyword = '$Keyword_q'
     ";
     $dbh -> do_query($query); 
  

  }
 
?> 
