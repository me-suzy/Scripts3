<?php

# $Id: html_catalog.php,v 1.27.2.13 2004/03/31 07:01:22 mclap Exp $

# This script generates search engine friendly HTML catalog for X-cart

@set_time_limit(2700);

require "./auth.php";
require $xcart_dir."/include/security.php";

$catalog_dir_name = "/catalog";
$cat_dir = $xcart_dir.$catalog_dir_name;
$per_page = $config["Appearance"]["products_per_page"];
$max_name_length = 64;
$php_scripts = array("search.php","giftcert.php","help.php", "cart.php", "product.php","register.php", "home.php", "pages.php", "pconf.php", "notify.php");
$site_location = parse_url($http_location);

function getmicrotime() {
        list($usec, $sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
}

function my_save_data($filename, $data) {
        global $hc_state, $count;
        $fp = func_fopen($filename, "w+", true);
        if ($fp === false) {
                echo "<font color=red>Cannot save file: ".$filename."</font>";
                x_session_save();
                exit;
        }
        fwrite($fp, $data);
        $count++;

        echo "$filename<BR>\n";

        func_flush();
        if ($hc_state["pages_per_pass"] > 0 && $count >= $hc_state["pages_per_pass"]) {
                $hc_state["count"] += $count;
                echo "<HR>";
                func_html_location("html_catalog.php?mode=continue",1);
        }
}

#
# Generate filename for a category page
#

function category_filename($cat, $cat_name, $page = 1){
    global $max_name_length;
        global $sql_tbl;
        global $hc_state;

        if (empty($cat_name)) $cat_name = array_pop(func_query_first("SELECT category FROM $sql_tbl[categories] where categoryid='$cat'"));
        if (empty($cat_name)) $cat_name = $cat;
    $cat_name =preg_replace("/[ \/]/", "_", $cat_name);
    $cat_name = preg_replace("/[^A-Za-z0-9_]+/", "", $cat_name);
    $cat_name = preg_replace(array("!{category_name}!S", "!{page}!S", "!{categoryid}!S"),array($cat_name, $page, $cat),$hc_state["category_namestyle"]);

    return $cat_name;
}

#
# Generate filename for a product page
#

function product_filename($productid, $prod_name=false){
    global $max_name_length, $sql_tbl;
        global $hc_state;

        if (empty($prod_name)) $prod_name = array_pop(func_query_first("SELECT product FROM $sql_tbl[products] WHERE productid = '$productid'"));
        if (empty($prod_name)) $prod_name = $productid;
    $prod_name = substr($prod_name, 0, $max_name_length);
    $prod_name = preg_replace("/[ \/]/", "_", $prod_name);
    $prod_name = preg_replace("/[^A-Za-z0-9_]+/", "", $prod_name);
    $prod_name = preg_replace(array("!{product_name}!S", "!{productid}!S"),array($prod_name, $productid),$hc_state["product_namestyle"]);

    return $prod_name;
}

function category_callback($found) {
        $cat = false;
        $fn = array(0,1);
        if (preg_match("/cat=([0-9]+)/S",$found[2], $m)) $fn[0] = $cat = $m[1];
        if (preg_match("/page=([0-9]+)/S",$found[2], $m)) $fn[1] = $m[1];

        return $found[1].category_filename($fn[0],false,$fn[1]).$found[3];
}

function product_callback($found) {
        if (preg_match("/productid=([0-9]+)/S",$found[2], $m))
                return $found[1].product_filename($m[1]).$found[3];
        return $found[1].$found[3];
};

#
# Modify hyperlinksks to point to HTML pages of the catalogue
#

$php_scripts_long = implode("|", $php_scripts);

function process_page($page_src){
    global $php_scripts_long;
        global $XCART_SESSION_NAME;
        global $site_location;

    # Modify links to categories
        $page_src = preg_replace_callback('/(<a[^<>]+href[ ]*=[ ]*["\']*)[^"\']*home.php\?(cat=[^"\'>]+)(["\'])/iS', "category_callback", $page_src);
        # FancyCategories links
        $page_src = preg_replace_callback('/(window.location[ ]*=[ ]*["\']*)[^"\']*home.php\?(cat=[^"\'>]+)(["\'])/iS', "category_callback", $page_src);

    # Modify links to products
        $page_src = preg_replace_callback('/(<a[^<>]+href[ ]*=[ ]*["\']*)[^"\']*product.php\?(productid=[^"\'>]+)(["\'>])/iUS', "product_callback", $page_src);


    # Modify links to PHP scripts

    $page_src = preg_replace("/<a(.+)href[ ]*=[ ]*[\"']*(".$php_scripts_long.")([^\"^']*)[\"']/iUS", "<a\\1href=\"".$site_location["path"].DIR_CUSTOMER."/\\2\\3\"", $page_src);

    # Modify action values in HTML forms

    $page_src = preg_replace("/action[ ]*=[ ]*[\"']*(".$php_scripts_long.")([^\"^']*)[\"']/iUS", "action=\"".$site_location["path"].DIR_CUSTOMER."/\\1\"", $page_src);

    # Strip all PHP transsids if any
        while (preg_match("/<a(.+)href[ ]*=[ ]*[\"']*([^\"^']*)(\?".$XCART_SESSION_NAME."=|&".$XCART_SESSION_NAME."=)([^\"^']*)[\"']/iS", $page_src))
                $page_src = preg_replace("/<a(.+)href[ ]*=[ ]*[\"']*([^\"^']*)(\?".$XCART_SESSION_NAME."=|&".$XCART_SESSION_NAME."=)([^\"^']*)[\"']/iS", "<a\\1href=\"\\2\"", $page_src);

    $page_src = preg_replace("/<input[ ]+type=\"hidden\"[ ]+name=\"".$XCART_SESSION_NAME."\"[ ]+value=\"[a-zA-z0-9]*\"[ ]*\/>/iS", "", $page_src);

return $page_src;
}

if ($REQUEST_METHOD=="POST" && $mode=="catalog_gen" || $REQUEST_METHOD=="GET" && $mode=="continue") {

    include "./safe_mode.php";

        echo "Generating catalog<BR><BR>";
        func_flush();

        # variables initiation
        x_session_register("hc_state");
        $count = 0;
        if (empty($hc_state) || $REQUEST_METHOD=="POST") {
                $hc_state="";
                $hc_state["category_processed"] = false;
                $hc_state["catproducts_processed"] = false;
                $hc_state["last_cid"] = 0;
                $hc_state["last_pid"] = 0;
                $hc_state["cat_pages"] = 0;
                $hc_state["cat_page"] = 1;
                $hc_state["time_used"] = 0;
                $hc_state["count"] = 0;
                $hc_state["start_category"] = $start_category;
                $hc_state["pages_per_pass"] = $pages_per_pass;
                $hc_state["gen_action"] = $gen_action;
                $hc_state["process_subcats"] = isset($process_subcats);
                $hc_state["time_start"] = getmicrotime();
                if ($namestyle == "hyphen") {
                        $hc_state["category_namestyle"] = "{category_name}-p-{page}-c-{categoryid}.html";
                        $hc_state["product_namestyle"] = "{product_name}-p-{productid}.html";
                }
                elseif ($namestyle == "new") {
                        $hc_state["category_namestyle"] = "{category_name}_page_{page}_c_{categoryid}.html";
                        $hc_state["product_namestyle"] = "{product_name}_p_{productid}.html";
                }
                else {
                        # Old scheme (before 3.5.0)
                        $hc_state["category_namestyle"] = "category_{categoryid}_{category_name}_page_{page}.html";
                        $hc_state["product_namestyle"] = "product_{productid}_{product_name}.html";
                }

                if ($drop_pages == "on") {
                        echo "Deleting old catalog...<BR>";
                        func_flush();
                        $dir = opendir ($cat_dir);
                        while ($file = readdir ($dir)) {
                                if (($file == ".") or ($file == "..") or ($file=="shop_closed.html") or (strstr($file,".html")!=".html"))
                                        continue;
                                if ((filetype ("$cat_dir/$file") != "dir")) {
                                        unlink ("$cat_dir/$file");
                                }
                        }
                }
                echo "Converting pages to HTML <BR>"; func_flush();

                # Dump X-cart home page to disk

                list($http_headers, $page_src) = func_http_get_request($site_location["host"].":".$site_location["port"], $site_location["path"].DIR_CUSTOMER."/home.php", "");

                $page_src = process_page($page_src);
                my_save_data("$cat_dir/index.html", $page_src);
        }
        else {
                echo "Continue converting pages to HTML (".$hc_state["count"]." pages converted) <BR>"; func_flush();
        }

        #
        # Let's generate the catalog
        #
        if ($hc_state["cat_pages"] > 0 || isset($hc_state["catproducts"]))
                $categories_cond = "categoryid>=".$hc_state["last_cid"];
        else
                $categories_cond = "categoryid>".$hc_state["last_cid"];

        if (!empty($hc_state["start_category"]))
                $categories_cond .= " AND category='".$hc_state["start_category"]."' ".(@$process_subcats?" OR category LIKE '$start_category/%'":"")." ";

        if (!$hc_state["process_subcats"]) {
                if (!empty($hc_state["start_category"]))
                        $categories_cond .= " AND category NOT LIKE '$start_category/%/%'";
                else
                        $categories_cond .= " AND category NOT LIKE '%/%'";
        }

        $categories_data = db_query("SELECT categoryid, category FROM $sql_tbl[categories] WHERE ".$categories_cond." ORDER BY categoryid");

        $avail_condition = "";
        if ($config["General"]["unlimited_products"] == "N" && $config["General"]["disable_outofstock_products"] == "Y")
                $avail_condition = " AND $sql_tbl[products].avail>0 ";

        if ($categories_data) {
                while ($category_data = db_fetch_array($categories_data)) {
                        $hc_state["last_cid"] = $category_data["categoryid"];

                        if (($hc_state["gen_action"] & 1) === 1 && !isset($hc_state["catproducts"])) {
                                if ($hc_state["cat_pages"]==0 && !isset($hc_state["cat_done"])) {
                                        $product_count = array_pop(func_query_first("SELECT COUNT(*) FROM $sql_tbl[products] WHERE (categoryid=$category_data[categoryid] OR categoryid1=$category_data[categoryid] OR categoryid2=$category_data[categoryid] OR categoryid3=$category_data[categoryid]) ".$avail_condition));

                                        $pages = ceil($product_count/$per_page); if ($pages == 0) $pages = 1;
                                        $first = 1;
                                        $hc_state["cat_pages"] = $pages;
                                        $hc_state["cat_done"] = false;
                                }
                                else {
                                        $first = $hc_state["cat_page"]+1;
                                        $pages = $hc_state["cat_pages"];
                                }

                                # process pages of category
                                if (!isset($hc_state["cat_done"]) || !@$hc_state["cat_done"]) {
                                        for ($i = $first; $i <= $pages; $i++) {
                                                list($http_headers, $page_src) = func_http_get_request($site_location["host"].":".$site_location["port"], $site_location["path"].DIR_CUSTOMER."/home.php", "cat=$category_data[categoryid]&page=$i");
                                                $page_src = process_page($page_src);
                                                $page_name = category_filename($category_data["categoryid"], $category_data["category"], $i);
                                                $hc_state["cat_page"] = $i;
                                                my_save_data("$cat_dir/$page_name", $page_src);
                                        }
                                }
                                unset($hc_state["cat_done"]);
                                $hc_state["cat_page"] = 1;
                                $hc_state["cat_pages"] = 0;
                        }

                        # process products in category
                        if (($hc_state["gen_action"] & 2) === 2) {
                                $prod_cond = " AND productid>".$hc_state["last_pid"];

                                $products_data = db_query("SELECT productid, product FROM $sql_tbl[products] WHERE categoryid=$category_data[categoryid] AND forsale='Y' $prod_cond ORDER BY productid");
                                if ($products_data) {
                                        $hc_state["catproducts"] = false;
                                        while($product_data = db_fetch_array($products_data)) {
                                                $hc_state["last_pid"] = $product_data["productid"];

                                                list($http_headers, $page_src) = func_http_get_request($site_location["host"].":".$site_location["port"], $site_location["path"].DIR_CUSTOMER."/product.php", "productid=$product_data[productid]");
                                                $page_src = process_page($page_src);
                                                $page_name = product_filename($product_data["productid"], $product_data["product"]);
                                                my_save_data("$cat_dir/$page_name", $page_src);
                                        }
                                        $hc_state["last_pid"] = 0;
                                        unset($hc_state["catproducts"]);
                                }
                        }
                }
        }

        $time_end = getmicrotime();

    echo "<BR>HTML catalog was created successfully.<BR>";
        echo "Time elapsed: ".round($time_end-$hc_state["time_start"],2)." second(s)";
        x_session_unregister("hc_state");
        func_html_location("html_catalog.php",30);
}
else {
#
# Grab all categories
#
        x_session_unregister("hc_state");
        $categories = func_query("SELECT * FROM $sql_tbl[categories] ORDER BY category");

#
# Smarty display code goes here
#
        $smarty->assign("cat_dir", $cat_dir);
        $smarty->assign("cat_url", $http_location.$catalog_dir_name."/index.html");
        $smarty->assign("categories", $categories);

    $smarty->assign("main","html_catalog");

    @include $xcart_dir."/modules/gold_display.php";
    $smarty->display("admin/home.tpl");
}
?>