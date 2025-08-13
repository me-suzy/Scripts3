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

// ------------------------
// Send them a page 
   $html -> send_header ("Database restore",$Cat,0,$user);
   $html -> admin_table_header("Database restore");
   $html -> open_admin_table();

   echo "<TR><TD CLASS=\"darktable\" colspan=\"2\">";
   echo "The following tables are available for restoration.  You may restore an individual table or all tables at one. <i>Note: This utility relies on the mysql command.  Make sure you have access to this and that the path to this command is set properly in the config file. Depending on server restrictions restoring all tables at once may time out or not complete properly.  You will also want to close your forums before restoring any tables.</i><br /><br /><b>Warning:</b>This will drop all data in the table before restoring, so use caution in using this function</b><br /><br />";
   echo "Path to mysql: {$config['mysqlpath']}<br />";
   echo "Directory to store backups: {$config['dumpdir']}<br/>";

	echo "</td></tr>";

	$dir = @opendir($config['dumpdir']);
	if ($dir) {
		while( ($file = readdir($dir)) != false) {
			if ($file == "." || $file == ".." || (!preg_match("/\.sql$/",$file)) ) {
				continue;
			}
			$time = filectime("{$config['dumpdir']}/$file");
			$time = $html -> convert_time($time);
			$filesize = filesize("{$config['dumpdir']}/$file");

			echo "<tr><td width=\"60%\" class=\"lighttable\" nowrap=\"nowrap\">$file <font class=small>($time) - $filesize bytes</font></td>";
			echo "<td class=\"lighttable\"><a href=\"{$config['phpurl']}/admin/dodbrestore.php?Cat=$Cat&table=$file\">Restore</a></td></tr>";
		}
		echo "<tr><td colspan=\"2\" class=\"darktable\"><a href=\"{$config['phpurl']}/admin/dodbrestore.php?Cat=$Cat\">Restore ALL tables</a>";
	}
	else {
		echo "<tr><td class=\"lighttable\">Cannot open database backup directory";
	}
   $html -> close_table();
   $html -> send_admin_footer();

?>
