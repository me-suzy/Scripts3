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
# $Id: featured_products.php,v 1.30.2.3 2003/06/02 11:57:43 svowl Exp $
#
# Get featured products data and store it into $f_products array
# Get new products data and store it into $new_products array
#

#
# select from featured products table
#

$membership = $user_account['membership'];
$f_cat = (empty ($cat) ? "0" : $cat);
if ($config["General"]["unlimited_products"]=="N")
	$avail_condition = "$sql_tbl[products].avail>0 and ";
else
	$avail_condition = "";
$f_products = func_query("select $sql_tbl[products].*, min($sql_tbl[pricing].price) as price from $sql_tbl[products], $sql_tbl[featured_products], $sql_tbl[pricing], $sql_tbl[categories] where $sql_tbl[products].productid=$sql_tbl[featured_products].productid and $sql_tbl[pricing].productid=$sql_tbl[products].productid AND $sql_tbl[products].categoryid=$sql_tbl[categories].categoryid AND ($sql_tbl[categories].membership='$membership' OR $sql_tbl[categories].membership='') and $sql_tbl[products].forsale='Y' and $avail_condition $sql_tbl[featured_products].avail='Y' and $sql_tbl[pricing].quantity=1 AND $sql_tbl[featured_products].categoryid='$f_cat' and ($sql_tbl[pricing].membership='$membership' or $sql_tbl[pricing].membership='') group by $sql_tbl[products].productid order by $sql_tbl[featured_products].product_order");

if(is_array($f_products)){ 
    foreach($f_products as $f_v => $f_k){
        $int_res = '';
        if(is_array($f_k)){

#
# Get thumbnail's URL (uses only if images stored in FS) 
#  
			$f_products[$f_v]["tmbn_url"] = func_get_thumbnail_url($f_products[$f_v]["productid"]);
#
# Recalculate prices if VAT is enabled and prices do not includes VAT 
#
            if ($config["Taxes"]["use_vat"]=="Y" && ($config["General"]["eu_national"]=="Y" || $config["Taxes"]["price_includes_vat"]=="Y") && $f_k["vat"]>0) {
                $vat = array_pop(func_query_first("SELECT value FROM $sql_tbl[vat_rates] WHERE rateid='$f_k[vat]'"));
                $f_products[$f_v]["vat"] = $vat;
                if ($config["Taxes"]["price_includes_vat"]=="N") {
                    $price = $f_k["price"];
                    $f_products[$f_v]["price"] += price_format($price*$vat/100);
                }
				elseif ($config["General"]["eu_national"]!="Y") {
					$price = $f_k["price"];
					$f_products[$f_v]["price"] -= price_format($price*$vat/100);
					$f_products[$f_v]["vat"] = 0;
				}
            }
			else
				$f_products[$f_v]["vat"] = 0;

#
# Check if product have product options
#
			$f_products[$f_v][product_options] = array_pop(func_query_first("SELECT COUNT(*) FROM $sql_tbl[product_options] WHERE productid='".$f_k["productid"]."'"));

// put in by funkydunk for product options to be available on products.tpl page:
if($active_modules["Product_Options"]){

	for($i=0;$i<count($f_products);$i++){
	$prodid = $f_products[$i]["productid"];
	$product_option_lines = func_query("select * from $sql_tbl[product_options] where productid='$prodid' order by orderby");
	// func_print_r($product_option_lines);
		if($product_option_lines){
			$f_products[$i]["product_options"]= array();
			foreach($product_option_lines as $product_option_line) {						
						$f_products[$i]["product_options"][] = array_merge($product_option_line,array("options" => func_parse_options($product_option_line["options"])));
			}
		}
	} // end of loop to add product options in
}
// end of funkydunk added code

#
# Replace descr and fulldescr on international (if defined)
#
            $int_res = func_query_first("SELECT * FROM  $sql_tbl[products_lng] WHERE code='$store_language' AND productid='".$f_k['productid']."'");
			if ($int_res["product"]){
				$f_products[$f_v]["product"] = stripslashes($int_res["product"]);
		    }    
         	if ($int_res["descr"]){
		    	$f_products[$f_v]["descr"] = str_replace("\n","<br>", stripslashes($int_res["descr"]));
		    }    
		    if ($int_res["full_descr"]){ 
		        $f_products[$f_v]["fulldescr"] = str_replace("\n","<br>", stripslashes($int_res["full_descr"]));
		    }    
		}        
	} 
};   


if($active_modules["Subscriptions"]) {
    include_once "../modules/Subscriptions/subscription.php";
}
	
$smarty->assign("f_products",$f_products);
?>