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

if ($_POST["updatesettings"] != "") {
mysql_query("UPDATE ttt_settings SET sitename='$_POST[sitename]', siteurl='$_POST[siteurl]', admin_email='$_POST[admin_email]', admin_icq='$_POST[admin_icq]', default_ratio='$_POST[default_ratio]', default_min_prod='$_POST[default_min_prod]', default_max_prod='$_POST[default_max_prod]', webmasterpage='$_POST[webmasterpage]', rules='$_POST[rules]', altout='$_POST[altout]', minimum_hits=$_POST[minimum_hits], delete_mintrades=$_POST[delete_mintrades], max_trades=$_POST[max_trades], use_blacklist=$_POST[use_blacklist], onlyfindtrades=$_POST[onlyfindtrades], blockheads=$_POST[blockheads], iplog=$_POST[iplog], iplogreset=$_POST[iplogreset], blocknocookies=$_POST[blocknocookies], nocookieout='$_POST[nocookieout]', max_clicks='$_POST[max_clicks]'") or print_error(mysql_error());
$msg = "Settings Updated";
}
elseif ($_POST["changepass"] != "") {
	if ($_POST["password"] != $_POST["password2"]) { $_GET["change"] = 1; $msg = "Passwords do not match"; }
	else {
		mysql_query("UPDATE ttt_settings SET username='$_POST[username]', password=PASSWORD('$_POST[password]')") or print_error(mysql_error());
		setcookie("ttt_admin", time() . "|$_POST[username]|$_POST[password]", time()+99999);
		$msg = "Username and password updated";
	}
}
elseif ($_GET["change"] == 1) { $msg = "Change Username and Password"; }
else { $msg = "Settings"; }
$res = mysql_query("SELECT * FROM ttt_settings");
$row = mysql_fetch_array($res);
extract($row);
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
<?php if ($_GET["change"] == 1) { ?>
<form action="settings.php" method="POST">
<div align="center"><strong><font size="4"><?php echo $msg; ?></font></strong><br><br>
</div>
<table width="350" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr> 
          <td colspan="2" class="toprows">Change Username and Password</td>
        </tr>
        <tr class="normalrow"> 
          <td align="left">Username:</td>
          <td align="left"> <input name="username" type="text" id="username" size="30" maxlength="50" value="<?php echo $username; ?>"> 
          </td>
        </tr>
        <tr class="normalrow"> 
          <td align="left">Password:</td>
          <td align="left"> <input name="password" type="password" id="password" size="30" maxlength="50"> 
          </td>
        </tr>
        <tr class="normalrow"> 
          <td align="left">Password Again:</td>
          <td align="left"> <input name="password2" type="password" id="password2" size="30" maxlength="50"> 
          </td>
        </tr>
        <tr class="normalrow"> 
          <td align="left">&nbsp;</td>
          <td align="left"><input name="changepass" type="submit" class="buttons" id="changepass" value="Change"></td>
        </tr>
      </table></td>
  </tr>
</table>
</form>
<p align="center"><font size="2"><a href="javascript:window.close()">Close Window</a></font></p>
<?php }
elseif ($_POST["changepass"] != "") { ?>
<div align="center"><strong><font size="4"><?php echo $msg; ?></font></strong><br>
<br><br><br><br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>
</div>
<?php } else { ?>
<form action="settings.php" method="POST">
<div align="center"><strong><font size="4"><?php echo $msg; ?><br>
  <br>
  </font></strong> </div>
<table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellpadding="2" cellspacing="0" class="normalrow">
        <tr class="toprows">
          <td colspan="2">Site Info</td>
        </tr>
        <tr>
          <td align="left">Site URL:</td>
          <td align="left"><input name="siteurl" type="text" size="25" maxlength="100" value="<?php echo $siteurl; ?>"></td>
        </tr>
        <tr>
          <td align="left">Site Name:</td>
          <td align="left"><input name="sitename" type="text" size="25" maxlength="100" value="<?php echo $sitename; ?>"></td>
        </tr>
        <tr class="toprows">
          <td colspan="2">Your Info</td>
        </tr>
        <tr>
          <td align="left">Your Email:</td>
          <td align="left"><input name="admin_email" type="text" size="25" maxlength="100" value="<?php echo $admin_email; ?>"></td>
        </tr>
        <tr>
          <td align="left">Your ICQ UIN:</td>
          <td align="left"><input name="admin_icq" type="text"  size="25" maxlength="20" value="<?php echo $admin_icq; ?>"></td>
        </tr>
        <tr class="toprows">
          <td colspan="2">Default Trade Settings</td>
        </tr>
        <tr>
          <td align="left">Default Ratio:</td>
          <td align="left"><input name="default_ratio" type="text" size="25" maxlength="5" value="<?php echo $default_ratio; ?>"></td>
        </tr>
        <tr>
          <td align="left">Default Max Prod:</td>
          <td align="left"><input name="default_max_prod" type="text" size="25" maxlength="5" value="<?php echo $default_max_prod; ?>"></td>
        </tr>
        <tr>
          <td align="left">Default Min Prod:</td>
          <td align="left"><input name="default_min_prod" type="text" size="25" maxlength="5" value="<?php echo $default_min_prod; ?>"></td>
        </tr>
       <tr class="toprows">
          <td colspan="2">IP Log Settings</td>
        </tr>
        <tr>
          <td align="left"><a title="IP logs help you catch hitbots.">Ip Log:</a></td>
          <td align="left"><select name=iplog><option value='1' <?php if ($iplog == 1) { echo "selected"; } ?>>On</option><option value='0' <?php if ($iplog == 0) { echo "selected"; } ?>>Off</option></select></td>
        </tr>
        <tr>
          <td align="left"><a title="Larger sites may want their IP logs to reset hourly.">Ip Log Reset:</a></td>
          <td align="left"><select name=iplogreset><option value='1' <?php if ($iplogreset == 1) { echo "selected"; } ?>>Daily</option><option value='0' <?php if ($iplogreset == 0) { echo "selected"; } ?>>Hourly</option></select></td>
        </tr>
       <tr class="toprows">
          <td colspan="2">No Cookie Settings</td>
        </tr>
        <tr>
          <td align="left"><a title="Nocookie clicks are often hitbots or siteleeches.">Redirect No Cookie Clicks:</a></td>
          <td align="left"><select name=blocknocookies><option value='1' <?php if ($blocknocookies == 1) { echo "selected"; } ?>>Yes - Send to Nocookie URL</option><option value='2' <?php if ($blocknocookies == 2) { echo "selected"; } ?>>Yes - Send to Galleries</option><option value='0' <?php if ($blocknocookies == 0) { echo "selected"; } ?>>No</option></select></td>
        </tr>
        <tr>
          <td align="left">No Cookie URL:</td>
          <td align="left"><input name="nocookieout" type="text" size="25" maxlength="255" value="<?php echo $nocookieout; ?>"></td>
        </tr>
        <tr class="toprows">
          <td colspan="2">FindTrades.com Settings</td>
        </tr>
        <tr>
          <td align="left"><a title="Allow trades with popups?">Allow Findtrades.com blacklisted trades with popups?</a></td>
          <td align="left"><select name="use_blacklist"><option value="1" <?php if ($use_blacklist == 1) { echo "selected"; } ?>>Yes</option>
<option value="0" <?php if ($use_blacklist == 0) { echo "selected"; } ?>>No</option></select>
</td>
        </tr>
        <tr>
          <td align="left"><a title="Only allow trades in Findtrades.com database to signup">Only Allow Findtrades.com: Approved Trades</a></td>
          <td align="left"><select name="onlyfindtrades"><option value="1" <?php if ($onlyfindtrades == 1) { echo "selected"; } ?>>Yes</option>
<option value="0" <?php if ($onlyfindtrades == 0) { echo "selected"; } ?>>No</option>
</td>
        </tr>
        <tr class="toprows">
          <td colspan="2">Minimum Hits Settings</td>
        </tr>
        <tr>
          <td align="left"><a title="A trade will not recieve any traffic and will be disabled if it has not sent 'Minimum Hits In' the last 24 hours">Minimum Hits In To Recieve Traffic And Avoid Disable/Delete:</a></td>
          <td align="left"><input name="minimum_hits" type="text"  size="25" maxlength="6" value="<?php echo $minimum_hits; ?>"></td>
        </tr>
        <tr>
          <td align="left"><a title="Trades with less than the set minimum hits">Trades With &lt;Min. Hits:</a></td>
          <td align="left"><select name="delete_mintrades"><option value="1" <?php if ($delete_mintrades == 1) { echo "selected"; } ?>>Delete Them</option>
<option value="0" <?php if ($delete_mintrades == 0) { echo "selected"; } ?>>Disable Them</option>
</td>
        </tr>
        <tr class="toprows">
          <td colspan="2">Cheat Protection</td>
        </tr>
        <tr>
          <td align="left"><a title="HEAD requests are often hitbots. It can also be search engine spiders.">Block HEAD Requests</a></td>
          <td align="left"><select name="blockheads"><option value="1" <?php if ($blockheads == 1) { echo "selected"; } ?>>Yes</option>
<option value="0" <?php if ($blockheads == 0) { echo "selected"; } ?>>No</option>
</td>
        </tr>
        <tr>
          <td align="left"><a title="The maximum number of clicks counted for each surfer.">Max Clicks Per Surfer:</a></td>
          <td align="left"><input name="max_clicks" type="text"  size="25" maxlength="4" value="<?php echo $max_clicks; ?>"></td>
        </tr>
        <tr class="toprows">
          <td colspan="2">Other Settings</td>
        </tr>
        <tr>
          <td align="left"><a title="When the max number of trades is hit, webmaster page will be disabled.">Max Trades:</a></td>
          <td align="left"><input name="max_trades" type="text"  size="25" maxlength="4" value="<?php echo $max_trades; ?>"></td>
        </tr>
        <tr>
          <td align="left">Alternative Out URL:</td>
          <td align="left"><input name="altout" type="text" size="25" maxlength="255" value="<?php echo $altout; ?>"></td>
        </tr>
        <tr class="toprows">
          <td colspan="2">Webmaster Page</td>
        </tr>
        <tr>
          <td align="left">Signup Page Status:</td>
          <td align="left"><select name="webmasterpage"><option value="1" <?php if ($webmasterpage == 1) { echo "selected"; } ?>>Enabled - Normal</option>
<option value="0" <?php if ($webmasterpage == 0) { echo "selected"; } ?>>Enabled - Disables new trades</option>
<option value="2" <?php if ($webmasterpage == 2) { echo "selected"; } ?>>Disabled - No new signups</option>
</td>
        </tr>
        <tr>
          <td align="left">Rules:</td>
          <td align="left"><textarea cols="25" rows="5" name="rules"><?php echo $rules; ?></textarea></td>
        </tr>
        <tr>
          <td align="left">&nbsp;</td>
          <td align="left"><input name="updatesettings" type="submit" class="buttons" value="Update Settings"></td>
        </tr>
      </table></td>
  </tr>
</table>
<p align="center"><font size="2"><a href="javascript:window.close()">Close Window</a></font></p>
</form>
<?php } ?>
</body>
</html>
