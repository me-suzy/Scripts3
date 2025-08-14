<?php
/* -----------------------------------------------------------------------------------------
   $Id: infobox.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (infobox.php,v 1.7 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Loginbox V1.0        	Aubrey Kilian <aubrey@mycon.co.za>

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
?>
<!-- infonbox //-->
          <tr>
            <td><?php
  if ($_SESSION['customers_status']['customers_status_image']!='') {
    $loginboxcontent = '<center>' . xtc_image('admin/images/icons/' . $_SESSION['customers_status']['customers_status_image']) . '</center>';
  }
  $loginboxcontent .= BOX_LOGINBOX_STATUS . '<b>' . $_SESSION['customers_status']['customers_status_name'] . '</b><br>';
  if ($_SESSION['customers_status']['customers_status_show_price'] == 0) {
    $loginboxcontent .= NOT_ALLOWED_TO_SEE_PRICES_TEXT;
  } else  {
    if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 1) {
      $loginboxcontent .= BOX_LOGINBOX_INCL . '<br>';
    } else {
      $loginboxcontent .= BOX_LOGINBOX_EXCL . '<br>';
    }
    if ($_SESSION['customers_status']['customers_status_discount'] != '0.00') {
      $loginboxcontent.=BOX_LOGINBOX_DISCOUNT . ' ' . $_SESSION['customers_status']['customers_status_discount'] . '%<br>';
    }
    if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == 1  && $_SESSION['customers_status']['customers_status_ot_discount'] != '0.00') {
      $loginboxcontent .= BOX_LOGINBOX_DISCOUNT_TEXT . ' '  . $_SESSION['customers_status']['customers_status_ot_discount'] . ' % ' . BOX_LOGINBOX_DISCOUNT_OT . '<br>';
    }
  }

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_ACCOUNTINFORMATION_HEADING);
  new infoBoxHeading($info_box_contents, false, false);
	
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                 'text'  => $loginboxcontent
                                );
  new infoBox($info_box_contents);
?></td>
          </tr>
<!-- infobox_eof //-->
