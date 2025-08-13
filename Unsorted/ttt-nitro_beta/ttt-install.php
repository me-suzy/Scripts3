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
require("ttt-mysqlfunc.inc.php");
if ($_POST["install"] != "") {

$values = "<?php
\$mysql_host = \"$_POST[mysql_host]\";
\$mysql_user = \"$_POST[mysql_user]\";
\$mysql_pass = \"$_POST[mysql_pass]\";
\$mysql_db = \"$_POST[mysql_db]\";
?>";
$fp = @fopen("ttt-mysqlvalues.inc.php", "w") or print_error("Could not open \"ttt-mysqlvalues.inc.php\"");
fputs($fp, $values);
fclose($fp);
require("ttt-mysqlvalues.inc.php");

open_conn();
if ($_POST["password"] != $_POST["password2"]) { print_error("Password do not match"); }
if ($_POST["username"] == "") { print_error("Type in a username"); }
if ($_POST["password"] == "") { print_error("Type in a password"); }
$fp = @fopen("ttt_toplist/ttt_test.html", "w") or print_error("Could not open \"ttt_toplist\"");
fputs($fp, " ");
fclose($fp);
unlink("ttt_toplist/ttt_test.html") or print_error("Could not delete \"ttt_toplist/ttt_test.html\"");


mysql_query("DROP TABLE IF EXISTS ttt_blacklist") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_blacklist (
  sitedomain char(150) NOT NULL default '',
  email char(150) NOT NULL default '',
  icq char(20) NOT NULL default '',
  reason char(255) NOT NULL default '',
  PRIMARY KEY  (sitedomain)
) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_daily") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_daily (
  dato date NOT NULL default '0000-00-00',
  hits_in int(6) NOT NULL default '0',
  uniq int(6) NOT NULL default '0',
  clicks int(6) NOT NULL default '0',
  hits_out int(6) NOT NULL default '0',
  KEY dato (dato)
) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_forces") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_forces (
  trade_id smallint(6) NOT NULL default '0',
  fh0 smallint(5) NOT NULL default '0',
  fd0 smallint(5) NOT NULL default '0',
  fh1 smallint(5) NOT NULL default '0',
  fd1 smallint(5) NOT NULL default '0',
  fh2 smallint(5) NOT NULL default '0',
  fd2 smallint(5) NOT NULL default '0',
  fh3 smallint(5) NOT NULL default '0',
  fd3 smallint(5) NOT NULL default '0',
  fh4 smallint(5) NOT NULL default '0',
  fd4 smallint(5) NOT NULL default '0',
  fh5 smallint(5) NOT NULL default '0',
  fd5 smallint(5) NOT NULL default '0',
  fh6 smallint(5) NOT NULL default '0',
  fd6 smallint(5) NOT NULL default '0',
  fh7 smallint(5) NOT NULL default '0',
  fd7 smallint(5) NOT NULL default '0',
  fh8 smallint(5) NOT NULL default '0',
  fd8 smallint(5) NOT NULL default '0',
  fh9 smallint(5) NOT NULL default '0',
  fd9 smallint(5) NOT NULL default '0',
  fh10 smallint(5) NOT NULL default '0',
  fd10 smallint(5) NOT NULL default '0',
  fh11 smallint(5) NOT NULL default '0',
  fd11 smallint(5) NOT NULL default '0',
  fh12 smallint(5) NOT NULL default '0',
  fd12 smallint(5) NOT NULL default '0',
  fh13 smallint(5) NOT NULL default '0',
  fd13 smallint(5) NOT NULL default '0',
  fh14 smallint(5) NOT NULL default '0',
  fd14 smallint(5) NOT NULL default '0',
  fh15 smallint(5) NOT NULL default '0',
  fd15 smallint(5) NOT NULL default '0',
  fh16 smallint(5) NOT NULL default '0',
  fd16 smallint(5) NOT NULL default '0',
  fh17 smallint(5) NOT NULL default '0',
  fd17 smallint(5) NOT NULL default '0',
  fh18 smallint(5) NOT NULL default '0',
  fd18 smallint(5) NOT NULL default '0',
  fh19 smallint(5) NOT NULL default '0',
  fd19 smallint(5) NOT NULL default '0',
  fh20 smallint(5) NOT NULL default '0',
  fd20 smallint(5) NOT NULL default '0',
  fh21 smallint(5) NOT NULL default '0',
  fd21 smallint(5) NOT NULL default '0',
  fh22 smallint(5) NOT NULL default '0',
  fd22 smallint(5) NOT NULL default '0',
  fh23 smallint(5) NOT NULL default '0',
  fd23 smallint(5) NOT NULL default '0',
  nht smallint(4) NOT NULL default '0',
  nhs smallint(4) NOT NULL default '0',
  PRIMARY KEY  (trade_id)) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_forces (trade_id) VALUES (1),(2),(3)") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_iplog") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_iplog (
  trade_id smallint(6) NOT NULL default '0',
  proxy tinyint(1) NOT NULL default '0',
  ip varchar(15) NOT NULL default '',
  hits smallint(6) NOT NULL default '0',
  clicks smallint(6) NOT NULL default '0',
  KEY trade_id (trade_id)) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_links") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_links (
  link varchar(50) NOT NULL default '',
  hour0 smallint(6) NOT NULL default '0',
  hour1 smallint(6) NOT NULL default '0',
  hour2 smallint(6) NOT NULL default '0',
  hour3 smallint(6) NOT NULL default '0',
  hour4 smallint(6) NOT NULL default '0',
  hour5 smallint(6) NOT NULL default '0',
  hour6 smallint(6) NOT NULL default '0',
  hour7 smallint(6) NOT NULL default '0',
  hour8 smallint(6) NOT NULL default '0',
  hour9 smallint(6) NOT NULL default '0',
  hour10 smallint(6) NOT NULL default '0',
  hour11 smallint(6) NOT NULL default '0',
  hour12 smallint(6) NOT NULL default '0',
  hour13 smallint(6) NOT NULL default '0',
  hour14 smallint(6) NOT NULL default '0',
  hour15 smallint(6) NOT NULL default '0',
  hour16 smallint(6) NOT NULL default '0',
  hour17 smallint(6) NOT NULL default '0',
  hour18 smallint(6) NOT NULL default '0',
  hour19 smallint(6) NOT NULL default '0',
  hour20 smallint(6) NOT NULL default '0',
  hour21 smallint(6) NOT NULL default '0',
  hour22 smallint(6) NOT NULL default '0',
  hour23 smallint(6) NOT NULL default '0',
  UNIQUE KEY link (link(15))) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_referers") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_referers (
  trade_id smallint(6) NOT NULL default '0',
  referer varchar(255) NOT NULL default '',
  hits smallint(6) NOT NULL default '0',
  KEY trade_id (trade_id)) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_reset") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_reset (
  hour_reset int(11) NOT NULL default '0',
  day_reset int(11) NOT NULL default '0') TYPE=MyISAM") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_reset VALUES (-1, 0)") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_stats") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_stats (
  trade_id smallint(6) NOT NULL default '0',
  in0 smallint(6) NOT NULL default '0',
  uniq0 smallint(6) NOT NULL default '0',
  tclicks0 smallint(6) NOT NULL default '0',
  clicks0 smallint(6) NOT NULL default '0',
  out0 smallint(6) NOT NULL default '0',
  in1 smallint(6) NOT NULL default '0',
  uniq1 smallint(6) NOT NULL default '0',
  tclicks1 smallint(6) NOT NULL default '0',
  clicks1 smallint(6) NOT NULL default '0',
  out1 smallint(6) NOT NULL default '0',
  in2 smallint(6) NOT NULL default '0',
  uniq2 smallint(6) NOT NULL default '0',
  tclicks2 smallint(6) NOT NULL default '0',
  clicks2 smallint(6) NOT NULL default '0',
  out2 smallint(6) NOT NULL default '0',
  in3 smallint(6) NOT NULL default '0',
  uniq3 smallint(6) NOT NULL default '0',
  tclicks3 smallint(6) NOT NULL default '0',
  clicks3 smallint(6) NOT NULL default '0',
  out3 smallint(6) NOT NULL default '0',
  in4 smallint(6) NOT NULL default '0',
  uniq4 smallint(6) NOT NULL default '0',
  tclicks4 smallint(6) NOT NULL default '0',
  clicks4 smallint(6) NOT NULL default '0',
  out4 smallint(6) NOT NULL default '0',
  in5 smallint(6) NOT NULL default '0',
  uniq5 smallint(6) NOT NULL default '0',
  tclicks5 smallint(6) NOT NULL default '0',
  clicks5 smallint(6) NOT NULL default '0',
  out5 smallint(6) NOT NULL default '0',
  in6 smallint(6) NOT NULL default '0',
  uniq6 smallint(6) NOT NULL default '0',
  tclicks6 smallint(6) NOT NULL default '0',
  clicks6 smallint(6) NOT NULL default '0',
  out6 smallint(6) NOT NULL default '0',
  in7 smallint(6) NOT NULL default '0',
  uniq7 smallint(6) NOT NULL default '0',
  tclicks7 smallint(6) NOT NULL default '0',
  clicks7 smallint(6) NOT NULL default '0',
  out7 smallint(6) NOT NULL default '0',
  in8 smallint(6) NOT NULL default '0',
  uniq8 smallint(6) NOT NULL default '0',
  tclicks8 smallint(6) NOT NULL default '0',
  clicks8 smallint(6) NOT NULL default '0',
  out8 smallint(6) NOT NULL default '0',
  in9 smallint(6) NOT NULL default '0',
  uniq9 smallint(6) NOT NULL default '0',
  tclicks9 smallint(6) NOT NULL default '0',
  clicks9 smallint(6) NOT NULL default '0',
  out9 smallint(6) NOT NULL default '0',
  in10 smallint(6) NOT NULL default '0',
  uniq10 smallint(6) NOT NULL default '0',
  tclicks10 smallint(6) NOT NULL default '0',
  clicks10 smallint(6) NOT NULL default '0',
  out10 smallint(6) NOT NULL default '0',
  in11 smallint(6) NOT NULL default '0',
  uniq11 smallint(6) NOT NULL default '0',
  tclicks11 smallint(6) NOT NULL default '0',
  clicks11 smallint(6) NOT NULL default '0',
  out11 smallint(6) NOT NULL default '0',
  in12 smallint(6) NOT NULL default '0',
  uniq12 smallint(6) NOT NULL default '0',
  tclicks12 smallint(6) NOT NULL default '0',
  clicks12 smallint(6) NOT NULL default '0',
  out12 smallint(6) NOT NULL default '0',
  in13 smallint(6) NOT NULL default '0',
  uniq13 smallint(6) NOT NULL default '0',
  tclicks13 smallint(6) NOT NULL default '0',
  clicks13 smallint(6) NOT NULL default '0',
  out13 smallint(6) NOT NULL default '0',
  in14 smallint(6) NOT NULL default '0',
  uniq14 smallint(6) NOT NULL default '0',
  tclicks14 smallint(6) NOT NULL default '0',
  clicks14 smallint(6) NOT NULL default '0',
  out14 smallint(6) NOT NULL default '0',
  in15 smallint(6) NOT NULL default '0',
  uniq15 smallint(6) NOT NULL default '0',
  tclicks15 smallint(6) NOT NULL default '0',
  clicks15 smallint(6) NOT NULL default '0',
  out15 smallint(6) NOT NULL default '0',
  in16 smallint(6) NOT NULL default '0',
  uniq16 smallint(6) NOT NULL default '0',
  tclicks16 smallint(6) NOT NULL default '0',
  clicks16 smallint(6) NOT NULL default '0',
  out16 smallint(6) NOT NULL default '0',
  in17 smallint(6) NOT NULL default '0',
  uniq17 smallint(6) NOT NULL default '0',
  tclicks17 smallint(6) NOT NULL default '0',
  clicks17 smallint(6) NOT NULL default '0',
  out17 smallint(6) NOT NULL default '0',
  in18 smallint(6) NOT NULL default '0',
  uniq18 smallint(6) NOT NULL default '0',
  tclicks18 smallint(6) NOT NULL default '0',
  clicks18 smallint(6) NOT NULL default '0',
  out18 smallint(6) NOT NULL default '0',
  in19 smallint(6) NOT NULL default '0',
  uniq19 smallint(6) NOT NULL default '0',
  tclicks19 smallint(6) NOT NULL default '0',
  clicks19 smallint(6) NOT NULL default '0',
  out19 smallint(6) NOT NULL default '0',
  in20 smallint(6) NOT NULL default '0',
  uniq20 smallint(6) NOT NULL default '0',
  tclicks20 smallint(6) NOT NULL default '0',
  clicks20 smallint(6) NOT NULL default '0',
  out20 smallint(6) NOT NULL default '0',
  in21 smallint(6) NOT NULL default '0',
  uniq21 smallint(6) NOT NULL default '0',
  tclicks21 smallint(6) NOT NULL default '0',
  clicks21 smallint(6) NOT NULL default '0',
  out21 smallint(6) NOT NULL default '0',
  in22 smallint(6) NOT NULL default '0',
  uniq22 smallint(6) NOT NULL default '0',
  tclicks22 smallint(6) NOT NULL default '0',
  clicks22 smallint(6) NOT NULL default '0',
  out22 smallint(6) NOT NULL default '0',
  in23 smallint(6) NOT NULL default '0',
  uniq23 smallint(6) NOT NULL default '0',
  tclicks23 smallint(6) NOT NULL default '0',
  clicks23 smallint(6) NOT NULL default '0',
  out23 smallint(6) NOT NULL default '0',
  PRIMARY KEY  (trade_id)) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_stats (trade_id) VALUES (1),(2),(3)") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_trades") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_trades (
  trade_id smallint(6) NOT NULL auto_increment,
  sitedomain varchar(100) NOT NULL default '',
  siteurl varchar(150) NOT NULL default '',
  sitename varchar(100) NOT NULL default '',
  wm_email varchar(100) NOT NULL default '',
  wm_icq varchar(20) NOT NULL default '',
  ratio decimal(4,2) NOT NULL default '1.20',
  max_prod decimal(4,2) NOT NULL default '5.00',
  min_prod decimal(4,2) NOT NULL default '0.10',
  turbo tinyint(1) NOT NULL default '0',
  enabled tinyint(1) NOT NULL default '1',
  status tinyint(1) NOT NULL default '0',
  turbo_max tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (trade_id)) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_trades (trade_id,sitedomain,siteurl,sitename) VALUES (1, 'bookmarks', 'bookmarks', 'Bookmarks')") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_trades (trade_id,sitedomain,siteurl,sitename) VALUES (2, 'nocookie', 'nocookie', 'No Cookie')") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_trades (trade_id,sitedomain,siteurl,sitename) VALUES (3, 'Choker', 'Choker', 'Choker')") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_settings") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_settings (
  siteurl varchar(150) NOT NULL default '',
  sitename varchar(100) NOT NULL default '',
  admin_email varchar(100) NOT NULL default '',
  admin_icq varchar(50) NOT NULL default '',
  default_ratio decimal(4,2) NOT NULL default '1.20',
  default_max_prod decimal(4,2) NOT NULL default '5.00',
  default_min_prod decimal(4,2) NOT NULL default '0.10',
  webmasterpage tinyint(1) NOT NULL default '1',
  altout varchar(150) NOT NULL default '',
  rules text NOT NULL,
  turbomode tinyint(1) NOT NULL default '0',
  username varchar(50) binary NOT NULL default '',
  password varchar(50) binary NOT NULL default '',
  max_trades smallint(4) NOT NULL default '1000',
  minimum_hits smallint(6) NOT NULL default '0',
  delete_mintrades tinyint(1) NOT NULL default '0',
  use_blacklist tinyint(1) NOT NULL default '1',
  onlyfindtrades tinyint(1) NOT NULL default '0',
  blockheads tinyint(1) NOT NULL default '0',
  iplog tinyint(1) NOT NULL default '1',
  iplogreset tinyint(1) NOT NULL default '1',
  blocknocookies tinyint(1) NOT NULL default '1',
  nocookieout varchar(150) NOT NULL default '',
  max_clicks tinyint(3) NOT NULL default '20') TYPE=MyISAM") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_settings VALUES ('$_POST[siteurl]', '$_POST[sitename]', '$_POST[admin_email]', '$_POST[admin_icq]', '$_POST[default_ratio]', '$_POST[default_max_prod]', '$_POST[default_min_prod]', $_POST[webmasterpage], '$_POST[altout]', '$_POST[rules]', 0, '$_POST[username]', PASSWORD('$_POST[password]'), 1000, $_POST[minimum_hits], 0, 1, 0, 0, 1, 1, 0, '$_POST[nocookieout]', 20)") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_overview") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_overview (
  id int(6) NOT NULL auto_increment,
  adminurl varchar(255) NOT NULL default '',
  adminpassword varchar(50) binary NOT NULL default '',
  UNIQUE KEY id (id,adminurl)) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_groups");
