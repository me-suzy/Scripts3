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
require("../ttt-mysqlvalues.inc.php");
require("../ttt-mysqlfunc.inc.php");
open_conn();
require("security.inc.php");

$time = localtime();
$thishour = $time[2];
if (!$_GET[h]) { $_GET["h"] = $thishour; }
$selhour = $_GET[h];
if (!$selhour) { $selhour="0"; }
$res = mysql_query("SELECT turbomode, max_trades FROM ttt_settings");
$row = mysql_fetch_array($res);
extract($row);
$res = mysql_query("SELECT COUNT(*) FROM ttt_trades");
$total_trades = mysql_fetch_array($res);
$total_trades = $total_trades[0]-3;
if ($_GET["checktrades"] == 1) { $msg = "Checking Trades From findtrades.com's database"; }
elseif ($turbomode == 1) { $msg = "Trades - Script Currently In Turbo Mode"; }
else { $msg = "Trades"; }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>TurboTrafficTrader</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<?php if (file_exists("../ttt-install.php")) { ?>
alert("\n\nYOU HAVE NOT DELETED \"ttt-install.php\". PLEASE DELETE THE FILE!\n\n\n");
<?php } ?>
function openwin(what) {
var edittemp = "";
var idtemp = "";
var f = "0";

for (i=0; i<document.form1.trade_id.length; i++) { if (document.form1.trade_id[i].checked) { if (f == 0) { var idtemp=idtemp+document.form1.trade_id[i].value; } else { var idtemp=idtemp+"|"+document.form1.trade_id[i].value; } f++; }}

if (what == "addtrade") { url = "addtrade.php"; features = "width=400,height=420,status=0,resizable=1"; }
if (what == "edit" && idtemp != "") { url = "edit.php?id="+idtemp; features = "width=660,height=580,scrollbars=1,resizable=1"; }
if (what == "iplog") { url = "iplog.php?id="+idtemp; features = "width=340,height=400,status=1,resizable=1,scrollbars=1"; }
if (what == "reflog") { url = "reflog.php?id="+idtemp; features = "width=500,height=400,status=1,resizable=1,scrollbars=1"; }
if (what == "settings") { url = "settings.php"; features = "width=440,height=500,status=1,resizable=1,scrollbars=1"; }
if (what == "delete") { url = "delete.php?id="+idtemp; features = "width=400,height=180,status=0,resizable=1,scrollbars=0"; }
if (what == "reset") { url = "reset.php?id="+idtemp; features = "width=400,height=120,status=0,resizable=1,scrollbars=0"; }
if (what == "turbo" || what == "turbo_off" || what == "turbo_on") {
	if (what == "turbo_on") { url = "turbo.php?mode=on"; }
	if (what == "turbo_off") { url = "turbo.php?mode=off"; }
	features = "width=450,height=120,status=0,resizable=1,scrollbars=0";
}
if (what == "turbotrade") { url = "turbo.php?id="+idtemp; features = "width=500,height=180,status=0,resizable=1,scrollbars=0"; }
if (what == "nitro") { url = "nitro.php?id="+idtemp; features = "width=500,height=120,status=0,resizable=1,scrollbars=0"; }
if (what == "toplist") { url = "toplist.php"; features = "width=490,height=550,status=0,resizable=1,scrollbars=1"; }
if (what == "changepass") { url = "settings.php?change=1"; features = "width=400,height=310,status=0,resizable=1"; }
if (what == "links") { url = "links.php"; features = "width=750,height=350,status=0,resizable=1,scrollbars=1"; }
if (what == "daily") { url = "daily.php"; features = "width=490,height=400,status=0,resizable=1,scrollbars=1"; }
if (what == "events") { url = "events.php"; features = "width=490,height=400,status=0,resizable=1,scrollbars=1"; }
if (what == "groups") { url = "groups.php"; features = "width=490,height=400,status=0,resizable=1,scrollbars=1"; }
if (what == "blacklist") { url = "blacklist.php"; features = "width=600,height=400,status=0,resizable=1,scrollbars=1"; }
window.open(url, "_blank", features);
}
</script>
<script language="JavaScript">
<!--
function Select() {
if (document.form1.masscheck.checked == true) {
selectStatus=0;
for (var i=0; i<=(document.form1.trade_id.length-4); i++) {
if (selectStatus == 0) { document.form1.trade_id[i].checked = true; }
else { document.form1.trade_id[i].checked = false; }
}
if(selectStatus == 0){ selectStatus = 1 }
else{ selectStatus = 0 }
}
else {
selectStatus=0;
for (var i=0; i<=(document.form1.trade_id.length-4); i++) {
if (selectStatus == 0) { document.form1.trade_id[i].checked = false; }
else { document.form1.trade_id[i].checked = false; }
}
if(selectStatus == 0){ selectStatus = 0 }
else{ selectStatus = 0 }
}
}
//-->
</script>
<base target="_blank">
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000" topmargin=0 marginheight=0>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="30" background="background2.gif">
  <tr>
   <td align=center valign=middle height=30>
    <a href="http://www.chickentraffic.com" target="_blank"><img src="tcn_ct.gif" border=0 width=102 height=20 alt=''></a>&nbsp;
    <a href="http://www.chickenboard.com" target="_blank"><img src="tcn_cb.gif" border=0 width=97 height=20 alt=''></a>&nbsp;
    <a href="http://www.findtrades.com" target="_blank"><img src="tcn_ft.gif" border=0 width=81 height=20 alt=''></a>&nbsp;
    <a href="http://www.galleryspots.com" target="_blank"><img src="tcn_gs.gif" border=0 width=91 height=20 alt=''></a>&nbsp;
    <a href="http://www.quicktgp.com" target="_blank"><img src="tcn_qt.gif" border=0 width=69 height=20 alt=''></a>&nbsp;
    <a href="http://www.sellmetraffic.com" target="_blank"><img src="tcn_smt.gif" border=0 width=97 height=20 alt=''></a>&nbsp;
    <a href="http://www.turbotraffictrader.com" target="_blank"><img src="tcn_ttt.gif" border=0 width=135 height=20 alt=''></a>
   </td>
  </tr>
