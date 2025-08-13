<?php
//#####################
// Turbo Traffic Trader Nitro v1.0
//#####################
// Copyright (c) 2003 TurboTrafficTrader (turbotraffictrader.com). All Rights Reserved.
// This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
// If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
// If you upload this script then you do so knowing that any changes to this script that you make are in violation
// of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!
//#####################
require("ttt-mysqlvalues.inc.php");
require("ttt-mysqlfunc.inc.php");

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
		$_POST["siteurl"] = my_addslashes($_POST["siteurl"]);
		$_POST["sitename"] = my_addslashes(htmlentities($_POST["sitename"]));
		$_POST["wm_email"] = my_addslashes($_POST["wm_email"]);
		$_POST["wm_icq"] = my_addslashes($_POST["wm_icq"]);
		$s = parse_url($_POST["siteurl"]);
		$_sitedomain = str_replace("www.","",$s["host"]);
		$_siteurl = "http://" . $s["host"] . $s["path"];
		if ($s["query"] != "") { $_siteurl .= "?" . $s["query"]; }
		if ($_POST["siteurl"] != $_siteurl OR $_sitedomain == "") {
			$msg[] = "$_POST[siteurl] is not valid url";
		}
		$findtrades = @implode("",@file("http://www.findtrades.com/ttt.php?d=$_sitedomain"));
                if ($use_blacklist == 1) {
                if ($findtrades == "1\n") { print_error("You are blacklisted by findtrades.com!!"); }
		}
		else {
                if ($findtrades == "1\n") { print_error("You are blacklisted by findtrades.com!!"); }
                elseif ($findtrades == "3\n") { print_error("You are blacklisted by findtrades.com!!"); }
		}
		if ($onlyfindtrades == 1) {
			$findtrades = @implode("",@file("http://www.findtrades.com/ttt.php?d=$_sitedomain"));
			if ($findtrades == "0\n") { $msg[] = "You are not in findtrades.com's database<br>You need to add your site before you can trade with this site<br>Go to <a href='http://www.findtrades.com/'>http://www.findtrades.com/</a>"; }
		}
	}
	$res = mysql_query("SELECT sitedomain FROM ttt_blacklist WHERE sitedomain='$_sitedomain' OR email='$_POST[wm_email]'") or print_error(mysql_error());
	if (mysql_num_rows($res) > 0) { print_error("You are blacklisted!!"); }
	
	$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE sitedomain='$_sitedomain'");
	if (mysql_num_rows($res) > 0) { $msg[] = "Site already exists in database"; }
	mysql_free_result($res);
	
	if (!isset($msg)) {
		mysql_query("INSERT INTO ttt_events (dato, timo, action, domain) VALUES (CURDATE(),CURTIME(),'4','$_sitedomain')") or print_error(mysql_error());
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
<title>Turbo Traffic Trader</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="ttt-style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<?php if ($tradeadded == 1) { ?>
<div align="center"><font size="4"><strong>Thank you for trading with <?php echo $sitename; ?><br>
  <br>
  Send To: <a href="<?php echo $siteurl; ?>" target="_blank"><?php echo $siteurl; ?></a></strong></font></div>
<?php } elseif (isset($msg)) { ?>
<div align="center"><font size="4"><strong>Error:<br>
<?php for ($i=0; $i<sizeof($msg); $i++) {	echo $msg[$i] . "<br>"; } ?>
</strong></font></div>
<?php } else { ?>
<table width="520" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="28" align="center" background="tttadmin/background.gif"><a href="http://www.turbotraffictrader.com/"><img src="tttadmin/ttt.gif" width="162" height="20" border="0"></a></td>
  </tr>
  <tr>
    <td height="55" class="normalrow">The Site is Powered By Turbo Traffic Trader<br>
      - a Free Version of ePower Trader - <a href="http://www.epowerscripts.com/?ref=TTT" target="_blank">Click 
      Here For More Info</a><br>
      - Download Your FREE Copy of TTT on <a href="http://www.turbotraffictrader.com/" target="_blank">http://www.turbotraffictrader.com/</a></td>
  </tr>
  <tr>
    <td class="normalrow"><iframe src="http://www.turbotraffictrader.com/iframe2.htm" width="520" marginwidth="0" height="240" marginheight="0" scrolling="auto" border"0" frameborder="0"></iframe></td>
  </tr>
  <tr>
    <td><form action="ttt-webmaster.php" method="POST"><table width="100%" border="1" cellspacing="0" cellpadding="2" class="normalrow" >
        <tr> 
          <td class="toprows" colspan="2"><font size="3">Rules</font></td>
        </tr>
        <tr> 
          <td colspan="2"><?php echo $rules; ?><br><br>
            <b><font size="3">Send To: <a href="<?php echo $siteurl; ?>"><?php echo $siteurl; ?></a><br><a href="http://www.findtrades.com/">Add Your Site To Findtrades.com's Database</a></font></b>
          </td>
        </tr>
<?php if ($webmasterpage != 2 AND $max_trades > $total_trades) { ?>
        <tr> 
          <td class="toprows" colspan="2"><font size="3">Important Notice</font></td>
        </tr>
        <tr align="center"> 
          <td width="520" colspan=2 align=center height=50 valign=middle><b>You must send at least <?php echo"$minimum_hits";?> hits before you recieve any traffic back.</b></td>
        </tr>
        <tr> 
          <td class="toprows" colspan="2"><font size="3">Signup</font></td>
        </tr>
        <tr align="left"> 
          <td width="170"><b>Site URL:</b></td>
          <td width="350"><input name="siteurl" type="text" id="siteurl" size="30" maxlength="150"></td>
        </tr>
        <tr align="left"> 
          <td width="170"><b>Site Name:</b></td>
          <td width="350"><input name="sitename" type="text" id="sitename" size="30" maxlength="100"></td>
        </tr>
        <tr align="left"> 
          <td width="170"><b>Your Email:</b></td>
          <td width="350"><input name="wm_email" type="text" id="wm_email" size="30" maxlength="100"></td>
        </tr>
        <tr align="left"> 
          <td width="170"><b>Your ICQ UIN:</b></td>
          <td width="350"><input name="wm_icq" type="text" id="wm_icq" size="30" maxlength="50"></td>
        </tr>
        <tr align="left"> 
          <td width="170">&nbsp;</td>
          <td width="350"><input name="addtrade" type="submit" class="buttons" value="Add Trade"></td>
        </tr>
<?php } else { ?>
        <tr> 
          <td class="toprows" colspan="2"><font size="3">Signup</font></td>
        </tr>
        <tr> 
          <td colspan="2"><font size="3"><br><br><b>Sorry, signup page disabled.<br>Contact webmaster directly.<br><br><br></b></font></td>
        </tr>
<?php } ?>
      </table></td>
  </tr>
</table>
</form>
<?php } ?>
</body>
</html>
