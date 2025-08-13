<?php
function open_conn() {
	global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_link;
	$mysql_link = @mysql_connect("$mysql_host","$mysql_user","$mysql_pass") or print_error("Chould not connect to MySQL<br>Please check your settings in ttt-mysqlfunc.inc.php");
	mysql_select_db("$mysql_db") or print_error("Could not choose the database: $mysql_db");
}

function close_conn() {
	global $mysql_link;
	mysql_close($mysql_link) or print_error("Could not close connection to MySQL.");
}
function print_error($msg) {
echo "<font face='verdana' size='3'><b>Error:</b><br><font size='2'>$msg</font></font>";
exit;
}
function my_stripslashes($str) {
	if (!get_magic_quotes_gpc()) { return stripslashes($str); } else { return $str; }
}
function my_addslashes($str) {
	if (!get_magic_quotes_gpc()) { return addslashes($str); } else { return $str; }
}
?>