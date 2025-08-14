<?php
/* -----------------------------------------------------------------------------------------
   $Id: checkout_confirmation.php,v 1.1 2003/09/06 21:38:27 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_confirmation.php,v 1.137 2003/05/07); www.oscommerce.com 
   (c) 2003	 nextcommerce (checkout_confirmation.php,v 1.21 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   agree_conditions_1.01        	Autor:	Thomas Plänkers (webmaster@oscommerce.at)
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_check_stock.inc.php');
  require_once(DIR_FS_INC . 'xtc_display_tax_value.inc.php');
  require_once(DIR_FS_INC . 'xtc_get_products_attribute_price_checkout.inc.php');

  // if the customer is not logged on, redirect them to the login page

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    xtc_redirect(xtc_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() < 1) {
    xtc_redirect(xtc_href_link(FILENAME_SHOPPING_CART));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($_SESSION['cart']->cartID) && isset($_SESSION['cartID'])) {
    if ($_SESSION['cart']->cartID != $_SESSION['cartID']) {
      xtc_redirect(xtc_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!isset($_SESSION['shipping'])) {
    xtc_redirect(xtc_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

//check if display conditions on checkout page is true

  if (isset($_POST['payment'])) $_SESSION['payment'] = $_POST['payment'];

  if ($_POST['comments_added'] != '') {
    $_SESSION['comments'] = xtc_db_prepare_input($_POST['comments']);
  }
  
//-- TheMedia Begin check if display conditions on checkout page is true
// if conditions are not accepted, redirect the customer to the payment method selection page
  if (DISPLAY_CONDITIONS_ON_CHECKOUT == 'true') {
    if ($_POST['conditions'] == false) {
      xtc_redirect(xtc_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_CONDITIONS_NOT_ACCEPTED), 'SSL', true, false));
    }
  }

// load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($_SESSION['payment']);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  $payment_modules->update_status();

  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$_SESSION['payment']) ) || (is_object($$_SESSION['payment']) && ($$_SESSION['payment']->enabled == false)) ) {
    xtc_redirect(xtc_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }

  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }

// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($_SESSION['shipping']);

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (xtc_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      xtc_redirect(xtc_href_link(FILENAME_SHOPPING_CART));
    }
  }

  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_CHECKOUT_CONFIRMATION);

  $breadcrumb->add(NAVBAR_TITLE_1, xtc_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2);
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
            <td class="pageHeading" align="right"><?php echo xtc_image(DIR_WS_IMAGES . 'table_background_confirmation.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
<?php
  if ($_SESSION['sendto'] != false) {
?>
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="' . xtc_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo xtc_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?></td>
              </tr>
<?php
    if ($order->info['shipping_method']) {
?>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_SHIPPING_METHOD . '</b> <a href="' . xtc_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['shipping_method']; ?></td>
              </tr>
<?php
    }
?>
            </table></td>
<?php
  }
?>
            <td width="<?php echo (($_SESSION['sendto'] != false) ? '70%' : '100%'); ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (sizeof($order->info['tax_groups']) > 1) {
?>
                  <tr>
                    <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . xtc_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
 <?php
  if ($_SESSION['customers_status']['customers_status_show_price_tax']== 0 && $_SESSION['customers_status']['customers_status_add_tax_ot']==1) {                   

?>				<td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
<?php
}
?>                    
					<td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                  </tr>
<?php
  } else {
?>
                  <tr>
                    <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . xtc_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
                  </tr>
<?php
  }

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td class="main" nowrap align="left" valign="top" width="">' . $order->products[$i]['qty'] .' x '.$order->products[$i]['name']. '</td>' . "\n" .
        // '            <td class="main" valign="top" align="left">' . $order->products[$i]['name'].'</td>';
	 '				<td class="main" align="right" valign="top">' .xtc_get_products_price($order->products[$i]['id'],$price_special=1,$quantity=$order->products[$i]['qty']). '</td></tr>' . "\n" ;

    if (STOCK_CHECK == 'true') {
      echo xtc_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
    }

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<tr>
		<td class="main" align="left" valign="top">
		<nobr><small>&nbsp;<i> - ' 
		. $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] .'
		</i></small></td>
		<td class="main" align="right" valign="top"><i><small>'
		.xtc_get_products_attribute_price_checkout($order->products[$i]['attributes'][$j]['price'],$order->products[$i]['tax'],1,$order->products[$i]['qty'],$order->products[$i]['attributes'][$j]['prefix']). 
		'</i></small></nobr></td></tr>';
      }
    }

    echo '' . "\n";

    if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 && $_SESSION['customers_status']['customers_status_add_tax_ot'] == 1) {
      if (sizeof($order->info['tax_groups']) > 1) echo '            <td class="main" valign="top" align="right">' . xtc_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
    }  
	 echo    '          </tr>' . "\n";
  }
?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_BILLING_ADDRESS . '</b> <a href="' . xtc_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo xtc_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b> <a href="' . xtc_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php include(DIR_WS_LANGUAGES . '/' . $_SESSION['language'] . '/modules/payment/' . $order->info['payment_method'] . '.php'); echo constant(MODULE_PAYMENT_ . strtoupper($order->info['payment_method']) . _TEXT_TITLE); ?></td>
              </tr>
            </table></td>
            <td width="70%" valign="top" align="right"><table border="0" cellspacing="0" cellpadding="2">
<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    echo $order_total_modules->output();
  }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
?>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
              </tr>
<?php
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
?>
              <tr>
                <td width="10"><?php echo xtc_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
                <td width="10"><?php echo xtc_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo stripslashes($confirmation['fields'][$i]['field']); ?></td>
              </tr>
<?php
      }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
  }
?>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if (xtc_not_null($order->info['comments'])) {
?>
      <tr>
        <td class="main"><?php echo '<b>' . HEADING_ORDER_COMMENTS . '</b> <a href="' . xtc_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo nl2br(htmlspecialchars($order->info['comments'])) . xtc_draw_hidden_field('comments', $order->info['comments']); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php 	  
    //check if display conditions on checkout page is true
    if (DISPLAY_CONDITIONS_ON_CHECKOUT == 'true') {
?>
      <tr>
        <td class="main"><b><?php echo HEADING_CONDITIONS_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" colspan="4"><?php echo HEADING_CONDITIONS_TEXT; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    }
?>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" class="main"><?php
  if (isset($$_SESSION['payment']->form_action_url)) {
    $form_action_url = $$_SESSION['payment']->form_action_url;
  } else {
    $form_action_url = xtc_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }

  echo xtc_draw_form('checkout_confirmation', $form_action_url, 'post');

  if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button();
  }

  echo xtc_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '</form>' . "\n";
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo xtc_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo xtc_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><?php echo xtc_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo xtc_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td><?php echo xtc_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
                <td width="50%"><?php echo xtc_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo xtc_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo xtc_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . xtc_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . xtc_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_PAYMENT . '</a>'; ?></td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
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