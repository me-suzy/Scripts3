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
	  <b>QuickTGP Settings &gt;</b> 
	  <a href="qtgp_index.php">Home</a> 
	  <font color="#000000">|</font> <a href="qtgp_settings.php">Settings</a>
	  <font color="#000000">|</font> <a href="qtgp_settings.php?v=paths">Paths</a>
          <font color="#000000">|</font> <a href="qtgp_settings.php?v=colors">Colors</a>
          <font color="#000000">|</font> <a href="qtgp_settings.php?v=tradescript">Trade Script</a>
          <font color="#000000">|</font> <a href="qtgp_settings.php?v=layout">Layout</a>
          <font color="#000000">|</font> <a href="qtgp_settings.php?v=template">Template</a>
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
case "savepaths": 
open_conn();
mysql_query("UPDATE qtgp_settings SET file='$_POST[file]', folder='$_POST[folder]'");
close_conn();
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Paths settings have been saved.
</font>
</td>
</tr>
<?
if ($_POST[w]) {
?>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Next Step'>
<input type='hidden' name='v' value='colors'>
<input type='hidden' name='w' value='1'>
</td>
</form>
</tr>
<?
}
else {
?>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Paths Settings'>
<input type='hidden' name='v' value='paths'>
</td>
</form>
</tr>
<?
}
?>
</table>
<?
break;
case "savecolors":
open_conn();
mysql_query("UPDATE qtgp_settings SET title_color='$_POST[title_color]', text_color='$_POST[text_color]', link_color='$_POST[link_color]'");
close_conn();
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Color settings have been saved.
</font>
</td>
</tr>
<?
if ($_POST[w]) {
?>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Next Step'>
<input type='hidden' name='v' value='tradescript'>
<input type='hidden' name='w' value='1'>
</td>
</form>
</tr>
<?
}
else {
?>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Colors Settings'>
<input type='hidden' name='v' value='colors'>
</td>
</form>
</tr>
<?
}
?>
</table>
<?
break;
case "savetradescript":
open_conn();
mysql_query("UPDATE qtgp_settings SET trade_script='$_POST[trade_script]', trade_skim='$_POST[trade_skim]'");
close_conn();
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Trade Script settings have been saved.
</font>
</td>
</tr>
<?
if ($_POST[w]) {
?>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Next Step'>
<input type='hidden' name='v' value='layout'>
<input type='hidden' name='w' value='1'>
</td>
</form>
</tr>
<?
}
else {
?>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Trade Script Settings'>
<input type='hidden' name='v' value='tradescript'>
</td>
</form>
</tr>
<?
}
?>
</table>
<?
break;
case "savelayout":
open_conn();
mysql_query("UPDATE qtgp_settings SET max_link='$_POST[max_link]', link_word_count='$_POST[link_word_count]', title_font_size='$_POST[title_font_size]', text_font_size='$_POST[text_font_size]', link_font_size='$_POST[link_font_size]', title_font_face='$_POST[title_font_face]', text_font_face='$_POST[text_font_face]', link_font_face='$_POST[link_font_face]', left_design='$_POST[left_design]', right_design='$_POST[right_design]', picture_title='$_POST[picture_title]', movie_title='$_POST[movie_title]', divider='$_POST[divider]'");
close_conn();
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Layout settings have been saved.
</font>
</td>
</tr>
<?
if ($_POST[w]) {
?>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Next Step'>
<input type='hidden' name='v' value='template'>
<input type='hidden' name='w' value='1'>
</td>
</form>
</tr>
<?
}
else {
?>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Layout Settings'>
<input type='hidden' name='v' value='layout'>
</td>
</form>
</tr>
<?
}
?>
</table>
<?
break;
case "savetemplate": 
open_conn();
mysql_query("UPDATE qtgp_settings SET template='$_POST[template]'");
close_conn();
$i_template=@implode("",@file("http://www.quicktgp.com/templates/i$_POST[template].txt"));
$p_template=@implode("",@file("http://www.quicktgp.com/templates/p$_POST[template].txt"));
if ($i_template == "") { build_error("Index template settings could not be downloaded."); }
elseif ($p_template == "") { build_error("Page template settings could not be downloaded."); }
elseif (!file_exists("index_template.tmp")) { build_error("Your index template temp file does not exist."); }
elseif (!is_readable("index_template.tmp")) { build_error("Your index template temp file is not readable."); }
elseif (!is_writeable("index_template.tmp")) { build_error("Your index template temp file is not writeable."); }
elseif (!file_exists("page_template.tmp")) { build_error("Your page template temp file does not exist."); }
elseif (!is_readable("page_template.tmp")) { build_error("Your page template temp file is not readable."); }
elseif (!is_writeable("page_template.tmp")) { build_error("Your page template temp file is not writeable."); }
else {
$f=fopen("index_template.tmp","w");
fwrite($f,"$i_template");
fclose($f);
$f=fopen("page_template.tmp","w");
fwrite($f,"$p_template");
fclose($f);
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Template settings have been saved.
</font>
</td>
</tr>
<?
if ($_POST[w]) {
?>
<tr>
<form method=get action='qtgp_categories.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Next Step'>
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
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Template Settings'>
<input type='hidden' name='v' value='template'>
</td>
</form>
</tr>
<?
}
?>
</table>
<?
}
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
Select your path settings for your multi-page tgp.
</font>
</td>
</tr>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Path Settings'>
<input type='hidden' name='v' value='paths'>
</td>
</form>
</tr>
</table>
<br>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Select your color settings for your multi-page tgp.
</font>
</td>
</tr>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Color Settings'>
<input type='hidden' name='v' value='colors'>
</td>
</form>
</tr>
</table>
<br>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Select your tradescript settings for your multi-page tgp.
</font>
</td>
</tr>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Trade Script Settings'>
<input type='hidden' name='v' value='tradescript'>
</td>
</form>
</tr>
</table>
<br>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Select your layout settings for your multi-page tgp.
</font>
</td>
</tr>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Layout Settings'>
<input type='hidden' name='v' value='layout'>
</td>
</form>
</tr>
</table>
<br>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Select your template settings for your multi-page tgp.
</font>
</td>
</tr>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Template Settings'>
<input type='hidden' name='v' value='template'>
</td>
</form>
</tr>
</table>
<?
break;
case "paths":
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<form method=post action='qtgp_settings.php'>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>

