<?php
//#####################
// Turbo Traffic Trader v2.0
//#####################
// Copyright (c) 2003 Choker (Chokinchicken.com). All Rights Reserved.
// Any unauthorized reproductions or alterations (modifications) of this script is strictly prohibited. 
// If you reproduce or alter this script in any way, I will shut down EVERY SINGLE one of your sites.
// You will be out of this business overnight. YOU HAVE BEEN WARNED!
//#####################
$cookie = explode("|", my_addslashes($_COOKIE["ttt_admin"]));

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