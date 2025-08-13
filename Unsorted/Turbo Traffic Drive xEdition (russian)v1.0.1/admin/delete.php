<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("../mysqlvalues.inc.php");
require("../mysqlfunc.inc.php");
open_conn();
require("security.inc.php");

if (isset($_GET["id"]) AND $_GET["id"] == "") { print_error("Please select a trade first"); }
if ($_POST["delete"] != "" OR $_POST["blacklist"] != "") {
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$_POST[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$msg = "$sitedomain deleted";
	if ($_POST["blacklist"] != "") {
		$res = mysql_query("SELECT sitedomain, wm_email, wm_icq FROM ttt_trades WHERE trade_id=$_POST[id]") or print_error(mysql_error());
		$row = mysql_fetch_array($res);
		mysql_query("INSERT INTO ttt_blacklist (sitedomain, email, icq) VALUES ('$row[0]', '$row[1]', '$row[2]')") or print_error(mysql_error());
		$msg = "$sitedomain deleted and blacklisted";
	}
if ($_POST["id"] != 1 AND $_POST["id"] != 2 AND $_POST["id"] != 3) {
mysql_query("DELETE FROM ttt_trades WHERE trade_id=$_POST[id]") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_stats WHERE trade_id=$_POST[id]") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_forces WHERE trade_id=$_POST[id]") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_iplog WHERE trade_id=$_POST[id]") or print_error(mysql_error());
mysql_query("DELETE FROM ttt_referers WHERE trade_id=$_POST[id]") or print_error(mysql_error());
}
else { $msg = "Can not delete $sitedomain"; }
}
else {
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$_GET[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$msg = "Are you sure you want to delete $sitedomain?";
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
<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
<div align="center"><strong><font size="4">Are you sure you want to delete <?php echo $sitedomain; ?>?<br>
  <input name="delete" type="submit" value="Yes, Delete It" class="buttons">&nbsp;&nbsp;
  <input name="goback" type="button" value="No, Go Back" onclick="window.close()" class="buttons"><br><br>
  <input name="blacklist" type="submit" value="Yes, Delete And Blacklist" class="buttons">
  </font></strong> </div>
</form>
<?php } ?>
</body>
</html>
