<?
// newest products script by funkydunk.net 2003

// set variables if this has not been passed to it by the browser set to default to 7 days
if (!$range){
// if range does not exist then we are on the home page
$range = 604800; // 60 * 60 * 24 * 7 days in unix time
$nowtime = time();
$oldtime = $nowtime - $range ;
$number = $config["Appearance"]["number_newest"];

// the database query
$query = "SELECT * FROM $sql_tbl[products],$sql_tbl[pricing] WHERE forsale='Y' AND avail>0 AND $sql_tbl[products].productid=$sql_tbl[pricing].productid AND add_date > " . $oldtime . " AND add_date < " . $nowtime . " ORDER BY RAND() LIMIT " . $number; 

// give the product array to smarty to make it available sitewide.
$newproductshome = func_query($query);
$smarty->assign("newproducts",$newproductshome);
}
else{
// we are on the newest items page

$nowtime = time();
$oldtime = $nowtime - $range ;

// to put in the number links for navigation purposes
$objects_per_page = $config["Appearance"]["products_per_page"];

$newest = array();

$newest["product_count"] = array_pop(func_query_first("SELECT COUNT(*) FROM $sql_tbl[products] WHERE $sql_tbl[products].forsale='Y' AND avail>0 AND add_date > $oldtime AND add_date < $nowtime" ));

$total_nav_pages = ceil($newest["product_count"]/$objects_per_page)+1;
require "../include/navigation.php";

// the database query for listing page
// $query = "add_date > " . $oldtime . " AND add_date < " . $nowtime . " ORDER BY add_date DESC"; 
$query = "($sql_tbl[products].add_date > $oldtime AND $sql_tbl[products].add_date < $nowtime )"; 
// func_print_r($newest);
$newproducts = func_search_products($query, $user_account['membership'], $first_page, $newest["product_count"]);
if (count($newproducts) ==0) $newproducts="";
// func_print_r($newproducts);
// give the product array to smarty to make it available on home page.

$smarty->assign("newproducts",$newproducts);
$smarty->assign("navigation_script","newestlist.php?main=newestlist&range=$range");
}
?>