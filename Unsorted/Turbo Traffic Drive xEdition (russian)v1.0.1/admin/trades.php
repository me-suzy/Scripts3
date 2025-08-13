<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("../mysqlvalues.inc.php");
require("../mysqlfunc.inc.php");
open_conn();
require("security.inc.php");

$time = localtime();
$thishour = $time[2];
$res = mysql_query("SELECT turbomode, max_trades FROM ttt_settings");
$row = mysql_fetch_array($res);
extract($row);
$res = mysql_query("SELECT COUNT(*) FROM ttt_trades");
$total_trades = mysql_fetch_array($res);
$total_trades = $total_trades[0]-3;
if ($turbomode == 1) { $msg = "Script Currently In Turbo Mode"; }
else { $msg = ""; }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>TurboTrafficTrader</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<?php if (file_exists("../install.php")) { ?>
alert("\n\nYOU HAVE NOT DELETED \"install.php\". PLEASE DELETE THE FILE!\n\n\n");
<?php if (file_exists("../upgrade_1.0.1.php")) { ?>
alert("\n\nYOU HAVE NOT DELETED \"upgrade_1.0.1.php\". PLEASE DELETE THE FILE!\n\n\n");
<?php
}
}

if ($orderby == "") {
$orderby = "uniq_total";
}
?>
function openwin(what) {
var temp = "";
for (i=0; i<document.form1.trade_id.length; i++) { if (document.form1.trade_id[i].checked) { var temp = document.form1.trade_id[i].value; }	}

if (what == "addtrade") { url = "addtrade.php"; features = "width=400,height=310,status=0,resizable=1"; }
if (what == "edit" && temp != "") { url = "edit.php?id="+temp; features = "width=660,height=580,scrollbars=1,resizable=1"; }
if (what == "iplog") { url = "iplog.php?id="+temp; features = "width=340,height=400,status=1,resizable=1,scrollbars=1"; }
if (what == "reflog") { url = "reflog.php?id="+temp; features = "width=500,height=400,status=1,resizable=1,scrollbars=1"; }
if (what == "settings") { url = "settings.php"; features = "width=440,height=500,status=1,resizable=1,scrollbars=1"; }
if (what == "delete") { url = "delete.php?id="+temp; features = "width=400,height=180,status=0,resizable=1,scrollbars=0"; }
if (what == "reset") { url = "reset.php?id="+temp; features = "width=400,height=120,status=0,resizable=1,scrollbars=0"; }
if (what == "turbo" || what == "turbo_off" || what == "turbo_on") {
	if (what == "turbo_on") { url = "turbo.php?mode=on"; }
	if (what == "turbo_off") { url = "turbo.php?mode=off"; }
	features = "width=450,height=120,status=0,resizable=1,scrollbars=0";
}
if (what == "turbotrade") { url = "turbo.php?id="+temp; features = "width=500,height=120,status=0,resizable=1,scrollbars=0"; }
if (what == "toplist") { url = "toplist.php"; features = "width=490,height=550,status=0,resizable=1,scrollbars=1"; }
if (what == "changepass") { url = "settings.php?change=1"; features = "width=400,height=310,status=0,resizable=1"; }
if (what == "links") { url = "links.php"; features = "width=750,height=350,status=0,resizable=1,scrollbars=1"; }
if (what == "daily") { url = "daily.php"; features = "width=490,height=400,status=0,resizable=1,scrollbars=1"; }
if (what == "blacklist") { url = "blacklist.php"; features = "width=600,height=400,status=0,resizable=1,scrollbars=1"; }
window.open(url, "_blank", features);
}
</script>
<base target="_blank">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<form name="form1" action="">
<table width="760" border="0" cellspacing="0" cellpadding="0" align="center">
<tr class="traderow">
<td valign="top">
<table width="600" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4">
<tr class="toprows"> 
<td colspan="9" background="background.gif" height="20"
<b>Server Time:</b> <?php echo date("M, j G:i:s"); ?>&nbsp;|&nbsp;
<b>Logged in:</b> <?php echo date("M, j G:i:s", $cookie[0]); ?>
</td>
        </tr>
<tr class="menu"> 
        


