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
if ($_POST["addnitro"] != "") {
$line=explode('|',$_POST[trade_id]);
if (count($line) > 1) { $sitedomain="Multiple sites"; $max=count($line); }
else {
$max=count($line);
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id='$line[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
}
for ($i=0; $i<$max; $i++) {
mysql_query("UPDATE ttt_forces SET nht=$_POST[nht] WHERE trade_id='$line[$i]'") or print_error(mysql_error());
}
$msg = "Nitro has been added to $sitedomain"; 
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
$msg = "Add nitro to $sitedomain"; 
}
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
<form action="nitro.php" method="POST">
<div align="center"><b><font size="4"><?php echo $msg; ?><br>
<input type="hidden" name="trade_id" value="<?php echo $trade_ids; ?>">
  <font size=2>Nitro Hits: <input type=text name=nht value=<?php echo "$nhr"; ?>>
  <input name="addnitro" type="submit" value="Add Nitro" class="buttons">
  </font></b>
<br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>  
  </div>
</form>
</body>
</html>