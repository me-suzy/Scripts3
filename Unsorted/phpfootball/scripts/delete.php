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
echo "<tr><td colspan=2 class=tdark><center>$dbtable deleting form</center></td></tr>";

$query = "SELECT * FROM $dbtable WHERE Id = '$Id'";
$result = mysql_query($query) or die ("Failed read $dbtable<br>Debug info: $query");
$num_row = mysql_num_rows($result);
for ($x=0; $x<$num_row; $x++){ $the_row = mysql_fetch_assoc($result); }
foreach ($the_row as $therow_n=> $therow_v){
	echo "<tr><td class=tddd>$therow_n</td><td class=tdark><center><input type=text class=td size=70% value=\"$therow_v\"></center></td></tr>\n";
}
?>
</table>
<input type="hidden" name="dbtable" value="<?php echo $dbtable; ?>">
<input type="hidden" name="Id" value="<?php echo $Id; ?>">
<input type="submit" name="delete" value="  Delete  " class="submit">
</form>

<?php
if($delete == "  Delete  "){
$query = "DELETE FROM $dbtable WHERE Id = '$Id'";
$result = mysql_query($query) or die ("died while deleting from table<br>Debug info: $query");
echo "<b>Sucessfully deleted entry<br></b>";
//echo mysql_errno().": ".mysql_error()."<BR>";
}
?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
