<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("../mysqlvalues.inc.php");
require("../mysqlfunc.inc.php");
open_conn();
require("security.inc.php");

if ($_POST["updatesettings"] != "") {
mysql_query("UPDATE ttt_settings SET sitename='$_POST[sitename]', siteurl='$_POST[siteurl]', admin_email='$_POST[admin_email]', admin_icq='$_POST[admin_icq]', default_ratio='$_POST[default_ratio]', default_min_prod='$_POST[default_min_prod]', default_max_prod='$_POST[default_max_prod]', webmasterpage='$_POST[webmasterpage]', rules='$_POST[rules]', altout='$_POST[altout]', minimum_hits=$_POST[minimum_hits], max_trades=$_POST[max_trades], use_blacklist=$_POST[use_blacklist]") or print_error(mysql_error());
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
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="../style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<?php if ($_GET["change"] == 1) { ?>
<form action="settings.php" method="POST">
<div align="center"><strong><font size="4"><?php echo $msg; ?></font></strong><br><br>
</div>
<table width="400" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td>
<table width="100%" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center">
        <tr> 
          <td background="background.gif" colspan="2" class="toprows">Change Username and Password</td>
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
<table width="400" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td>
<table border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center" class="normalrow">
        <tr class="toprows">
          <td background="background.gif" colspan="2">Site Info</td>
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
          <td background="background.gif" colspan="2">Your Info</td>
        </tr>
        <tr>
          <td align="left">Email:</td>
          <td align="left"><input name="admin_email" type="text" size="25" maxlength="100" value="<?php echo $admin_email; ?>"></td>
        </tr>
        <tr>
          <td align="left">ICQ:</td>
          <td align="left"><input name="admin_icq" type="text"  size="25" maxlength="20" value="<?php echo $admin_icq; ?>"></td>
        </tr>
        <tr class="toprows">
          <td background="background.gif" colspan="2">Default Trade Settings</td>
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
        <tr>
          <td align="left"><a title="A trade will be disabled if it has not sent 'Minimum Hits In' the last 24 hours">Minimum Hits In:</a></td>
          <td align="left"><input name="minimum_hits" type="text"  size="25" maxlength="6" value="<?php echo $minimum_hits; ?>"></td>
        </tr>
        <tr class="toprows">
          <td background="background.gif" colspan="2">Webmaster Page</td>
        </tr>
        <tr>
          <td align="left">Signup Page Status:</td>
          <td align="left"><select name="webmasterpage"><option value="1" <?php if ($webmasterpage == 1) { echo "selected"; } ?>>Enabled - Normal</option>
<option value="0" <?php if ($webmasterpage == 0) { echo "selected"; } ?>>Enabled - Disables new trades</option>
<option value="2" <?php if ($webmasterpage == 2) { echo "selected"; } ?>>Disabled - No new signups</option>
</td>
        </tr>
        <tr>
          <td align="left"><a title="Use Findtrades.com's blacklist">Use Findtrade.com:</a></td>
          <td align="left"><select name="use_blacklist"><option value="1" <?php if ($use_blacklist == 1) { echo "selected"; } ?>>Yes</option>
<option value="0" <?php if ($use_blacklist == 0) { echo "selected"; } ?>>No</option>
</td>
        </tr>
        <tr>
          <td align="left"><a title="When the max number of trades is hit, webmaster page will be disabled.">Max Trades:</a></td>
          <td align="left"><input name="max_trades" type="text"  size="25" maxlength="4" value="<?php echo $max_trades; ?>"></td>
        </tr>
        <tr>
          <td align="left">Rules:</td>
          <td align="left"><textarea cols="25" rows="5" name="rules"><?php echo $rules; ?></textarea></td>
        </tr>
        <tr>
          <td align="left">Alternative Out URL:</td>
          <td align="left"><input name="altout" type="text" size="25" maxlength="255" value="<?php echo $altout; ?>"></td>
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
