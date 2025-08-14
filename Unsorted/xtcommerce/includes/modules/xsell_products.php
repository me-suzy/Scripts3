<?php
/* -----------------------------------------------------------------------------------------
   $Id: xsell_products.php,v 1.1 2003/09/06 22:13:54 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (xsell_products.php,v 1.5 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contribution:
   Cross-Sell (X-Sell) Admin 1				Autor: Joshua Dechant (dreamscape)
   
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/ 

  if ($HTTP_GET_VARS['products_id']) { 
    $xsell_query = xtc_db_query("select distinct p.products_id, p.products_image, pd.products_name from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' order by xp.products_id asc limit " . MAX_DISPLAY_ALSO_PURCHASED); 
    $num_products_xsell = xtc_db_num_rows($xsell_query); 
    if ($num_products_xsell >= MIN_DISPLAY_ALSO_PURCHASED) { 
?> 
<!-- xsell_products //-->
<?php
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_XSELL_PRODUCTS);
      new contentBoxHeading($info_box_contents);

      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($xsell = xtc_db_fetch_array($xsell_query)) {
        $xsell['products_name'] = xtc_get_products_name($xsell['products_id']);
        $info_box_contents[$row][$col] = array('align' => 'center',
                                               'params' => 'class="smallText" width="33%" valign="top"',
                                               'text' => '<a href="' . xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . xtc_image(DIR_WS_IMAGES . $xsell['products_image'], $xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . xtc_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . $xsell['products_name'] . '</a>');
        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }
      new contentBox($info_box_contents);
?>
<!-- xsell_products_eof //-->
<?php
    }
  }
?>