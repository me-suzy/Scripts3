<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_listing.php,v 1.1 2003/09/06 22:13:54 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_listing.php,v 1.42 2003/05/27); www.oscommerce.com 
   (c) 2003	 nextcommerce (product_listing.php,v 1.19 2003/08/1); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'xtc_get_all_get_params.inc.php');
  require_once(DIR_FS_INC . 'xtc_image_button.inc.php');

  $listing_split = new splitPageResults($listing_sql, $_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = xtc_db_query($listing_split->sql_query);
    while ($listing = xtc_db_fetch_array($listing_query)) {
      $rows++;
      echo '
<table width="100%" border="0">
  <tr>
    <td colspan="2"class="main"><font size="2"><a href="' . xtc_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id'], 'NONSSL') . '"><b>' . $listing['products_name'] . '</b></a></font></td>
  </tr>
  <tr>
    <td width="150" class="main"><a href="' . xtc_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . xtc_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'],SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT)  . '</a></td>
    <td class="main">' . substr($listing['products_short_description'], 0, 200) . ' <br><a href="' . xtc_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id'], 'NONSSL') . '"><b></a></td>
  </tr>
  <tr>
    <td></td>
    <td class="main" align="right">';
      if ($_SESSION['customers_status']['customers_status_show_price'] != '0') {
        echo xtc_get_products_price($listing['products_id'], $price_special=1, $quantity=1);
      }
      echo '</td>
  </tr>
  <tr>
    <td></td>
    <td align="right">';
      if ($_SESSION['customers_status']['customers_status_show_price'] != '0') {
        echo '<a href="' . xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id'], 'NONSSL') . '">' . xtc_image_button('button_buy_now.gif', TEXT_BUY . $listing['products_name'] . TEXT_NOW);
      }
      echo '</td>
  </tr>
</table>';
      echo xtc_draw_separator();
    }
  }

  if  ($listing_split->number_of_rows > 0) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, xtc_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>