<?php
include ("header.php");

if($access[cantemplates]=="1"){
if($submit){
$sql = mysql_query("UPDATE st_template SET tempcontent='$tempcontent' WHERE templateid=$templateid");
  if (!$sql){
    echo("<p>Problem : " .
         mysql_error() . "</p>");
  }
  else { echo "<font size='3' face='arial'><b>Template Updated!<br> Thank you...</b></font>";}

?>
<meta http-equiv ="Refresh" content = "1 ; URL=templates.php">
<?php

exit; }// End of Submit


if($action=="edit"){
$sql = mysql_query("SELECT * FROM st_template WHERE templateid='$templateid'");
$result = mysql_fetch_array($sql);
$title = $result["title"];
$tempinfo = $result["tempinfo"];
$tempcontent = $result["tempcontent"];

if($templateid=="13"){ echo "";} else {
?>
<form action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="templateid" value="<?=$templateid?>">
<table width="500" border="0" cellspacing="0" cellpadding="5">
<tr>
<td><font size="4"><b><font face="Arial">Edit Template: <?=$title?></font></b></font></td>
</tr>
<tr>
<td><textarea name="tempcontent" cols="55" rows="15"><?=$tempcontent?></textarea></td>
</tr>
<tr>
<td><font face="Arial" size="2"><b>Description:</b> <?=$tempinfo?></font></td>
</tr>
</table>

<table width="500" border="0" cellspacing="0" cellpadding="5">
<tr> 
<td width="350"><div align="left"><input type=reset value="Reset Form"><input type="submit" name="submit" value="SUBMIT"></div></td>
</tr>
</table>
</form>
<?php
}
exit;}


if($action=="temps"){
?>
<table width="500" border="0" cellspacing="0" cellpadding="4" bgcolor="#333366">
<tr> 
<td><font size="3"><b><font face="Arial" color="#FFFFFF">Templates</font></b></font></td>
</tr>
</table>

<table width="500" border="1" bordercolor="FFFFFF" cellspacing="" cellpadding="4" bgcolor="#F0F3FF">
 

<?php
$sql = mysql_query("SELECT * FROM st_template WHERE catset='$catset'");
while($r = mysql_fetch_array($sql)){
$templateid = $r["templateid"];
$tempsetid = $r["tempsetid"];
$title = $r["title"];
$tempcontent = $r["tempcontent"];
$tempinfo = $r["tempinfo"];

if($templateid=="13"){ echo "";} else {
?>

<tr>
<td width="180"><font face="verdana" size="1"><?=$title?> &nbsp;</b></font></td>
<td width=""><font face="verdana" size="1"><?=$tempinfo?></font></td>
<td width="40"><center><font face="verdana" size="1"><b><a href="templates.php?templateid=<?=$templateid?>&action=edit">[edit]</a></b></font></center></td>


<?php
}
}
echo "</tr></table>";
exit;}
?>
<table width="500" border="0" cellspacing="0" cellpadding="4" bgcolor="#333366">
<tr> 
<td><font size="3"><b><font face="Arial" color="#FFFFFF">Template Pages</font></b></font></td>
</tr>
</table>
<?php
$sql = mysql_query("SELECT * FROM st_template_cats");
while($r = mysql_fetch_array($sql)){
$tcid = $r["tcid"];
$tempcatname = $r["tempcatname"];
?>
<table width="500" border="1" bordercolor="FFFFFF" cellspacing="" cellpadding="4" bgcolor="#F0F3FF">
<tr> 
<td width=""><font face="Arial" size="2"><b><a href="templates.php?catset=<?=$tcid?>&action=temps"><?=$tempcatname?></a></b></font></td>
</tr>
</table>

<?php
}
}
include("footer.php");
?>