<td background="background.gif"><input type="button" class="buttons" value="Refresh" onClick="window.location=('trades.php?orderby=<? echo $orderby ?>')""></td>
<td background="background.gif"><input type="button" class="buttons" value="Add trade" onclick="openwin('addtrade')"></td>
<td background="background.gif"><input type="button" class="buttons" value="Links" onclick="openwin('links')"></td>
<td background="background.gif"><input type="button" class="buttons" value="Daily" onclick="openwin('daily')"></td>
<td background="background.gif"><input type="button" class="buttons" value="Blacklist" onclick="openwin('blacklist')"></td>
<td background="background.gif"><input type="button" class="buttons" value="Toplist" onclick="openwin('toplist')"></td>
<td background="background.gif"><input type="button" class="buttons" value="Settings" onclick="openwin('settings')"></td>
<td background="background.gif"><input type="button" class="buttons" value="Pass" onclick="openwin('changepass')"></td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
    <td valign="top" width="200">
<?php if ($turbomode == 1) { ?>
    <a href="javascript:openwin('turbo_off')" target="_self"><img src="turbo_on.gif" alt="Disable Turbo" width="110" height="53" border="0"></a>
<?php } else { ?>
    <a href="javascript:openwin('turbo_on')" target="_self"><img src="turbo_off.gif" alt="Enable Turbo" width="110" height="53" border="0"></a>
<?php } ?>
    </td>
  </tr>
</table>
<center><br>
<font size="4"><strong><?php echo $msg; ?></strong></font><br>
</center>

<table width="760" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center">
  <tr align="center">
    <td background="background.gif"><input name="edit" type="button" class="buttons" id="edit" value="   Edit   " onclick="openwin('edit')"></td>
    <td background="background.gif"><input name="iplog" type="button" class="buttons" id="iplog" value="   IP Log   " onclick="openwin('iplog')"></td>
    <td background="background.gif"><input name="reflog" type="button" class="buttons" id="reflog" value="   Ref Log   " onclick="openwin('reflog')"></td>
    <td background="background.gif"><input name="reset" type="button" class="buttons" id="reset" value="   Reset   " onclick="openwin('reset')"></td>
    <td background="background.gif"><input name="delete" type="button" class="buttons" id="delete" value="   Delete   " onclick="openwin('delete')"></td>
    <td background="background.gif"><input name="turbo" type="button" class="buttons" value="  Add/Delete Turbo  " onclick="openwin('turbotrade')"></td>
  </tr>
</table>

<br>


<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
<tr>
<td>

<table width="100%" border="1" cellpadding="1" cellspacing="2"  bordercolor="#000000" bgcolor="#E4E4E4">
<tr class="toprows"> 
<td background="background.gif" colspan="2">&nbsp;</td>
<td background="background.gif" colspan="6">This Hour</td>
<td background="background.gif" colspan="6">Last 23 Hours + This Hour</td>
<td background="background.gif">&nbsp;</td>
<td background="background.gif">&nbsp;&nbsp;Force&nbsp;&nbsp;</td>
<td background="background.gif">&nbsp;</td>
</tr>
<tr class="toprows"> 
<td background="background.gif" colspan="2">Domain</td>
<td background="background.gif">Q</td>
<td background="background.gif">&nbsp;<a target=_top href="?orderby=in_hour" title="Raw In">rIn</a>&nbsp;</td>
<td background="background.gif">&nbsp;<a target=_top href="?orderby=uniq_hour" title="Unique In">uIn</a>&nbsp;</td>
<td background="background.gif"><a target=_top href="?orderby=out_hour" title="Out to Trade">Out</a></td>
<td background="background.gif"><a target=_top href="?orderby=clicks_hour" title="Clicks by Trader">Clicks</a></td>
<td background="background.gif">Prod</td>
<td background="background.gif"><a title="Actual Ratio">aRatio</a></td>
<td background="background.gif">&nbsp;<a target=_top href="?orderby=in_total" title="Raw In">rIn</a>&nbsp;</td>
<td background="background.gif">&nbsp;<a target=_top href="?orderby=uniq_total" title="Unique In">uIn</a>&nbsp;</td>
<td background="background.gif"><a target=_top href="?orderby=out_total" title="Out to Trade">Out</a></td>
<td background="background.gif"><a target=_top href="?orderby=clicks_total" title="Clicks by Trader">Clicks</a></td>
<td background="background.gif">Prod</td>
<td background="background.gif"><a title="Actual Ratio">aRatio</a></td>
<td background="background.gif">Ratio</td>
<td background="background.gif"><a title="F = Force   Fd = Forced"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="toprows2"><tr align="center"><td width="50%"><a target=_top href="?orderby=myforce">F</a></td><td>|</td><td width="50%"><a target=_top href="?orderby=myforced">Fd</a></td></tr></table></a></td>
        </tr>
