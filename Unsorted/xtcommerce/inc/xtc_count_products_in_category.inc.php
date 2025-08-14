<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_count_products_in_category.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (xtc_count_products_in_category.inc.php,v 1.3 2003/08/13); www.nextcommerce.org 

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function xtc_count_products_in_category($category_id, $include_inactive = false) {
    $products_count = 0;
    if ($include_inactive == true) {
      $products_query = xtc_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . $category_id . "'");
    } else {
      $products_query = xtc_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . $category_id . "'");
    }
    $products = xtc_db_fetch_array($products_query);
    $products_count += $products['total'];

    $child_categories_query = xtc_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $category_id . "'");
    if (xtc_db_num_rows($child_categories_query)) {
      while ($child_categories = xtc_db_fetch_array($child_categories_query)) {
        $products_count += xtc_count_products_in_category($child_categories['categories_id'], $include_inactive);
      }
    }

    return $products_count;
  }
 ?>