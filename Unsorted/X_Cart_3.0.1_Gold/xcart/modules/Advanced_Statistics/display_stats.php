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
# $Id: display_stats.php,v 1.4 2002/04/22 17:10:29 mav Exp $
#
# This module generates lists to be displayed in advanced statistics 
#

#
# Navigation code
#
$res = func_query_first("SELECT COUNT(*) FROM referers");
$total_nav_pages = ceil($res["COUNT(*)"]/$products_per_page)+1;

require "../include/navigation.php";



include "../include/categories.php";
# List of category views

$query = "SELECT category FROM categories WHERE categoryid = '$cat'";
$res = func_query_first($query);
$cat_name = $res['category'];
$query = "SELECT categoryid, category, views_stats FROM categories WHERE category LIKE '$cat_name%'  ORDER BY views_stats DESC";
$category_viewes = func_query($query);
$query = "SELECT MAX(views_stats) FROM categories WHERE category LIKE '$cat_name%'";
$res = func_query_first($query);
$max_category_viewes = $res['MAX(views_stats)'];

# Select only categories from the current herarchy level
$tmp = array();
foreach($category_viewes as $key => $value)
	if (!strstr(substr($value['category'],strlen($cat_name)+1),"/"))  array_push($tmp, $value);
$category_viewes = $tmp;

# Make navigation bar

if (empty($cat_name)) $tmp = array();
	else $tmp = split("/", $cat_name);
$nav_bar = array();
$cur_cat_name = "";
foreach($tmp as $value){
	$cur_cat_name .= (empty($cur_cat_name)?"$value":"/$value");
	$query = "SELECT categoryid FROM categories WHERE category = '$cur_cat_name'";
	$res = func_query_first($query);
	array_push($nav_bar, array("cat_name"=>$value, "cat"=>$res['categoryid']));	
}

if ($cat_name != ""){
# List of product views
	$product_viewes = func_query("SELECT productid, product, products.views_stats FROM products, categories WHERE (products.views_stats > 0)AND(products.categoryid = categories.categoryid)AND(categories.category LIKE '$cat_name%') ORDER BY products.views_stats DESC");
	$res = func_query_first("SELECT MAX(products.views_stats) FROM products, categories WHERE (products.views_stats > 0)AND(products.categoryid = categories.categoryid)AND(categories.category LIKE '$cat_name%')");
	$max_product_viewes = $res['MAX(products.views_stats)'];
# List of product sales 

	$product_sales = func_query("SELECT productid, product, products.sales_stats FROM products, categories WHERE (products.sales_stats > 0)AND(products.categoryid = categories.categoryid)AND(categories.category LIKE '$cat_name%') ORDER BY products.sales_stats DESC");
	$res = func_query_first("SELECT MAX(products.sales_stats) FROM products, categories WHERE (products.sales_stats > 0)AND(products.categoryid = categories.categoryid)AND(categories.category LIKE '$cat_name%')");
	$max_product_sales = $res['MAX(products.sales_stats)'];
# List of deleted from the cart products 

	$product_deleted = func_query("SELECT productid, product, products.del_stats FROM products, categories WHERE (products.del_stats > 0)AND(products.categoryid = categories.categoryid)AND(categories.category LIKE '$cat_name%') ORDER BY products.del_stats DESC");
	$res = func_query_first("SELECT MAX(products.del_stats) FROM products, categories WHERE (products.del_stats > 0)AND(products.categoryid = categories.categoryid)AND(categories.category LIKE '$cat_name%')");
	$max_product_deleted = $res['MAX(products.del_stats)'];

}

#
# Prepare statistics on referers
#

	$referers_array = func_query("SELECT * FROM referers ORDER BY visits DESC LIMIT $first_page, $products_per_page");
	$res = func_query_first("SELECT MAX(visits) FROM referers");
	$max_visits = $res["MAX(visits)"];





$smarty->assign("category_viewes", $category_viewes);
$smarty->assign("product_viewes", $product_viewes);
$smarty->assign("product_sales", $product_sales);
$smarty->assign("product_deleted", $product_deleted);
$smarty->assign("referers_array", $referers_array);
$smarty->assign("max_category_viewes", $max_category_viewes);
$smarty->assign("max_product_viewes", $max_product_viewes);
$smarty->assign("max_product_sales", $max_product_sales);
$smarty->assign("max_product_deleted", $max_product_deleted);
$smarty->assign("max_visits", $max_visits);
$smarty->assign("cat_name", $cat_name);
$smarty->assign("nav_bar", $nav_bar);
$smarty->assign("navigation_script","statistics.php?cat={$cat}");

?>
