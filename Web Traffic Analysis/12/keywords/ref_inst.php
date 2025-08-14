<?php
require('setup.php');
$db = mysql_connect($my_host,$my_user,$my_pass);
mysql_query("CREATE DATABASE IF NOT EXISTS $my_db", $db);
mysql_select_db($my_db, $db);
mysql_query("DROP TABLE IF EXISTS keywords", $db);
mysql_query("CREATE TABLE keywords(keyword varchar(200),wordhits int(16),lastuse varchar(30))", $db);
echo "Created table keywords";
if(strlen(mysql_error())<4) { echo "<br />\n"; }
else { echo ", ERRORS: ".mysql_error()."<br />\n"; }
?>