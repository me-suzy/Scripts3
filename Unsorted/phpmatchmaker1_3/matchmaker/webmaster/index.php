<? include "admheader.php"; ?>


<h3>Simple statistics</h3><br>
<?
$sql = "select count(*) from user";
$result = mysql_query($sql);
$rad = mysql_fetch_array($result);
$usr_total = $rad["count(*)"];

$sql = "select count(*) from user where sex like 'Male%'";
$result = mysql_query($sql);
$rad = mysql_fetch_array($result);
$male_total = $rad["count(*)"];

$sql = "select count(*) from user where sex like 'Female%'";
$result = mysql_query($sql);
$rad = mysql_fetch_array($result);
$female_total = $rad["count(*)"];

$others_total = $usr_total - $male_totalt - $female_totalt;

$sql = "select count(*) from matchprofiles";
$result = mysql_query($sql);
$rad = mysql_fetch_array($result);
$match_total = $rad["count(*)"];

$sql = "select count(*) from mail";
$result = mysql_query($sql);
$rad = mysql_fetch_array($result);
$mail_total = $rad["count(*)"];

$sql = "select count(*) from favorites";
$result = mysql_query($sql);
$rad = mysql_fetch_array($result);
$fav_total = $rad["count(*)"];


$sql = "select count(*) from visitors";
$result = mysql_query($sql);
$rad = mysql_fetch_array($result);
$vis_total = $rad["count(*)"];

?>

<table width="250" bgcolor="Silver">
<tr>
<td><font face="Verdana" size="1">Num. users</font></td>
<td><font face="Verdana" size="1"><b><? echo $usr_total ?></b></font></td>
</tr>

<tr>
<td><font face="Verdana" size="1">Num. Male</font></td>
<td><font face="Verdana" size="1"><b><? echo $male_total ?></b></font></td>
</tr>

<tr>
<td><font face="Verdana" size="1">Num. Female</font></td>
<td><font face="Verdana" size="1"><b><? echo $female_total ?></b></font></td>
</tr>

<tr>
<td><font face="Verdana" size="1">Num. Others</font></td>
<td><font face="Verdana" size="1"><b><? echo $others_total ?></b></font></td>
</tr>

<tr>
<td><font face="Verdana" size="1">Num. Matches</font></td>
<td><font face="Verdana" size="1"><b><? echo $match_total ?></b></font></td>
</tr>


<tr>
<td><font face="Verdana" size="1"># of mails current present</font></td>
<td><font face="Verdana" size="1"><b><? echo $mail_total ?></b></font></td>
</tr>


<tr>
<td><font face="Verdana" size="1"># of favorites</font></td>
<td><font face="Verdana" size="1"><b><? echo $fav_total ?></b></font></td>
</tr>

<tr>
<td><font face="Verdana" size="1"># of visitors (members on detail.php)</font></td>
<td><font face="Verdana" size="1"><b><? echo $vis_total ?></b></font></td>
</tr>

</table>

<? include "admfooter.php"; ?>