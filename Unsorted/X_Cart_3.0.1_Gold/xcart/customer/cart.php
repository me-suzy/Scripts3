<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2002 RRF.ru development. All rights reserved.            |
+-----------------------------------------------------------------------------+
| The RRF.RU DEVELOPMENT forbids, under any circumstances, the unauthorized   |
| reproduction of software or use of illegally obtained software. Making      |
| illegal copies of software is prohibited. Individuals who violate copyright |
| law and software licensing agreements may be subject to criminal or civil   |
| action by the owner of the copyright.                                       |
|                                                                             |
| 1. It is illegal to copy a software, and install that single program for    |
| simultaneous use on multiple machines.                                      |
|                                                                             |
| 2. Unauthorized copies of software may not be used in any way. This applies |
| even though you yourself may not have made the illegal copy.                |
|                                                                             |
| 3. Purchase of the appropriate number of copies of a software is necessary  |
| for maintaining legal status.                                               |
|                                                                             |
| DISCLAIMER                                                                  |
|                                                                             |
| THIS SOFTWARE IS PROVIDED BY THE RRF.RU DEVELOPMENT TEAM ``AS IS'' AND ANY  |
| EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED |
| WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE      |
| DISCLAIMED.  IN NO EVENT SHALL THE RRF.RU DEVELOPMENT TEAM OR ITS           |
| CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,       |
| EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,         |
| PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; |
| OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,    |
| WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR     |
| OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF      |
| ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                  |
|                                                                             |
| The Initial Developer of the Original Code is RRF.ru development.           |
| Portions created by RRF.ru development are Copyright (C) 2001-2002          |
| RRF.ru development. All Rights Reserved.                                    |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: cart.php,v 1.108 2002/06/03 13:07:37 lucky Exp $
#
# This script implements shopping cart facility
#

require "../smarty.php";
require "../config.php";
@include "./https.php";
require "./auth.php";

include "./nocookie_warning.php";

session_register("cart");

#
# $order_secureid (for security reasons)
#
session_register("order_secureid");

#
# Register member if not registerred yet
# (not a newbie - do not show help messages)
#
$smarty->assign("register_script_name","cart.php");

require "../include/register.php";
if ($auto_login) {
    header("Location: cart.php?mode=checkout&registered=");
    exit;
}


if (!empty($login)) $userinfo = func_userinfo($login, $current_area);

#
# Add to cart
#
if($mode=="add" && $productid!="") {

	$added_product=func_select_product($productid, $user_account['membership']);

#
# Add to cart amount of items that is not much than in stock
#
	$amount = array_pop(func_query_first("select least('$amount',avail) from products where productid='$productid'"));

	if ($productid && $amount) { 

		if ($amount < $added_product[min_amount]) {
			header ("Location: error_message.php?access_denied");
			exit;
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
					$cart["products"][$k]["amount"] += $amount;
				}
			}
		}
#
# price value is defined by customer if administrator set it to '0.00'
#
		if (!$found)
			$cart["products"][]=array("productid"=>$productid,"amount"=>$amount, "options"=>$options, "price"=>$price);

	}
	

}



if (!func_is_cart_empty($cart)) {

	$products = func_products_in_cart($cart, $userinfo["membership"]);
#
# Fetch real shipping values
#
	if($realtime_shipping=="Y" && !empty($cart["products"]))
		$intershipper_rates = func_intershipper(func_weight_shipping_products($products));

	if (!empty($cart["products"])) {
#
# We must know shipping methods with destination $userinfo["s_country"]
#
		$destination_condition = " and destination=".($userinfo["s_country"]!=$location_country?"'I'":"'L'");

		if (!$login || $realtime_shipping!="Y")
			$shipping = func_query("select * from shipping where active='Y' $destination_condition order by orderby");
		else {
			$shipping = array ();
			if($intershipper_rates)
				foreach($intershipper_rates as $intershipper_rate) {
					$result = func_query_first("select * from shipping where subcode='$intershipper_rate[methodid]' and active='Y' $destination_condition order by orderby");
					if ($result)
						$shipping[] = $result;
			}
			$result = func_query ("SELECT * FROM shipping WHERE code='' AND active='Y' $destination_condition ORDER BY orderby");
			if ($result) {
				foreach ($result as $res) {
					$shipping [] = $res;
				}
			}
		}
	}
	if (count($shipping)==0) unset($shipping);
	$smarty->assign("shipping", $shipping);
}

 

if ($mode=="delete" && $productindex!="") {
#
# Delete product from cart
#
	if($active_modules["Advanced_Statistics"])
	    @include "../modules/Advanced_Statistics/prod_del.php";
	array_splice($cart["products"],$productindex,1);
	header("Location: cart.php");
	exit;
} elseif($shippingid!="") {
	$cart["shippingid"]=$shippingid;
}

#
# If current shipping is empty set it to default (first in shipping array)
#
	$shipping_matched = false;

	if($shipping)
		foreach($shipping as $shipping_method)
			if($cart["shippingid"]==$shipping_method["shippingid"]) $shipping_matched=true;

	if(!$shipping_matched)
		$cart["shippingid"]=$shipping[0]["shippingid"];

	$cart["delivery"]=array_pop(func_query_first("select shipping from shipping where shippingid='$cart[shippingid]'"));

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
	if($redirect_to_cart=="Y" || $cat=="")
		header("Location: cart.php");
	else
		header("Location: home.php?cat=$cat&page=$page");

	exit;
}
#
# SHOPPING CART FEATURE
#
if (($mode=="checkout") and (!empty($cart["products"])) and (!$shipping) and ($login)) {
	header ("Location: error_message.php?error_no_shipping");
	exit;
}

if ($mode=="checkout" && !func_is_cart_empty($cart) && ($cart["sub_total"]<$minimal_order_amount)) {
	header ("Location: error_message.php?error_min_order");
	exit;
}

if($mode=="checkout" && $login=="" && !func_is_cart_empty($cart)) {
#
# Anonimous checkout
#
	$smarty->assign("main","anonymous_checkout");
	$smarty->assign("anonymous","Y");

} elseif($mode=="checkout" && $paymentid!="" && !func_is_cart_empty($cart)) {
#
# Generate uniq orderid which will identify order session
#
	$order_secureid = md5(uniqid(rand()));
#
# Show payment details checkout page
#
	$payment_data = func_query_first("select * from payment_methods where paymentid='$paymentid'");
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
	$payment_methods=func_query("select * from payment_methods where active='Y'AND(membership='' OR  membership='$membership') order by orderby");
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

$smarty->display("customer/home.tpl");
?>
