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
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$_GET[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
mysql_free_result($res);
$res = mysql_query("SELECT referer, hits FROM ttt_referers WHERE trade_id=$_GET[id] ORDER BY hits DESC LIMIT 100") or print_error(mysql_error());
close_conn();
$msg = "Ref Log - $sitedomain";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<table border="0" cellspacing="0" cellpadding="1" align="center">
  <tr> 
    <td colspan="4" align="center"><strong><font size="4"><?php echo $msg; ?></font></strong></td>
  </tr>
  <tr> 
    <td height="10" colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4"><font size="1"><strong><font size="2">Top 100 Refering URLs</font></strong></font></td>
  </tr>
  <tr> 
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4"><font size="1">
<?php
while ($row = mysql_fetch_array($res)) {
	extract ($row);
	$url = $referer;
	if (strlen($referer) > 70) { $referer = substr($referer, 0, 70) . "..."; }
	echo "$hits - <a href=\"$url\" title=\"$url\" target=\"_blank\">$referer</a><br>";
}
mysql_free_result($res);
?>
      </font></td>
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
