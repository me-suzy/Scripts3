<select style="FONT-SIZE: 8pt" size="1" name="catid">
                     <option value="0" selected>All categories</option>

<?


require("db.php");
require("config/general.inc.php");
$sql_select = "select * from $cat_tbl where catfatherid = 0 order by catname";
$result = mysql_query ($sql_select);

while ($row = mysql_fetch_array($result)) {

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