<table border=0 cellspacing=1 cellpadding=2 width=70%>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Index File:</b></font><br>
</TD>
<td align=left>
<input type=text size=30 value='<?php echo"$file"; ?>' name='file'>
</td>
</tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Sub Categories Path:</b></font><br>
</TD>
<td align=left>
<input type=text size=30 value='<?php echo"$folder"; ?>' name='folder'>
</td>
</tr>
</table>

</td>
</tr>
<tr>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Save Paths Settings'>
<input type='hidden' name='v' value='savepaths'>
<?
if ($_GET[w]) {
?>
<input type='hidden' name='w' value='1'>
<?
}
?>
</td>
</form>
</tr>
</table>
<?
break;
case "colors":
$colorsarray=array("#000000","#CCCCCC","#FFFFFF","#CC0000","#00CC00","#0000CC","#FFCC00","#FF6600","#FFFFEE","#800080","#666666","#3399FF");
$max=count($colorsarray);
$i=1;
$title_color_select.="<table border=0 cellspacing=2 cellpadding=2 width=100%>";
for ($x=0; $x<$max; $x++) {
if ($i == 1) { $title_color_select.="<tr>"; }
if ($title_color == $colorsarray[$x]) { $title_color_select.="<td width=16%><table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=#000000><tr><td bgcolor=$colorsarray[$x] align=center><input type=radio name=title_color value=$colorsarray[$x] CHECKED></td></tr></table></td>"; }
else { $title_color_select.="<td width=16%><table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=#000000><tr><td bgcolor=$colorsarray[$x] align=center><input type=radio name=title_color value=$colorsarray[$x]></td></tr></table></td>"; }
if ($i == 6) { $title_color_select.="</tr>"; $i=0; }
$i++;
}
$title_color_select.="</table>";
$max=count($colorsarray);
$i=1;
$text_color_select.="<table border=0 cellspacing=2 cellpadding=2 width=100%>";
for ($x=0; $x<$max; $x++) {
if ($i == 1) { $text_color_select.="<tr>"; }
if ($text_color == $colorsarray[$x]) { $text_color_select.="<td width=16%><table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=#000000><tr><td bgcolor=$colorsarray[$x] align=center><input type=radio name=text_color value=$colorsarray[$x] CHECKED></td></tr></table></td>"; }
else { $text_color_select.="<td width=16%><table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=#000000><tr><td bgcolor=$colorsarray[$x] align=center><input type=radio name=text_color value=$colorsarray[$x]></td></tr></table></td>"; }
if ($i == 6) { $text_color_select.="</tr>"; $i=0; }
$i++;
}
$text_color_select.="</table>";
$max=count($colorsarray);
$i=1;
$link_color_select.="<table border=0 cellspacing=2 cellpadding=2 width=100%>";
for ($x=0; $x<$max; $x++) {
if ($i == 1) { $link_color_select.="<tr>"; }
if ($link_color == $colorsarray[$x]) { $link_color_select.="<td width=16%><table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=#000000><tr><td bgcolor=$colorsarray[$x] align=center><input type=radio name=link_color value=$colorsarray[$x] CHECKED></td></tr></table></td>"; }
else { $link_color_select.="<td width=16%><table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=#000000><tr><td bgcolor=$colorsarray[$x] align=center><input type=radio name=link_color value=$colorsarray[$x]></td></tr></table></td>"; }
if ($i == 6) { $link_color_select.="</tr>"; $i=0; }
$i++;
}
$link_color_select.="</table>";
?>
<table border=0 cellspacing=1 cellpadding=2 width=70% bgcolor=#CCCCCC>
<tr>
<form method=post action='qtgp_settings.php'>
<td bgcolor=#FFFFFF align=center valign=top width=100%>

