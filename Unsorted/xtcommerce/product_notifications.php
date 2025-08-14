<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_notifications.php,v 1.1 2003/09/06 21:38:27 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_notifications.php,v 1.7 2003/02/14); www.oscommerce.com 
   (c) 2003	 nextcommerce (product_notifications.php,v 1.9 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    xtc_redirect(xtc_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'update_notifications')) {
    $products = $_POST['products'];
    $remove = '';
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      $remove .= '\'' . $products[$i] . '\',';
    }
    $remove = substr($remove, 0, -1);

    if (xtc_not_null($remove)) {
      xtc_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id in (" . $remove . ")");
    }

    xtc_redirect(xtc_href_link(FILENAME_PRODUCT_NOTIFICATIONS, '', 'SSL'));
  } elseif (isset($_GET['action']) && ($_GET['action'] == 'global_notify')) {
    if (isset($_POST['global']) && ($_POST['global'] == 'enable')) {
      xtc_db_query("update " . TABLE_CUSTOMERS_INFO . " set global_product_notifications = '1' where customers_info_id = '" . $_SESSION['customer_id'] . "'");
    } else {
      $check_query = xtc_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $_SESSION['customer_id'] . "' and global_product_notifications = '1'");
      $check = xtc_db_fetch_array($check_query);
      if ($check['count'] > 0) {
        xtc_db_query("update " . TABLE_CUSTOMERS_INFO . " set global_product_notifications = '0' where customers_info_id = '" . $_SESSION['customer_id'] . "'");
      }
    }

    xtc_redirect(xtc_href_link(FILENAME_PRODUCT_NOTIFICATIONS, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_PRODUCT_NOTIFICATIONS);

  $breadcrumb->add(NAVBAR_TITLE_1, xtc_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, xtc_href_link(FILENAME_PRODUCT_NOTIFICATIONS, '', 'SSL'));

  $global_status_query = xtc_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $_SESSION['customer_id'] . "'");
  $global_status = xtc_db_fetch_array($global_status_query);
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
            <td class="pageHeading" align="right"><?php echo xtc_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><?php echo TEXT_PRODUCT_NOTIFICATIONS_INTRODUCTION; ?></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  if ($global_status['global_product_notifications'] == '1') {
?>
          <tr>
            <td class="main"><b><?php echo HEADING_GLOBAL_PRODUCT_NOTIFICATIONS; ?></b></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_ENABLED; ?></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_DESCRIPTION_ENABLED; ?></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <?php echo xtc_draw_form('global', xtc_href_link(FILENAME_PRODUCT_NOTIFICATIONS, 'action=global_notify', 'SSL')); ?>
          <tr>
            <td class="main"><?php echo xtc_draw_checkbox_field('global', 'enable', true) . '&nbsp;' . TEXT_ENABLE_GLOBAL_NOTIFICATIONS; ?></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo xtc_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
          </tr>
          </form>
<?php
  } else {
?>
          <tr>
            <td class="main"><b><?php echo HEADING_GLOBAL_PRODUCT_NOTIFICATIONS; ?></b></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_DISABLED; ?></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_DESCRIPTION_DISABLED; ?></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <?php echo xtc_draw_form('global', xtc_href_link(FILENAME_PRODUCT_NOTIFICATIONS, 'action=global_notify', 'SSL')); ?>
          <tr>
            <td class="main"><?php echo xtc_draw_checkbox_field('global', 'enable') . '&nbsp;' . TEXT_ENABLE_GLOBAL_NOTIFICATIONS; ?></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo xtc_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
          </tr>
          </form>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo HEADING_PRODUCT_NOTIFICATIONS; ?></b></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCT_NOTIFICATIONS_LIST; ?></td>
          </tr>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <?php echo xtc_draw_form('notifications', xtc_href_link(FILENAME_PRODUCT_NOTIFICATIONS, 'action=update_notifications', 'SSL')); ?>
<?php
    $products_query = xtc_db_query("select pd.products_id, pd.products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where pn.customers_id = '" . $_SESSION['customer_id'] . "' and pn.products_id = pd.products_id and pd.language_id = '" . $_SESSION['languages_id'] . "' order by pd.products_name");
    while ($products = xtc_db_fetch_array($products_query)) {
      echo '          <tr>' . "\n" .
           '            <td class="main">' . xtc_draw_checkbox_field('products[]', $products['products_id']) . '&nbsp;' . $products['products_name'] . '</td>' . "\n" .
           '          </tr>' . "\n";
    }
?>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo xtc_image_submit('button_remove_notifications.gif', IMAGE_BUTTON_REMOVE_NOTIFICATIONS); ?></td>
          </tr>
          </form>
<?php
  }
?>
          <tr>
            <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td align="right" class="smallText"><?php echo '<a href="' . xtc_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . xtc_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td class="navRight" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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