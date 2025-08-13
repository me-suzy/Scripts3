<?
require("functions.php");

if($user_id) {
        require("header.php");
		$buyi = $db_connect->query_first("SELECT armourname, money FROM db_shop_armour where id='1'");
$thread_armor = $db_connect->query_first("select userarmour from db_users where userid='$user_id]'");
$result = $db_connect->query("SELECT id, armourname, money FROM db_shop_armour ORDER BY money ASC");
while($item = $db_connect->fetch_array($result)) eval ("\$shop_items .= \"".gettemplate("armor_shopbit")."\";");
	eval("dooutput(\"".gettemplate("armor_shop")."\");");
} else header("LOCATION: misc.php?action=access_error&boardid=$boardid&styleid=$styleid$session"); 
?>