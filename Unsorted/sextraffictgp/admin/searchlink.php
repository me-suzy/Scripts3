<?php
include("header.php");

if($access[cangalleries]=="1"){

$select = "SELECT DISTINCT *";
$from   = " FROM st_links";
$where  = " WHERE linkid > 0 ";


if ($searchtext == "") {
echo "<table width='550' border='0' cellspacing='0' cellpadding='3' >
       <tr> 
        <td><font size='2' face='Arial'><b>Sorry - no matches were found.</b><br><br>Please go <a href='javascript:history.back(-1)'><b>back</b></a> and choose a differrent criteria and try again</font></td>
		   </tr>
		   </table>";
include("footer.php");exit;}


if ($searchtext != "") {
$where .= " AND (des LIKE '%$searchtext%' OR name LIKE '%$searchtext%' OR url LIKE '%$searchtext%') ORDER BY date DESC";
}
?>
<table width="550" border="0" cellspacing="0" cellpadding="4"><tr> 
<td bgcolor="<?=$menucolor1?>"><font face="Arial" size="4"><b>Your Search Results</b></font></td>
</tr></table><br>
<?php
$sxgs = mysql_query($select . $from . $where);
?>
<table width='<?=$tablewidth?>' border='0' cellspacing='0' cellpadding='5'><tr>
<?php

if (mysql_num_rows($sxgs) > 0) {
while ($result = mysql_fetch_array($sxgs)) {
$linkid = $result["linkid"];
$catid = $result["catid"];
$des = $result["des"];
$url = $result["url"];
$url2 = $result["url"];
$clicks = $result["clicks"];
$date = $result["date"];
if(!empty($select)) {

$catlists = mysql_query("SELECT catname,cid FROM st_categories WHERE cid='$catid'");
$cat = mysql_fetch_array($catlists);
$cid = $cat["cid"];
$catname = $cat["catname"];

// Highlight sreach text color
$des = str_replace($searchtext, "<font color=\"red\"><b>$searchtext</b></font>", $des);
$url2 = str_replace($searchtext, "<font color=\"red\"><b>$searchtext</b></font>", $url2);
$name = str_replace($searchtext, "<font color=\"red\"><b>$searchtext</b></font>", $name);

?>
<td>
<font size='3' color='blue' face='arial'><b>Link # <?=$linkid?></b></font>&nbsp;&nbsp;
<font size='3' face='arial'><a href='<?=$url?>'><b><?=$des?></b></a></font>&nbsp;&nbsp;&nbsp;
<a href="links.php?linkid=<?=$linkid?>&actions=editlink"><img src="images/icon-edit.gif" border="0"></a>&nbsp;&nbsp;<a href="actions.php?linkid=<?=$linkid?>&action=deletelink"><img src="images/icon-del.gif" border="0"></a>
<font size='2' face='arial'><b>&nbsp;&nbsp;<?=$date?> </b></font><br>
<font size='2' face='arial'><?=$des?><br><font color='#006633' size='2'><?=$url2?></font><br>
<font size='2' face='arial'>Category: <b><a href="editcategory.php?cid=<?=$cid?>"><?=$catname?></a></b></font></font><hr></td><tr>

<?php
if($trbg=="$color3") $trbg = "$color2"; else $trbg = "$color3";

}
}
echo "</table>";
}

else 
{
echo "<table width='550' border='0' cellspacing='0' cellpadding='3' >
       <tr> 
        <td><font size='2' face='Arial'><b>Sorry - no matches were found.</b><br><br>Please go <a href='javascript:history.back(-1)'><b>back</b></a> and choose a differrent criteria and try again</font></td>
		   </tr>
		   </table>";
}
}
include("footer.php");
?>
</table>
</body>
</html>