<table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#EFEFEF align=center>
<font size=2 face='verdana,arial,helvetica'><b>TITLE COLOR</b></font>
</td>
</tr>
<tr>
<td bgcolor=#FFFFFF align=center>
<?echo"$title_color_select";?>
</td>
</tr>
</table>
<br>
<table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#EFEFEF align=center>
<font size=2 face='verdana,arial,helvetica'><b>TEXT COLOR</b></font>
</td>
</tr>
<tr>
<td bgcolor=#FFFFFF align=center>
<?echo"$text_color_select";?>
</td>
</tr>
</table>
<br>
<table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#EFEFEF align=center>
<font size=2 face='verdana,arial,helvetica'><b>LINK COLOR</b></font>
</td>
</tr>
<tr>
<td bgcolor=#FFFFFF align=center>
<?echo"$link_color_select";?>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Save Color Settings'>
<input type='hidden' name='v' value='savecolors'>
<?
if ($_GET[w]) {
?>
<input type='hidden' name='w' value='1'>
<?
}
?>
</td>
</form>
</tr>
</table>
<?
break;
case "tradescript":
$trade_scriptarray=array("TurboTrafficTrader","ePowerTrader","CJUltra","UCJ","LanasPowerTrader","Larsen","PHPTrade","RB4","TrafficDrive","TM3","AWGTrade","Scorpion","ArrowTrader");
$trade_script_select.="<select name=trade_script>";
for ($x=0; $x<count($trade_scriptarray); $x++) {
if ($trade_script == $trade_scriptarray[$x]) { $trade_script_select.="<option value='$trade_scriptarray[$x]' SELECTED>$trade_scriptarray[$x]</option>"; }
else { $trade_script_select.="<option value='$trade_scriptarray[$x]'>$trade_scriptarray[$x]</option>"; }
}
$trade_script_select.="</select>";
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<form method=post action='qtgp_settings.php'>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>

