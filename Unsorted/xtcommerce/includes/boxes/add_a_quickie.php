<?php
/* -----------------------------------------------------------------------------------------
   $Id: add_a_quickie.php,v 1.1 2003/09/07 10:38:25 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(add_a_quickie.php,v 1.10 2001/12/19); www.oscommerce.com 

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contribution:
   Add A Quickie v1.0 Autor  Harald Ponce de Leon
    
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
?>
<!-- add_a_quickie //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_ADD_PRODUCT_ID
                              );
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('form' => '<form name="quick_add" method="post" action="' . xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('action')) . 'action=add_a_quickie', 'NONSSL') . '">',
                               'align' => 'left',
                               'text'  => '<div align="center"><input type="text" name="quickie" size="10">&nbsp;' . xtc_image_submit('button_add_quick.gif', BOX_HEADING_ADD_PRODUCT_ID) . '</div>' . BOX_ADD_PRODUCT_ID_TEXT
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- add_a_quickie_eof //-->
