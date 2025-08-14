<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_reviews_info.php,v 1.1 2003/09/06 21:38:27 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_reviews_info.php,v 1.47 2003/02/13); www.oscommerce.com
   (c) 2003	 nextcommerce (product_reviews_info.php,v 1.12 2003/08/17); www.nextcommerce.org 

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
  
  // include needed functions
  require_once(DIR_FS_INC . 'xtc_break_string.inc.php');
  require_once(DIR_FS_INC . 'xtc_date_long.inc.php');
  require_once(DIR_FS_INC . 'xtc_image_button.inc.php');

  // lets retrieve all $HTTP_GET_VARS keys and values..
  $get_params = xtc_get_all_get_params(array('reviews_id'));
  $get_params = substr($get_params, 0, -1); //remove trailing &

  $reviews_query = xtc_db_query("select rd.reviews_text, r.reviews_rating, r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, p.products_id, pd.products_name, p.products_image from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd left join " . TABLE_PRODUCTS . " p on (r.products_id = p.products_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (p.products_id = pd.products_id and pd.language_id = '". $_SESSION['languages_id'] . "') where r.reviews_id = '" . (int)$_GET['reviews_id'] . "' and r.reviews_id = rd.reviews_id and p.products_status = '1'");
  if (!xtc_db_num_rows($reviews_query)) xtc_redirect(xtc_href_link(FILENAME_REVIEWS));
  $reviews = xtc_db_fetch_array($reviews_query);

  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_PRODUCT_REVIEWS_INFO);

  $breadcrumb->add(NAVBAR_TITLE, xtc_href_link(FILENAME_PRODUCT_REVIEWS, $get_params));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php include(DIR_WS_MODULES.FILENAME_METATAGS); ?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="navLeft" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td class="tableBody" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  xtc_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . $reviews['reviews_id'] . "'");

  $reviews_text = xtc_break_string(htmlspecialchars($reviews['reviews_text']), 60, '-<br>');
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(HEADING_TITLE, $reviews['products_name']); ?></td>
            <td class="pageHeading" align="right"><?php echo xtc_image(DIR_WS_IMAGES . 'table_background_reviews.gif', sprintf(HEADING_TITLE, $reviews['products_name']), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><b><?php echo SUB_TITLE_PRODUCT; ?></b> <?php echo $reviews['products_name']; ?></td>
            <td class="smallText" rowspan="3" align="center"><a href="javascript:popupImageWindow('<?php echo xtc_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $reviews['products_id']); ?>')"><?php echo xtc_image(DIR_WS_IMAGES . $reviews['products_image'], $reviews['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); ?><br></a></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo SUB_TITLE_FROM; ?></b> <?php echo $reviews['customers_name']; ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo SUB_TITLE_DATE; ?></b> <?php echo xtc_date_long($reviews['date_added']); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo SUB_TITLE_REVIEW; ?></b></td>
      </tr>
      <tr>
        <td class="main"><br><?php echo nl2br($reviews_text); ?></td>
      </tr>
      <tr>
        <td class="main"><br><b><?php echo SUB_TITLE_RATING; ?></b> <?php echo xtc_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])); ?> <small>[<?php echo sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating']); ?>]</small></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><?php echo '<a href="' . xtc_href_link(FILENAME_PRODUCT_REVIEWS, $get_params) . '">' . xtc_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td align="right" class="main"><?php echo '<a href="' . xtc_href_link(FILENAME_DEFAULT, 'action=buy_now&products_id=' . $reviews['products_id']) . '">' . xtc_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART); ?></a></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td class="navRight" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>