<table border=0 cellspacing=1 cellpadding=2 width=70%>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Trade Script</b></font><br>
</TD>
<td align=left>
<?echo"$trade_script_select";?>
</td>
</tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>% To Galleries Skim</b></font><br>
</TD>
<td align=left>
<input type=text size=3 maxlength=3 name=trade_skim value="<?echo"$trade_skim";?>">
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Save Tradescript Settings'>
<input type='hidden' name='v' value='savetradescript'>
<?
if ($_GET[w]) {
?>
<input type='hidden' name='w' value='1'>
<?
}
?>
</td>
</form>
</tr>
</table>
<?
break;
case "layout":
$fontsarray=array("arial","comic sans ms","verdana");
$max=count($fontsarray);
$title_font_face_select.="<font size=2 face='verdana,arial,helvetica'><b>Style:</b></font> <select name=title_font_face>";
$text_font_face_select.="<font size=2 face='verdana,arial,helvetica'><b>Style:</b></font> <select name=text_font_face>";
$link_font_face_select.="<font size=2 face='verdana,arial,helvetica'><b>Style:</b></font> <select name=link_font_face>";
for ($x=0; $x<$max; $x++) {
if ($title_font_face == $fontsarray[$x]) { $title_font_face_select.="<option value='$fontsarray[$x]' SELECTED>$fontsarray[$x]</option>"; }
else { $title_font_face_select.="<option value='$fontsarray[$x]'>$fontsarray[$x]</option>"; }
if ($text_font_face == $fontsarray[$x]) { $text_font_face_select.="<option value='$fontsarray[$x]' SELECTED>$fontsarray[$x]</option>"; }
else { $text_font_face_select.="<option value='$fontsarray[$x]'>$fontsarray[$x]</option>"; }
if ($link_font_face == $fontsarray[$x]) { $link_font_face_select.="<option value='$fontsarray[$x]' SELECTED>$fontsarray[$x]</option>"; }
else { $link_font_face_select.="<option value='$fontsarray[$x]'>$fontsarray[$x]</option>"; }
}
$title_font_face_select.="</select>";
$text_font_face_select.="</select>";
$link_font_face_select.="</select>";
$title_font_size_select.="<font size=2 face='verdana,arial,helvetica'><b>Size:</b></font> <select name=title_font_size>";
$text_font_size_select.="<font size=2 face='verdana,arial,helvetica'><b>Size:</b></font> <select name=text_font_size>";
$link_font_size_select.="<font size=2 face='verdana,arial,helvetica'><b>Size:</b></font> <select name=link_font_size>";
for ($x=1; $x<5; $x++) {
if ($title_font_size == $x) { $title_font_size_select.="<option value='$x' SELECTED>$x</option>"; }
else { $title_font_size_select.="<option value='$x'>$x</option>"; }
if ($text_font_size == $x) { $text_font_size_select.="<option value='$x' SELECTED>$x</option>"; }
else { $text_font_size_select.="<option value='$x'>$x</option>"; }
if ($link_font_size == $x) { $link_font_size_select.="<option value='$x' SELECTED>$x</option>"; }
else { $link_font_size_select.="<option value='$x'>$x</option>"; }
}
$title_font_size_select.="</select>";
$text_font_size_select.="</select>";
$link_font_size_select.="</select>";
$link_word_count_select.="<select name=link_word_count>";
for ($x=1; $x<3; $x++) {
if ($link_word_count == $x) { $link_word_count_select.="<option value='$x' SELECTED>$x</option>"; }
else { $link_word_count_select.="<option value='$x'>$x</option>"; }
}
$link_word_count_select.="</select>";
$left_designarray=array("-",".:",".",":","[","~","*","&lt;","+","x","(","`");
$right_designarray=array("-",":.",".",":","]","~","*","&gt;","+","x",")","`");
$left_design_select.="<select name=left_design>";
$right_design_select.="<select name=right_design>";
for ($x=0; $x<count($left_designarray); $x++) {
if ($left_design == $left_designarray[$x]) { $left_design_select.="<option value='$left_designarray[$x]' SELECTED>$left_designarray[$x]</option>"; }
else { $left_design_select.="<option value='$left_designarray[$x]'>$left_designarray[$x]</option>"; }
if ($right_design == $right_designarray[$x]) { $right_design_select.="<option value='$right_designarray[$x]' SELECTED>$right_designarray[$x]</option>"; }
else { $right_design_select.="<option value='$right_designarray[$x]'>$right_designarray[$x]</option>"; }
}
$left_design_select.="</select>";
$right_design_select.="</select>";
$picture_titlearray=array("Pictures","Pics","Images","Imgs");
$movie_titlearray=array("Movies","Movs","Videos","Vids");
$picture_title_select.="<select name=picture_title>";
$movie_title_select.="<select name=movie_title>";
for ($x=0; $x<count($movie_titlearray); $x++) {
if ($picture_title == $picture_titlearray[$x]) { $picture_title_select.="<option value='$picture_titlearray[$x]' SELECTED>$picture_titlearray[$x]</option>"; }
else { $picture_title_select.="<option value='$picture_titlearray[$x]'>$picture_titlearray[$x]</option>"; }
if ($movie_title == $movie_titlearray[$x]) { $movie_title_select.="<option value='$movie_titlearray[$x]' SELECTED>$movie_titlearray[$x]</option>"; }
else { $movie_title_select.="<option value='$movie_titlearray[$x]'>$movie_titlearray[$x]</option>"; }
}
$picture_title_select.="</select>";
$movie_title_select.="</select>";
$dividerarray=array("-",",","/",".");
$divider_select.="<select name=divider>";
for ($x=0; $x<count($dividerarray); $x++) {
if ($divider == $dividerarray[$x]) { $divider_select.="<option value='$dividerarray[$x]' SELECTED>$dividerarray[$x]</option>"; }
else { $divider_select.="<option value='$dividerarray[$x]'>$dividerarray[$x]</option>"; }
}
$divider_select.="</select>";
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<form method=post action='qtgp_settings.php'>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>

