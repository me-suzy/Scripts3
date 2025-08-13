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
function emptyips() {
mysql_query("DELETE FROM ttt_iplog") or print_error(mysql_error());
}
function dailylog() {
$today = date("Y-m-d", mktime(0,0,0,date("m"), date("d")-1, date("Y")));
$tomorrow = mktime (0,0,0, date("m"), date("d")+1, date("Y"));
mysql_query("UPDATE ttt_reset SET day_reset=$tomorrow") or print_error(mysql_error());
$res = mysql_query("SELECT sum(in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23) AS in_total, sum(uniq0+uniq1+uniq2+uniq3+uniq4+uniq5+uniq6+uniq7+uniq8+uniq9+uniq10+uniq11+uniq12+uniq13+uniq14+uniq15+uniq16+uniq17+uniq18+uniq19+uniq20+uniq21+uniq22+uniq23) AS uniq_total, sum(tclicks0+tclicks1+tclicks2+tclicks3+tclicks4+tclicks5+tclicks6+tclicks7+tclicks8+tclicks9+tclicks10+tclicks11+tclicks12+tclicks13+tclicks14+tclicks15+tclicks16+tclicks17+tclicks18+tclicks19+tclicks20+tclicks21+tclicks22+tclicks23) AS tclicks_total, sum(clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23) AS clicks_total, sum(out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23) AS out_total FROM ttt_stats") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
mysql_free_result($res);
$clicks_total=$clicks_total+$tclicks_total;
mysql_query("INSERT INTO ttt_daily (dato, hits_in, uniq, clicks, hits_out) VALUES ('$today', $in_total, $uniq_total, $clicks_total, $out_total)") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_referers") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_links WHERE hour0+hour1+hour2+hour3+hour4+hour5+hour6+hour7+hour8+hour9+hour10+hour11+hour12+hour13+hour14+hour15+hour16+hour17+hour18+hour19+hour20+hour21+hour22+hour23=0") or print_error(mysql_error());
@mysql_query("OPTIMIZE TABLE ttt_referers, ttt_iplog, ttt_links");
}
function anticheat()
{
	$time = localtime();
	$thishour = $time[2];
	$next_hour = mktime ($time[2]+1,0,0,date("m"),date("d"),date("Y"));
	mysql_query("UPDATE ttt_reset SET hour_reset=$next_hour") or print_error(mysql_error());
	$res = mysql_query("SELECT minimum_hits, delete_mintrades FROM ttt_settings") or print_error(mysql_error());
	$row = mysql_fetch_array($res);
	extract($row);
	$res = mysql_query("SELECT ttt_trades.trade_id, in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in21+in22+in23 AS in_total, tclicks0+tclicks1+tclicks2+tclicks3+tclicks4+tclicks5+tclicks6+tclicks7+tclicks8+tclicks9+tclicks10+tclicks11+tclicks12+tclicks13+tclicks14+tclicks15+tclicks16+tclicks17+tclicks18+tclicks19+tclicks20+tclicks21+tclicks22+tclicks23 AS tclicks_total, clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_total, min_prod, max_prod, enabled, sitedomain, status FROM ttt_stats, ttt_trades WHERE ttt_stats.trade_id=ttt_trades.trade_id AND ttt_stats.trade_id NOT IN (1,2,3) AND enabled!=0") or print_error(mysql_error());
	while ($row = mysql_fetch_array($res))
	{
		extract($row);
		if ($in_total < $minimum_hits AND $delete_mintrades == 1 AND $status == 1)
		{
			if ($status == 1) {
			mysql_query("DELETE FROM ttt_trades WHERE trade_id=$trade_id") or print_error(mysql_error());
			mysql_query("DELETE FROM ttt_stats WHERE trade_id=$trade_id") or print_error(mysql_error());
			mysql_query("DELETE FROM ttt_forces WHERE trade_id=$trade_id") or print_error(mysql_error());
			mysql_query("DELETE FROM ttt_iplog WHERE trade_id=$trade_id") or print_error(mysql_error());
			mysql_query("DELETE FROM ttt_referers WHERE trade_id=$trade_id") or print_error(mysql_error());
			mysql_query("INSERT INTO ttt_events (dato, timo, action, domain) VALUES (CURDATE(),CURTIME(),'3','$sitedomain')") or print_error(mysql_error());
			}
		}
		else
		{
			if ($in_total == 0) { $prod_total = 1; }
			else { $prod_total = $clicks_total/$in_total; }
			if ((($prod_total > $max_prod OR $prod_total < $min_prod) AND $in_total > 100) OR $in_total < $minimum_hits) { if ($status == 0) { $newstatus=1; } else { $newstatus = 2; } }
			else { $newstatus = 1; }
			if ($enabled != $newstatus) {
			mysql_query("UPDATE ttt_trades SET enabled=$newstatus WHERE trade_id=$trade_id") or print_error(mysql_error()); 
			mysql_query("INSERT INTO ttt_events (dato, timo, action, domain) VALUES (CURDATE(),CURTIME(),'$newstatus','$sitedomain')") or print_error(mysql_error());
			}
		}
	}
	mysql_free_result($res);
	mysql_query("UPDATE ttt_stats SET in$thishour=0, uniq$thishour=0, tclicks$thishour=0, clicks$thishour=0, out$thishour=0") or print_error(mysql_error());
	mysql_query("UPDATE ttt_trades SET turbo=0, turbo_max=0 WHERE turbo='1' AND turbo_max='$thishour'") or print_error(mysql_error());
	mysql_query("UPDATE ttt_forces SET fd$thishour=0") or print_error(mysql_error());
	mysql_query("UPDATE ttt_links SET hour$thishour=0") or print_error(mysql_error());
}

