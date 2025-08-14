<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2003 Ruslan R. Fazliev <rrf@rrf.ru>                      |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev             |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2003           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: search.php,v 1.44.2.3 2003/08/26 10:48:01 svowl Exp $
#

require "../smarty.php";
require "../config.php";
@include "./https.php";
require "./auth.php";
require "../include/categories.php";

require "./news.php";

$tmp=strstr($QUERY_STRING, "$XCART_SESSION_NAME=");
if (!empty($tmp))  
	$QUERY_STRING=ereg_replace("$XCART_SESSION_NAME=([0-9a-zA-Z]*)", "", $QUERY_STRING);

if(!empty($QUERY_STRING)) {
#
# Perform SQL search query
#

	$substring = trim($substring);
	
	$price_condition = $price_search_1?" AND $sql_tbl[pricing].price>='$price_search_1'":"";
	$price_condition .= $price_search_2?" AND $sql_tbl[pricing].price<='$price_search_2'":"";

	$price_substring = $price_search_1?"&price_search_1=".urlencode($price_search_1):"";
	$price_substring .= $price_search_2?"&price_search_2=".urlencode($price_search_2):"";

	if ($price_condition)
		$sort_by_price = "price";
	
	if ($in_category) {
		$search_category = addslashes(array_pop(func_query_first("select category from $sql_tbl[categories] where categoryid='$in_category'")));
		$search_categories = func_query("select categoryid from $sql_tbl[categories] where $sql_tbl[categories].category like '$search_category%'");
		if(is_array($search_categories)) {
			$category_condition=" in ( ";
			foreach($search_categories as $k=>$v)
				$category_condition .= "'$v[categoryid]', ";
			$category_condition = ereg_replace(", $", ")", $category_condition);
			$category_condition=" ($sql_tbl[products].categoryid $category_condition or $sql_tbl[products].categoryid1 $category_condition or $sql_tbl[products].categoryid2 $category_condition or $sql_tbl[products].categoryid3 $category_condition) ";
		}
	}
	else
		$category_condition = "1";
	
	$membership_condition = " AND ($sql_tbl[categories].membership='". $user_account['membership']."' OR $sql_tbl[categories].membership='') ";

	if ($store_language != $config["default_customer_language"] && $substring) {
	
		$substring_query = "AND (($sql_tbl[products].productcode like '%$substring%' or $sql_tbl[products].product like '%$substring%' or $sql_tbl[products].descr like '%$substring%' or $sql_tbl[products].fulldescr like '%$substring%') OR ($sql_tbl[products_lng].code='$store_language' AND ($sql_tbl[products_lng].product LIKE '%$substring%' OR $sql_tbl[products_lng].descr LIKE '%$substring%' OR $sql_tbl[products_lng].full_descr LIKE '%$substring%')))";

		$search_query_count = "select count(*) from $sql_tbl[pricing], $sql_tbl[categories], $sql_tbl[products] LEFT JOIN $sql_tbl[products_lng] ON $sql_tbl[products].productid=$sql_tbl[products_lng].productid where $sql_tbl[pricing].productid=$sql_tbl[products].productid and $sql_tbl[pricing].quantity=1 and $sql_tbl[products].categoryid=$sql_tbl[categories].categoryid $membership_condition and ($sql_tbl[pricing].membership='". $user_account['membership']."' or $sql_tbl[pricing].membership='') AND $category_condition and $sql_tbl[products].forsale='Y' and $sql_tbl[categories].avail='Y' $price_condition $substring_query group by $sql_tbl[products].productid";

	}
	else {
		$substring_query = "AND ($sql_tbl[products].productcode like '%$substring%' or $sql_tbl[products].product like '%$substring%' OR $sql_tbl[products].descr like '%$substring%' OR $sql_tbl[products].fulldescr like '%$substring%')";

		$search_query_count = "select count(*) from $sql_tbl[products], $sql_tbl[pricing], $sql_tbl[categories] where $sql_tbl[pricing].productid=$sql_tbl[products].productid and $sql_tbl[pricing].quantity=1 and $sql_tbl[products].categoryid=$sql_tbl[categories].categoryid $membership_condition and ($sql_tbl[pricing].membership='". $user_account['membership']."' or $sql_tbl[pricing].membership='') and ($sql_tbl[products].product like '%$substring%' or $sql_tbl[products].descr like '%$substring%') AND $category_condition and $sql_tbl[products].forsale='Y' and $sql_tbl[categories].avail='Y' $price_condition $substring_query group by $sql_tbl[products].productid";
	}
	
	$search_query = "$category_condition and $sql_tbl[categories].avail='Y' and $sql_tbl[products].forsale='Y' $price_condition $substring_query ";

	
	$total_products_in_search =  count(func_query($search_query_count));

#
# Navigation code
#
	$objects_per_page = $config["Appearance"]["products_per_page"];

	$total_nav_pages = ceil($total_products_in_search/$objects_per_page)+1;
	require "../include/navigation.php";

	$smarty->assign("products",func_search_products($search_query, $user_account['membership'],$first_page,$total_products_in_search, 0, $sort_by_price));

	$smarty->assign("navigation_script","search.php?substring=".urlencode($substring)."&in_category=$in_category".$price_substring);

	$HTTP_GET_VARS["substring"] = stripslashes($HTTP_GET_VARS["substring"]);

	$smarty->assign("main","search");

}
else {
	$smarty->assign("main","advanced_search");
}

$smarty->display("customer/home.tpl");
?>
