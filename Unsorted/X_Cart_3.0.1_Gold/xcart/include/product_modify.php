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
# $Id: product_modify.php,v 1.49 2002/05/24 13:38:10 mav Exp $
#
# Add, modify product
# Get product information
#

if ($productid!="") {
	$product_info = func_select_product($productid, $user_account['membership']);
	$product_languages = func_query ("SELECT products_lng.*, countries.country, countries.language FROM products_lng, countries WHERE products_lng.productid='$productid' AND products_lng.code=countries.code");
}

$int_languages = func_query ("SELECT DISTINCT(languages.code), countries.country, countries.language FROM languages, countries WHERE languages.code=countries.code AND languages.code!='$default_admin_language'");

$other_languages = array ();
if ($int_languages) {
	foreach ($int_languages as $key=>$value) {
		$found = false;
		if ($product_languages) {
			foreach ($product_languages as $lng) {
				if ($lng["code"] == $value["code"])
					$found = true;
			}
		}
		if (!$found)
			$other_languages [] = $value;
	}
}

$smarty->assign ("int_languages", $int_languages);
$smarty->assign ("other_languages", $other_languages);
$smarty->assign ("product_languages", $product_languages);

if ($REQUEST_METHOD == "POST" && $mode=="product_modify") {

    $fillerror = ($categoryid=="" || empty($product) || empty($descr) || $price=="" || $avail=="");

	if (($userfile!="none")&&($userfile!="")) {
			move_uploaded_file($userfile, "$file_temp_dir/$userfile_name");
			$userfile="$file_temp_dir/$userfile_name";
            $fd = fopen($userfile, "rb");
            $image = addslashes(fread($fd, filesize($userfile)));
            fclose($fd);
            list($image_size, $image_x, $image_y) = get_image_size($userfile);
			unlink($userfile);
    }
#
# Replace newlines by <br>'s in description fields
#
    $descr=str_replace("\n","<br>", $descr);
    $fulldescr=str_replace("\n","<br>", $fulldescr);

    if (!$fillerror) {
        if ($productid=="") {
#
# New product
#
        db_query("insert into products (provider, add_date) values ('$login', '".time()."')");

        $productid = db_insert_id();
#
# Insert pricing and image
#
        db_query("insert into pricing (productid, quantity, price) values ('$productid', '1', '$price')");
        if (($userfile!="none")&&($userfile!="")) db_query("insert into thumbnails (productid, image, image_type) values ('$productid', '$image', '$userfile_type')");

        $smarty->assign("main","product_add_message");
        }
        else {
#
# Update existing product
# For security reasons check if provider owners the
# product and then update pricing
#
            db_query("update pricing set price='$price' where productid='$productid' and quantity='1' and membership=''");

            if (($userfile!="none")&&($userfile!="")) { 
				db_query("delete from thumbnails where productid='$productid'");
				db_query("insert into thumbnails (productid, image, image_type) values ('$productid', '$image', '$userfile_type')");
				db_query("update products set image_x='$image_x', image_y='$image_y' where productid='$productid'");
			}

        	$smarty->assign("main","product_modify_message");
        }
#
# Update product data
#
	db_query("update products set product='$product', categoryid='$categoryid', categoryid1='$categoryid1', categoryid2='$categoryid2', categoryid3='$categoryid3', brand='$brand', model='$model', descr='$descr', fulldescr='$fulldescr', warranty='$warranty', avail='$avail', discount='$discount', location='$location', weight='$weight', productcode='$productcode', forsale='$forsale', distribution='$distribution', free_shipping='$free_shipping', shipping_freight='$shipping_freight', discount_avail='$discount_avail', min_amount='$min_amount', param00='$param00', param01='$param01', param02='$param02', param03='$param03', param04='$param04', param05='$param05', param06='$param06', param07='$param07', param08='$param08', param09='$param09' where productid='$productid'");

}
else {
#
# Form filled with errors
#
    $product_info=$HTTP_POST_VARS;
    $product_info["productid"]=$productid;
    $smarty->assign("main","product_modify");
    }
} else {
#
# GET request
#
    $smarty->assign("main","product_modify");
}

#
# International descriptions
#
if ($mode == "update_lng") {
	if ($product_lng_code) {
		foreach ($product_lng_code as $value) {
			db_query ("UPDATE products_lng SET descr='".addslashes($product_lng_descr[$value])."', full_descr='".addslashes($product_lng_full_descr[$value])."' WHERE code='$value' AND productid='$productid'");
		}
	}

	if ($product_new_descr or $product_new_full_descr) {
		db_query ("INSERT INTO products_lng (code, productid, descr, full_descr) VALUES ('$product_new_language','$productid','".addslashes($product_new_descr)."','".addslashes($product_new_full_descr)."')");
	}

	header ("Location: product_modify.php?productid=$productid&lng_updated");
	exit;
}

if ($mode == "del_lang") {
	db_query ("DELETE FROM products_lng WHERE productid='$productid' AND code='$code'");

	header ("Location: product_modify.php?productid=$productid&lng_deleted");
	exit;
}

#
# Detailed__Product_Images module
#
if($active_modules["Detailed_Product_Images"]) {
    include "../modules/Detailed_Product_Images/product_images_modify.php";
    include "../modules/Detailed_Product_Images/product_images.php";
}
#
# Wholesale trading module
#
if($active_modules["Wholesale_Trading"])
	include "../modules/Wholesale_Trading/product_wholesale.php";
#
# Product options module
#
if($active_modules["Product_Options"])
	include "../modules/Product_Options/product_options.php";
#
# Extra fields module
#
if($active_modules["Extra_Fields"]) {
	$extra_fields_provider=$login;
	include "../modules/Extra_Fields/extra_fields.php";
}

#include "../modules/Product_Shipping/product_shipping.php";

#
# Upselling products module
#
if($active_modules["Upselling_Products"])
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

if ($productid!="" && !$fillerror) 
    $product_info = func_select_product($productid, $user_account['membership']); 

$smarty->assign("product",$product_info);
$smarty->assign("fillerror",$fillerror);

?>
