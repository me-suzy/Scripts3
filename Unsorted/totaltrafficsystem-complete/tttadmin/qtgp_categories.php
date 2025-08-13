<?
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
$res = mysql_query("SELECT * FROM qtgp_settings");
$row = mysql_fetch_array($res);
extract($row);
$res = mysql_query("SELECT * FROM qtgp_categories ORDER by name");
close_conn();
$valid=mysql_num_rows($res);
?>
<html>
<head>
<title>TurboTrafficTrader: QuickTGP</title>
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#FFFFFF text=#000000 link=#000000 vlink=#000000 alink=#000000 topmargin=0 marginheight=0>
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
          <td colspan="10" background="background.gif" height="30"><img src="qtgp.gif"></td>
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
	  <b>QuickTGP Settings &gt;</b> 
	  <a href="qtgp_index.php">Home</a> 
	  <font color="#000000">|</font> <a href="qtgp_categories.php">Categories</a>
	  <font color="#000000">|</font> <a href="qtgp_categories.php?v=get">Get Categories</a>
          <font color="#000000">|</font> <a href="qtgp_categories.php?v=manage">Manage</a>
</td>
	</tr>
	<tr class="menu" height="20"> 
          <td colspan="10">
	  <a href="trades.php" target="_self"><font color=#FF0000>TTT Trades</font></a>
	  <font color="#000000">|</font> <a href="overview.php" target="_self"><font color=#FF0000>MTTS Overview</font></a>
	  <font color="#000000">|</font> <a href="ctc_index.php" target="_self"><font color=#FF0000>CTC Overview</font></a>
	  <font color="#000000">|</font> <a href="gs3_index.php" target="_self"><font color=#FF0000>GS3 Statistics</font></a></td>	
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
<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
<tr>
<td width=100% bgcolor=#FFFFFF align=center valing=middle>
<br>

