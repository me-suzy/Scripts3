<?php
/* -----------------------------------------------------------------------------------------
   $Id: new_products.php,v 1.1 2003/09/06 22:13:54 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(new_products.php,v 1.33 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (new_products.php,v 1.9 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/   

  require_once(DIR_FS_INC . 'xtc_get_products_name.inc.php');
  require_once(DIR_FS_INC . 'xtc_get_products_price.inc.php');
?>
<!-- new_products //-->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')));

  new contentBoxHeading($info_box_contents);

  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
    $new_products_query = xtc_db_query("select p.products_id, p.products_image, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where c.categories_status='1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and products_status = '1' order by p.products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } else {
    $new_products_query = xtc_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where c.categories_status='1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . $new_products_category_id . "' and p.products_status = '1' order by p.products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
  }
  $row = 0;
  $col = 0;
  $info_box_contents = array();
  while ($new_products = xtc_db_fetch_array($new_products_query)) {
    $new_products['products_name'] = xtc_get_products_name($new_products['products_id']);
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . xtc_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a><br>' . xtc_get_products_price($new_products['products_id'],$price_special=1,$quantity=1));

    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }

  new contentBox($info_box_contents);
?>
<!-- new_products_eof //-->
