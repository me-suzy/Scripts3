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
<td colspan=4 class=tdark align=center>Venue Creation Wizard</td>
</tr>

<tr>
<td class=tdark align=center>Name</td>
<td class=td align=center>
<input class=input type=text size=20% maxlength=15 name=VenueName>
</td>

<td class=td align=center>
<input class=input type=text size=20% maxlength=30 name=County>
<td class=tdark align=center>County</td>
</td>
</tr>

<tr>
<td class=tddd align=center>Address</td>
<td class=td align=center>
<input class=input type=text size=20% maxlength=30 name=Address>
</td>

<td class=td align=center>
<input class=input type=text size=20% maxlength=30 name=Phone>
</td>
<td class=tddd align=center>Phone</td>
</tr>

<tr>
<td class=tddd align=center>Email</td>
<td class=td align=center>
<input class=input type=text size=20% maxlength=30 name=Email>
</td>

<td class=td align=center>
<input class=input type=text size=20% maxlength=50 name=Website>
</td>
<td class=tdark align=center>Website</td>
</tr>

</table>
   
<center>
<input class="submit" type="submit" name="league" value="Add Venue">
</center>

</td></tr></table>

</form>

<?php
if ($league == "Add Venue"){
//write statistics
$Name = "$VenueName";
$query = "INSERT INTO Venues (Id, Name, County, Address, Phone, Email, Website) VALUES ('', '$Name', '$County', '$Address', '$Phone', '$Email', '$Website')";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
echo "<b>Sucessfully created venue</b><br>";
}
?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>