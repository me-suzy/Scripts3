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

# $Id: html_catalog.php,v 1.16.2.3 2003/08/27 07:19:29 svowl Exp $

# This script generates search engine friendly HTML catalog for X-cart

@set_time_limit(2700);

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

include  "../include/categories.php";

$cat_dir = "../catalog";
$per_page = $config["Appearance"]["products_per_page"];
$max_name_length = 64;
$php_scripts = array("search.php","giftcert.php","help.php", "cart.php", "product.php","register.php", "home.php", "pages.php", "notify.php"); 

#
# Generate filename for a category page
#

function category_filename($cat, $page = 1){
    global $cat_names, $max_name_length;

    $cat_name = substr($cat_names[$cat], 0, $max_name_length);
    $cat_name =preg_replace("/[ \/]/", "_", $cat_name);
    $cat_name = preg_replace("/[^A-Za-z0-9_]+/", "", $cat_name);
    $cat_name = "category_".$cat."_".$cat_name."_page_".$page.".html";

    return $cat_name;
}

#
# Generate filename for a product page
#

function product_filename($productid){
    global $max_name_length, $sql_tbl;

    $tmp = func_query_first("SELECT product FROM $sql_tbl[products] WHERE productid = '$productid'");
    $prod_name = substr($tmp[product], 0, $max_name_length);
    $prod_name = preg_replace("/[ \/]/", "_", $prod_name);
    $prod_name = preg_replace("/[^A-Za-z0-9_]+/", "", $prod_name);
    $prod_name = "product_".$productid."_".$prod_name.".html";

    return $prod_name;
}

#
# Modify hyperlinksks to point to HTML pages of the catalogue 
#

function process_page($page_src){
    global $php_scripts;
	global $XCART_SESSION_NAME;

    # Modify links to categories with page number
    while (preg_match("/<a(.+)href[ ]*=[ ]*[\"']*home.php\?cat=[ ]*([0-9]+)&page=([0-9]+)[^\"^']*[\"']/i", $page_src, $found))
        $page_src = preg_replace("/<a(.+)href[ ]*=[ ]*([\"']*)home.php\?cat=[ ]*".$found[2]."&page=".$found[3]."([^0-9]*)/i", "<a\\1href=\\2".category_filename($found[2], $found[3])."\\3", $page_src);


    # Modify links to categories
    while (preg_match("/<a(.+)href[ ]*=[ ]*[\"']*home.php\?cat=[ ]*([0-9]+)[^\"^']*[\"']/i", $page_src, $found))
        $page_src = preg_replace("/<a(.+)href[ ]*=[ ]*([\"']*)home.php\?cat=[ ]*".$found[2]."([^0-9])/i", "<a\\1href=\\2".category_filename($found[2])."\\3", $page_src);

    # Modify links to products
    while (preg_match("/<a(.+)href[ ]*=[ ]*[\"']*product.php\?productid=[ ]*([0-9]+)([^\"^']*)[\"']/i", $page_src, $found))
		$page_src = preg_replace("/<a(.+)href[ ]*=[ ]*[\"']*product.php\?productid=[ ]*".$found[2]."([^0-9][^\"'>]*[\"']*|[\"'])/i", "<a\\1href=\"".product_filename($found[2])."\"", $page_src);
		

    # Modify links to PHP scripts

    $page_src = preg_replace("/<a(.+)href[ ]*=[ ]*[\"']*(".implode("|", $php_scripts).")([^\"^']*)[\"']/i", "<a\\1href=\"../customer/\\2\\3\"", $page_src);

    # Modify action values in HTML forms

    $page_src = preg_replace("/action[ ]*=[ ]*[\"']*(".implode("|", $php_scripts).")([^\"^']*)[\"']/i", "action=\"../customer/".$php_script."\\1\"", $page_src);

    # Strip all PHP transsids if any
	while (preg_match("/<a(.+)href[ ]*=[ ]*[\"']*([^\"^']*)(\?".$XCART_SESSION_NAME."=|&".$XCART_SESSION_NAME."=)([^\"^']*)[\"']/i", $page_src))
		$page_src = preg_replace("/<a(.+)href[ ]*=[ ]*[\"']*([^\"^']*)(\?".$XCART_SESSION_NAME."=|&".$XCART_SESSION_NAME."=)([^\"^']*)[\"']/i", "<a\\1href=\"\\2\"", $page_src);

    $page_src = preg_replace("/<input[ ]+type=\"hidden\"[ ]+name=\"".$XCART_SESSION_NAME."\"[ ]+value=\"[a-zA-z0-9]*\"[ ]*\/>/i", "", $page_src);

return $page_src; 
}

