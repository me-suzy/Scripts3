        <select size="1" name="category_wanted" class="txt">
                     <option selected>Choose category</option>

<?

$sql_select = "select * from $cat_tbl";
$result = mysql_query ($sql_select);

while ($row = mysql_fetch_array($result)) 
{
	$catid_n = $row["catid"];
    $catname = $row["catname"];
	print("<option value='$catid_n'");
   
	if ($catid_n == $catid)
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
