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
# $Id: product.php,v 1.51.2.1 2003/06/02 11:57:43 svowl Exp $
#

require "../smarty.php";
require "../config.php";
@include "./https.php";
require "./auth.php";

require "./news.php";

#
# Put all product info into $product array
#

$product_info = func_select_product($productid, $user_account['membership']);
if (empty($cat)) {
	$cat = $product_info["categoryid"];
}

if($active_modules["Detailed_Product_Images"])
	include "../modules/Detailed_Product_Images/product_images.php";

if($active_modules["Product_Options"])
	include "../modules/Product_Options/customer_options.php";

if($active_modules["Upselling_Products"])
	include "../modules/Upselling_Products/related_products.php";

if($active_modules["Advanced_Statistics"])
    include "../modules/Advanced_Statistics/prod_viewed.php";

if($active_modules["Extra_Fields"]) {
    $extra_fields_provider=$product_info["provider"];
    include "../modules/Extra_Fields/extra_fields.php";
}

if($active_modules["Subscriptions"])
	include "../modules/Subscriptions/subscription_modify.php";
	
if($active_modules["Recommended_Products"])
	include "./recommends.php";

include "./vote.php";

#
# Wholesales table
#
$wresult = func_query ("SELECT * FROM $sql_tbl[pricing] WHERE productid='$productid' AND ($sql_tbl[pricing].membership='$user_account[membership]' or $sql_tbl[pricing].membership='') AND $sql_tbl[pricing].quantity>1 ORDER BY quantity");
if ($wresult) {
	$f = true;
	foreach ($wresult as $wk=>$wv) {
		if ($f)
			$wresult [$wk]["next_quantity"] = 0;
		else
			$wresult [$wk-1]["next_quantity"] = $wv["quantity"]-1;
		$f = false;
	}
	$smarty->assign ("product_wholesale", $wresult);
}

$smarty->assign("pricing",$pricing);


require "../include/categories.php";

if(!empty($current_category)) $location = $category_location;

$smarty->assign("location",$location);
$smarty->assign("product",$product_info);
$smarty->assign("main","product");

$smarty->display("customer/home.tpl");
?>
