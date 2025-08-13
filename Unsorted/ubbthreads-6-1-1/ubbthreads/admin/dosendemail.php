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
   $mailer = new mailer;

// -----------------------------
// Make sure they should be here
   if ($user['U_Status'] != 'Administrator'){
      $html -> not_right ("You must be logged in, and be a valid administrator to access this.",$Cat);
   }

// What type of line breaks
   $newline = "\n";
   if (stristr(PHP_OS,"win")) {
      $newline = "\r\n";
   }

// ------------------------------------------------------------
// If all required info is not filled in, then we can't proceed 
   if((!$Subject)||(!$Body)){
      $html -> not_right("You must have a subject and body to be mailed",$Cat);
   }

// -----------------------------------
// Get the group #'s ready for display
	$query = "
		SELECT G_Name,G_Id
		FROM	 {$config['tbprefix']}Groups
	";
	$sth = $dbh -> do_query($query);
	while(list($gname,$gid) = $dbh -> fetch_array($sth)) {
		$groupnames[$gid] = $gname;
	}

// --------------------------------------
// We only send to the groups we selected
   $arraysize = sizeof($HTTP_POST_VARS['whatgroups']);
   for ($i=0; $i<$arraysize; $i++) {
      if ($HTTP_POST_VARS['whatgroups'][$i] == "sendtoall") {
			$printgroup = "all users";
         $groupcheck = "";
         break;
      }
      if (!$i) {
			$printgroup .= "({$groupnames[$HTTP_POST_VARS['whatgroups'][$i]]}) ";
         $groupcheck .=" U_Groups LIKE '%-{$HTTP_POST_VARS['whatgroups'][$i]}-%'";
      }
      else {
			$printgroup .= "({$groupnames[$HTTP_POST_VARS['whatgroups'][$i]]}) ";
         $groupcheck .=" OR U_Groups LIKE '%-{$HTTP_POST_VARS['whatgroups'][$i]}-%'";
      }
   }
	if ($groupcheck) {
		$groupcheck = " AND ($groupcheck)";
	}

// ----------------------------------------------------------------------
// We are going to execute this block of code twice.  First time we will
// send out to those that want plaintext format and the second time
// we will send out those that want HTML format
	$block = array("plaintext","HTML");

	for ($loop = 0; $loop <=1; $loop++ ) {

	// -------------------------------
	// Grab all of the email addresses 
		if ($block[$loop] == "plaintext") {
			$extra = "AND U_EmailFormat <> 'HTML'";
		}
		else {
			$extra = "AND U_EmailFormat = 'HTML'";
		}
   	$query = "
    		SELECT U_Username,U_Email
    		FROM   {$config['tbprefix']}Users
	 		WHERE  U_AdminEmails <> 'Off'
			$extra
    		$groupcheck
   	";
   	$sth = $dbh -> do_query($query);
   	$rows = $dbh -> total_rows($sth);
 
	// -------------------------------------
	// Now we need to mail the message
   	$header = $mailer -> headers($block[$loop]);
   	$count = 0;
   	while ( list($Sendto,$Email) = $dbh -> fetch_array($sth)) {
      	if (strcmp($Email,"") != 0) { $bcc .= "$Email,"; }
      	$count++;
      	if ($count == 50) {
         	$bcc=ereg_replace(",$","$newline",$bcc);
         	mail("$bogus","$Subject","$Body","From: $from{$newline}Bcc:$bcc$header");
         	$count = 0;
         	$bcc = "";
         	$to = "";
      	}
   	}
   	if ($bcc) {
      	$bcc=ereg_replace(",$","$newline",$bcc);
      	mail("$bogus","$Subject","$Body","From: $from{$newline}Bcc:$bcc$header");
   	}
		$bcc = "";
	}
  	$dbh -> finish_sth($sth);


// ------------------------
// Send them a confirmation
   $html -> send_header("Email sent",$Cat,0,$user);
   $html -> admin_table_header("Email sent");

   echo " 
     <TABLE BORDER=0 WIDTH=\"95%\" align=\"center\">
     <TR><TD class=\"cleartable\"><span class=\"onbody\">
     Email has been sent to $printgroup.
     </span>
     </TD></TR></TABLE>
   ";


// -------------
// Send a footer
   $html -> send_admin_footer();


?>
