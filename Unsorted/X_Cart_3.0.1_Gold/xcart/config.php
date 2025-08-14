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
# $Id: config.php,v 1.197 2002/06/05 08:16:14 verbic Exp $
#
# Global definitions & common functions
#

#
# SQL database details
#
$sql_host ="%SQL_HOST%";
$sql_user ="%SQL_USER%";
$sql_db ="%SQL_DB%";
$sql_password ="%SQL_PASSWORD%";

#
# X-Cart HTTP & HTTPS host
# and web directory where X-Cart installed
#
$xcart_http_host =$HTTP_HOST;
$xcart_https_host =$HTTP_HOST;
$xcart_web_dir ="/xcart";

@include "config.local";

# Store Credit Cards
# if set to true, X-Cart will store Credit Card numbers in database
#
$store_cc = false;

#
# Which image to show when product has no photo.
#
$default_image = "default_image.gif";
$default_icon = "default_icon.gif";
$default_banner = "default_banner.gif";

#
# Single Store mode (Pro package only)
#
# If you own a Gold package, this value should always be equal to "true".
#
# If $single_mode is set to true the store has shipping
# rates, discounts, taxes and discounts coupons shared between all product
# providers. All product providers can edit each other's products.
# If $single_mode is set to false, each provider has his own
# rates, taxes which applied to on his products only.
#
$single_mode = true;

#
# Defined possible customer classes
#
#
# 
#
$membership_levels = array(
	array("usertype"=>"C", "membership"=>"Premium"),
	array("usertype"=>"C", "membership"=>"Wholesale"),
	array("usertype"=>"A", "membership"=>"Fulfillment staff")
);

$smarty->assign("membership_levels",$membership_levels);

#
#
# DO NOT CHANGE ANYTHING BELOW THIS LINE UNLESS
# YOU REALLY KNOW WHAT ARE YOU DOING
#
#

#
# Error reporting level:
# default: E_ALL ^ E_NOTICE
#
error_reporting (E_ALL ^ E_NOTICE);
set_magic_quotes_runtime(0);

#
# Demo mode - protects several pages from writing
#
$admin_safe_mode = false;

#
# HTTP & HTTPS locations
#
$http_location = "http://$xcart_http_host".$xcart_web_dir;
$https_location = "https://$xcart_https_host".$xcart_web_dir;

$smarty->assign("http_location",$http_location);
$mail_smarty->assign("http_location",$http_location);
$smarty->assign("https_location",$https_location);
$mail_smarty->assign("https_location",$https_location);

#
# Files directory
#
$files_dir_name = "../files";
$files_http_location = $http_location."/files";

#
# Templates repository
# where original templates are located for "restore" facility
#
$templates_repository = "../skin1_original";

#
# Store sessions data in database
#
$store_sessions_in_mysql = false;

#
# Including file for storing of PHP-sessions in MySQL database
#
if ($store_sessions_in_mysql)
    if (!@include_once("../include/mysql_sessions.php"))
        @include_once("./include/mysql_sessons.php");

#
# Skin configuration file.
# Configuration files are located under ./configs directory
#
$smarty->assign("skin_config","skin1.conf");
$mail_smarty->assign("skin_config","skin1.conf");

#
# Defined Titles
#
$name_titles = array("Mr.","Mrs.");
$smarty->assign("name_titles",$name_titles);

#
# Defined Card types
#
$card_types = array(
	array("code"=>"VISA","type"=>"Visa"),
	array("code"=>"MC","type"=>"Mastercard"),
	array("code"=>"AMEX","type"=>"American Express")
);

$smarty->assign("card_types",$card_types);

#
# Store location
#
$smarty->assign("location_country",$location_country);
$mail_smarty->assign("location_country",$location_country);

#
# Anonimous user name
#
$anonymous_username_prefix="anonymous";

#
# Anonymous user password
#
$anonymous_password="42a51f1538a39636879414b681dd7df6";

#
# SALT & CODE for user password & credit card encryption
#
$CRYPT_SALT = 85; # any number ranging 1-255
$START_CHAR_CODE = 100; # 'd' letter

#
# Connect to database
#

db_connect($sql_host, $sql_user, $sql_password);
db_select_db($sql_db) || die("Could not connect to SQL db");


#
# Read config variables from Database
# This variables are used iside php scripts, not in smarty templates
#
$c_result = db_query("select name, value from config");
while ($row = db_fetch_row($c_result)) {
        ${$row[0]} = $row[1];
}
db_free_result($c_result);

#
# Debug console handling
#
if($enable_debug_console=="Y")
	$smarty->debugging=true;

#
# Read Modules and put in into $active_modules
#
$all_active_modules=func_query("select * from modules where active='Y'");
foreach($all_active_modules as $active_module)
	$active_modules[$active_module["module_name"]]=true;

$active_modules["Simple_Mode"]=true;

unset($all_active_modules);

$smarty->assign("active_modules",$active_modules);
$mail_smarty->assign("active_modules",$active_modules);

#
# Database abstract layer functions
#
function db_connect($sql_host, $sql_user, $sql_password) {
	return mysql_connect($sql_host, $sql_user, $sql_password);
}

function db_select_db($sql_db) {
	return mysql_select_db($sql_db) || die("Could not connect to SQL db");
}

function db_query($query) {
        return mysql_query($query);
}

function db_fetch_row($result) {
	return mysql_fetch_row($result);
}

function db_fetch_array($result) {
    return mysql_fetch_array($result);
}

function db_free_result($result) {
        @mysql_free_result($result);
}

function db_num_rows($result) {
       return mysql_num_rows($result);
}

function db_insert_id() {
       return mysql_insert_id();
}

#
# Execute mysql query adn store result into associative array with
# column names as keys...
#
function func_query($query) {

	#$result=array();

	$p_result = db_query($query);
    while($arr = db_fetch_array($p_result))
		$result[]=$arr;
	db_free_result($p_result);

	return $result;

}

#
# Execute mysql query and store result into associative array with
# column names as keys and then return first element of this array
# If array is empty return array().
#
function func_query_first($query) {
   
	$arr = func_query($query);
	if($arr) return array_pop(array_reverse($arr)); 
		else return array();

}

