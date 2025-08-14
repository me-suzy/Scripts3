<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_info.php,v 1.1 2003/09/06 21:38:27 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_info.php,v 1.94 2003/05/04); www.oscommerce.com 
   (c) 2003	 nextcommerce (product_info.php,v 1.46 2003/08/25); www.nextcommerce.org

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contribution:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist
   New Attribute Manager v4b				Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com   
   Cross-Sell (X-Sell) Admin 1				Autor: Joshua Dechant (dreamscape)
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
require_once(DIR_FS_INC.'xtc_get_download.inc.php');
require_once(DIR_FS_INC.'xtc_delete_file.inc.php');
if ($_GET['action']=='get_download') {
xtc_get_download($_GET['cID']);	
	
}  
  // include needed functions
  require_once(DIR_FS_INC . 'xtc_get_all_get_params.inc.php');
  require_once(DIR_FS_INC . 'xtc_date_long.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'xtc_image_button.inc.php');
  require_once(DIR_FS_INC . 'xtc_get_products_attribute_price.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_form.inc.php');
  require_once(DIR_FS_INC . 'xtc_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_input_field.inc.php');
  require_once(DIR_FS_INC . 'xtc_image_submit.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_input_field.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_input_field.inc.php');

  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_PRODUCT_INFO);
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php include(DIR_WS_MODULES.FILENAME_METATAGS_PRODUCTS_INFO); ?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
    <td class="tableBody" width="100%" valign="top"><?php echo xtc_draw_form('cart_quantity', xtc_href_link(FILENAME_PRODUCT_INFO, xtc_get_all_get_params(array('action')) . 'action=add_product')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  $product_info_query = xtc_db_query("select p.products_discount_allowed,p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . $_SESSION['languages_id'] . "'");
  if (!xtc_db_num_rows($product_info_query)) { // product not found in database
?>
      <tr>
        <td class="main"><br><?php echo TEXT_PRODUCT_NOT_FOUND; ?></td>
      </tr>
      <tr>
        <td align="right"><br><a href="<?php echo xtc_href_link(FILENAME_DEFAULT); ?>"><?php echo xtc_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a></td>
      </tr>
<?php
  } else {
    xtc_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['products_id'] . "' and language_id = '" . $_SESSION['languages_id'] . "'");
    $product_info = xtc_db_fetch_array($product_info_query);

    $products_price=xtc_get_products_price($product_info['products_id'], $price_special=1, $quantity=1); 
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr height="40">
            <td class="pageHeading"><?php echo $product_info['products_name']; ?></td>
            <td align="right" class="pageHeading"><?php echo $products_price; ?><br><small><?php
    if ($_SESSION['customers_status']['customers_status_public'] == 1 && $_SESSION['customers_status']['customers_status_discount'] != '0.00') {
      $discount = $_SESSION['customers_status']['customers_status_discount'];
      if ($product_info['products_discount_allowed'] < $_SESSION['customers_status']['customers_status_discount']) $discount = $product_info['products_discount_allowed'];
      if ($discount != '0.00' ) echo 'Sie erhalten ' . $discount . '% Rabatt';
    }
?></small></td>
          </tr>
          <tr>
            <td class="smalltext">
            
 <?php 
 echo  TEXT_PRODUCT_PRINT; ?><img src="<?php echo DIR_WS_ICONS; ?>print.gif"  style="cursor:hand" onClick="window.open('<?php echo xtc_href_link(FILENAME_PRINT_PRODUCT_INFO,'products_id='.$_GET['products_id']); ?>', 'popup', 'toolbar=0, width=640, height=600')">

 
 
            
</td>
          </tr>
<?php
    if (PRODUCT_LIST_MODEL > 0) {
      echo '          <tr>' . "\n" .
           '            <td colspan="2" class="pageHeading">' . $product_info['products_model'] . '</td>' . "\n" .
           '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2" align="right">
<?php
    if (xtc_not_null($product_info['products_image'])) {
?>
          <tr>
            <td align="center" class="smallText"><script language="javascript"><!--
document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . xtc_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '\\\')">' . xtc_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
//--></script>
<noscript>
<?php echo '<a href="' . xtc_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '">' . xtc_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
</noscript>            </td>
          </tr>
<?php
    }
?>
        </table><p><?php echo stripslashes($product_info['products_description']); ?></p>
<?php
    $products_attributes_query = xtc_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $_SESSION['languages_id'] . "'");
    $products_attributes = xtc_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
      echo '<b>' . TEXT_PRODUCT_OPTIONS . '</b><br><table border="0" cellpadding="0" cellspacing"0">';
      $products_options_name_query = xtc_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $_SESSION['languages_id'] . "' order by popt.products_options_name");
      while ($products_options_name = xtc_db_fetch_array($products_options_name_query)) {
        $selected = 0;
        $products_options_array = array();
        echo '<tr><td class="main">' . $products_options_name['products_options_name'] . ':</td><td>' . "\n";
        $products_options_query = xtc_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix,pa.attributes_stock, pa.attributes_model from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['products_id'] . "' and pa.options_id = '" . $products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $_SESSION['languages_id'] . "' order by pov.products_options_values_name");
        while ($products_options = xtc_db_fetch_array($products_options_query)) {
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .=  ' '.$products_options['price_prefix'].' '.xtc_get_products_attribute_price($products_options['options_values_price'], $tax_class=$product_info['products_tax_class_id'],$price_special=0,$quantity=1,$prefix=$products_options['price_prefix']).' '.$_SESSION['currency'] ;
          }
        }
        echo xtc_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $_SESSION['cart']->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']]);
        echo '</td></tr>';
      }
      echo '</table>';
    }
