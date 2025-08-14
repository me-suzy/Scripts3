<?php
/* -----------------------------------------------------------------------------------------
   $Id: reviews.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(reviews.php,v 1.36 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (reviews.php,v 1.9 2003/08/17 22:40:08); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_random_select.inc.php');
  require_once(DIR_FS_INC . 'xtc_break_string.inc.php');
?>
<!-- reviews //-->
          <tr>
            <td><?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_REVIEWS);

  new infoBoxHeading($info_box_contents, false, false, xtc_href_link(FILENAME_REVIEWS));

  $random_select = "select r.reviews_id, r.reviews_rating, p.products_id, p.products_image, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and rd.languages_id = '" . $_SESSION['languages_id'] . "' and p.products_id = pd.products_id and pd.language_id = '" . $_SESSION['languages_id'] . "'";
  if (isset($_GET['products_id'])) {
    $random_select .= " and p.products_id = '" . (int)$_GET['products_id'] . "'";
  }
  $random_select .= " order by r.reviews_id desc limit " . MAX_RANDOM_SELECT_REVIEWS;
  $random_product = xtc_random_select($random_select);

  $info_box_contents = array();

  if ($random_product) {
    // display random review box
    $review_query = xtc_db_query("select substring(reviews_text, 1, 60) as reviews_text from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . $random_product['reviews_id'] . "' and languages_id = '" . $_SESSION['languages_id'] . "'");
    $review = xtc_db_fetch_array($review_query);

    $review = htmlspecialchars($review['reviews_text']);
    $review = xtc_break_string($review, 15, '-<br>');

    $info_box_contents[] = array('text' => '<div align="center"><a href="' . xtc_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . xtc_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div><a href="' . xtc_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . $review . ' ..</a><br><div align="center">' . xtc_image(DIR_WS_IMAGES . 'stars_' . $random_product['reviews_rating'] . '.gif' , sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $random_product['reviews_rating'])) . '</div>');
  } elseif (isset($_GET['products_id'])) {
    // display 'write a review' box
    $info_box_contents[] = array('text' => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBoxContents"><a href="' . xtc_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $_GET['products_id']) . '">' . xtc_image(DIR_WS_IMAGES . 'box_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a></td><td class="infoBoxContents"><a href="' . xtc_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $_GET['products_id']) . '">' . BOX_REVIEWS_WRITE_REVIEW .'</a></td></tr></table>');
  } else {
    // display 'no reviews' box
    $info_box_contents[] = array('text' => BOX_REVIEWS_NO_REVIEWS);
  }

  new infoBox($info_box_contents);
?></td>
          </tr>
<!-- reviews_eof //-->
