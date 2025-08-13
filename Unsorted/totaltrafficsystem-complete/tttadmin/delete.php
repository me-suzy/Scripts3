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
if ($_POST["delete"] != "" OR $_POST["blacklist"] != "") {
$line=explode('|',$_POST[trade_id]);
if (count($line) > 1) { $_sitedomain="Multiple sites"; $max=count($line); }
else {
$max=count($line);
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id='$line[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$_sitedomain=$sitedomain;
}
$msg = "$_sitedomain deleted";
for ($i=0; $i<$max; $i++) {
	if ($_POST["blacklist"] != "") {
		$res = mysql_query("SELECT sitedomain, wm_email, wm_icq FROM ttt_trades WHERE trade_id=$line[$i]") or print_error(mysql_error());
		$row = mysql_fetch_array($res);
		mysql_query("INSERT INTO ttt_blacklist (sitedomain, email, icq) VALUES ('$row[0]', '$row[1]', '$row[2]')") or print_error(mysql_error());
		$msg = "$_sitedomain deleted and blacklisted";
	}
}
for ($i=0; $i<$max; $i++) {
if ($line[$i] != 1 AND $line[$i] != 2 AND $line[$i] != 3) {
mysql_query("DELETE FROM ttt_trades WHERE trade_id=$line[$i]") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_stats WHERE trade_id=$line[$i]") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_forces WHERE trade_id=$line[$i]") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_iplog WHERE trade_id=$line[$i]") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_referers WHERE trade_id=$line[$i]") or print_error(mysql_error());
}
}
}
else {
$line=explode('|',$_GET[id]);
if (count($line) > 1) { $sitedomain="multiple sites"; $max=count($line); }
else {
$max=count($line);
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id='$line[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
}
for ($i=0; $i<$max; $i++) {
$trade_ids.="$line[$i]|";
}
$trade_ids=substr($trade_ids,0,strlen($trade_ids)-1);
$msg = "Are you sure you want to delete $sitedomain?";
}
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
<?php
if ($_POST["delete"] != "" OR $_POST["blacklist"] != "") {
?>
<div align="center"><strong><font size="4"><?php echo $msg; ?><br></font></strong>
<br><br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>
</div>
<?php
}
else {
?>
<form action="delete.php" method="POST">
<input type="hidden" name="trade_id" value="<?php echo $trade_ids; ?>">
<div align="center"><strong><font size="4"><?php echo "$msg"; ?><br>
  <input name="delete" type="submit" value="Yes, Delete It" class="buttons">&nbsp;&nbsp;
  <input name="goback" type="button" value="No, Go Back" onclick="window.close()" class="buttons"><br><br>
  <input name="blacklist" type="submit" value="Yes, Delete And Blacklist" class="buttons">
  </font></strong> </div>
</form>
<?php } ?>
</body>
</html>
