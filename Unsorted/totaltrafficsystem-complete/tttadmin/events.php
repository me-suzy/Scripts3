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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Events</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
</head>

<?php
if ($_POST["reset_events"] == "Reset") {
@mysql_query("DELETE FROM ttt_events") or print_error(mysql_error());
}
?>

<body>
<div align="center"><strong><font size="4">Events<br>
  </font></strong></div>
<table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="1">
        <tr class="toprows"> 
          <td>Date</td>
          <td>Time</td>
          <td>Action</td>
          <td>Domain</td>
        </tr>
<?php
$res = mysql_query("SELECT * FROM ttt_events ORDER BY dato DESC,timo DESC") or print_error(mysql_error());
close_conn();
$valid = mysql_num_rows($res);
if ($valid < 1) {
print <<<END
	<tr class=normalrow><td colspan=4 bgcolor=#EFEFEF align=center><font size=3><b>NO EVENTS RECORDED</b></font></td></tr>
END;
}
else {
while ($row = mysql_fetch_array($res)) {
	extract($row);
	if ($action == 1) { $_action="Enabled"; }
	if ($action == 2) { $_action="Disabled"; }
	if ($action == 3) { $_action="Deleted"; }
	if ($action == 4) { $_action="Signup"; }
print <<<END
        <tr class="normalrow"> 
          <td><font size=1>$dato</font></td>
          <td><font size=1>$timo</font></td>
          <td><font size=1>$_action</font></td>
          <td><font size=1>$domain</font></td>
        </tr>
END;
	$_action="";
}
}
?>
      </table></td>
  </tr>
</table><br>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <form name=form1 method=post action='events.php'>
    <td colspan="4" align=center>
  	<input name='reset_events' type=submit value='Reset' class=buttons>
    </td>
    </form>
  </tr>
</table>
<br>
<div align="center"><font size="2"><a href="javascript:window.close()">Close Window</a></font>  
  </div>
</body>
</html>
