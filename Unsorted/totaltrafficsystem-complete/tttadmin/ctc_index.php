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

$time = localtime();
$thishour = $time[2];

$s_date_y=$_POST["s_date_y"];
$s_date_m=$_POST["s_date_m"];
$s_date_d=$_POST["s_date_d"];
$e_date_y=$_POST["e_date_y"];
$e_date_m=$_POST["e_date_m"];
$e_date_d=$_POST["e_date_d"];
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

if ($_POST["addprogram"] != "") {
if (strlen($_POST[program_name]) < 2) { 
print_error("You must enter a program name");
}
if (strlen($_POST[program_url]) < 2) { 
print_error("You must enter a program URL");
}
elseif (!strstr($_POST[program_url],"http://")) {
print_error("You must include http:// in your program URL");
}
else {
mysql_query("INSERT INTO ctc_programs (program_id,sponsor_id,name,url) VALUES ('','$_POST[program_sponsor]','$_POST[program_name]','$_POST[program_url]')") or print_error(mysql_error());
}
}

if ($_POST["addsponsor"] != "") {
if (strlen($_POST[sponsor_name]) < 2) { 
print_error("You must enter a sponsor name");
}
if (strlen($_POST[sponsor_url]) < 2) { 
print_error("You must enter a sponsor URL");
}
elseif (!strstr($_POST[sponsor_url],"http://")) {
print_error("You must include http:// in your sponsor URL");
}
else {
mysql_query("INSERT INTO ctc_sponsors (sponsor_id,name,url) VALUES ('','$_POST[sponsor_name]', '$_POST[sponsor_url]')") or print_error(mysql_error());
}
}

?>
<html>
<head>
<title>TurboTrafficTrader: Overview</title>
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
function openwin(what) {
var idtemp = "";
var f = "0";
for (i=0; i<document.form1.program_id.length; i++) { if (document.form1.program_id[i].checked) { if (f == 0) { var idtemp=idtemp+document.form1.program_id[i].value; } else { var idtemp=idtemp+"|"+document.form1.program_id[i].value; } f++; }}
if (what == "edit") { url = "ctc_edit.php?id="+idtemp; features = "width=400,height=180,scrollbars=1,resizable=1"; }
if (what == "reflog") { url = "ctc_reflog.php?id="+idtemp; features = "width=500,height=400,status=1,resizable=1,scrollbars=1"; }
if (what == "daily") { url = "ctc_daily.php?id="+idtemp; features = "width=500,height=400,status=1,resizable=1,scrollbars=1"; }
if (what == "delete") { url = "ctc_delete.php?id="+idtemp; features = "width=400,height=180,status=0,resizable=1,scrollbars=0"; }
if (what == "reset") { url = "ctc_reset.php?id="+idtemp; features = "width=400,height=120,status=0,resizable=1,scrollbars=0"; }
window.open(url, "_blank", features);
}
</script>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000" topmargin=0 marginheight=0>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="30" background="background2.gif">
  <tr>
   <td align=center valign=middle height=30>
    <a href="http://www.chickentraffic.com" target="_blank"><img src="tcn_ct.gif" border=0 width=102 height=20 alt=''></a>&nbsp;
    <a href="http://www.chickenboard.com" target="_blank"><img src="tcn_cb.gif" border=0 width=97 height=20 alt=''></a>&nbsp;
    <a href="http://www.findtrades.com" target="_blank"><img src="tcn_ft.gif" border=0 width=81 height=20 alt=''></a>&nbsp;
    <a href="http://www.galleryspots.com" target="_blank"><img src="tcn_gs.gif" border=0 width=91 height=20 alt=''></a>&nbsp;
    <a href="http://www.quicktgp.com" target="_blank"><img src="tcn_qt.gif" border=0 width=69 height=20 alt=''></a>&nbsp;
    <a href="http://www.sellmetraffic.com" target="_blank"><img src="tcn_smt.gif" border=0 width=97 height=20 alt=''></a>&nbsp;
    <a href="http://www.turbotraffictrader.com" target="_blank"><img src="tcn_ttt.gif" border=0 width=135 height=20 alt=''></a>
   </td>
  </tr>
