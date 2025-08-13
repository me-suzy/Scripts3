<?
include_once("inc.php");
print "<b>AD TABLE: $ads_tbl</b><ol>";
$result = mysql_query("select * from $ads_tbl");
for ($i = 0; $i < mysql_num_fields($result); $i++) {
print "<li>".mysql_field_name($result, $i)."</li>\n";
}

print "</ol><p><b>CAT TABLE: $cat_tbl</b><ol>";
$result = mysql_query("select * from $cat_tbl");
for ($i = 0; $i < mysql_num_fields($result); $i++) {
print "<li>".mysql_field_name($result, $i)."</li>\n";
}

print "</ol><p><b>USER TABLE: $usr_tbl</b><ol>";
$result = mysql_query("select * from $usr_tbl");
for ($i = 0; $i < mysql_num_fields($result); $i++) {
print "<li>".mysql_field_name($result, $i)."</li>\n";
}

print "</ol><p><b>TEMPLATE TABLE: template</b><ol>";
$result = mysql_query("select * from template");
for ($i = 0; $i < mysql_num_fields($result); $i++) {
print "<li>".mysql_field_name($result, $i)."</li>\n";
}


print "</ol><p><b>PICTURE TABLE: $pic_tbl</b><ol>";
$result = mysql_query("select * from $pic_tbl");
for ($i = 0; $i < mysql_num_fields($result); $i++) {
print "<li>".mysql_field_name($result, $i)."</li>\n";
}


print "</ol><p><b>OTHER:</b><br />";
$dir = getcwd ();

if (!$dir)
{
        $dir = dirname($SCRIPT_FILENAME);
}

print "Path to program (full path), but remove the /admin end: $dir<p>";


?>
