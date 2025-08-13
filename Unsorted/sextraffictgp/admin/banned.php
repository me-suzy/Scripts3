<?php
include ("header.php");

if($access[canbanwords]=="1"){
#######################################################
## START OF SUBMITTED ADDING LINK
#######################################################

if($addbanned){

$sql = mysql_query("INSERT INTO st_banned SET banned_url='$bannedurl'");
if (!$sql){
echo("<font size='2' face='arial'><b>Banned word added</b></font>");
} else {
echo("Error: " .
mysql_error() . "");
}
?>
<meta http-equiv ="Refresh" content = "0 ; URL=banned.php">
<?php
}


#######################################################
## START OF ADDING LINK
#######################################################
if($add=="banned"){
?>
<FORM ACTION="<?=$PHP_SELF?>" METHOD=POST>
<table width="550" border="0" cellspacing="0" cellpadding="7">
<tr>
<td bgcolor="<?=$admincolor3?>"><font face="Arial" size="3" color="#FFFFFF"><b>Add a banned word</b></font><br>
<font size="1" face="Verdana" color="#FFFFFF">Note: If you add a word like &quot;.com&quot; all submissions with &quot;.com&quot; in them will not be allowed into the database and will show the error in your template.<br>
Another example is &quot;sex&quot;, if someone entered &quot;www.mysexsite.com&quot; the submission will not be accepted.</font></td>
</tr>
</table>
  <table width="550" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="120" height="14"><div align="right"><font face="Arial" size="2"><b>Banned Word:</b></font></div></td>
      <td valign="top" height="14"><input type="text" name="bannedurl" size="45"><br>
	<font face="Verdana" size="1">URL or E-Mail of submitter</font></td>
    </tr>
  </table>  
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="7">
    <tr>
      <td valign="top" width="120" bgcolor="<?=$admincolor3?>">&nbsp;</td>
      <td valign="top" colspan="4" bgcolor="<?=$admincolor3?>"><input type="submit" name="addbanned" value="SUBMIT"></td>
    </tr>
  </table>
</form>
<?php
include("footer.php");
exit;}
###################################
?>


<table width="100%" border="0" cellspacing="0" cellpadding="5">
<tr> 
<td bgcolor="<?=$admincolor3?>"><font face="Arial" color="<?=$admincolor4?>" size="4"><b>Banned Words </b></font></td>
</tr>
</table>

<table width="100%" border="0" cellspacing="1" cellpadding="5">
<tr>
<td width="50" bgcolor="<?=$admincolor1?>"><div align="center"><font size="3" face="Arial"><b>ID</b></font></div></td>
<td width="" bgcolor="<?=$admincolor1?>"><font size="3" face="Arial"><b>Banned name</b></font></td>
<td width="50" bgcolor="<?=$admincolor1?>"><font size="3" face="Arial"><b>DELETE</b></font></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="5">
<tr>
<?php
$q = mysql_query("select * from st_banned order by bid ASC");
while($r = mysql_fetch_array($q)){
$bid = $r["bid"];
$banned = $r["banned_url"];

?>
<td width="50" bgcolor="<?=$admincolor55?>"><div align="center"><font size="2" face="Arial"><b><?=$bid?></b></font></div></td>
<td width="" bgcolor="<?=$admincolor55?>"><font size="2" face="Arial"><b><?=$banned?></b></font></td>
<td width="50" bgcolor="<?=$admincolor55?>"><div align="center"><font size="2" face="Arial">[<a href="actions.php?bid=<?=$bid?>&action=deletebannedurl"><b> DELETE </b></a>] </font></div></td>
</tr>
<?php
}
?>
</table>
<?php
}
include("footer.php");
?>