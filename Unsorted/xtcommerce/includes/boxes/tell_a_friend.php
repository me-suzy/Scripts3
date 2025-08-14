<?php
/* -----------------------------------------------------------------------------------------
   $Id: tell_a_friend.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(tell_a_friend.php,v 1.15 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (tell_a_friend.php,v 1.9 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_draw_form.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_input_field.inc.php');
  require_once(DIR_FS_INC . 'xtc_image_submit.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'xtc_hide_session_id.inc.php');
?>
<!-- tell_a_friend //-->
          <tr>
            <td><?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_TELL_A_FRIEND);

  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('form' => xtc_draw_form('tell_a_friend', xtc_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false), 'get'),
                               'align' => 'center',
                               'text' => xtc_draw_input_field('send_to', '', 'size="10"') . '&nbsp;' . xtc_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) . xtc_draw_hidden_field('products_id', $_GET['products_id']) . xtc_hide_session_id() . '<br>' . BOX_TELL_A_FRIEND_TEXT);

  new infoBox($info_box_contents);
?></td>
          </tr>
<!-- tell_a_friend_eof //-->
