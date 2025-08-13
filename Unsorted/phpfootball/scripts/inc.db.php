<?php
require("inc.config.php");
$link = mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db("$dbname",$link);
?>