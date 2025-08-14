<?php
/* -----------------------------------------------------------------------------------------
   $Id: metatags_product_info.php,v 1.1 2003/09/06 22:13:54 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2003	 nextcommerce (metatags_product_info.php,v 1.10 2003/08/14); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  $product_meta_query = xtc_db_query("select pd.products_name, pd.products_description,pd.products_short_description, p.products_model, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . $_SESSION['languages_id'] . "'");
  $product_meta = xtc_db_fetch_array($product_meta_query);
 
  $descr = ereg_replace("\.|\n|\t|;|:),", '', strip_tags($product_meta['products_description']));

  $keydata = explode(' ', $descr);
  for($i = 0; $i < count($keydata); $i++) {
    if (strlen($keydata[$i]) >= META_MIN_KEYWORD_LENGTH) {
      $meta_keywords .= $keydata[$i] . ', ';
      $key_i++;
    }
    if ($key_i >= META_KEYWORDS_NUMBER-1) break;
  }
  $meta_keywords .= $product_info_values1['products_name'];
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>">
<meta name="robots" content="<?php echo META_ROBOTS; ?>">
<meta name="language" content="<?php echo $language; ?>">
<meta name="author" content="<?php echo META_AUTHOR; ?>">
<?php
/*
  The following copyright announcement is in compliance
  to section 2c of the GNU General Public License, and
  thus can not be removed, or can only be modified
  appropriately.

  Please leave this comment intact together with the
  following copyright announcement.
  
*/
?>
<meta name="generator" content="(c) by <?php echo PROJECT_VERSION; ?> , http://www.xt-commerce.com">

<meta name="publisher" content="<?php echo META_PUBLISHER; ?>">
<meta name="company" content="<?php echo META_COMPANY; ?>">
<meta name="page-topic" content="<?php echo META_TOPIC; ?>">
<meta name="reply-to" content="<?php echo META_REPLY_TO; ?>">
<meta name="distribution" content="global">
<meta name="revisit-after" content="<?php echo META_REVISIT_AFTER; ?>">
<META NAME="description" CONTENT="<?php echo substr($product_meta['products_description'], 0, META_DESCRIPTION_LENGTH);?>">
<META NAME="keywords" CONTENT="<?php echo $meta_keywords;?>">
<title><?php echo TITLE.' - '.$product_meta['products_name'].' '.$product_meta['products_model']; ?></title>