#
# Get image size abstract function
#
function get_image_size($filename) {

	$width=0;
	$height=0;
	
	return array(filesize($filename),$width,$height);
}
#
# Send mail abstract function
# $from - from/reply-to address
#
function func_send_mail($to, $subject_template, $body_template, $from, $to_admin, $crypted=false) {
	global $mail_smarty;
	global $enable_pgp, $html_mail;
	global $admin_language;
	global $customer_language;

	if ($to_admin or !$customer_language)
		$mail_smarty->assign ("lng", $admin_language);
	else
		$mail_smarty->assign ("lng", $customer_language);

    $mail_message = $mail_smarty->fetch("$body_template");
    $mail_subject = chop($mail_smarty->fetch("$subject_template"));

	if (($enable_pgp=="Y") and ($crypted)) {
		$mail_message = func_pgp_encrypt ($mail_message);
	}

	mail($to,$mail_subject,$mail_message,($html_mail=="Y"?"Content-Type: text/html\n":"")."From: $from\nReply-to: $from\nX-Mailer: PHP/".phpversion());
}

function func_send_simple_mail($to, $subject, $body, $from) { 
    mail($to,$subject,$body,($html_mail=="Y"?"Content-Type: text/html\n":"")."From: $from\nReply-to: $from\nX-Mailer: PHP/".phpversion());
}

#
# Simple crypt function. Returns an encrypted version of argument.
# Does not matter what type of info you encrypt, the function will return
# a string of ASCII chars representing the encrypted version of argument.
# Note: text_crypt returns string, which length is 2 time larger
#
function text_crypt_symbol($c) {
# $c is ASCII code of symbol. returns 2-letter text-encoded version of symbol

        global $START_CHAR_CODE;

        return chr($START_CHAR_CODE + ($c & 240) / 16).chr($START_CHAR_CODE + ($c & 15));
}

function text_crypt($s) {
    global $START_CHAR_CODE, $CRYPT_SALT;

    if ($s == "")
        return $s;
    $enc = rand(1,255); # generate random salt.
    $result = text_crypt_symbol($enc); # include salt in the result;
    $enc ^= $CRYPT_SALT;
    for ($i = 0; $i < strlen($s); $i++) {
        $r = ord(substr($s, $i, 1)) ^ $enc++;
        if ($enc > 255)
            $enc = 0;
        $result .= text_crypt_symbol($r);
    }
    return $result;
}

function text_decrypt_symbol($s, $i) {
# $s is a text-encoded string, $i is index of 2-char code. function returns number in range 0-255

        global $START_CHAR_CODE;

        return (ord(substr($s, $i, 1)) - $START_CHAR_CODE)*16 + ord(substr($s, $i+1, 1)) - $START_CHAR_CODE;
}

function text_decrypt($s) {
    global $START_CHAR_CODE, $CRYPT_SALT;

    if ($s == "")
        return $s;
    $enc = $CRYPT_SALT ^ text_decrypt_symbol($s, 0);
    for ($i = 2; $i < strlen($s); $i+=2) { # $i=2 to skip salt
        $result .= chr(text_decrypt_symbol($s, $i) ^ $enc++);
        if ($enc > 255)
            $enc = 0;
    }
    return $result;
}

#
# Recursively deletes category with all its contents
#

function func_rm_dir_files ($path) {
        $dir = opendir ($path);

        while ($file = readdir ($dir)) {
                if (($file == ".") or ($file == ".."))
                        continue;
                if (filetype ("$path/$file") == "dir") {
                        func_rm_dir_files ("$path/$file");
                        rmdir ("$path/$file");
                } else {
                        unlink ("$path/$file");
                }
        }
}

function func_rm_dir ($path) {
        func_rm_dir_files ($path);
        rmdir ($path);
}

#
# Delete product from products table + all associated information
# $productid - product's id
#
function func_delete_product($productid) {

	db_query("delete from pricing where productid='$productid'");
	db_query("delete from product_links where productid1='$productid' or productid2='$productid'");
	db_query("delete from featured_products where productid='$productid'");
	db_query("delete from products where productid='$productid'");
	db_query("delete from delivery where productid='$productid'");
	db_query("delete from images where productid='$productid'");
	db_query("delete from thumbnails where productid='$productid'");
	db_query("delete from product_options where productid='$productid'");
	db_query("DELETE FROM product_options_ex WHERE productid='$productid'");
	db_query("DELETE FROM product_votes WHERE productid='$productid'");
	db_query("DELETE FROM product_reviews WHERE productid='$productid'");
	db_query("DELETE FROM products_lng WHERE productid='$productid'");
}

#
# Delete profile from customers table + all associated information
#
function func_delete_profile($user,$usertype) {

	global $files_dir_name, $single_mode;

	if($usertype=="P" && !$single_mode) {
# If user is provider delete some associated info to keep DB integrity
# Delete products
#
		$products = func_query("SELECT productid FROM products WHERE provider='$user'");
		if (!empty($products))
			foreach($products as $product) 
				func_delete_product($product["productid"]);
#
# Delete Shipping, Discounts, Coupons, States/Tax, Countries/Tax
#
		db_query("delete from shipping_rates where provider='$user'");
		db_query("delete from discounts where provider='$user'");
		db_query("delete from discount_coupons where provider='$user'");
		db_query("delete from state_tax where provider='$user'");
		db_query("delete from country_tax where provider='$user'");
#
# Delete provider's file dir
#
		@func_rm_dir ("$files_dir_name/$user");
	}

#
# If it is partner, then remove all his information
#
	if ($usertype == "B") {
		db_query ("DELETE FROM partner_clicks WHERE login='$user'");
		db_query ("DELETE FROM partner_commitions WHERE login='$user'");
		db_query ("DELETE FROM partner_payment WHERE login='$user'");
		db_query ("DELETE FROM partner_views WHERE login='$user'");
	}

	db_query("DELETE FROM  customers WHERE login='$user' AND usertype='$usertype'");
}

#
# Get information associated with user
#
function func_userinfo($user,$usertype) {

    $userinfo = func_query_first("SELECT customers.*, countries.country FROM customers, countries WHERE customers.login='$user' AND customers.usertype='$usertype' AND countries.code=customers.b_country");

    $userinfo["passwd1"] = text_decrypt($userinfo["password"]);
    $userinfo["passwd2"] = text_decrypt($userinfo["password"]);
    $userinfo["password"] = text_decrypt($userinfo["password"]);
    $userinfo["card_number"] = text_decrypt($userinfo["card_number"]);

	$email = $userinfo["email"];

	if(func_query_first("SELECT email FROM maillist WHERE email='$email'")) $userinfo["newsletter"]="Y";

	return $userinfo;
}

#
# Convert price to "XXXXX.XX" format
#
function price_format($price) {
    if (strstr($price,".")) {
        $price.="00";
        $price = ereg_replace("(\...).*$","\\1",$price);
    } else $price.=".00";
        
    return $price;
}   

