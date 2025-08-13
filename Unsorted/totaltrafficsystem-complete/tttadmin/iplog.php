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

if (isset($_GET["id"]) AND $_GET["id"] == "") { print_error("Please select a trade first"); }
$line = explode('|',$_GET["id"]);
if (count($line) > 1) { print_error("Please select only one trade"); }
$_GET["id"]=$line[0];
if ($_GET["method"] == "") { $_GET["method"]="Incoming"; }
if ($_GET["method"] == "Incoming") { $sortmethod="hits"; } else { $sortmethod="clicks"; }
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$_GET[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$res = mysql_query("SELECT ip, proxy, hits, clicks FROM ttt_iplog WHERE trade_id=$_GET[id] ORDER BY $sortmethod DESC LIMIT 0,150") or print_error(mysql_error());
$cnt1 = mysql_query("SELECT count(ip) AS total1 FROM ttt_iplog WHERE trade_id=$_GET[id]") or print_error(mysql_error());
$cnt2 = mysql_query("SELECT count(ip) AS total2 FROM ttt_iplog WHERE proxy='1' AND trade_id=$_GET[id]") or print_error(mysql_error());
close_conn();
$row = mysql_fetch_array($cnt1);
extract($row);
$row = mysql_fetch_array($cnt2);
extract($row);
$total_ips="$total1";
$total_proxies="$total2";
if ($total_ips == 0) { $total_pct="0.00"; } else { $total_pct=round(($total_proxies/$total_ips)*100,2); }
$msg = "IP Log - $sitedomain";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
<script>
function whois() {
	window.open("http://www.epowerscripts.com/ttt/info.php#whois", "ept", "width=400,height=500,scrollbars=1");
}
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<table width="300" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr> 
    <td colspan="4" align="center"><strong><font size="4"><?php echo $msg; ?></font></strong></td>
  </tr>
  <tr> 
    <td height="10" colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4"><font size="1"><b><font size="2">Top 150 <?php echo $_GET["method"]; ?> IPs</font></b><br><font size="2">Sort by: <b><a href='iplog.php?id=<?php echo "$_GET[id]"; ?>&method=Incoming'>Incoming</a> | <a href='iplog.php?id=<?php echo "$_GET[id]"; ?>&method=Outgoing'>Outgoing</a></b><br><br>Total IPs: <?php echo "$total_ips"; ?> | Proxy IPs: <?php echo "$total_proxies"; ?><br>Proxy %: <?php echo "$total_pct"; ?></font></td>
  </tr>
  <tr> 
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4">
	<table border=0 cellspacing=1 cellpadding=1 width=100%>
	<tr>
	<td align=left><font size="1">Incoming</font></td>
	<td align=left><font size="1">Outgoing</font></td>
	<td align=left colspan=2><font size="1">IP Address</font></td>
	</tr>	
<?php
while ($row = mysql_fetch_array($res)) {
	extract ($row);
	if ($proxy == 1) { $font_start = "<font color='red'>"; $font_end = "</font>"; }
	else if ($proxy == 2) { $font_start = "<font color='blue'>"; $font_end = "</font>"; }
	else { $font_start = "<font color='black'>"; $font_end = "</font>"; }
	echo "<tr><td align=left><font size=\"1\">$font_start$hits$font_end</font></td><td align=left><font size=\"1\">$font_start$clicks$font_end</font></td><td align=left><font size=\"1\">$font_start$ip$font_end</font></td><td align=left><font size=\"1\"><a href=\"javascript:whois()\"><img src='whois.gif' border='0'></a></font></td></tr>";
}
?>
	</table>
  </td>
  </tr>
  <tr> 
    <td colspan="4"><hr size="1" color="#000000">
      <font size="1"><strong>ePower Trader has both incoming and outgoing iplog, marks proxy IPs, has hourly stats for each IP. You will also get builtin &quotWhois&quot; and &quot;Proxychecker&quot;<br>
      </strong></font>
      <hr size="1" color="#000000">
      <font size="1"><strong> </strong></font></td>
  </tr>
  <tr> 
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4" align="center"><font size="2"><a href="javascript:window.close()">Close Window</a></font></td>
  </tr>
</table>
</body>
</html>
