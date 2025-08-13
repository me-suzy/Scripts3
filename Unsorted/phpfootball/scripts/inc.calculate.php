<?

//filter team names and scores
if (!$HomeTeam | !$AwayTeam){
	preg_match ("/^(.*?) v (.*?)$/",$Name,$teams);
	$HomeTeam = $teams[1];
	$AwayTeam = $teams[2];
}
if (!$HomeTeam | !$AwayTeam){ echo "Format is hometeam v awayteam not : $Name"; die;}
if (defined($HomeScore) | defined($AwayScore)){
	preg_match ("/^(\d+?)-(\d+?)$/",$Score,$scores);
	$HomeScore = $scores[1];
	$AwayScore = $scores[2];
}
if (defined($HomeScore) | defined($AwayScore)){ echo "Format is homescore-awayscore not : $Score"; die;}

//
//alocate points
//
if ($HomeScore == $AwayScore){
$home_p = "1";
$away_p = "1";
}
if ($HomeScore > $AwayScore){
$home_p = "3";
$away_p = "0";
}
if ($HomeScore < $AwayScore){
$home_p = "0";
$away_p = "3";
}

//
//add score to home team
//
//get current home team statistics
$query = "SELECT * FROM Teams WHERE Name = '$HomeTeam'";
$result = mysql_query($query) or die ("Failed to read team<br>Debug info: $query");

$num_res = mysql_num_rows($result);
for ($i=0; $i<$num_res; $i++){
	$row = mysql_fetch_array($result);
	$Played = $row["Played"];
	$Wins = $row["Wins"];
	$Draws = $row["Draws"];
	$Loses = $row["Loses"];
	$GF = $row["GF"];
	$GA = $row["GA"];
	$GD = $row["GD"];
	$Pts = $row["Pts"];
}
//update home team statistics
$Played = $Played + 1;
if ($HomeScore == $AwayScore){ $Draws = $Draws + 1; }
if ($HomeScore > $AwayScore) { $Wins = $Wins + 1; }
if ($HomeScore < $AwayScore) { $Loses = $Loses + 1; }
$GF = $GF + $HomeScore;
$GA = $GA + $AwayScore;
$GD = $GF - $GA;
$Pts = $Pts + $home_p;
//write the new home team data
$query = "UPDATE Teams SET Played='$Played',Wins='$Wins',Draws='$Draws',Loses='$Loses',GF='$GF',GA='$GA',GD='$GD',Pts='$Pts' WHERE Name = '$HomeTeam'";
mysql_query($query) or die ("Failed to update team<br>Debug info: $query");


//
//add score to away team
//
//get current away team statistics
$query = "SELECT * FROM Teams WHERE Name = '$AwayTeam'";
$result = mysql_query($query) or die ("Failed to read team<br>Debug info: $query");

$num_res = mysql_num_rows($result);
for ($i=0; $i<$num_res; $i++){
	$row = mysql_fetch_array($result);
	$Played = $row["Played"];
	$Wins = $row["Wins"];
	$Draws = $row["Draws"];
	$Loses = $row["Loses"];
	$GF = $row["GF"];
	$GA = $row["GA"];
	$GD = $row["GD"];
	$Pts = $row["Pts"];
}
//update away team statistics
$Played = $Played + 1;
if ($HomeScore == $AwayScore){ $Draws = $Draws + 1; }
if ($HomeScore < $AwayScore) { $Wins = $Wins + 1; }
if ($HomeScore > $AwayScore) { $Loses = $Loses + 1; }
$GF = $GF + $AwayScore;
$GA = $GA + $HomeScore;
$GD = $GF - $GA;
$Pts = $Pts + $away_p;
//write the new away team data
$query = "UPDATE Teams SET Played='$Played',Wins='$Wins',Draws='$Draws',Loses='$Loses',GF='$GF',GA='$GA',GD='$GD',Pts='$Pts' WHERE Name = '$AwayTeam'";
mysql_query($query) or die ("Failed to update team<br>Debug info: $query");


//
//calculate and update positions
//
$query = "SELECT Name FROM Teams ORDER BY Pts DESC, GD DESC";
$result = mysql_query($query) or die ("Failed to get positions<br>Debug info: $query");
$pnum = "0";
while ($line = mysql_fetch_assoc($result)) {
	while(list($col_name, $col_value) = each($line)) {
		$pnum = $pnum + 1;
		$query = "UPDATE Teams SET Position='$pnum' WHERE Name = '$col_value'";
		mysql_query($query) or die ("Failed to update positions<br>Debug info: $query");
	}
}

echo "<b>Sucesfully updated/added team positions and statistics</b>";

?>