<table border=0 cellspacing=1 cellpadding=2 width=70%>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Title Font Properties</b></font><br>
</TD>
<td align=left>
<?echo"$title_font_size_select";?> <?echo"$title_font_face_select";?>
</td>
</tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Text Font Properties</b></font><br>
</TD>
<td align=left>
<?echo"$text_font_size_select";?> <?echo"$text_font_face_select";?>
</td>
</tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Link Font Properties</b></font><br>
</TD>
<td align=left>
<?echo"$link_font_size_select";?> <?echo"$link_font_face_select";?>
</td>
</tr>
<tr><td colspan=2 bgcolor=#CCCCCC height=1><spacer type=square width=1 height=1></td></tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Categories</b></font><br>
</TD>
<td align=left>
<?echo"$link_word_count_select";?> <font size=2 face='verdana,arial,helvetica'><b>Words In Name</b></font>
</td>
</tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Max Links Per Category</b></font><br>
</TD>
<td align=left>
<input type=text size=4 maxlength=4 name=max_link value="<?echo"$max_link";?>">
</td>
</tr>
<tr><td colspan=2 bgcolor=#CCCCCC height=1><spacer type=square width=1 height=1></td></tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Letter Left Design</b></font><br>
</TD>
<td align=left>
<?echo"$left_design_select";?> <font size=2 face='verdana,arial,helvetica'><b><?echo"$left_design";?> A</b></font>
</td>
</tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Letter Right Design</b></font><br>
</TD>
<td align=left>
<?echo"$right_design_select";?> <font size=2 face='verdana,arial,helvetica'><b>A <?echo"$right_design";?></b></font>
</td>
</tr>
<tr><td colspan=2 bgcolor=#CCCCCC height=1><spacer type=square width=1 height=1></td></tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Title for Link to Pictures</b></font><br>
</TD>
<td align=left>
<?echo"$picture_title_select";?>
</td>
</tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Title for Link to Movies</b></font><br>
</TD>
<td align=left>
<?echo"$movie_title_select";?>
</td>
</tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Title Divider</b></font><br>
</TD>
<td align=left>
<?echo"$divider_select";?>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Save Layout Settings'>
<input type='hidden' name='v' value='savelayout'>
<?
if ($_GET[w]) {
?>
<input type='hidden' name='w' value='1'>
<?
}
?>
</td>
</form>
</tr>
</table>
<?
break;
case "template":
$contents=@implode("",@file("http://www.quicktgp.com/templates/template.txt"));
$templatearray=explode('|',$contents);
array_pop($templatearray);
for ($x=0; $x<count($templatearray); $x++) {
$dtemplate=explode('-',$templatearray[$x]);
if ($template == $templatearray[$x]) { $template_select.="<input type='radio' name='template' value='$templatearray[$x]' CHECKED> <a href='http://www.quicktgp.com/templates/$templatearray[$x].html' target='_blank'>Design ID #$dtemplate[0]</a><br>"; }
else { $template_select.="<input type='radio' name='template' value='$templatearray[$x]'> <a href='http://www.quicktgp.com/templates/$templatearray[$x].html' target='_blank'>Design ID #$dtemplate[0]</a><br>"; }
}
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<form method=post action='qtgp_settings.php'>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>

<table border=0 cellspacing=1 cellpadding=2 width=70%>
<tr>
<td><b></b></td>
<td align=left><font size=2 face='verdana,arial,helvetica'><b>Click to Preview</b></font></td>
</tr>
<tr>
<td align=LEFT>
<font size=2 face='verdana,arial,helvetica'><b>Index/Subpage Template</b></font><br>
</TD>
<td align=left>
<font size=2 face='verdana,arial,helvetica'>
<?echo"$template_select";?>
</font>
</td>
</tr>
</table>

</td>
</tr>
<tr>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Save Template'>
<input type='hidden' name='v' value='savetemplate'>
<?
if ($_GET[w]) {
?>
<input type='hidden' name='w' value='1'>
<?
}
?>
</td>
</form>
</tr>
</table>
<?
break;
}
}


function build_error($msg) {
print <<<HTML
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Your multi-page template could not be saved.<br><br>

<font color=#CC0000>Reason: $msg</font><br><br>

Please fix this before trying to save your template again.
</font>
</td>
</tr>
<tr>
<form method=get action='qtgp_settings.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Template Settings'>
<input type='hidden' name='v' value='template'>
</td>
</form>
</tr>
</table>
HTML;
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