<?php
function open_conn() {
	global $mysqlhost, $dbusername, $dbpass, $db, $mysqllink;
	$mysqllink = mysql_connect("$mysqlhost","$dbusername","$dbpass") or exit("Chould not connect to MySQL");
	mysql_select_db("$db") or exit("Could not choose the database: $db");
}

function close_conn() {
	global $mysqllink;
	mysql_close($mysqllink) or exit("Could not close connection to MySQL.");
}
?>