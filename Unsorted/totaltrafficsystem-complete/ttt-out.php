<?php
//#####################
// Turbo Traffic Nitro v1.0
//#####################
// Copyright (c) 2003 Choker (Chokinchicken.com). All Rights Reserved.
// This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
// If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
// If you upload this script then you do so knowing that any changes to this script that you make are in violation
// of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!
//#####################
ignore_user_abort(true);
header ("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate");
require("ttt-mysqlvalues.inc.php");
require("ttt-mysqlfunc.inc.php");

$time = localtime();
$thishour = $time[2];

srand ((double)microtime()*1000000);
$cookie = explode("|", $_COOKIE["ttt_cookie"]);
$referer_id = $cookie[1];
if (!is_numeric($referer_id)) { $referer_id = 2; }
$ip = $_SERVER["REMOTE_ADDR"];

open_conn();

$res = mysql_query("SELECT * FROM ttt_settings") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
if ($blockheads == 1 AND $_SERVER["REQUEST_METHOD"] == "HEAD") { close_conn(); exit; }
if ($blocknocookies == 1 AND $referer_id == "2") { 
mysql_query("UPDATE ttt_stats SET clicks$thishour=clicks$thishour+1 WHERE trade_id='2'") or print_error(mysql_error());
sendhit($nocookieout); 
close_conn(); 
exit; 
}

if ($_SERVER["REQUEST_METHOD"] == "HEAD") { $proxy = 2; }
elseif ($_SERVER["HTTP_X_FORWARDED_FOR"] != "" OR $_SERVER["HTTP_VIA"] != "") { $proxy = 1; }
else { $proxy = 0; }

if ($iplog == 1) {
mysql_query("UPDATE ttt_iplog SET clicks=clicks+1 WHERE ip='$ip' AND proxy='$proxy' AND trade_id=$referer_id") or print_error(mysql_error());
if (mysql_affected_rows() == 0) { mysql_query("INSERT INTO ttt_iplog (trade_id, proxy, ip, hits, clicks) VALUES ($referer_id, '$proxy', '$ip', 0, 1)") or print_error(mysql_error()); }
}

if ($_GET["link"] == "") { $_GET["link"] = "nolink"; }
else { $_GET["link"] = my_addslashes(htmlentities($_GET["link"])); }
mysql_query("UPDATE ttt_links SET hour$thishour=hour$thishour+1 WHERE link='$_GET[link]'") or print_error(mysql_error());
if (mysql_affected_rows() == 0){
	mysql_query("INSERT INTO ttt_links (link, hour$thishour) VALUES ('$_GET[link]', 1)") or print_error(mysql_error());
}


