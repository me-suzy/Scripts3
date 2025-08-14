<?php

error_reporting(7);

@set_time_limit(0);

define('upgrade', '1');

if (!defined('directory')) {
	define('directory', '../');
}

include(directory . "functions.php");
include(directory . "config.php");
include(directory . "http.php");
include(directory . "mysql.php");

$s24_sql->pconnect();
$s24_sql->select_db();

adminhead("ExitPopup Upgrade from 1.0.0 to 1.0.1");

if (empty($step)) {
	echo "<p><b>You are about to upgrade from ExitPopup 1.0.0 to 1.0.1</b><br><br>";
	echo "<ul>
	<li>Upgrading from 1.0.0 run upgrade1.php</li>
</ul>";
	echo "It's highly recommended to backup the database before you continue using a tool like <a href=\"http://phpwizard.net/projects/phpMyAdmin/\">PHPMyAdmin</a>!<br><br></p>";
	echo "<p><a href=\"$PHP_SELF?step=".($step+1)."\">Next Step</a></p>";
}

if ($step == '1') {

	$sql = "SELECT version FROM $options_tbl";
	$result = mysql_query($sql);
	$row = mysql_fetch_row($result);
	if ($row[0] == '1.0.1') {
		echo "<p>It looks like your database has already been upgraded!<br>";
		echo "If you already have run this upgrade script just delete it from your server<br><br>";
		echo "Do not run it again. If you think that something went wrong during the upgrade contact our tech support!<br></p>";
		exit;
	}
	echo "<p>";

	$sql = "UPDATE $options_tbl SET version='1.0.1'";
	$result = $s24_sql->query($sql);
	echo "<p>$sql</p>";
	flush();

	echo "<br>Tables have been altered<br>";
	echo "<p>Upgrade to ExitPopup 1.0.1 completed successfully!</p>";
}

adminfooter();

?>