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
# $Id: products.php,v 1.30 2002/11/19 12:06:10 alfiya Exp $
#
# Navigation code
#

$objects_per_page = $config["Appearance"]["products_per_page"];

$total_nav_pages = ceil($current_category["product_count"]/$config["Appearance"]["products_per_page"])+1;

require "../include/navigation.php";

if($active_modules["Advanced_Statistics"])
    include "../modules/Advanced_Statistics/cat_viewed.php";



#
# Get products data for current category and store it into $products array
#
$search_query = "($sql_tbl[products].categoryid='$cat' or $sql_tbl[products].categoryid1='$cat' or $sql_tbl[products].categoryid2='$cat' or $sql_tbl[products].categoryid3='$cat') and $sql_tbl[products].forsale='Y'";

$products = func_search_products($search_query, $user_account['membership'], $first_page,$current_category["product_count"]);
if (count($products) ==0) $products="";

// put in by funkydunk for product options to be available on products.tpl page:
if($active_modules["Product_Options"]){

	for($i=0;$i<count($products);$i++){
	$prodid = $products[$i]["productid"];
	$product_option_lines = func_query("select * from $sql_tbl[product_options] where productid='$prodid' order by orderby");
	// func_print_r($product_option_lines);
		if($product_option_lines){
			foreach($product_option_lines as $product_option_line) {
						$products[$i]["product_options"]= array();
						$products[$i]["product_options"][] = array_merge($product_option_line,array("options" => func_parse_options($product_option_line["options"])));
			}
		}
	} // end of loop to add product options in
}
// end of funkydunk added code


if($active_modules["Subscriptions"]) {
    include "../modules/Subscriptions/subscription.php";
}

$smarty->assign("products",$products);
$smarty->assign("navigation_script","home.php?cat=$cat");
?>
