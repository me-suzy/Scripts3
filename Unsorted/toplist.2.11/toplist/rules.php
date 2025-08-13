<?
include "config.php";
include "db/db.php";
db_options();
include "style/" . $option['style'] . ".php";
echo "<center>" . $option['nameoflist'] . " rules:<br><br>";
include "rules.txt";
?>