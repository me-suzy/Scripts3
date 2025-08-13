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
if ($_POST["delete"] != "") {
$line=explode('|',$_POST[program_id]);
if (count($line) > 1) { $_sitename="Multiple selections"; $max=count($line); }
else {
$max=count($line);
if (substr($line[0],0,1) == "s") {
$sline[0]=substr($line[0],1,strlen($line[0]));
$res = mysql_query("SELECT name FROM ctc_sponsors WHERE sponsor_id='$sline[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$_sitename="$name";
}
else {
$res = mysql_query("SELECT name FROM ctc_programs WHERE program_id='$line[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$_sitename="$name";
}
}
$msg = "$_sitename deleted";
for ($i=0; $i<$max; $i++) {
if ($line[$i] == "1" || $line[$i] == "s1") { }
else {
if (substr($line[$i],0,1) == "s") {
$sline[$i]=substr($line[$i],1,strlen($line[$i]));
mysql_query("DELETE FROM ctc_sponsors WHERE sponsor_id='$sline[$i]'") or print_error(mysql_error());
mysql_query("DELETE FROM ctc_programs WHERE sponsor_id='$sline[$i]'") or print_error(mysql_error());
mysql_query("DELETE FROM ctc_stats WHERE sponsor_id='$sline[$i]'") or print_error(mysql_error());
mysql_query("DELETE FROM ctc_referers WHERE sponsor_id='$sline[$i]'") or print_error(mysql_error());
}
else {
mysql_query("DELETE FROM ctc_programs WHERE program_id='$line[$i]'") or print_error(mysql_error());
mysql_query("DELETE FROM ctc_stats WHERE program_id='$line[$i]'") or print_error(mysql_error());
mysql_query("DELETE FROM ctc_referers WHERE program_id='$line[$i]'") or print_error(mysql_error());
}
}
}
}
else {
$line=explode('|',$_GET[id]);
if (count($line) > 1) { $sitename="multiple selections"; $max=count($line); }
else {
$max=count($line);
if (substr($line[0],0,1) == "s") {
$sline[0]=substr($line[0],1,strlen($line[0]));
$res = mysql_query("SELECT name FROM ctc_sponsors WHERE sponsor_id='$sline[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename = "$name"; 
}
else {
$res = mysql_query("SELECT name FROM ctc_programs WHERE program_id='$line[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename = "$name"; 
}
}
for ($i=0; $i<$max; $i++) {
$program_ids.="$line[$i]|";
}
$program_ids=substr($program_ids,0,strlen($program_ids)-1);
$msg = "Are you sure you want to delete $sitename?";
}
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
<?php
if ($_POST["delete"] != "") {
?>
<div align="center"><strong><font size="4"><?php echo $msg; ?><br></font></strong>
<br><br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>
</div>
<?php
}
else {
?>
<form action="ctc_delete.php" method="POST">
<input type="hidden" name="program_id" value="<?php echo $program_ids; ?>">
<div align="center"><strong><font size="4"><?php echo "$msg"; ?><br>
  <input name="delete" type="submit" value="Yes, Delete It" class="buttons">&nbsp;&nbsp;
  <input name="goback" type="button" value="No, Go Back" onclick="window.close()" class="buttons">
  </font></strong> </div>
</form>
<?php } ?>
</body>
</html>
