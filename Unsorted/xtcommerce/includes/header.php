<?php
/* -----------------------------------------------------------------------------------------
   $Id: header.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce 
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(header.php,v 1.40 2003/03/14); www.oscommerce.com 
   (c) 2003	 nextcommerce (header.php,v 1.13 2003/08/17); www.nextcommerce.org 

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  // include needed functions
  require_once('inc/xtc_output_warning.inc.php');
  require_once('inc/xtc_image.inc.php');
  require_once('inc/xtc_parse_input_field_data.inc.php');
  require_once('inc/xtc_draw_separator.inc.php');

//  require_once('inc/xtc_draw_form.inc.php');
//  require_once('inc/xtc_draw_pull_down_menu.inc.php');

  // check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($_SERVER['SCRIPT_FILENAME']) . '/xtc_installer')) {
      xtc_output_warning(WARNING_INSTALL_DIRECTORY_EXISTS);
    }
  }

  // check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      xtc_output_warning(WARNING_CONFIG_FILE_WRITEABLE);
    }
  }

  // check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(xtc_session_save_path())) {
        xtc_output_warning(WARNING_SESSION_DIRECTORY_NON_EXISTENT);
      } elseif (!is_writeable(xtc_session_save_path())) {
        xtc_output_warning(WARNING_SESSION_DIRECTORY_NOT_WRITEABLE);
      }
    }
  }

  // check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      xtc_output_warning(WARNING_SESSION_AUTO_START);
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      xtc_output_warning(WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT);
    }
  }
?>
<center><table width="800">
  <tr>
    <td class="tableShop"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr class="header">
        <td width="123" valign="middle"><a href="<?php echo xtc_href_link(FILENAME_DEFAULT); ?>"><?php echo xtc_image(DIR_WS_IMAGES . 'logo.gif', 'XT-Commerce'); ?></a></td>
        <td background="images/bg_top.jpg" align="left" valign="bottom"></td>
	<td background="images/bg_top.jpg" align="right" valign="bottom"><?php echo xtc_image(DIR_WS_IMAGES . 'img_line.jpg', '', '', ''); ?></td>
      </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="1">
      <tr class="headerNavigation">
        <td class="headerNavigation">&nbsp;&nbsp;<?php echo $breadcrumb->trail(' &raquo; '); ?></td>
        <td align="right" class="headerNavigation"><?php if (isset($_SESSION['customer_id'])) { ?><a href="<?php echo xtc_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_LOGOFF; ?></a> &nbsp;|&nbsp; <?php } ?><a href="<?php echo xtc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a> &nbsp;|&nbsp; <a href="<?php echo xtc_href_link(FILENAME_SHOPPING_CART); ?>" class="headerNavigation"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a> &nbsp;|&nbsp; <a href="<?php echo xtc_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>" class="headerNavigation"><?php echo HEADER_TITLE_CHECKOUT; ?></a> &nbsp;&nbsp;</td>
      </tr>
    </table>
<?php
  if (isset($_GET['error_message']) && xtc_not_null($_GET['error_message'])) {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="headerError">
        <td class="headerError"><?php echo htmlspecialchars(urldecode($_GET['error_message'])); ?></td>
      </tr>
    </table>
<?php
  }

  if (isset($_GET['info_message']) && xtc_not_null($_GET['info_message'])) {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="headerInfo">
        <td class="headerInfo"><?php echo htmlspecialchars($_GET['info_message']); ?></td>
      </tr>
    </table>
<?php
  }
?>