mysql_query("CREATE TABLE ttt_groups (
name VARCHAR(67) NOT NULL default '0',
skim INT(3) NOT NULL default '0',UNIQUE (name))") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_groups VALUES ('default','50')") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS qtgp_settings") or print_error(mysql_error());
mysql_query("CREATE TABLE qtgp_settings (
  file varchar(67) NOT NULL default '0',
  folder varchar(67) NOT NULL default '0',
  max_link smallint(3) NOT NULL default '0',
  link_word_count smallint(1) NOT NULL default '0',
  title_color varchar(7) NOT NULL default '0',
  text_color varchar(7) NOT NULL default '0',
  link_color varchar(7) NOT NULL default '0',
  title_font_size tinyint(1) NOT NULL default '0',
  title_font_face varchar(30) NOT NULL default '0',
  text_font_size tinyint(1) NOT NULL default '0',
  text_font_face varchar(30) NOT NULL default '0',
  link_font_size tinyint(1) NOT NULL default '0',
  link_font_face varchar(30) NOT NULL default '0',
  left_design varchar(3) NOT NULL default '0',
  right_design varchar(3) NOT NULL default '0',
  picture_title varchar(20) NOT NULL default '0',
  movie_title varchar(20) NOT NULL default '0',
  divider varchar(1) NOT NULL default '0',
  trade_script varchar(50) NOT NULL default '0',
  trade_skim varchar(3) NOT NULL default '0',
  template varchar(5) NOT NULL default '0',
  updated DATE NOT NULL default '0000-00-00')") or print_error(mysql_error());
