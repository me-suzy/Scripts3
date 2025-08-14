<?
// From AlexaSurf.com

include "alexa_config.php";

//Grabbing toolbar information

$query="SELECT * FROM useragent WHERE alexa_agent LIKE '%alexa%'";
$result=mysql_query($query);

$num=mysql_num_rows($result);

mysql_close($conn);

echo "<a href=http://www.alexasurf.com/index.php?site=$site><img border=0 src=http://www.alexasurf.com/banner.jpg><br>";
echo "<SCRIPT type=text/javascript language=JavaScript src=http://xslt.alexa.com/site_stats/js/s/c?url=$site></SCRIPT><br>";
echo "<SCRIPT type=text/javascript language=JavaScript src=http://xsltcache.alexa.com/traffic_graph/js/g/c/3m?&u=$site></SCRIPT><br>";
echo "<br><b>Alexa Stats by AlexaSurf.com</b><br>";
echo "Amount of Alexa Toolbars: $num<br><br>";

$i=0;
while ($i < $num) {

$useragent=mysql_result($result,$i,"alexa_agent");
$ip=mysql_result($result,$i,"alexa_ip");

echo "<b>Agent:</b>$useragent <b>IP:</b>$ip<br>";

$i++;
}
?>