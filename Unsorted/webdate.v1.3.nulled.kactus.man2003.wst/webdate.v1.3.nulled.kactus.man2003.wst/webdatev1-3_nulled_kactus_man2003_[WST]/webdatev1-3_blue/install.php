<?
    require "engine/load_configuration.pml";
    //$db = c();
    execute("tables.sql");
function execute($filename)
{
global $mysqlhostname, $mysqlusername, $mysqlpassword, $mysqldbname;
//mysql_connect($mysqlhostname, $mysqlusername, $mysqlpassword);
//mysql_select_db($mysqldbname);
$text = join ('', file ($filename));
$words= explode (";",$text);
$max=count($words);

for ($i=0;$i<$max;$i++)
 {
	echo "<H3>i create your tables now! MySQL execution finished.</H3>";
 $words[$i]=str_replace("\n","",$words[$i]);
 $words[$i]=str_replace("\r","",$words[$i]);
 for ($z=0;$z<10;$z++) $words[$i]=str_replace("  "," ",$words[$i]);
 echo "<br>".$words[$i];
// $r =  mysql($db_name,$words[$i]);
 $rst = q($words[$i]);
 }
}
 	echo "<H1>install completed.enjoy kactus_man2003[WST] :)</H1> ";

	//d($db);

?>
