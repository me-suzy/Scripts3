<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("mysqlfunc.inc.php");
if ($_POST["install"] != "") {

$values = "<?php
\$mysql_host = \"$_POST[mysql_host]\";
\$mysql_user = \"$_POST[mysql_user]\";
\$mysql_pass = \"$_POST[mysql_pass]\";
\$mysql_db = \"$_POST[mysql_db]\";
?>";
$fp = @fopen("mysqlvalues.inc.php", "w") or print_error("Could not open \"mysqlvalues.inc.php\"");
fputs($fp, $values);
fclose($fp);
require("mysqlvalues.inc.php");

open_conn();
if ($_POST["password"] != $_POST["password2"]) { print_error("Password do not match"); }
if ($_POST["username"] == "") { print_error("Type in a username"); }
if ($_POST["password"] == "") { print_error("Type in a password"); }
$fp = @fopen("toplist/ttt_test.html", "w") or print_error("Could not open \"toplist\"");
fputs($fp, " ");
fclose($fp);
unlink("toplist/ttt_test.html") or print_error("Could not delete \"toplist/ttt_test.html\"");


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
  PRIMARY KEY  (trade_id)
) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_forces (trade_id) VALUES (1),(2),(3)") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_iplog") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_iplog (
  trade_id smallint(6) NOT NULL default '0',
  ip varchar(15) NOT NULL default '',
  hits smallint(6) NOT NULL default '0',
  use_proxy tinyint(1) NOT NULL default '',
  KEY trade_id (trade_id)
) TYPE=MyISAM") or print_error(mysql_error());
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
  UNIQUE KEY link (link(15))
) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_referers") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_referers (
  trade_id smallint(6) NOT NULL default '0',
  referer varchar(255) NOT NULL default '',
  hits smallint(6) NOT NULL default '0',
  KEY trade_id (trade_id)
) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_reset") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_reset (
  hour_reset int(11) NOT NULL default '0',
  day_reset int(11) NOT NULL default '0'
) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_reset VALUES (-1, 0)") or print_error(mysql_error());
mysql_query("DROP TABLE IF EXISTS ttt_stats") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_stats (
  trade_id smallint(6) NOT NULL default '0',
  in0 smallint(6) NOT NULL default '0',
  uniq0 smallint(6) NOT NULL default '0',
  clicks0 smallint(6) NOT NULL default '0',
  out0 smallint(6) NOT NULL default '0',
  in1 smallint(6) NOT NULL default '0',
  uniq1 smallint(6) NOT NULL default '0',
  clicks1 smallint(6) NOT NULL default '0',
  out1 smallint(6) NOT NULL default '0',
  in2 smallint(6) NOT NULL default '0',
  uniq2 smallint(6) NOT NULL default '0',
  clicks2 smallint(6) NOT NULL default '0',
  out2 smallint(6) NOT NULL default '0',
  in3 smallint(6) NOT NULL default '0',
  uniq3 smallint(6) NOT NULL default '0',
  clicks3 smallint(6) NOT NULL default '0',
  out3 smallint(6) NOT NULL default '0',
  in4 smallint(6) NOT NULL default '0',
  uniq4 smallint(6) NOT NULL default '0',
  clicks4 smallint(6) NOT NULL default '0',
  out4 smallint(6) NOT NULL default '0',
  in5 smallint(6) NOT NULL default '0',
  uniq5 smallint(6) NOT NULL default '0',
  clicks5 smallint(6) NOT NULL default '0',
  out5 smallint(6) NOT NULL default '0',
  in6 smallint(6) NOT NULL default '0',
  uniq6 smallint(6) NOT NULL default '0',
  clicks6 smallint(6) NOT NULL default '0',
  out6 smallint(6) NOT NULL default '0',
  in7 smallint(6) NOT NULL default '0',
  uniq7 smallint(6) NOT NULL default '0',
  clicks7 smallint(6) NOT NULL default '0',
  out7 smallint(6) NOT NULL default '0',
  in8 smallint(6) NOT NULL default '0',
  uniq8 smallint(6) NOT NULL default '0',
  clicks8 smallint(6) NOT NULL default '0',
  out8 smallint(6) NOT NULL default '0',
  in9 smallint(6) NOT NULL default '0',
  uniq9 smallint(6) NOT NULL default '0',
  clicks9 smallint(6) NOT NULL default '0',
  out9 smallint(6) NOT NULL default '0',
  in10 smallint(6) NOT NULL default '0',
  uniq10 smallint(6) NOT NULL default '0',
  clicks10 smallint(6) NOT NULL default '0',
  out10 smallint(6) NOT NULL default '0',
  in11 smallint(6) NOT NULL default '0',
  uniq11 smallint(6) NOT NULL default '0',
  clicks11 smallint(6) NOT NULL default '0',
  out11 smallint(6) NOT NULL default '0',
  in12 smallint(6) NOT NULL default '0',
  uniq12 smallint(6) NOT NULL default '0',
  clicks12 smallint(6) NOT NULL default '0',
  out12 smallint(6) NOT NULL default '0',
  in13 smallint(6) NOT NULL default '0',
  uniq13 smallint(6) NOT NULL default '0',
  clicks13 smallint(6) NOT NULL default '0',
  out13 smallint(6) NOT NULL default '0',
  in14 smallint(6) NOT NULL default '0',
  uniq14 smallint(6) NOT NULL default '0',
  clicks14 smallint(6) NOT NULL default '0',
  out14 smallint(6) NOT NULL default '0',
  in15 smallint(6) NOT NULL default '0',
  uniq15 smallint(6) NOT NULL default '0',
  clicks15 smallint(6) NOT NULL default '0',
  out15 smallint(6) NOT NULL default '0',
  in16 smallint(6) NOT NULL default '0',
  uniq16 smallint(6) NOT NULL default '0',
  clicks16 smallint(6) NOT NULL default '0',
  out16 smallint(6) NOT NULL default '0',
  in17 smallint(6) NOT NULL default '0',
  uniq17 smallint(6) NOT NULL default '0',
  clicks17 smallint(6) NOT NULL default '0',
  out17 smallint(6) NOT NULL default '0',
  in18 smallint(6) NOT NULL default '0',
  uniq18 smallint(6) NOT NULL default '0',
  clicks18 smallint(6) NOT NULL default '0',
  out18 smallint(6) NOT NULL default '0',
  in19 smallint(6) NOT NULL default '0',
  uniq19 smallint(6) NOT NULL default '0',
  clicks19 smallint(6) NOT NULL default '0',
  out19 smallint(6) NOT NULL default '0',
  in20 smallint(6) NOT NULL default '0',
  uniq20 smallint(6) NOT NULL default '0',
  clicks20 smallint(6) NOT NULL default '0',
  out20 smallint(6) NOT NULL default '0',
  in21 smallint(6) NOT NULL default '0',
  uniq21 smallint(6) NOT NULL default '0',
  clicks21 smallint(6) NOT NULL default '0',
  out21 smallint(6) NOT NULL default '0',
  in22 smallint(6) NOT NULL default '0',
  uniq22 smallint(6) NOT NULL default '0',
  clicks22 smallint(6) NOT NULL default '0',
  out22 smallint(6) NOT NULL default '0',
  in23 smallint(6) NOT NULL default '0',
  uniq23 smallint(6) NOT NULL default '0',
  clicks23 smallint(6) NOT NULL default '0',
  out23 smallint(6) NOT NULL default '0',
  PRIMARY KEY  (trade_id)
) TYPE=MyISAM") or print_error(mysql_error());
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
  PRIMARY KEY  (trade_id)
) TYPE=MyISAM") or print_error(mysql_error());
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
  use_blacklist tinyint(1) NOT NULL default '1'
) TYPE=MyISAM") or print_error(mysql_error());
mysql_query("INSERT INTO ttt_settings VALUES ('$_POST[siteurl]', '$_POST[sitename]', '$_POST[admin_email]', '$_POST[admin_icq]', '$_POST[default_ratio]', '$_POST[default_max_prod]', '$_POST[default_min_prod]', $_POST[webmasterpage], '$_POST[altout]', '$_POST[rules]', 0, '$_POST[username]', PASSWORD('$_POST[password]'), 1000, $_POST[minimum_hits], 1)") or print_error(mysql_error());
close_conn();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Turbo Traffic Trader xEdition v 1.0.1 Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<?php if ($_POST["install"] != "") { ?>
<div align="center"><b><font size="4">Turbo Traffic Trader xEdition v 1.0.1  was succesfully 
  installed<br>Make sure you delete install.php!!<br>
  <br>
  <a href="admin/">Goto the admin</a></font></b></div>
<?php } else { ?>
<form action="install.php" method="POST">
<table width="500" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center"  class="normalrow">
  <tr> 
    <td class="toprows" height="28" align="center" background="admin/background.gif">Turbo Traffic Trader xEdition v 1.0.1 <a target="_blank" href="http://www.x-forum.info/showthread.php?s=&threadid=1383">free update</a></td>
  </tr>
  <tr class="normalrow"> 
    <td align="left" >Before you use this installation file, make sure you have: 
      <ul>
        <li> Uploaded all files and folders to your web server.<br>
        .php and .css files in ASCII mode and all GIFs in BINARY.</li>
        <li> Chmod'ed the &quot;toplist&quot; directory to 777.</li>
        <li> Chmod'ed &quot;mysqlvalues.inc.php&quot; to 777.</li>
      </ul></td>
  </tr>
  <tr class="normalrow"> 
    <td align="left">This installation file will: 
      <ul>
        <li> Delete all previous TTT MySQL tables.</li>
        <li>Install all needed TTT MySQL tables.</li>
      </ul></td>
  </tr>

</table>
<br>

<table width="500" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center"  class="normalrow">
        <tr> 
          <td background="admin/background.gif" colspan="2" align="left" class="toprows">Installation</td>
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
        <tr class="toprows"> 
          <td background="admin/background.gif" colspan="2">Default Trade Settings</td>
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
          <td background="admin/background.gif" colspan="2">Webmaster Page</td>
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
          <td background="admin/background.gif" colspan="2" align="left" class="toprows">MySQL Login</td>
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
          <td background="admin/background.gif" colspan="2" align="left" class="toprows">Username &amp; Password</td>
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
          <td align="left"><input name="install" type="submit" class="buttons" id="install" value="Install"></td>
        </tr>
      </table>
</form>
<?php } ?>
</body>
</html>
