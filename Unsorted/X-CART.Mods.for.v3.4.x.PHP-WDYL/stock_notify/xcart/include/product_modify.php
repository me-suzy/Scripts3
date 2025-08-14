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
# $Id: product_modify.php,v 1.75.2.4 2003/07/24 12:46:48 svowl Exp $
#
# Add, modify product
# Get product information
#

if ($mode=="delete_thumbnail" && !empty($productid)) {
	db_query("DELETE FROM $sql_tbl[thumbnails] WHERE productid='$productid'");
	db_query("UPDATE $sql_tbl[products] SET image_x='0', image_y='0' WHERE productid='$productid'");
	func_header_location("product_modify.php?productid=$productid");
}

x_session_register("file_upload_data");

if ($productid != "") {
	$product_info = func_select_product($productid, $user_account['membership']);
	$product_languages = func_query ("SELECT $sql_tbl[products_lng].*, $sql_tbl[countries].country, $sql_tbl[countries].language FROM $sql_tbl[products_lng], $sql_tbl[countries] WHERE $sql_tbl[products_lng].productid='$productid' AND $sql_tbl[products_lng].code=$sql_tbl[countries].code");
}

$int_languages = func_query ("SELECT DISTINCT($sql_tbl[languages].code), $sql_tbl[countries].country, $sql_tbl[countries].language FROM $sql_tbl[languages], $sql_tbl[countries] WHERE $sql_tbl[languages].code=$sql_tbl[countries].code AND $sql_tbl[languages].code!='$config[default_admin_language]'");

$other_languages = array ();
if ($int_languages) {
	foreach ($int_languages as $key=>$value) {
		$found = false;

		if ($product_languages) {
			foreach ($product_languages as $key1 => $lng) {
				$product_languages[$key1]["product"] = stripslashes($lng["product"]);
				$product_languages[$key1]["descr"] = stripslashes($lng["descr"]);
				$product_languages[$key1]["full_descr"] = stripslashes($lng["full_descr"]);
				if ($lng["code"] == $value["code"])
					$found = true;
			}
		}
		if (!$found)
			$other_languages[] = $value;
	}
}

$smarty->assign("int_languages", $int_languages);
$smarty->assign("other_languages", $other_languages);
$smarty->assign("product_languages", $product_languages);