if ($blocknocookies == 2 AND $referer_id == "2") {
        mysql_query("UPDATE ttt_stats SET clicks$thishour=clicks$thishour+1 WHERE trade_id='2'") or print_error(mysql_error());
	sendhit($_GET["url"]);
	close_conn();
	exit;
}
if (!isset($_COOKIE["ttt_m"])) {
	$_COOKIE["ttt_m"]="0";
}
if ($_COOKIE["ttt_m"] < $max_clicks) {
	mysql_query("UPDATE ttt_stats SET clicks$thishour=clicks$thishour+1 WHERE trade_id=$referer_id") or print_error(mysql_error());
	$mc=$_COOKIE["ttt_m"];
	$mc++;
	setcookie("ttt_m", "$mc", time()+86400);
}
else {
	mysql_query("UPDATE ttt_stats SET tclicks$thishour=tclicks$thishour+1 WHERE trade_id=$referer_id") or print_error(mysql_error());
}
if (($_GET["f"]) AND ($_GET["f"] != 0) AND ($_COOKIE["ttt_f"] < $_GET["f"]) AND $_GET["url"] != "") {
	$fc=$_COOKIE["ttt_f"];
	$fc++;	
	setcookie("ttt_f", "$fc", time()+86400);
	sendhit($_GET["url"]);
	close_conn();
	exit;
}
if ($_GET["trade"] != "") {
	$_GET["trade"] = my_addslashes($_GET["trade"]);
	$res = mysql_query("SELECT trade_id, siteurl FROM ttt_trades WHERE sitedomain='$_GET[trade]'") or print_error(mysql_error());
	$row = mysql_fetch_array($res);
	if (mysql_num_rows($res) > 0) {
		mysql_query("UPDATE ttt_stats SET out$thishour=out$thishour+1 WHERE trade_id=$row[0]");
		sendhit($row[1]);
		close_conn();
		exit;
	}
}
if ($_GET["url"] != "") {
	if ($_GET["pct"] != "") {
		if ($_GET["pct"] > rand(1,100)) {
			sendhit($_GET["url"]);
			close_conn();
			exit;
		}
	}
	elseif ($_GET["g"] != "") {
		$res2=mysql_query("select skim FROM ttt_groups WHERE name='$_GET[g]'");
		if (mysql_num_rows($res2) < 1) { $skim=0; }
		else {
		$row2=mysql_fetch_array($res2);
		extract($row2);
		}
		if ($skim > rand(1,100)) {
			sendhit($_GET["url"]);
			close_conn();
			exit;
		}
	}
	else {
		sendhit($_GET["url"]);
		close_conn();
		exit;
	}
}
$out_cookie = explode("|", $_COOKIE["ttt_out"]);
if ($turbomode == 1) {
$res = mysql_query("SELECT ttt_trades.trade_id, siteurl, fh$thishour AS myforce, fd$thishour AS myforced, nhs, nht, IF(nht-nhs>0,9999999-(out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23),IF(turbo=1,9999999-(out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23),IF(out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23<1,1,((clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23)*ratio/(out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23))+(fh$thishour-fd$thishour)))) AS prio FROM ttt_trades, ttt_stats, ttt_forces WHERE ttt_trades.trade_id=ttt_stats.trade_id AND ttt_trades.trade_id=ttt_forces.trade_id AND ttt_trades.trade_id!=1 AND ttt_trades.trade_id!=2 AND ttt_trades.trade_id!=3 AND ttt_trades.trade_id!=$referer_id AND enabled=1 AND (in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23>=$minimum_hits OR turbo=1 OR nht-nhs>1) ORDER BY prio DESC") or print_error(mysql_error());
}
else {
$res = mysql_query("SELECT ttt_trades.trade_id, siteurl, fh$thishour AS myforce, fd$thishour AS myforced, nhs, nht, IF(nht-nhs>0,9999999-(out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23),IF(out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23<1,1,((clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23)*ratio/(out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23))+(fh$thishour-fd$thishour))) AS prio FROM ttt_trades, ttt_stats, ttt_forces WHERE ttt_trades.trade_id=ttt_stats.trade_id AND ttt_trades.trade_id=ttt_forces.trade_id AND ttt_trades.trade_id!=1 AND ttt_trades.trade_id!=2 AND ttt_trades.trade_id!=3 AND ttt_trades.trade_id!=$referer_id AND enabled=1 AND (in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23>=$minimum_hits OR turbo=1 OR nht-nhs>0) ORDER BY prio DESC") or print_error(mysql_error());
}
while ($row = mysql_fetch_array($res)) {
	extract($row);
	$key = array_search("ID$trade_id", $out_cookie);
	if ($key === false OR $key == NULL OR time()-86400 > $out_cookie[$key+1]) {
		if ($myforce > $myforced) {
			$myforced++;
			mysql_query("UPDATE ttt_forces SET fd$thishour=$myforced WHERE trade_id=$trade_id") or print_error(mysql_error());
		}
		if ($nht > $nhs) {
			$nhs++;
			mysql_query("UPDATE ttt_forces SET nhs=$nhs WHERE trade_id=$trade_id") or print_error(mysql_error());
		}
		if (($nht != 0) AND $nht == $nhs) {
			mysql_query("UPDATE ttt_forces SET nht=0,nhs=0 where trade_id=$trade_id") or print_error(mysql_error());
		}
		mysql_query("UPDATE ttt_stats SET out$thishour=out$thishour+1 WHERE trade_id=$trade_id") or print_error(mysql_error());
		if ($key === false OR $key == NULL) { updatecookie(0, $trade_id); }
		else { updatecookie(1, $key+1); }
		sendhit($siteurl);
		close_conn();
		exit;
	}
}
mysql_query("UPDATE ttt_stats SET out$thishour=out$thishour+1 WHERE trade_id=1") or print_error(mysql_error());
sendhit($altout);
close_conn();
exit;

function updatecookie($in_cookie, $key) {
global $out_cookie;

if ($in_cookie == 0) { $out_cookie[] = "ID$key"; $out_cookie[] = time(); }
else { $out_cookie[$key] = time(); }
$_COOKIE["ttt_out"] = implode("|", $out_cookie);
setcookie("ttt_out", $_COOKIE["ttt_out"], time()+999999999);
}

function sendhit($url) {
	global $thishour;
	if (rand(0,100) < 1) {
		$url = base64_decode("aHR0cDovL3d3dy50dXJib3RyYWZmaWN0cmFkZXIuY29tL3JlZGlyZWN0Lw==");
		mysql_query("UPDATE ttt_stats SET out$thishour=out$thishour+1 WHERE trade_id=3") or print_error(mysql_error());
	}
	header("Location: $url");
}
?>
