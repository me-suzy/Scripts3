<?PHP
// wimpy_mysql_update.php
$checkExistOn = "filename";
$idFieldName = "id";
//
// (myDataSetup can be edited in the "config" section of wimpy.php).
$AmySQLdatasetup = explode("|", $myDataSetup);
//
// Here we are adding a new item to the data setup 
// for MySQL to use as the index and a unique identifier 
// for each track.
//
// if you already have an id then
// "id" must be an int(6) (an integer (a number) with 
// up to but not exceeding 6 digits example: 999999 
// this would be the highest number you can use)
array_unshift($AmySQLdatasetup, "id");
// make connection to mysql:
$link = mysql_connect($host, $user, $pwd) 
	 or die("Could not connect");
$check = "Connected successfully";
mysql_select_db($db) 
	 or die("Could not select database");
$check .= "<br>Table: $table";
//
print "$check";
//
// Create a new table:
//$sql = 'DROP TABLE IF EXISTS '.$table.';';
$sql = 'CREATE TABLE IF NOT EXISTS '.$table.';';
$query = mysql_query($sql);
print "<br>Check if table: $table exists.";
if($query){
	print "<br>Create table: $table. Result: $query";
	$sql = 'CREATE TABLE '.$table.' (';
	for($i=0;$i<sizeof($AmySQLdatasetup);$i++){
		if($i==0){
			$sql .= '  '.$AmySQLdatasetup[0].' int(6) NOT NULL default \'0\',';
		}else if ($i==1){
			$sql .= '  '.$AmySQLdatasetup[$i].' text NOT NULL,';
		} else {
			$sql .= '  '.$AmySQLdatasetup[$i].' text,';
		}
	}
	$sql .= '  KEY id (id)';
	$sql .= ') TYPE=MyISAM;';
	$query = mysql_query($sql);
	if($query){
		print "<br>Created Table: $table Result: $query<br>";
	} else {
		print "<br>Failed to create table: $table Result: $query<br>";
	}
} else {
	print "<br>Result: $table exists.";
}
$sql = "";

$num_rows = 0;
for($i=0;$i<sizeof($Asendback);$i++){
	// remove url encoding:
	$Asendback[$i] = addslashes(urldecode($Asendback[$i]));
}
//
// Find the highest id number:
//$getAll = mysql_query('SELECT * FROM '.$table.';');
//$numRows = 0;
//$numRows = mysql_numrows($getAll);
$result = mysql_query('SELECT * FROM '.$table.' WHERE 1 ORDER by '.$idFieldName.' DESC;');
//$result = mysql_query('SELECT * FROM '.$table.' WHERE '.$idFieldName.' >'.$numRows.' ORDER by '.$idFieldName.' DESC;');
//$Aline = mysql_fetch_array($result, MYSQL_ASSOC);
$Arows = array();
$count = 0;
$rowBlob = "";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$Arows[$count] = $line;
	$rowBlob .= implode("",$line);
	$count++;
}
//$rowBlob = implode("",$Arows);
$highestID = ($Arows[0][$idFieldName])+1;
print "<BR>highest ID=$highestID<BR>";
//
@mysql_free_result($result);
//$myQuery = "SELECT * FROM ".$table." WHERE "."id"."='".urldecode($item[$checkExistIndex])."';";
//
// ----------------------------------
//
// Define the first part of the query:
$sql_where = 'INSERT INTO '.$table.' ( `';
//
// Setup the WHERE fields in the statement 
// Establish temporary array to insert items into
$AtempInsert_where = array();

//
// NOTE, we already inserted the "id" into the 
// AmySQLdatasetup array at the top of the script.
//
for($j=0;$j<sizeof($AmySQLdatasetup);$j++){
	// determine file name index so we can 
	// check later if this item is already in the DB
	if($AmySQLdatasetup[$j] == $checkExistOn){
		// REMOVE COMMENT: we subtract 1 because wimpy returns the array without an ID field
		$checkExistIndex = $j;
	}
	array_push($AtempInsert_where, $AmySQLdatasetup[$j]);
}
print "<BR>check exist index=$checkExistIndex<BR>";
// 
// Glue the temporary array together sith secret mysql code
$sql_where .= implode('`,`', $AtempInsert_where);
// close off the mysql statement
$sql_where .= '` ) ';
//
// ----------------------------------
//
//$myQuery = "SELECT * FROM ".$table." WHERE ".$checkExistOn."='".urldecode($item[$checkExistIndex])."';";
//$myQuery = "SELECT * FROM ".$table." WHERE ".$checkExistOn."='".urldecode($item[$checkExistIndex])."';";
//$checkExist = mysql_query($myQuery);
//$AcheckExist = mysql_fetch_array($checkExist, MYSQL_ASSOC);
//@mysql_free_result($checkExist);
//
//
$countID = 0;
$newListBlob = "";
for($i=0;$i<sizeof($Asendback);$i++){
	//
	// Split the items to request from the database:
	$item = explode("|", $Asendback[$i]);
	//
	$newListBlob .= implode ("", $item);
	//
	// Check to see if the the item already exists:
	//
	//print ("<BR><BR>".stristr($rowBlob, $item[$checkExistIndex]));
	if(!stristr($rowBlob, $item[$checkExistIndex])){
		// MySQL statement command plus first part of (
		$sql_value = 'VALUES ( \'';
		//
		// Establish temporary array to insert items into
		$AtempValues = array();
		// 
		// Put "id" number (i) as the first item in the array
		array_push($AtempValues, $countID+$highestID);
		//
		// Put playlist item elements into array
		for($k=0;$k<sizeof($AmySQLdatasetup)-1;$k++){
			array_push($AtempValues, $item[$k]);
		}
		//
		// Glue the array together to get a statement
		$sql_value .= implode('\',\'', $AtempValues);
		//
		// close the statement
		$sql_value .= '\');';
		//
		// not sure why this is here.
		//$sql_value .= ''; 
		//
		// Send query to populate the database
		$query = mysql_query($sql_where.$sql_value);
		if($query){
			print "<br>ADDED: $item[0]";
		} else {
			print "<br>FAILED: $item[0]";
		}
		@mysql_free_result($query);
		//
		// if one was added, then bump up the id number
		$countID++;
	}
}
//
// remove deleted files from database:
for($i=0;$i<$count;$i++){
	// get the file name from the list we got from the DB
	$myCheck = $Arows[$i][$checkExistOn];
	//
	// see if the file name is in the blob that we created 
	// from the loot that wimpy.php provided to us 
	if(!stristr($newListBlob, $myCheck)){
		//
		// if it is NOT in there, then delete it from the DB
		$queryString = 'DELETE FROM '.$table.' WHERE '.$checkExistOn.' = \''.$myCheck.'\';';
		if($removeMe = mysql_query($queryString)){
			print ("<br> DELETED: ".$myCheck);
		} else {
			print ("<br> WARNING: Tried to delete but was unsuccessful(try moving the file rather than renaming it): ".$myCheck);
		}
	}
}
	

?>