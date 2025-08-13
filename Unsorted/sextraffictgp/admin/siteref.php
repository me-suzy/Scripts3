<?php
include("header.php");

if($access[canstats]=="1"){

#######################################################
## START OF REDIRECT
#######################################################
if(!$actions){?>
<meta http-equiv ="Refresh" content = "0 ; URL=siteref.php?actions=count">
<?php
}

#######################################################
## START OF REFFERERS
#######################################################

if($actions=="ref"){
$thecounter	= mysql_query("SELECT count FROM st_counter");
$thecount =	mysql_fetch_array($thecounter);
$count = $thecount["count"];

?>
<table width='100%'	border='0' cellspacing='0' cellpadding='4'>
  <tr>
	<td	bgcolor='<?=$admincolor3?>'><font	face='Arial' size='4' color="FFFFFF"><b>Usage Statistics</b></font></td>
  </tr>
</table><br>
<LINK href="boxx.css" type=text/css rel=stylesheet>

<?php

$sql = mysql_query("SELECT * FROM st_ref ORDER BY counter DESC LIMIT 50");
if (!$sql) {
  echo("<p>Error retrieving	stats".
mysql_error() .	"</p>");
  exit();
}

?>
<table width='100%'	border='0' cellspacing='0' cellpadding='4'>
  <tr>
	<td	width='10%'>
	  <div align='center'><b><font size="3"	face="Arial, Helvetica,	sans-serif">Count</font></b></div>
	</td>
	<td	width='90%'><b><font size='3' face='Arial, Helvetica, sans-serif'>Referrers	Website</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size='2' face='arial'>( <a href='actions.php?action=removeunder5'>Remove referrers under 5 hits</a> )</font></b></td>
  </tr>

<?php

while ($all	= mysql_fetch_array($sql)) {
$site =	$all["site"];
$counter = $all["counter"];

?>
<tr>
 <td width='10%'><font size='2' face='Arial'><center><?=$counter?></center></font></td>
	<td width='90%'><font size='2' face='arial'><a href='<?=$site?>' target='_blank'><b><?=$site?></b></a></font></td>
 </tr>
<?php
}
?>
<table>
<?php
}


#######################################################
## START OF DAY COUNT
#######################################################
if($actions=="count"){

$sql = mysql_fetch_array(mysql_query("SELECT count FROM st_counter"));
$fullsitecount = $sql["count"];
?>
<table width='100%' border='0' cellspacing='' cellpadding='6'>
<tr>
<td bgcolor='<?=$admincolor3?>' width=''><b><font face='Arial' color='#FFFFFF' size='4'>Number of visits: <?=$fullsitecount?> since <?=$installdate?></font></b></td>
</tr>
</table><br>

<table width='100%' border='0' cellspacing='' cellpadding='6'>
<tr>
<td bgcolor='<?=$admincolor3?>' width='170'><b><font face='Arial' color='#FFFFFF' size='4'>Date</font></b></td>
<td bgcolor='<?=$admincolor3?>' width='60'><b><font face='Arial' color='#FFFFFF' size='4'><center>Hits</center></font></b></td>
<td bgcolor='<?=$admincolor3?>' width=''><b><font face='Arial' color='#FFFFFF' size='4'>Graph</font></b></td>
</tr>
<?php

$sql = mysql_query("SELECT count, date_format(date,'%d/%m/%Y - %W') AS formatted FROM st_counter2 ORDER BY date DESC LIMIT 30");

$trbg = "$admincolor1";

$sql2 = mysql_query("SELECT count FROM st_counter2 ORDER BY count DESC LIMIT 1");
$themax = mysql_fetch_array($sql2);
$max = $themax["count"];

while ($thecount = mysql_fetch_array($sql)) {
$count = $thecount["count"];
$date  = $thecount["formatted"];

$width =  intval($count * 100 / $max -1) . "%";

echo " 
<tr bgcolor='$trbg'>
<td width='170'><font size='2' face='Arial'><b>$date</b></font></td>
<td width='60'><font size='2' face='Arial'><b><center>$count</center></b></font></td>
<td>
<img src='images/bar3.gif' ALT='$width' width='$width' height='10'>
</font></td>";

if($trbg=="$admincolor1") $trbg = "$admincolor2"; else $trbg = "$admincolor1";
}

echo "</tr></table><br>";
}
}
include("footer.php");
?>