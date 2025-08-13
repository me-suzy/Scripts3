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
ignore_user_abort(true);
require("ttt-mysqlvalues.inc.php");
require("ttt-mysqlfunc.inc.php");

$time = localtime();
$thishour = $time[2];

$referer = htmlentities($_SERVER["HTTP_REFERER"]);
$ip = $_SERVER["REMOTE_ADDR"];
$page_ref = $referer;
if ($referer != "") {
	$referer = parse_url($referer);
	$referer = str_replace("www.","",$referer["host"]);
	$localhost = str_replace("www.","",$_SERVER["HTTP_HOST"]);
}
else { 
	$referer = "bookmarks";
	$page_ref = "no refering url";
}

if ($referer == "$localhost") { }
else {
open_conn();

$res = mysql_query("SELECT * FROM ttt_settings") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
if ($blockheads == 1 AND $_SERVER["REQUEST_METHOD"] == "HEAD") { close_conn(); exit; }

$res = mysql_query("SELECT trade_id FROM ttt_trades WHERE sitedomain='$referer'") or print_error(mysql_error());
if (mysql_num_rows($res) == 0) { $trade_id = 1;}
else { $row = mysql_fetch_array($res); extract($row); }

mysql_query("UPDATE ttt_referers SET hits=hits+1 WHERE trade_id=$trade_id AND referer='$page_ref'") or print_error(mysql_error());
if (mysql_affected_rows() == 0) {
mysql_query("INSERT INTO ttt_referers (trade_id, referer, hits) VALUES ($trade_id, '$page_ref', 1)") or print_error(mysql_error());
}

$res = mysql_query("SELECT * FROM ttt_reset") or print_error(mysql_error());
$row = mysql_fetch_array($res);

if (time() > $row[1]) { require_once("ttt-do_reset.inc.php"); dailylog(); if ($iplogreset==1) { emptyips(); } }
if (time() > $row[0]) { require_once("ttt-do_reset.inc.php"); anticheat(); toplist(); if ($iplogreset==0) { emptyips(); } }

if ($_SERVER["REQUEST_METHOD"] == "HEAD") { $proxy = 2; }
elseif ($_SERVER["HTTP_X_FORWARDED_FOR"] != "" OR $_SERVER["HTTP_VIA"] != "") { $proxy = 1; }
else { $proxy = 0; }

if ($iplog == 1) {
mysql_query("UPDATE ttt_iplog SET hits=hits+1 WHERE ip='$ip' AND proxy='$proxy' AND trade_id=$trade_id") or print_error(mysql_error());
if (mysql_affected_rows() == 0) { mysql_query("INSERT INTO ttt_iplog (trade_id, proxy, ip, hits, clicks) VALUES ($trade_id, '$proxy', '$ip', 1, 0)") or print_error(mysql_error()); }
}

$cookie = explode("|",$_COOKIE["ttt_cookie"]);

if (time()-86400 > $cookie[0]) {
	mysql_query("UPDATE ttt_stats SET in$thishour=in$thishour+1, uniq$thishour=uniq$thishour+1 WHERE trade_id=$trade_id") or print_error(mysql_error());
	$cookie[0] = time();
}
else {
	mysql_query("UPDATE ttt_stats SET in$thishour=in$thishour+1 WHERE trade_id=$trade_id") or print_error(mysql_error());
}
close_conn();
$cookie[1] = $trade_id;
?>
<script language="JavaScript">
<!--
document.cookie='ttt_cookie=<?php echo "$cookie[0]%7C$cookie[1]"; ?>; path=/; expires=Monday, 31-Dec-20 10:10:10 GMT;';
//-->
</script>
<?
}
?>