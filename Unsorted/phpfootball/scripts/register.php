<?php 
/*
***************************************************************************
Parameters :

$Username
$Password
***************************************************************************
*/
?>

<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<?php
$query = "SELECT * FROM $dbtable";
$result = mysql_query($query) or die ("died while opening table<br>Debug info: $query");
?>

<form name="form" action="<?php echo "{$_SERVER['PHP_SELF']}"; ?>" target="top" method="post">
<input type="hidden" name="Password" value="<?php echo $Password; ?>">
<input type="hidden" name="Username" value="<?php echo $Username; ?>">
<input type="hidden" name="Userlevel" value="<?php echo $Userlevel; ?>">
<input type="hidden" name="dbtable" value="<?php echo $dbtable; ?>">

<table align=center width=50% cellpadding="0" cellspacing="1" >

<?php
//create form based on the given databsase
if(!$register){
echo "<tr><td colspan=2 class=tdark><center>New User Registration Form</center></td></tr>";
}

$fields = mysql_list_fields("$dbname", "$dbtable", $link);
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
	$field = mysql_field_name($fields, $i);
if ($field != "Id" && $field != "Last_Login" && $field != "Username" && $field != "Password" && $field != "Userlevel" && !$register){
print "<tr><td class=tddd>$field</td><td class=td><input type=text class=input size=60% maxlength=50 name=$field value=${$field}></td></tr>";
$names[] = $field;
}
}
//concatenate the form elements in mysql query formated string
if ($names){
foreach($names as $na) {
	$names_n .= "".$na.",";
	$names_v .= "'".${$na}."',";
}
}
//md5 the password and add it and the username to the query 
$Password = md5($Password);
$names_n .= "".Password.",";
$names_v .= "'".$Password."',";
//also add the username ad userlevel
$names_n .= "".Userlevel.",";
$names_v .= "'".$Userlevel."',";
$names_n .= "".Username.",";
$names_v .= "'".$Username."',";
//remove the trailing "," from the query end
$names_n = substr($names_n, 0, -1);
$names_v = substr($names_v, 0, -1);
?>

</table>
<?php
if(!$register){
echo "<input type=submit name=register value=Register class=submit>";
}
?>
</form>

<?php
//insert the user entry into the database
if ($Contact_name && $Contact_email && $Username && $Password && $names_n && $names_v && $register == "Register"){
$query = "INSERT INTO $dbtable ($names_n) VALUES ($names_v)";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
echo "<br>Sucessfully created user<br>";
}
?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
