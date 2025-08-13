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
if (isset($_GET["id"]) AND $_GET["id"] == "") { print_error("Please select a sponsor first"); }
if (strstr($_GET["id"],"s")) { print_error("Please select a program, not a sponsor"); }

if ($_POST["reset"] != "") {
$line=explode('|',$_POST[program_id]);
if (count($line) > 1) { $sitename="Multiple programs"; $max=count($line); }
else {
$max=count($line);
$res = mysql_query("SELECT name FROM ctc_programs WHERE program_id='$line[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename="$name";
}
for ($i=0; $i<$max; $i++) {
mysql_query("DELETE FROM ctc_stats WHERE program_id='$line[$i]'") or print_error(mysql_error());
mysql_query("DELETE FROM ctc_referers WHERE program_id='$line[$i]'") or print_error(mysql_error());
}
$msg = "$sitename reseted";
}
else {
$line=explode('|',$_GET[id]);
if (count($line) > 1) { $sitename="multiple programs"; $max=count($line); }
else {
$max=count($line);
$res = mysql_query("SELECT name FROM ctc_programs WHERE program_id='$line[0]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename="$name";
}
for ($i=0; $i<$max; $i++) {
$program_ids.="$line[$i]|";
}
$program_ids=substr($program_ids,0,strlen($program_ids)-1);
$msg = "Are you sure you want to reset $sitename?";
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
if ($_POST["reset"] != "") {
?>
<div align="center"><strong><font size="4"><?php echo $msg; ?><br></font></strong>
<br><br><br><font size="2"><a href="javascript:window.close()">Close Window</a></font>
</div>
<?php
}
else {
?>
<form action="ctc_reset.php" method="POST">
<input type="hidden" name="program_id" value="<?php echo "$program_ids"; ?>">
<div align="center"><strong><font size="4">Are you sure you want to reset <?php echo $sitename; ?>?<br>
  <input name="reset" type="submit" value="Yes, Reset <?php if (count($line) > 1) { echo"Them"; } else { echo"It"; }?>" class="buttons">&nbsp;&nbsp;
  <input name="goback" type="button" value="No, Go Back" onclick="window.close()" class="buttons">
  </font></strong> </div>
</form>
<?php } ?>
</body>
</html>
