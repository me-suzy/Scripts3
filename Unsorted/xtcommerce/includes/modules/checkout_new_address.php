<?php
/* -----------------------------------------------------------------------------------------
   $Id: checkout_new_address.php,v 1.1 2003/09/06 22:13:54 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_new_address.php,v 1.3 2003/05/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (checkout_new_address.php,v 1.8 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_draw_radio_field.inc.php');
  require_once(DIR_FS_INC . 'xtc_get_country_list.inc.php');

  if (!isset($process)) $process = false;
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') {
    $male = ($gender == 'm') ? true : false;
    $female = ($gender == 'f') ? true : false;
?>
  <tr>
    <td class="main"><?php echo ENTRY_GENDER; ?></td>
    <td class="main"><?php echo xtc_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . xtc_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (xtc_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
  </tr>
<?php
  }
?>
  <tr>
    <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
    <td class="main"><?php echo xtc_draw_input_field('firstname') . '&nbsp;' . (xtc_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
  </tr>
  <tr>
    <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
    <td class="main"><?php echo xtc_draw_input_field('lastname') . '&nbsp;' . (xtc_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
  </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
  <tr>
    <td class="main"><?php echo ENTRY_COMPANY; ?></td>
    <td class="main"><?php echo xtc_draw_input_field('company') . '&nbsp;' . (xtc_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
  </tr>
<?php
  }
?>
  <tr>
    <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
    <td class="main"><?php echo xtc_draw_input_field('street_address') . '&nbsp;' . (xtc_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
  </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
  <tr>
    <td class="main"><?php echo ENTRY_SUBURB; ?></td>
    <td class="main"><?php echo xtc_draw_input_field('suburb') . '&nbsp;' . (xtc_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
  </tr>
<?php
  }
?>
  <tr>
    <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
    <td class="main"><?php echo xtc_draw_input_field('postcode') . '&nbsp;' . (xtc_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
  </tr>
  <tr>
    <td class="main"><?php echo ENTRY_CITY; ?></td>
    <td class="main"><?php echo xtc_draw_input_field('city') . '&nbsp;' . (xtc_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
  </tr>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
  <tr>
    <td class="main"><?php echo ENTRY_STATE; ?></td>
    <td class="main">
<?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_query = xtc_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . xtc_db_input($country) . "' order by zone_name");
        while ($zones_values = xtc_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo xtc_draw_pull_down_menu('state', $zones_array);
      } else {
        echo xtc_draw_input_field('state');
      }
    } else {
      echo xtc_draw_input_field('state');
    }

    if (xtc_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;
?>
    </td>
  </tr>
<?php
  }
?>
  <tr>
    <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
    <td class="main"><?php echo xtc_get_country_list('country') . '&nbsp;' . (xtc_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>
  </tr>
</table>