if (($REQUEST_METHOD == "POST") && ($mode == "product_modify")) {

	$fillerror = (($categoryid == "") || empty($product) || empty($descr) || ($price == "") || ($avail == "") || empty($low_avail_limit));

    $image_posted = func_check_image_posted($file_upload_data, "T");
    $store_in = ($config["Images"]["thumbnails_location"] == "FS"?"FS":"DB");

#
# Replace newlines by <br>'s in description fields
#
	if ($descr == strip_tags($descr))
		$descr = str_replace("\n", "<br>", $descr);
	if ($fulldescr == strip_tags($fulldescr))
		$fulldescr = str_replace("\n", "<br>", $fulldescr);

	if (!$fillerror) {
		if ($productid == "") {
#
# New product
#

#
# Get the last pos in products' category
#
			$orderby = 1 + array_pop(func_query_first("select max(orderby) from $sql_tbl[products] where categoryid='$categoryid'"));

			db_query("insert into $sql_tbl[products] (provider, add_date, orderby) values ('$login', '".time()."', '$orderby')");

			$productid = db_insert_id();

			if ($image_posted)
				$image_data = func_get_image_content($file_upload_data, $productid);
#
# Insert pricing and image
#
			db_query("insert into $sql_tbl[pricing] (productid, quantity, price) values ('$productid', '1', '$price')");

			if ($image_posted) {
				if ($store_in == "FS")
					db_query("insert into $sql_tbl[thumbnails] (productid, image_path, image_type) values ('$productid', '$image_data[image]', '$image_data[image_type]')");
				else
					db_query("insert into $sql_tbl[thumbnails] (productid, image, image_type) values ('$productid', '$image_data[image]', '$image_data[image_type]')");
			}

			$smarty->assign("main", "product_add_message");
		} else {
#
# Update existing product
# For security reasons check if provider owners the
# product and then update pricing
#
			db_query("update $sql_tbl[pricing] set price='$price' where productid='$productid' and quantity='1' and membership=''");

		// funkydunk added code for notify when back in stock
		
		if ($notify){
			if ($avail > $oldavail){
			// send the email to all registered product watchers
			$mailproduct= func_query_first("SELECT * from `xcart_products` WHERE productid = '$productid'");
			$watchers = db_query("SELECT email from `xcart_notify` WHERE productid = '$productid'");
			// echo "SELECT * from `xcart_notify` WHERE productid = '$productid'";
			$mail_smarty->assign ("product", $mailproduct); // put in to assign product info to smarty
			$mail_smarty->assign ("userinfo", $userinfo);	
				while ($row = db_fetch_array($watchers)){
					// echo $row['email'];
					$email = $row['email'];
					func_send_mail($email, "mail/".$prefix."stock_notify_subj.tpl", "mail/".$prefix."stock_notify.tpl", $config["Company"]["orders_department"], false);
					// delete watchers from table
					db_query("delete from `xcart_notify` where email='" . $email . "' and productid = $productid");
				}
			
			}
		}
		// end of code added by funkydunk

			if ($image_posted) {
				$image_data = func_get_image_content($file_upload_data, $productid);

				db_query("delete from $sql_tbl[thumbnails] where productid='$productid'");

				if ($store_in == "FS") 
					db_query("insert into $sql_tbl[thumbnails] (productid, image_path, image_type) values ('$productid', '$image_data[image]', '$image_data[image_type]')");
				else
					db_query("insert into $sql_tbl[thumbnails] (productid, image, image_type) values ('$productid', '$image_data[image]', '$image_data[image_type]')");
				db_query("update $sql_tbl[products] set image_x='$image_data[image_x]', image_y='$image_data[image_y]' where productid='$productid'");
			}

        		$smarty->assign("main","product_modify_message");
        	}

		if ($product_info)
			$old_product_categories = array($product_info["categoryid"], $product_info["categoryid1"], $product_info["categoryid2"], $product_info["categoryid3"]);
		if ($config["Taxes"]["use_canadian_taxes"] == "Y")
			$apply_canadian_taxes = ", apply_gst='$apply_gst', apply_pst='$apply_pst'";
		if ($config["Taxes"]["use_vat"] == "Y")
			$apply_vat = "vat='$vat', ";

#
# Update product data
#
		db_query("update $sql_tbl[products] set product='$product', categoryid='$categoryid', categoryid1='$categoryid1', categoryid2='$categoryid2', categoryid3='$categoryid3', brand='$brand', model='$model', descr='$descr', fulldescr='$fulldescr', avail='$avail', list_price='$list_price', weight='$weight', productcode='$productcode', forsale='$forsale', distribution='$distribution', free_shipping='$free_shipping', shipping_freight='$shipping_freight', discount_avail='$discount_avail', min_amount='$min_amount', param00='$param00', param01='$param01', param02='$param02', param03='$param03', param04='$param04', param05='$param05', param06='$param06', param07='$param07', param08='$param08', param09='$param09', low_avail_limit='$low_avail_limit', $apply_vat free_tax='$free_tax' $apply_canadian_taxes where productid='$productid'");

#
# Update products counter for selected categories
#
		$product_categories = array_merge($old_product_categories, array($categoryid, $categoryid1, $categoryid2, $categoryid3));

		foreach($product_categories as $catid) {
			if ($catid) {
				$product_count = array_pop(func_query_first("SELECT COUNT(*) FROM $sql_tbl[products] WHERE forsale='Y' AND (categoryid='$catid' OR categoryid1='$catid' OR categoryid2='$catid' OR categoryid3='$catid')"));
				db_query("UPDATE $sql_tbl[categories] SET product_count='$product_count' WHERE categoryid='$catid'");
			}
		}
	
	} else {
#
# Form filled with errors
#
		$product_info = $HTTP_POST_VARS;
		$product_info["productid"] = $productid;
		$smarty->assign("main", "product_modify");
	}
} else {
#
# GET request
#
	$smarty->assign("main", "product_modify");
}

