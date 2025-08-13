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
<td colspan=4 class=tdark align=center>Fixtureless Game Creation Wizard </td>
</tr>

<tr>
<td class=tdark align=center>Hometeam</td>
<td class=td align=center>
<?php
$reqadm = "1";
$incsel = "rows";
$inctable = "Teams";
$incfield = "Name";
$incshow = "Name";
$incvar = "HomeTeam";
?>
<?php require("inc.select.php"); ?>
</td>

<td class=td align=center>
<?php
$reqadm = "1";
$inctable = "Teams";
$incfield = "Name";
$incshow = "Name";
$incvar = "AwayTeam";
?>
<?php require("inc.select.php"); ?>
</td>
<td class=tdark align=center>Awayteam</td>
</tr>

<tr>
<td class=tdark align=center>HomeScore</td>
<td class=td align=center>
<input class=input type=text size=2 maxlength=3 name=HomeScore value="0">
</td>

<td class=td align=center>
<input class=input type=text size=2 maxlength=3 name=AwayScore value="0">
</td>
<td class=tdark align=center>AwayScore</td>
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
<input class=input type=text size=20% maxlength=30 name=PoG>
</td>
<td class=tddd align=center>P of the G</td>
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
<input class="submit" type="submit" name="league" value="Add Game">
</center>

</td></tr></table>

</form>




<form action="<?php echo "{$_SERVER['PHP_SELF']}"; ?>" method="post"> 

<table class=td border="0"><tr><td>

<table width=600 height="30px" border="0" cellspacing="0" cellpadding="0" >

<tr>
<td colspan=4 class=tdark align=center>Game From Fixture Creation Wizard</td>
</tr>

<tr>
<td class=tdark align=center>Fixture</td>
<td class=td align=center colspan=2>
<?php
$reqadm = "1";
$incsel = "rows";
$inctable = "Fixtures";
$incfield = "Game";
$incshow = "Game";
$incvar = "Fixture";
?>
<?php require("inc.select.php"); ?>
</td>

<td class=tdark align=center>Fixture</td>
</td>
</tr>

<tr>
<td class=tdark align=center>HomeScore</td>
<td class=td align=center>
<input class=input type=text size=2 maxlength=3 name=HomeScore value="0">
</td>

<td class=td align=center>
<input class=input type=text size=2 maxlength=3 name=AwayScore value="0">
</td>
<td class=tdark align=center>AwayScore</td>
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
<input class=input type=text size=20% maxlength=30 name=PoG>
</td>
<td class=tddd align=center>P of the G</td>
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
<input class="submit" type="submit" name="league" value="Add Game for Fixture">
</center>

</td></tr></table>

</form>






<form action="<?php echo "{$_SERVER['PHP_SELF']}"; ?>" method="post"> 

<table class=td border="0"><tr><td>

<table width=600 height="30px" border="0" cellspacing="0" cellpadding="0" >

<tr>
<td colspan=4 class=tdark align=center>Postponed/Abandoned Game Creation Wizard</td>
</tr>

<tr>
<td class=tdark align=center>Fixture</td>
<td class=td align=center colspan=2>
<?php
$reqadm = "1";
$incsel = "rows";
$inctable = "Fixtures";
$incfield = "Game";
$incshow = "Game";
$incvar = "Fixture";
?>
<?php require("inc.select.php"); ?>
</td>

<td class=tdark align=center>Fixture</td>
</td>
</tr>

<tr>
<td class=tdark align=center>Postponed</td>
<td class=td align=center>
<input type="radio" name="Game_Status" value="P">
</td>

<td class=td align=center>
<input type="radio" name="Game_Status" value="A">
</td>
<td class=tdark align=center>Abandoned</td>
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
<input class=input type=text size=20% maxlength=30 name=PoG>
</td>
<td class=tddd align=center>P of the G</td>
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
<input class="submit" type="submit" name="league" value="Add Postponed/Abandoned Game for Fixture">
</center>

</td></tr></table>

</form>







<?php
if ($league == "Add Game"){
//a little check
if ($HomeTeam == $AwayTeam){ echo "Team cant play against itself"; die; }
//craft wariables
$Name = "$HomeTeam v $AwayTeam";
$Score = "$HomeScore-$AwayScore";
$Date = "$year-$month-$day";
//write statistics
$query = "INSERT INTO Games (Id, Name, Score, PoG, Agegroup, League, Division, Date) VALUES ('', '$Name', '$Score', '$PoG', '$Agegroup', '$League', '$Division', '$Date')";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
echo "<b>Sucessfully created game</b><br>";
//call global statistics update
include("inc.calculate.php");
}
?>

<?php
if ($league == "Add Game for Fixture"){
//craft wariables
$Name = "$Fixture";
$Score = "$HomeScore-$AwayScore";
$Date = "$year-$month-$day";
//write statistics
$query = "INSERT INTO Games (Id, Name, Score, PoG, Agegroup, League, Division, Date) VALUES ('', '$Name', '$Score', '$PoG', '$Agegroup', '$League', '$Division', '$Date')";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
echo "<b>Sucessfully created game</b><br>";
//delete fixture
$query = "DELETE FROM Fixtures WHERE Game = '$Name'";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
echo "<b>Sucessfully deleted fixture</b><br>";
//call global statistics update
include("inc.calculate.php");
}
?>

<?php
if ($league == "Add Postponed/Abandoned Game for Fixture"){
//craft wariables
$Name = "$Fixture";
$Score = "$Game_Status";
$Date = "$year-$month-$day";
//write statistics
$query = "INSERT INTO Games (Id, Name, Score, PoG, Agegroup, League, Division, Date) VALUES ('', '$Name', '$Score', '$PoG', '$Agegroup', '$League', '$Division', '$Date')";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
echo "<b>Sucessfully created game</b><br>";
//delete fixture
$query = "DELETE FROM Fixtures WHERE Game = '$Name'";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
echo "<b>Sucessfully deleted fixture</b><br>";
}
?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>