<?php
require("../ttt-mysqlvalues.inc.php");
require("../ttt-mysqlfunc.inc.php");
if ($check) {
echo "1";
}
else {
$time = localtime();
$thishour = $time[2];
open_conn();
$res = mysql_query("SELECT * from ttt_settings WHERE password='$_GET[password]'");
if (mysql_num_rows($res) < 1) {
echo "0|0|0|0|0|0%|0%|0%|0|0|0|0|0%|0%|0%|";
}
else {
$res = mysql_query("SELECT COUNT(*) FROM ttt_trades");
$total_trades = mysql_fetch_array($res);
$total_trades = $total_trades[0]-3;
$res = mysql_query("SELECT in$thishour AS in_hour, uniq$thishour AS uniq_hour, tclicks$thishour as tclicks_hour, clicks$thishour AS clicks_hour, out$thishour AS out_hour, in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in21+in22+in23 AS in_total, uniq0+uniq1+uniq2+uniq3+uniq4+uniq5+uniq6+uniq7+uniq8+uniq9+uniq10+uniq11+uniq12+uniq13+uniq14+uniq15+uniq16+uniq17+uniq18+uniq19+uniq20+uniq21+uniq22+uniq23 AS uniq_total, tclicks0+tclicks1+tclicks2+tclicks3+tclicks4+tclicks5+tclicks6+tclicks7+tclicks8+tclicks9+tclicks10+tclicks11+tclicks12+tclicks13+tclicks14+tclicks15+tclicks16+tclicks17+tclicks18+tclicks19+tclicks20+tclicks21+tclicks22+tclicks23 AS tclicks_total, clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_total, out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out_total FROM ttt_stats WHERE trade_id!='3'") or print_error(mysql_error());
while ($row = mysql_fetch_array($res)) {
extract($row);
$sum["tclicks_hour"] += $tclicks_hour;
$sum["tclicks_total"] += $tclicks_total;
$tclicks_hour=$tclicks_hour+$clicks_hour;
$tclicks_total=$tclicks_total+$clicks_total;
if ($in_hour == 0) { $prod_hour = "no hits"; $uniq_pct_hour = "no hits"; }
else { $prod_hour = "" . round($clicks_hour/$in_hour*100, 1) . "%"; $uniq_pct_hour = "" . round($uniq_hour/$in_hour*100, 1) . "%"; }
if ($in_total == 0) { $prod_total = "no hits"; $uniq_pct_total = "no hits"; }
else { $prod_total = "" . round($clicks_total/$in_total*100, 1) . "%"; $uniq_pct_total = "" . round($uniq_total/$in_total*100, 1) . "%"; }
if ($in_hour == 0) { $tprod_hour = "no hits"; $uniq_pct_hour = "no hits"; }
else { $tprod_hour = "" . round($tclicks_hour/$in_hour*100, 1) . "%"; $uniq_pct_hour = "" . round($uniq_hour/$in_hour*100, 1) . "%"; }
if ($in_total == 0) { $tprod_total = "no hits"; $uniq_pct_total = "no hits"; }
else { $tprod_total = "" . round($tclicks_total/$in_total*100, 1) . "%"; $uniq_pct_total = "" . round($uniq_total/$in_total*100, 1) . "%"; }
$sum["in_hour"] += $in_hour;
$sum["uniq_hour"] += $uniq_hour;
$sum["clicks_hour"] += $clicks_hour;
$sum["out_hour"] += $out_hour;
$sum["in_total"] += $in_total;
$sum["uniq_total"] += $uniq_total;
$sum["clicks_total"] += $clicks_total;
$sum["out_total"] += $out_total;
}
close_conn();
$sum["tclicks_hour"] = $sum["tclicks_hour"]+$sum["clicks_hour"];
$sum["tclicks_total"] = $sum["tclicks_total"]+$sum["clicks_total"];
if ($sum["in_hour"] == 0) { $sum["prod_hour"] = "no hits"; $sum["return_hour"] = "no hits"; $sum["uniq_pct_hour"] = "no hits"; }
else { $sum["prod_hour"] = "" . round($sum["clicks_hour"]/$sum["in_hour"]*100, 1) . "%"; $sum["return_hour"] = "" . round($sum["out_hour"]/$sum["in_hour"]*100, 1) . "%"; $sum["uniq_pct_hour"] = "" . round($sum["uniq_hour"]/$sum["in_hour"]*100, 1) . "%"; }
if ($sum["in_total"] == 0) { $sum["prod_total"] = "no hits"; $sum["return_total"] = "no hits"; $sum["uniq_pct_total"] = "no hits"; }
else { $sum["prod_total"] = "" . round($sum["clicks_total"]/$sum["in_total"]*100, 1) . "%"; $sum["return_total"] = "" . round($sum["out_total"]/$sum["in_total"]*100, 1) . "%"; $sum["uniq_pct_total"] = "" . round($sum["uniq_total"]/$sum["in_total"]*100, 1) . "%"; }
if ($sum["in_hour"] == 0) { $sum["tprod_hour"] = "no hits"; $sum["return_hour"] = "no hits"; $sum["uniq_pct_hour"] = "no hits"; }
else { $sum["tprod_hour"] = "" . round($sum["tclicks_hour"]/$sum["in_hour"]*100, 1) . "%"; $sum["return_hour"] = "" . round($sum["out_hour"]/$sum["in_hour"]*100, 1) . "%"; $sum["uniq_pct_hour"] = "" . round($sum["uniq_hour"]/$sum["in_hour"]*100, 1) . "%"; }
if ($sum["in_total"] == 0) { $sum["tprod_total"] = "no hits"; $sum["return_total"] = "no hits"; $sum["uniq_pct_total"] = "no hits"; }
else { $sum["tprod_total"] = "" . round($sum["tclicks_total"]/$sum["in_total"]*100, 1) . "%"; $sum["return_total"] = "" . round($sum["out_total"]/$sum["in_total"]*100, 1) . "%"; $sum["uniq_pct_total"] = "" . round($sum["uniq_total"]/$sum["in_total"]*100, 1) . "%"; }
print "$total_trades|$sum[in_hour]|$sum[out_hour]|$sum[clicks_hour]|$sum[tclicks_hour]|$sum[prod_hour]|$sum[tprod_hour]|$sum[return_hour]|$sum[in_total]|$sum[out_total]|$sum[clicks_total]|$sum[tclicks_total]|$sum[prod_total]|$sum[tprod_total]|$sum[return_total]|";
}
}
?>