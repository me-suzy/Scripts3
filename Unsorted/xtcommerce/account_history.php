<?php
/* -----------------------------------------------------------------------------------------
   $Id: account_history.php,v 1.1 2003/09/06 21:38:27 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(account_history.php,v 1.60 2003/05/27); www.oscommerce.com 
   (c) 2003	 nextcommerce (account_history.php,v 1.13 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_count_customer_orders.inc.php');
  require_once(DIR_FS_INC . 'xtc_date_long.inc.php');
  require_once(DIR_FS_INC . 'xtc_image_button.inc.php');
  require_once(DIR_FS_INC . 'xtc_get_all_get_params.inc.php');

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    xtc_redirect(xtc_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_ACCOUNT_HISTORY);

  $breadcrumb->add(NAVBAR_TITLE_1, xtc_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, xtc_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php include(DIR_WS_MODULES.FILENAME_METATAGS); ?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="navLeft" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td class="tableBody" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo xtc_image(DIR_WS_IMAGES . 'table_background_history.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
  if (($orders_total = xtc_count_customer_orders()) > 0) {
    $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$_SESSION['customer_id'] . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$_SESSION['languages_id'] . "' order by orders_id DESC";
    $history_split = new splitPageResults($history_query_raw, $_GET['page'], MAX_DISPLAY_ORDER_HISTORY);
    $history_query = xtc_db_query($history_split->sql_query);

    while ($history = xtc_db_fetch_array($history_query)) {
      $products_query = xtc_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $history['orders_id'] . "'");
      $products = xtc_db_fetch_array($products_query);

      if (xtc_not_null($history['delivery_name'])) {
        $order_type = TEXT_ORDER_SHIPPED_TO;
        $order_name = $history['delivery_name'];
      } else {
        $order_type = TEXT_ORDER_BILLED_TO;
        $order_name = $history['billing_name'];
      }

      $order_heading = '<table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n" .
                       '  <tr>' . "\n" .
                       '    <td class="main"><b>' . TEXT_ORDER_NUMBER . '</b> ' . $history['orders_id'] . '</td>' . "\n" .
                       '    <td class="main" align="right"><b>' . TEXT_ORDER_STATUS . '</b> ' . $history['orders_status_name'] . '</td>' . "\n" .
                       '  </tr>' . "\n" .
                       '</table>';

      $order = '<table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n" .
               '  <tr>' . "\n" .
               '    <td class="main" width="50%" valign="top"><b>' . TEXT_ORDER_DATE . '</b> ' . xtc_date_long($history['date_purchased']) . '<br><b>' . $order_type . '</b> ' . $order_name . '</td>' . "\n" .
               '    <td class="main" width="30%" valign="top"><b>' . TEXT_ORDER_PRODUCTS . '</b> ' . $products['count'] . '<br><b>' . TEXT_ORDER_COST . '</b> ' . strip_tags($history['order_total']) . '</td>' . "\n" .
               '    <td class="main" width="20%"><a href="' . xtc_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'page=' . $HTTP_GET_VARS['page'] . '&order_id=' . $history['orders_id'], 'SSL') . '">' . xtc_image_button('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a></td>' . "\n" .
               '  </tr>' . "\n" .
               '</table>';

      new tableBox(array(array('text' => $order_heading)), true);
      new infoBox(array(array('text' => $order)));

      echo xtc_draw_separator('pixel_trans.gif', '1', '10');
    }
  } else {
    new infoBox(array(array('text' => TEXT_NO_PURCHASES)));
  }
?>
        </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($orders_total > 0) {
?>
          <tr>
            <td class="smallText" valign="top"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
            <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, xtc_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
<?php
  }
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo xtc_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . xtc_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . xtc_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td width="10"><?php echo xtc_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->    <td class="navRight" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>