</table><br>
<?
$res=mysql_query("select sum(nht) as total_nht, sum(nhs) as total_nhs from ttt_forces");
$row=mysql_fetch_array($res);
extract($row);
$total_nhr=($total_nht-$total_nhs);
?>
<form name="form1" action="">
<table width="750" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="700" valign="top" align="center" bgcolor=#FFFFFF>
     <!-- CHICKENBANNERS -->
     <SCRIPT LANGUAGE="JavaScript" SRC="http://www.chickenbanners.com/ads.php"></SCRIPT>
     <!-- /CHICKENBANNERS --><br>
    </td>
  </tr>
  <tr class="traderow">
    <td width="700" valign="top"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" bgcolor="#E4E4E4">
        <tr class="toprows"> 
          <td colspan="10" background="background.gif" height="30"><img src="ttt.gif"></td>
        </tr>
        <tr class="normalrow"> 
          <td colspan="10" height="20">This Is a Free Version of ePower Trader - <a href="http://www.epowerscripts.com/?ref=TTT" target="_blank">Click 
            Here For More Info</a></td>
        </tr>
        <tr class="menu" height="20"> 
          <td><a href="trades.php" target="_self">Refresh</a></td>
          <td><a href="javascript:openwin('links')" target="_self">Links</a></td>
          <td><a href="javascript:openwin('daily')" target="_self">Daily</a></td>
          <td><a href="javascript:openwin('events')" target="_self">Events</a></td>
          <td><a href="javascript:openwin('settings')" target="_self">Settings</a></td>
          <td><a href="javascript:openwin('blacklist')" target="_self">Blacklist</a></td>
          <td><a href="javascript:openwin('toplist')" target="_self">Toplist</a></td>
          <td><a href="javascript:openwin('changepass')" target="_self">Change Pass</a></td>
	  <td><a href="javascript:openwin('groups')" target="_self">Groups</a></td>
          <td><a href="javascript:openwin('addtrade')" target="_self">Add Trade</a></td>
        </tr>
	<tr class="menu" height="20"> 
          <td colspan="10">
	  <a href="trades.php?checktrades=1" target="_self">Check Trades</a>
	  <font color="#000000">|</font> <a href="http://chickenboard.com/list.php?f=13">Support</a>
          <font color="#000000">|</font> <a href="http://www.chickentraffic.com/">Feeder Traffic</a>
          <font color="#000000">|</font> <a href="http://www.findtrades.com/">Find Trades</a>
          <font color="#000000">|</font> <a href="http://www.quicktgp.com/gs3/categories.php">Get Galleries</a>
          <font color="#000000">|</font> <a href="http://www.findtrades.com/">Report Cheater</a>
        </tr>
	<tr class="menu" height="20"> 
          <td colspan="10">
          <a href="overview.php" target="_self"><font color=#FF0000>MTTTS Overview</font></a>
	  <font color="#000000">|</font> <a href="qtgp_index.php" target="_self"><font color=#FF0000>QuickTGP TGP Builder</font></a>
	  <font color="#000000">|</font> <a href="ctc_index.php" target="_self"><font color=#FF0000>CTC Overview</font></a>
	  <font color="#000000">|</font> <a href="gs3_index.php" target="_self"><font color=#FF0000>GS3 Statistics</font></a></td>	
        </tr>
      </table></td>
  </tr>
  <tr class="traderow">
    <td align="left">
	<table border=0 cellspacing=0 cellpadding=0 width=700>
	<tr>
	<td width=100% valign=top align=left>
	      <font size="1">
	      <b>Server Time:</b> <?php echo date("M, j G:i:s"); ?><br>
	      <b>Logged in:</b> <?php echo date("M, j G:i:s", $cookie[0]); ?><br></font>
	</td>
        </tr>
	</table>
    </td>
  </tr>
