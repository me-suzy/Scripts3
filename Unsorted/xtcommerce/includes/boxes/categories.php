<?php
/* -----------------------------------------------------------------------------------------
   $Id: categories.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.23 2002/11/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (categories.php,v 1.10 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_show_category.inc.php');
  require_once(DIR_FS_INC . 'xtc_has_category_subcategories.inc.php');
  require_once(DIR_FS_INC . 'xtc_count_products_in_category.inc.php');
?>
<!-- categories //-->
          <tr>
            <td><?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_CATEGORIES);

  new infoBoxHeading($info_box_contents, true, false);

  $categories_string = '';

  $categories_query = xtc_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . $_SESSION['languages_id'] ."' order by sort_order, cd.categories_name");
  while ($categories = xtc_db_fetch_array($categories_query))  {
    $foo[$categories['categories_id']] = array(
                                        'name' => $categories['categories_name'],
                                        'parent' => $categories['parent_id'],
                                        'level' => 0,
                                        'path' => $categories['categories_id'],
                                        'next_id' => false);

    if (isset($prev_id)) {
      $foo[$prev_id]['next_id'] = $categories['categories_id'];
    }

    $prev_id = $categories['categories_id'];

    if (!isset($first_element)) {
      $first_element = $categories['categories_id'];
    }
  }

  //------------------------
  if ($cPath) {
    $new_path = '';
    $id = split('_', $cPath);
    reset($id);
    while (list($key, $value) = each($id)) {
      unset($prev_id);
      unset($first_id);
      $categories_query = xtc_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '" . $value . "' and c.categories_id = cd.categories_id and cd.language_id='" . $_SESSION['languages_id'] ."' order by sort_order, cd.categories_name");
      $category_check = xtc_db_num_rows($categories_query);
      if ($category_check > 0) {
        $new_path .= $value;
        while ($row = xtc_db_fetch_array($categories_query)) {
          $foo[$row['categories_id']] = array(
                                              'name' => $row['categories_name'],
                                              'parent' => $row['parent_id'],
                                              'level' => $key+1,
                                              'path' => $new_path . '_' . $row['categories_id'],
                                              'next_id' => false);

          if (isset($prev_id)) {
            $foo[$prev_id]['next_id'] = $row['categories_id'];
          }

          $prev_id = $row['categories_id'];

          if (!isset($first_id)) {
            $first_id = $row['categories_id'];
          }

          $last_id = $row['categories_id'];
        }
        $foo[$last_id]['next_id'] = $foo[$value]['next_id'];
        $foo[$value]['next_id'] = $first_id;
        $new_path .= '_';
      } else {
        break;
      }
    }
  }
  xtc_show_category($first_element); 

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $categories_string);

  new infoBox($info_box_contents);
?></td>
          </tr>
<!-- categories_eof //-->