function func_get_products_providers ($products) {
	$products_providers = array ();
	foreach ($products as $product) {
		if (!in_array ($product["provider"], $products_providers)) {
			$products_providers [] = $product["provider"];
		}
	}
	
	return $products_providers;
}

function func_get_products_by_provider ($products, $provider) {
	global $single_mode;

	$result = array ();

	if ($single_mode) {
		$result = $products;
	} else {
		foreach ($products as $product) {
			if ($product["provider"] == $provider)
				$result[] = $product;
		}
	}

	return $result;
}

#
# This function Get shipping rates from Intershipper service
#
function func_intershipper($weight) {

	global $login, $login_type;
	global $location_country, $location_zipcode, $location_state, $location_city;
	global $intershipper_username, $intershipper_password, $intershipper_weightunits, $intershipper_dimunits, $intershipper_timeout;

	$carriers = func_query("select distinct(code) from shipping where code!='' and active='Y'");

	if(!$login || !$carriers) return(array());

	foreach($carriers as $carrier)
		if ($carrier_string) $carrier_string.=",".$carrier["code"];
		else $carrier_string=$carrier["code"];


	require("../cls_intershipper.php");

	$userinfo = func_userinfo($login, $login_type);

	$IShip = new InterShipper();

	$IShip->setVar("email", $intershipper_username);
	$IShip->setVar("password", $intershipper_password);
	$IShip->setVar("Oaddress", $location_address);
	$IShip->setVar("Ocity", $location_city);
	$IShip->setVar("Ostate", $location_state);
	$IShip->setVar("Ocountry", $location_country);
	$IShip->setVar("Opostalcode", $location_zipcode);
	$IShip->setVar("Daddress", $userinfo["s_address"]);
	$IShip->setVar("Dcity", $userinfo["s_city"]);
	$IShip->setVar("Dstate", $userinfo["s_state"]);
	$IShip->setVar("Dcountry", $userinfo["s_country"]);
	$IShip->setVar("Dpostalcode", $userinfo["s_zipcode"]);
	$IShip->setVar("carriers", $carrier_string);
	$IShip->setVar("weight", $weight);
	$IShip->setVar("weightunits", $intershipper_weightunits);
	$IShip->setVar("dimensionunits", $intershipper_dimunits);
	$IShip->setVar("length", "");
	$IShip->setVar("width", "");
	$IShip->setVar("height", "");
	$IShip->setVar("shipmethod", "");
	$IShip->setVar("shipdate", "");

#
# Run Intershipper !!!
#
	if($IShip->Quote($intershipper_timeout))
		return($IShip->getQuote());
	else
		return(array());

}
#
# This function do real shipping calcs
#
function func_real_shipping($delivery) {

	global $intershipper_rates;

	$shipping_codes = func_query_first("select code, subcode from shipping where shippingid='$delivery'");

	if ($intershipper_rates) {
		foreach($intershipper_rates as $rate)
			if ($rate["methodid"]==$shipping_codes["subcode"])
				return $rate["rate"];
	} else
		return "0.00";

}
#
# This function calculates costs of contents of shopping cart
#
function func_calculate($cart, $products, $login, $login_type) {
	global $single_mode;

	if ($single_mode) {
		$return = array ();
		$result = func_calculate_single ($cart, $products, $login, $login_type);
		$return = $result;
		$return ["orders"] = array ();
		$return ["orders"][0] = $result;
		$return ["orders"][0]["provider"] = $products[0]["provider"];
	} else {
		$products_providers = func_get_products_providers ($products);

		$return = array ();
		$return["orders"] = array ();
		$key = 0;
		foreach ($products_providers as $provider_for) {
			$_products = func_get_products_by_provider ($products, $provider_for);
			$result = func_calculate_single ($cart, $_products, $login, $login_type);
			$return ["total_cost"] += $result ["total_cost"];
			$return ["shipping_cost"] += $result ["shipping_cost"];
			$return ["tax_cost"] += $result ["tax_cost"];
			$return ["discount"] += $result ["discount"];
			if ($result["coupon"]) {
				$return ["coupon"] = $result ["coupon"];
			}
			$return ["coupon_discount"] += $result ["coupon_discount"];
			$return ["sub_total"] += $result ["sub_total"];

			$return ["orders"][$key] = $result;
			$return ["orders"][$key]["provider"] = $provider_for;
			
			$key ++;
		}
		if ($cart["giftcerts"]) {
			$_products = array ();
			$result = func_calculate_single ($cart, $_products, $login, $login_type);
			$return ["total_cost"] += $result ["total_cost"];
			$return ["shipping_cost"] += $result ["shipping_cost"];
			$return ["tax_cost"] += $result ["tax_cost"];
			$return ["discount"] += $result ["discount"];
			$return ["sub_total"] += $result ["sub_total"];
			$return ["coupon_discount"] += $result ["coupon_discount"];

			$return ["orders"][$key] = $result;
			$return ["orders"][$key]["provider"] = ""; #$provider_for;
			$key++;
		}
	}

	return $return;
}