<?
$res = mysql_query("SELECT ttt_trades.trade_id, sitedomain, siteurl, sitename, wm_email, wm_icq, ratio,  enabled, turbo, in$thishour AS in_hour, uniq$thishour AS uniq_hour, clicks$thishour AS clicks_hour, out$thishour AS out_hour, in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in21+in22+in23 AS in_total, uniq0+uniq1+uniq2+uniq3+uniq4+uniq5+uniq6+uniq7+uniq8+uniq9+uniq10+uniq11+uniq12+uniq13+uniq14+uniq15+uniq16+uniq17+uniq18+uniq19+uniq20+uniq21+uniq22+uniq23 AS uniq_total, clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_total, out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out_total, fh$thishour AS myforce, fd$thishour AS myforced FROM ttt_trades, ttt_stats, ttt_forces WHERE ttt_trades.trade_id=ttt_stats.trade_id AND ttt_trades.trade_id=ttt_forces.trade_id AND ttt_trades.trade_id!=1 AND ttt_trades.trade_id!=2 AND ttt_trades.trade_id!=3 ORDER BY $orderby DESC") or print_error(mysql_error());
while ($row = mysql_fetch_array($res)) {
extract($row);

if ($in_hour == 0) {
	$prod_hour = "no hits";
	$return_hour = "no hits";
	$uniq_pct_hour = "no hits";
}
else {
	$prod_hour = "" . round($clicks_hour/$in_hour*100, 1) . "%";
	$return_hour = "" . round($out_hour/$in_hour*100, 1) . "%";
	$uniq_pct_hour = "" . round($uniq_hour/$in_hour*100, 1) . "%";
}
if ($in_total == 0) {
	$prod_total = "no hits";
	$return_total = "no hits";
	$uniq_pct_total = "no hits";
}
else {
	$prod_total = "" . round($clicks_total/$in_total*100, 1) . "%";
	$return_total = "" . round($out_total/$in_total*100, 1) . "%";
	$uniq_pct_total = "" . round($uniq_total/$in_total*100, 1) . "%";
}
if ($out_total == 0) { $qvalue = "--"; }
else { $qvalue = number_format($clicks_total/$out_total, 2); }

$ratio = "" . $ratio*100 . "%";

$sum["in_hour"] += $in_hour;
$sum["uniq_hour"] += $uniq_hour;
$sum["clicks_hour"] += $clicks_hour;
$sum["out_hour"] += $out_hour;
$sum["in_total"] += $in_total;
$sum["uniq_total"] += $uniq_total;
$sum["clicks_total"] += $clicks_total;
$sum["out_total"] += $out_total;
$sum["myforce"] += $myforce;
$sum["myforced"] += $myforced;
if ($enabled == 0) { $bg = "bgcolor=\"#0099CC\""; }
elseif ($enabled == 2) { $bg = "bgcolor=\"#FFFF99\""; }
elseif ($turbo == 1) { $bg = "bgcolor=\"#00CC66\""; }
else { $bg = "bgcolor=\"EFEFEF\""; }
print <<<END
        <tr $bg class="traderow"> 
<td bgcolor="#EFEFEF"><input type="radio" name="trade_id" value="$trade_id"></td>
<td align="left"><a href="$siteurl" title="Sitename: $sitename    Email: $wm_email    ICQ: $wm_icq">$sitedomain</a></td>
<td bgcolor="#EFEFEF">$qvalue</td>
<td bgcolor="#80FFFF">$in_hour</td>
<td bgcolor="#80FFFF">$uniq_hour</td>
<td bgcolor="#FFFF00">$out_hour</td>
<td bgcolor="#80FF80">$clicks_hour</td>
<td bgcolor="#FF80FF">$prod_hour</td>
<td bgcolor="#FF8000">$return_hour</td>
<td bgcolor="#80FFFF">$in_total</td>
<td bgcolor="#80FFFF">$uniq_total</td>
<td bgcolor="#FFFF00">$out_total</td>
<td bgcolor="#80FF80">$clicks_total</td>
<td bgcolor="#FF80FF">$prod_total</td>
<td bgcolor="#FF8000">$return_total</td>
<td bgcolor="#CCCCCC">$ratio</td>
<td bgcolor="#EFEFEF"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="traderow"  bgcolor="#EFEFEF"><tr align="center"><td width="50%">$myforce</td><td>|</td><td width="50%">$myforced</td></tr></table></td>
        </tr>
        
END;
}
?>
        <tr class="toprows"> 
