<?
require "functions";

$useritem1_stat = $db_connect->query_first("select useritem1 from db_users where userid='$user_id]'");
$useritem1 = $useritem1_stat[useritem1];
$useritem2_stat = $db_connect->query_first("select useritem2 from db_users where userid='$user_id]'");
$useritem2 = $useritem2_stat[useritem2];
$useritem3_stat = $db_connect->query_first("select useritem3 from db_users where userid='$user_id]'");
$useritem3 = $useritem3_stat[useritem3];
$useritem4_stat = $db_connect->query_first("select useritem4 from db_users where userid='$user_id]'");
$useritem4 = $useritem4_stat[useritem4];
$useritem5_stat = $db_connect->query_first("select useritem5 from db_users where userid='$user_id]'");
$useritem5 = $useritem5_stat[useritem5];
$useritem6_stat = $db_connect->query_first("select useritem6 from db_users where userid='$user_id]'");
$useritem6 = $useritem6_stat[useritem6];
$useritem7_stat = $db_connect->query_first("select useritem7 from db_users where userid='$user_id]'");
$useritem7 = $useritem7_stat[useritem7];
$useritem8_stat = $db_connect->query_first("select useritem8 from db_users where userid='$user_id]'");
$useritem8 = $useritem8_stat[useritem8];
$userweapon_stat = $db_connect->query_first("select userweapon from db_users where userid='$user_id]'");
$userweapon = $userweapon_stat[userweapon];
$usershield_stat = $db_connect->query_first("select usershield from db_users where userid='$user_id]'");
$usershield = $usershield_stat[usershield];
$userarmour_stat = $db_connect->query_first("select userarmour from db_users where userid='$user_id]'");
$userarmour = $userarmour_stat[userarmour];
$usergloves_stat = $db_connect->query_first("select usergloves from db_users where userid='$user_id]'");
$usergloves = $usergloves_stat[usergloves];
$userboots_stat = $db_connect->query_first("select userboots from db_users where userid='$user_id]'");
$userboots = $userboots_stat[userboots];
$userextra1_stat = $db_connect->query_first("select userextra1 from db_users where userid='$user_id]'");
$userextra1 = $userextra1_stat[userextra1];

if($action == sell){
   if($send == send){
      $newmoneyvalue=$itemmoney/2;
      $db_connect->query("UPDATE db_users SET usermoney='$newmoneyvalue' WHERE userid = '$user_id'")
   }
if($item == $useritem1){
$theitem_stat = $db_connect->query_first("select money from db_smallitems where smallitemname='$useritem1'");
$theitem = $theitem_stat[money];
}

$itemmoney=$theitem;

}











?>