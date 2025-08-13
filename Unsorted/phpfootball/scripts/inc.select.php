<?php
/*
***************************************************************************
Parameters :

$reqadm 0,1
$incsel tables,fields,rows
$inctable
$incfield
$incvar
$incshow
***************************************************************************
*/
?>

<?php
if (in_array("$userlev", $admins) | $reqadm == "0") {
	//
	//in case we need tables
	//
	if ($incsel == "tables"){
	//get table names
	unset ($tables);
	$result = mysql_list_tables($dbname);
	while ($row = mysql_fetch_row($result)) {
        	$tables[] =  $row[0];
	}

	print "<select name=$incvar>\n";
	foreach ($tables as $table){
		print "<option value=\"$table\" >$table</option>\n";
	}
	print "</select>";
	}
	//
	//in case we need fields
	//
	if ($incsel == "fields"){
	//get field names
	unset ($names);
	$fields = mysql_list_fields("$dbname", "$inctable", $link);
	$num_fie = mysql_num_fields($fields);
	for ($f = 0; $f < $num_fie; $f++) {
		$names[] = mysql_field_name($fields, $f);
	}

	print "<select name=$incvar>\n";	
	foreach ($names as $name){
		$s_name = preg_replace("/src/","",$name);
		print "<option value=\"$name\" >$s_name</option>\n";
	}
	print "</select>";
	}
	//
	//in case we need rows
	//
	if ($incsel == "rows"){
	//get rows
	$query = "SELECT * FROM $inctable";
	$result = mysql_query($query) or die ("Failed to read rows<br>Debug info: $query");
	$num_res = mysql_num_rows($result);

	print "<select name=$incvar>\n";	
	for ($i=0; $i<$num_res; $i++){
		$myrow = mysql_fetch_array($result);
		${$incfield} = $myrow["$incfield"];
		${$incshow} = $myrow["$incshow"];
		print "<option value=\"${$incfield}\" >${$incshow}</option>\n";
	}
	print "</select>";
	}

}else{
	${$incvar} = $who;
	print "<select name=$incvar><option value=${$incvar} selected>${$incvar}</option></select>\n";
}
?>