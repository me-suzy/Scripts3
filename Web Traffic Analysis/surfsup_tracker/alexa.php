<?
// From AlexaSurf.com

include "alexa_config.php";

//Get user information

//-browser
$useragent=$_SERVER["HTTP_USER_AGENT"];
//-ip
$ip=$_SERVER['REMOTE_ADDR'];

//Insert User info into SQL
$query = ("INSERT INTO useragent (alexa_agent, alexa_ip) VALUES ('$useragent', '$ip')");
mysql_query($query);

$query = "FLUSH PRIVILEGES";
mysql_query($query);

//close connection
mysql_close($conn);

?>
