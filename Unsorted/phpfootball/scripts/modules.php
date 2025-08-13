<?php 
/*
***************************************************************************
Parameters :

action
dbfield
***************************************************************************
*/
?>

<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<p>&nbsp;</p>
<table width="75%" class=tddd align="center">
  <tr> 
    <td colspan="4">Available Moduless</td>
  </tr>
<?php
$query = "SELECT * FROM Modules";
$result = mysql_query($query) or die ("Failed read modules<br>Debug info: $query");
$num_res = mysql_num_rows($result);
for ($i=0; $i<$num_res; $i++){
	$myrow = mysql_fetch_assoc($result);
}
foreach ($myrow as $myrow_n=>$myrow_v){
	if ($myrow_v){$myrow_v = "enabled";}else{$myrow_v = "disabled";}
	echo "<tr><td class=tdark align=center>$myrow_n</td><td class=tdark align=center>$myrow_v&nbsp;</td></tr>";
}
?>
  <tr> 
    <td class=form colspan="4" align=center>
<?php
//league module can not be disabled
if ($dbfield == "league"){
	echo "<b>league module can not be changed</b>";
	$action = "none";
}

if ($action == "disable"){
$query = "UPDATE Modules SET $dbfield=''";
$result = mysql_query($query) or die ("died while deleting from table<br>Debug info: $query");
echo "<b>Sucessfully disabled module<br></b>";
}

if ($action == "enable"){
$dbfield_low = strtolower($dbfield);
$query = "UPDATE Modules SET $dbfield='[ <a href=../$dbfield_low.index.php target=_parent>$dbfield</a> ]'";
$result = mysql_query($query) or die ("died while deleting from table<br>Debug info: $query");
echo "<b>Sucessfully enabled module<br></b>";
}
?>
    </td>
  </tr>
</table>
<p>&nbsp;</p>


<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
