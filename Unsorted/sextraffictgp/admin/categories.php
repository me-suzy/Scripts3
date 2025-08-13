<?php
include ("header.php");

if($access[cancategory]=="1"){

if($submit){
while (list($cid,$catorder)=each($all)) {
$sql = mysql_query("UPDATE st_categories SET catorder='$catorder' WHERE cid=$cid");
if(!$sql) {
 echo("<p>Problem : " .
   mysql_error() . "</p>");
}
}
while(@list($cid)=each($deleteit)) {
$sql2 = mysql_query("DELETE FROM st_categories WHERE cid=$cid");
    if (!$sql2) {	
  echo("<p><br>".
       "Error: " . mysql_error() . "</p>");
  exit();
}
}
}
?>
<form action="<?=$PHP_SELF?>" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
 <tr> 
  <td bgcolor="<?=$admincolor3?>"><font face="Arial" color="<?=$admincolor4?>" size="4"><b>Categories</b></font></td>
 </tr>
</table>

<table width="100%" border="0" cellspacing="1" cellpadding="5">
 <tr>
  <td width="50" bgcolor='<?=$admincolor1?>'><div align="center"><font size="2" face="Arial"><b>ID</b></font></div></td>
  <td width='50' bgcolor='<?=$admincolor1?>'><font size="2" face="Arial"><b>Order</b></font></td>
  <td width='' bgcolor='<?=$admincolor1?>'><font size="2" face="Arial"><b>Name</b></font></td>
  <td width="70" bgcolor='<?=$admincolor1?>'><div align="center"><font size="2" face="Arial"><b>ADS</b></font></div></td>
  <td width="70" bgcolor='<?=$admincolor1?>'><div align="center"><font size="2" face="Arial"><b>Visable</b></font></div></td>
  <td width="70" bgcolor='<?=$admincolor1?>'><div align="center"><font size="2" face="Arial"><b># Links</b></font></div></td>
  <td width="70" bgcolor='<?=$admincolor1?>'><div align="center"><font size="2" face="Arial"><b>Edit</b></font></div></td>
  <td width="50" bgcolor='<?=$admincolor1?>'><div align="center"><font size="2" face="Arial"><b>Delete</b></font></div></td>
</tr>

<?php
$q = mysql_query("select * from st_categories order by cid ASC");
while($r = mysql_fetch_array($q)){
$catname = $r["catname"];
$cid = $r["cid"];
$advert = $r["advert"];
$visable = $r["visable"];
$theorder = $r["theorder"];
$catorder = $r["catorder"];
if($visable=="Y"){ $visable = "<font color='blue'><b>ONLINE</b></font>";} else { $visable = "<font color='red'><b>OFFLINE</b></font>";} 

$row1 = mysql_fetch_row(mysql_query("SELECT COUNT(*) AS count FROM st_links WHERE catid='$cid'"));
$count = $row1[0];
$counted = "$count";
if($count > 0) {  $counted = " <font size='2'><b>$count</b></font>";} else {  $counted = "";}

$row = mysql_fetch_row(mysql_query("SELECT COUNT(*) AS ads FROM st_categories WHERE cid='$cid' AND advert!=''"));
$ads = $row[0];
if($ads > 0){ $ads = "<b>YES</b>";} else { $ads = "";}

$row = mysql_fetch_row(mysql_query("SELECT COUNT(*) AS thevisable FROM st_categories WHERE cid='$cid' AND visable='N'"));
$thevisable = $row[0];
if($thevisable > 0){ $admincolor55 = "#FEDEE0";} else { $admincolor55 = "$admincolor1";}

?>
<tr>
 <td width="50" bgcolor="<?=$admincolor55?>"><div align="center"><font size="2" face="Arial"><b><?=$cid?></b></font></div></td>
 <td width="50" bgcolor="<?=$admincolor55?>"><input type="text" name="all[<?=$cid?>]" value="<?=$catorder?>" size="2" maxlength="3"></td>
 <td width="" bgcolor="<?=$admincolor55?>"><font size='2' face='Arial'><?=$catname?></font></td>
 <td width="70" bgcolor="<?=$admincolor55?>"><div align="center"><font size="2" face="Arial"><a href="viewads.php?cid=<?=$cid?>"><b><?=$ads?></b></a></font></div></td>
 <td width="70" bgcolor="<?=$admincolor55?>"><div align="center"><font size="2" face="Arial"><?=$visable?></font></div></td>
 <td width="70" bgcolor="<?=$admincolor55?>"><div align="center"><font size="2" face="Arial"><?=$counted?></font></div></td>
 <td width="70" bgcolor="<?=$admincolor55?>"><div align="center"><font size="2" face="Arial">[<a href="editcategory.php?cid=<?=$cid?>"><b> Edit </b></a>] </font></div></td>
 <td width="50" bgcolor='<?=$admincolor1?>'><div align="center"><input type="checkbox" name="deleteit[<?=$cid?>]" value="Y"></div></td>
</tr>
<?php
}
?>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="5">
 <tr>
  <td width="50%"><div align="right"><input type=reset value="Reset Form"></div></td>
  <td width="50%"><input type="submit" name="submit" value="Update order"></td>
 </tr>
</table>

<?php
}
include("footer.php");
?>