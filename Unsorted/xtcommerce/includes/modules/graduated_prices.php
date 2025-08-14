<?php
/* -----------------------------------------------------------------------------------------
   $Id: graduated_prices.php,v 1.1 2003/09/06 22:13:54 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (graduated_prices.php,v 1.11 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_format_price_graduated.inc.php');

  $staffel_query = xtc_db_query("SELECT
                                     quantity, personal_offer
                                 FROM
                                     personal_offers_by_customers_status_" . $_SESSION['customers_status']['customers_status_id'] . "
                                 WHERE
                                     products_id = '" . $_GET['products_id'] . "'
                                 ORDER BY quantity ASC");
  $staffel_data = array();
  $i = '';
  while ($staffel_values = xtc_db_fetch_array($staffel_query)) {
  $i++;
  $staffel_data[$i] = array(
    'QUANTITY' => $staffel_values['quantity'],
    'PRICE' => $staffel_values['personal_offer']);
  }

  if (sizeof($staffel_data) > 1) {
?><br><br><table>
  <tr>
    <td class="main" colspan="2" style="border-bottom: 1px solid; border-color: #cccccc;">STAFFELPREISE</td>
  </tr>
<?php
    for ($col = 1, $n = sizeof($staffel_data); $col < $n+1; $col++) {
      echo '
  <tr>';
      if ($staffel_data[$col]['QUANTITY'] == 1) {
        echo '<td class="main">' . $staffel_data[$col]['QUANTITY'] . ' stk</td>';
      } else {
        $min_roducts = $staffel_data[$col-1]['QUANTITY']+1;
         echo '<td class="main">' . $min_roducts . '-' . $staffel_data[$col]['QUANTITY'] . ' stk</td>';
      }
      echo '
    <td class="main">a ' . xtc_format_price_graduated($staffel_data[$col]['PRICE'], $price_special=1, $calculate_currencies=true, $tax_class=$product_info['products_tax_class_id']) . '</td>
  </tr>
';
    }

    echo '</table>';
  }
?>