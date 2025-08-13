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

if ($_POST["enableturbo"] != "" OR $_GET["mode"] == "on") {
mysql_query("UPDATE ttt_settings SET turbomode=1") or print_error(mysql_error());
$msg = "Turbo Mode Activated";
$refresh = 1;
}
elseif ($_POST["disableturbo"] != "" OR $_GET["mode"] == "off") {
mysql_query("UPDATE ttt_settings SET turbomode=0") or print_error(mysql_error());
$msg = "Turbo Mode Deactivated";
$refresh = 1;
}
elseif ($_POST["addturbo"] != "") {
$line=explode('|',$_POST[trade_id]);
if (count($line) > 1) { $sitedomain="Multiple sites"; $max=count($line); }
else {
$max=count($line);
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id='$line[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
}
if ($_POST["turbo_max"] == "0") {
$reset_hour="99"; 
}
else {
$temphour=$thishour+$_POST["turbo_max"];
if ($temphour > 23) {
$reset_hour=($temphour-24);
}
else {
$reset_hour=$temphour;
}
}
for ($i=0; $i<$max; $i++) {
mysql_query("UPDATE ttt_trades SET turbo=1,turbo_max='$reset_hour' WHERE trade_id='$line[$i]'") or print_error(mysql_error());
}
$msg = "$sitedomain turbo charged"; 
$_GET["mode"] = "blah";
$refresh=1;
}
elseif ($_POST["deleteturbo"] != "") {
$line=explode('|',$_POST[trade_id]);
if (count($line) > 1) { $sitedomain="Multiple sites"; $max=count($line); }
else {
$max=count($line);
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id='$line[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
}
for ($i=0; $i<$max; $i++) {
mysql_query("UPDATE ttt_trades SET turbo=0,turbo_max='0' WHERE trade_id=$line[$i]") or print_error(mysql_error());
}
$msg = "$sitedomain removed from turbo charge"; 
$_GET["mode"] = "blah";
$refresh=1;
}
elseif ($_GET["id"] != "") {
$line=explode('|',$_GET[id]);
if (count($line) > 1) { $sitedomain="Multiple sites"; $max=count($line); }
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
$msg = "$sitedomain"; 
for ($i=0; $i<24; $i++) {
if ($i == 0) { $select_output.="<option value='$i'>All hours</option>"; }
else { $select_output.="<option value='$i'>$i hours</option>"; }
}
}
else { $msg = "Turbo Mode"; }
close_conn();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
<?php if ($refresh == 1) { ?>
<script>
window.opener.location.reload();
</script>
<?php } ?>
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<?php if ($_GET["mode"] != "") { ?>

<div align="center"><b><font size="4"><?php echo $msg; ?><br><br></font></b>
<br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>  
  </div>
<?php } elseif ($_GET["id"] != "" OR $_POST["id"] != "") { ?>
<form action="turbo.php" method="POST">
<div align="center"><b><font size="4"><?php echo $msg; ?><br>
<input type="hidden" name="trade_id" value="<?php echo $trade_ids; ?>">
  <font size=2>Turbo Hours</font> <select name=turbo_max><?php echo"$select_output";?></select><br><br>
  <input name="addturbo" type="submit" value="Activate Turbo Mode" class="buttons"><br>
  <input name="deleteturbo" type="submit" value="Deactivate Turbo Mode" class="buttons">
  </font></b>
<br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>  
  </div>
</form>
<?php } else { ?>
<form action="turbo.php" method="POST">
<div align="center"><strong><font size="4"><?php echo $msg; ?><br>
  <input name="enableturbo" type="submit" value="Activate Turbo Mode" class="buttons"><br>
  <input name="disableturbo" type="submit" value="Deactivate Turbo Mode" class="buttons">
  </font></strong>
<br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>  
  </div>
</form>
<?php } ?>
</body>
</html>