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

$time = localtime();
$thishour = $time[2];

$msg = "Link Report";
$bg[$thishour] = "bgcolor=\"#CCCCCC\"";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $msg; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
</head>

<body>
<div align="center"><strong><font size="4"><?php echo $msg; ?><br>
  </font></strong><br>
</div>
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="1">
        <tr class="toprowssmall"> 
          <td>Link</td>
          <td>00<br>
            -<br>
            01</td>
          <td>01<br>
            -<br>
            02</td>
          <td> 02<br>
            -<br>
            03</td>
          <td>03<br>
            -<br>
            04</td>
          <td>04<br>
            -<br>
            05</td>
          <td>05<br>
            -<br>
            06</td>
          <td>06<br>
            -<br>
            07</td>
          <td>07<br>
            -<br>
            08</td>
          <td>08<br>
            -<br>
            09</td>
          <td>09<br>
            -<br>
            10</td>
          <td>10<br>
            -<br>
            11</td>
          <td>11<br>
            -<br>
            12</td>
          <td>12<br>
            -<br>
            13</td>
          <td>13<br>
            -<br>
            14</td>
          <td>14<br>
            -<br>
            15</td>
          <td>15<br>
            -<br>
            16</td>
          <td>16<br>
            -<br>
            17</td>
          <td>17<br>
            -<br>
            18</td>
          <td>18<br>
            -<br>
            19</td>
          <td>19<br>
            -<br>
            20</td>
          <td>20<br>
            -<br>
            21</td>
          <td>21<br>
            -<br>
            22</td>
          <td>22<br>
            -<br>
            23</td>
          <td>23<br>
            -<br>
            00</td>
          <td>Total</td>
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
