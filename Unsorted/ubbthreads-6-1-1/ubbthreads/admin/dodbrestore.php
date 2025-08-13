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

// -----------
// No timeouts
   @set_time_limit(0);

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

// ------------------------
// Send them a page 
   $html -> send_header ("Database restored",$Cat,0,$user);
   $html -> admin_table_header("Database restored");
   $html -> open_admin_table();

   echo "<TR><TD CLASS=\"darktable\">";

	$dir = opendir($config['dumpdir']);

// ---------------------------------------
// Are we restoring a single table or all?
	if ($table) {
		$tables[0] = $table;
	}
	else {
		$i = 0;
		while( ($file = readdir($dir)) != false) {
			if ($file == "." || $file == ".." || (!preg_match("/\.sql$/",$file)) ) {
				continue;
			}
			$tables[$i] = $file;
			$i++;
		}
	}

// -------------------------------
// Setup the basic restore command
   $restorecommand = $config['mysqlpath'] . "/mysql -h {$config['dbserver']} -u{$config['dbuser']} -p{$config['dbpass']} {$config['dbname']} ";
	
   foreach($tables as $tablename) {
      $dorestorecommand = $restorecommand . "< {$config['dumpdir']}/$tablename";
      echo "$tablename has been restored.<br>";
      exec($dorestorecommand);
	}

   $html -> close_table();
   $html -> send_admin_footer();

?>
