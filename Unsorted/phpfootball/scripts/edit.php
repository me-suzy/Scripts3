<?php 
/*
***************************************************************************
Parameters :

$dbfield
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
//go into table edit more
if (!$dbfield){
echo "<tr><td colspan=2 class=tdark><center>$dbtable editing form</center></td></tr>";

$fields = mysql_list_fields("$dbname", "$dbtable", $link);
$columns = mysql_num_fields($fields);
$query = "SELECT * FROM $dbtable WHERE Id = '$Id'";
$result = mysql_query($query) or die ("died while getting field data<br>Debug info: $query");
$data = mysql_fetch_array($result);
$n = -1;

for ($i = 0; $i < $columns; $i++) {
    $n++;
	$field = mysql_field_name($fields, $i);
	print "<tr><td class=tddd>$field</td><td class=tdark><center><input type=text name=$field class=input size=70% value=\"$data[$n]\"></center></td></tr>\n";
	$names[] = $field;
}

foreach($names as $na) {
    $sql .= " ".$na."='".${$na}."',";
}
$sql = substr($sql, 0, -1);
}
?>

<?php
//go into field edit mode
if ($dbfield){
echo "<tr><td colspan=2 class=tdark><center>$dbtable editing form</center></td></tr>";

$query = "SELECT $dbfield FROM $dbtable";
$result = mysql_query($query) or die ("died while getting field data<br>Debug info: $query");
$data = mysql_fetch_array($result);

${$dbfield} = $data["$dbfield"];

print "<tr><td class=tddd>$dbfield</td><td class=tdark><center><textarea name=field class=input cols=70 rows=10>${$dbfield}</textarea></center></td></tr>\n";

}
?>

<?php
//buton disabling
if($edit == "  Edit  "){
$disabled = "disabled";
}
?>

</table>
<input type="hidden" name="dbtable" value="<?php echo $dbtable; ?>">
<input type="hidden" name="dbfield" value="<?php echo $dbfield; ?>">
<input type="submit" name="edit" value="  Edit  " class="submit" <?php echo $disabled; ?> >
</form>

<?php
//table edit
if($sql && $edit == "  Edit  " && !$dbfield){
$query = "UPDATE $dbtable SET $sql WHERE Id = '$Id'";
$result = mysql_query($query) or die ("died while updating fields<br>Debug info: $query");
//echo mysql_errno().": ".mysql_error()."<BR>";
echo "<b>Sucessfully edited entry<br></b>";
}
?>

<?php
//field edit
if($edit == "  Edit  " && $dbfield){
$query = "UPDATE $dbtable SET $dbfield='$field'";
$result = mysql_query($query) or die ("died while updating fields<br>Debug info: $query");
//echo mysql_errno().": ".mysql_error()."<BR>";
echo "<b>Sucessfully edited entry<br></b>";
}
?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