#
# Calculate total products price
# 1) calculate total sum,
# 2) a) total = total - discount
#    b) total = total - coupon_discount
# 3) calculate shipping
# 4) calculate tax
# 5) total_cost = total + shipping + tax
# 6) total_cost = total_cost + giftcerts_cost
#
function func_calculate_single($cart, $products, $login, $login_type) {
	global $single_mode, $location_country;
	global $active_modules, $realtime_shipping;

	if (!$products)
		$provider_for = "";
	else
		$provider_for = $products[0]["provider"];

	$delivery = $cart["shippingid"];
	$giftcerts = $cart["giftcerts"];
	$discount_coupon = $cart["discount_coupon"];
	
	$provider_condition=($single_mode?"":"and provider='$provider_for'");

	if(!empty($login)) $customer_info = func_userinfo($login,$login_type);

	$total=0;
	$sub_total=0;
	$total_weight=0;
	$total_items=0;
	$avail_discount_total=0;
	$total_weight_shipping = 0;
	$total_items_shipping = 0;
	$total_shipping = 0;

	foreach($products as $product) {
		if ($product["discount_avail"]=='Y')
			$avail_discount_total+=$product["price"]*$product["amount"];
		$total+=$product["price"]*$product["amount"];
		$total_weight+=$product["weight"]*$product["amount"];
		if ($product["distribution"]=="") $total_items+=$product["amount"];
		if ($product["free_shipping"] != "Y") {
			$total_shipping += $product["price"]*$product["amount"];
			$total_weight_shipping += $product["weight"]*$product["amount"];
			if ($product["distribution"]=="")
				$total_items_shipping += $product["amount"];
		}
	}

#
# Calculate Gift Certificates cost
#
	$giftcerts_cost=0;

	if ((($single_mode) or (!$provider_for)) and ($giftcerts))
		foreach($giftcerts as $giftcert)
			$giftcerts_cost+=$giftcert["amount"];

	$sub_total = $total+$giftcerts_cost;
#
# Deduct discount
#

	$discount=0;
    $discount_info = func_query_first("select * from discounts where minprice<='$avail_discount_total' $provider_condition AND ((membership='$customer_info[membership]') OR (membership='')) order by minprice desc");
	if ($discount_info["discount_type"]=="absolute")
		$discount = $discount_info["discount"];
	elseif ($discount_info["discount_type"]=="percent")
		$discount = $total*$discount_info["discount"]/100;

		$total = $total-$discount;
		$total_shipping -= $discount;
		if ($total_shipping < 0)
			$total_shipping = 0;
#
# Deduct discount by discount coupon
#
	$coupon_discount=0;
	$coupon_total = 0;
	$coupon_amount = 0;

	$discount_coupon_data = func_query_first("select * from discount_coupons where coupon='$discount_coupon' $provider_condition");

	if (($discount_coupon_data["productid"]>0) and (($discount_coupon_data["coupon_type"]=="absolute") or ($discount_coupon_data["coupon_type"]=="percent"))) {
		foreach($products as $product) {
			if ($product["productid"] == $discount_coupon_data["productid"]) {
				$coupon_total += $product["price"]*$product["amount"];
				$coupon_amount += $product["amount"];
			}
		}
		if ($discount_coupon_data["coupon_type"]=="absolute") {
			$coupon_discount = $coupon_amount*$discount_coupon_data["discount"];
		} else {
			$coupon_discount = $coupon_total*$discount_coupon_data["discount"]/100;
		}
	} elseif (($discount_coupon_data["categoryid"]>0) and (($discount_coupon_data["coupon_type"]=="absolute") or ($discount_coupon_data["coupon_type"]=="percent"))) {
		foreach ($products as $product) {
			if ($product["categoryid"] == $discount_coupon_data["categoryid"]) {
				$coupon_total += $product["price"]*$product["amount"];
				$coupon_amount += $product["amount"];
			}
		}
		if ($discount_coupon_data["coupon_type"]=="absolute") {
			$coupon_discount = $coupon_amount*$discount_coupon_data["discount"];
		} else {
			$coupon_discount = $coupon_total*$discount_coupon_data["discount"]/100;
		}
	} else {
		if ($discount_coupon_data["coupon_type"]=="absolute")
       		$coupon_discount = $discount_coupon_data["discount"];
    	elseif ($discount_coupon_data["coupon_type"]=="percent")
        	$coupon_discount = $total*$discount_coupon_data["discount"]/100;
	}

	if ((!$single_mode) and (($discount_coupon_data["provider"] != $provider_for) or (!$products)))
		$discount_coupon = "";

	$total = $total-$coupon_discount;
	$total_shipping -= $coupon_discount;
	if ($total_shipping<0)
		$total_shipping = 0;

#
# Calculate shipping cost
#
# Shipping also calculated based on zones
#
# Advanced shipping formula:
# AMOUNT = amount of ordered products
# SUM = total sum of order
# TOTAL_WEIGHT = total weight of products
#
# SHIPPING = rate+TOTAL_WEIGHT*weight_rate+AMOUNT*item_rate+SUM*rate_p/100
#

	$shipping_cost = 0;
	$shipping_freight = 0;

#
# Zones code
#
	if($login) {
		$customer_zone = array_pop(func_query_first("select zoneid from country_zones where code='$customer_info[s_country]'"));
		if ($customer_info["s_country"]==$location_country)
			$customer_zone = array_pop(func_query_first("select zoneid from state_zones where code='$customer_info[s_state]'"));
	}
	if(!$customer_zone) $customer_zone=0;
#
# if $products is empty then shipping and tax are alwayz zero
#
	if ($total_items_shipping) {
		$shipping = func_query("select * from shipping_rates where shippingid='$delivery' $provider_condition and zoneid='$customer_zone' and maxtotal>=$total_shipping and maxweight>=$total_weight_shipping order by maxtotal, maxweight");

		if($shipping) 
			$shipping_cost = $shipping[0]["rate"]+$total_weight_shipping*$shipping[0]["weight_rate"]+$total_items_shipping*$shipping[0]["item_rate"]+$total_shipping*$shipping[0]["rate_p"]/100;
	}
	
	foreach($products as $product)
		$shipping_freight += $product["shipping_freight"]*$product["amount"];
	
	$result = func_query_first ("SELECT * FROM shipping WHERE shippingid='$delivery' AND code!=''");
	if($realtime_shipping=="Y" and $result)
		$shipping_cost = func_real_shipping($delivery);

	if ($discount_coupon_data["coupon_type"]=='free_ship') {
		if (($single_mode) or ($provider_for == $discount_coupon_data["provider"])) {
			$coupon_discount = $shipping_cost;
			$total -= $coupon_discount;
		}
	}

	$shipping_cost += $shipping_freight;

#
# Calculate tax cost
# SUM = total sum of order
#
# TAX = country_tax_flat + SUM*country_tax_percent/100 + state_tax_flat + SUM*state_tax_percent/100;
#
	$tax_cost = 0;

	if (!empty($login) and ($active_modules["Tax_Zones"])) {
		include "../modules/Tax_Zones/calc_tax.php";
	}
	
	if (!empty($login) && $total_items) {
		$country_tax = array();
		$state_tax = array();

		$country_tax = func_query_first("select country_tax.* from country_tax, countries where country_tax.code='$customer_info[s_country]' $provider_condition and country_tax.code=countries.code");

		if ($customer_info["s_country"]==$location_country) {

			if ($customer_info["s_state"])
				$state_tax = func_query_first("select state_tax.* from states, state_tax where states.code=state_tax.code $provider_condition and state_tax.code='".$customer_info["s_state"]."'");

			if ($customer_info["s_zipcode"])
				$zipcode_tax = func_query_first("select sum(tax_percent) as tax_percent, sum(tax_flat) as tax_flat from zipcode_tax where '$customer_info[s_zipcode]' like zipcode_mask");
		}

		$tax_cost += $country_tax["tax_flat"]+$total*$country_tax["tax_percent"]/100+$state_tax["tax_flat"]+$total*$state_tax["tax_percent"]/100;
		$tax_cost += $zipcode_tax["tax_flat"]+$total*$zipcode_tax["tax_percent"]/100;
	}

#
# Calculate total
#
	$total+=$shipping_cost+$tax_cost+$giftcerts_cost;
	
	return array("total_cost"=>price_format($total), "shipping_cost"=>price_format($shipping_cost), "tax_cost"=>price_format($tax_cost), "discount"=>price_format($discount), "coupon"=>$discount_coupon, "coupon_discount"=>price_format($coupon_discount), "sub_total"=>price_format($sub_total));
}

