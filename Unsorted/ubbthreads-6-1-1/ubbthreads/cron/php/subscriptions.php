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
   require ("/www/htdocs/ubbthreads/main.inc.php");
   require ("$thispath/languages/${$config['cookieprefix']."w3t_language"}/subscriptions.php");
   $html = new html;
   $mailer = new mailer;

// ---------------------------------------------- 
// First we grab all boards that have subscribers
   $query = " 
     SELECT DISTINCT S_Board,MAX(S_Last)
     FROM   {$config['tbprefix']}Subscribe
     GROUP BY S_Board
   ";
   $sth = $dbh -> do_query($query);
   $rows = $dbh -> total_rows($sth); 

   $ostype = find_environmental("OSTYPE");
   $newline = "\n";
   if (stristr($ostype,"win")) {
      $newline = "\r\n";
   }


// ----------------------------------------------------------------
// Now we have the boards we need to cycle through the subscribers 
   for ($j = 0;$j < $rows; $j++ ) {
      list($Board,$Last) = $dbh -> fetch_array($sth);
      $Board_q       = addslashes($Board);

      $query = " 
         SELECT Bo_Title,Bo_Read_Perm
         FROM   {$config['tbprefix']}Boards
         WHERE  Bo_Keyword = '$Board_q'
      ";
      $sta = $dbh -> do_query($query); 
      list($Title,$ReadPerm) = $dbh -> fetch_array($sta); 

   // ------------------------------
   // Now we grab all the posts info 
      $query = " 
         SELECT   B_Number,B_Posted,B_Username,B_IP,B_Subject,B_Body
         FROM     {$config['tbprefix']}Posts 
         WHERE    B_Number > $Last
         AND      B_Approved = 'yes'
         AND      B_Board = '$Board_q'
         ORDER BY B_Number ASC
      "; 
      $stk = $dbh -> do_query($query);
      $postrows = $dbh -> total_rows($stk);
      $seperator = "----------------------------------------------------------------------\n";
      $message  = "{$ubbt_lang['SUB_DESC2']}\n";  
      $message  = $message . "{$ubbt_lang['SUB_DESC']} '$Title' Forum.\n$seperator\n\n";  

   // ------------------------------------------------------
   // Only send out an email if there are posts for the day
      if($postrows > 0){

      // --------------------------------------------------------
      // Now we cycle through the posts and append to the message
         for ($l =0; $l < $postrows; $l++) {
            list($Number,$PPosted,$PUsername,$PIP,$PSubject,$PBody) = $dbh -> fetch_array($stk);
            $message = $message .   "{$ubbt_lang['SUBJECT_TEXT']}: $PSubject\n";
            $message = $message .   "{$ubbt_lang['POSTER_TEXT']}: $PUsername\n";
            $date = $html -> convert_time($PPosted);
            $message = $message .   "{$ubbt_lang['POSTON_TEXT']}: $date\n";
            $PBody = str_replace("<br>","\n",$PBody);
            $PBody = str_replace("<br />","\n",$PBody);
            $PBody = str_replace("&lt;br&gt;","\n",$PBody);
            $message = $message . "\n$PBody\n\n";
            $message = $message . "$seperator\n\n";

         }

      // ------------------------------------
      // Now we need to update the last entry
         $query = " 
            UPDATE {$config['tbprefix']}Subscribe
            SET    S_Last = $Number
            WHERE  S_Board = '$Board_q' 
         "; 
         $dbh -> do_query($query);

      // -----------------------------------------
      // Now grab everyone that we need to mail to
         $query = " 
            SELECT S_Username
            FROM   {$config['tbprefix']}Subscribe
            WHERE  S_Board = '$Board_q'
         ";
         $sti = $dbh -> do_query($query); 
         $userrows = $dbh -> total_rows($sti);

      // ---------------------------------------------------------
      // Now that we have all the users we need to cycle through 
         for ($k = 0; $k < $userrows; $k++) {

         // ---------------------------------------
         // Now we need to grab their email address
            list($Username)   = $dbh -> fetch_array($sti);
            $Username_q = addslashes($Username);
            $query = " 
               SELECT U_Email,U_TimeOffset,U_Groups,U_EmailFormat
               FROM   {$config['tbprefix']}Users
               WHERE  U_Username = '$Username_q'
            "; 
            $stj = $dbh -> do_query($query);
            list($Email,$TimeOffset,$Groups,$emailformat) = $dbh -> fetch_array($stj);
            if (!$Email) { continue; }
            $query = "
               SELECT B_Username
               FROM   {$config['tbprefix']}Banned
               WHERE  B_Username='$Username_q'
            ";
            $stx = $dbh -> do_query($query);
            list ($Checkuser) = $dbh -> fetch_array($stx);
            $dbh -> finish_sth($stx);
			   if ($Checkuser) {
				    continue;
				}

            $Grouparray = split("-",$Groups);
            $canread = 0;
            $gsize = sizeof($Grouparray);
            for ($i=0; $i<$gsize; $i++) {
               if (ereg("-$Grouparray[$i]-",$ReadPerm) ) {
                  $canread = 1;
               }
            }
            if (!$canread) { continue; }

				if ($emailformat == "HTML") {
            	$message = str_replace("\"{$config['images']}","\"{$config['imageurl']}",$message);
					$message = str_replace("\n","<br />",$message);
				}

				$header = $mailer -> headers($emailformat);
            $to   = $Email;
            $from = $config['emailaddy'];
            $subject = "{$ubbt_lang['SUB_INTRO']} $Title";
            mail("$to","$subject","$message",$header);

         }
      } 
   }

?>
