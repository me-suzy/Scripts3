<?php

include 'config.php';

$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
	or die(mysql_error());

$query = "CREATE TABLE users (username VARCHAR(255),email VARCHAR(255),password VARCHAR(255))";
if(mysql_query($query)){
echo "Sucessfully created tables in database. Now remove this file from the server";
} else {
echo "Error, tables have not been created.";
}
?>