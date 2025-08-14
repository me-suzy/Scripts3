<?php
require "_header.php";
require "../lib/mysql.lib";

echo "<font size=1> Database settings : $db_host,$db_login,$db_pswd,$db_name <br><br><b>Processing :</b><br>";
$db = c();

function execute($filename)
{
global $db_name;
$text = join ('', file ($filename)); 
$words= explode (";",$text);
$max=count($words);

for ($i=0;$i<$max;$i++) 
	{
	$words[$i]=str_replace("\n","",$words[$i]);
	$words[$i]=str_replace("\r","",$words[$i]);
	for ($z=0;$z<10;$z++) $words[$i]=str_replace("  "," ",$words[$i]);
	echo "<br>$db_name + '".$words[$i];$r =  mysql($db_name,$words[$i]); echo"'";
	}
}
execute("sql/upgrade1.sql");
d($db);
require "_footer.php";
?>
