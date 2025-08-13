<?php

$dir = "/home/directory/to/data/";
include "config.inc";
$global_dbh = mysql_connect($dbhost, $dbusername, $dbpassword);
mysql_select_db($dbname, $global_dbh);
##
##  BE SURE TO CREATE THE DATABASE AND TABLE BEFORE RUNNING THIS
##  USE TABLESETUP.TXT TO SEE HOW TABLE MUST BE CREATED
##  WITH PHPMYADMIN YOU CAN JUST USE THE TEXT FILE UPLOAD
##  THEN YOU MUST CHANGE INFO IN CONFIG.INC
##

$dir_files = array();

chdir($dir);
$handle = @opendir($dir) or die("Directory \"$dir\"not found.");

while($entry = readdir($handle)) 
    {
        if($entry != ".." && $entry != ".")
        {   
            if (strstr($entry, ".")) { } else { $dir_files[] = $entry; }
        }
    }


$counter = count($dir_files);

for ($jjj=0; $jjj<$counter; $jjj++) {

	$DBE = array();
	$DBE = file("$dir/$dir_files[$jjj]");
	$date = time();
	$queryy = "INSERT INTO $dbtable(username, password, email, lastip, urls, creditsearned, creditsused, referer, memberlevel, checkcode, refercredits, ispaused, lasttime) VALUES('$dir_files[$jjj]', '$DBE[1]', '$DBE[9]', '0.0.0.0', '|||||||||', '$DBE[3]','$DBE[4]','$DBE[5]', '1', '0', '0', '1', '$date')";
	$result = @mysql_query($queryy);
	if($result == 0) {
		echo "<FONT COLOR=RED>ERROR CREATING " . $dir_files[$jjj] . "</FONT><BR>";
	}
	else {
		echo "<FONT COLOR=BLACK>DONE CREATING " . $dir_files[$jjj] . "</FONT><BR>";
	}
}
?>
<P>
<B>DONE</B>
