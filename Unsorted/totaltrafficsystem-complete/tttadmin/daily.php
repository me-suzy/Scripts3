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
<title>Daily Stats</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
</head>

<body>
<div align="center"><strong><font size="4">Daily Stats<br>
  </font></strong></div>
<table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="1">
        <tr class="toprows"> 
          <td>Date</td>
          <td>In</td>
          <td>Uniques</td>
          <td>Clicks</td>
          <td>Out</td>
          <td>Unique %</td>
          <td>Prod</td>
        </tr>
<?php
$res = mysql_query("SELECT * FROM ttt_daily ORDER BY dato DESC") or print_error(mysql_error());
close_conn();
while ($row = mysql_fetch_array($res)) {
	extract($row);
	if ($hits_in != 0) {
		$prod = round($clicks/$hits_in*100,2) . "%";
		$unique = round($uniq/$hits_in*100,2) . "%";
	 }
	else { $prod = "no hits"; $unique = "no hits"; }
print <<<END
        <tr class="normalrow"> 
          <td>$dato</td>
          <td>$hits_in</td>
          <td>$uniq</td>
          <td>$clicks</td>
          <td>$hits_out</td>
          <td>$unique</td>
          <td>$prod</td>
        </tr>
END;
}
?>
      </table></td>
  </tr>
</table><br><br>
<div align="center"><font size="2"><a href="javascript:window.close()">Close Window</a></font>  
  </div>
</body>
</html>
