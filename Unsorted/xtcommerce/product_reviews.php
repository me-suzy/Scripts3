<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_reviews.php,v 1.1 2003/09/06 21:38:27 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_reviews.php,v 1.47 2003/02/13); www.oscommerce.com 
   (c) 2003	 nextcommerce (product_reviews.php,v 1.12 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
  
  // include needed functions
  require_once(DIR_FS_INC . 'xtc_image_button.inc.php');
  require_once(DIR_FS_INC . 'xtc_row_number_format.inc.php');
  require_once(DIR_FS_INC . 'xtc_date_short.inc.php');

  // lets retrieve all $HTTP_GET_VARS keys and values..
  $get_params = xtc_get_all_get_params();
  $get_params_back = xtc_get_all_get_params(array('reviews_id')); // for back button
  $get_params = substr($get_params, 0, -1); //remove trailing &
  if (xtc_not_null($get_params_back)) {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
  } else {
    $get_params_back = $get_params;
  }

  $product_info_query = xtc_db_query("select pd.products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS . " p on pd.products_id = p.products_id where pd.language_id = '" . $_SESSION['languages_id'] . "' and p.products_status = '1' and pd.products_id = '" . (int)$_GET['products_id'] . "'");
  if (!xtc_db_num_rows($product_info_query)) xtc_redirect(xtc_href_link(FILENAME_REVIEWS));
  $product_info = xtc_db_fetch_array($product_info_query);

  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_PRODUCT_REVIEWS);

  $breadcrumb->add(NAVBAR_TITLE, xtc_href_link(FILENAME_PRODUCT_REVIEWS, $get_params));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php include(DIR_WS_MODULES.FILENAME_METATAGS); ?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(HEADING_TITLE, $product_info['products_name']); ?></td>
            <td class="pageHeading" align="right"><?php echo xtc_image(DIR_WS_IMAGES . 'table_background_reviews.gif', sprintf(HEADING_TITLE, $product_info['products_name']), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading"><?php echo TABLE_HEADING_NUMBER; ?></td>
            <td class="tableHeading"><?php echo TABLE_HEADING_AUTHOR; ?></td>
            <td align="center" class="tableHeading"><?php echo TABLE_HEADING_RATING; ?></td>
            <td align="center" class="tableHeading"><?php echo TABLE_HEADING_READ; ?></td>
            <td align="right" class="tableHeading"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
          </tr>
          <tr>
            <td colspan="5"><?php echo xtc_draw_separator(); ?></td>
          </tr>
<?php
  $reviews_query = xtc_db_query("select reviews_rating, reviews_id, customers_name, date_added, last_modified, reviews_read from " . TABLE_REVIEWS . " where products_id = '" . (int)$_GET['products_id'] . "' order by reviews_id DESC");
  if (xtc_db_num_rows($reviews_query)) {
    $row = 0;
    while ($reviews = xtc_db_fetch_array($reviews_query)) {
      $row++;

      if (($row / 2) == floor($row / 2)) {
        echo '          <tr class="productReviews-even">' . "\n";
      } else {
        echo '          <tr class="productReviews-odd">' . "\n";
      }

      echo '            <td class="smallText">' . xtc_row_number_format($row) . '.</td>' . "\n" .
           '            <td class="smallText"><a href="' . xtc_href_link(FILENAME_PRODUCT_REVIEWS_INFO, $get_params . '&reviews_id=' . $reviews['reviews_id']) . '">' . $reviews['customers_name'] . '</a></td>' . "\n" .
           '            <td align="center" class="smallText">' . xtc_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</td>' . "\n" .
           '            <td align="center" class="smallText">' . $reviews['reviews_read'] . '</td>' . "\n" .
           '            <td align="right" class="smallText">' . xtc_date_short($reviews['date_added']) . '</td>' . "\n" .
           '          </tr>' . "\n";
    }
  } else {
?>
          <tr class="productReviews-odd">
            <td colspan="5" class="smallText"><?php echo TEXT_NO_REVIEWS; ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td colspan="5"><?php echo xtc_draw_separator(); ?></td>
          </tr>
          <tr>
            <td class="main" colspan="5"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<a href="' . xtc_href_link(FILENAME_PRODUCT_INFO, $get_params_back) . '">' . xtc_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right" class="main"><?php echo '<a href="' . xtc_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, $get_params) . '">' . xtc_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?></td>
              </tr>
            </table></td>
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