<?
if ($_POST["v"]) {
switch($_POST["v"]) {
case "savemanage":
open_conn();
foreach($_POST["ucategories"] as $temp) {
$vtemp=explode('-',$temp);
if ($vtemp[0] == $vtemp[2]) { }
else {
mysql_query("UPDATE qtgp_categories SET status='$vtemp[0]' WHERE name='$vtemp[1]'");
}
}
close_conn();
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<b><font size=2 face='verdana,arial,helvetica'>Categories have been updated</font></b>
</td>
</tr>
<?
if ($_POST[w]) {
?>
<tr>
<form method=get action='qtgp_build.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Build TGP'>
</td>
</form>
</tr>
<?
}
else {
?>
<tr>
<form method=get action='qtgp_categories.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Manage'>
<input type='hidden' name='v' value='manage'>
</td>
</form>
</tr>
<?
}
?>
</table>
<?
break;
}
}
else {
switch($_GET["v"]) {
case "":
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Get the latest categories from QuickTGP.
</font>
</td>
</tr>
<tr>
<form method=get action='qtgp_categories.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Get Categories'>
<input type='hidden' name='v' value='get'>
</td>
</form>
</tr>
</table>
<br>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Manage your categories.
</font>
</td>
</tr>
<tr>
<form method=get action='qtgp_categories.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Manage'>
<input type='hidden' name='v' value='manage'>
</td>
</form>
</tr>
</table>
<?
break;
case "get":
$cats=@implode("",@file("http://www.quicktgp.com/gs3/mirrors/update.php?date=$updated"));
if ($cats == "none") {
$tc=$valid;
$nc=0;
}
else {
$temp=explode('<br>',$cats);
$nw=count($temp)-1;
open_conn();
for ($x=0; $x<$nw; $x++) {
$temp2=explode('|',$temp[$x]);
mysql_query("INSERT into qtgp_categories values('$temp2[0]','$temp2[1]','1')");
}
mysql_query("UPDATE qtgp_settings SET updated=CURDATE()");
close_conn();
$tc=$valid;
$nc=$nw;
}
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
<b>Categories successfully updated</b><br>
Total Categories: <?echo"$tc";?><br>
New Categories: <?echo"$nc";?>
</font>
</td>
</tr>
<?
if ($_GET[w]) {
?>
<tr>
<form method=get action='qtgp_categories.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Next Step'>
<input type='hidden' name='v' value='manage'>
<input type='hidden' name='w' value='1'>
</td>
</form>
</tr>
<?
}
else {
?>
<tr>
<form method=get action='qtgp_categories.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Get Categories'>
<input type='hidden' name='v' value='get'>
</td>
</form>
</tr>
<?
}
?>
</table>
<?
break;
case "manage":
if ($valid < 1) { 
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
You do not have any categories in your database.
</font>
</td>
</tr>
<?
if ($_GET[w]) {
?>
<tr>
<form method=get action='qtgp_categories.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Previous Step'>
<input type='hidden' name='v' value='get'>
<input type='hidden' name='w' value='1'>
</td>
</form>
</tr>
<?
}
else {
?>
<tr>
<form method=get action='qtgp_categories.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Manage'>
<input type='hidden' name='v' value='get'>
</td>
</form>
</tr>
<?
}
?>
</table>
<?
}
else {
$x=0;
while($temp=mysql_fetch_array($res)) {
$displayname="";
$tv=substr($temp[0],0,3);
$tempname=substr($temp[0],4,strlen($temp[0]));
$splitname=explode('_',$tempname);
for($m=0; $m<count($splitname); $m++) {
$tempword=ucfirst($splitname[$m]);
$displayname.="$tempword ";
}
$displayname=substr($displayname,0,strlen($displayname)-1);
if (strlen($temp[0]) < 5) { }
else {
if (count($splitname) > $link_word_count) { }
else {
if ($temp[2] == "0") { $codeoutput[$tv].="<tr><td width=10% align=center><input type=radio name='ucategories[$x]' value='0-$temp[0]-$temp[2]' CHECKED></td><td width=10% align=center><input type=radio name='ucategories[$x]' value='1-$temp[0]-$temp[2]'></td><td width=80% align=left><b><font size=2 face='verdana,arial,helvetica'>$displayname</font></b></td></tr>\n"; }
else { $codeoutput[$tv].="<tr><td width=10% align=center><input type=radio name='ucategories[$x]' value='0-$temp[0]-$temp[2]'></td><td width=10% align=center><input type=radio name='ucategories[$x]' value='1-$temp[0]-$temp[2]' CHECKED></td><td width=80% align=left><b><font size=2 face='verdana,arial,helvetica'>$displayname</font></b></td></tr>\n"; }
$x++;
}
}
}
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<form method=post action='qtgp_categories.php'>
<td bgcolor=#FFFFFF width=100% valign=middle align=center>

<table border=0 cellspacing=2 cellpadding=2>
<tr>
<form method=post action='qtgp_categories.php'>
<td colspan=3 align=center width=100%>
<font size=2 face='verdana,arial,helvetica'>
<b>Categories:</b><br>
<a href='#m_g'>Gay Movies</a> | <a href='#m_l'>Lesbian Movies</a> | <a href='#m_s'>Straight Movies</a> | <a href='#m_t'>Tranny Movies</a><br>
<a href='#p_g'>Gay Pictures</a> | <a href='#p_l'>Lesbian Pictures</a> | <a href='#p_s'>Straight Pictures</a> | <a href='#p_t'>Tranny Pictures</a>
</font>
</td>
</tr>
<tr>
<td width=10% valign=top align=center>
<font size=2 face='verdana,arial,helvetica'><b>Off</b></font>
</td>
<td width=10% valign=top align=center>
<font size=2 face='verdana,arial,helvetica'><b>On</b></font>
</td>
<td width=10% valign=top align=left>
<font size=2 face='verdana,arial,helvetica'><b>Niche</b></font>
</td>
</tr>
<tr><td colspan=3 align=center><b><font size=4 face='verdana,arial,helvetica'><a name=m_g>Gay Movies</a></font></b></td></tr>
<?php if (strlen($codeoutput[m_g]) < 5) { echo "<tr><td colspan=3 align=center><font size=2 face='verdana,arial,helvetica'>No categories</font></td></tr>"; } else { echo "$codeoutput[m_g]"; } ?>
<tr><td colspan=3 align=center><b><font size=4 face='verdana,arial,helvetica'><a name=m_l>Lesbian Movies</a></font></b></td></tr>
<?php if (strlen($codeoutput[m_l]) < 5) { echo "<tr><td colspan=3 align=center><font size=2 face='verdana,arial,helvetica'>No categories</font></td></tr>"; } else { echo "$codeoutput[m_l]"; } ?>
<tr><td colspan=3 align=center><b><font size=4 face='verdana,arial,helvetica'><a name=m_s>Straight Movies</a></font></b></td></tr>
<?php if (strlen($codeoutput[m_s]) < 5) { echo "<tr><td colspan=3 align=center><font size=2 face='verdana,arial,helvetica'>No categories</font></td></tr>"; } else { echo "$codeoutput[m_s]"; } ?>
<tr><td colspan=3 align=center><b><font size=4 face='verdana,arial,helvetica'><a name=m_t>Tranny Movies</a></font></b></td></tr>
<?php if (strlen($codeoutput[m_t]) < 5) { echo "<tr><td colspan=3 align=center><font size=2 face='verdana,arial,helvetica'>No categories</font></td></tr>"; } else { echo "$codeoutput[m_t]"; } ?>
<tr><td colspan=3 align=center><b><font size=4 face='verdana,arial,helvetica'><a name=p_g>Gay Pictures</a></font></b></td></tr>
<?php if (strlen($codeoutput[p_g]) < 5) { echo "<tr><td colspan=3 align=center><font size=2 face='verdana,arial,helvetica'>No categories</font></td></tr>"; } else { echo "$codeoutput[p_g]"; } ?>
<tr><td colspan=3 align=center><b><font size=4 face='verdana,arial,helvetica'><a name=p_l>Lesbian Pictures</a></font></b></td></tr>
<?php if (strlen($codeoutput[p_l]) < 5) { echo "<tr><td colspan=3 align=center><font size=2 face='verdana,arial,helvetica'>No categories</font></td></tr>"; } else { echo "$codeoutput[p_l]"; } ?>
<tr><td colspan=3 align=center><b><font size=4 face='verdana,arial,helvetica'><a name=p_s>Straight Pictures</a></font></b></td></tr>
<?php if (strlen($codeoutput[p_s]) < 5) { echo "<tr><td colspan=3 align=center><font size=2 face='verdana,arial,helvetica'>No categories</font></td></tr>"; } else { echo "$codeoutput[p_s]"; } ?>
<tr><td colspan=3 align=center><b><font size=4 face='verdana,arial,helvetica'><a name=p_t>Tranny Pictures</a></font></b></td></tr>
<?php if (strlen($codeoutput[p_t]) < 5) { echo "<tr><td colspan=3 align=center><font size=2 face='verdana,arial,helvetica'>No categories</font></td></tr>"; } else { echo "$codeoutput[p_t]"; } ?>
</table>
</td>
</tr>
<tr>
<td width=100% colspan=3 bgcolor=#EFEFEF align=center><input type=submit value='Save Changes'></td>
</tr>
<input type='hidden' name='v' value='savemanage'>
<?
if ($_GET[w]) {
?>
<input type='hidden' name='w' value='1'>
<?
}
?>
</form>
</table>
<?
}
break;
}
}
?>

<br>
</td>
</tr>
</table>
<br><br>
<table border=0 cellspacing=0 cellpadding=0 width=99%>
<tr>
<td width=100% valign=top align=center>
<font size=2 face='verdana,arial,helvetica'>
Copyright © 2003 Choker (Chokinchicken.com). All Rights Reserved.<br>
This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
If you upload this script then you do so knowing that any changes to this script that you make are in violation
of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!<br>
</font>
</td>
</tr>
</table>
<br>
</center>
</body>
</html>