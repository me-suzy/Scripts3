<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

ignore_user_abort(true);
require("mysqlvalues.inc.php");
require("mysqlfunc.inc.php");
//------------------------------------------ proxy
global $PROXY_VARS;
$PROXY_VARS = array();
$PROXY_VARS[0] = "HTTP_X_FORWARDED_FOR";
$PROXY_VARS[1] = "HTTP_VIA";
$PROXY_VARS[2] = "HTTP_PROXY_CONNECTION";
$PROXY_VARS[3] = "HTTP_CLIENT_IP";
$PROXY_VARS[4] = "HTTP_X_COMING_FROM";
$PROXY_VARS[5] = "HTTP_X_FORWARDED";
$PROXY_VARS[6] = "HTTP_COMING_FROM";
$PROXY_VARS[7] = "HTTP_PROXY";
$PROXY_VARS[8] = "HTTP_XPROXY";
$PROXY_VARS[9] = "HTTP_FORWARDED_FOR";
$PROXY_VARS[10] = "HTTP_FORWARDED";
$PROXY_VARS[11] = "ZHTTP_CACHE_CONTROL";
$PROXY_VARS[12] = "HTTP_CACHE_INFO";
$PROXY_VARS[13] = "HTTP_XONNECTION";
$PROXY_VARS[14] = "HTTP_PRAGMA";
$PROXY_VARS[15] = "X-Cache";

function isProxy(){
	foreach($GLOBALS["PROXY_VARS"] as $k=>$val){if(getenv($val)) { return 1; } }
	return 0;
}


$use_proxy = isProxy();
//------------------------------------------
$time = localtime();
$thishour = $time[2];

$referer = getenv("HTTP_REFERER");
$ip = getenv("REMOTE_ADDR");
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

$res = mysql_query("SELECT trade_id FROM ttt_trades WHERE sitedomain='$referer'") or print_error(mysql_error());
if (mysql_num_rows($res) == 0) { $trade_id = 1;}
else { $row = mysql_fetch_array($res); extract($row); }

mysql_query("UPDATE ttt_referers SET hits=hits+1 WHERE trade_id=$trade_id AND referer='$page_ref'") or print_error(mysql_error());
if (mysql_affected_rows() == 0) {
mysql_query("INSERT INTO ttt_referers (trade_id, referer, hits) VALUES ($trade_id, '$page_ref', 1)") or print_error(mysql_error());
}

$res = mysql_query("SELECT * FROM ttt_reset") or print_error(mysql_error());
$row = mysql_fetch_array($res);
if (time() > $row[1]) { require_once("do_reset.inc.php"); dailylog(); }
if (time() > $row[0]) { require_once("do_reset.inc.php"); anticheat(); toplist(); }

mysql_query("UPDATE ttt_iplog SET hits=hits+1 WHERE ip='$ip' AND trade_id=$trade_id") or print_error(mysql_error());
if (mysql_affected_rows() == 0) { mysql_query("INSERT INTO ttt_iplog (trade_id, ip, hits, use_proxy) VALUES ($trade_id, '$ip', 1, '$use_proxy')") or print_error(mysql_error()); }

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

$ttt_rotator=intval($HTTP_COOKIE_VARS['ttt_rotator'])+1;

if (is_file('./index/'.$ttt_rotator.'.php') && $ttt_rotator > 1) {
	$index_page ="index/$ttt_rotator.php";
	}
else
	{
	$ttt_rotator ="1";
	$index_page ="index/1.php";
	}
?>

<script language="JavaScript">
<!--
document.cookie='ttt_cookie=<?php echo "$cookie[0]%7C$cookie[1]"; ?>; expires=Monday, 31-Dec-20 10:10:10 GMT;';
document.cookie='ttt_rotator=<?php echo "$ttt_rotator"; ?>; expires=Monday, 31-Dec-20 10:10:10 GMT;';
//-->
</script>

<? include("$index_page"); ?>
