<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("mysqlvalues.inc.php");
require("mysqlfunc.inc.php");

open_conn();
$tradeadded = 0;

$res = mysql_query("SELECT * FROM ttt_settings") or print_error(mysql_error());
$row = mysql_fetch_array($res);
mysql_free_result($res);
extract($row);

$res = mysql_query("SELECT COUNT(*) FROM ttt_trades") or print_error(mysql_error());
$row = mysql_fetch_array($res);
$total_trades = $row[0]-3;
mysql_free_result($res);


if ($_POST["addtrade"] != "" AND $webmasterpage != 2 AND $max_trades > $total_trades) {
	if ($_POST["siteurl"] == "") { $msg[] = "Enter a Site URL"; }
	if ($_POST["wm_email"] == "") { $msg[] = "Enter an email address"; }
	else {
		$s = parse_url($_POST["siteurl"]);
		$_sitedomain = str_replace("www.","",$s["host"]);
		$_siteurl = "http://" . $s["host"] . $s["path"];
		if ($s["query"] != "") { $_siteurl .= "?" . $s["query"]; }
		if ($_POST["siteurl"] != $_siteurl OR $_sitedomain == "") {
			$msg[] = "$_POST[siteurl] is not valid url";
		}
		if ($use_blacklist == 1) {
			$findtrades = implode("",@file("http://www.findtrades.com/ttt.php?d=$_sitedomain"));
			if ($findtrades == "1") { print_error("You are blacklisted by findtrades.com!!"); }
		}
	}
	$res = mysql_query("SELECT sitedomain FROM ttt_blacklist WHERE sitedomain='$_sitedomain' OR email='$_POST[wm_email]'") or print_error(mysql_error());
	if (mysql_num_rows($res) > 0) { print_error("You are blacklisted!!"); }
	
	$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE sitedomain='$_sitedomain'");
	if (mysql_num_rows($res) > 0) { $msg[] = "Site already exists in database"; }
	mysql_free_result($res);
	
	if (!isset($msg)) {
		mysql_query("INSERT INTO ttt_trades (sitedomain, siteurl, sitename, wm_email, wm_icq, ratio, min_prod, max_prod, enabled) VALUES ('$_sitedomain', '$_POST[siteurl]', '$_POST[sitename]', '$_POST[wm_email]', '$_POST[wm_icq]', '$default_ratio', '$default_min_prod', '$default_max_prod', '$webmasterpage')") or print_error(mysql_error());
		mysql_query("INSERT INTO ttt_stats (trade_id) VALUES (LAST_INSERT_ID())") or print_error(mysql_error());
		mysql_query("INSERT INTO ttt_forces (trade_id) VALUES (LAST_INSERT_ID())") or print_error(mysql_error());
		$tradeadded = 1;
	}
}
close_conn();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registration</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<?php if ($tradeadded == 1) { ?>
<div align="center"><font size="4"><strong>Thank you for trading with <?php echo $sitename; ?></strong><br>
  <br>
  Send All Hits To:<br><br><strong><?php echo $siteurl; ?><br><br></strong></font></div>
<?php } elseif (isset($msg)) { ?>
<div align="center"><font size="4"><strong>Error:<br>
<?php for ($i=0; $i<sizeof($msg); $i++) {	echo $msg[$i] . "<br>"; } ?>
</strong></font></div>
<?php } else { ?>
<table border="0" cellpadding="0" cellspacing="0" align="center">

  <tr>
    <td><form action="webmaster.php" method="POST">
<table border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center" class="normalrow">
        <tr> 
          <td background="admin/background.gif" class="toprows" colspan="2">Rules</td>
        </tr>
        <tr> 
          <td align="left" colspan="2"><?php echo $rules; ?><br><br>
            <font size="2"><center>send all hits to: <b><?php echo $siteurl; ?><center></font></b>
          </td>
        </tr>
        <tr> 
          <td background="admin/background.gif" class="toprows" colspan="2">Signup</td>
        </tr>
<?php if ($webmasterpage != 2 AND $max_trades > $total_trades) { ?>
        <tr align="left"> 
          <td><b>Site URL:</b></td>
          <td><input name="siteurl" type="text" id="siteurl" size="30" maxlength="150"></td>
        </tr>
        <tr align="left"> 
          <td><b>Site Name:</b></td>
          <td ><input name="sitename" type="text" id="sitename" size="30" maxlength="100"></td>
        </tr>
        <tr align="left"> 
          <td><b>Your Email:</b></td>
          <td><input name="wm_email" type="text" id="wm_email" size="30" maxlength="100"></td>
        </tr>
        <tr align="left"> 
          <td><b>Your ICQ UIN:</b></td>
          <td><input name="wm_icq" type="text" id="wm_icq" size="30" maxlength="50"></td>
        </tr>
        <tr align="left"> 
          <td>&nbsp;</td>
          <td><input name="addtrade" type="submit" class="buttons" value="Add Trade"></td>
        </tr>
<?php } else { ?>
        <tr> 
          <td colspan="2"><font size="3"><br><br><b>Sorry, signup page disabled.<br>Contact webmaster directly.<br><br><br></b></font></td>
        </tr>
<?php } ?>
      </table></td>
  </tr>
</table>
</form>
<?php } ?>
<br>
<center>
<a target="_blank" href="http://www.x-forum.info/showthread.php?s=&threadid=1383">Powered by Turbo Traffic Drive xEdition v1.0.1</a>
</center>
</body>
</html>
