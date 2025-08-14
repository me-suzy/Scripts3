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
# $Id: process_product.php,v 1.12.2.2 2003/06/02 11:57:45 svowl Exp $
#


if ($mode == "links")
	func_header_location("../admin/product_links.php?productid=$productid");

if ($mode == "update") {
	if ($product_avail) {
		foreach ($product_avail as $key=>$value){
			// echo $product_avail[$key];
			// funkydunk added code for notify when back in stock
			// send the email to all registered product watchers
			$watchers = db_query("SELECT email from `xcart_notify` WHERE productid = '$key'");
			$mailproduct = func_query_first("SELECT * from `xcart_products` WHERE productid = '$key'");
			$oldavail = intval($product['avail']);
			// echo $key . "<br>";
			// echo $oldavail . "now";
			// echo $value . "<br>";
			if ($value > $oldavail){			
				// echo "SELECT * from `xcart_notify` WHERE productid = '$productid'";
				$mail_smarty->assign ("product", $mailproduct); // put in to assign product info to smarty
					while ($row = db_fetch_array($watchers)){
						$email = $row['email'];
						// echo $email;
						func_send_mail($email, "mail/".$prefix."stock_notify_subj.tpl", "mail/".$prefix."stock_notify.tpl", $config["Company"]["orders_department"], false);
						// delete watchers from table
						db_query("delete from `xcart_notify` where email='" . $email . "' and productid = $key");
					}
				// $product = "";
				$watchers = "";
			}
			// end of code added by funkydunk
			db_query ("UPDATE $sql_tbl[products] SET avail='$value' WHERE productid='$key' $provider_condition");
			}
    }

	if ($product_price) {
		foreach ($product_price as $key1 => $value)
			db_query ("UPDATE $sql_tbl[pricing] SET price='$value' WHERE productid='$key1' AND quantity='1' AND membership=''");
	}

	if ($product_orderby) {
		foreach ($product_orderby as $key1 => $value)
			db_query ("UPDATE $sql_tbl[products] SET orderby='$value' WHERE productid='$key1'");
	}

	func_header_location("search.php?substring=$substring&search_category=$search_category&search_productid=$search_productid&updated");
}

if ($mode == "clone") {
#
# Clone product
#
	if ($productid) 
		include "../include/product_clone.php";

	func_header_location("search.php?substring=$substring&search_category=$search_category&search_productid=$search_productid&updated");
}
elseif($mode=="details") {
#
# Show product details
#
	func_header_location("product.php?productid=$productid");
}
elseif($mode=="modify") {
#
# Modify product
#
	func_header_location("product_modify.php?productid=$productid");
}
elseif($mode=="delete") {
	$product_info = func_select_product($productid, $user_account['membership']);
#
# Delete product
#   
	if($confirmed=="Y") {
		if($product_info) {
#
# Delete product from database
#
            func_delete_product($productid);
        }
		$smarty->assign("main","product_delete_message");

    } else {

		$smarty->assign("main","product_delete_confirmation");
		$smarty->assign("product",$product_info);

    }
}
elseif($mode=="pconfupdate" && $active_modules["Product_Configurator"]) {
	include("../modules/Product_Configurator/process.php");
}
?>
