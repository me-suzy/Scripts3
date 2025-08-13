<?php require("inc.db.php"); ?>

<html>
<head>
<title>PHP Football</title>
<link rel="stylesheet" href="style.css">
</head>
<body <?php echo $body_extra; ?> >

<table width="100%" class=table>
  <tr> 
    <td width=33% class=td align=center height=50>
<a href=http://www.phpfootball.sourceforge.net target=_top><img src=images/phpfootball_logo.gif border=0></a>
</td>
<td class=td width=33%>
<div align=center><b>PHP Football</b> <a href=http://www.nextdesign.eu.org/ target=_blank> (support)</a><br>
<b>Version : <?php echo $versionsrc; ?></b> <a href=update.php> (update)</a><br>
<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>A Nextdesign Product</b></font></div>
</td>
<td background="images/back.gif" class=td width=33%>
&nbsp;<?php //include("http://www.nextdesign.eu.org/banners.php"); ?>
</td>

  </tr>
  <tr>
    <td class=tddd align=center height=20 colspan=3>
<?
$query = "SELECT * FROM Modules";
$result = mysql_query($query) or die ("Failed read modules<br><span class=span0><span class=span1>Debug info: $query</span></span>");
$num_res = mysql_num_rows($result);
for ($i=0; $i<$num_res; $i++){ $myrow = mysql_fetch_assoc($result); }
foreach ($myrow as $row){ echo $row; }
unset ($i);
?>
   </td>
  </tr>
</table>

<div align=center>