</table><br>
<table width="750" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="700" valign="top" align="center" bgcolor=#FFFFFF>
     <!-- CHICKENBANNERS -->
     <SCRIPT LANGUAGE="JavaScript" SRC="http://www.chickenbanners.com/ads.php"></SCRIPT>
     <!-- /CHICKENBANNERS --><br>
    </td>
  </tr>
  <tr class="traderow">
    <td width="700" valign="top"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" bgcolor="#E4E4E4">
        <tr class="toprows"> 
          <td colspan="10" background="background.gif" height="30"><img src="ctc.gif"></td>
        </tr>
	<tr class="menu" height="20"> 
          <td colspan="10">
          <a href="http://chickenboard.com/list.php?f=13">Support</a>
          <font color="#000000">|</font> <a href="http://www.chickentraffic.com/">Feeder Traffic</a>
          <font color="#000000">|</font> <a href="http://www.findtrades.com/">Find Trades</a>
          <font color="#000000">|</font> <a href="http://www.quicktgp.com/gs3/categories.php">Get Galleries</a>
          <font color="#000000">|</font> <a href="http://www.findtrades.com/">Report Cheater</a>
        </tr>
	<tr class="menu" height="20"> 
          <td colspan="10">
	  <a href="trades.php" target="_self"><font color=#FF0000>TTT Trades</font></a>
	  <font color="#000000">|</font> <a href="overview.php" target="_self"><font color=#FF0000>MTTS Overview</font></a>
	  <font color="#000000">|</font> <a href="qtgp_index.php" target="_self"><font color=#FF0000>QuickTGP TGP Builder</font></a>
	  <font color="#000000">|</font> <a href="gs3_index.php" target="_self"><font color=#FF0000>GS3 Statistics</font></a></td>	
        </tr>
	<tr class="menu" height="20"> 
          <td colspan="10">
	  <a href="ctc_index.php" target="_self">Refresh</a>
        </tr>
      </table></td>
  </tr>
  <tr class="traderow">
    <td align="left">
	<table border=0 cellspacing=0 cellpadding=0 width=700>
	<tr>
	<td width=100% valign=top align=left>
	      <font size="1">
	      <b>Server Time:</b> <?php echo date("M, j G:i:s"); ?><br>
	      <b>Logged in:</b> <?php echo date("M, j G:i:s", $cookie[0]); ?><br></font>
	</td>
        </tr>
	</table>
    </td>
  </tr>
</table>
<br>
  <center><font size="4"><strong>Overview</strong></font></center>
<table border=0 cellspacing=1 cellpadding=2 width=100%>
<form action=ctc_index.php method=post>
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
<td bgcolor=#CC0000 align=center width=6%><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>--</b></td>
<td bgcolor=#CC0000 align=center width=22%><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Program Name</b></td>
<td bgcolor=#CC0000 align=center width=62%><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Link To</b></td>
<td bgcolor=#CC0000 align=center width=10%><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Hits</b></td>
</tr>
<?
$res = mysql_query("SELECT * FROM ctc_sponsors ORDER by name ASC");
while($row = mysql_fetch_array($res)) {
extract($row);
$sponsor_select.="<option value='$sponsor_id'>$name</option>";
?>
<tr>
<td bgcolor=#EFEFEF align=center><input type="checkbox" name="program_id" value="s<?echo"$sponsor_id";?>"></td>
<td bgcolor=#EFEFEF align=left colspan=3><font size=2 face="verdana,arial,helvetica"><b>Sponsor:</b> <a href="<?echo"$url";?>" target="_blank"><?echo"$name";?></a></font></td>
</tr>
<?
$res2 = mysql_query("SELECT * FROM ctc_programs WHERE sponsor_id='$sponsor_id'");
if (mysql_num_rows($res2) < 1) { }
else {
while($row2 = mysql_fetch_array($res2)) {
extract($row2);
$res3 = mysql_query("SELECT sum(hits) as sum_hits FROM ctc_stats WHERE (date>='$s_date_y-$s_date_m-$s_date_d' AND date<='$e_date_y-$e_date_m-$e_date_d') AND program_id='$program_id'") or print_error(mysql_error());
if (mysql_num_rows($res3) < 1) { $sum_hits="0"; }
else {
$row3=mysql_fetch_array($res3);
extract($row3);
}
if ($sum_hits < 1) { $sum_hits="0"; }
$tmp = split('/',$_SERVER[SCRIPT_NAME]); array_pop($tmp); array_pop($tmp); $durl = join('/',$tmp); 
$durl = "$_SERVER[HTTP_HOST]$durl";

?>
<tr>
<td bgcolor=#EFEFEF align=center><input type="checkbox" name="program_id" value="<?echo"$program_id";?>"></td>
<td bgcolor=#EFEFEF align=left><font size=2 face="verdana,arial,helvetica"><a href="<?echo"$url";?>" target="_blank"><?echo"$name";?></a></font></td>
<td bgcolor=#EFEFEF align=center><font size=2><a href='http://<?echo"$durl";?>/ctc.php?p=<?echo"$program_id";?>' target='_blank'>http://<?echo"$durl";?>/ctc.php?p=<?echo"$program_id";?></a></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$sum_hits";?></font></td>
</tr>
<?
$total_hits=$total_hits+$sum_hits;
$sponsor_hits=$sponsor_hits+$sum_hits;
}
?>
<tr>
<td bgcolor=#EFEFEF align=center>--</td>
<td bgcolor=#EFEFEF align=left colspan=2><font size=2 face="verdana,arial,helvetica"><b>Sponsor Total:</b></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$sponsor_hits";?></font></td>
</tr>
<?
$sponsor_hits="0";
}
}
?>
<tr>
<td bgcolor=#EFEFEF align=center><font size=2 face="verdana,arial,helvetica">--</font></td>
<td bgcolor=#EFEFEF align=center><font size=2 face="verdana,arial,helvetica">--</font></td>
<td bgcolor=#EFEFEF align=center><font size=2 face="verdana,arial,helvetica">--</font></td>
<td bgcolor=#EFEFEF align=center><font size=2 face="verdana,arial,helvetica"><?echo"$total_hits";?></font></td>
</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr align="center">
    <td><input name="edit" type="button" class="buttons" id="edit" value=" Edit " onclick="openwin('edit')"></td>
    <td><input name="reflog" type="button" class="buttons" id="reflog" value=" Ref Log " onclick="openwin('reflog')"></td>
    <td><input name="daily" type="button" class="buttons" id="daily" value=" Daily " onclick="openwin('daily')"></td>
    <td><input name="reset" type="button" class="buttons" id="reset" value=" Reset " onclick="openwin('reset')"></td>
    <td><input name="delete" type="button" class="buttons" id="delete" value=" Delete " onclick="openwin('delete')"></td>
  </tr>
