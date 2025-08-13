<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("../mysqlvalues.inc.php");
require("../mysqlfunc.inc.php");
open_conn();
require("security.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Daily Stats</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="../style.css" rel="stylesheet" type="text/css">
</head>

<body>
<div align="center"><strong><font size="4">Daily Stats<br>
  </font></strong></div>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td>
<table width="100%" border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center">
        <tr class="toprows"> 
          <td background="background.gif"><a target=_top href="?orderby=dato">Date</a></td>
          <td background="background.gif"><a target=_top href="?orderby=hits_in">Raw In</a></td>
          <td background="background.gif"><a target=_top href="?orderby=uniq">Unique In</a></td>
          <td background="background.gif"><a target=_top href="?orderby=clicks">Clicks</a></td>
          <td background="background.gif"><a target=_top href="?orderby=hits_out">Out</a></td>
          <td background="background.gif">Unique %</td>
          <td background="background.gif">Prod</td>
        </tr>
<?php

if ($orderby == "") {
$orderby = "dato";
}

$res = mysql_query("SELECT * FROM ttt_daily ORDER BY $orderby DESC") or print_error(mysql_error());
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
