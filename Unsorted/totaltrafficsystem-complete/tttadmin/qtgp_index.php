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
close_conn();
$row = mysql_fetch_array($res);
extract($row);
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
	  <b>QuickTGP &gt;</b> 
	  <a href="qtgp_settings.php">Settings</a>
          <font color="#000000">|</font> <a href="qtgp_categories.php">Categories</a>
          <font color="#000000">|</font> <a href="qtgp_build.php">Build</a></td>
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
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
<b>Welcome to QuickTGP v1.0</b><br><br>

QuickTGP v1.0 is an automated multi-page TGP builder written entirely in PHP/MYSQL that gives you the ability to build custom multi-page TGPs (using GS3 categories) in a matter of minutes.
</font>
</td>
</tr>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='hidden' name='v' value='paths'>
<input type='hidden' name='w' value='1'>
<input type='submit' value='Start'>
</td>
</form>
</tr>
</table>
<br>
</td>
</tr>
</table><br><br>
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