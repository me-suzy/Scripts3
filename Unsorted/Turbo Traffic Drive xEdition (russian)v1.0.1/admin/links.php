<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

require("../mysqlvalues.inc.php");
require("../mysqlfunc.inc.php");
open_conn();
require("security.inc.php");

$time = localtime();
$thishour = $time[2];

$msg = "Link Report";
$bg[$thishour] = "bgcolor=\"#CCCCCC\"";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="../style.css" rel="stylesheet" type="text/css">
</head>

<body>
<div align="center"><strong><font size="4"><?php echo $msg; ?><br>
  </font></strong><br>
</div>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td>
<table border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4" align="center">
        <tr class="toprowssmall"> 
          <td background="background.gif">Link</td>
          <td background="background.gif">00</td>
          <td background="background.gif">01</td>
          <td background="background.gif">02</td>
          <td background="background.gif">03</td>
          <td background="background.gif">04</td>
          <td background="background.gif">05</td>
          <td background="background.gif">06</td>
          <td background="background.gif">07</td>
          <td background="background.gif">08</td>
          <td background="background.gif">09</td>
          <td background="background.gif">10</td>
          <td background="background.gif">11</td>
          <td background="background.gif">12</td>
          <td background="background.gif">13</td>
          <td background="background.gif">14</td>
          <td background="background.gif">15</td>
          <td background="background.gif">16</td>
          <td background="background.gif">17</td>
          <td background="background.gif">18</td>
          <td background="background.gif">19</td>
          <td background="background.gif">20</td>
          <td background="background.gif">21</td>
          <td background="background.gif">22</td>
          <td background="background.gif">23</td>
          <td background="background.gif">Total</td>
        </tr>
<?php
$res = mysql_query("SELECT * FROM ttt_links ORDER BY link ASC") or print_error(mysql_error());
close_conn();
if (mysql_num_rows($res) == 0) {
print <<<END
        <tr class="normalrowsmall"> 
          <td colspan="26"><font size="3"><br><b>No Data</b><br><br></font></td>
        </tr>
END;
}
while ($row = mysql_fetch_array($res)) {
	extract($row);
	$total = $hour0+$hour1+$hour2+$hour3+$hour4+$hour5+$hour6+$hour7+$hour8+$hour9+$hour10+$hour11+$hour12+$hour13+$hour14+$hour15+$hour16+$hour17+$hour18+$hour19+$hour20+$hour21+$hour22+$hour23;
print <<<END
        <tr class="normalrowsmall"> 
          <td align="left">$link</td>
          <td $bg[0]>$hour0</td>
          <td $bg[1]>$hour1</td>
          <td $bg[2]>$hour2</td>
          <td $bg[3]>$hour3</td>
          <td $bg[4]>$hour4</td>
          <td $bg[5]>$hour5</td>
          <td $bg[6]>$hour6</td>
          <td $bg[7]>$hour7</td>
          <td $bg[8]>$hour8</td>
          <td $bg[9]>$hour9</td>
          <td $bg[10]>$hour10</td>
          <td $bg[11]>$hour11</td>
          <td $bg[12]>$hour12</td>
          <td $bg[13]>$hour13</td>
          <td $bg[14]>$hour14</td>
          <td $bg[15]>$hour15</td>
          <td $bg[16]>$hour16</td>
          <td $bg[17]>$hour17</td>
          <td $bg[18]>$hour18</td>
          <td $bg[19]>$hour19</td>
          <td $bg[20]>$hour20</td>
          <td $bg[21]>$hour21</td>
          <td $bg[22]>$hour22</td>
          <td $bg[23]>$hour23</td>
          <td>$total</td>
        </tr>
END;
}
?>
      </table></td>
  </tr>
</table>
<br>
<div align="center"><font size="2"><a href="javascript:window.close()">Close 
        Window</a></font></div>
</body>
</html>