</table>
<center><br>
<table border="0" align=center cellspacing=0 cellpadding=2 class=traderow width=850>
<tr>
<td width=100% align=center>
<table border="0" align=center cellspacing=0 cellpadding=2 class=traderow>
<tr class="traderow">
    <td valign="top" align=center>
<?php if ($turbomode == 1) { ?>
    <a href="javascript:openwin('turbo_off')" target="_self"><img src="turbo_on.gif" width="110" height="53" border="0"></a>
<?php } else { ?>
    <a href="javascript:openwin('turbo_on')" target="_self"><img src="turbo_off.gif" width="110" height="53" border="0"></a>
<?php } ?>
    </td>
    <td valign=bottom align=center rowspan=2>
  <font size="4"><strong><?php echo $msg; ?></strong></font>
    </td>
    <td valign="top" align=center>
<?php if ($total_nhr >= 1) { ?>
    <a href="javascript:openwin('nitro')" target="_self"><img src="nitro_on.gif" width="110" height="43" border="0"></a>
<?php } else { ?>
    <a href="javascript:openwin('nitro')" target="_self"><img src="nitro_off.gif" width="110" height="43" border="0"></a>
<?php } ?>
    </td>
    </tr>
    <tr class="traderow">
    <td valign="top" align=center>
<?php if ($turbomode == 1) { ?>
    <b>Disable Turbo</b>
<?php } else { ?>
    <b>Enable Turbo</b>
<?php } ?>
    </td>
    <td valign="top" align=center>
<?php if ($total_nhr >= 1) { ?>
    <b><?php echo"$total_nhr";?> Hits Left</b>
<?php } else { ?>
    <b>Add Nitro</b>
<?php } ?>
    </td>
</tr>
</table>
</td>
</tr>
</table>
<?php if ($_GET["checktrades"] == 1) { ?>
<table border="0" align="center" cellpadding="0" cellspacing="2" class="traderow">
  <tr> 
    <td bgcolor="#00CC00" width="20">&nbsp;</td>
    <td align="left" width="160">In findtrades.com's DB</td>
    <td bgcolor="#FF0000" width="20">&nbsp;</td>
    <td align="left" width="200">Blacklisted at findtrades.com</td>
    <td bgcolor="#EFEFEF" width="20">&nbsp;</td>
    <td align="left" width="150">Not in database</td>
  </tr>
</table>
<?php
} 
switch($_GET[v]) {
	case "": 
	  $ob="in_total";
	  $oo="DESC";
	  $_GET[v]="default";
	break;
	case "default": 
	  $ob="in_total";
	  $oo="DESC";
	  $_GET[v]="default";
	break;
	case "domain": 
	  $ob="sitedomain";
	  $oo="ASC";
	  $_GET[v]="domain";
	break;
}
?>
</center>
<table width="850" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="1">
        <tr class="toprows"> 
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="5">This Hour</td>
          <td colspan="5">Last 23 Hours + This Hour</td>
          <td>&nbsp;</td>
          <td>&nbsp;&nbsp;Force&nbsp;&nbsp;</td>
        </tr>
        <tr class="toprows"> 
          <td><input type=checkbox name=masscheck onClick='Select()'></td>
          <td>Site Domain</td>
          <td>Q</td>
          <td width="40">In</td>
          <td width="40">Out</td>
          <td>Clicks</td>
          <td>Prod</td>
          <td>Return</td>
          <td>&nbsp;In&nbsp;</td>
          <td>&nbsp;Out&nbsp;</td>
          <td>Clicks</td>
          <td>Prod</td>
          <td>Return</td>
          <td>Ratio</td>
          <td><a title="F = Force   Fd = Forced"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="toprows"><tr align="center"><td width="50%">F</td><td>|</td><td width="50%">Fd</td></tr></table></a></td>
        </tr>
