<?
require("../header.php");
require("../config.php");
print("$menu");
print("<p>$list_message");
print("<p>");
?>


<p>
<form method="post" action"<? echo $PHP_SELF?>">
<input type="hidden" name="check_password" value="<? echo $check_password ?>">
<?php include("../categories_listbox.php"); ?>
<input type=submit value=<? echo $check ?>>
</form>


<table border="0" cellpadding="2" cellspacing="2" width="100%">
<tr>
	<td bgcolor="#E6E6E6"><? echo $title ?></a></td>
	<td bgcolor="#E6E6E6"><? echo $category ?></td>
	<td bgcolor="#E6E6E6"><? echo $modify ?></td>
</tr>
<?
require($full_path_to_db);
$sql_select = "select siteid,sitetitle,sitedescription,sitedate,sitecatid,sitehits,sitevotes, catid, catname from sites,categories where catid=sitecatid AND sitecatid = '$sitecatid'";
$result = mysql_query ($sql_select);

while ($row = mysql_fetch_array($result))
{
        $siteid = $row["siteid"];
        $sitetitle = $row["sitetitle"];
        $sitedescription = $row["sitedescription"];
        $siteurl = $row["siteurl"];
        $sitedate = $row["sitedate"];
        $sitecatid = $row["sitecatid"];
        $sitehits = $row["sitehits"];
        $sitevotes = $row["sitevotes"];
        $catid = $row["catid"];
        $catname = $row["catname"];


print("<tr>");
print("<td width='30%'>$sitetitle</td>");
print("<td width='17%'><a href='index.php?catid=$sitecatid&catname=$catname'  target=\"_blank\">$catname</a></td>");
print("<td width='17%'><a href='add.php?siteid=$siteid&check_password=$check_password'><u>Edit</u></a></td>");
print("</tr>");




}


?>
</table>
<?


require("../footer.php");
?>