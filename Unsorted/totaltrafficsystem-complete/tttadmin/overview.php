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

if ($_POST["addtgp"] != "") {
if (!strstr($_POST[adminurl],"http://")) {
print_error("You must include http://");
}
elseif (substr($_POST[adminurl],strlen($_POST[adminurl])-1,strlen($_POST[adminurl])) == "/") {
print_error("You must not include a trailing / on your TTTAdmin URL");
}
elseif ($_POST[adminpassword] == "") {
print_error("You must enter an admin password");
}
else {
$valid = @implode('',@file("$_POST[adminurl]/reflect.php?check=1"));
if ($valid != "1") {
print_error("You must place reflect.php in your $_POST[adminurl] directory");
}
else {
mysql_query("INSERT INTO ttt_overview values('','$_POST[adminurl]',PASSWORD('$_POST[adminpassword]'))") or print_error(mysql_error());
}
}
}
if ($_GET["del"] != "") {
mysql_query("DELETE FROM ttt_overview where id='$_GET[del]'") or print_error(mysql_error());
}
?>
<html>
<head>
<title>TurboTrafficTrader: Overview</title>
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
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
          <td colspan="10" background="background.gif" height="30"><img src="mttts.gif"></td>
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
	  <font color="#000000">|</font> <a href="qtgp_index.php" target="_self"><font color=#FF0000>QuickTGP TGP Builder</font></a>
	  <font color="#000000">|</font> <a href="ctc_index.php" target="_self"><font color=#FF0000>CTC Overview</font></a>
	  <font color="#000000">|</font> <a href="gs3_index.php" target="_self"><font color=#FF0000>GS3 Statistics</font></a></td>	
        </tr>
	<tr class="menu" height="20"> 
          <td colspan="10">
	  <a href="overview.php" target="_self">Refresh</a>
          </td>
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
  <center><font size="4"><strong>Overview</strong></font><br></center>
<table width="100%" border="1" cellspacing="0" cellpadding="1">
<tr> 
<td bgcolor=#CC0000>&nbsp;</td>
<td bgcolor=#CC0000>&nbsp;</td>
<td bgcolor=#CC0000>&nbsp;</td>
<td bgcolor=#CC0000 colspan="5" align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>This Hour</b></font></td>
<td bgcolor=#CC0000 colspan="5" align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Last 23 Hours + This Hour</b></font></td>
</tr>
<tr> 
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Del</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Site Domain</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Trades</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>In</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Out</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Clicks</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Prod</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Return</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>In</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Out</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Clicks</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Prod</b></td>
<td bgcolor=#CC0000 align=center><font size=2 face="verdana,arial,helvetica" color=#FFFFFF><b>Return</b></td>
</tr>

<?
$res = mysql_query("SELECT * FROM ttt_overview");
while($row = mysql_fetch_array($res)) {
extract($row);
$stats = implode('',@file("$adminurl/reflect.php?password=$adminpassword"));
$domain = parse_url($adminurl);
$domain = str_replace("www.","",$domain["host"]);
$temp=explode('|',$stats);
?>
<tr>
<td bgcolor=#EFEFEF align=center><font size=2 face="verdana,arial,helvetica"><a href="overview.php?del=<?echo"$id";?>" target="_self">X</a></font></td>
<td bgcolor=#EFEFEF align=left><font size=2 face="verdana,arial,helvetica"><a href="<?echo"$adminurl";?>" target="_blank"><?echo"$domain";?></a></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$temp[0]";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$temp[1]";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$temp[2]";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><a title='Internal Clicks: <?echo"$temp[3]";?>'><?echo"$temp[4]";?></a></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><a title='Internal Prod: <?echo"$temp[5]";?>'><?echo"$temp[6]";?></a></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$temp[7]";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$temp[8]";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$temp[9]";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><a title='Internal Clicks: <?echo"$temp[10]";?>'><?echo"$temp[11]";?></a></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><a title='Internal Prod: <?echo"$temp[12]";?>'><?echo"$temp[13]";?></a></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$temp[14]";?></font></td>
</tr>
<?
$trades=$trades+$temp[0];
$this_in=$this_in+$temp[1];
$this_out=$this_out+$temp[2];
$this_clicks=$this_clicks+$temp[3];
$this_tclicks=$this_tclicks+$temp[4];
$last_in=$last_in+$temp[8];
$last_out=$last_out+$temp[9];
$last_clicks=$last_clicks+$temp[10];
$last_tclicks=$last_tclicks+$temp[11];
if ($this_in==0) { $this_prod="0"; $this_tprod="0"; $this_return="0"; }
else { $this_prod=round(($this_clicks/$this_in)*100,1); $this_tprod=round(($this_tclicks/$this_in)*100,1); $this_return=round(($this_out/$this_in)*100,1); }
if ($last_in==0) { $last_prod="0"; $last_tprod="0"; $last_return="0"; }
else { $last_prod=round(($last_clicks/$last_in)*100,1); $last_tprod=round(($last_tclicks/$last_in)*100,1); $last_return=round(($last_out/$last_in)*100,1);}
}

