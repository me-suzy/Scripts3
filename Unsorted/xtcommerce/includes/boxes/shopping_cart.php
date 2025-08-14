<?php
/* -----------------------------------------------------------------------------------------
   $Id: shopping_cart.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce 
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(shopping_cart.php,v 1.18 2003/02/10); www.oscommerce.com
   (c) 2003	 nextcommerce (shopping_cart.php,v 1.15 2003/08/17); www.nextcommerce.org 

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include needed files
  require_once(DIR_FS_INC . 'xtc_format_price.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_separator.inc.php');
  require_once(DIR_FS_INC . 'xtc_recalculate_price.inc.php');
?>
<!-- shopping_cart //-->
          <tr>
            <td><?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_SHOPPING_CART);

  new infoBoxHeading($info_box_contents, false, true, xtc_href_link(FILENAME_SHOPPING_CART));

  $cart_contents_string = '';
  if ($_SESSION['cart']->count_contents() > 0) {
    $cart_contents_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
    $products = $_SESSION['cart']->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      $cart_contents_string .= '<tr><td align="right" valign="top" class="infoBoxContents">';

      if ((isset($_SESSION['new_products_id_in_cart'])) && ($_SESSION['new_products_id_in_cart'] == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="newItemInCart">';
      } else {
        $cart_contents_string .= '<span class="infoBoxContents">';
      }

      $cart_contents_string .= $products[$i]['quantity'] . '&nbsp;x&nbsp;</span></td><td valign="top" class="infoBoxContents"><a href="' . xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';

      if ((isset($_SESSION['new_products_id_in_cart'])) && ($_SESSION['new_products_id_in_cart'] == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="newItemInCart">';
      } else {
        $cart_contents_string .= '<span class="infoBoxContents">';
      }

      $cart_contents_string .= $products[$i]['name'] . '</span></a></td></tr>';

      if ((isset($_SESSION['new_products_id_in_cart'])) && ($_SESSION['new_products_id_in_cart'] == $products[$i]['id'])) {
        unset($_SESSION['new_products_id_in_cart']);
      }
    }
    $cart_contents_string .= '</table>';
  } else {
    $cart_contents_string .= BOX_SHOPPING_CART_EMPTY;
  }

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $cart_contents_string);

  if ($_SESSION['cart']->count_contents() > 0) {
//    $box_price_string=  xtc_format_price($_SESSION['cart']->show_total(),$price_special=1,$calculate_currencies=false);
    $total_price =xtc_format_price($_SESSION['cart']->show_total(), $price_special = 0, $calculate_currencies = false);
    if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == '1' && $_SESSION['customers_status']['customers_status_ot_discount'] != '0.00' ) {
      $box_price_string .= '<br>' . SUB_TITLE_OT_DISCOUNT . ' -' . xtc_format_price(xtc_recalculate_price($total_price, $_SESSION['customers_status']['customers_status_ot_discount']), $price_special = 1, $calculate_currencies = false)  .'<br>';
      $box_price_string .= SUB_TITLE_SUB_NEW . ' ' . xtc_format_price(($total_price), $price_special = 1, $calculate_currencies = false);
    } else {
      $box_price_string .= SUB_TITLE_SUB_NEW . ' ' . xtc_format_price(($total_price), $price_special = 1, $calculate_currencies = false);
    }

    $info_box_contents[] = array('text' => xtc_draw_separator());
    $info_box_contents[] = array('align' => 'right',
                                 'text' => $box_price_string);
  }

  new infoBox($info_box_contents);
?></td>
          </tr>
<!-- shopping_cart_eof //-->
