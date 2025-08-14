<?
function dbconnect() 
{ 
	require("config.php");
	global $dbhost, $dbuname, $dbpass, $dbname; 
	mysql_connect($dbhost, $dbuname, $dbpass); 
	@mysql_select_db($dbname) or die ("Unable to select database !"); 
} 

function query_db($query) 
{ 
	dbconnect(); 
	return @mysql_query($query); 
} 
?>