<?php
$res = mysql_query("SELECT ttt_trades.trade_id, sitedomain, siteurl, sitename, wm_email, wm_icq, ratio,  enabled, turbo, status, nht, nhs, in$selhour AS in_hour, uniq$selhour AS uniq_hour, tclicks$selhour as tclicks_hour, clicks$selhour AS clicks_hour, out$selhour AS out_hour, in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23 AS in_total, uniq0+uniq1+uniq2+uniq3+uniq4+uniq5+uniq6+uniq7+uniq8+uniq9+uniq10+uniq11+uniq12+uniq13+uniq14+uniq15+uniq16+uniq17+uniq18+uniq19+uniq20+uniq21+uniq22+uniq23 AS uniq_total, tclicks0+tclicks1+tclicks2+tclicks3+tclicks4+tclicks5+tclicks6+tclicks7+tclicks8+tclicks9+tclicks10+tclicks11+tclicks12+tclicks13+tclicks14+tclicks15+tclicks16+tclicks17+tclicks18+tclicks19+tclicks20+tclicks21+tclicks22+tclicks23 as tclicks_total, clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_total, out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out_total, fh$selhour AS myforce, fd$selhour AS myforced FROM ttt_trades, ttt_stats, ttt_forces WHERE ttt_trades.trade_id=ttt_stats.trade_id AND ttt_trades.trade_id=ttt_forces.trade_id AND ttt_trades.trade_id!=1 AND ttt_trades.trade_id!=2 AND ttt_trades.trade_id!=3 ORDER BY $ob $oo") or print_error(mysql_error());
while ($row = mysql_fetch_array($res)) {
extract($row);
$sum["tclicks_hour"] += $tclicks_hour;
$sum["tclicks_total"] += $tclicks_total;
$tclicks_hour=$tclicks_hour+$clicks_hour;
$tclicks_total=$tclicks_total+$clicks_total;
if ($in_hour == 0) {
	$tprod_hour = "no hits";
	$prod_hour = "no hits";
	$return_hour = "no hits";
	$uniq_pct_hour = "no hits";
}
else {
	$tprod_hour = "" . round($tclicks_hour/$in_hour*100, 1) . "%";
	$prod_hour = "" . round($clicks_hour/$in_hour*100, 1) . "%";
	$return_hour = "" . round($out_hour/$in_hour*100, 1) . "%";
	$uniq_pct_hour = "" . round($uniq_hour/$in_hour*100, 1) . "%";
}
if ($in_total == 0) {
	$tprod_total = "no hits";
	$prod_total = "no hits";
	$return_total = "no hits";
	$uniq_pct_total = "no hits";
}
else {
	$tprod_total = "" . round($tclicks_total/$in_total*100, 1) . "%";
	$prod_total = "" . round($clicks_total/$in_total*100, 1) . "%";
	$return_total = "" . round($out_total/$in_total*100, 1) . "%";
	$uniq_pct_total = "" . round($uniq_total/$in_total*100, 1) . "%";
}
if ($out_total == 0) { $tqvalue = "--"; }
else { $tqvalue = number_format($tclicks_total/$out_total, 2); }
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
if ($_GET["checktrades"] == 1)
{
	$findtrades = @implode("",@file("http://www.findtrades.com/ttt.php?d=$sitedomain"));
	if ($findtrades == "1\n") { $bg = "bgcolor=\"#FF0000\""; }
	elseif ($findtrades == "2\n") { $bg = "bgcolor=\"#00CC00\""; }
	else { $bg = "bgcolor=\"#EFEFEF\""; }
}
elseif ($enabled == 0) { $bg = "bgcolor=\"#0099CC\""; }
elseif ($enabled == 2) { $bg = "bgcolor=\"#FFFF99\""; }
elseif ($turbo == 1) { $bg = "bgcolor=\"#00CC66\""; }
else { $bg = "bgcolor=\"#EFEFEF\""; }
if ($status == 0) { $bg = "bgcolor=\"#FFC0C0\""; }
if ($nht-$nhs >= 1) { $bg = "bgcolor=\"#FFCC33\""; }
print <<<END
        <tr $bg class="traderow"> 
          <td><input type="checkbox" name="trade_id" value="$trade_id"></td>
          <td align="left"><a href="$siteurl" title="Sitename: $sitename\nEmail: $wm_email\nICQ: $wm_icq">$sitedomain</a></td>
          <td><a title="Internal Q: $qvalue">$tqvalue</a></td>
          <td><a title="Uniques: $uniq_hour - Unique %: $uniq_pct_hour">$in_hour</a></td>
          <td>$out_hour</td>
          <td><a title="Internal Clicks: $clicks_hour">$tclicks_hour</a></td>
          <td><a title="Internal Prod: $prod_hour">$tprod_hour</a></td>
          <td>$return_hour</td>
          <td><a title="Uniques: $uniq_total - Unique %: $uniq_pct_total">$in_total</a></td>
          <td>$out_total</td>
          <td><a title="Internal Clicks: $clicks_total">$tclicks_total</a></td>
          <td><a title="Internal Prod: $prod_hour">$tprod_total</a></td>
          <td>$return_total</td>
          <td>$ratio</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="traderow" $bg><tr align="center"><td width="50%">$myforce</td><td>|</td><td width="50%">$myforced</td></tr></table></td>
        </tr>
        
END;
}
?>
        <tr class="toprows"> 
          <td>&nbsp;</td>
          <td>Site Domain</td>
          <td>Q</td>
          <td>In</td>
          <td>Out</td>
          <td>Clicks</td>
          <td>Prod</td>
          <td>Return</td>
          <td>In</td>
          <td>Out</td>
          <td>Clicks</td>
          <td>Prod</td>
          <td>Return</td>
          <td>Ratio</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="toprows"><tr align="center"><td width="50%">F</td><td>|</td><td width="50%">Fd</td></tr></table></td>
        </tr>
