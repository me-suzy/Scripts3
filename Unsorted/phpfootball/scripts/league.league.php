<?php 
/*
***************************************************************************
Parameters :

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

<form name="Setup" action="<?php echo "{$_SERVER['PHP_SELF']}"; ?>" method="post">
<table width=60% cellpadding="0" cellspacing="1" bordercolorlight="#666666" bordercolordark="#CCCCCC">

<?php
//
//first page
//
if (!$setup | !$league_name | !$league_fullname | $num_agegroups=="" | $num_divisions==""){
$value = "Continue";
echo "
<tr>
<td class=tddd align=center>
<input size=2% class=td type=text maxlength=2 name=num_agegroups value=0>
</td>
<td class=tdark>
Agegroups
</td>
</tr>
<tr>
<td class=tddd align=center>
<input size=2% class=td type=text maxlength=2 name=num_divisions value=0>
</td>
<td class=tdark>
Divisions
</td>
</tr>
<tr><td class=tddd colspan=2><center>Setup League</center></td></tr>
<tr><td class=td><center>Name</center></td><td class=td ><center>Fullname</center></td></tr>
<tr>
<td class=tddd align=center>
<input size=15% maxlength=15 class=input type=text name=league_name>
</td>
<td class=td align=center>
<input size=85% maxlength=100 class=input type=text name=league_fullname>
</td>
</tr>
";
}
?>

<?php
//
//second page
//
if ($league_name !="" && $league_fullname !="" && $num_agegroups !="" && $num_divisions !="" && $setup == "Continue") {
$value = "Finish";
echo"
<input type=hidden name=league_name value=\"$league_name\">
<input type=hidden name=league_fullname value=\"$league_fullname\">
<input type=hidden name=num_agegroups value=\"$num_agegroups\">
<input type=hidden name=num_divisions value=\"$num_divisions\">
";
echo "<tr><td class=tdark colspan=2><center>$league_name</center></td></tr>";
//see if we have agegroups
if ($num_agegroups == "0"){
echo "<tr><td class=tddd colspan=2><center>Your leage will have no Agegroups</center></td></tr>";
}else{
	echo "<tr><td class=tddd colspan=2><center>Setup Agegroups</center></td></tr>";
	echo "<tr><td class=td><center>Name</center></td><td class=td ><center>Fullname</center></td></tr>";
	for ($i=0; $i<$num_agegroups; $i++){
		echo "<tr><td class=tddd><input size=15% maxlength=15 class=input type=text name=agegroup_n$i></td><td class=td><input size=85% maxlength=100 class=input type=text name=agegroup_v$i></td></tr>";
	}
	unset($i);
}
//see if we have divisions
if ($num_divisions == "0"){
echo "<tr><td class=tddd colspan=2><center>Your leage will have no Divisions</center></td></tr>";
}else{
	echo "<tr><td class=tddd colspan=2><center>Setup Divisions</center></td></tr>";
	echo "<tr><td class=td><center>Name</center></td><td class=td ><center>Fullname</center></td></tr>";
	for ($i=0; $i<$num_divisions; $i++){
		echo "<tr><td class=tddd><input size=15% maxlength=15 class=input type=text name=division_n$i></td><td class=td><input size=85% maxlength=100 class=input type=text name=division_v$i></td></tr>";
	}
	unset($i);
}
}
?>

</table>
<?php
if ($value == "Continue" | $value == "Finish"){ 
echo "<input type=submit name=setup value=$value class=submit>";
}
?>
</form>

<?php
//write data
if($setup == "Finish"){
//write league
$query = "INSERT INTO Leagues (Name,Fullname) VALUES ('$league_name','$league_fullname')";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
//write agegroups
if ($num_agegroups != "0"){
	//get data
	for ($a=0; $a<$num_agegroups; $a++){
	$agegroup_n = "agegroup_n$a";
	$agegroup_v = "agegroup_v$a";
	$agegroups[${$agegroup_n}] = "${$agegroup_v}";
	}
	//process data
	foreach ($agegroups as $agegroupn=> $agegroupv) {
	$query = "INSERT INTO Agegroups (Name,Fullname) VALUES ('$agegroupn','$agegroupv')";
	$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
	}
}
//write divisions
if ($num_divisions != "0"){
	//get data
	for ($a=0; $a<$num_divisions; $a++){
	$division_n = "division_n$a";
	$division_v = "division_v$a";
	$divisions[${$division_n}] = "${$division_v}";
	}
	//process data
	foreach ($divisions as $divisionsn=> $divisionsv) {
	$query = "INSERT INTO Divisions (Name,Fullname) VALUES ('$divisionsn','$divisionsv')";
	$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
	}
}
echo "<b>Sucessfully created the $league_name league<br></b>";
//echo mysql_errno().": ".mysql_error()."<BR>";
}
?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
