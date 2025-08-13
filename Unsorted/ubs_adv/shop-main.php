<?
require("functions.php");
require "header.php";

if($user_name == Guest){
        header("LOCATION: index.php");
        exit;
}

if($canusetheshop == no){

eval("dooutput(\"".gettemplate("shop_closed")."\");");
}

if($user_id) {
        require("header.php");
$userstat_money = $db_connect->query_first("select usermoney from db_users where userid='$user_id'");
$user_money = $userstat_money[usermoney];
$userstat_sword = $db_connect->query_first("select userweapon from db_users where userid='$user_id]'");
$sword = $userstat_sword[userweapon];
$userstat_shield = $db_connect->query_first("select usershield from db_users where userid='$user_id]'");
$shield = $userstat_shield[usershield];
$userstat_armor = $db_connect->query_first("select userarmour from db_users where userid='$user_id]'");
$armor = $userstat_armor[userarmour];
$userstat_helmet = $db_connect->query_first("select userhelmet from db_users where userid='$user_id]'");
$helmet = $userstat_helmet[userhelmet];
$userstat_boots = $db_connect->query_first("select userboots from db_users where userid='$user_id]'");
$boots = $userstat_boots[userboots];
$userstat_gloves = $db_connect->query_first("select usergloves from db_users where userid='$user_id]'");
$gloves = $userstat_gloves[usergloves];
$userstat_specialitem = $db_connect->query_first("select userextra1 from db_users where userid='$user_id]'");
$specialitem = $userstat_specialitem[userextra1];
$userstat_canusetheshop = $db_connect->query_first("select canusetheshop from db_users where userid='$user_id]'");
$canusetheshop = $userstat_canusetheshop[canusetheshop];

$result = $db_connect->query("SELECT id, swordname, money FROM db_shop_swords ORDER BY money ASC");
$blurp = ($result[id]+1);
$ride = "shop-main.php?";
$ifpurchased = "You already purchased that item";
$newpurchase = "Thank you for your purchase";
$neg = "You don't have enough Gil to puchase that item";
$selling = "Thank you for selling your item to us";

if($sell == "sell" && $canusetheshop == "yes") {
	if($item == "$sword") {
	$sellitem = $db_connect->query_first("SELECT  money FROM db_shop_swords where swordname='$sword'");
	$sellmoney=($user_money+$sellitem[money]/2);
	$db_connect->query("UPDATE db_users SETuserweapon = '$nothing' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$sellmoney' WHERE userid = '$user_id'");
	$infobuy = ($selling);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
	}
                 
                if($item == "$shield") {
	$sellitem = $db_connect->query_first("SELECT  money FROM db_shop_shields where shieldname='$shield'");
	$sellmoney=($user_money+$sellitem[money]/2);
	$db_connect->query("UPDATE db_users SET usershield = '$nothing' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$sellmoney' WHERE userid = '$user_id'");
	$infobuy = ($selling);
			
	}

                if($item == "$armour") {
	$sellitem = $db_connect->query_first("SELECT  money FROM db_shop_armour where armourname='$armor'");
	$sellmoney=($user_money+$sellitem[money]/2);
	$db_connect->query("UPDATE db_users SET userarmour = '$nothing' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$sellmoney' WHERE userid = '$user_id'");
	$infobuy = ($selling);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
	}

                if($item == "$helmet") {
	$sellitem = $db_connect->query_first("SELECT  money FROM db_shop_helmet where helmetname='$helmet'");
	$sellmoney=($user_money+$sellitem[money]/2);
	$db_connect->query("UPDATE db_users SET userhelmet = '$nothing' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$sellmoney' WHERE userid = '$user_id'");
	$infobuy = ($selling);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
	}

                if($item == "$boots") {
	$sellitem = $db_connect->query_first("SELECT  money FROM db_shop_boots where bootname='$boots'");
	$sellmoney=($user_money+$sellitem[money]/2);
	$db_connect->query("UPDATE db_users SET userboots = '$nothing' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$sellmoney' WHERE userid = '$user_id'");
	$infobuy = ($selling);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
	}

                if($item == "$glove") {
	$sellitem = $db_connect->query_first("SELECT  money FROM db_shop_glove where glovename='$glove'");
	$sellmoney=($user_money+$sellitem[money]/2);
	$db_connect->query("UPDATE db_users SETuserglove = '$nothing' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$sellmoney' WHERE userid = '$user_id'");
	$infobuy = ($selling);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
	}

                if($item == "$specialitem") {
	$sellitem = $db_connect->query_first("SELECT  money FROM db_shop_specialitem where specialitemname='$specialitem'");
	$sellmoney=($user_money+$sellitem[money]/2);
	$db_connect->query("UPDATE db_users SET $slotchoice = '$nothing' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$sellmoney' WHERE userid = '$user_id'");
	$infobuy = ($selling);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
	}

                if($item == "$smallitem") {
	$sellitem = $db_connect->query_first("SELECT  money FROM db_shop_smallitem where smallitemname='$smallitem'");
	$sellmoney=($user_money+$sellitem[money]/2);
	$db_connect->query("UPDATE db_users SETuserextra1 = '$nothing' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$sellmoney' WHERE userid = '$user_id'");//100
	$infobuy = ($selling);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
	}
}
   if($buy == "weapon" && $canusetheshop == "yes") {
	$buyweapon = $db_connect->query_first("SELECT swordname, money FROM db_shop_swords where id='$nr'");
	$buymoney=($user_money-$buyweapon[money]);
	if($buyweapon[swordname]==$sword)   {
			$infobuy = ($ifpurchased);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
						}
	else	{
		if($user_money>=$buyweapon[money]) {
			$db_connect->query("UPDATE db_users SET userweapon = '$buyweapon[swordname]' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$buymoney' WHERE userid = '$user_id'");
			$infobuy = ($newpurchase);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");			
						}		
			$infobuy = ($neg);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");	
		}
				}
   if($buy == "shield" && $canusetheshop == "yes") {
	$buyshield = $db_connect->query_first("SELECT shieldname, money FROM db_shop_shields where id='$nr'");
	$buymoney=($user_money-$buyshield[money]);
	if($buyshield[shieldname]==$shield)   {
			$infobuy = ($ifpurchased);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
						}
	else	{
		if($user_money>=$buyshield[money]) {
			$db_connect->query("UPDATE db_users SET usershield = '$buyshield[shieldname]' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$buymoney' WHERE userid = '$user_id'");
			$infobuy = ($newpurchase);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");			
						}		
			$infobuy = ($neg);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");	
		}
				}
   if($buy == "armour" && $canusetheshop == "yes") {
	$buyarmor = $db_connect->query_first("SELECT armourname, money FROM db_shop_armour where id='$nr'");
	$buymoney=($user_money-$buyarmor[money]);
	if($buyarmor[armourname]==$armor)   {
			$infobuy = ($ifpurchased);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
						}
	else	{
		if($user_money>=$buyarmor[money]) {
			$db_connect->query("UPDATE db_users SET userarmour = '$buyarmor[armourname]' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$buymoney' WHERE userid = '$user_id'");
			$infobuy = ($newpurchase);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");			
						}		
			$infobuy = ($neg);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");	
		}
				}
   if($buy == "helmet" && $canusetheshop == "yes") {
	$buyhelmet = $db_connect->query_first("SELECT helmetname, money FROM db_shop_helmet where id='$nr'");
	$buymoney=($user_money-$buyhelmet[money]);
	if($buyhelmet[helmetname]==$helmet)   {
			$infobuy = ($ifpurchased);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
						}
	else	{
		if($user_money>=$buyhelmet[money]) {
			$db_connect->query("UPDATE db_users SET userhelmet = '$buyhelmet[helmetname]' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$buymoney' WHERE userid = '$user_id'");
			$infobuy = ($newpurchase);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");			
						}		
			$infobuy = ($neg);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");	
		}
				}
   if($buy == "boots" && $canusetheshop == "yes") {
	$buyboots = $db_connect->query_first("SELECT bootname, money FROM db_shop_boots where id='$nr'");
	$buymoney=($user_money-$buyboots[money]);
	if($buyboots[bootname]==$boots)   {
			$infobuy = ($ifpurchased);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
						}
	else	{
		if($user_money>=$buyboots[money]) {
			$db_connect->query("UPDATE db_users SET userboots = '$buyboots[bootname]' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$buymoney' WHERE userid = '$user_id'");
			$infobuy = ($newpurchase);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");			
						}		
			$infobuy = ($neg);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");	
		}
				}

   if($buy == "glove" && $canusetheshop == "yes") {
	$buygloves = $db_connect->query_first("SELECT glovename, money FROM db_shop_gloves where id='$nr'");
	$buymoney=($user_money-$buygloves[money]);
	if($buygloves[glovename]==$gloves)   {
			$infobuy = ($ifpurchased);//200
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
						}
	else	{
		if($user_money>=$buygloves[money]) {
			$db_connect->query("UPDATE db_users SET usergloves = '$buygloves[glovename]' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$buymoney' WHERE userid = '$user_id'");
			$infobuy = ($newpurchase);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");			
						}		
			$infobuy = ($neg);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");	
		}
				}

   if($buy == "specialitem" && $canusetheshop == "yes") {
	$buyspecialitem = $db_connect->query_first("SELECT specialitemname, money FROM db_shop_specialitem where id='$nr'");
	$buymoney=($user_money-$buyspecialitem[money]);
	if($buyspecialitem[specialitemname]==$specialitem)   {
			$infobuy = ($ifpurchased);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
						}
	else	{
		if($user_money>=$buyspecialitem[money]) {
			$db_connect->query("UPDATE db_users SET userextra1 = '$buyspecialitem[specialitemname]' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$buymoney' WHERE userid = '$user_id'");
			$infobuy = ($newpurchase);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");			
						}		
			$infobuy = ($neg);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");	
		}
				}

   if($buy == "smallitem" && $canusetheshop == "yes") {
	$buysmallitem = $db_connect->query_first("SELECT smallitemname, money FROM db_shop_smallitems where id='$nr'");
	$buymoney=($user_money-$buysmallitem[money]);
	if($buysmallitem[smallitemname]==$smallitem)   {
			$infobuy = ($ifpurchased);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");
						}
	else	{
		if($user_money>=$buysmallitem[money]) {
			$db_connect->query("UPDATE db_users SET $slotchoice='$buysmallitem[smallitemname]' WHERE userid = '$user_id'");
                  	$db_connect->query("UPDATE db_users SET usermoney = '$buymoney' WHERE userid = '$user_id'");
			$infobuy = ($newpurchase);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");			
						}		
			$infobuy = ($neg);
			eval("dooutput(\"".gettemplate("shop_ride")."\");");	
		}
                                                                }
	}

	eval("dooutput(\"".gettemplate("main_shop")."\");");
?>