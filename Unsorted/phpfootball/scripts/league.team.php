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


<form action="<?php echo "{$_SERVER['PHP_SELF']}"; ?>" method="post"> 

<table class=td border="0"><tr><td>

<table width=600 height="30px" border="0" cellspacing="0" cellpadding="0" >

<tr>
<td colspan=4 class=tdark align=center>Team Creation Wizard</td>
</tr>

<tr>
<td class=tdark align=center>Name</td>
<td class=td align=center>
<input class=input type=text size=20% maxlength=30 name=TeamName>
</td>

<td class=td align=center>
<input class=input type=text size=20% maxlength=50 name=Website>
</td>
<td class=tdark align=center>Website</td>
</tr>

<tr>
<td class=tddd align=center>League</td>
<td class=td align=center>
<?php
$reqadm = "1";
$incsel = "rows";
$inctable = "Leagues";
$incfield = "Name";
$incshow = "Name";
$incvar = "League";
?>
<?php require("inc.select.php"); ?>
</td>

<td class=td align=center>
<?php
$reqadm = "1";
$incsel = "rows";
$inctable = "Divisions";
$incfield = "Name";
$incshow = "Name";
$incvar = "Division";
?>
<?php require("inc.select.php"); ?>
</td>
<td class=tddd align=center>Division</td>
</tr>

<tr>
<td class=tddd align=center>Agegroup</td>
<td class=td align=center>
<?php
$reqadm = "1";
$incsel = "rows";
$inctable = "Agegroups";
$incfield = "Name";
$incshow = "Name";
$incvar = "Agegroup";
?>
<?php require("inc.select.php"); ?>
</td>

<td class=td align=center>
<?php
$reqadm = "1";
$incsel = "rows";
$inctable = "Venues";
$incfield = "Name";
$incshow = "Name";
$incvar = "Venue";
?>
<?php require("inc.select.php"); ?>
<td class=tddd align=center>Homeground</td>
</td>
</tr>

</table>
   
<center>
<input class="submit" type="submit" name="league" value="Add Team">
</center>

</td></tr></table>

</form>

<?php
if ($league == "Add Team"){
//write statistics
$Name = "$TeamName";
$query = "INSERT INTO Teams (Id, Name, Agegroup, League, Division, Venue, Website) VALUES ('', '$Name', '$Agegroup', '$League', '$Division', '$Venue', '$Website')";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
echo "<b>Sucessfully created team</b><br>";
}
?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>