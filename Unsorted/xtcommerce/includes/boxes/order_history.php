<?php
/* -----------------------------------------------------------------------------------------
   $Id: order_history.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(order_history.php,v 1.4 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (order_history.php,v 1.9 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_get_all_get_params.inc.php');

  if (isset($_SESSION['customer_id'])) {
    // retreive the last x products purchased
    $orders_query = xtc_db_query("select distinct op.products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . $_SESSION['customer_id'] . "' and o.orders_id = op.orders_id and op.products_id = p.products_id and p.products_status = '1' group by products_id order by o.date_purchased desc limit " . MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX);
    if (xtc_db_num_rows($orders_query)) {
?>
<!-- customer_orders //-->
          <tr>
            <td><?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => BOX_HEADING_CUSTOMER_ORDERS);

      new infoBoxHeading($info_box_contents, false, false);

      $product_ids = '';
      while ($orders = xtc_db_fetch_array($orders_query)) {
        $product_ids .= $orders['products_id'] . ',';
      }
      $product_ids = substr($product_ids, 0, -1);

      $customer_orders_string = '<table border="0" width="100%" cellspacing="0" cellpadding="1">';
      $products_query = xtc_db_query("select products_id, products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . $product_ids . ") and language_id = '" . $_SESSION['languages_id'] . "' order by products_name");
      while ($products = xtc_db_fetch_array($products_query)) {
        $customer_orders_string .= '  <tr>' .
                                   '    <td class="infoBoxContents"><a href="' . xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">' . $products['products_name'] . '</a></td>' .
                                   '    <td class="infoBoxContents" align="right" valign="top"><a href="' . xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('action')) . 'action=cust_order&pid=' . $products['products_id']) . '">' . xtc_image(DIR_WS_ICONS . 'cart.gif', ICON_CART) . '</a></td>' .
                                   '  </tr>';
      }
      $customer_orders_string .= '</table>';

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $customer_orders_string);

      new infoBox($info_box_contents);
?></td>
          </tr>
<!-- customer_orders_eof //-->
<?php
    }
  }
?>