mysql_query("INSERT INTO qtgp_settings VALUES('index.shtml','categories','100','2','#000000','#000000','#0000FF','3','verdana','2','verdana','2','verdana','-','-','Pictures','Movies','/','TurboTrafficTrader','50','1-1','0000-00-00')");
mysql_query("DROP TABLE IF EXISTS qtgp_categories") or print_error(mysql_error());
mysql_query("CREATE TABLE qtgp_categories (
  name varchar(150) NOT NULL default '0',
  description varchar(150) NOT NULL default '0',
  status tinyint(1) NOT NULL default '1',
  UNIQUE ( `name` ))") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ctc_stats") or print_error(mysql_error());
mysql_query("CREATE TABLE ctc_stats (
  program_id smallint(6) NOT NULL auto_increment,
  sponsor_id smallint(6) NOT NULL,
  date date NOT NULL default '0000-00-00',
  hits smallint(6) NOT NULL default '0',
  UNIQUE KEY program_id (program_id,date))");
mysql_query("DROP TABLE IF EXISTS ctc_programs") or print_error(mysql_error());
mysql_query("CREATE TABLE ctc_programs (
  program_id smallint(6) NOT NULL,
  sponsor_id smallint(6) NOT NUll default '',
  name varchar(67) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  UNIQUE KEY program_id (program_id))");
