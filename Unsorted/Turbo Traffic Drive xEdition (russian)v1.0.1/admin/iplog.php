<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("../mysqlvalues.inc.php");
require("../mysqlfunc.inc.php");
open_conn();
require("security.inc.php");

if ($orderby == "") {
$orderby = "hits";
}


if (isset($_GET["id"]) AND $_GET["id"] == "") { print_error("Please select a trade first"); }

$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$_GET[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);

$res = mysql_query("SELECT ip, hits, use_proxy FROM ttt_iplog WHERE trade_id=$_GET[id] ORDER BY $orderby DESC LIMIT 0,150") or print_error(mysql_error());

close_conn();
$msg = "IP Log - $sitedomain";


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="../style.css" rel="stylesheet" type="text/css">

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
    <td colspan="4" align="center"><font size="1"><b><font size="2">Top 150 Incoming IPs</font></b></font></td>
  </tr>
  <tr> 
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" colspan="4"><font size="1">

<table width="250" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4">
<tr class="toprows"> 
<td background="background.gif"><b><a target=_top href="?orderby=hits&id=<? echo $id ?>" title="Sort By Hits">Hits</a></b></td>
<td background="background.gif" width="100%"><b><a target=_top href="?orderby=ip&id=<? echo $id ?>" title="Sort By IP">IP</a></b></td>
<td background="background.gif" width="100%"><b><a target=_top href="?orderby=use_proxy&id=<? echo $id ?>" title="Sort By Proxy">Proxy</a></b></td>
<td background="background.gif"><b>Whois</b></td>
</tr>
<?php
while ($row = mysql_fetch_array($res)) {
	extract ($row);
	echo "
<tr class=\"normalrow\"> 
<td>$hits</td>
<td width=\"100%\" align=\"left\">$ip</td>
<td width=\"100%\"><img border=\"0\"  alt=\"Red: Use Proxy | Green: No Proxy\" src=\"$use_proxy.gif\"></td>
<td><a href=\"#\" onClick=\"window.open('https://www.nic.ru/whois/?ip=$ip'); return false\">[whois]</a></td>
</tr>
";
}
?>
</table>
      </font></td>
  </tr>
  <tr> 
    <td colspan="4" align="center"><font size="2"><a href="javascript:window.close()">Close Window</a></font></td>
  </tr>
</table>
</body>
</html>
