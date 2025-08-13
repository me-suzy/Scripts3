<?php 
/*
***************************************************************************
Parameters :

$dbtable
$dbfield
$style
***************************************************************************
*/
?>

<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<?php
//if table does not have asked field notice the user
$fields = mysql_list_fields("$dbname", "$dbtable", $link);
$columns = mysql_num_fields($fields);
for ($x = 0; $x < $columns; $x++) {
	$dbfields[] = mysql_field_name($fields, $x);
}

if (in_array ("$dbfield", $dbfields)) { } else {
	echo "$dbtable does not have a $dbfield row";
	die;
}

//proceed with our work
$query = "SELECT DISTINCT $dbfield FROM $dbtable";
$result = mysql_query($query) or die ("died while opening table<br>Debug info: $query");

$fields = mysql_list_fields("$dbname", "$dbtable", $link);
$columns = mysql_num_fields($fields);
?>

<table align=center width=50% cellpadding="0" cellspacing="1" bordercolorlight="#CCCCCC" bordercolordark="#666666">

<?php
//define the style
$getrows = mysql_query("SELECT * FROM $dbtable", $link);
$rows = mysql_num_rows($getrows)+1;
if ($style == "square") {
	$style = "rowspan=$rows";
} else { $style = ""; }

//write table header
$tabletext = "Viewing all unique $dbfield's in $dbtable";
echo "<tr><td colspan=$columns class=tdark><center><b>$tabletext</b></center></td></tr>\n";

//write data
echo "<tr><td $style class=tddd><b>$dbfield</b></td></tr>\n";
while ($line = mysql_fetch_assoc($result)) {
	while(list($col_name, $col_value) = each($line)) {
			print "<tr><td class=td>$col_value</td></tr>\n";
		}
}
?>

</table>


<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