<?php
unset($tclicks_hour);
unset($tclicks_total);
$res = mysql_query("SELECT in$selhour AS in_hour, uniq$selhour AS uniq_hour, tclicks$selhour as tclicks_hour, clicks$selhour AS clicks_hour, out$selhour AS out_hour, in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in21+in22+in23 AS in_total, uniq0+uniq1+uniq2+uniq3+uniq4+uniq5+uniq6+uniq7+uniq8+uniq9+uniq10+uniq11+uniq12+uniq13+uniq14+uniq15+uniq16+uniq17+uniq18+uniq19+uniq20+uniq21+uniq22+uniq23 AS uniq_total, tclicks0+tclicks1+tclicks2+tclicks3+tclicks4+tclicks5+tclicks6+tclicks7+tclicks8+tclicks9+tclicks10+tclicks11+tclicks12+tclicks13+tclicks14+tclicks15+tclicks16+tclicks17+tclicks18+tclicks19+tclicks20+tclicks21+tclicks22+tclicks23 as tclicks_total, clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_total, out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out_total FROM ttt_stats WHERE ttt_stats.trade_id=1") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sum["tclicks_hour"] += $tclicks_hour;
$sum["tclicks_total"] += $tclicks_total;
$tclicks_hour=$tclicks_hour+$clicks_hour;
$tclicks_total=$tclicks_total+$clicks_total;
if ($in_hour == 0) { $prod_hour = "no hits"; $tprod_hour = "no hits"; $uniq_pct_hour = "no hits"; }
else { $prod_hour = "" . round($clicks_hour/$in_hour*100, 1) . "%"; $tprod_hour = "" . round($tclicks_hour/$in_hour*100, 1) . "%"; $uniq_pct_hour = "" . round($uniq_hour/$in_hour*100, 1) . "%"; }
if ($in_total == 0) { $prod_total = "no hits"; $prod_total = "no hits"; $uniq_pct_total = "no hits"; }
else { $prod_total = "" . round($clicks_total/$in_total*100, 1) . "%"; $tprod_total = "" . round($tclicks_total/$in_total*100, 1) . "%"; $uniq_pct_total = "" . round($uniq_total/$in_total*100, 1) . "%"; }
$sum["in_hour"] += $in_hour;
$sum["uniq_hour"] += $uniq_hour;
$sum["clicks_hour"] += $clicks_hour;
$sum["out_hour"] += $out_hour;
$sum["in_total"] += $in_total;
$sum["uniq_total"] += $uniq_total;
$sum["clicks_total"] += $clicks_total;
$sum["out_total"] += $out_total;
print <<<END
        <tr class="grayrows"> 
          <td><input type="checkbox" name="trade_id" value="1"></td>
          <td align="left"><a title="No ref and bookmark hits">Bookmarks</a></td>
          <td>--</td>
          <td><a title="Uniques: $uniq_hour - Unique %: $uniq_pct_hour">$in_hour</a></td>
          <td>$out_hour</td>
          <td><a title="Internal Clicks: $clicks_hour">$tclicks_hour</a></td>
          <td><a title="Internal Prod: $prod_hour">$tprod_hour</a></td>
          <td>--</td>
          <td><a title="Uniques: $uniq_total - Unique %: $uniq_pct_total">$in_total</a></td>
          <td>$out_total</td>
          <td><a title="Internal Clicks: $clicks_total">$tclicks_total</a></td>
          <td><a title="Internal Prod: $prod_total">$tprod_total</a></td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
        </tr>