#
# Search for products in products database
#
function func_search_products($query, $membership) {
	global $current_area;
	global $store_language;
	
	if ($current_area == "C") {
		$membership_condition = " AND (categories.membership='$membership' OR categories.membership='') ";
	} else {
		$membership_condition = "";
	}
	
	$search_query = "select products.*, categories.category, min(pricing.price) as price from products, pricing, categories where pricing.productid=products.productid and pricing.quantity=1 and products.categoryid=categories.categoryid $membership_condition and (pricing.membership='$membership' or pricing.membership='') and ".$query;

	$result = func_query ($search_query);

	if ($result and $current_area=="C") {
		foreach ($result as $key=>$value) {
			$int_res = func_query_first ("SELECT * FROM products_lng WHERE code='$store_language' AND productid='$value[productid]'");
			if ($int_res[descr])
				$result[$key][descr] = $int_res[descr];
			if ($int_res[full_descr])
				$result[$key][full_descr] = $int_res[full_descr];
		}
	}

	return $result;
}

#
# Delete category recursively and all subcategories and products
#
function func_delete_category($cat) {

	$cat_name = array_pop(func_query_first("select category from categories where categoryid='$cat'"));
	$cat_name = addslashes($cat_name);
#
# Delete products from subcategories
#
	$prods = func_query("select productid from products, categories where (categories.category='$cat_name' or categories.category like '$cat_name/%') and products.categoryid=categories.categoryid");
	if($prods)
		while(list($key,$prod)=each($prods)) 
			func_delete_product($prod["productid"]);
#
# Delete subcategories
#
	$subcats = func_query("select categoryid from categories where category like '$cat_name/%' or category='$cat_name'");

	while(list($key,$subcat)=each($subcats)) {
		$cat_id=$subcat["categoryid"];
		db_query("delete from categories where categoryid='$cat_id'");
	}
#
# Delete associated data
#
		db_query("delete from icons where categoryid='$cat'");
		db_query("delete from featured_products where categoryid='$cat'");
}

#
# Count products in category
#
function func_products_count($cat) {

	$cat_name = array_pop(func_query_first("select category from categories where categoryid='$cat'"));
	$cat_name = addslashes($cat_name);
#
# Select products from subcategories
#
	$prods = func_query("select count(productid) from products, categories where (categories.category='$cat_name' or categories.category like '$cat_name/%') and products.categoryid=categories.categoryid");
	if ($prods) return array_pop(array_pop($prods));
}

#
# Put all product info into $product array
#
function func_select_product($id, $membership) {
	
	global $login, $login_type, $current_area, $single_mode, $cart;
	global $store_language;

	$in_cart=0;

	if ($current_area == "C") {
		$membership_condition = " AND (categories.membership='$membership' OR categories.membership='') ";
	} else {
		$membership_condition = "";
	}

	if($current_area=="C" && $cart["products"])
		foreach($cart["products"] as $cart_item) 
			if($cart_item["productid"]==$id) $in_cart+=$cart_item["amount"];

	if(!$single_mode)
		$login_condition=(($login!="" and $login_type=="P")?"and products.provider='$login'":"");

	$product = func_query_first("select products.*, products.avail-$in_cart as avail, categories.category as category_text, min(pricing.price) as price from products, pricing, categories where products.productid='$id' ".$login_condition." and products.categoryid=categories.categoryid $membership_condition and pricing.productid=products.productid and pricing.quantity=1 and (pricing.membership = '$membership' or pricing.membership = '') group by products.productid");

#
# Error handling
#   
	if(!$product) {
    	header("Location: error_message.php?access_denied");
    	exit;
	}

	$int_res = func_query_first ("SELECT * FROM products_lng WHERE code='$store_language' AND productid='$id'");
	if ($current_area == "C") {
		if ($int_res["descr"])
			$product["descr"] = $int_res["descr"];
		if ($int_res["full_descr"])
			$product["descr"] = $int_res["full_descr"];
	}

	$product["producttitle"]="$product[product] $product[brand] $product[model] #$product[productid]";

#
# Shipping data
#
	$product["delivery"] = func_select_product_delivery($id);

	return $product;

}

#
# Get delivery options by product ID
#
function func_select_product_delivery($id) {
	return func_query("select shipping.*, count(delivery.productid) as avail from shipping left join delivery on delivery.shippingid=shipping.shippingid and delivery.productid='$id' where shipping.active='Y' group by shippingid");
}

#
# Return number of available products
#
function insert_productsonline() {
	global $user_account;

	return array_pop(array_pop(func_query("select count(products.productid) from products, categories where products.categoryid=categories.categoryid AND (categories.membership='$user_account[membership]' OR categories.membership='')")));

}

#
# Return number of available items
#
function insert_itemsonline() {
	global $user_account;

    return array_pop(array_pop(func_query("select sum(products.avail) from products, categories WHERE products.categoryid=categories.categoryid AND (categories.membership='$user_account[membership]' OR categories.membership='')")));

}

#
# Write array to file
#
function func_write_array($filename,$array) {

	if (gettype($array)=="array") $array=implode("\n",$array);

	$fp = fopen($filename,"w");
	fputs($fp, $array);
	fclose($fp);
}

