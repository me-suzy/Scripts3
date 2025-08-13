<?php
include("header.php");

if($access[cangalleries]=="1"){

$sql = mysql_query("SELECT * FROM st_links WHERE linkid LIKE '$linkid'");

if ($linkid == "") {
echo "<table width='550' border='0' cellspacing='0' cellpadding='3' >
       <tr> 
        <td><font size='2' face='Arial'>Sorry - no matches were found.<br>Please go <a href='javascript:history.back(-1)'><b>back</b></a> and choose a differrent criteria and try again</font></td>
		   </tr>
		   </table>";
include("footer.php");exit;}

?>
<table width="<?=$tablewidth?>" border="0" cellspacing="0" cellpadding="5"><tr> 
<td bgcolor="<?=$menucolor1?>"><font face="Arial" size="4"><b>Your Search Results for link ID: <font color="red"><?=$linkid?></font></b></font></td>
</tr></table><br>
<?php
?>
<table width='<?=$tablewidth?>' border='0' cellspacing='0' cellpadding='5'><tr>
<?php

if (mysql_num_rows($sql) > 0) {
while ($result = mysql_fetch_array($sql)) {
$linkid = $result["linkid"];
$catid = $result["catid"];
$name = $result["name"];
$url = $result["url"];
$des = $result["des"];

if(!empty($sql)) {

$cat = @mysql_fetch_array(mysql_query("SELECT * FROM st_categories WHERE cid=$catid"));
$cid = $cat["cid"];
$catname = $cat["catname"];

?>
<td>
<font size='3' color='red' face='arial'><b>link # <?=$linkid?></b></font> &nbsp;&nbsp;
<font size='3' face='arial'><a href='<?=$url?>'><b><?=$des?></b></a></font>&nbsp;&nbsp;&nbsp;
<a href="links.php?linkid=<?=$linkid?>&actions=editlink"><img src="images/icon-edit.gif" border="0"></a>&nbsp;&nbsp;<a href="actions.php?linkid=<?=$linkid?>&action=deletelink"><img src="images/icon-del.gif" border="0"></a><br>
<font color='#006633' size='2'><?=$url?></font><br>
<font size='2' face='arial'>Category: <a href='category.php?cid=<?=$cid?>'><b><?=$catname?></b></a></font></td><tr>

<?php
if($trbg=="$color3") $trbg = "$color2"; else $trbg = "$color3";

}
}
echo "</table>";
}

else 
{
print "<table bgcolor='0066FF' width='$tablewidth' border='0' cellspacing='0' cellpadding='3' align='center'><tr><td><font size='3' face='Arial' color='#FFFFFF'><b>Sorry - no matches were found.<br>Please choose a differrent criteria and try again</font></b></td></tr></table>";
}
}
include("footer.php");
?>
</table>
</body>
</html>