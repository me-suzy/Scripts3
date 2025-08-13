<?php
require "functions.php";

$result = $db_connect->query("SELECT id, swordname, money FROM db_shop_swords ORDER BY money ASC");
while($db_connect->fetch_array($result)) eval ("\$shop_items .= \"".gettemplate("weapon_shopbit")."\";");
eval("dooutput(\"".gettemplate("weapon_shop")."\");");
?>