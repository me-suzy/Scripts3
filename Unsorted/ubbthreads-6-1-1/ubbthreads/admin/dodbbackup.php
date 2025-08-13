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

// ---------------------------------------------
// Are we backing up one table or the entire db?
	if ($table) {
		$tables[0] = $table;
	}
	else {
		$i=0;
		$db = mysql_list_tables($config['dbname']);
		while(list($table) = $dbh -> fetch_array($db)) {
			$tables[$i] = $table;
			$i++;
		}
	}
// ------------------------
// Send them a page 
   $html -> send_header ("Backup complete",$Cat,0,$user);
   $html -> admin_table_header("Backup complete");
   $html -> open_admin_table();

   echo "<TR><TD CLASS=\"lighttable\">";

// ------------------------
// Now do the actual backup
	$dumpcommand = $config['mysqldumppath'] . "/mysqldump --opt -h {$config['dbserver']} -u{$config['dbuser']} -p{$config['dbpass']} {$config['dbname']} ";
	foreach($tables as $tablename) {
		$tablename = escapeshellcmd($tablename);
		$dodumpcommand = $dumpcommand . "$tablename > {$config['dumpdir']}/$tablename.sql";
		system($dodumpcommand);
		$size = filesize("{$config['dumpdir']}/$tablename.sql");
		if (!$size) {
			echo "$tablename.sql is 0 bytes or did not get created.";
		}
		else {
			echo "$tablename has been backed up.<br>";
		}
	}

	echo "<br /><br />You will need to verify that this command actually backed up the selected table(s)."; 
   $html -> close_table();
   $html -> send_admin_footer();

?>
