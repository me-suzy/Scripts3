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

$res = mysql_query("SELECT * FROM ttt_settings") or print_error(mysql_error());
$row = mysql_fetch_array($res);
mysql_free_result($res);
extract($row);
if ($_POST["siteurl"] != "") {
	if ($_POST["siteurl"] == "") { $error[] = "Enter a Site URL"; }
	$s = parse_url($_POST["siteurl"]);
	$sitedomain = str_replace("www.","",$s["host"]);
	$siteurl = "http://" . $s["host"] . $s["path"];
	if ($s["query"] != "") { $siteurl .= "?" . $s["query"]; }
	if ($_POST["siteurl"] != $siteurl OR $sitedomain == "") { $error[] = "$siteurl is not valid url"; }
	$findtrades = @implode("",@file("http://www.findtrades.com/ttt.php?d=$sitedomain"));
	if ($use_blacklist == 1) {
	if ($findtrades == "1\n") { $error[] = "Trade is blacklisted by findtrades.com"; }
	}
	else {
	if ($findtrades == "1\n") { $error[] = "Trade is blacklisted by findtrades.com"; }
	if ($findtrades == "3\n") { $error[] = "Trade is blacklisted by findtrades.com"; }
	}	

	$res = mysql_query("SELECT sitedomain FROM ttt_blacklist WHERE sitedomain='$sitedomain'") or print_error(mysql_error());
	if (mysql_num_rows($res) > 0) { $error[] = "Trade is blacklisted!!"; }
	
	$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE sitedomain='$sitedomain'");
	if (mysql_num_rows($res) > 0) { $error[] = "Site already exists in database"; }
	if (!isset($error)) {
		mysql_query("INSERT INTO ttt_trades (sitedomain, siteurl, sitename, wm_email, wm_icq, enabled, ratio, min_prod, max_prod) VALUES ('$sitedomain', '$_POST[siteurl]', '$_POST[sitename]', '$_POST[wm_email]', '$_POST[wm_icq]', 1, '$_POST[ratio]', '$_POST[min_prod]', '$_POST[max_prod]')") or print_error(mysql_error());
		mysql_query("INSERT INTO ttt_stats (trade_id) VALUES (LAST_INSERT_ID())") or print_error(mysql_error());
		mysql_query("INSERT INTO ttt_forces (trade_id) VALUES (LAST_INSERT_ID())") or print_error(mysql_error());
		$msg = "$sitedomain added";
	}
}
else { $msg = "Add Trade"; }
close_conn();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<?php if (isset($error)) { ?>
<div align="center"><font size="4"><b>Error:<br>
<?php for ($i=0; $i<sizeof($error); $i++) {	echo $error[$i] . "<br>"; } ?>
</b></font></div>
<?php } else { ?>
<form action="addtrade.php" method="POST">
<div align="center"><strong><font size="4"><?php echo $msg; ?><br>
  <br>
  </font></strong> </div>
<table width="300" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellpadding="2" cellspacing="0" class="normalrow">
        <tr class="toprows"> 
          <td colspan="2">Site Info</td>
        </tr>
        <tr> 
          <td align="left">Site URL:</td>
          <td align="left"><input name="siteurl" type="text" id="siteurl" size="25" maxlength="100"></td>
        </tr>
        <tr> 
          <td align="left">Site Name:</td>
          <td align="left"><input name="sitename" type="text" id="sitename" size="25" maxlength="100"></td>
        </tr>
        <tr class="toprows"> 
          <td colspan="2">Webmaster Info</td>
        </tr>
        <tr> 
          <td align="left">Email:</td>
          <td align="left"><input name="wm_email" type="text" id="email" size="25" maxlength="100"></td>
        </tr>
        <tr> 
          <td align="left">ICQ:</td>
          <td align="left"><input name="wm_icq" type="text" id="icq" size="25" maxlength="20"></td>
        </tr>
        <tr class="toprows"> 
          <td colspan="2">Trade Settings</td>
        </tr>
        <tr> 
          <td align="left">Trade Raio:</td>
          <td align="left"><input name="ratio" type="text" id="ratio" size="25" maxlength="20" value="<?php echo"$default_ratio";?>"></td>
        </tr>
        <tr> 
          <td align="left">Minimum Prod:</td>
          <td align="left"><input name="min_prod" type="text" id="min_prod" size="25" maxlength="20" value="<?php echo"$default_min_prod";?>"></td>
        </tr>
        <tr> 
          <td align="left">Maximum Prod:</td>
          <td align="left"><input name="max_prod" type="text" id="max_prod" size="25" maxlength="20" value="<?php echo"$default_max_prod";?>"></td>
        </tr>
        <tr> 
          <td align="left">&nbsp;</td>
          <td align="left"><input name="Submit" type="submit" class="buttons" value="Add Trade"></td>
        </tr>
      </table></td>
  </tr>
</table>
<div align="center">
<br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>
</form>
</div>
<?php } ?>
</body>
</html>