END;
unset($tclicks_hour);
unset($tclicks_total);
$res = mysql_query("SELECT tclicks$selhour as tclicks_hour, clicks$selhour AS clicks_hour, tclicks0+tclicks1+tclicks2+tclicks3+tclicks4+tclicks5+tclicks6+tclicks7+tclicks8+tclicks9+tclicks10+tclicks11+tclicks12+tclicks13+tclicks14+tclicks15+tclicks16+tclicks17+tclicks18+tclicks19+tclicks20+tclicks21+tclicks22+tclicks23 as tclicks_total, clicks0+clicks1+clicks2+clicks3+clicks4+clicks5+clicks6+clicks7+clicks8+clicks9+clicks10+clicks11+clicks12+clicks13+clicks14+clicks15+clicks16+clicks17+clicks18+clicks19+clicks20+clicks21+clicks22+clicks23 AS clicks_total FROM ttt_stats WHERE ttt_stats.trade_id=2") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$tclicks_hour=$tclicks_hour+$clicks_hour;
$tclicks_total=$tclicks_total+$clicks_total;
$sum["clicks_hour"] += $clicks_hour;
$sum["clicks_total"] += $clicks_total;
print <<<END
        <tr class="grayrows"> 
          <td><input type="checkbox" name="trade_id" value="2"></td>
          <td align="left"><a title="Clicks without a valid TTT Cookie">No Cookie</a></td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
          <td><a title="Counted Clicks: $clicks_hour">$tclicks_hour</a></td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
          <td><a title="Counted Clicks: $clicks_hour">$tclicks_total</a></td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
        </tr>
END;

$res = mysql_query("SELECT out$selhour AS out_hour, out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS out_total FROM ttt_stats WHERE ttt_stats.trade_id=3") or print_error(mysql_error());
$row = mysql_fetch_array($res);
$res = mysql_query("UPDATE ttt_trades SET status='1' WHERE status='0'");
extract($row);
close_conn();

print <<<END
        <tr class="grayrows"> 
          <td><input type="checkbox" name="trade_id" value="3"></td>
          <td align="left"><a title="Traffic sent to Choker as fee. ONLY 1%!">Choker</a></td>
          <td>--</td>
          <td>--</td>
          <td>$out_hour</td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
          <td>$out_total</td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
          <td>--</td>
        </tr>
END;
?>
   
