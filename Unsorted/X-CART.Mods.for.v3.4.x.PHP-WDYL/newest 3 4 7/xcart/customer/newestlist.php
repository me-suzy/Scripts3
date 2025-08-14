<?
// newestlist.php

require "../smarty.php";
require "../config.php";
@include "./https.php";
require "./auth.php";

require "../include/categories.php";

require "./newest_products.php";

if($active_modules["Bestsellers"])
	include "../modules/Bestsellers/bestsellers.php";

#
# Assign Smarty variables and show template
#

$smarty->assign("main","newestlist");
$smarty->assign("location","Newest Products");
$smarty->display("customer/home.tpl");
?>
