<?php
include("../inc/config.php");
include("header.php");
$listData="SELECT * FROM ".$tst["tbl"]["articles"]." ORDER BY id DESC";
$list=mysql_query($listData);
if(mysql_num_rows($list)<1) {
	echo $tst["lang"]["noArticles"];
	exit;
}
echo'<form method=post action="mngAnnc.php">';
echo'<table class="text" cellspacing="1" cellpadding="2" width="100%" align="center">';
echo'<tr bgcolor="#FFFFFF">';
echo'<td colspan="6"><img src="../images/arrow_down.gif" align="left" width="38" height="22" border=0 alt="">';
echo'<input class="formButton" type="submit" value="'.$tst["lang"]["delSelectedBtn"].'"> <input class="formButton" type="button" value="'.$tst["lang"]["deleteAllBtn"].'" onclick="parent.location=\'mngAnnc.php?act=deleteAll\'"></td>';
echo'</tr>';
echo'<tr bgcolor="#FFFFFF">';
echo'<td width="10"></td>';
echo'<td width="15">'.$tst["lang"]["status"].'</td>';
echo'<td>'.$tst["lang"]["newsHeading"].'</td>';
echo'<td>'.$tst["lang"]["datePosted"].'</td>';
echo'<td colspan="2" align="center">'.$tst["lang"]["management"].'</td>';
echo'</tr>';
$rows=0;
while($row=mysql_fetch_array($list)) {
	if($row['status']==0) {
		$ns=1;
	}elseif($row['status']==1) {
		$ns=0;
	}
	if($rows%2) {
		$rowBg=$row1bg;
	}else{
		$rowBg=$row2bg;
	}
	echo'<tr bgcolor='.$rowBg.'>';
	echo'<td width="10"><input type="checkbox" name="delMulti[]" value="'.$row['id'].'"></td>';
	echo'<td  align="center"><a class="link1"  href="mngAnnc.php?act=changeStatus&id='.$row['id'].'&newStatus='.$ns.'"><img src="../images/status_'.$row['status'].'.gif" width="15" height="15" border=0 alt="'.$tst["lang"]["click2ChangeStatus"].'"></a></td>';
	echo'<td>'.$row['heading'].'</td>';
	$newDate=reformat_date($row['datePosted']);
	echo'<td>'.$newDate.'</td>';
	echo'<td align="center"><a class="link1"  href="mngAnnc.php?act=edit&id='.$row['id'].'">'.$tst["lang"]["edit"].'</a></td>';
	echo'<td align="center"><a class="link1"  href="mngAnnc.php?act=delete&id='.$row['id'].'">'.$tst["lang"]["delete"].'</a></td>';
	echo'</tr>';
	$rows++;
}
echo'<tr bgcolor="'.$buttonsRowBg.'">';
echo'<td colspan="6"><img src="../images/arrow_ltr.gif" width="38" height="22" border=0 alt="">';
echo'<input class="formButton" type="submit" value="'.$tst["lang"]["delSelectedBtn"].'"> <input class="formButton" type="button" value="'.$tst["lang"]["deleteAllBtn"].'" onclick="parent.location=\'mngAnnc.php?act=deleteAll\'"></td>';
echo'</tr>';
echo'</table>';
echo'<input class="formButton" type="hidden" name="act" value="deleteMulti">';
echo'</form>';
include("footer.php");
?>