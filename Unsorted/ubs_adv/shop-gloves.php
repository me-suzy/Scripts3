<?
require("functions.php");

if($user_id) {
        require("header.php");
		$buyi = $db_connect->query_first("SELECT glovename, money FROM db_shop_gloves where id='1'");
$thread_gloves = $db_connect->query_first("select usergloves from db_users where userid='$user_id]'");
$result = $db_connect->query("SELECT id, glovename, money FROM db_shop_gloves ORDER BY money ASC");
while($item = $db_connect->fetch_array($result)) eval ("\$shop_items .= \"".gettemplate("glove_shopbit")."\";");
	eval("dooutput(\"".gettemplate("glove_shop")."\");");
} else header("LOCATION: misc.php?action=access_error&boardid=$boardid&styleid=$styleid$session"); 
?>