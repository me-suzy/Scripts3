<?
require("functions.php");

if($user_id) {
        require("header.php");
		$buyi = $db_connect->query_first("SELECT helmetname, money FROM db_shop_helmet where id='1'");
$thread_helmet = $db_connect->query_first("select userhelmet from db_users where userid='$user_id]'");
$result = $db_connect->query("SELECT id, helmetname, money FROM db_shop_helmet ORDER BY money ASC");
while($item = $db_connect->fetch_array($result)) eval ("\$shop_items .= \"".gettemplate("helmet_shopbit")."\";");
	eval("dooutput(\"".gettemplate("helmet_shop")."\");");
} else header("LOCATION: misc.php?action=access_error&boardid=$boardid&styleid=$styleid$session");
?>