<td background="background.gif" colspan="3">Bookmarks & No Cookie</td>
<td background="background.gif"><a title="Raw In">rIn</a></td>
<td background="background.gif"><a title="Unique In">uIn</a></td>
<td background="background.gif"><a title="Out to Trade">Out</a></td>
<td background="background.gif"><a title="Clicks by Trader">Clicks</a></td>
<td background="background.gif">Prod</td>
<td background="background.gif"><a title="Actual Ratio">aRatio</a></td>
<td background="background.gif"><a title="Raw In">rIn</a></td>
<td background="background.gif"><a title="Unique In">uIn</a></td>
<td background="background.gif"><a title="Out to Trade">Out</a></td>
<td background="background.gif"><a title="Clicks by Trader">Clicks</a></td>
<td background="background.gif">Prod</td>
<td background="background.gif"><a title="Actual Ratio">aRatio</a></td>
<td background="background.gif">Ratio</td>
<td background="background.gif"><a title="F = Force   Fd = Forced"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="toprows2"><tr align="center"><td width="50%">F</td><td>|</td><td width="50%">Fd</td></tr></table></a></td>

        </tr>
<?php
$res = mysql_query("SELECT in$thishour AS in_hour, uniq$thishour AS uniq_hour, clicks$thishour AS clicks_hour, out$thishour AS out_hour, in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in21+in22+in23 AS in_total, uniq0+uniq1+uniq2+uniq3+uniq4+uniq5+uniq6+uniq7+uniq8+uniq9+uniq10+uniq11+uniq12+uniq13+uniq14+uniq15+uniq16+uniq17+uniq18+uniq19+uniq20+uniq21+uniq22+uniq23 AS uniq_total, clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_total, out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out_total FROM ttt_stats WHERE ttt_stats.trade_id=1") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
if ($in_hour == 0) { $prod_hour = "no hits"; $uniq_pct_hour = "no hits"; }
else { $prod_hour = "" . round($clicks_hour/$in_hour*100, 1) . "%"; $uniq_pct_hour = "" . round($uniq_hour/$in_hour*100, 1) . "%"; }
if ($in_total == 0) { $prod_total = "no hits"; $uniq_pct_total = "no hits"; }
else { $prod_total = "" . round($clicks_total/$in_total*100, 1) . "%"; $uniq_pct_total = "" . round($uniq_total/$in_total*100, 1) . "%"; }


$sum["in_hour"] += $in_hour;
$sum["uniq_hour"] += $uniq_hour;
$sum["clicks_hour"] += $clicks_hour;
$sum["out_hour"] += $out_hour;
$sum["in_total"] += $in_total;
$sum["uniq_total"] += $uniq_total;
$sum["clicks_total"] += $clicks_total;
$sum["out_total"] += $out_total;

print <<<END
        <tr class="traderow"> 
<td><input type="radio" name="trade_id" value="1"></td>
<td colspan="2" align="left"><a title="No ref and bookmark hits">Bookmarks</a></td>
<td bgcolor="#80FFFF">$in_hour</td>
<td bgcolor="#80FFFF">$uniq_hour</td>
<td bgcolor="#FFFF00">$out_hour</td>
<td bgcolor="#80FF80">$clicks_hour</td>
<td bgcolor="#FF80FF">$prod_hour</td>
<td bgcolor="#FF8000">--</td>
<td bgcolor="#80FFFF">$in_total</td>
<td bgcolor="#80FFFF">$uniq_total</td>
<td bgcolor="#FFFF00">$out_total</td>
<td bgcolor="#80FF80">$clicks_total</td>
<td bgcolor="#FF80FF">$prod_total</td>
<td bgcolor="#FF8000">--</td>
<td bgcolor="#CCCCCC">--</td>
<td>--</td>
        </tr>