#
# International descriptions
#
if ($mode == "update_lng") {
	if ($product_lng_code) {
		foreach ($product_lng_code as $value) {
			db_query ("UPDATE $sql_tbl[products_lng] SET product='".$product_lng_title[$value]."', descr='".$product_lng_descr[$value]."', full_descr='".$product_lng_full_descr[$value]."' WHERE code='$value' AND productid='$productid'");
		}
	}

	if ($product_new_descr or $product_new_full_descr) {
		db_query ("INSERT INTO $sql_tbl[products_lng] (code, productid, product, descr, full_descr) VALUES ('$product_new_language','$productid','$product_new_title','$product_new_descr','$product_new_full_descr')");
	}

	func_header_location("product_modify.php?productid=$productid&lng_updated");
}

if ($mode == "del_lang") {
	db_query ("DELETE FROM $sql_tbl[products_lng] WHERE productid='$productid' AND code='$code'");

	func_header_location("product_modify.php?productid=$productid&lng_deleted");
}

#
# Detailed__Product_Images module
#
if ($active_modules["Detailed_Product_Images"]) {
	include "../modules/Detailed_Product_Images/product_images_modify.php";
	include "../modules/Detailed_Product_Images/product_images.php";
}
#
# Subscription module
#
if ($active_modules["Subscriptions"])
    include "../modules/Subscriptions/subscription_modify.php";
#
# Wholesale trading module
#
if ($active_modules["Wholesale_Trading"])
	include "../modules/Wholesale_Trading/product_wholesale.php";
#
# Product Configurator module
#
if($active_modules["Product_Configurator"])
	include "../modules/Product_Configurator/compatibility.php";
#
# Product options module
#
if ($active_modules["Product_Options"])
	include "../modules/Product_Options/product_options.php";
#
# Extra fields module
#
if ($active_modules["Extra_Fields"]) {
	$extra_fields_provider = ( $current_area == "A" ? $product_info["provider"] : $login );
	include "../modules/Extra_Fields/extra_fields.php";
}

#include "../modules/Product_Shipping/product_shipping.php";

#
# Upselling products module
#
if ($active_modules["Upselling_Products"])
	include "../modules/Upselling_Products/edit_upsales.php";

#   
# Tax Zones module
#
if ($active_modules["Tax_Zones"])
	include "../modules/Tax_Zones/edit_product.php";

#
# Customer Reviews module
#
include "../include/reviews.php";

if (($productid != "") && !$fillerror) 
	$product_info = func_select_product($productid, $user_account['membership']); 

#
# Obtain VAT rates
#
if ($single_mode)
	$provider_condition = "";
elseif ($current_area == "A")
	$provider_condition = "AND provider='$product_info[provider]'";
else
	$provider_condition = "AND provider='$login'";

$vat_rates = func_query("SELECT * FROM $sql_tbl[vat_rates] WHERE 1 $provider_condition");

$smarty->assign("vat_rates",$vat_rates);

#
# Check if image selected is not expired
#
if ($file_upload_data["imtype"] == "T") {

	if ($file_upload_data["counter"] == 1) {
	    $file_upload_data["counter"]++;

	    $smarty->assign("file_upload_data", $file_upload_data);
	}
	else {
	    if ($file_upload_data["source"] == "L")
	        @unlink($file_upload_data["file_path"]);
	    x_session_unregister("file_upload_data");
	}
}

x_session_save();

$smarty->assign("query_string", urlencode($QUERY_STRING));
$smarty->assign("product", $product_info);
$smarty->assign("fillerror", $fillerror);

?>