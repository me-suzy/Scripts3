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
ini_set('max_execution_time','3000');
ignore_user_abort(true);
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
	  <font color="#000000">|</font> <a href="qtgp_build.php">Build</a>
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
switch($_POST["v"]) {
case "":
?>

<?
if (!file_exists("../$file")) { build_error("Your index file does not exist."); }
elseif (!is_readable("../$file")) { build_error("Your index file is not readable."); }
elseif (!is_writeable("../$file")) { build_error("Your index file is not writeable."); }
elseif (!file_exists("../index_template.qtgp")) { build_error("Your index template file does not exist."); }
elseif (!is_readable("../index_template.qtgp")) { build_error("Your index template file is not readable."); }
elseif (!is_writeable("../index_template.qtgp")) { build_error("Your index template file is not writeable."); }
elseif (!file_exists("../page_template.qtgp")) { build_error("Your page template file does not exist."); }
elseif (!is_readable("../page_template.qtgp")) { build_error("Your page template file is not readable."); }
elseif (!is_writeable("../page_template.qtgp")) { build_error("Your page template file is not writeable."); }
elseif (!file_exists("../$folder")) { build_error("Your category folder does not exist."); }
elseif (!is_readable("../$folder")) { build_error("Your category folder is not readable."); }
elseif (!is_writeable("../$folder")) { build_error("Your category folder is not writeable."); }
else {
$f=fopen("../index_template.qtgp","r");
$ct=fread($f,filesize("../index_template.qtgp"));
fclose($f);
if (!strstr($ct,"[qtgp_categories]")) { build_error("You do not have [qtgp_categories] in your index template file."); }
else {
if ($trade_script == "TurboTrafficTrader") { $outurl="/ttt-out.php?url=[trade_url]&pct=[trade_skim]"; }
if ($trade_script == "ePowerTrader") { $outurl="/out.php?url=[trade_url]&pct=[trade_skim]"; }
if ($trade_script == "CJUltra") { $outurl="/out.php?url=[trade_url]&s=[trade_skim]"; }
if ($trade_script == "UCJ") { $outurl="/cgi-bin/ucj/c.cgi?url=[trade_url]&p=[trade_skim]"; }
if ($trade_script == "LanasPowerTrader") { $outurl="/out.php?url=[trade_url]&s=[trade_skim]"; }
if ($trade_script == "Larsen") { $outurl="/cgi-bin/lpt/lptout.cgi?url=[trade_url]&s=[trade_skim]"; }
if ($trade_script == "PHPTrade") { $outurl="/out.php?url=[trade_url]&pct=[trade_skim]"; }
if ($trade_script == "RB4") { $outurl="/cgi-bin/rb4/cout.cgi?gll=[trade_skim]/index.php|http://www.galleryspots.com/freepics/'+code+'"; }
if ($trade_script == "TrafficDrive") { $outurl="/o.php?url=[trade_url]"; }
if ($trade_script == "TM3") { $outurl="/cgi-bin/tm3/l?u=[trade_url]&p=[trade_skim]"; }
if ($trade_script == "AWGTrade") { $outurl="/cgi-bin/awgtrade/out.cgi?gal=[trade_skim]&galurl=[trade_url]"; }
if ($trade_script == "Scorpion") { $outurl="/cgi-bin/s3/out.cgi?surl=[trade_url]|p=[trade_skim]"; }
if ($trade_script == "ArrowTrader") { $outurl="/cgi-bin/at/out.cgi?s=[trade_skim]&u=[trade_url]"; }
$outurl=str_replace("[trade_skim]","$trade_skim",$outurl);
$templates=explode('-',$template);
if ($templates[1] == "1") { $categorycolumns=4; $linkcolumns=2; }
if ($templates[1] == "2") { $categorycolumns=3; $linkcolumns=2; }
if ($templates[1] == "3") { $categorycolumns=2; $linkcolumns=2; }
if ($templates[1] == "4") { $categorycolumns=1; $linkcolumns=2; }
if ($templates[1] == "5") { $categorycolumns=4; $linkcolumns=1; }
if ($templates[1] == "6") { $categorycolumns=3; $linkcolumns=1; }
if ($templates[1] == "7") { $categorycolumns=2; $linkcolumns=1; }
if ($templates[1] == "8") { $categorycolumns=1; $linkcolumns=1; }
if ($templates[1] == "9") { $categorycolumns=1; $linkcolumns=2; }
if ($templates[1] == "10") { $categorycolumns=1; $linkcolumns=1; }
if ($templates[1] == "11") { $categorycolumns=3; $linkcolumns=2; }
if ($templates[1] == "12") { $categorycolumns=2; $linkcolumns=2; }
if ($templates[1] == "13") { $categorycolumns=1; $linkcolumns=2; }
if ($templates[1] == "14") { $categorycolumns=3; $linkcolumns=1; }
if ($templates[1] == "15") { $categorycolumns=2; $linkcolumns=1; }
if ($templates[1] == "16") { $categorycolumns=1; $linkcolumns=1; }
if ($templates[1] == "17") { $categorycolumns=2; $linkcolumns=2; }
if ($templates[1] == "18") { $categorycolumns=2; $linkcolumns=1; }
################################################################################################
open_conn();
$res=mysql_query("select * from qtgp_categories where status='1'");
close_conn();
################################################################################################
while($temp=mysql_fetch_array($res)) {
$vtemp=explode('_',$temp[0]);
if (count($vtemp) != (2+$link_word_count) || strlen($temp[0]) < 5) { }
else {
$gx=strlen($temp[0]);
$tempcomp1=substr($temp[0],0,3);
$tempcomp2=substr($temp[0],4,$gx);
$tempcomp3=explode('_',$tempcomp1);
if ($tempcomp3[0] == "p") { $tc1="0"; }
if ($tempcomp3[0] == "m") { $tc1="1"; }
if ($tempcomp3[0] == "l") { $tc1="2"; }
if ($tempcomp3[0] == "c") { $tc1="3"; }
if ($tempcomp3[1] == "s") { $tc2="0"; }
if ($tempcomp3[1] == "l") { $tc2="1"; $tc4="lesbian^"; }
if ($tempcomp3[1] == "g") { $tc2="2"; $tc4="gay^"; }
if ($tempcomp3[1] == "t") { $tc2="3"; $tc4="tranny^"; }
$realcategories[]="$tc4$tempcomp2$tc1$tc2";
unset($tc4);
$mx++;
} 
}
unset($categories);
for ($x=0; $x<count($realcategories); $x++) {
$categories[]=chop($realcategories[$x]);
}
sort($categories);
reset($categories);
unset($realcategories);
for ($x=0; $x<count($categories); $x++) {
$ta=strlen($categories[$x])-2;
$tb=strlen($categories[$x])-1;
$tempcomp1=substr($categories[$x],0,$ta);
$tempcomp2=$categories[$x]{$ta};
$tempcomp3=$categories[$x]{$tb};
if ($tempcomp2 == "0") { $tc1="p"; }
if ($tempcomp2 == "1") { $tc1="m"; }
if ($tempcomp2 == "2") { $tc1="l"; }
if ($tempcomp2 == "3") { $tc1="c"; }
if ($tempcomp3 == "0") { $tc2="s"; }
if ($tempcomp3 == "1") { $tc2="l"; }
if ($tempcomp3 == "2") { $tc2="g"; }
if ($tempcomp3 == "3") { $tc2="t"; }
$tempcomp=$tc1."_".$tc2."_".$tempcomp1;
$realcategories[]="$tempcomp";
$tempcomp="";
}
unset($categories);
for ($x=0; $x<count($realcategories); $x++) {
$nichecategories[]="$realcategories[$x]";
}
unset($realcategories);
################################################################################################
for ($x=0; $x<count($nichecategories); $x++) {
$y=$x+1;

$displayname="";
$tempname=substr($nichecategories[$x],4,strlen($nichecategories[$x]));
$splitname=explode('_',$tempname);
if ($nichecategories[$x]{2} == "l") { $splitname=str_replace("lesbian^","lesbian",$splitname); }
if ($nichecategories[$x]{2} == "g") { $splitname=str_replace("gay^","gay",$splitname); }
if ($nichecategories[$x]{2} == "t") { $splitname=str_replace("tranny^","tranny",$splitname); }
for($m=0; $m<count($splitname); $m++) { $tempword=ucfirst($splitname[$m]); $displayname.="$tempword "; }
$displayname=substr($displayname,0,strlen($displayname)-1);
$categoryname="$displayname";
$clicklink=$nichecategories[$x];
if ($nichecategories[$x]{2} == "l") { $clicklink=str_replace("lesbian^","",$clicklink); }
if ($nichecategories[$x]{2} == "g") { $clicklink=str_replace("gay^","",$clicklink); }
if ($nichecategories[$x]{2} == "t") { $clicklink=str_replace("tranny^","",$clicklink); }
#################################################################################################
if ($templates[1] == 1 || $templates[1] == 2 || $templates[1] == 3 || $templates[1] == 4 || $templates[1] == 5 || $templates[1] == 6 || $templates[1] == 7 || $templates[1] == 8) {
if (substr($lastcategory,2,strlen($lastcategory)) == substr($nichecategories[$x],2,strlen($nichecategories[$x]))) { }
else { $vx=$nichecategories[$x]{4}; $categorylist[$cav].="$vx^<b>$categoryname</b>: "; }
if (substr($lastcategory,2,strlen($lastcategory)) == substr($nichecategories[$x],2,strlen($nichecategories[$x]))) { if ($nichecategories[$x]{0} == "p") { $categorylist[$cav].="$divider <a href='/categories/$clicklink.shtml' target='_blank'><b>$picture_title</b></a>"; } if ($nichecategories[$x]{0} == "m") { $categorylist[$cav].="$divider <a href='/categories/$clicklink.shtml' target='_blank'><b>$movie_title</b></a>"; } }
else { if ($nichecategories[$x]{0} == "p") { $categorylist[$cav].="<a href='/$folder/$clicklink.shtml' target='_blank'><b>$picture_title</b></a> "; } if ($nichecategories[$x]{0} == "m") { $categorylist[$cav].="<a href='/$folder/$clicklink.shtml' target='_blank'><b>$movie_title</b></a> "; } }
if (substr($nichecategories[$x],2,strlen($nichecategories[$x])) != substr($nichecategories[$y],2,strlen($nichecategories[$y]))) { $categorylist[$cav].="<br>\n"; $cav++; }
$lastcategory=$nichecategories[$x];
}
elseif ($templates[1] == "9" || $templates[1] == "10") {
$rowformat="<tr><td width=48% valign=top align=right><font size=[text_font_size] face='[text_font_face]'>[pictureoutput]</font></td><td width=1% valign=top>&nbsp;</td><td width=2% bgcolor='[title_color]' align=center valign=top><font size=[title_font_size] face='[title_font_face]' color=#FFFFFF><b>[current_letter]</b></font></td><td width=1% valign=top>&nbsp;</td><td width=48% valign=top align=left><font size=[text_font_size] face='[text_font_face]'>[movieoutput]</font></td></tr>";
if($current_letter!=strtoupper($nichecategories[$x]{4})) { if ($pictureoutput == "" || $movieoutput == "") { } else { $rowoutput=str_replace('[current_letter]',$current_letter,$rowoutput); $rowoutput=str_replace('[pictureoutput]',$pictureoutput,$rowoutput); $rowoutput=str_replace('[movieoutput]',$movieoutput,$rowoutput); $rowoutput=str_replace('[title_font_face]',$title_font_face,$rowoutput); $rowoutput=str_replace('[title_font_size]',$title_font_size,$rowoutput); $rowoutput=str_replace('[title_color]',$title_color,$rowoutput); $rowoutput=str_replace('[text_font_face]',$text_font_face,$rowoutput); $rowoutput=str_replace('[text_font_size]',$text_font_size,$rowoutput); $categoryoutput[1].="$rowoutput"; } unset($pictureoutput); unset($movieoutput); $rowoutput=$rowformat; $current_letter=strtoupper($nichecategories[$x]{4}); }
else { if ($nichecategories[$x]{0} == "p") { $pictureoutput.="<a href='/$folder/$clicklink.shtml' target='_blank'>$categoryname</a>, \n"; } elseif ($nichecategories[$x]{0} == "m") { $movieoutput.="<a href='/$folder/$clicklink.shtml' target='_blank'>$categoryname</a>, \n"; } }
}
elseif ($templates[1] == "11" || $templates[1] == "12" || $templates[1] == "13" || $templates[1] == "14" || $templates[1] == "15" || $templates[1] == "16") {
if (substr($lastcategory,4,strlen($lastcategory)) == substr($nichecategories[$x],4,strlen($nichecategories[$x]))) { }
else { 
$vx=$nichecategories[$x]{4}; $categorylist[$cv].="$vx^<tr><td width=40% align=left><font size=[text_font_size] face='[text_font_face]' color='[text_color]'><b>$categoryname</b></font></td>";
}
if (substr($lastcategory,4,strlen($lastcategory)) == substr($nichecategories[$x],4,strlen($nichecategories[$x]))) { 
if ($nichecategories[$x]{0} == "p") { $categorylist[$cv].="&nbsp;&nbsp;<a href='/$folder/$clicklink.shtml' target='_blank'><b>$picture_title</b></a>"; }
if ($nichecategories[$x]{0} == "m") { $categorylist[$cv].="&nbsp;&nbsp;<a href='/$folder/$clicklink.shtml' target='_blank'><b>$movie_title</b></a>"; }
}
else {
if ($nichecategories[$x]{0} == "p") { $categorylist[$cv].="<td width=60% align=right><font size=[text_font_size] face='[text_font_face]'><a href='/$folder/$clicklink.shtml' target='_blank'><b>$picture_title</b></a> "; }
if ($nichecategories[$x]{0} == "m") { $categorylist[$cv].="<td width=60% align=right><font size=[text_font_size] face='[text_font_face]'><a href='/$folder/$clicklink.shtml' target='_blank'><b>$movie_title</b></a> "; }
}
if (substr($nichecategories[$x],4,strlen($nichecategories[$x])) != substr($nichecategories[$y],4,strlen($nichecategories[$y]))) { $categorylist[$cv].="</font></td></tr>"; $cv++; }
$lastcategory=$nichecategories[$x];
}
elseif ($templates[1] == "17" || $templates[1] == "18") {
if ($nichecategories[$x]{0} == "p") {
if($current_letter1!=strtoupper($nichecategories[$x]{4})) {
$current_letter1=strtoupper($nichecategories[$x]{4});
$pictureoutput.="<br><font size=$title_font_size face='$title_font_face' color='$title_color'><b>$current_letter1</b></font><br>";
}
$pictureoutput.="<a href='/$folder/$clicklink.shtml' target='_blank'>$categoryname</a>, \n"; 
}
else {
if($current_letter2!=strtoupper($nichecategories[$x]{4})) {
$current_letter2=strtoupper($nichecategories[$x]{4});
$movieoutput.="<br><font size=$title_font_size face='$title_font_face' color='$title_color'><b>$current_letter2</b></font><br>";
}
$movieoutput.="<a href='/$folder/$clicklink.shtml' target='_blank'>$categoryname</a>, \n"; 
}
$categoryoutput[1]="$pictureoutput";
$categoryoutput[2]="$movieoutput";
}
}
if ($templates[1] == "1" || $templates[1] == "2" || $templates[1] == "3" || $templates[1] == "4" || $templates[1] == "5" || $templates[1] == "6" || $templates[1] == "7" || $templates[1] == "8") {
$percolumn=round((count($categorylist)/$categorycolumns),0);
$it=count($categorylist);
$y=0;
$cv=1;
$current_letter="";
for ($x=0; $x<count($categorylist); $x++) {
$temp=explode('^',$categorylist[$x]);
if ($percolumn == $y) { $cv++; $y=0; }
if($current_letter!=strtoupper($temp[0])) {
$current_letter=strtoupper($temp[0]); 
$categoryoutput[$cv].="<font size='$title_font_size' face='$title_font_face' color='$title_color'><b>$current_letter</b></font><br>"; 
}
$categoryoutput[$cv].="$temp[1]";
$y++;
}
$f=fopen("index_template.tmp","r");
$ct=fread($f,filesize("index_template.tmp"));
fclose($f);
$ct=str_replace('[categoryoutput1]',$categoryoutput[1],$ct);
$ct=str_replace('[categoryoutput2]',$categoryoutput[2],$ct);
$ct=str_replace('[categoryoutput3]',$categoryoutput[3],$ct);
$ct=str_replace('[categoryoutput4]',$categoryoutput[4],$ct);
$ct=str_replace('[text_font_face]',$text_font_face,$ct);
$ct=str_replace('[text_font_size]',$text_font_size,$ct);
}
##
elseif ($templates[1] == "9" || $templates[1] == "10") {
$f=fopen("index_template.tmp","r");
$ct=fread($f,filesize("index_template.tmp"));
fclose($f);
$ct=str_replace('[categoryoutput1]',$categoryoutput[1],$ct);
$ct=str_replace('[title_font_face]',$title_font_face,$ct);
$ct=str_replace('[title_font_size]',$title_font_size,$ct);
$ct=str_replace('[title_color]',$title_color,$ct);
}
##
elseif ($templates[1] == "11" || $templates[1] == "12" || $templates[1] == "13" || $templates[1] == "14" || $templates[1] == "15" || $templates[1] == "16") {
$percolumn=round((count($categorylist)/$categorycolumns),0);
$it=count($categorylist);
$y=0;
$cv=1;
$current_letter="";
for ($x=0; $x<count($categorylist); $x++) {
$temp=explode('^',$categorylist[$x]);
if ($percolumn == $y) { $cv++; $y=0; }
if($current_letter!=strtoupper($temp[0])) {
$current_letter=strtoupper($temp[0]); 
$categoryoutput[$cv].="<tr><td colspan=2 align=left><font size='$title_font_size' face='$title_font_face' color='$title_color'><b>$current_letter</b></font></td>"; 
}
$categoryoutput[$cv].="$temp[1]";
$y++;
}
$f=fopen("index_template.tmp","r");
$ct=fread($f,filesize("index_template.tmp"));
fclose($f);
$ct=str_replace('[categoryoutput1]',$categoryoutput[1],$ct);
$ct=str_replace('[categoryoutput2]',$categoryoutput[2],$ct);
$ct=str_replace('[categoryoutput3]',$categoryoutput[3],$ct);
$ct=str_replace('[title_font_face]',$title_font_face,$ct);
$ct=str_replace('[title_font_size]',$title_font_size,$ct);
$ct=str_replace('[title_color]',$title_color,$ct);
$ct=str_replace('[text_font_face]',$text_font_face,$ct);
$ct=str_replace('[text_font_size]',$text_font_size,$ct);
$ct=str_replace('[text_color]',$text_color,$ct);
}
elseif ($templates[1] == "17" || $templates[1] == "18") {
$f=fopen("index_template.tmp","r");
$ct=fread($f,filesize("index_template.tmp"));
fclose($f);
$ct=str_replace('[categoryoutput1]',$categoryoutput[1],$ct);
$ct=str_replace('[categoryoutput2]',$categoryoutput[2],$ct);
$ct=str_replace('[title_font_face]',$title_font_face,$ct);
$ct=str_replace('[title_font_size]',$title_font_size,$ct);
$ct=str_replace('[title_color]',$title_color,$ct);
$ct=str_replace('[text_font_face]',$text_font_face,$ct);
$ct=str_replace('[text_font_size]',$text_font_size,$ct);
$ct=str_replace('[text_color]',$text_color,$ct);
}
################################################################################################
$f=fopen("../index_template.qtgp","r");
$ct2=fread($f,filesize("../index_template.qtgp"));
fclose($f);
$ct2=str_replace('[qtgp_categories]',$ct,$ct2);
$f=fopen("../$file","w");
fwrite($f,"$ct2");
fclose($f);
unset($categorylist);
#################################################################################################
for ($x=0; $x<count($nichecategories); $x++) {
if ($nichecategories[$x]{2} == "l") { $nichecategories[$x]=str_replace("lesbian^","",$nichecategories[$x]); }
if ($nichecategories[$x]{2} == "g") { $nichecategories[$x]=str_replace("gay^","",$nichecategories[$x]); }
if ($nichecategories[$x]{2} == "t") { $nichecategories[$x]=str_replace("tranny^","",$nichecategories[$x]); }
$categories[]="$nichecategories[$x]";
}
#################################################################################################
for ($x=0; $x<count($categories); $x++) {
unset($subcategories);
$displayname="";
$temp=explode('_',$categories[$x]);
$currentcategory=substr($categories[$x],4,strlen($categories[$x]));
$currentfile=$categories[$x];
$splitname=substr($currentfile,4,strlen($currentfile));
$tempname=explode('_',$splitname);
for($m=0; $m<count($tempname); $m++) { $tempword=ucfirst($tempname[$m]); $displayname.="$tempword "; }
$displayname=substr($displayname,0,strlen($displayname)-1);
$nichename=strtoupper($displayname);
$matchtype=substr($currentfile,0,3);
if ($temp[0] == "p") { $nichename.=" PICTURE"; } else { $nichename.=" MOVIE"; }
if ($temp[1] == "g") { $nichename="GAY $nichename"; }
if ($temp[1] == "l") { $nichename="LESBIAN $nichename"; }
if ($temp[1] == "t") { $nichename="TRANNY $nichename"; }
################################################################################################
open_conn();
$res=mysql_query("select * from qtgp_categories where (name LIKE '$matchtype%$currentcategory%') ORDER by name");
close_conn();
$valid=mysql_num_rows($res)-1;
################################################################################################
while($temp=mysql_fetch_array($res)) {
$subcategories[]="$temp[0]";
}
for ($y=0; $y<$max_link; $y++) {
if ($valid == 1) { $rn[$y]="0"; }
else {
$cn=rand(0,$valid);
$rn[$y]="$cn"; 
}
}
for ($y=0; $y<$max_link; $y++) {
$tn=$rn[$y];
$categorylist[$y]="$subcategories[$tn]";
}
$percolumn=round(($max_link/$linkcolumns),0);
$lv=1;
$l=1;
for ($y=0; $y<$max_link; $y++) {
$displayname="";
$linkname=$categorylist[$y];
$tempname=substr($linkname,4,strlen($linkname));
$splitname=explode('_',$tempname);
for($m=0; $m<count($splitname); $m++) {
$tempword=ucfirst($splitname[$m]);
$displayname.="$tempword ";
}
$displayname=substr($displayname,0,strlen($displayname)-1);
$categoryname="$displayname";
$categoryname=strtolower($categoryname);
$categoryname=ucfirst($categoryname);
$linkformat=str_replace("[trade_url]","http://www.galleryspots.com/freepics/$linkname/index.php",$outurl);
$linkoutput[$lv].= "<script language='javascript'>dd();</script><a href='$linkformat' target='_blank'>$categoryname</a><br>";
if ($l == $percolumn) {
$lv++;
$l=0;
}
$l++;
}
$f=fopen("page_template.tmp","r");
$ct=fread($f,filesize("page_template.tmp"));
fclose($f);
$ct=str_replace("[linkoutput1]",$linkoutput[1],$ct);
$ct=str_replace("[linkoutput2]",$linkoutput[2],$ct);
$ct=str_replace("[nichename]",$nichename,$ct);
$ct=str_replace("[title_font_size]",$title_font_size,$ct);
$ct=str_replace("[title_font_color]",$title_font_color,$ct);
$ct=str_replace("[title_font_face]",$title_font_face,$ct);
$ct=str_replace("[link_font_size]",$link_font_size,$ct);
$ct=str_replace("[link_font_color]",$link_font_color,$ct);
$ct=str_replace("[link_font_face]",$link_font_face,$ct);
################################################################################################
$f=fopen("../page_template.qtgp","r");
$ct2=fread($f,filesize("../page_template.qtgp"));
fclose($f);
$ct2=str_replace('[qtgp_links]',$ct,$ct2);
$f=fopen("../$folder/$currentfile.shtml","w");
fwrite($f,"$ct2");
fclose($f);
################################################################################################
unset($linkoutput);
}
?>
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Congratulations! Your multi-page TGP has been built.
</font>
</td>
</tr>
<tr>
<form method=post action='qtgp_index.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Home'>
</td>
</form>
</tr>
</table>
<br>
<?
}
}
break;
}

function build_error($msg) {
print <<<HTML
<table border=0 cellspacing=1 cellpadding=4 width=99% bgcolor=#CCCCCC>
<tr>
<td bgcolor=#FFFFFF width=100% valign=middle align=center height=100>
<font size=2 face='verdana,arial,helvetica'>
Your multi-page TGP could not be built.<br><br>

<font color=#CC0000>Reason: $msg</font><br><br>

Please fix this before trying to build your TGP again.
</font>
</td>
</tr>
<tr>
<form method=post action='qtgp_build.php'>
<td bgcolor=#EFEFEF width=100% valign=middle align=center>
<input type='submit' value='Return to Build'>
</td>
</form>
</tr>
</table>
<br>
HTML;
}
?>

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