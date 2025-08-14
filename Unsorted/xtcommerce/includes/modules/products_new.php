<?php
/* -----------------------------------------------------------------------------------------
   $Id: products_new.php,v 1.1 2003/09/06 22:13:54 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(products_new.php,v 1.11 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (products_new.php,v 1.6 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (sizeof($products_new_array) < 1) {
?>
  <tr>
    <td class="main"><?php echo TEXT_NO_NEW_PRODUCTS; ?></td>
  </tr>
<?php
  } else {
    for($i = 0, $n = sizeof($products_new_array); $i < $n; $i++) {
      $products_price = xtc_get_products_price($products_new_array[$i]['id'], $price_special=1, $quantity=1);
?>
  <tr>
    <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="top" class="main"><a href="<?php echo xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new_array[$i]['id']) . '">' . xtc_image(DIR_WS_IMAGES . $products_new_array[$i]['image'], $products_new_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?></a></td>
    <td valign="top" class="main"><a href="<?php echo xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new_array[$i]['id']) . '"><b><u>' . $products_new_array[$i]['name'] . '</u></b></a><br>' . TEXT_DATE_ADDED . ' ' . $products_new_array[$i]['date_added'] . '<br>' . TEXT_MANUFACTURER . ' ' . $products_new_array[$i]['manufacturer'] . '<br><br>' . TEXT_PRICE . ' ' . $products_price; ?></td>
    <td align="right" valign="middle" class="main"><a href="<?php echo xtc_href_link(FILENAME_PRODUCTS_NEW, xtc_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new_array[$i]['id']) . '">' . xtc_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART); ?></a></td>
  </tr>
<?php
      if (($i+1) != $n) {
?>
  <tr>
    <td colspan="3" class="main">&nbsp;</td>
  </tr>
<?php
      }
    }
  }
?>
</table>
