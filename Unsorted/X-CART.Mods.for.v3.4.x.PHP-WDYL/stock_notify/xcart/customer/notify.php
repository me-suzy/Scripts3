<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2002 Ruslan R. Fazliev. All rights reserved.            |
+-----------------------------------------------------------------------------+
| The Ruslan R. Fazliev forbids, under any circumstances, the unauthorized   |
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
| THIS SOFTWARE IS PROVIDED BY Ruslan R. Fazliev ``AS IS'' AND ANY  |
| EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED |
| WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE      |
| DISCLAIMED.  IN NO EVENT SHALL Ruslan R. Fazliev OR ITS           |
| CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,       |
| EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,         |
| PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; |
| OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,    |
| WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR     |
| OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF      |
| ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                  |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev.           |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2002          |
| Ruslan R. Fazliev. All Rights Reserved.                                    |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: product.php,v 1.49 2002/11/29 14:46:02 vod Exp $
#

require "../smarty.php";
require "../config.php";
@include "./https.php";
require "./auth.php";

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

require "../include/categories.php";

if(($login)&& ($product_info != "") && ($active_modules["stock_notify"])) {
// ie they are looged in and have selected a product
// echo "logged and ready to roll";
$userinfo = func_userinfo($login,$login_type);
// echo $userinfo['email'];
// echo "INSERT INTO `xcart_notify` ( `email` , `productid` ) VALUES ('" . $userinfo['email'] . "', '$productid');";

// deletes their email if they are already watching this product
db_query("delete from `xcart_notify` where email='" . $userinfo['email'] . "' and productid = $productid");
// add them to the notify table
db_query("INSERT INTO `xcart_notify` ( `email` , `productid` ) VALUES ('" . $userinfo['email'] . "', '$productid')");
}
else {

	if (($guestemail) && ($product_info != "") && ($active_modules["stock_notify"])){
	// deletes their email if they are already watching this product
	db_query("delete from `xcart_notify` where email='" . $guestemail . "' and productid = $productid");
	// add them to the notify table
	db_query("INSERT INTO `xcart_notify` ( `email` , `productid` ) VALUES ('" . $guestemail . "', '$productid')");
	$smarty->assign("guestlogged","true");
	}
	else{
	if ($guest!="true"){
		header("Location: error_message.php?access_denied");
		exit();
		}
	}
}
$smarty->assign("productid",$productid);
$smarty->assign("userinfo",$userinfo);
$smarty->assign("product",$product_info);
$smarty->assign("main","notify");
$smarty->display("customer/home.tpl");
?>