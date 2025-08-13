<?php 
/*
***************************************************************************
Parameters :

Howmany
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
<td colspan=4 class=tdark align=center>Fixture Creation Wizard</td>
</tr>

<?php
//html between tags
$home = "<tr><td class=tdark align=center>Hometeam</td><td class=td align=center>";
$homeaway = "</td><td class=td align=center>";
$away = "</td><td class=tdark align=center>Awayteam</td></tr>";
if ($Howmany == ""){ $Howmany = "1"; }
//print data
for ($h=0; $h<$Howmany; $h++){
	echo $home;
	$reqadm = "1";
	$incsel = "rows";
	$inctable = "Teams";
	$incfield = "Name";
	$incshow = "Name";
	$incvar = "HomeTeam$h";
	require("inc.select.php");
	echo $homeaway;
	$reqadm = "1";
	$incsel = "rows";
	$inctable = "Teams";
	$incfield = "Name";
	$incshow = "Name";
	$incvar = "AwayTeam$h";
	require("inc.select.php");
	echo $away;
}
?>

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
</td>
<td class=tddd align=center>Venue</td>
</tr>

<tr>
<td colspan=4 class=tdark align=center>
<select name="year">
<?php
for($i = 1950 ; $i < 2010 ; $i++){
	if($i<10){
		$i = "0".$i;
	}
	if($i == "2003")
		echo "<option value=\"$i\" SELECTED>$i</option>\n";
	else
		echo "<option value=\"$i\">$i</option>\n";
	}
?>
</select>
<select name="month">
<?php
for($i = 1 ; $i < 13 ; $i++){
	if($i<10){
		$i = "0".$i;
	}
	if($i == "01")
		echo "<option value=\"$i\" SELECTED>$i</option>\n";
	else
	echo "<option value=\"$i\">$i</option>\n";
	}
?>
</select>
<select name="day">
<?php
for($i = 1 ; $i < 32 ; $i++){
	if($i<10){
		$i = "0".$i;
	}
	if($i == "01")
		echo "<option value=\"$i\" SELECTED>$i</option>\n";
	else
	echo "<option value=\"$i\">$i</option>\n";
	}
?>
</select>
</td>
</tr>

</table>
   
<center>
<input type="hidden" name="Howmany" value="<?php echo $Howmany; ?>">
<input class="submit" type="submit" name="league" value="Add Fixture">
</center>

</td></tr></table>

</form>


<?php
if ($league == "Add Fixture"){
//get data
for ($h1=0; $h1<$Howmany; $h1++){
$HomeTeam = "HomeTeam$h1";
$AwayTeam = "AwayTeam$h1";
$teams[${$HomeTeam}] = "${$AwayTeam}";
}
//process data
foreach ($teams as $HomeTeam=> $AwayTeam) {
//a little check
if ($HomeTeam == $AwayTeam){ echo "Team cant play against itself"; die; }
//craft wariables
$Game = "$HomeTeam v $AwayTeam";
$Date = "$year-$month-$day";
//write statistics
$query = "INSERT INTO Fixtures (Id, Game, Venue, Agegroup, League, Division, Date) VALUES ('', '$Game', '$Venue', '$Agegroup', '$League', '$Division', '$Date')";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
}
echo "<b>Sucessfully created $Howmany fixtures</b><br>";
}
?>


<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>