if ($trades=="") { $trades="0"; }
if ($this_in=="") { $this_in="0"; }
if ($this_out=="") { $this_out="0"; }
if ($this_clicks=="") { $this_clicks="0"; }
if ($this_tclicks=="") { $this_tclicks="0"; }
if ($this_prod=="") { $this_prod="0"; }
if ($this_tprod=="") { $this_tprod="0"; }
if ($this_return=="") { $this_return="0"; }
if ($last_in=="") { $last_in="0"; }
if ($last_out=="") { $last_out="0"; }
if ($last_clicks=="") { $last_clicks="0"; }
if ($last_tclicks=="") { $last_tclicks="0"; }
if ($last_prod=="") { $last_prod="0"; }
if ($last_tprod=="") { $last_tprod="0"; }
if ($last_return=="") { $last_return="0"; }

?>
<tr>
<td bgcolor=#EFEFEF align=center><font size=2 face="verdana,arial,helvetica">--</font></td>
<td bgcolor=#EFEFEF><font size=2 face="verdana,arial,helvetica"><b>Totals</b></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$trades";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$this_in";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$this_out";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><a title='Internal Clicks: <?echo"$this_clicks";?>'><?echo"$this_tclicks";?></a></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><a title='Internal Prod: <?echo"$this_prod";?>'><?echo"$this_tprod";?></a>%</font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$this_return";?>%</font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$last_in";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$last_out";?></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><a title='Internal Clicks: <?echo"$last_clicks";?>'><?echo"$last_tclicks";?></a></font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><a title='Internal Prod: <?echo"$last_prod";?>'><?echo"$last_tprod";?></a>%</font></td>
<td bgcolor=#EFEFEF align="center"><font size=2 face="verdana,arial,helvetica"><?echo"$last_return";?>%</font></td>
</tr>
</table>
<br>
  <center><font size="4"><strong>Add TGP</strong></font><br></center>
<table width="100%" border="1" cellspacing="0" cellpadding="1">
<form method="post" action='overview.php'>
<tr> 
<td width=100% bgcolor=#EFEFEF align=center>

<table border=0 cellspacing=1 cellpadding=2>
<tr>
<td align=left><font size=2 face="verdana,arial,helvetica" color=#000000><b>TGP TTTAdmin URL</b></td>
<td align=left><font size=2 face="verdana,arial,helvetica"><input type=text size=25 name='adminurl'></font></td>
</tr>
<tr>
<td align=left><font size=2 face="verdana,arial,helvetica" color=#000000><b>TGP TTTAdmin Password</b></td>
<td align=left><font size=2 face="verdana,arial,helvetica"><input type=text size=25 name='adminpassword'></font></td>
</tr>
<tr>
<td colspan=2 align=center><input type=submit value='Add TGP' name='addtgp' id='addtgp'></td>
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

