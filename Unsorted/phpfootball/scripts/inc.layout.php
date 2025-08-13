<?php require("inc.db.php"); ?>

<?
//get field names
$lay_fields = mysql_list_fields("$dbname", "Layout", $link);
$num_fie = mysql_num_fields($lay_fields);
for ($f = 0; $f < $num_fie; $f++) {
	$lay_names[] = mysql_field_name($lay_fields, $f);
}

//get row values
$query = "SELECT * FROM Layout";
$result = mysql_query($query);
$num_res = mysql_num_rows($result);
for ($r=0; $r<$num_res; $r++){
	$rows = mysql_fetch_array($result);
}

//create variables
foreach ($lay_names as $lay_name){
	${$lay_name} = $rows["$lay_name"];
}
?>