<?php
$sum[tclicks_hour]=$sum[tclicks_hour]+$sum[clicks_hour];
$sum[tclicks_total]=$sum[tclicks_total]+$sum[clicks_total];
if ($sum["in_hour"] == 0) {
	$sum["prod_hour"] = "no hits";
	$sum["tprod_hour"] = "no hits";
	$sum["return_hour"] = "no hits";
	$sum["uniq_pct_hour"] = "no hits";
}
else {
	$sum["prod_hour"] = "" . round($sum["clicks_hour"]/$sum["in_hour"]*100, 1) . "%";
	$sum["tprod_hour"] = "" . round($sum["tclicks_hour"]/$sum["in_hour"]*100, 1) . "%";
	$sum["return_hour"] = "" . round($sum["out_hour"]/$sum["in_hour"]*100, 1) . "%";
	$sum["uniq_pct_hour"] = "" . round($sum["uniq_hour"]/$sum["in_hour"]*100, 1) . "%";
}
if ($sum["in_total"] == 0) {
	$sum["prod_total"] = "no hits";
	$sum["tprod_total"] = "no hits";
	$sum["return_total"] = "no hits";
	$sum["uniq_pct_total"] = "no hits";
}
else {
	$sum["prod_total"] = "" . round($sum["clicks_total"]/$sum["in_total"]*100, 1) . "%";
	$sum["tprod_total"] = "" . round($sum["tclicks_total"]/$sum["in_total"]*100, 1) . "%";
	$sum["return_total"] = "" . round($sum["out_total"]/$sum["in_total"]*100, 1) . "%";
	$sum["uniq_pct_total"] = "" . round($sum["uniq_total"]/$sum["in_total"]*100, 1) . "%";
}
print <<<END
        <tr class="toprows" height="25"> 
          <td>--</td>
          <td align="left">Total</td>
          <td>&nbsp;</td>
          <td><a title="Uniques: $sum[uniq_hour] - Unique %: $sum[uniq_pct_hour]">$sum[in_hour]</a></td>
          <td>$sum[out_hour]</td>
          <td><a title="Internal Clicks: $sum[clicks_hour]">$sum[tclicks_hour]</a></td>
          <td><a title="Internal Prod: $sum[prod_hour]">$sum[tprod_hour]</a></td>
          <td>$sum[return_hour]</td>
          <td><a title="Uniques: $sum[uniq_total] - Unique %: $sum[uniq_pct_total]">$sum[in_total]</a></td>
          <td>$sum[out_total]</td>
          <td><a title="Internal Clicks: $sum[clicks_total]">$sum[tclicks_total]</a></td>
          <td><a title="Internal Prod: $sum[prod_total]">$sum[tprod_total]</a></td>
          <td>$sum[return_total]</td>
          <td>--</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="toprows"><tr align="center"><td width="50%">$sum[myforce]</td><td>|</td><td width="50%">$sum[myforced]</td></tr></table></td>
END;
?>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<?
for($i=0; $i<24; $i++) { 
if ($i < 10) { $di="0$i"; } else { $di="$i"; }
if ($i == $_GET["h"]) {
if ($i == $thishour) { 
$hourselect.=" This Hour |";
}
else {
$hourselect.=" $di |";
}
}
else {
if ($i == $thishour) { 
$hourselect.=" <a href='trades.php?h=$i&v=$_GET[v]' target='_self'>This Hour</a> |";
}
else {
$hourselect.=" <a href='trades.php?h=$i&v=$_GET[v]' target='_self'>$di</a> |";
}
}
}
$hourselect=substr($hourselect,0,strlen($hourselect)-2);
?>
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td colspan=2 align=center><font size=1><b>Hours: </b><?php echo"$hourselect"; ?></font></td>
  </tr>
</table>
<br>
<table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><font size="1"><b>Total: </b><?php echo $total_trades; ?></font></td>
    <td align="right"><font size="1"><strong>Max Trades:</strong> <?php echo $max_trades; ?></font></td>
  </tr>
</table>
<br>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr align="center">
    <td><input name="edit" type="button" class="buttons" id="edit" value=" Edit " onclick="openwin('edit')"></td>
    <td><input name="iplog" type="button" class="buttons" id="iplog" value=" IP Log " onclick="openwin('iplog')"></td>
    <td><input name="reflog" type="button" class="buttons" id="reflog" value=" Ref Log " onclick="openwin('reflog')"></td>
    <td><input name="reset" type="button" class="buttons" id="reset" value=" Reset " onclick="openwin('reset')"></td>
    <td><input name="delete" type="button" class="buttons" id="delete" value=" Delete " onclick="openwin('delete')"></td>
    <td><input name="nitro" type="button" class="buttons" value="Add Nitro Hits" onclick="openwin('nitro')"></td>
    <td><input name="turbo" type="button" class="buttons" value="Add/Delete Turbo" onclick="openwin('turbotrade')"></td>
  </tr>