#
# Spam to given maillist function
#
function func_spam($maillist, $subject, $body) {

	global $newsletter_email, $use_PHP_mailer;

	$mail_file = "newsletter/maillist_".md5(uniqid(rand()));
	$subject_file = "newsletter/subject_".md5(uniqid(rand()));
	$body_file = "newsletter/body_".md5(uniqid(rand()));

    #Use PHP mailer for sending newsletter
	if ($use_PHP_mailer == 'Y') {

        foreach($maillist as $key=>$value)  
            func_send_simple_mail($value, $subject, $body, $newsletter_email);  
    }
    else{	
	# Else use external shell script
	func_write_array($mail_file,$maillist);
	func_write_array($subject_file,$subject);
	func_write_array($body_file,$body);

	exec("( REPLYTO=$newsletter_email; ./spam.sh \"$mail_file\"  \"$subject_file\" \"$body_file\" \"$newsletter_email\") &");
	}
}
#
# Generate products array in $cart
#
function func_products_in_cart($cart, $membership) {

	global $active_modules;

    $products = array();

	if($cart["products"])
    foreach($cart["products"] as $product_data) {

		$productid = $product_data["productid"];
		$amount = $product_data["amount"];
		$options = $product_data["options"];

#
# Product options code
#
		$product_option_lines = func_query("select * from product_options where productid='$productid' order by orderby");
		$product_options = array();

		if($product_option_lines)
			foreach($product_option_lines as $product_option_line)
				$product_options[$product_option_line["optclass"]] = func_parse_options($product_option_line["options"]);

		$absolute_surcharge = 0;
		$percent_surcharge = 0;
		$this_product_options = "";

#
# Calculate option surcharges
#
		foreach($options as $option) {
			$my_option = $product_options[$option["optclass"]][$option["optionindex"]];

			if(!empty($product_options[$option["optclass"]])) {
				$this_product_options.="$option[optclass]: $my_option[option]\n";
				if($my_option["type"]=="absolute")
					$absolute_surcharge+=$my_option["surcharge"];
				elseif($my_option["type"]=="percent")
					$percent_surcharge+=$my_option["surcharge"];
			} else
				$this_product_options.="$option[optclass]: $option[optionindex]\n";

		}
		$this_product_options = chop($this_product_options);
#
# /Product options
#

        $products_array = func_query_first("select products.*, min(round(pricing.price+$absolute_surcharge+$percent_surcharge*pricing.price/100,2)) as price from products, pricing where pricing.productid=products.productid and products.productid='$productid' and products.avail>=$amount and pricing.quantity<=$amount and (pricing.membership='$membership' or pricing.membership='') group by products.productid order by pricing.quantity desc");

        if($products_array) {
#
# If priduct's price is 0 then use customer-defined price
#
			if($products_array["price"]==0)
				$products_array["price"]=price_format($product_data["price"]?$product_data["price"]:0);
			$products_array["total"]=price_format($amount*$products_array["price"]);
			$products_array["product_options"]=$this_product_options;
			$products_array["amount"]=$amount;
			$products[] = $products_array;
		}
    }

	return $products;
}

#
# Calculate total weight of all products in cart
#
function func_weight_products($products) {

	foreach($products as $product)
		$total_weight+=$product["weight"]*$product["amount"];	
	
	return $total_weight;
}

function func_weight_shipping_products ($products) {
	$total_weight = 0;

	foreach ($products as $product) {
		if ($product["free_shipping"] != "Y")
			$total_weight += $product["weight"]*$product["amount"];
	}

	return $total_weight;
}

#
# This function increments product rating
#
function func_increment_rating($productid) {
	db_query("update products set rating=rating+1 where productid='$productid'");
}

#
# This function creates array with order data
#
function func_select_order($orderid) {
	#$order = func_query_first("select orders.*, shipping.shipping from orders, shipping where orders.orderid='$orderid' and shipping.shippingid=orders.shippingid");
	$order = func_query_first("select * from orders where orders.orderid='$orderid'");
	$shipping = func_query_first("select shipping from shipping where shippingid=".$order["shippingid"]);

	$order["shipping"] = $shipping["shipping"];

	$order["details"]=text_decrypt($order["details"]);
	return($order);
}

#
# This function returns data about specified order ($orderid)
#
function func_order_data($orderid) {

	$products = func_query("select products.*, order_details.* from order_details, products where order_details.orderid='$orderid' and order_details.productid=products.productid");
#
# If products are not present in products table, but they are present in
# order_details, then create fake $products from order_details data
#
	if(!$products) $products = func_query("select order_details.*, 'PRODUCT (deleted from database)' as product from order_details where order_details.orderid='$orderid'");
	$giftcerts = func_query("select * from giftcerts where orderid='$orderid'");

    $order = func_select_order($orderid);
    $userinfo = func_query_first("select * from orders where orderid='$orderid'");
	$result = func_query_first ("SELECT country FROM countries WHERE code='$userinfo[s_country]'");
	$userinfo["s_country_text"] = $result ["country"];

	$result = func_query_first ("SELECT state FROM states WHERE code='$userinfo[s_state]'");
	$userinfo["s_state_text"] = $result ["state"];

	if (!$products)
		$products = array ();

	return(array("order"=>$order,"products"=>$products,"userinfo"=>$userinfo, "giftcerts"=>$giftcerts));
}

