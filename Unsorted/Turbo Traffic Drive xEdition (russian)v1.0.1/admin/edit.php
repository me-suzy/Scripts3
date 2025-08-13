<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("../mysqlvalues.inc.php");
require("../mysqlfunc.inc.php");
open_conn();
require("security.inc.php");

if (isset($_GET["id"]) AND $_GET["id"] == "") { print_error("Please select a trade first"); }
if ($_POST["updatetrade"] != "") {
$sitedomain = parse_url($_POST["siteurl"]);
$sitedomain = str_replace("www.","",$sitedomain["host"]);
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$_POST[trade_id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
if ($row[0] != $sitedomain) { $msg = "Site URL And Site Domain Does Not Match"; }
else {
mysql_query("UPDATE ttt_trades SET siteurl='$_POST[siteurl]', sitename='$_POST[sitename]', wm_email='$_POST[wm_email]', wm_icq='$_POST[wm_icq]', ratio='$_POST[ratio]', min_prod='$_POST[min_prod]', max_prod='$_POST[max_prod]', enabled='$_POST[enabled]', turbo='$_POST[turbo]' WHERE trade_id=$_POST[trade_id]") or print_error(mysql_error());
mysql_query("UPDATE ttt_forces SET fh0=$_POST[fh0], fh1=$_POST[fh1], fh2=$_POST[fh2], fh3=$_POST[fh3], fh4=$_POST[fh4], fh5=$_POST[fh5], fh6=$_POST[fh6], fh7=$_POST[fh7], fh8=$_POST[fh8], fh9=$_POST[fh9], fh10=$_POST[fh10], fh11=$_POST[fh11], fh12=$_POST[fh12], fh13=$_POST[fh13], fh14=$_POST[fh14], fh15=$_POST[fh15], fh16=$_POST[fh16], fh17=$_POST[fh17], fh18=$_POST[fh18], fh19=$_POST[fh19], fh20=$_POST[fh20], fh21=$_POST[fh21], fh22=$_POST[fh22], fh23=$_POST[fh23] WHERE trade_id=$_POST[trade_id]") or print_error(mysql_error());
$_GET["id"] = $_POST["trade_id"];
$msg = "$sitedomain Updated";
}
}
$res = mysql_query("SELECT * FROM ttt_trades, ttt_forces WHERE ttt_trades.trade_id=ttt_forces.trade_id AND ttt_trades.trade_id=$_GET[id]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
mysql_free_result($res);
extract($row);
if (!isset($msg)) { $msg = "Edit $sitedomain"; }
close_conn();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function allhours() {
this.form1.fh0.value=this.form1.allhour.value;
this.form1.fh1.value=this.form1.allhour.value;
this.form1.fh2.value=this.form1.allhour.value;
this.form1.fh3.value=this.form1.allhour.value;
this.form1.fh4.value=this.form1.allhour.value;
this.form1.fh5.value=this.form1.allhour.value;
this.form1.fh6.value=this.form1.allhour.value;
this.form1.fh7.value=this.form1.allhour.value;
this.form1.fh8.value=this.form1.allhour.value;
this.form1.fh9.value=this.form1.allhour.value;
this.form1.fh10.value=this.form1.allhour.value;
this.form1.fh11.value=this.form1.allhour.value;
this.form1.fh12.value=this.form1.allhour.value;
this.form1.fh13.value=this.form1.allhour.value;
this.form1.fh14.value=this.form1.allhour.value;
this.form1.fh15.value=this.form1.allhour.value;
this.form1.fh16.value=this.form1.allhour.value;
this.form1.fh17.value=this.form1.allhour.value;
this.form1.fh18.value=this.form1.allhour.value;
this.form1.fh19.value=this.form1.allhour.value;
this.form1.fh20.value=this.form1.allhour.value;
this.form1.fh21.value=this.form1.allhour.value;
this.form1.fh22.value=this.form1.allhour.value;
this.form1.fh23.value=this.form1.allhour.value;
}
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<form action="edit.php" method="POST" name="form1">
<input type="hidden" name="trade_id" value="<?php echo $trade_id; ?>">
<div align="center"><b><font size="4"><?php echo $msg; ?></font></b><br>
  <br>
</div>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
<table width="600" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center">
        <tr class="toprows"> 
          <td background="background.gif" colspan="4"><b>Webmaster &amp; Trade Info</b></td>
        </tr>
        <tr class="normalrow"> 
          <td width="25%">Site Name:</td>
          <td width="25%"><input name="sitename" type="text" id="sitename" size="20" maxlength="100" value="<?php echo $sitename; ?>"></td>
          <td width="25%">Webmaster Email:</td>
          <td width="25%"><input name="wm_email" type="text" id="wm_email" size="20" maxlength="100" value="<?php echo $wm_email; ?>"></td>
        </tr>
        <tr class="normalrow"> 
          <td>Site URL:</td>
          <td><input name="siteurl" type="text" id="siteurl" size="20" maxlength="150" value="<?php echo $siteurl; ?>"></td>
          <td>Webmaster ICQ:</td>
          <td><input name="wm_icq" type="text" id="wm_icq" size="20" maxlength="50" value="<?php echo $wm_icq; ?>"></td>
        </tr>
        <tr class="toprows"> 
          <td background="background.gif" colspan="4">Trade Settings</td>
        </tr>
        <tr align="left"> 
          <td class="normalrow">Trade Ratio:</td>
          <td><input name="ratio" type="text" id="ratio" size="20" maxlength="10" value="<?php echo $ratio; ?>"></td>
          <td class="normalrow">Status:</td>
          <td class="normalrow"><select name="enabled"><option value="1" <?php if ($enabled == 1) { echo "selected"; } ?>>Enabled</option>
<option value="0" <?php if ($enabled == 0) { echo "selected"; } ?>>Disabled</option>
<option value="2" <?php if ($enabled == 2) { echo "selected"; } ?>>Auto Disabled</option>
</td>
        </tr>
        <tr align="left"> 
          <td class="normalrow">Minimum Prod:</td>
          <td><input name="min_prod" type="text" id="min_prod" size="20" maxlength="10" value="<?php echo $min_prod; ?>"></td>
          <td class="normalrow">Include In Turbo:</td>
          <td class="normalrow"><select name="turbo"><option value="1" <?php if ($turbo == 1) { echo "selected"; } ?>>Yes</option>
<option value="0" <?php if ($turbo == 0) { echo "selected"; } ?>>No</option>
</td>
        </tr>
        <tr align="left">
          <td class="normalrow">Maximum Prod:</td>
          <td><input name="max_prod" type="text" id="max_prod" size="20" maxlength="10" value="<?php echo $max_prod; ?>"></td>
          <td class="normalrow">&nbsp;</td>
          <td class="normalrow">&nbsp;</td>
        </tr>
  <tr class="toprows">
    <td background="background.gif" colspan="4"> Force Hits:</td>
  </tr>
  <tr class="normalrow">
    <td align="left">Hour 00-01: <input type="text" size="5" name="fh0" value="<?php echo $fh0; ?>"></td>
    <td align="left">Hour 01-02: <input type="text" size="5" name="fh1" value="<?php echo $fh1; ?>"></td>
    <td align="left">Hour 02-03: <input type="text" size="5" name="fh2" value="<?php echo $fh2; ?>"></td>
    <td align="left">Hour 03-04: <input type="text" size="5" name="fh3" value="<?php echo $fh3; ?>"></td>
  </tr>
  <tr class="normalrow">
    <td align="left">Hour 04-05: <input type="text" size="5" name="fh4" value="<?php echo $fh4; ?>"></td>
    <td align="left">Hour 05-06: <input type="text" size="5" name="fh5" value="<?php echo $fh5; ?>"></td>
    <td align="left">Hour 06-07: <input type="text" size="5" name="fh6" value="<?php echo $fh6; ?>"></td>
    <td align="left">Hour 07-08: <input type="text" size="5" name="fh7" value="<?php echo $fh7; ?>"></td>
  </tr>
  <tr class="normalrow">
    <td align="left">Hour 08-09: <input type="text" size="5" name="fh8" value="<?php echo $fh8; ?>"></td>
    <td align="left">Hour 09-10: <input type="text" size="5" name="fh9" value="<?php echo $fh9; ?>"></td>
    <td align="left">Hour 10-11: <input type="text" size="5" name="fh10" value="<?php echo $fh10; ?>"></td>
    <td align="left">Hour 11-12: <input type="text" size="5" name="fh11" value="<?php echo $fh11; ?>"></td>
  </tr>
  <tr class="normalrow">
    <td align="left">Hour 12-13: <input type="text" size="5" name="fh12" value="<?php echo $fh12; ?>"></td>
    <td align="left">Hour 13-14: <input type="text" size="5" name="fh13" value="<?php echo $fh13; ?>"></td>
    <td align="left">Hour 14-15: <input type="text" size="5" name="fh14" value="<?php echo $fh14; ?>"></td>
    <td align="left">Hour 15-16: <input type="text" size="5" name="fh15" value="<?php echo $fh15; ?>"></td>
  </tr>
  <tr class="normalrow">
    <td align="left">Hour 16-17: <input type="text" size="5" name="fh16" value="<?php echo $fh16; ?>"></td>
    <td align="left">Hour 17-18: <input type="text" size="5" name="fh17" value="<?php echo $fh17; ?>"></td>
    <td align="left">Hour 18-19: <input type="text" size="5" name="fh18" value="<?php echo $fh18; ?>"></td>
    <td align="left">Hour 19-20: <input type="text" size="5" name="fh19" value="<?php echo $fh19; ?>"></td>
  </tr>
  <tr class="normalrow">
    <td align="left">Hour 20-21: <input type="text" size="5" name="fh20" value="<?php echo $fh20; ?>"></td>
    <td align="left">Hour 21-22: <input type="text" size="5" name="fh21" value="<?php echo $fh21; ?>"></td>
    <td align="left">Hour 22-23: <input type="text" size="5" name="fh22" value="<?php echo $fh22; ?>"></td>
    <td align="left">Hour 23-00: <input type="text" size="5" name="fh23" value="<?php echo $fh23; ?>"></td>
  </tr>
  <tr class="normalrow">
    <td colspan="4" align="left">Set All Hours To: 
      <input type="text" size="5" name="allhour"> <input name="button1" type="button" class="buttons" onclick="allhours()" value="Set"> 
    </td>
  </tr>
      </table></td>
  </tr>
</table>
<div align="center"><br>
  <input name="updatetrade" type="submit" class="buttons" id="updatetrade" value="Update Trade">
<br><br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>
</div>
</form>
</body>
</html>