<html><?
print "<select name=\"catfatherid\">";
$sql_1 = "select catfatherid,catfullname from $cat_tbl where catid = $category_wanted";
$result = mysql_query ($sql_1);
$row = mysql_fetch_array($result);
$catfatherid_wanted = $row["catfatherid"];
$sql_select = "select * from $cat_tbl where catfullname <> '' order by catfullname";
$result = mysql_query ($sql_select);
print "<option value='0'>Top-level</option>";
while ($row = mysql_fetch_array($result))
{
 $catid3 = $row["catid"];
 $catfullname = $row["catfullname"];
 $catfatherid = $row["catfatherid"];
 print "<option value='$catid3'";
 if ($catfatherid_wanted == $catid3)
 print "selected='selected'";
 print ">";
 print "$catfullname</option>";
}
?>
</select></html>