END;
$res = mysql_query("SELECT clicks$thishour AS clicks_hour, clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_total FROM ttt_stats WHERE ttt_stats.trade_id=2") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);

$sum["clicks_hour"] += $clicks_hour;
$sum["clicks_total"] += $clicks_total;

print <<<END
        <tr class="traderow"> 
<td><input type="radio" name="trade_id" value="2"></td>
<td colspan="2" align="left"><a title="Clicks without a valid TTT Cookie">No Cookie</a></td>
<td bgcolor="#80FFFF">--</td>
<td bgcolor="#80FFFF">--</td>
<td bgcolor="#FFFF00">--</td>
<td bgcolor="#80FF80">$clicks_hour</td>
<td bgcolor="#FF80FF">--</td>
<td bgcolor="#FF8000">--</td>
<td bgcolor="#80FFFF">--</td>
<td bgcolor="#80FFFF">--</td>
<td bgcolor="#FFFF00">--</td>
<td bgcolor="#80FF80">$clicks_total</td>
<td bgcolor="#FF80FF">--</td>
<td bgcolor="#FF8000">--</td>
<td bgcolor="#CCCCCC">--</td>
<td>--</td>
        </tr>
END;

$res = mysql_query("SELECT out$thishour AS out_hour, out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out_total FROM ttt_stats WHERE ttt_stats.trade_id=3") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
close_conn();

print <<<END
        <tr class="toprows"> 
<td background="background.gif" colspan="3">Total</td>
<td background="background.gif"><a title="Raw In">rIn</a></td>
<td background="background.gif"><a title="Unique In">uIn</a></td>
<td background="background.gif"><a title="Out to Trade">Out</a></td>
<td background="background.gif"><a title="Clicks by Trader">Clicks</a></td>
<td background="background.gif">Prod</td>
<td background="background.gif"><a title="Actual Ratio">aRatio</a></td>
<td background="background.gif"><a title="Raw In">rIn</a></td>
<td background="background.gif"><a title="Unique In">uIn</a></td>
<td background="background.gif"><a title="Out to Trade">Out</a></td>
<td background="background.gif"><a title="Clicks by Trader">Clicks</a></td>
<td background="background.gif">Prod</td>
<td background="background.gif"><a title="Actual Ratio">aRatio</a></td>
<td background="background.gif">Ratio</td>
<td background="background.gif"><a title="F = Force   Fd = Forced"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="toprows2"><tr align="center"><td width="50%">F</td><td>|</td><td width="50%">Fd</td></tr></table></a></td>
        </tr>

END;
?>
   
<?php
if ($sum["in_hour"] == 0) {
	$sum["prod_hour"] = "no hits";
	$sum["return_hour"] = "no hits";
	$sum["uniq_pct_hour"] = "no hits";
}
else {
	$sum["prod_hour"] = "" . round($sum["clicks_hour"]/$sum["in_hour"]*100, 1) . "%";
	$sum["return_hour"] = "" . round($sum["out_hour"]/$sum["in_hour"]*100, 1) . "%";
	$sum["uniq_pct_hour"] = "" . round($sum["uniq_hour"]/$sum["in_hour"]*100, 1) . "%";
}
if ($sum["in_total"] == 0) {
	$sum["prod_total"] = "no hits";
	$sum["return_total"] = "no hits";
	$sum["uniq_pct_total"] = "no hits";
}
else {
	$sum["prod_total"] = "" . round($sum["clicks_total"]/$sum["in_total"]*100, 1) . "%";
	$sum["return_total"] = "" . round($sum["out_total"]/$sum["in_total"]*100, 1) . "%";
	$sum["uniq_pct_total"] = "" . round($sum["uniq_total"]/$sum["in_total"]*100, 1) . "%";
}
print <<<END
        <tr class="toprows" height="25"> 
