<?
// From AlexaSurf.com

include "alexa_config.php";

//Grabbing toolbar information

$query="SELECT * FROM useragent WHERE alexa_agent LIKE '%alexa%'";
$result=mysql_query($query);

$num=mysql_num_rows($result);

mysql_close($conn);

echo "<b>Alexa Visits: $num</b>";
?>
