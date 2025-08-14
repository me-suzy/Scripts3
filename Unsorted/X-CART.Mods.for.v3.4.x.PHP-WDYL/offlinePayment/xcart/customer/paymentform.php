<?
/* first stab at smarty to create a payment form to be printed etc
*/

require "../smarty.php";
require "../config.php";
@include "./https.php";
require "./auth.php";

//include "./nocookie_warning.php";

session_register("cart");
session_register("intershipper_rates");
session_register("intershipper_recalc");

$intershipper_recalc = "Y";

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
		header("Location: cart.php?$QUERY_STRING");
		exit();
    }

	if($active_modules["Subscriptions"]) {
    	include "../modules/Subscriptions/subscription.php";
	}
	
	if (!$login && $config["General"]["apply_default_country"]=="Y")
		$userinfo["s_country"] = $config["General"]["default_country"];

#
# Check if only downloadable products placed in cart
#
	$need_shipping = false;
	if (is_array($products))
		foreach ($products as $product) 
			if (empty($product["distribution"])) $need_shipping = true;

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
				foreach ($result as $res) {
					$shipping [] = $res;
				}
			}
				function array_cmp($a, $b) {
					if ($a[orderby] == $b[orderby]) return 0;
					return ($a[orderby] > $b[orderby]) ? 1: -1;
				}
				usort($shipping, "array_cmp");
		}
	}
	if (count($shipping)==0) unset($shipping);
	$smarty->assign("shipping", $shipping);

	}
}

#
# $order_secureid (for security reasons)
#
session_register("order_secureid");

if (!empty($login)) $userinfo = func_userinfo($login, $current_area);

if (!$login && $config["General"]["apply_default_country"]=="Y")
		$userinfo["s_country"] = $config["General"]["default_country"];
		



# Assign Smarty variables and show template
#
$smarty->assign("register_script_name","paymentform.php");
$smarty->assign("cart",$cart);
$smarty->assign("payment_data",$payment_data);
$smarty->assign("userinfo",$userinfo);
$smarty->assign("products",$products);
$smarty->assign("giftcerts",$giftcerts);
$smarty->assign("order",$order);
$smarty->assign ("orders", $orders);
$smarty->display("customer/main/payment_form.tpl");
?>
