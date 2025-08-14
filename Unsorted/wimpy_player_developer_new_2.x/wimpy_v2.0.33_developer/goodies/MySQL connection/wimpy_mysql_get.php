<?php
// wimpy_mysql_get.php

$link = mysql_connect($host, $user, $pwd) 
	 or die("Could not connect");
$check = "Connected successfully";
mysql_select_db($db) 
	 or die("Could not select database");
$check .= "<br>Table: $table";
$AqueryItems = explode ("|", $queryValue);
$count=0;
$sendback = "";
for($i=0;$i<sizeof($AqueryItems);$i++){
	$myQuery = "SELECT * FROM ".$table." WHERE ".$queryWhere."='".urldecode($AqueryItems[$i])."';";
	//print "<BR>$myQuery";
	$result = mysql_query($myQuery);
	//$result = mysql_query($myQuery) or die("Query failed : " . mysql_error());
	if($result){
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			array_shift($line);
			$sendback .= "&item$count=".implode("|", cleanForFlash($line));
			$count++;
		}
	}
	@mysql_free_result($result);
}
mysql_close($link);
$sendback .= "&totalitems=$count";
$sendback .= "&datasetup=$myDataSetup";
$sendback .= "&directoryoffset=0";
$sendback .= "&queryValue=$queryValue";
$sendback .= "&queryWhere=$queryWhere";
echo ("$sendback");
?>