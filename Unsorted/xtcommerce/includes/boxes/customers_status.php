<?php
/* -----------------------------------------------------------------------------------------
   $Id: customers_status.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(typical box file); www.oscommerce.com
   (c) 2003	 nextcommerce (customers_status.php,v 1.4 2003/08/13); www.nextcommerce.org 

   Released under the GNU General Public License   
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Customers Status v3.x (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
?>
<!-- customers_statusbox //-->
          <tr>
            <td><?php
  if ($customer_status_value['customers_status_public'] == 1 ) {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => BOX_HEADING_CUSTOMERS_STATUS_BOX);

    new infoBoxHeading($info_box_contents, false, false);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => TEXT_INFO_CSNAME . '<br>' . $customer_status_value['customers_status_name']);

    if ($customer_status_value['customers_status_discount'] > 0 ) {
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => TABLE_HEADING_DISCOUNT . ':' . $customer_status_value['customers_status_discount'] . '%.<br>' . SUB_TITLE_OT_DISCOUNT . $customer_status_value['customers_status_ot_discount'] . '%');
    }

    if ($customer_status_value['customers_status_show_price'] == 1) {
      if ($customer_status_value['customers_status_show_price_tax'] == 1) {
        $info_box_contents[] = array('align' => 'center',
                                     'text'  => TEXT_INFO_SHOW_PRICE_WITH_TAX_YES);
      } else {
        $info_box_contents[] = array('align' => 'center',
                                    'text'  => TEXT_INFO_SHOW_PRICE_WITH_TAX_NO);
      }
    } else {
      $info_box_contents[] = array('align' => 'center',
                                   'text'  => TEXT_INFO_SHOW_PRICE_NO);
    }
    new infoBox($info_box_contents);
  }
?></td>
          </tr>
<!-- customers_statusbox_eof //-->
