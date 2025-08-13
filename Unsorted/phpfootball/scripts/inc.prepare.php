<?php
//get id
$id = mysql_insert_id();

if ($dbtable == "Games" && $id != "0"){

//get team names and scores
$result = mysql_query("SELECT * FROM Games WHERE Id = $id");
$num_res = mysql_num_rows($result);

for ($i=0; $i<$num_res; $i++){
	$myrow = mysql_fetch_array($result);
	$Name = $myrow["Name"];
	$Score = $myrow["Score"];
	$League = $row["League"];
	$Division = $row["Division"];
}


//create hometeam if does not exist
$test = mysql_query("SELECT Name FROM Teams WHERE Name = '$HomeTeam'");
if (mysql_num_rows($test) == "0"){
	$query = "INSERT INTO Teams (Name,League,Division) VALUES ('$HomeTeam','$League','$Division')";
	mysql_query($query) or die ("Failed to add team<br>Debug info: $query");
	echo "New team added to the teams database : $HomeTeam<br>";
}

//create awayteam if does not exist
$test = mysql_query("SELECT Name FROM Teams WHERE Name = '$AwayTeam'");
if (mysql_num_rows($test) == 0){
	$query = "INSERT INTO Teams (Name,League,Division) VALUES ('$AwayTeam','$League','$Division')";
	mysql_query($query) or die ("Failed to add team<br>Debug info: $query");
	echo "New team added to the teams database : $AwayTeam<br>";
}
}
?>

<?php include("inc.calculate.php"); ?>