<?php
/* -----------------------------------------------------------------------------------------
   $Id: password_forgotten.php,v 1.1 2003/09/06 21:38:27 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(password_forgotten.php,v 1.49 2003/05/28); www.oscommerce.com 
   (c) 2003	 nextcommerce (password_forgotten.php,v 1.16 2003/08/24); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');

  // include needed functions
  require_once(DIR_FS_INC . 'xtc_image_button.inc.php');
  require_once(DIR_FS_INC . 'xtc_encrypt_password.inc.php');
  require_once(DIR_WS_CLASSES.'class.phpmailer.php');
  require_once(DIR_FS_INC . 'xtc_php_mail.inc.php');

  // initialize smarty
  $smarty = new Smarty;
  
  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_PASSWORD_FORGOTTEN);

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $check_customer_query = xtc_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $_POST['email_address'] . "'");
    if (xtc_db_num_rows($check_customer_query)) {
      $check_customer = xtc_db_fetch_array($check_customer_query);
      // Crypted password mods - create a new password, update the database and mail it to them
      $newpass = xtc_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
      $crypted_password = xtc_encrypt_password($newpass);
      
      xtc_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . $crypted_password . "' where customers_id = '" . $check_customer['customers_id'] . "'");
      
      	// assign language to template for caching
  	$smarty->assign('language', $_SESSION['language']);	
  	$smarty->assign('tpl_path',DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/');
      
      	// assign vars
      	$smarty->assign('EMAIL',$_POST['email_address']);
      	$smarty->assign('PASSWORD',$newpass);
      	$smarty->assign('FIRSTNAME',$check_customer['customers_firstname']);
      	$smarty->assign('LASTNAME',$check_customer['customers_lastname']);
      	// dont allow cache
  	$smarty->caching = false;
  	
  	// create mails
 	$html_mail=$smarty->fetch(CURRENT_TEMPLATE.'/mail/change_password_mail.html');
  	$txt_mail=$smarty->fetch(CURRENT_TEMPLATE.'/mail/change_password_mail.txt');
      
      xtc_php_mail(EMAIL_SUPPORT_ADDRESS,EMAIL_SUPPORT_NAME , $_POST['email_address'], $check_customer['customers_firstname'] . " " . $check_customer['customers_lastname'], EMAIL_SUPPORT_FORWARDING_STRING, EMAIL_SUPPORT_REPLY_ADDRESS, EMAIL_SUPPORT_REPLY_ADDRESS_NAME, '', '', EMAIL_SUPPORT_SUBJECT, $html_mail, $txt_mail);    
      
      if (!isset($mail_error)) {
          xtc_redirect(xtc_href_link(FILENAME_LOGIN, 'info_message=' . urlencode(TEXT_PASSWORD_SENT), 'SSL', true, false));
      }
      else {
          echo $mail_error;
      }
    } else {
      xtc_redirect(xtc_href_link(FILENAME_PASSWORD_FORGOTTEN, 'email=nonexistent', 'SSL'));
    }
  } else {
    $breadcrumb->add(NAVBAR_TITLE_1, xtc_href_link(FILENAME_LOGIN, '', 'SSL'));
    $breadcrumb->add(NAVBAR_TITLE_2, xtc_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'));
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
<?php include(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="navLeft" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- left_navigation //-->
<?php include(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td class="tableBody" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo xtc_image(DIR_WS_IMAGES . 'table_background_password_forgotten.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_form('password_forgotten', xtc_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL')); ?><br><table border="0" width="100%" cellspacing="0" cellpadding="3">
          <tr>
            <td align="right" class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main"><?php echo xtc_draw_input_field('email_address', '', 'maxlength="96"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><br><table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td valign="top"><a href="<?php echo xtc_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . xtc_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right" valign="top"><?php echo xtc_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    if (isset($_GET['email']) && ($_GET['email'] == 'nonexistent')) {
      echo '          <tr>' . "\n";
      echo '            <td colspan="2" class="smallText">' .  TEXT_NO_EMAIL_ADDRESS_FOUND . '</td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
        </table></form></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td class="navRight" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- right_navigation //-->
<?php include(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
  }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
