<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

$cookie = explode("|", $_COOKIE["ttt_admin"]);

$res = mysql_query("SELECT username, password, PASSWORD('$cookie[2]') AS password2 FROM ttt_settings") or print_error(mysql_error());
$row = mysql_fetch_array($res);
mysql_free_result($res);
extract($row);
if ($cookie[0] < time()-3600) { redirect(); }
elseif ($username != $cookie[1] OR $password != $password2) { redirect(); }

function redirect() {
	setcookie("ttt_admin", "", time());
	header("Location: index.php");
	exit;
}
?>