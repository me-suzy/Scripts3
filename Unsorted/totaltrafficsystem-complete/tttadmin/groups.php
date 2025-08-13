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

if ($_POST["updategroups"] != "" AND (strlen($_POST["new_name"]) < 1) AND (strlen($_POST["new_skim"]) < 1)) {
$names=$_POST[name];
$skims=$_POST[skim];
for ($x=1; $x<=sizeof($names); $x++) {
$key=key($names);
next($names);
mysql_query("update ttt_groups set skim='$skims[$key]' where name='$names[$key]'");
}
}
elseif ($_POST["updategroups"] != "" AND (strlen($_POST["new_name"]) > 1) AND (strlen($_POST["new_skim"]) > 1)) {
mysql_query("insert into ttt_groups values('$_POST[new_name]','$_POST[new_skim]')");
}
elseif ($_GET["deletegroups"] != "") {
mysql_query("delete from ttt_groups where name='$_GET[deletegroups]'");
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Groups</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center"><strong><font size="4">Groups<br>
  </font></strong></div>
<table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="1">
    <form name=form1 method=post action='groups.php'>
        <tr class="toprows"> 
          <td width=5%>Del</td>
          <td width=65%>Group</td>
          <td width=30%>Skim %</td>
        </tr>
<?php
$res = mysql_query("SELECT * FROM ttt_groups ORDER BY name ASC") or print_error(mysql_error());
close_conn();
$valid = mysql_num_rows($res);
if ($valid < 1) {
print <<<END
	<tr class=normalrow><td colspan=3 bgcolor=#EFEFEF align=center><font size=3><b>NO GROUPS AVAILABLE</b></font></td></tr>
END;
}
else {
$x=0;
while ($row = mysql_fetch_array($res)) {
	extract($row);
if ($name != "default") {
print <<<END
        <tr class="traderow"> 
          <td><font size=2 width=5%><a href='groups.php?deletegroups=$name'>Del</a></td>
          <td><font size=2 width=65%><input type=hidden name=name[$x] value="$name">$name</font></td>
          <td><font size=2 width=30%><input type=text name=skim[$x] value='$skim' size=4 maxlength=3></font></td>
        </tr>
END;
}
else {
print <<<END
        <tr class="traderow"> 
          <td><font size=2 width=5%>Del</td>
          <td><font size=2 width=65%><input type=hidden name=name[$x] value="$name">$name</font></td>
          <td><font size=2 width=30%><input type=text name=skim[$x] value='$skim' size=4 maxlength=3></font></td>
        </tr>
END;
}
}
$x++;
}
?>
        <tr class="toprows"> 
	<td colspan=3 align=center>Add New Group</td>
        </tr>
        <tr class="traderow"> 
          <td><font size=2 width=5%>--</td>
          <td><font size=2 width=65%><input type=text name=new_name size=25 maxlength=67></font></td>
          <td><font size=2 width=30%><input type=text name=new_skim size=4 maxlength=3></font></td>
        </tr>
      </table></td>
  </tr>
</table><br>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td colspan="4" align=center>
  	<input name='updategroups' type=submit value='Update' class=buttons>
    </td>
    </form>
  </tr>
</table>
<br>
<div align="center"><font size="2"><a href="javascript:window.close()">Close Window</a></font>  
  </div>
</body>
</html>
