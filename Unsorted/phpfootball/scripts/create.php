<?php 
/*
***************************************************************************
Parameters :

$dbtable
$id
***************************************************************************
*/
?>

<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<?php require("inc.auth.php"); ?>
<?php 
if ($userlev == "guest") {
	echo "<font color=red><b>You are not allowed to view this page</b></font>";
	die;
}
?>

<form name="Create" action="<?php echo "{$_SERVER['PHP_SELF']}"; ?>" method="post">
<table width=50% cellpadding="0" cellspacing="1" bordercolorlight="#666666" bordercolordark="#CCCCCC">

<?php

echo "<tr><td colspan=2 class=tdark><center>$dbtable creation form</center></td></tr>";

$fields = mysql_list_fields("$dbname", "$dbtable", $link);
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
	$field = mysql_field_name($fields, $i);
	echo "<tr><td class=tddd>$field</td><td class=tdark><center><input type=text name=$field class=input size=70%></center></td></tr>\n";
	$names[] = $field;
}
?>

</table>
<input type="hidden" name="dbtable" value="<?php echo $dbtable; ?>">
<input type="submit" name="create" value="  Create  " class="submit">
</form>

<?php
//get names and values
foreach($names as $na) {
	$names_n .= "".$na.",";
	$names_v .= "'".${$na}."',";
}
$names_n = substr($names_n, 0, -1);
$names_v = substr($names_v, 0, -1);
//write data
if($names_n && $names_v && $create == "  Create  "){
$query = "INSERT INTO $dbtable ($names_n) VALUES ($names_v)";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
echo "<b>Sucessfully created entry<br></b>";
//echo mysql_errno().": ".mysql_error()."<BR>";
}
?>

<?php //include("inc.prepare.php"); ?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
