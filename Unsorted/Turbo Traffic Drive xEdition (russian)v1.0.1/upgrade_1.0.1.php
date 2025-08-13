<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("mysqlvalues.inc.php");
require("mysqlfunc.inc.php");

if ($_POST["upgrade"] != "") {

open_conn();

mysql_query("DROP TABLE IF EXISTS ttt_iplog") or print_error(mysql_error());
mysql_query("CREATE TABLE ttt_iplog (
  trade_id smallint(6) NOT NULL default '0',
  ip varchar(15) NOT NULL default '',
  hits smallint(6) NOT NULL default '0',
  use_proxy tinyint(1) NOT NULL default '',
  KEY trade_id (trade_id)
) TYPE=MyISAM") or print_error(mysql_error());
close_conn();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Turbo Traffic Trader v 1.0 Update</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<?php if ($_POST["upgrade"] != "") { ?>
<div align="center"><b><font size="4">Turbo Traffic Trader v 1.0  Update succesfully<br>Make sure you delete upgrade_1.0.1.php!!<br>
  <br>
  <a href="admin/">Goto the admin</a></font></b></div>
<?php } else { ?>
<form action="upgrade_1.0.1.php" method="POST">
<table width="500" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center"  class="normalrow">
  <tr> 
    <td class="toprows" height="28" align="center" background="admin/background.gif">Turbo Traffic Trader xEdition v 1.0.1 <a target="_blank" href="http://www.x-forum.info/showthread.php?s=&threadid=1383">free update</a></td>
  </tr>
  <tr class="normalrow"> 
    <td align="left">This installation file will: 
      <ul>
        <li>Delete all data from ttt_iplog table.</li>
        <li>Delete ttt_iplog table.</li>
        <li>Create new ttt_iplog table.</li>
      </ul></td>
  </tr>

</table>
<br>

<table width="500" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center"  class="normalrow">

        <tr> 
          <td background="admin/background.gif" colspan="2" align="left" class="toprows">MySQL Login</td>
        </tr>
        <tr> 
          <td align="left">MySQL Host:</td>
          <td align="left"><input name="mysql_host" type="text" size="40" maxlength="50" value="<? echo $mysql_host; ?>"></td>
        </tr>
        <tr> 
          <td align="left">MySQL Username:</td>
          <td align="left"><input name="mysql_user" type="text" size="40" maxlength="50" value="<? echo $mysql_user; ?>"></td>
        </tr>
        <tr> 
          <td align="left">MySQL Password:</td>
          <td align="left"><input name="mysql_pass" type="text" size="40" maxlength="50" value="<? echo $mysql_pass; ?>"></td>
        </tr>
        <tr> 
          <td align="left">MySQL Database:</td>
          <td align="left"><input name="mysql_db" type="text" size="40" maxlength="50" value="<? echo $mysql_db; ?>"></td>
        </tr>

          <td align="left">&nbsp;</td>
          <td align="left"><input name="upgrade" type="submit" class="buttons" id="" value="upgrade"></td>
        </tr>
      </table>
</form>
<?php } ?>
</body>
</html>