function toplist($fromadmin = 0)
{
	if ($fromadmin == 1) { $prefix = "../"; }
	else { $prefix = ""; }
	$res = mysql_query("SELECT sitedomain, sitename, in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in21+in22+in23 AS in_total, out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out21+out22+out23 AS out_total FROM ttt_stats, ttt_trades WHERE ttt_stats.trade_id=ttt_trades.trade_id AND ttt_trades.trade_id!=1 AND ttt_trades.trade_id!=2 AND ttt_trades.trade_id!=3 AND enabled=1") or print_error(mysql_error());
	if (mysql_num_rows($res) == 0) { print_error("You have not added any trades<br>Before you can use TTT you need to add at least one trade"); }
	while ($row = mysql_fetch_array($res))
	{
		extract($row);
		$sitenames["$sitedomain"] = $sitename;
		$hits_in["$sitedomain"] = $in_total;
		$hits_out["$sitedomain"] = $out_total;
	}
	mysql_free_result($res);
	array_multisort($hits_in, SORT_NUMERIC, SORT_DESC);
	$dir = @opendir($prefix . "ttt_toplist") or print_error("Could not open directory \"ttt_toplist\"");
	while (($file = readdir($dir)) !== false)
  	{
    		if (substr($file, -6) == ".thtml") { $toplists[] = $file; }
	}  
  	closedir($dir);
	for ($i=0; $i<sizeof($toplists); $i++)
	{
		reset($hits_in);
		$template = implode("", file($prefix . "ttt_toplist/$toplists[$i]"));
		for ($a=1; $a<=100; $a++)
		{
			$key = key($hits_in);
			next($hits_in);
			if ($key == "") { $key = "Your Site Here"; $link = "<a href=\"/ttt-webmaster.php\">$key</a>"; }
			else { $link = "<a href=\"/ttt-out.php?trade=$key\">$sitenames[$key]</a>"; }
			$template = str_replace("##sitename$a##", $sitenames["$key"], $template);
			$template = str_replace("##in$a##", $hits_in["$key"], $template);
			$template = str_replace("##out$a##", $hits_out["$key"], $template);
			$template = str_replace("##sitedomain$a##", $key, $template);
			$template = str_replace("##link$a##", "$link", $template);
		}
		$t = str_replace(".thtml", ".html", $toplists[$i]);
		$fp = @fopen($prefix . "ttt_toplist/$t", "w") or print_error("Could not open \"$t\"");
		fputs($fp, "$template");
		fclose($fp);
	}

}
?>