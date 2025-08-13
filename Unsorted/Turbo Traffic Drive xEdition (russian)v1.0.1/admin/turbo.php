<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("../mysqlvalues.inc.php");
require("../mysqlfunc.inc.php");
open_conn();
require("security.inc.php");

if (isset($_GET["id"]) AND $_GET["id"] == "") { print_error("Please select a trade first"); }
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
mysql_query("UPDATE ttt_trades SET turbo=1 WHERE trade_id=$_POST[id]") or print_error(mysql_error());
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$_POST[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
$msg = "$row[0] Turbo Charged";
$_GET["mode"] = "blah";
$refresh = 1;
}
elseif ($_POST["deleteturbo"] != "") {
mysql_query("UPDATE ttt_trades SET turbo=0 WHERE trade_id=$_POST[id]") or print_error(mysql_error());
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$_POST[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
$msg = "$row[0] Removed From Turbo Charge";
$_GET["mode"] = "blah";
$refresh = 1;
}
elseif ($_GET["id"] != "") {
$res = mysql_query("SELECT sitedomain, turbo FROM ttt_trades WHERE trade_id=$_GET[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
if ($row[1] == 1) { $msg = "Remove Turbo Charge From &quot;$row[0]&quot;"; }
else { $msg = "Turbo Charge &quot;$row[0]&quot;"; }
}
else { $msg = "Turbo Mode"; }
close_conn();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="../style.css" rel="stylesheet" type="text/css">
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
<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
<?php if ($turbo == 0) { ?>
  <input name="addturbo" type="submit" value="Yes, Turbo Charge It" class="buttons"><br>
<?php } else { ?>  
  <input name="deleteturbo" type="submit" value="Yes, Remove Turbo Charge" class="buttons"><br>
  <?php } ?>
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