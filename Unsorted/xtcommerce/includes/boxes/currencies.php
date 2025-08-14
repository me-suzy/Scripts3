<?php
/* -----------------------------------------------------------------------------------------
   $Id: currencies.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(currencies.php,v 1.16 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (currencies.php,v 1.11 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include functions
  require_once(DIR_FS_INC . 'xtc_draw_form.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_pull_down_menu.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'xtc_hide_session_id.inc.php');
  if (isset($currencies) && is_object($currencies)) {
?>
<!-- currencies //-->
          <tr>
            <td><?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_CURRENCIES);

    new infoBoxHeading($info_box_contents, false, false);

    reset($currencies->currencies);
    $currencies_array = array();
    while (list($key, $value) = each($currencies->currencies)) {
      $currencies_array[] = array('id' => $key, 'text' => $value['title']);
    }

    $hidden_get_variables = '';
    reset($_GET);
    while (list($key, $value) = each($_GET)) {
      if ( ($key != 'currency') && ($key != xtc_session_name()) && ($key != 'x') && ($key != 'y') ) {
        $hidden_get_variables .= xtc_draw_hidden_field($key, $value);
      }
    }

    $info_box_contents = array();
    $info_box_contents[] = array('form' => xtc_draw_form('currencies', xtc_href_link(basename($PHP_SELF), '', $request_type, false), 'get'),
                                 'align' => 'center',
                                 'text' => xtc_draw_pull_down_menu('currency', $currencies_array, $_SESSION['currency'], 'onChange="this.form.submit();" style="width: 100%"') . $hidden_get_variables . xtc_hide_session_id());

    new infoBox($info_box_contents);
?></td>
          </tr>
<!-- currencies_eof //-->
<?php
  }
?>