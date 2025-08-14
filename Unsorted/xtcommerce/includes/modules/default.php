<?php
/* -----------------------------------------------------------------------------------------
   $Id: default.php,v 1.1 2003/09/06 22:13:54 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(default.php,v 1.84 2003/05/07); www.oscommerce.com
   (c) 2003	 nextcommerce (default.php,v 1.11 2003/08/22); www.nextcommerce.org

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_customer_greeting.inc.php');  
  require_once(DIR_FS_INC . 'xtc_get_path.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_hidden_field.inc.php');  

 if ($category_depth == 'nested') {
    $category_query = xtc_db_query("select cd.categories_name, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '" . $_SESSION['languages_id'] . "'");
    $category = xtc_db_fetch_array($category_query);
?>
    <td class="tableBody" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo xtc_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
<?php
    if (isset($cPath) && ereg('_', $cPath)) {
      // check to see if there are deeper categories within the current category
      $category_links = array_reverse($cPath_array);
      for($i = 0, $n = sizeof($category_links); $i < $n; $i++) {
        $categories_query = xtc_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '" . $category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $_SESSION['languages_id'] . "' order by sort_order, cd.categories_name");
        if (xtc_db_num_rows($categories_query) < 1) {
          // do nothing, go through the loop
        } else {
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $categories_query = xtc_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $_SESSION['languages_id'] . "' order by sort_order, cd.categories_name");
    }

    $rows = 0;
    while ($categories = xtc_db_fetch_array($categories_query)) {
      $rows++;
      $cPath_new = xtc_get_path($categories['categories_id']);
      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
      echo '                <td align="center" class="smallText" style="width: ' . $width . '" valign="top"><a href="' . xtc_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . xtc_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories['categories_name'] . '</a></td>' . "\n";
      if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != xtc_db_num_rows($categories_query))) {
        echo '              </tr>' . "\n";
        echo '              <tr>' . "\n";
      }
    }
?>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><br><?php $new_products_category_id = $current_category_id; include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<?php
  } elseif ($category_depth == 'products' || $_GET['manufacturers_id']) {
    // show the products of a specified manufacturer
    if (isset($_GET['manufacturers_id'])) {
      if (isset($_GET['filter_id']) && xtc_not_null($_GET['filter_id'])) {
        // We are asked to show only a specific category
        $listing_sql = "select p.products_model, pd.products_name, m.manufacturers_name, p.products_quantity, p.products_image, p.products_weight, pd.products_short_description, pd.products_description, p.products_id, p.manufacturers_id, p.products_price, p.products_discount_allowed, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $_SESSION['languages_id'] . "' and p2c.categories_id = '" . $_GET['filter_id'] . "'";
      } else {
        // We show them all
        $listing_sql = "select p.products_model, pd.products_name, m.manufacturers_name, p.products_quantity, p.products_image, p.products_weight, pd.products_short_description, pd.products_description, p.products_id, p.manufacturers_id, p.products_price, p.products_discount_allowed, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . $_SESSION['languages_id'] . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $_GET['manufacturers_id'] . "'";
      }
    } else {
      // show the products in a given categorie
      if (isset($_GET['filter_id']) && xtc_not_null($_GET['filter_id'])) {
        // We are asked to show only specific catgeory
        $listing_sql = "select p.products_model, pd.products_name, m.manufacturers_name, p.products_quantity, p.products_image, p.products_weight, pd.products_short_description, pd.products_description, p.products_id, p.manufacturers_id, p.products_price, p.products_discount_allowed, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . $_GET['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $_SESSION['languages_id'] . "' and p2c.categories_id = '" . $current_category_id . "'";
      } else {
        // We show them all
        $listing_sql = "select p.products_model, pd.products_name, m.manufacturers_name, p.products_quantity, p.products_image, p.products_weight, pd.products_short_description, pd.products_description, p.products_id, p.manufacturers_id, p.products_price, p.products_discount_allowed, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $_SESSION['languages_id'] . "' and p2c.categories_id = '" . $current_category_id . "'";
      }
    }
?>
    <td  class="tableBody" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
<?php
    // optional Product List Filter
    if (PRODUCT_LIST_FILTER > 0) {
      if (isset($_GET['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . $_SESSION['languages_id'] . "' and p.manufacturers_id = '" . $_GET['manufacturers_id'] . "' order by cd.categories_name";
      } else {
        $filterlist_sql = "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' order by m.manufacturers_name";
      }
      $filterlist_query = xtc_db_query($filterlist_sql);
      if (xtc_db_num_rows($filterlist_query) > 1) {
        echo '            <td align="center" class="main">' . xtc_draw_form('filter', FILENAME_DEFAULT, 'GET') . TEXT_SHOW . '&nbsp;';
        if (isset($_GET['manufacturers_id'])) {
          echo xtc_draw_hidden_field('manufacturers_id', $_GET['manufacturers_id']);
          $options = array(array('text' => TEXT_ALL_CATEGORIES));
        } else {
          echo xtc_draw_hidden_field('cPath', $cPath);
          $options = array(array('text' => TEXT_ALL_MANUFACTURERS));
        }
        echo xtc_draw_hidden_field('sort', $_GET['sort']);
        while ($filterlist = xtc_db_fetch_array($filterlist_query)) {
          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
        echo xtc_draw_pull_down_menu('filter_id', $options, $_GET['filter_id'], 'onchange="this.form.submit()"');
        echo '</form></td>' . "\n";
      }
    }

    // Get the right image for the top-right
    $image = DIR_WS_IMAGES . 'table_background_list.gif';
    if (isset($_GET['manufacturers_id'])) {
      $image = xtc_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $_GET['manufacturers_id'] . "'");
      $image = xtc_db_fetch_array($image);
      $image = $image['manufacturers_image'];
    } elseif ($current_category_id) {
      $image = xtc_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . $current_category_id . "'");
      $image = xtc_db_fetch_array($image);
      $image = $image['categories_image'];
    }
?>
            <td align="right"><?php echo xtc_image(DIR_WS_IMAGES . $image, HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); ?></td>
      </tr>
    </table></td>
<?php
  } else { // default page
?>
    <td class="tableBody" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo xtc_image(DIR_WS_IMAGES . 'table_background_default.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><?php echo xtc_customer_greeting(); ?></td>
          </tr>
          <tr>
            <td class="main"><br><?php echo TEXT_MAIN; ?></td>
          </tr>
          <tr>
            <td><br><?php include(DIR_WS_INCLUDES . FILENAME_CENTER_MODULES); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<?php
  }
?>
