<?php
/* --------------------------------------------------------------
   $Id: new_attributes.php,v 1.1 2003/09/06 22:05:29 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(new_attributes); www.oscommerce.com 
   (c) 2003	 nextcommerce (new_attributes.php,v 1.13 2003/08/21); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contributions:
   New Attribute Manager v4b				Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/ 

  $adminImages = "includes/languages/english/images/buttons/";
  $backLink = "<a href=\"javascript:history.back()\">";

  require('new_attributes_config.php');
  require('includes/application_top.php');

  if ( isset($cPathID) && $_POST['action'] == 'change') {
    include('new_attributes_change.php');

    xtc_redirect( './' . FILENAME_CATEGORIES . '?cPath=' . $cPathID . '&pID=' . $_POST['current_product_id'] );
  }

  function findTitle($current_pid, $languageFilter) {
    $query = "SELECT * FROM products_description where language_id = '" . $_SESSION['languages_id'] . "' AND products_id = '" . $current_pid . "'";

    $result = mysql_query($query) or die(mysql_error());

    $matches = mysql_num_rows($result);

    if ($matches) {
      while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $productName = $line['products_name'];
      }
      return $productName;
    } else {
      return "Something isn't right....";
    }
  }

  function attribRedirect($c_Path) {
    return '<SCRIPT LANGUAGE="JavaScript"> window.location="./configure.php?cPath=' . $c_Path . '"; </script>';
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="<?php echo $_SESSION['language_charset']; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
     <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>

<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  switch($_POST['action']) {
    case 'edit':
      $pageTitle = 'Edit Attributes -> ' . findTitle($_POST['current_product_id'], $languageFilter);
      include('new_attributes_include.php');
      break;

    case 'change':
      $pageTitle = 'Product Attributes Updated.';
      include('new_attributes_change.php');
      include('new_attributes_select.php');
      break;

    default:
      $pageTitle = 'Edit Attributes';
      include('new_attributes_select.php');
      break;
  }
?>
    </table></td>
  </tr>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>