if($REQUEST_METHOD=="POST" && $mode=="catalog_gen") {

    include "./safe_mode.php";

    echo "Generating catalog<BR><BR>";

    func_flush();
    if ($drop_pages == "on"){
        echo "Deleting old catalog...<BR>";
        func_flush();
        $dir = opendir ($cat_dir);
        while ($file = readdir ($dir)) {
            if (($file == ".") or ($file == ".."))
                continue;
            if ((filetype ("$cat_dir/$file") != "dir")) {
                unlink ("$cat_dir/$file");
            }
        }   
    }


    echo "Preparing data... <BR>";
    func_flush();

    # Prepare array with the names of categories

    $tmp_array = func_query("SELECT categoryid, category FROM $sql_tbl[categories]");

    foreach($tmp_array as $key=>$value)
        $cat_names[$value[categoryid]] = $value[category];


    #Count number of products in each category

    foreach ($all_categories as $key=>$value)
        $all_categories[$key]["product_count"] = array_pop(func_query_first("SELECT COUNT(*) FROM $sql_tbl[products] WHERE (categoryid=$value[categoryid] OR categoryid1=$value[categoryid] OR categoryid2=$value[categoryid] OR categoryid3=$value[categoryid] AND avail>0)"));

    echo "Converting category pages to HTML <BR>";
 
    # Dump X-cart home page to disk

    list($http_headers, $page_src) = func_http_get_request($xcart_http_host, $xcart_web_dir."/customer/home.php", "");


    $page_src = process_page($page_src);


    $fp = fopen("$cat_dir/index.html", "w+");
    if (!$fp) exit(); 
    fwrite($fp, $page_src);

    # Traverse all categories and dump them on disk

    foreach ($all_categories as $key=>$value){
        $pages = ceil($value[product_count] / $per_page);
        if ($pages == 0) $pages = 1;
        for($i = 1; $i <= $pages; $i++){
            list($http_headers, $page_src) = func_http_get_request($xcart_http_host, $xcart_web_dir."/customer/home.php", "cat=$value[categoryid]&page=$i");
            $page_src = process_page($page_src);
            $page_name = category_filename($value[categoryid], $i); 
            $fp = fopen("$cat_dir/$page_name", "w+");
            if (!$fp) exit(); 
            fwrite($fp, $page_src);
            echo ".";
            func_flush();
        }
    }

    echo "<BR> Converting product pages to HTML <BR>";

    # Cycle through all products and dump them on disk

    $product_ids = func_query("select productid from $sql_tbl[products] where $sql_tbl[products].forsale='Y'");

    foreach($product_ids as $key=>$value){
        list($http_headers, $page_src) = func_http_get_request($xcart_http_host, $xcart_web_dir."/customer/product.php", "productid=$value[productid]");
        $page_src = process_page($page_src);
        $page_name = product_filename($value["productid"]); 
        $fp = fopen("$cat_dir/$page_name", "w+");
        if (!$fp) exit(); 
        fwrite($fp, $page_src);
        echo ".";
        func_flush();
    }

    echo "<BR> HTML catalog successfuly created.";
}
else {
#
# Smarty display code goes here
#
    $smarty->assign("main","html_catalog");

    @include "../modules/gold_display.php";
    $smarty->display("admin/home.tpl");
}
?>
