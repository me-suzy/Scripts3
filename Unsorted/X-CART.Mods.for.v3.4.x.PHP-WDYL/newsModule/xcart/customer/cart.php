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
# $Id: cart.php,v 1.153.2.8 2003/09/10 06:14:10 svowl Exp $
#
# This script implements shopping cart facility
#

require "../smarty.php";
require "../config.php";
@include "./https.php";
require "./auth.php";
require "./news.php";

//include "./nocookie_warning.php";

x_session_register("cart");
x_session_register("intershipper_rates");
x_session_register("intershipper_recalc");
x_session_unregister("secure_oid");

$intershipper_recalc = "Y";

#
# $order_secureid (for security reasons)
#
x_session_register("order_secureid");


#
# This function checks if shipping method have defined shipping rate
#
function func_is_shipping_method_allowable($shippingid, $customer_info, $products) {
	global $sql_tbl, $config, $single_mode;

	foreach($products as $product) {
#
# Get the provider info (only for PRO, single_mode=false)
#
		if (!$single_mode) {
			$provider = array_pop(func_query_first("SELECT provider FROM $sql_tbl[products] WHERE productid='$product[productid]'"));
			$provider_condition = "AND provider='$provider'";
		}
		else
			$provider_condition = "";

#
# Get the shipping zone
#
		$customer_zone = array_pop(func_query_first("select zoneid from $sql_tbl[country_zones] where code='$customer_info[s_country]'  $provider_condition"));
		if ($customer_info["s_country"] == $config["Company"]["location_country"]) {
			$customer_zone_tmp = array_pop(func_query_first("select zoneid from $sql_tbl[state_zones] where code='$customer_info[s_state]' $provider_condition"));
			if ($customer_zone_tmp) $customer_zone = $customer_zone_tmp;
		}

		if (!$customer_zone) $customer_zone = 0;
	
		$shipping = array_pop(func_query_first("SELECT COUNT(*) FROM $sql_tbl[shipping_rates] WHERE shippingid='$shippingid' $provider_condition AND zoneid='$customer_zone' AND type='D'"));

		if (!$shipping) return false;
	}
	return true;
}



#
# UNSET GIFT CERTIFICATE
#
if ($mode == "unset_gc" && $gcid) {

    foreach ($cart["applied_giftcerts"] as $k=>$v) {
        if ($v["giftcert_id"] == $gcid) {
            $cart["total_cost"] = $cart["total_cost"] - $v["giftcert_cost"];
            continue;
        }
        $tmp[] = $v;
    }
    $cart["applied_giftcerts"] = $tmp;

    db_query("UPDATE $sql_tbl[giftcerts] SET status='A' WHERE gcid='$gcid'");
    func_header_location("cart.php?mode=checkout");

}

#
# Register member if not registerred yet
# (not a newbie - do not show help messages)
#
$smarty->assign("register_script_name","cart.php");

if ($mode == "checkout") {
	$usertype = "C";
	include "../include/register.php";
	if ($auto_login) {
	    func_header_location("cart.php?mode=checkout&registered=");
	}
}

if (!empty($login)) $userinfo = func_userinfo($login, $current_area);

#
# Add to cart
#
if($mode=="add" && $productid!="") {

	$added_product=func_select_product($productid, $user_account['membership']);
	$amount = intval($amount);

#
# Add to cart amount of items that is not much than in stock
#
	if ($config["General"]["unlimited_products"]=="N")
		if ($amount > $added_product["avail"])
			$amount = $added_product["avail"];
		
	if ($productid && $amount) { 

		if ($amount < $added_product[min_amount]) {
			func_header_location ("error_message.php?access_denied");
		}

#
# Do addition to cart
# With options
#
		$options=array();

		if($product_options) {
			if ($active_modules["Product_Options"])
				func_check_product_options ($productid, $product_options);
		
			foreach($product_options as $key=>$product_option)
				$options[]=array("optclass"=>$key,"optionindex"=>$product_option);
		}

		$found = false;
		if ($cart["products"]) {
			foreach ($cart["products"]  as $k=>$v) {
				if (($v["productid"] == $productid) and (!$found) and ($v["options"] == $options)) {
					$found = true;
					$distribution = array_pop(func_query_first("select distribution from $sql_tbl[products] where productid='$productid'"));
					if (($cart["products"][$k]["amount"] >=1) && ($distribution))		
						{$cart["products"][$k]["amount"]=1; $amount=0;}
					$cart["products"][$k]["amount"] += $amount;
				}
			}
		}
#
# price value is defined by customer if administrator set it to '0.00'
#
		if (!$found)
			$cart["products"][]=array("productid"=>$productid,"amount"=>$amount, "options"=>$options, "price"=>$price);

		$intershipper_recalc = "Y";

	}
	

}