#
# This function creates order entry in orders table
#
function func_place_order($payment_method, $order_status, $order_details) {
	
	global $cart, $userinfo, $discount_coupon, $mail_smarty, $orders_department, $unlimited_products, $active_modules, $download_key_ttl, $single_mode, $partner;

	$orderids = array ();

	$products = func_products_in_cart($cart, $userinfo["membership"]);

	foreach ($cart["orders"] as $current_order) {
#
# Insert into orders
#
    	db_query("insert into orders (login, total, subtotal, shipping_cost, shippingid, tax, discount, coupon, coupon_discount, date, status, payment_method, flag, details, title, firstname, lastname, company, b_address, b_city, b_state, b_country, b_zipcode, s_address, s_city, s_state, s_country, s_zipcode, phone, fax, email) values ('$userinfo[login]', '$current_order[total_cost]', '$current_order[sub_total]','$current_order[shipping_cost]', '$cart[shippingid]', '$current_order[tax_cost]', '$current_order[discount]', '$current_order[coupon]', '$current_order[coupon_discount]', '".time()."', '$order_status', '$payment_method', 'N', '".text_crypt($order_details)."', '$userinfo[title]', '$userinfo[firstname]', '$userinfo[lastname]', '$userinfo[company]', '$userinfo[b_address]', '$userinfo[b_city]', '$userinfo[b_state]', '$userinfo[b_country]', '$userinfo[b_zipcode]', '$userinfo[s_address]', '$userinfo[s_city]', '$userinfo[s_state]', '$userinfo[s_country]', '$userinfo[s_zipcode]', '$userinfo[phone]', '$userinfo[fax]', '$userinfo[email]')");

    	$orderid=db_insert_id();

		$partner_commitions = 0;
		if ($partner) {
			$result = func_query_first ("SELECT commition FROM partner_commitions WHERE login='$partner'");
			if ($result) {
				$partner_commitions = $result ["commition"];
				$to_partner = price_format(($current_order[sub_total]*$partner_commitions)/100);
				db_query ("INSERT INTO partner_payment (login, orderid, commitions, paid) VALUES ('$partner', '$orderid', '$to_partner', 'N')");
			}
		}
		
		$orderids [] = $orderid;
		$order=func_select_order($orderid);

#
# Insert into order details 
#
		foreach($products as $product) {
			if (($single_mode) or ($product["provider"] == $current_order["provider"])) {
        		db_query("insert into order_details (orderid, productid, product_options, amount, price, provider) values ('$orderid','$product[productid]','".addslashes($product["product_options"])."','$product[amount]','$product[price]','".addslashes($product["provider"])."')");
			}
		}

# 
# Update statistics for sold products 
#
		if ($active_modules["Advanced_Statistics"])
			include "../modules/Advanced_Statistics/prod_sold.php";

if ((($single_mode) or (!$current_order["provider"])) and ($cart["giftcerts"])) {
	foreach($cart["giftcerts"] as $giftcert) {
		
		$gcid = strtoupper(md5(uniqid(rand())));

		db_query("insert into giftcerts (gcid, orderid, purchaser, recipient, recipient_email, message, amount, debit, status, add_date) values ('$gcid', '$orderid','$giftcert[purchaser]','$giftcert[recipient]','$giftcert[recipient_email]','$giftcert[message]','$giftcert[amount]','$giftcert[amount]','P','".time()."')");
	}
}

#
# If $status=="F" then DO NOT DO FOLLOWING
#
		if ($order_status=="F") {
			break;
		}

#
# Mark discount coupons used
#

	$discount_coupon = $current_order[coupon];
	if ($discount_coupon) {
		db_query("update discount_coupons set times_used=times_used+1 where coupon='$discount_coupon'");
		db_query("update discount_coupons set status='U' where coupon='$discount_coupon' and times_used=times");
		$discount_coupon="";
	}

# Mail template processing
#

		$order_data = func_order_data($orderid);
    	$mail_smarty->assign("products",$order_data["products"]);
    	$mail_smarty->assign("giftcerts",$order_data["giftcerts"]);
    	$mail_smarty->assign("order",$order_data["order"]);

    	func_send_mail($orders_department, "mail/order_notification_subj.tpl", "mail/order_notification_admin.tpl", $userinfo["email"], true, true);

    	func_send_mail($userinfo["email"], "mail/order_customer_subj.tpl", "mail/order_customer.tpl", $orders_department, false);
		
		if ((!$single_mode) and ($current_order["provider"])) {
			$pr_query = "SELECT email FROM customers WHERE login='$current_order[provider]'";
			$pr_result = func_query_first ($pr_query);
			$prov_email = $pr_result ["email"];

			func_send_mail($prov_email, "mail/order_notification_subj.tpl", "mail/order_notification.tpl", $userinfo["email"], true);
		} else {
			$providers = array();
			foreach($products as $product) {
				$pr_result = func_query_first("select email from customers where login='$product[provider]'");
				$providers[] = $pr_result["email"];
			}
			
			$providers = array_unique($providers);
			
			foreach($providers as $prov_email)
				func_send_mail($prov_email, "mail/order_notification_subj.tpl", "mail/order_notification.tpl", $userinfo["email"], true);
		}

#
# If $status=="P" then send Download Keys
#
		if($order_status=="P")
			func_process_order($orderid);
	
	}

	#
	# Do not decrease number of products if order is failed
	#
	if ($order_status == "F") {
		return $orderids;
	}

	foreach($products as $product) {
#
# Decrease number of products in stock and increase product rating
#
		db_query("update products set rating=rating+1, avail=avail-".($unlimited_products=="Y"?"0":$product["amount"])." where productid=".$product["productid"]
);
 
	}

	return $orderids;
}
#
# This function performs activities nedded when order is processed
#
function func_process_order($orderid) {

	global $orders_department, $mail_smarty, $active_modules, $download_key_ttl;
	global $customer_language;

	$order_data = func_order_data($orderid);

	$order = $order_data["order"];
	$userinfo = $order_data["userinfo"];
	$products = $order_data["products"];
	$giftcerts = $order_data["giftcerts"];

	$res = func_query_first ("SELECT language FROM customers WHERE login='$userinfo[login]'");
	$customer_language = func_get_language ($res[language]);

	$mail_smarty->assign("customer",$userinfo);
	$mail_smarty->assign("products",$products);
	$mail_smarty->assign("giftcerts",$giftcerts);
	$mail_smarty->assign("order",$order);

#
# Order processing routine
# Send gift certificates
#
	if($giftcerts)
		foreach($giftcerts as $giftcert) {
		db_query("update giftcerts set status='A' where gcid='$giftcert[gcid]'");
		func_send_gc($userinfo["email"], $giftcert);
	}

#
# Send mail notifications
#
	func_send_mail($userinfo["email"], "mail/order_cust_processed_subj.tpl", "mail/order_customer_processed.tpl", $orders_department, false);

#
# Send E-goods download keys
#
	if($active_modules["Egoods"])
		include "../modules/Egoods/send_keys.php";

}

#
# This function joins order_id's and urlencodes 'em
#
function func_get_urlencoded_orderids ($orderids) {
	return urlencode (join (",", $orderids));
}

#
# This function performs activities nedded when order is declined
#
function func_decline_order($orderid) {

	global $orders_department, $mail_smarty;
	global $customer_language;
#
# Order decline routine
#
	$order_data = func_order_data($orderid);

	$order = $order_data["order"];
	$userinfo = $order_data["userinfo"];
	$products = $order_data["products"];
	$giftcerts = $order_data["giftcerts"];

	$res = func_query_first ("SELECT language FROM customers WHERE login='$userinfo[login]'");
	$customer_language = func_get_language ($res[language]);

	$mail_smarty->assign("customer",$userinfo);
	$mail_smarty->assign("products",$products);
	$mail_smarty->assign("giftcerts",$giftcerts);
	$mail_smarty->assign("order",$order);

	$giftcerts = $order_data["giftcerts"];

#
# Set GC's status to 'D'
#
	if($giftcerts)
		foreach($giftcerts as $giftcert)
		db_query("update giftcerts set status='D' where gcid='$giftcert[gcid]'");

# Send mail notifications
#
	func_send_mail($userinfo["email"], "mail/decline_notification_subj.tpl","mail/decline_notification.tpl", $orders_department, false);

	func_update_quantity ($products);

}

#
# Generate zones list
#
function func_shipping_zones() {

	global $single_mode, $login;

	$provider_condition=($single_mode?"":"and provider='$login'");

#
# Prepare all zones list (gather zones from country_zones and state_zones
#
	$all_zones = func_query("select zoneid from country_zones where 1 $provider_condition group by zoneid order by zoneid");

	if(empty($all_zones)) $all_zones = array();

	$all_zones = array_merge($all_zones,func_query("select zoneid from state_zones where 1 $provider_condition group by zoneid order by zoneid"));

	$zones = array();

	foreach($all_zones as $zone_data) 
        $zones[]=$zone_data["zoneid"];

	$zones = array_unique($zones);

	$all_zones=array(array("zoneid"=>0,"zone"=>"Zone Default"));

	foreach($zones as $zone_data) 
    	$all_zones[] = array("zoneid"=>$zone_data, "zone"=>"Zone $zone_data");

	return $all_zones;
}

