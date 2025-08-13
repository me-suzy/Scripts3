<select size="1" name="category_wanted">
<option selected='selected'><? echo $choose_cat ?></option>

<?
$sql_select = "select * from $cat_tbl order by catfullname";
$result = mysql_query ($sql_select);
while ($row = mysql_fetch_array($result))
{

        $catid = $row["catid"];
        $catfatherid = $row["catfatherid"];
        $catname = $row["catname"];
        $catdescription = $row["catdescription"];
        $catfullname = $row["catfullname"];
        print("<option value='$catid'>");
        print("$catfullname");
        print("</option>");
}
?>
</select>