#
# DELETE PRODUCT FROM THE CART
#
if ($mode=="delete" && $productindex!="") {
#
# Delete product from cart
#
	if($active_modules["Advanced_Statistics"])
	    @include "../modules/Advanced_Statistics/prod_del.php";
	array_splice($cart["products"],$productindex,1);
	$intershipper_recalc = "Y";
	func_header_location("cart.php");
} elseif($shippingid!="") {
	$cart["shippingid"]=$shippingid;
}

#
# UPDATES PRODUCTS QUANTITY IN THE CART
#
if ($action=="update" && $productindexes!="") {
#
# Update quantity
#
	foreach($cart["products"] as $k=>$v) {
		$tot = 0;
		foreach($productindexes as $productindex=>$new_quantity) {
			if (!is_numeric($new_quantity)) continue;
			if ($cart["products"][$productindex]["productid"] == $v["productid"])
			$tot += floor($new_quantity);
		}
		$updates_array[$k] = array("quantity"=>$v["amount"], "total_quantity"=>$tot);
	}

	foreach($productindexes as $productindex => $new_quantity) {
	
		if (!is_numeric($new_quantity)) continue;
		
		$new_quantity = floor($new_quantity);
		$productid=$cart["products"][$productindex]["productid"];
		if ($config["General"]["unlimited_products"]=="N")
			$amount_max=array_pop(func_query_first("select avail from $sql_tbl[products] where productid='$productid'"));
		else
			$amount_max=$updates_array[$productindex]["total_quantity"]+1;
		$amount_min=array_pop(func_query_first("select min_amount from $sql_tbl[products] where productid='$productid'"));

		if ($amount_max < $updates_array[$productindex]["total_quantity"])
			continue;

		if (($new_quantity >= $amount_min ) && ($products[$productindex]["distribution"]))
			$cart["products"][$productindex]["amount"] = 1;	
		elseif (($new_quantity >= $amount_min) && ($new_quantity <= $amount_max))
			$cart["products"][$productindex]["amount"] = $new_quantity;
		elseif  ($new_quantity >= $amount_min)
			$cart["products"][$productindex]["amount"] = $amount_max;
		else
			$cart["products"][$productindex]["amount"] = 0;
	}
	
    foreach($cart["products"] as $index => $l)
    	if(!$cart["products"][$index]["amount"])
			array_splice($cart["products"],$index,1);

	$intershipper_recalc = "Y";
#
# Update shipping method
#
	if($shippingid!="") $cart["shippingid"]=$shippingid;

	func_header_location("cart.php?mode=$mode");
}



