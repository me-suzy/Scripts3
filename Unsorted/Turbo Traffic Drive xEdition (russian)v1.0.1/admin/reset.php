<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("../mysqlvalues.inc.php");
require("../mysqlfunc.inc.php");
open_conn();
require("security.inc.php");

if (isset($_GET["id"]) AND $_GET["id"] == "") { print_error("Please select a trade first"); }
if ($_POST["reset"] != "") {
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$_POST[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
mysql_query("DELETE FROM ttt_stats WHERE trade_id=$_POST[id]") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_stats (trade_id) VALUES ($_POST[id])") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_iplog WHERE trade_id=$_POST[id]") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_referers WHERE trade_id=$_POST[id]") or print_error(mysql_error());
$msg = "$sitedomain reseted";
}
else {
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$_GET[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$msg = "Are you sure you want to reset $sitedomain?";
}
close_conn();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="../style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<?php
if ($_POST["reset"] != "") {
?>
<div align="center"><strong><font size="4"><?php echo $msg; ?><br></font></strong>
<br><br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>
</div>
<?php
}
else {
?>
<form action="reset.php" method="POST">
<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
<div align="center"><strong><font size="4">Are you sure you want to reset <?php echo $sitedomain; ?>?<br>
  <input name="reset" type="submit" value="Yes, Reset It" class="buttons">&nbsp;&nbsp;
  <input name="goback" type="button" value="No, Go Back" onclick="window.close()" class="buttons">
  </font></strong> </div>
</form>
<?php } ?>
</body>
</html>
