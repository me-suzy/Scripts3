<?php
/* -----------------------------------------------------------------------------------------
   $Id: admin.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (admin.php,v 1.12 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
?>
<!-- admin //-->
          <tr>
            <td><?php
  $orders_contents = '';
  $orders_status_query = xtc_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "'");
  while ($orders_status = xtc_db_fetch_array($orders_status_query)) {
    $orders_pending_query = xtc_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'");
    $orders_pending = xtc_db_fetch_array($orders_pending_query);
    $orders_contents .= '<a href="' . xtc_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $orders_pending['count'] . '<br>';
  }
  $orders_contents = substr($orders_contents, 0, -4);

  $customers_query = xtc_db_query("select count(*) as count from " . TABLE_CUSTOMERS);
  $customers = xtc_db_fetch_array($customers_query);
  $products_query = xtc_db_query("select count(*) as count from " . TABLE_PRODUCTS . " where products_status = '1'");
  $products = xtc_db_fetch_array($products_query);
  $reviews_query = xtc_db_query("select count(*) as count from " . TABLE_REVIEWS);
  $reviews = xtc_db_fetch_array($reviews_query);
  $admin_image = '<a href="' . xtc_href_link(FILENAME_START,'').'">' . xtc_image(DIR_WS_IMAGES.'admin_button.gif') .'</a>';
  if ($cPath != '' && $product_info['products_id'] != '') {
    $admin_link='<a href="' . xtc_href_link(FILENAME_EDIT_PRODUCTS, 'cPath=' . $cPath . '&pID=' . $product_info['products_id']) . '&action=new_product' . '" target="_blank">' . xtc_image(DIR_WS_ICONS . 'edit_product.gif') . '</a>';
  }
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_ADMIN);

  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<b>' . BOX_TITLE_STATISTICS . '</b><br>' . $orders_contents . '<br>' .
                                         BOX_ENTRY_CUSTOMERS . ' ' . $customers['count'] . '<br>' .
                                         BOX_ENTRY_PRODUCTS . ' ' . $products['count'] . '<br>' .
                                         BOX_ENTRY_REVIEWS . ' ' . $reviews['count'] .'<br>' .
                                         $admin_image . '<br>' .
                                         $admin_link);
  new infoBox($info_box_contents);
?></td>
          </tr>
<!-- admin //-->
