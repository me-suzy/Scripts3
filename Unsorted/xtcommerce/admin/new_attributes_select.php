<?php
/* --------------------------------------------------------------
   $Id: new_attributes_select.php,v 1.1 2003/09/06 22:05:29 fanta2k Exp $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(new_attributes_select.php); www.oscommerce.com 
   (c) 2003	 nextcommerce (new_attributes_select.php,v 1.9 2003/08/21); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contributions:
   New Attribute Manager v4b				Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/ 
?>
  <tr>
    <td class="pageHeading" colspan="3"><?php echo $pageTitle; ?></td>
  </tr>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="SELECT_PRODUCT" method="post"><input type="hidden" name="action" value="edit">
<?php
  echo "<TR>";
  echo "<TD class=\"main\"><BR><B>Please select a product to edit:<BR></TD>";
  echo "</TR>";
  echo "<TR>";
  echo "<TD class=\"main\"><SELECT NAME=\"current_product_id\">";

  $query = "SELECT * FROM products_description where products_id LIKE '%' AND language_id = '" . $_SESSION['languages_id'] . "' ORDER BY products_name ASC";

  $result = mysql_query($query) or die(mysql_error());

  $matches = mysql_num_rows($result);

  if ($matches) {
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $title = $line['products_name'];
      $current_product_id = $line['products_id'];

      echo "<OPTION VALUE=\"" . $current_product_id . "\">" . $title;
    }
  } else {
    echo "You have no products at this time.";
  }

  echo "</SELECT>";
  echo "</TD></TR>";

  echo "<TR>";
  echo "<TD class=\"main\"><input type=\"image\" src=\"" . $adminImages . "button_edit.gif\"></TD>";
  echo "</TR>";
?>
</form>