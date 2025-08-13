<select size="1" name="catfatherid">
<option value"0" selected>0</option>
<?
$sql_select = "select * from $cat_tbl";
//$sql_select = "select * from $cat_tbl where catfatherid <> 0";
// marker farkategori til denne valgte catid_n. Denne ligger i $catid
$result = mysql_query ($sql_select);

while ($row = mysql_fetch_array($result)) 
{
	$catid3 = $row["catid"];
    $catname = $row["catname"];
	print("<option value='$catid3'");
	
	if ($catid3 == $catfatherid)
	{
		print("selected");
	}
	print(">$catname</option>");
}
?>
</select>
<?
$catname = 0;
?>