</table>
</p>
</form><br>
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr class="traderow"> 
    <td width="45%" align="left"><b><font size="3">Useful Links:</font></b></td>
    <td width="55%" align="left"><b><font size="3">Useful Info:</font></b></td>
  </tr>
  <tr class="traderow">
    <td align="left" valign="top"><strong>Webmaster Page:</strong> <a href="../ttt-webmaster.php">ttt-webmaster.php</a><br>
      <b>Out Script:</b> <a href="../ttt-out.php">ttt-out.php</a><br>
      <b>TTT Help:</b> <a href="http://www.turbotraffictrader.com/help/">ttt-help.php</a><br>
      <b>TTT Support Forum:</b> <a href="http://chickenboard.com/list.php?f=13">Support</a><br>
      Please do NOT ICQ Choker or Eskimoen.<br>Use the support forum!<br>
      <b>ePower Trader:</b> <a href="http://www.epowerscripts.com/?ref=TTT">http://www.epowerscripts.com</a><br>
      <b>Findtrades.com:</b> <a href="http://www.findtrades.com">http://www.findtrades.com</a><br>
      <b>Chicken Board:</b> <a href="http://www.chickenboard.com">http://www.chickenboard.com</a><br>
      <b>TTT Home:</b> <a href="http://www.turbotraffictrader.com">http://www.turbotraffictrader.com</a><br>
    </td>
    <td align="left" valign="top">
      <table width="100%" border="0" cellspacing="2" cellpadding="0" class="traderow">
  <tr>
    <td width="20" bgcolor="#FFC0C0" style="border: 1px solid black;">&nbsp;</td>
    <td align="left">A new trade since you last logged in</td>
  </tr>
  <tr>
    <td width="20" bgcolor="#0099CC" style="border: 1px solid black;">&nbsp;</td>
    <td align="left">A disabled trade</td>
  </tr>
  <tr>
    <td width="20" bgcolor="#00CC66" style="border: 1px solid black;">&nbsp;</td>
    <td align="left">Trade in turbo</td>
  </tr>
  <tr>
    <td width="20" bgcolor="#FFCC33" style="border: 1px solid black;">&nbsp;</td>
    <td align="left">Trade with nitro hits remaining</td>
  </tr>
  <tr>
    <td width="20" bgcolor="#FFFF99" style="border: 1px solid black;">&nbsp;</td>
    <td align="left">Trade auto disabled</td>
  </tr>
</table>
<b>Normal:</b> &quot;ttt-out.php&quot;<br>
<b>To URL:</b> &quot;ttt-out.php?url=http://www.example.com&quot;<br>
<b>Skim:</b> &quot;ttt-out.php?pct=50&url=http://www.example.com&quot;<br>
<b>Internal Skim:</b> &quot;ttt-out.php?g=default&url=http://www.example.com&quot;<br>
<b>First Click:</b> &quot;ttt-out.php?f=1&pct=50&url=http://www.example.com&quot;<br>
<b>First 5 Clicks:</b> &quot;ttt-out.php?f=5&pct=50&url=http://www.example.com&quot;<br>
<b>To Trade:</b> &quot;ttt-out.php?trade=example.com&quot;<br>
<b>Track Link:</b> &quot;ttt-out.php?link=blah&quot;<br>
<b>SSI Code:</b> &lt;!--#include file=&quot;ttt-in.php&quot; --&gt;<br>
Insert code between &lt;head&gt; and &lt;/head&gt;<br><br>
    </td>
  </tr>
  <tr class="traderow"> 
  <td align=center colspan=2>
Internal Click/Prod Statistics: Internal statistics are the COUNTED clicks/prod (Using the Max Clicks Per Surfer Setting) for each trade, while the displayed statistics are the TOTAL clicks/prod (Not Using the Max Clicks Per Surfer Setting) for each trade.<br>
Trades Q and Return is calculated using the COUNTED clicks/prod.<br>
Remember: Trades that exceed the minimum or maximum productivty are auto disabled<br>
NB: You need to select a trade to use the menu buttons at the bottom.<br><br>
<font size="5"><a href="http://www.epowerscripts.com/?ref=TTT">UPGRADE YOUR SCRIPT NOW!</a></font></td>
  </tr>
</table>
<div align="center"><br>
  <br>
  Copyright &copy; 2003 Choker (Chokinchicken.com). All Rights Reserved.<br>
This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
If you upload this script then you do so knowing that any changes to this script that you make are in violation
of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!<br>
</div>
</body>
</html>