mysql_query("INSERT INTO ctc_programs VALUES ('1','1','default', 'http://www.yoursponsor.com')");
mysql_query("DROP TABLE IF EXISTS ctc_referers") or print_error(mysql_error());
mysql_query("CREATE TABLE ctc_referers (
  program_id smallint(6) NOT NULL,
  sponsor_id smallint(6) NOT NUll default '',
  referer varchar(255) NOT NULL default '',
  hits smallint(6) NOT NULL default '0',
  UNIQUE KEY program_id (program_id,referer))");
mysql_query("DROP TABLE IF EXISTS ctc_reset") or print_error(mysql_error());
mysql_query("CREATE TABLE ctc_reset (reset int(11) NOT NULL default '0')");
mysql_query("INSERT INTO ctc_reset (reset) VALUES (0)");
mysql_query("DROP TABLE IF EXISTS ctc_sponsors") or print_error(mysql_error());
mysql_query("CREATE TABLE ctc_sponsors (
  sponsor_id smallint(6) NOT NULL auto_increment,
  name varchar(67) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  UNIQUE KEY sponsor_id (sponsor_id))");
mysql_query("INSERT INTO ctc_sponsors VALUES ('1','default', 'http://www.yoursponsor.com')");
mysql_query("CREATE TABLE ttt_events (dato date NOT NULL default '0000-00-00',timo time NOT NULL default '',action tinyint(2) NOT NULL default '0',domain varchar(150) NOT NULL default '0')") or print_error(mysql_error());
close_conn();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Turbo Traffic Trader Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="ttt-style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<?php if ($_POST["install"] != "") { ?>
<div align="center"><b><font size="4">Turbo Traffic Trader was succesfully 
  installed<br>Make sure you delete ttt-install.php!!<br>
  <br>
  <a href="tttadmin/">Goto the admin to add trades</a></font></b></div>
