<?
// news.php funkydunk 2003

require "../smarty.php";
require "../config.php";
@include "./https.php";
require "./auth.php";
require "../include/categories.php";

require "./news.php";

$query = "SELECT * FROM `xcart_news` WHERE enabled = 'Y' and newsid = '$newsid'"; 
$newsitem = func_query_first($query);
#
# Assign Smarty variables
#
$smarty->assign("location","News");
$smarty->assign("newsitem",$newsitem);
$smarty->assign("main","news");
$smarty->display("customer/home.tpl");
?>
