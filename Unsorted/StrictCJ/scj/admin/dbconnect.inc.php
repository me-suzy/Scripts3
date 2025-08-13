<?
/***********************************************************
			set your variables
***********************************************************/
// ask your host for those information if you don'T know them
// you need to ask about getting a MySQL database
$host="localhost";
$user="your_username_here";
$password="your_password_here";
$dbname="your_databse_name";
/***********************************************************
			End of variables
***********************************************************/
mysql_connect($host,$user,$password) or die("Error Connecting to the database...");
mysql_select_db($dbname) or die(mysql_error());
?>
