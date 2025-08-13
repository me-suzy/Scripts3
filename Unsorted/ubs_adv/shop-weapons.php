<?
require("functions.php");

if($user_id) {
        require("header.php");
		$buyi = $db_connect->query_first("SELECT swordname, money FROM db_shop_swords where id='1'");
$thread_sword = $db_connect->query_first("select userweapon from db_users where userid='$user_id]'");
$result = $db_connect->query("SELECT id, swordname, money FROM db_shop_swords ORDER BY money ASC");
while($item = $db_connect->fetch_array($result)) eval ("\$shop_items .= \"".gettemplate("weapon_shopbit")."\";");
	eval("dooutput(\"".gettemplate("weapon_shop")."\");");
} else header("LOCATION: misc.php?action=access_error&boardid=$boardid&styleid=$styleid$session"); 
?>