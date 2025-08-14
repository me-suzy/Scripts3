<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_get_parent_categories.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (xtc_get_parent_categories.inc.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
  function xtc_get_parent_categories(&$categories, $categories_id) {
    $parent_categories_query = xtc_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . $categories_id . "'");
    while ($parent_categories = xtc_db_fetch_array($parent_categories_query)) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        xtc_get_parent_categories($categories, $parent_categories['parent_id']);
      }
    }
  }
 ?>