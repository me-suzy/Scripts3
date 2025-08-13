<?php
/*
***************************************************************************
Parameters :
inctable
incwhat
***************************************************************************
*/
?>

<?php
	$query = "SHOW TABLE STATUS FROM $dbname";
	$result = mysql_query($query) or die ("Failed to read rows<br>Debug info: $query");
	$num_res = mysql_num_rows($result);
	//get data
	for ($i=0; $i<$num_res; $i++){
		$myrow = mysql_fetch_assoc($result);
		if (preg_match ("/$inctable/i", $myrow["Name"])){
		$stats = $myrow;
		}
	}
	//print first table
	$what = explode(",", $incwhat);
	echo "<tr>";
	foreach ($what as $what_v){
		echo "<td class=tdark>$what_v</td>";	
	}
	echo "</tr>";
	//print second table
	echo "<tr>";
	foreach ($stats as $stats_n=>$stats_v){
		if (in_array("$stats_n", $what)){
			echo "<td class=tddd>$stats_v</td>";
		}
	}
	echo "</tr>";
?>