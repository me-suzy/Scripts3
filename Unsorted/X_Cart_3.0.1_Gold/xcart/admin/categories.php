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
# $Id: categories.php,v 1.9 2002/04/22 17:10:28 mav Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

require "../include/categories.php";

#
# Ajust category_location array
#
reset($category_location);
while(list($key,$cat_loc) = each($category_location)) 
	$category_location[$key][1] = str_replace("home.php","categories.php",$category_location[$key][1]);

if(!empty($current_category)) $location = array_merge($location,$category_location);

if(!empty($current_category)) $location = $category_location;

# FEATURED PRODUCTS
$f_cat = (empty ($cat) ? "0" : $cat);

if ($REQUEST_METHOD=="POST") {
	while(list($key,$val)=each($HTTP_POST_VARS)) {
		if (strstr($key,"-")) {
			list($field,$productid)=split("-",$key);
			if ($field=="avail")
				$val="Y";
			db_query("update featured_products set avail='N', $field='$val' where productid='$productid' AND categoryid='$f_cat'");
		}
	}

	if ($newproductid!="") {
		$newavail=($newavail=="on" ? "Y" : "N");
		if ($neworder=="") {
			$maxorder = array_pop(func_query_first("select max(product_order) from featured_products WHERE categoryid='$f_cat'"));
			$neworder=$maxorder+1;
		}

		if(func_query_first("select productid from products where productid='$newproductid'"))
			db_query("insert into featured_products (productid, product_order, avail, categoryid) values ('$newproductid','$neworder','$newavail', '$f_cat')");
	}
	header("Location: categories.php?cat=$cat");
	exit;
}

if ($mode == "delete") {
	db_query ("DELETE FROM featured_products WHERE productid='$productid' AND categoryid='$f_cat'");
	header("Location: categories.php?cat=$cat");
	exit;
}

$products = func_query ("SELECT featured_products.productid, products.product, featured_products.product_order, featured_products.avail from featured_products, products where featured_products.productid=products.productid AND featured_products.categoryid='$f_cat' order by featured_products.product_order");
$smarty->assign ("products", $products);
$smarty->assign ("f_cat", $f_cat);

$smarty->assign("location",$location);
$smarty->assign("main","categories");

@include "../modules/gold_display.php";
$smarty->display("admin/home.tpl");
?>
