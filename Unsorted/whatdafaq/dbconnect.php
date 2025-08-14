<?php
// Configure these variables for your MySQL environment
$dbserver= "localhost";
$dbuser = "username";
$dbpassword = "password";
$db = "db_name";

$link = mysql_connect ($dbserver, $dbuser, $dbpassword);
if (! $link){
	exit();
}
if (!mysql_select_db ($db, $link) )	{
	exit ();
}
?>