#
# This function returns true if $cart is empty
#
function func_is_cart_empty($cart) {
	return !($cart["products"] || $cart["giftcerts"]);
}

#
# This function sends GC emails (called from func_place_order
# and provider/order.php"
#
function func_send_gc($from_email, $giftcert) {
	global $mail_smarty, $orders_department;
	global $customer_language;

	$giftcert["purchaser_email"] = $from_email;
	$mail_smarty->assign("giftcert", $giftcert);

#
# Send GC to recipient
#
	func_send_mail($giftcert["recipient_email"], "mail/giftcert_subj.tpl", "mail/giftcert.tpl", $from_email, false);
#
# Send notifs to $orders_department & purchaser
#
	func_send_mail($from_email, "mail/giftcert_notification_subj.tpl", "mail/giftcert_notification.tpl", $orders_department, false);
	func_send_mail($orders_department, "mail/giftcert_notification_subj.tpl", "mail/giftcert_notification.tpl", $from_email, true);
}

#
# This function checks for exception of product options for product
#
function func_check_product_options ($productid, $productoptions) {
	# First parse product options
	$product_options = array ();

	if ($productoptions) {
		foreach ($productoptions as $key=>$value) {
			$result = func_query_first ("SELECT * FROM product_options WHERE productid='$productid' AND optclass='$key'");
			$options = func_parse_options ($result[options]);
			$selected_option = $options [$value][option];
			$product_options [$key] = $selected_option;
		}
	}

	$result = func_query ("SELECT * FROM product_options_ex WHERE productid='$productid'");

	if (!$result)
		return;

	foreach ($result as $key=>$value) {
		$exceptions = array ();
	
		$columns = explode (";", $value[exception]);

		# Trim exceptions
		foreach ($columns as $subvalue) {
			$exception = explode ("=", $subvalue);
			$exception_optclass = trim ($exception[0]);
			$exception_option = trim ($exception[1]);

			$exceptions [$exception_optclass] = $exception_option;
		}

		$ex_size = sizeof($exceptions);
		$ex_found = 0;
	
		foreach ($exceptions as $subkey=>$subvalue) {
			if ($product_options[$subkey] == $subvalue)
				$ex_found ++;
		}
		
		if ($ex_found == $ex_size) {
			header ("Location: product.php?productid=$productid&err=options");
			exit;
		}
	}
}

#
# This function parses options string
#
function func_parse_options($option_lines) {
	
	$return=array();

	if(empty($option_lines)) return;

	$options = explode("\n",$option_lines);
	foreach($options as $option_line) {
		$option_line = chop($option_line);
		if(empty($option_line)) continue;
		if(strpos($option_line, "="))
			$option = substr($option_line, 0, strpos($option_line, "="));
		else
			$option = substr($option_line, 0);
		$surcharge=strstr($option_line,"=");
		$surcharge = str_replace("=","",$surcharge);
		$surcharge_type = (strstr($surcharge,"%")?"percent":"absolute");
		$surcharge = str_replace("%","",$surcharge);
		if($surcharge=="") $surcharge ="0";
#
# Check validity code goes here
#
		$return[] = array("option"=>$option, "surcharge"=>$surcharge, "type"=>$surcharge_type);
	}
	return $return;
}

function func_pgp_encrypt ($message) {
	global $pgp_prog, $pgp_key, $pgp_home_dir;
	global $use_pgp6;

	putenv ("PGPPATH=$pgp_home_dir");
	putenv ("PGPHOME=$pgp_home_dir");

	$message = addslashes ($message);

	if ($use_pgp6 == "Y") {
		$fn = tempnam ("/tmp", "msg");
		$fd = fopen ($fn, "w");
		fwrite ($fd, $message);
		fclose ($fd);

		echo `$pgp_prog +batchmode +force -ea $fn "$pgp_key"`;

		unlink ($fn);
		$fd = fopen ("$fn.asc", "r");
		$message = fread ($fd, 65535);
		fclose ($fd);
		unlink ("$fn.asc");
	} else {
		$message = `echo "$message" | $pgp_prog +batchmode +force -fea "$pgp_key" 2>/dev/null 2>&1`;
	}
	return $message;
}

#
# Move products back to the inventory
#
function func_update_quantity ($products) {
	if ($products) {
		foreach ($products as $product) {
			db_query ("UPDATE products SET avail=avail+'$product[amount]' WHERE productid='$product[productid]'");
		}
	}
}

function func_pgp_remove_key () {
	global $use_pgp6, $pgp_prog, $pgp_key;
	global $pgp_home_dir;

	putenv ("PGPPATH=$pgp_home_dir");
	putenv ("PGPHOME=$pgp_home_dir");

	if ($use_pgp6 == "Y") {
		`$pgp_prog -kr +force +batchmode '$pgp_key'`;
	} else {
		`$pgp_prog -kr +force '$pgp_key'`;
	}
}

function func_pgp_add_key () {
	global $use_pgp6;
	global $pgp_prog, $pgp_key;
	global $pgp_public_key, $pgp_home_dir;

	putenv ("PGPPATH=$pgp_home_dir");
	putenv ("PGPHOME=$pgp_home_dir");

	$fn = tempnam ("/tmp", "pub_key");

	$fd = fopen ($fn, "w");
	fwrite ($fd, $pgp_public_key);
	fclose ($fd);
	
	if ($use_pgp6 == "Y") {
		`$pgp_prog +batchmode -ka $fn 2>&1`;
		`$pgp_prog +batchmode -ks "$pgp_key"`;
	} else {
		`$pgp_prog -ka +force +batchmode $fn >/dev/null 2>&1`;
		`$pgp_prog +batchmode -ks '$pgp_key'`;
	}

	unlink ($fn);
}

function func_update_pgp () {
	global $use_pgp6;

	func_pgp_remove_key ();
	func_pgp_add_key ();
}

function func_get_language ($lang) {
	$result = func_query ("SELECT * FROM languages WHERE code='$lang'");
	if (!$result)
		return array ();
	
	$return = array ();
	
	foreach ($result as $key=>$value) {
		$return [$value[name]] = $value[value];
	}

	return $return;
}

#
# WARNING :
# Please ensure that you have no whitespaces / empty lines below this message.
# Adding a whitespace or an empty line below this line will cause a PHP error.
#
?>
