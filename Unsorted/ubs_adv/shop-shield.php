<?
require("functions.php");

if($user_id) {
        require("header.php");
		$buyi = $db_connect->query_first("SELECT shieldname, money FROM db_shop_shields where id='1'");
$thread_shield = $db_connect->query_first("select usershield from db_users where userid='$user_id]'");
$result = $db_connect->query("SELECT id, shieldname, money FROM db_shop_shields ORDER BY money ASC");
while($item = $db_connect->fetch_array($result)) eval ("\$shop_items .= \"".gettemplate("shield_shopbit")."\";");
	eval("dooutput(\"".gettemplate("shield_shop")."\");");
} else header("LOCATION: misc.php?action=access_error&boardid=$boardid&styleid=$styleid$session"); 
?>