<?
require("functions.php");

if($user_id) {
        require("header.php");
		$buyi = $db_connect->query_first("SELECT specialitemname, money FROM db_shop_specialitem where id='1'");
$thread_specialitem = $db_connect->query_first("select userextra1 from db_users where userid='$user_id]'");
$result = $db_connect->query("SELECT id, specialitemname, money FROM db_shop_specialitem ORDER BY money ASC");
while($item = $db_connect->fetch_array($result)) eval ("\$shop_items .= \"".gettemplate("specialitem_shopbit")."\";");
	eval("dooutput(\"".gettemplate("specialitem_shop")."\");");
} else header("LOCATION: misc.php?action=access_error&boardid=$boardid&styleid=$styleid$session"); 
?>