</form>
</table>
<br>
  <center><font size="4"><strong>Add Sponsor</strong></font><br></center>
<table width="100%" border="1" cellspacing="0" cellpadding="1">
<form method="post" action='ctc_index.php'>
<tr> 
<td width=100% bgcolor=#EFEFEF align=center>

<table border=0 cellspacing=1 cellpadding=2>
<tr>
<td align=left><font size=2 face="verdana,arial,helvetica" color=#000000><b>Sponsor Name</b></td>
<td align=left><font size=2 face="verdana,arial,helvetica"><input type=text size=25 name='sponsor_name'></font></td>
</tr>
<tr>
<td align=left><font size=2 face="verdana,arial,helvetica" color=#000000><b>Sponsor URL</b></td>
<td align=left><font size=2 face="verdana,arial,helvetica"><input type=text size=25 name='sponsor_url'></font></td>
</tr>
<tr>
<td colspan=2 align=center><input type=submit value='Add Sponsor' name='addsponsor' id='addsponsor'></td>
</tr>
</table>

</td>
</tr>
</form>
</table>
<br><br>
  <center><font size="4"><strong>Add Program</strong></font><br></center>
<table width="100%" border="1" cellspacing="0" cellpadding="1">
<form method="post" action='ctc_index.php'>
<tr> 
<td width=100% bgcolor=#EFEFEF align=center>

<table border=0 cellspacing=1 cellpadding=2>
<tr>
<td align=left><font size=2 face="verdana,arial,helvetica" color=#000000><b>Program Sponsor</b></td>
<td align=left><font size=2 face="verdana,arial,helvetica"><select name='program_sponsor'><?echo"$sponsor_select";?></select></font></td>
</tr>
<tr>
<td align=left><font size=2 face="verdana,arial,helvetica" color=#000000><b>Program Name</b></td>
<td align=left><font size=2 face="verdana,arial,helvetica"><input type=text size=25 name='program_name'></font></td>
</tr>
<tr>
<td align=left><font size=2 face="verdana,arial,helvetica" color=#000000><b>Program URL</b></td>
<td align=left><font size=2 face="verdana,arial,helvetica"><input type=text size=25 name='program_url'></font></td>
</tr>
<tr>
<td colspan=2 align=center><input type=submit value='Add Program' name='addprogram' id='addprogram'></td>
</tr>
</table>

</td>
</tr>
</form>
</table>
<br>
<?
close_conn();
?>
<div align="center"><br>
  <br>
  Copyright &copy; 2003 Choker (Chokinchicken.com). All Rights Reserved.<br>
This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
If you upload this script then you do so knowing that any changes to this script that you make are in violation
of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!<br>
</div>
</body>
</html>

