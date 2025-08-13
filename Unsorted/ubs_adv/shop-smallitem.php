<?
require("functions.php");

$userlastchoice2_stat = $db_connect->query_first("select userextra2 from db_users where userid='$user_id]'");
$mylastchoiceslot2 = $userlastchoice2_stat[userextra2];

if($action == "changeslot"){
$db_connect->query("UPDATE db_users SET userextra2 = '$itemslot' WHERE userid = '$user_id'");
header("LOCATION: shop-smallitem.php");
}

   $user = $db_connect->query_first("SELECT * FROM db_users WHERE userid='$user_id'");
		
		if($itemslot==useritem1) $itemslot[1] = "selected";
                                if($itemslot==useritem2) $itemslot[2] = "selected";
                                if($itemslot==useritem3) $itemslot[3] = "selected";
                                if($itemslot==useritem4) $itemslot[4] = "selected";
                                if($itemslot==useritem5) $itemslot[5] = "selected";
                                if($itemslot==useritem6) $itemslot[6] = "selected";
                                if($itemslot==useritem7) $itemslot[7] = "selected";
                                if($itemslot==useritem8) $itemslot[8] = "selected";

if($user_id) {
        require("header.php");
		$buyi = $db_connect->query_first("SELECT smallitemname, money FROM db_shop_smallitems where id='1'");
$thread_smallitem = $db_connect->query_first("SELECT $mylastchoiceslot2 from db_users where userid='$user_id'");
$result = $db_connect->query("SELECT id, smallitemname, money FROM db_shop_smallitems ORDER BY money ASC");
while($item = $db_connect->fetch_array($result)) eval ("\$shop_items .= \"".gettemplate("smallitem_shopbit")."\";");
	eval("dooutput(\"".gettemplate("smallitem_shop")."\");");
} else header("LOCATION: misc.php?action=access_error&boardid=$boardid&styleid=$styleid$session"); 
?>