<?php
/* -----------------------------------------------------------------------------------------
   $Id: manufacturers.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(manufacturers.php,v 1.18 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (manufacturers.php,v 1.9 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include needed funtions
  require_once(DIR_FS_INC . 'xtc_hide_session_id.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_form.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_pull_down_menu.inc.php');
?>
<!-- manufacturers //-->
          <tr>
            <td><?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_MANUFACTURERS);

  new infoBoxHeading($info_box_contents, false, false);

  $manufacturers_query = xtc_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  if (xtc_db_num_rows($manufacturers_query) <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
    // Display a list
    $manufacturers_list = '';
    while ($manufacturers = xtc_db_fetch_array($manufacturers_query)) {
      $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
      if (isset($HTTP_GET_VARS['manufacturers_id']) && ($HTTP_GET_VARS['manufacturers_id'] == $manufacturers['manufacturers_id'])) $manufacturers_name = '<b>' . $manufacturers_name .'</b>';
      $manufacturers_list .= '<a href="' . xtc_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id']) . '">' . $manufacturers_name . '</a><br>';
    }

    $info_box_contents = array();
    $info_box_contents[] = array('text' => substr($manufacturers_list, 0, -4));
  } else {
    // Display a drop-down
    $manufacturers_array = array();
    if (MAX_MANUFACTURERS_LIST < 2) {
      $manufacturers_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
    }

    while ($manufacturers = xtc_db_fetch_array($manufacturers_query)) {
      $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'text' => $manufacturers_name);
    }

    $info_box_contents = array();
    $info_box_contents[] = array('form' => xtc_draw_form('manufacturers', xtc_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get'),
                                 'text' => xtc_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $_GET['manufacturers_id'], 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 100%"') . xtc_hide_session_id());
  }

  new infoBox($info_box_contents);
?></td>
          </tr>
<!-- manufacturers_eof //-->
