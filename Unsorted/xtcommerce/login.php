<?php
/* -----------------------------------------------------------------------------------------
   $Id: login.php,v 1.1 2003/09/06 21:38:27 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(login.php,v 1.79 2003/05/19); www.oscommerce.com 
   (c) 2003      nextcommerce (login.php,v 1.13 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   
   guest account idea by Ingo T. <xIngox@web.de>
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
  
  // include needed functions
  require_once(DIR_FS_INC . 'xtc_draw_password_field.inc.php');
  require_once(DIR_FS_INC . 'xtc_validate_password.inc.php');
  require_once(DIR_FS_INC . 'xtc_array_to_string.inc.php');
  require_once(DIR_FS_INC . 'xtc_image_button.inc.php');

  // redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    xtc_redirect(xtc_href_link(FILENAME_COOKIE_USAGE));
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $email_address = xtc_db_prepare_input($_POST['email_address']);
    $password = xtc_db_prepare_input($_POST['password']);

    // Check if email exists
    $check_customer_query = xtc_db_query("select customers_id, customers_firstname,customers_lastname, customers_gender, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . xtc_db_input($email_address) . "'");
    if (!xtc_db_num_rows($check_customer_query)) {
      $_GET['login'] = 'fail';
    } else {
      $check_customer = xtc_db_fetch_array($check_customer_query);
      // Check that password is good
      if (!xtc_validate_password($password, $check_customer['customers_password'])) {
        $_GET['login'] = 'fail';
      } else {
        if (SESSION_RECREATE == 'True') {
          xtc_session_recreate();
        }

        $check_country_query = xtc_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $check_customer['customers_id'] . "' and address_book_id = '" . $check_customer['customers_default_address_id'] . "'");
        $check_country = xtc_db_fetch_array($check_country_query);

        $_SESSION['customer_gender'] = $check_customer['customers_gender'];
        $_SESSION['customer_last_name'] = $check_customer['customers_lastname'];
        $_SESSION['customer_id'] = $check_customer['customers_id'];
        $_SESSION['customer_default_address_id'] = $check_customer['customers_default_address_id'];
        $_SESSION['customer_first_name'] = $check_customer['customers_firstname'];
        $_SESSION['customer_country_id'] = $check_country['entry_country_id'];
        $_SESSION['customer_zone_id'] = $check_country['entry_zone_id'];

        $date_now = date('Ymd');
        xtc_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");

        // restore cart contents
        $_SESSION['cart']->restore_contents();

        if (sizeof($_SESSION['navigation']->snapshot) > 0) {
          $origin_href = xtc_href_link($_SESSION['navigation']->snapshot['page'], xtc_array_to_string($_SESSION['navigation']->snapshot['get'], array(xtc_session_name())), $_SESSION['navigation']->snapshot['mode']);
          $_SESSION['navigation']->clear_snapshot();
          xtc_redirect($origin_href);
        } else {
          xtc_redirect(xtc_href_link(FILENAME_DEFAULT));
        }
      }
    }
  }

  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_LOGIN);

  $breadcrumb->add(NAVBAR_TITLE, xtc_href_link(FILENAME_LOGIN, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php include(DIR_WS_MODULES.FILENAME_METATAGS); ?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function session_win() {
  window.open("<?php echo xtc_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td rowspan="2" class="pageHeading" align="right"><?php echo xtc_image(DIR_WS_IMAGES . 'table_background_login.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (sizeof(TEXT_STEP_BY_STEP) > 0) {
?>
      <tr>
        <td class="main"><?php echo TEXT_STEP_BY_STEP; ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if (isset($_GET['login']) && ($_GET['login'] == 'fail')) {
    $info_message = TEXT_LOGIN_ERROR;
  } elseif ($_SESSION['cart']->count_contents()) {
    $info_message = TEXT_VISITORS_CART;
  }
 if (ACCOUNT_OPTIONS=='account' or ACCOUNT_OPTIONS=='both') {
  if (isset($info_message)) {
?>
      <tr>
        <td class="smallText"><?php echo $info_message; ?></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }

?>
      <tr>
        <td><?php echo xtc_draw_form('login', xtc_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" width="50%" valign="top"><b><?php echo HEADING_NEW_CUSTOMER; ?></b></td>
            <td class="main" width="50%" valign="top"><b><?php echo HEADING_RETURNING_CUSTOMER; ?></b></td>
          </tr>
          <tr>
            <td width="50%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="1" class="infoBox">
              <tr>
                <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2" class="infoBoxContents">
                  <tr>
                    <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="main" valign="top"><?php echo TEXT_NEW_CUSTOMER . '<br><br>' . TEXT_NEW_CUSTOMER_INTRODUCTION; ?></td>
                  </tr>
                  <tr>
                    <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="50%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="1" class="infoBox">
              <tr>
                <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2" class="infoBoxContents">
                  <tr>
                    <td colspan="2"><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="main" colspan="2"><?php echo TEXT_RETURNING_CUSTOMER; ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                    <td class="main"><?php echo xtc_draw_input_field('email_address'); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><b><?php echo ENTRY_PASSWORD; ?></b></td>
                    <td class="main"><?php echo xtc_draw_password_field('password'); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="smallText" colspan="2"><?php echo '<a href="' . xtc_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td width="50%" align="right" valign="top"><?php echo '<a href="' . xtc_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . xtc_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
            <td width="50%" align="right" valign="top"><?php echo xtc_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN); ?></td>
          </tr>
        </table></form></td>
      </tr>
      <tr>
 <?php
}
if (ACCOUNT_OPTIONS=='guest' or ACCOUNT_OPTIONS=='both') {      
?>
       <td>
        <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="1" class="infoBox">
         <tr>
          <td class="main" width="50%" valign="top"><b><?php echo HEADING_GUEST_CUSTOMER; ?></b></td>
         </tr>
         <tr>
          <td>
           <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2" class="infoBox">
            <tr>
             <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
            </tr>
            <tr>
             <td class="main" valign="top"><?php echo TEXT_GUEST_CUSTOMER . '<br><br>' . TEXT_GUEST_CUSTOMER_INTRODUCTION; ?></td>
            </tr>
            <tr>
             <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
            </tr>
            <tr>
             <td width="50%" align="right" valign="top"><?php echo '<a href="' . xtc_href_link(FILENAME_CREATE_GUEST_ACCOUNT, '', 'SSL') . '">' . xtc_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
             <td><?php echo xtc_draw_separator('pixel_trans.gif', '50%', '10'); ?></td>
            </tr>
           </table>
          </td>
         </tr>
        </table>
       </td>
      </tr>

 <?php
}
?>     
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