<?php
//#####################
// Turbo Traffic Trader Nitro v1.0
//#####################
// Copyright (c) 2003 Choker (Chokinchicken.com). All Rights Reserved.
// This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
// If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
// If you upload this script then you do so knowing that any changes to this script that you make are in violation
// of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!
//#####################
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