if (!func_is_cart_empty($cart)) {

	$products = func_products_in_cart($cart, $userinfo["membership"]);
    if (is_array($products))
    if (count($products) != count($cart["products"])) {
        foreach($products as $k=>$v)
            $prodids[] = $v["productid"];
        if (is_array($prodids)) {
            foreach($cart["products"] as $k=>$v)
                if (in_array($v["productid"], $prodids))
                    $cart_prods[$k] = $v;
			$cart["products"] = $cart_prods;
        }
		else
			$cart = "";
		func_header_location("cart.php?$QUERY_STRING");
    }

	if($active_modules["Subscriptions"]) {
    	include "../modules/Subscriptions/subscription.php";
	}

	if (!$login && $config["General"]["apply_default_country"]=="Y") {
		$userinfo["s_country"] = $config["General"]["default_country"];
		$userinfo["s_state"] = $config["General"]["default_state"];
		$userinfo["s_zipcode"] = $config["General"]["default_zipcode"];
		$userinfo["s_city"] = $config["General"]["default_city"];
	}

#
# Check if only downloadable products placed in cart
#
	$need_shipping = false;
	if ($config["Shipping"]["disable_shipping"]!="Y") {
		if (is_array($products))
			foreach ($products as $product) 
				if (empty($product["distribution"]) && $product["free_shipping"]!="Y") $need_shipping = true;
	}

	$smarty->assign("need_shipping", $need_shipping);

	if ($need_shipping) {
	
	if (!empty ($cart["products"]) && ($login || $config["General"]["apply_default_country"]=="Y" || !($config["Shipping"]["enable_all_shippings"] == "Y"))) {
		$destination_condition = " and destination=".($userinfo["s_country"]!=$config["Company"]["location_country"]?"'I'":"'L'");
	}
#
# Fetch real shipping values
#
	$total_weight_shipping = func_weight_shipping_products($products);

	if($config["Shipping"]["realtime_shipping"]=="Y" && !empty($cart["products"]) && ($login || $config["General"]["apply_default_country"]=="Y")) {
		if ($intershipper_recalc != "N") {
			if ($config["Shipping"]["use_intershipper"] == "Y")
				include "../shipping/intershipper.php";
			else
				include "../shipping/myshipper.php";

			$intershipper_rates = func_shipper($total_weight_shipping);
			if( !empty($intershipper_error) ){
				$smarty->assign("shipping_calc_service","Intershipper");
				$smarty->assign("shipping_calc_error",$intershipper_error);
			}

			$intershipper_recalc = "N";
		}
	}

	if (!empty($cart["products"])) {
		$weight_condition = " AND (weight_limit='0' OR weight_limit>='$total_weight_shipping')";
		if ((!$login && $config["General"]["apply_default_country"]!="Y") || $config["Shipping"]["realtime_shipping"]!="Y")
			$shipping = func_query("select * from $sql_tbl[shipping] where active='Y' $destination_condition $weight_condition order by orderby");
		else {
			$shipping = array ();
			if($intershipper_rates)
				foreach($intershipper_rates as $intershipper_rate) {
					$result = func_query_first("select * from $sql_tbl[shipping] where subcode='$intershipper_rate[methodid]' and active='Y' $weight_condition order by orderby");
					if ($result)
						$shipping[] = $result;
			}
			$result = func_query ("SELECT * FROM $sql_tbl[shipping] WHERE code='' AND active='Y' $destination_condition $weight_condition ORDER BY orderby");
			if ($result) {
				foreach ($result as $res) 
						$shipping[] = $res;
			}
				function array_cmp($a, $b) {
					if ($a[orderby] == $b[orderby]) return 0;
					return ($a[orderby] > $b[orderby]) ? 1: -1;
				}
				usort($shipping, "array_cmp");
		}
	}

	if (count($shipping)==0) 
		unset($shipping);
	elseif ($active_modules["Simple_Mode"] || $single_mode) {
		
		foreach($shipping as $k=>$v) {
# Only for defined shipping rates (not for real-time)
			if (($config["Shipping"]["realtime_shipping"]=="Y" && $v["code"]=="") || $config["Shipping"]["realtime_shipping"]!="Y") {
				if (func_is_shipping_method_allowable($v["shippingid"], $userinfo, $cart["products"]))
					$tmp_shipping[] = $v;
			}
			else
				$tmp_shipping[] = $v;
		}
		if ($tmp_shipping) {
			$shipping = $tmp_shipping;
			unset($tmp_shipping);
		}
		else
			unset($shipping);
	}
	$smarty->assign("shipping", $shipping);

	}
}


if ($need_shipping) {
#
# If current shipping is empty set it to default (first in shipping array)
#
	$shipping_matched = false;

	if($shipping)
		foreach($shipping as $shipping_method)
			if($cart["shippingid"]==$shipping_method["shippingid"]) $shipping_matched=true;

	if(!$shipping_matched)
		$cart["shippingid"]=$shipping[0]["shippingid"];

	$cart["delivery"]=array_pop(func_query_first("select shipping from $sql_tbl[shipping] where shippingid='$cart[shippingid]'"));

}
else { 
	$cart["delivery"] = ""; 
	$cart["shippingid"] = 0;
}
		
