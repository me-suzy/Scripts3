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

if ($_POST["updatetrade"] != "") {
$line = explode('|',$_POST["trade_id"]);
if (count($line) == 1) {
$sitedomain = parse_url($_POST["siteurl"]);
$sitedomain = str_replace("www.","",$sitedomain["host"]);
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$line[0]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
if ($row[0] != $sitedomain) { $msg = "Site URL And Site Domain Does Not Match"; $_GET["id"] = $_POST["trade_id"];}
else { mysql_query("UPDATE ttt_trades SET siteurl='$_POST[siteurl]', sitename='$_POST[sitename]', wm_email='$_POST[wm_email]', wm_icq='$_POST[wm_icq]' WHERE trade_id=$line[0]") or print_error(mysql_error()); }
}
for ($x=0; $x<count($line); $x++) {
$res = mysql_query("SELECT sitedomain FROM ttt_trades WHERE trade_id=$line[$x]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
$sitedomains.="$sitedomain, ";
mysql_query("UPDATE ttt_trades SET ratio='$_POST[ratio]', min_prod='$_POST[min_prod]', max_prod='$_POST[max_prod]', enabled='$_POST[enabled]', turbo='$_POST[turbo]' WHERE trade_id=$line[$x]") or print_error(mysql_error());
if ($_POST[nht] == 0) { 
mysql_query("UPDATE ttt_forces SET fh0=$_POST[fh0], fh1=$_POST[fh1], fh2=$_POST[fh2], fh3=$_POST[fh3], fh4=$_POST[fh4], fh5=$_POST[fh5], fh6=$_POST[fh6], fh7=$_POST[fh7], fh8=$_POST[fh8], fh9=$_POST[fh9], fh10=$_POST[fh10], fh11=$_POST[fh11], fh12=$_POST[fh12], fh13=$_POST[fh13], fh14=$_POST[fh14], fh15=$_POST[fh15], fh16=$_POST[fh16], fh17=$_POST[fh17], fh18=$_POST[fh18], fh19=$_POST[fh19], fh20=$_POST[fh20], fh21=$_POST[fh21], fh22=$_POST[fh22], fh23=$_POST[fh23], nht=0, nhs=0 WHERE trade_id=$line[$x]") or print_error(mysql_error());
}
else {
mysql_query("UPDATE ttt_forces SET fh0=$_POST[fh0], fh1=$_POST[fh1], fh2=$_POST[fh2], fh3=$_POST[fh3], fh4=$_POST[fh4], fh5=$_POST[fh5], fh6=$_POST[fh6], fh7=$_POST[fh7], fh8=$_POST[fh8], fh9=$_POST[fh9], fh10=$_POST[fh10], fh11=$_POST[fh11], fh12=$_POST[fh12], fh13=$_POST[fh13], fh14=$_POST[fh14], fh15=$_POST[fh15], fh16=$_POST[fh16], fh17=$_POST[fh17], fh18=$_POST[fh18], fh19=$_POST[fh19], fh20=$_POST[fh20], fh21=$_POST[fh21], fh22=$_POST[fh22], fh23=$_POST[fh23], nht=$_POST[nht] WHERE trade_id=$line[$x]") or print_error(mysql_error());
}
$_GET["id"].="$line[$x]|";
}
$sitedomains=substr($sitedomains,0,strlen($sitedomains)-2);
$_GET["id"]=substr($_GET["id"],0,strlen($_GET["id"])-1);
}
$line = explode('|',$_GET["id"]);
if (count($line) == 1) {
$res = mysql_query("SELECT * FROM ttt_trades, ttt_forces WHERE ttt_trades.trade_id=ttt_forces.trade_id AND ttt_trades.trade_id=$line[0]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
mysql_free_result($res);
extract($row);
if (!isset($msg)) { $msg = "Edit $sitedomain"; }
$trade_ids="$line[0]";
$nhr=$nht-$nhs;
close_conn();
}
elseif (count($line) > 1) {
$sitedomains="";
for ($x=0; $x<count($line); $x++) {
$res = mysql_query("SELECT * FROM ttt_trades WHERE trade_id=$line[$x]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
mysql_free_result($res);
extract($row);
$trade_ids.="$line[$x]|";
}
$res = mysql_query("SELECT * FROM ttt_settings") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$trade_ids=substr($trade_ids,0,strlen($trade_ids)-1);
if (!isset($msg)) { $msg="Edit multiple sites"; }
$enabled=$webmasterpage;
$fh0=0; $fh1=0; $fh2=0; $fh3=0; $fh4=0; $fh5=0; $fh6=0; $fh7=0; $fh8=0; $fh9=0; $fh10=0; $fh11=0; $fh12=0; $fh13=0; $fh14=0; $fh15=0; $fh16=0; $fh17=0; $fh18=0; $fh19=0; $fh20=0; $fh21=0; $fh22=0; $fh23=0;
$max_prod=$default_max_prod;
$min_prod=$default_min_prod;
$ratio=$default_ratio;
$turbo=$turbomode;
$nhr=0;
close_conn();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
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
<input type="hidden" name="trade_id" value="<?php echo $trade_ids; ?>">
<div align="center"><b><font size="4"><?php echo $msg; ?></font></b><br>
  <br>
</div>
<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellpadding="2" cellspacing="0" class="normalrow">
<?php
if (count($line) == 1) {
?>
        <tr class="toprows"> 
          <td colspan="4">Webmaster &amp; Trade Info</td>
        </tr>
        <tr align="left"> 
          <td width="25%">Site Name:</td>
          <td width="25%"><input name="sitename" type="text" id="sitename" size="20" maxlength="100" value="<?php echo $sitename; ?>"></td>
          <td width="25%">Webmaster Email:</td>
          <td width="25%"><input name="wm_email" type="text" id="wm_email" size="20" maxlength="100" value="<?php echo $wm_email; ?>"></td>
        </tr>
        <tr align="left"> 
          <td>Site URL:</td>
          <td><input name="siteurl" type="text" id="siteurl" size="20" maxlength="150" value="<?php echo $siteurl; ?>"></td>
          <td>Webmaster ICQ:</td>
          <td><input name="wm_icq" type="text" id="wm_icq" size="20" maxlength="50" value="<?php echo $wm_icq; ?>"></td>
        </tr>
<?php
}
?>
        <tr class="toprows"> 
          <td colspan="4">Trade Settings</td>
        </tr>
        <tr align="left"> 
          <td>Trade Ratio:</td>
          <td><input name="ratio" type="text" id="ratio" size="20" maxlength="10" value="<?php echo $ratio; ?>"></td>
          <td>Status:</td>
          <td><select name="enabled"><option value="1" <?php if ($enabled == 1) { echo "selected"; } ?>>Enabled</option>
<option value="0" <?php if ($enabled == 0) { echo "selected"; } ?>>Disabled</option>
<option value="2" <?php if ($enabled == 2) { echo "selected"; } ?>>Auto Disabled</option>
</td>
        </tr>
        <tr align="left"> 
          <td>Minimum Prod:</td>
          <td><input name="min_prod" type="text" id="min_prod" size="20" maxlength="10" value="<?php echo $min_prod; ?>"></td>
          <td>Include In Turbo:</td>
          <td><select name="turbo"><option value="1" <?php if ($turbo == 1) { echo "selected"; } ?>>Yes</option>
<option value="0" <?php if ($turbo == 0) { echo "selected"; } ?>>No</option>
</td>
        </tr>
        <tr align="left">
          <td>Maximum Prod:</td>
          <td><input name="max_prod" type="text" id="max_prod" size="20" maxlength="10" value="<?php echo $max_prod; ?>"></td>
          <td><a title='Nitro hits are sent as QUICKLY as possible until they run out when TURBOMODE is off.'>Nitro Hits:</a></td>
          <td><input name="nht" type="text" id="nht" size="20" maxlength="4" value="<?php echo $nhr; ?>"></td>
        </tr>
  <tr class="toprows">
    <td colspan="4"> Force Hits:</td>
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
<?php
if (count($line) == 1) {
?>
  <input name="updatetrade" type="submit" class="buttons" id="updatetrade" value="Update Trade">
<?php
}
elseif (count($line) > 1) {
?>
  <input name="updatetrade" type="submit" class="buttons" id="updatetrade" value="Update Trades">
<?php
}
?>
<br><br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>
</div>
</form>
</body>
</html>