<td bgcolor="#EFEFEF" colspan="3">&nbsp;</td>
<td bgcolor="#80FFFF">$sum[in_hour]</td>
<td bgcolor="#80FFFF">$sum[uniq_hour]</td>
<td bgcolor="#FFFF00">$sum[out_hour]</td>
<td bgcolor="#80FF80">$sum[clicks_hour]</td>
<td bgcolor="#FF80FF">$sum[prod_hour]</td>
<td bgcolor="#FF8000">$sum[return_hour]</td>
<td bgcolor="#80FFFF">$sum[in_total]</td>
<td bgcolor="#80FFFF">$sum[uniq_total]</td>
<td bgcolor="#FFFF00">$sum[out_total]</td>
<td bgcolor="#80FF80">$sum[clicks_total]</td>
<td bgcolor="#FF80FF">$sum[prod_total]</td>
<td bgcolor="#FF8000">$sum[return_total]</td>
<td bgcolor="#CCCCCC">&nbsp;</td>
<td bgcolor="#EFEFEF"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="toprows2"><tr align="center"><td width="50%">$sum[myforce]</td><td>|</td><td width="50%">$sum[myforced]</td></tr></table></td>
END;
?>
        </tr>
      </table></td>
  </tr>
</table>
<br>

<table width="760" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center">
  <tr align="center">
    <td background="background.gif"><input name="edit" type="button" class="buttons" id="edit" value="   Edit   " onclick="openwin('edit')"></td>
    <td background="background.gif"><input name="iplog" type="button" class="buttons" id="iplog" value="   IP Log   " onclick="openwin('iplog')"></td>
    <td background="background.gif"><input name="reflog" type="button" class="buttons" id="reflog" value="   Ref Log   " onclick="openwin('reflog')"></td>
    <td background="background.gif"><input name="reset" type="button" class="buttons" id="reset" value="   Reset   " onclick="openwin('reset')"></td>
    <td background="background.gif"><input name="delete" type="button" class="buttons" id="delete" value="   Delete   " onclick="openwin('delete')"></td>
    <td background="background.gif"><input name="turbo" type="button" class="buttons" value="  Add/Delete Turbo  " onclick="openwin('turbotrade')"></td>
  </tr>
</table>
</p>
</form>






<table width="760" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center">
  <tr>
    <td background="background.gif" width="30%">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="traderow">
  <tr>
    <td align="left"><font size="1"><b>Total: <?php echo $total_trades; ?></b></font></td>
    <td align="right"><font size="1"><b>Max: <?php echo $max_trades; ?>&nbsp;&nbsp;</b></font></td>
  </tr>
</table>
    </td>
    <td background="background.gif" width="70%">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="traderow">
  <tr>
    <td style="border: 1 solid #000000" width="15" bgcolor="#0099CC">&nbsp;</td>
    <td align="left"><b>&nbsp;A disabled trade&nbsp;</b></td>
    <td style="border: 1 solid #000000" width="15" bgcolor="#00CC66">&nbsp;</td>
    <td align="left"><b>&nbsp;Trade in turbo&nbsp;</b></td>
    <td style="border: 1 solid #000000" width="15" bgcolor="#FFFF99">&nbsp;</td>
    <td align="left"><b>&nbsp;Trade auto disabled&nbsp;</b></td>
  </tr>
</table>
    </td>
  </tr>
  <tr>
<td align="left" width="100%" colspan="2">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="traderow">
<tr  align="left">
<td width="100%">
<b>To URL:</b> &quot;out.php?url=http://www.example.com&quot;<br>
<b>Skimming:</b> &quot;out.php?pct=50&url=http://www.example.com&quot;<br>
<b>First Click:</b> &quot;out.php?f=1&pct=50&url=http://www.example.com&quot;<br>
<b>To Trade:</b> &quot;out.php?trade=example.com&quot;<br>
<b>Track Link:</b> &quot;out.php?link=blah&quot;<br>
Remember: Trades that exceed the minimum or maximum productivty are auto disabled<br>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<center>
<a target="_blank" href="http://www.x-forum.info/showthread.php?s=&threadid=1383">Powered by Turbo Traffic Drive xEdition v1.0.1</a>
</center>
</body>
</html>