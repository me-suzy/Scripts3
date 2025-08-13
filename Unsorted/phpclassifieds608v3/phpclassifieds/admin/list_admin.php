<select size="1" name="sitecatid">
<option selected="selected"><? echo $choose_cat ?></option>

<?
$sql_select = "select * from $cat_tbl where allowads = 'on' order by catfullname";
$result = mysql_query ($sql_select);

while ($row = mysql_fetch_array($result))
{
        $catid = $row["catid"];
        $catfatherid = $row["catfatherid"];
        $catname = $row["catname"];
        $catfullname = $row["catfullname"];
        $catdescription = $row["catdescription"];
        print("<option value='$catid'>");
        print("$catfullname");
        print("</option>");
}
?>
</select>