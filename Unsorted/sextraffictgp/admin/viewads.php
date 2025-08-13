<?php
include ("header.php");

if($access[cancategory]=="1"){

$q = mysql_query("select * from st_categories WHERE cid='$cid'");
$r = mysql_fetch_array($q);
$catname = $r["catname"];
$cid = $r["cid"];
$advert = $r["advert"];
$visable = $r["visable"];
?>
	
<table width="100%" border="0" cellspacing="0" cellpadding="5">
<tr> 
<td bgcolor="<?=$admincolor3?>"><font face="Arial" color="<?=$admincolor4?>" size="4"><b>This is the advert in <font color="#7ACDFE"><?=$catname?></font> category...</b></font></td>
</tr>
</table>

<table width='100%' border="0" cellspacing='1' cellpadding='5' align='center'>
<tr>
<td bgcolor='<?=$admincolor5?>'><?=$advert?></td>
</tr>
</table>

<?php
}
include ("footer.php");
?>