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
# $Id: product.php,v 1.37 2002/04/22 17:10:29 mav Exp $
#

require "../smarty.php";
require "../config.php";
@include "./https.php";
require "./auth.php";

#
# Put all product info into $product array
#

$product_info = func_select_product($productid, $user_account['membership']);
$cat = $product_info["categoryid"];

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

include "./vote.php";

#
# Wholesales table
#
$wresult = func_query ("SELECT * FROM pricing WHERE productid='$productid' AND (pricing.membership='$user_account[membership]' or pricing.membership='') AND pricing.quantity>1 ORDER BY quantity");
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
