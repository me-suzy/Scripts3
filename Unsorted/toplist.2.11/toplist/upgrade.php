<?
include "config.php";
mysql_connect("$dbhost","$dbuser","$dbpass") or die('Database connection error');
mysql_select_db("$dbname") or die('Database error!');
include "style/default.php";

echo "<center>Best Top List upgrade script. Choose upgrade.<br><br><br><a href=upgrade.php?ver=2.09>From 2.09. version to 2.10</a>";


if($ver == "2.09"){

echo "<center><br><br>creating toplista_ip_blocking table ... ";
$create = "CREATE TABLE toplista_ip_blocking (ip CHAR(20), czas INT, idstrony INT, id INT PRIMARY KEY AUTO_INCREMENT)";
$utworz = mysql_query($create) or die('<font color=red>error! - ' . mysql_error() . '</font>');
echo "<font color=red>ok!</font>";

}

?>