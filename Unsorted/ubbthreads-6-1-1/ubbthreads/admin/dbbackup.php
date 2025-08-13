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
   $html -> send_header ("Database backup",$Cat,0,$user);
   $html -> admin_table_header("Database backup");
   $html -> open_admin_table();

   echo "<TR><TD CLASS=\"darktable\" colspan=\"2\">";
   echo "The following tables are available for backup or you may choose to backup all tables. You should always close your forums before making a backup.  <i>Note: This utility relies on the mysqldump command so make sure you have access to this and that the path to this command is set properly in the config file. Depending on server restrictions backing up all tables at once may time out or not complete properly. If you are using MySQL 4.x you'll also need to be sure that the database user that your UBB.threads is running as has LOCK TABLES permissions</i><br /><br />";

	echo "Path to mysqldump: {$config['mysqldumppath']}<br />";
	echo "Directory to store backups: {$config['dumpdir']}<br/>";
	echo "</td></tr>";
	$tables = mysql_list_tables($config['dbname']);
	while (list($table) = $dbh -> fetch_array($tables)) {
		echo "<tr><td width=\"10%\" class=\"lighttable\">$table</td>";
		echo "<td class=\"lighttable\"><a href=\"{$config['phpurl']}/admin/dodbbackup.php?Cat=$Cat&table=$table\">Backup</a></td></tr>";
	}
	echo "<tr><td colspan=\"2\" class=\"darktable\"><a href=\"{$config['phpurl']}/admin/dodbbackup.php?Cat=$Cat\">Backup ALL tables</a>";
   $html -> close_table();
   $html -> send_admin_footer();

?>