$smarty->assign("main","cart");

#
# Wishlist facility
#
if($active_modules["Wishlist"]) {
	@include "../modules/Wishlist/wishlist.php";
}

#
# If cart is not empty put products' details into products array
#
if (!func_is_cart_empty($cart)) {
#
# Discount coupons
#
	if($active_modules["Discount_Coupons"])
		include "../modules/Discount_Coupons/discount_coupons.php";

#
# Calculate all prices
#
		$cart = array_merge ($cart, func_calculate($cart, $products, $login, $current_area));
		$smarty->assign("cart",$cart);

}

#
# Redirect
#
if($mode=="add" and $productid) {
	if($config["General"]["redirect_to_cart"]=="Y")
		func_header_location("cart.php");
	else
		func_header_location("home.php?cat=$cat&page=$page");
}

#
# SHOPPING CART FEATURE
#

if (($mode=="checkout") && (!empty($cart["products"])) && (!$shipping) && ($login) && $need_shipping && $config["Shipping"]["disable_shipping"]!="Y") {
	func_header_location("error_message.php?error_no_shipping");
}

if ($mode=="checkout" && !func_is_cart_empty($cart) && ($cart["sub_total"]<$config["General"]["minimal_order_amount"])) {
	func_header_location("error_message.php?error_min_order");
}

if($mode=="checkout" && $login=="" && !func_is_cart_empty($cart)) {
#
# Anonimous checkout
#
	$smarty->assign("main","anonymous_checkout");
	$smarty->assign("anonymous","Y");
	unset($userinfo);

} elseif($mode=="checkout" && $paymentid!="" && !func_is_cart_empty($cart)) {
#
# Check if paymentid isn't faked
#
	$membership = $user_account["membership"];
	$is_valid_paymentid = array_pop(func_query_first("SELECT COUNT(*) FROM $sql_tbl[payment_methods] WHERE paymentid='$paymentid' AND active='Y' AND (membership='' OR  membership='$membership') "));
	if (!$is_valid_paymentid)
		func_header_location("cart.php?mode=checkout");
#
# Generate uniq orderid which will identify order session
#
	$order_secureid = md5(uniqid(rand()));
#
# Show payment details checkout page
#
	$payment_data = func_query_first("select * from $sql_tbl[payment_methods] where paymentid='$paymentid'");
#
# Generate payment script URL depending on HTTP/HTTPS settings
#
	$payment_data["payment_script_url"] = ($payment_data["protocol"]=="https"?$https_location:$http_location)."/payment/".$payment_data["payment_script"];
		
	$smarty->assign("payment_data",$payment_data);
	$smarty->assign("main","checkout");

} elseif($mode=="checkout" && !func_is_cart_empty($cart)) {
#
# Show checkout page with payment options only methods availiable to current 
#  membership level are displayed
#
	$membership = $user_account["membership"];
	$payment_methods=func_query("select * from $sql_tbl[payment_methods] where active='Y' AND (membership='' OR  membership='$membership') order by orderby");
	$smarty->assign("payment_methods",$payment_methods);
	$smarty->assign("main","checkout");

}
elseif ($mode=="order_message") {
	$smarty->assign("main","order_message");
}
elseif ($mode=="auth") {
	$smarty->assign("main","checkout");
}

require "../include/categories.php";

$giftcerts=$cart["giftcerts"];
#
# In this mode cart.php show info about existing order (order_message)
#
if($orderids) {
	$orders = array ();
	$_orderids = split (",",$orderids);

	foreach ($_orderids as $orderid) {
		$order_data = func_order_data($orderid);
#
# Security check if current customer is not order's owner
#
		if ($order_data["order"]["login"]!=$login) unset($order_data);

		$orders[] = $order_data;
	}

	$smarty->assign ("orders", $orders);
}

include "./minicart.php";

$smarty->assign("userinfo",$userinfo);
$smarty->assign("products",$products);
$smarty->assign("giftcerts",$giftcerts);

$smarty->assign("order",$order);

if ($login)
    db_query("UPDATE $sql_tbl[customers] SET cart='".addslashes(serialize($cart))."' WHERE login='$login'");
	
x_session_save();

$smarty->display("customer/home.tpl");
?>
