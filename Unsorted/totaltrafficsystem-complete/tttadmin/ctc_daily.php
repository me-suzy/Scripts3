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

$s_date_y=$_GET["s_date_y"];
$s_date_m=$_GET["s_date_m"];
$s_date_d=$_GET["s_date_d"];
$e_date_y=$_GET["e_date_y"];
$e_date_m=$_GET["e_date_m"];
$e_date_d=$_GET["e_date_d"];
if(!$s_date_y)
{
$s_date_y=date("Y");
$s_date_m=date("m");
$s_date_d=date("d");
}
if(!$e_date_y)
{
$e_date_y=date("Y");
$e_date_m=date("m");
$e_date_d=date("d");
}

if (isset($_GET["id"]) AND $_GET["id"] == "") { print_error("Please select a program first"); }
$line = explode('|',$_GET["id"]);
if (count($line) > 1) { print_error("Please select only one program"); }

if (substr($line[0],0,1) == "s") {
$sline[0]=substr($line[0],1,strlen($line[0]));
$res = mysql_query("SELECT name FROM ctc_sponsors WHERE sponsor_id=$sline[0]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename="$name";
$msg="Daily Statistics - $sitename";
}
else {
$res = mysql_query("SELECT name FROM ctc_programs WHERE program_id=$line[0]") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$sitename="$name";
$msg="Daily Statistics - $sitename";
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
<table border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td width=100% bgcolor=#FFFFFF align=center>
  <center><font size="4"><strong><?echo"$msg";?></strong></font></center>
<table border=0 cellspacing=1 cellpadding=2 width=100%>
<form action=ctc_daily.php method=get>
<input type=hidden name='id' value='<?echo"$_GET[id]";?>'>
<tr>
<td bgcolor=#FFFFFF align=center colspan=2>
<table><tr>
<td align=center valign=top><font size=2 face="verdana,arial,helvetica"><b>From:</b></font></td>
<td align=center valign=top><input type=text size=4 name=s_date_y value="<?echo $s_date_y;?>"><br><font size=2 face="verdana,arial,helvetica">Year</font></td>
<td align=center valign=top><input type=text size=2 name=s_date_m value="<?echo $s_date_m;?>"><br><font size=2 face="verdana,arial,helvetica">Month</font></td>
<td align=center valign=top><input type=text size=2 name=s_date_d value="<?echo $s_date_d;?>"><br><font size=2 face="verdana,arial,helvetica">Day</font></td>
<td align=center valign=top><font size=2 face="verdana,arial,helvetica"><b>To:</b></font></td>
<td align=center valign=top><input type=text size=4 name=e_date_y value="<?echo $e_date_y;?>"><br><font size=2 face="verdana,arial,helvetica">Year</font></td>
<td align=center valign=top><input type=text size=2 name=e_date_m value="<?echo $e_date_m;?>"><br><font size=2 face="verdana,arial,helvetica">Month</font></td>
<td align=center valign=top><input type=text size=2 name=e_date_d value="<?echo $e_date_d;?>"><br><font size=2 face="verdana,arial,helvetica">Day</font></td>
</tr></table>
</td>
</tr>
<tr><td align=center><input type=submit value=View></td></tr>
</form>
</table>
<br>
<table width="100%" border="1" cellspacing="0" cellpadding="1">
<form name="form1" action="">
<tr> 
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Date</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Hits</b></td>
</tr>
<?
if (substr($_GET[id],0,1) == "s") {
$_GET["sid"]=substr($_GET[id],1,strlen($_GET[id]));
$res = mysql_query("SELECT date, sum(hits) as sum_hits FROM ctc_stats WHERE date>='$s_date_y-$s_date_m-$s_date_d' AND date<='$e_date_y-$e_date_m-$e_date_d' AND sponsor_id='$_GET[sid]' GROUP by date");
}
else {
$res = mysql_query("SELECT date,hits FROM ctc_stats WHERE date>='$s_date_y-$s_date_m-$s_date_d' AND date<='$e_date_y-$e_date_m-$e_date_d' AND program_id='$_GET[id]'");
}

if (mysql_num_rows($res) < 1) { 
?>
<tr>
<td bgcolor=#EFEFEF align=center colspan=2><font size=2 face="verdana,arial,helvetica"><b>No statistics available</b></font></td>
</tr>
<?
}
else {
while ($row=mysql_fetch_array($res)) {
extract($row);

if (substr($_GET[id],0,1) == "s") { $hits="$sum_hits"; }
?>
<tr>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$date";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$hits";?></font></td>
</tr>
<?
$total_hits=$total_hits+$hits;
}
}
if (!$total_hits) { $total_hits="--"; }
?>
<tr>
<td bgcolor=#EFEFEF align=center><font size=2 face="verdana,arial,helvetica">--</font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$total_hits";?></font></td>
</tr>
</table>
    </td>
  </tr>
  <tr> 
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4" align="center"><font size="2"><a href="javascript:window.close()">Close Window</a></font></td>
  </tr>
</table>
</body>
</html>