?></td>
      </tr>
<?php
    if ($_SESSION['customers_status']['customers_status_graduated_prices'] == 1) {
?>
      <tr>
        <td class="main"><?php include(DIR_WS_MODULES.FILENAME_GRADUATED_PRICE); ?></td>
      </tr>
<?php
    }

    $reviews_query = xtc_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . $_GET['products_id'] . "'");
    $reviews = xtc_db_fetch_array($reviews_query);
    if ($reviews['count'] > 0) {
?>
      <tr>
        <td class="main"><br><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>
      </tr>
<?php
    }

    if (xtc_not_null($product_info['products_url'])) {
?>
      <tr>
        <td class="main"><br><?php echo sprintf(TEXT_MORE_INFORMATION, xtc_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)); ?></td>
      </tr>
<?php
    }

    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
?>
      <tr>
        <td align="center" class="smallText"><br><?php echo sprintf(TEXT_DATE_AVAILABLE, xtc_date_long($product_info['products_date_available'])); ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="center" class="smallText"><br><?php echo sprintf(TEXT_DATE_ADDED, xtc_date_long($product_info['products_date_added'])); ?></td>
      </tr>
<?php
    }
//    if (isset($cPath) && xtc_not_null($cPath)) { echo $cPath;}  
?>
<tr>
<td>
<?php include(DIR_WS_MODULES . FILENAME_PRODUCTS_MEDIA); ?>
</td></tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><a href="<?php echo xtc_href_link(FILENAME_PRODUCT_REVIEWS, substr(xtc_get_all_get_params(), 0, -1)); ?>"><?php echo xtc_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS); ?></a></td>
            <td align="right" class="main"><?php 
    if (ALLOW_ADD_TO_CART == 'false' && $_SESSION['customers_status']['customers_status_show_price'] != '1') {
      echo NOT_ALLOWED_TO_ADD_TO_CART; 
    } else {
      echo xtc_draw_input_field('products_qty', '1','size="3"') . ' ' . xtc_draw_hidden_field('products_id', $product_info['products_id']) . xtc_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART); 
    }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><?php

      include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS); 
     
    include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
  }
?></td>
      </tr>
    </table></form></td>
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