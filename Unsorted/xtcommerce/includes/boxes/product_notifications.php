<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_notifications.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_notifications.php,v 1.7 2003/05/25); www.oscommerce.com 
   (c) 2003	 nextcommerce (product_notifications.php,v 1.9 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include needed files
  require_once(DIR_FS_INC . 'xtc_get_products_name.inc.php');

  if (isset($_GET['products_id'])) {
?>
<!-- notifications //-->
          <tr>
            <td><?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_NOTIFICATIONS);

    new infoBoxHeading($info_box_contents, false, false, xtc_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL'));

    if (isset($_SESSION['customer_id'])) {
      $check_query = xtc_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . $_SESSION['customer_id'] . "'");
      $check = xtc_db_fetch_array($check_query);

      $notification_exists = (($check['count'] > 0) ? true : false);
    } else {
      $notification_exists = false;
    }

    $info_box_contents = array();
    if ($notification_exists == true) {
      $info_box_contents[] = array('text' => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBoxContents"><a href="' . xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . xtc_image(DIR_WS_IMAGES . 'box_products_notifications_remove.gif', IMAGE_BUTTON_REMOVE_NOTIFICATIONS) . '</a></td><td class="infoBoxContents"><a href="' . xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY_REMOVE, xtc_get_products_name($_GET['products_id'])) .'</a></td></tr></table>');
    } else {
      $info_box_contents[] = array('text' => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBoxContents"><a href="' . xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . xtc_image(DIR_WS_IMAGES . 'box_products_notifications.gif', IMAGE_BUTTON_NOTIFICATIONS) . '</a></td><td class="infoBoxContents"><a href="' . xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY, xtc_get_products_name($_GET['products_id'])) .'</a></td></tr></table>');
    }

    new infoBox($info_box_contents);
?></td>
          </tr>
<!-- notifications_eof //-->
<?php
  }
?>