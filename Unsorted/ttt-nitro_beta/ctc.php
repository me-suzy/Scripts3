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
}
else { 
	$referer = "bookmarks";
	$page_ref = "no refering url";
}
open_conn();
$res = mysql_query("SELECT url,sponsor_id FROM ctc_programs WHERE program_id='$_GET[p]'");
if (mysql_num_rows($res) < 1) {
	$res = mysql_query("SELECT url,sponsor_id FROM ctc_programs WHERE program_id='1'");
	$row = mysql_fetch_array($res);
	extract($row);
	$_GET["p"] = "1";
}
else { 
	$row = mysql_fetch_array($res);
	extract($row);
}
mysql_query("UPDATE ctc_referers SET hits=hits+1 WHERE program_id='$_GET[p]' AND referer='$page_ref'") or print_error(mysql_error());
if (mysql_affected_rows() == 0) {
mysql_query("INSERT INTO ctc_referers (program_id, sponsor_id, referer, hits) VALUES ('$_GET[p]','$sponsor_id','$page_ref', 1)") or print_error(mysql_error());
}
$res = mysql_query("SELECT * FROM ctc_reset") or print_error(mysql_error());
$row = mysql_fetch_array($res);
if (time() > $row[0]) { require_once("ctc-do_reset.inc.php"); resetlog(); }
$today=date('Y-m-d');
mysql_query("UPDATE ctc_stats SET hits=hits+1 WHERE program_id='$_GET[p]' AND date='$today'") or print_error(mysql_error());
if (mysql_affected_rows() == 0) {
mysql_query("INSERT INTO ctc_stats (program_id, sponsor_id, date, hits) VALUES ('$_GET[p]','$sponsor_id','$today', 1)") or print_error(mysql_error());
}
header("Location: $url");
close_conn();
?>