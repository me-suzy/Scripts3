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
$line = explode('|',$_GET["id"]);
if (count($line) > 1) { print_error("Please select only one selection"); }


if ($_POST["updateprogram"] != "") {

if (substr($_POST[program_id],0,1) == "s") {
$_POST[sprogram_id]=substr($_POST[program_id],1,strlen($_POST[program_id]));
$res = mysql_query("SELECT * FROM ctc_sponsors WHERE sponsor_id='$_POST[sprogram_id]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename="$name";
$siteurl="$url";
mysql_query("UPDATE ctc_sponsors SET name='$_POST[program_name]', url='$_POST[program_url]' WHERE sponsor_id='$_POST[sprogram_id]'") or print_error(mysql_error());
$msg = "$sitename updated";
}
else {
$res = mysql_query("SELECT * FROM ctc_programs WHERE program_id='$_POST[program_id]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename="$name";
$siteurl="$url";
mysql_query("UPDATE ctc_programs SET name='$_POST[program_name]', url='$_POST[program_url]' WHERE program_id='$_POST[program_id]'") or print_error(mysql_error());
$msg = "$sitename updated";
}
}
if ($_POST["updateprogram"] != "") {
if (substr($_POST[program_id],0,1) == "s") {
$_POST[sprogram_id]=substr($_POST[program_id],1,strlen($_POST[program_id]));
$res = mysql_query("SELECT * FROM ctc_sponsors WHERE sponsor_id='$_POST[sprogram_id]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename="$name";
$siteurl="$url";
$msg="Edit $sitename";
$type="Sponsor";
}
else {
$res = mysql_query("SELECT * FROM ctc_programs WHERE program_id='$_POST[program_id]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename="$name";
$siteurl="$url";
$msg="Edit $sitename";
$type="Program";
}
}
else {
if (substr($_GET[id],0,1) == "s") {
$_GET[sid]=substr($_GET[id],1,strlen($_GET[id]));
$res = mysql_query("SELECT * FROM ctc_sponsors WHERE sponsor_id='$_GET[sid]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename="$name";
$siteurl="$url";
$msg="Edit $sitename";
$type="Sponsor";
}
else {
$res = mysql_query("SELECT * FROM ctc_programs WHERE program_id='$_GET[id]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename="$name";
$siteurl="$url";
$msg="Edit $sitename";
$type="Program";
}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
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
<div align=center>
<form action="ctc_edit.php" method="POST">

<?
if ($_POST[updateprogram] == "") {
?>
<input type="hidden" name="program_id" value="<?php echo "$_GET[id]"; ?>">
<?
}
else {
?>
<input type="hidden" name="program_id" value="<?php echo "$_POST[program_id]"; ?>">
<?
}
?>
<div align=center>
<font size=4><strong><?echo"$msg";?></strong></font><br>
<table border=0 cellspacing=1 cellpadding=2>
<tr>
<td><font size=2 face='verdana,arial,helvetica'><b><?echo"$type";?> Name:</b></td>
<td><input type=text size=20 name='program_name' value="<?echo"$sitename";?>">
</tr>
<tr>
<td><font size=2 face='verdana,arial,helvetica'><b><?echo"$type";?> URL:</b></td>
<td><input type=text size=20 name='program_url' value="<?echo"$siteurl";?>">
</tr>
<tr>
<td colspan=2 align=center>
  <input name="updateprogram" type="submit" value="Update" class="buttons">&nbsp;&nbsp;
  <input name="goback" type="button" value="Cancel" onclick="window.close()" class="buttons">
</td>
</tr>
</table>
</div>
</form>
<?php } ?>
</body>
</html>