<?php } else { ?>
<form action="ttt-install.php" method="POST">
<table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr> 
    <td height="28" align="center" background="tttadmin/background.gif"><a href="http://www.turbotraffictrader.com/"><img src="tttadmin/ttt.gif" width="162" height="20" border="0"></a></td>
  </tr>
  <tr class="normalrow"> 
    <td align="left" >Before you use this installation file, make sure you have: 
      <ul>
        <li> Uploaded all files in the zip file to your web root.<br>
        .php and .css files in ASCII mode and all GIFs in BINARY.</li>
	<li> Created a directory named 'categories' in your webroot directory</li>
        <li> Chmod'ed the &quot;categories&quot; directory to 777.</li>
        <li> Chmod'ed the &quot;ttt_toplist&quot; directory to 777.</li>
        <li> Chmod'ed &quot;ttt-mysqlvalues.inc.php&quot; to 777.</li>
        <li> Chmod'ed &quot;/tttadmin/index.template.tmp&quot; to 777.</li>
        <li> Chmod'ed &quot;/tttadmin/page.template.tmp&quot; to 777.</li>
        <li> Chmod'ed &quot;index.template.qtgp&quot; to 777.</li>
        <li> Chmod'ed &quot;index.template.qtgp&quot; to 777.</li>
        <li> A working MySQL database.<br>
        Contact your host if you are not sure about your MySQL login.</li>
      </ul></td>
  </tr>
  <tr class="normalrow"> 
    <td align="left">This installation file will: 
      <ul>
        <li> Delete all previous TTT MySQL tables.</li>
        <li>Install all needed TTT MySQL tables.</li>
      </ul></td>
  </tr>
  <tr> 
    <td><table width="100%" border="1" cellpadding="1" cellspacing="0" class="normalrow">
        <tr> 
          <td colspan="2" align="left" class="toprows">Installation</td>
        </tr>
        <tr> 
          <td width="30%" align="left">Site URL:</td>
          <td width="70%" align="left"><input name="siteurl" type="text" id="siteurl" size="40" maxlength="150"></td>
        </tr>
        <tr> 
          <td align="left">Site Name:</td>
          <td align="left"><input name="sitename" type="text" id="sitename" size="40" maxlength="100"></td>
        </tr>
        <tr> 
          <td align="left">Admin Email:</td>
          <td align="left"><input name="admin_email" type="text" id="admin_email" size="40" maxlength="100"></td>
        </tr>
        <tr> 
          <td align="left">Admin ICQ:</td>
          <td align="left"><input name="admin_icq" type="text" id="admin_icq" size="40" maxlength="50"></td>
        </tr>
        <tr> 
          <td align="left">Alternative Out URL:</td>
          <td align="left"><input name="altout" type="text" id="altout" value="http://www.yoursponsor.com/" size="40" maxlength="150"></td>
        </tr>
        <tr> 
          <td align="left">Nocookie Out URL:</td>
          <td align="left"><input name="nocookieout" type="text" id="altout" value="http://www.yoursponsor.com/" size="40" maxlength="150"></td>
        </tr>
        <tr class="toprows"> 
          <td colspan="2">Default Trade Settings</td>
        </tr>
        <tr> 
          <td align="left">Default Ratio:</td>
          <td align="left"><input name="default_ratio" type="text" id="default_ratio" value="1.3" size="40" maxlength="5"></td>
        </tr>
        <tr> 
          <td align="left">Default Minimum Prod:</td>
          <td align="left"><input name="default_min_prod" type="text" id="default_min_prod" value="0.10" size="40" maxlength="5"></td>
        </tr>
        <tr> 
          <td align="left">Default Maximum Prod:</td>
          <td align="left"><input name="default_max_prod" type="text" id="default_max_prod" value="5.0" size="40" maxlength="5"></td>
        </tr>
        <tr> 
          <td align="left">Minimum Hits:</td>
          <td align="left"><input name="minimum_hits" type="text" value="0" size="40" maxlength="5"></td>
        </tr>
        <tr class="toprows"> 
          <td colspan="2">Webmaster Page</td>
        </tr>
        <tr> 
          <td align="left">Signup Page Status:</td>
          <td align="left"><select name="webmasterpage"><option value="1">Enabled - Normal</option>
<option value="0">Enabled - Disables new trades</option>
<option value="2">Disabled - No new signups</option>
</td>
        </tr>
        <tr> 
          <td align="left">Rules HTML:</td>
          <td align="left"><textarea name="rules" cols="40" rows="5" id="rules"></textarea></td>
        </tr>
        <tr> 
          <td colspan="2" align="left" class="toprows">MySQL Login</td>
        </tr>
        <tr> 
          <td align="left">MySQL Host:</td>
          <td align="left"><input name="mysql_host" type="text" size="40" maxlength="50" value="localhost"></td>
        </tr>
        <tr> 
          <td align="left">MySQL Username:</td>
          <td align="left"><input name="mysql_user" type="text" size="40" maxlength="50"></td>
        </tr>
        <tr> 
          <td align="left">MySQL Password:</td>
          <td align="left"><input name="mysql_pass" type="text" size="40" maxlength="50"></td>
        </tr>
        <tr> 
          <td align="left">MySQL Database:</td>
          <td align="left"><input name="mysql_db" type="text" size="40" maxlength="50"></td>
        </tr>
        <tr> 
          <td colspan="2" align="left" class="toprows">Username &amp; Password</td>
        </tr>
        <tr> 
          <td align="left">Username:</td>
          <td align="left"><input name="username" type="text" id="username" size="40" maxlength="50"></td>
        </tr>
        <tr> 
          <td align="left">Password:</td>
          <td align="left"><input name="password" type="password" id="password" size="40" maxlength="50"></td>
        </tr>
        <tr> 
          <td align="left">Password Again:</td>
          <td align="left"><input name="password2" type="password" id="password2" size="40" maxlength="50"></td>
        </tr>
        <tr> 
          <td align="left">&nbsp;</td>
          <td align="left"><input name="install" type="submit" class="buttons" id="install" value="Install TTT"></td>
        </tr>
      </table></td>
  </tr>
</table>
<div align="center"><br>
  <br>
Copyright (c) 2003 Choker (Chokinchicken.com). All Rights Reserved.<br>
This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
If you upload this script then you do so knowing that any changes to this script that you make are in violation
of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!<br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
</div>
</form>
<?php } ?>
</body>
</html>
