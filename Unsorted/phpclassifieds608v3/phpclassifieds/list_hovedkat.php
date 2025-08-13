<select style="FONT-SIZE: 8pt" size="1" name="catid">
<option value="0" selected="selected"><? echo $la_all ?></option>
<?
$sql_select = "select * from $cat_tbl where catfatherid = 0 order by catname";
$result = mysql_query ($sql_select);

while ($row = mysql_fetch_array($result))
{
        $catid = $row["catid"];
        $catfatherid = $row["catfatherid"];
        $catname = $row["catname"];
        $catdescription = $row["catdescription"];
        print("<option value='$catid'>");
        print("$catname");
        print("</option>");
};
?>
</select>