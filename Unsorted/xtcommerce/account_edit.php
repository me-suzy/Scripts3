<?php
/* -----------------------------------------------------------------------------------------
   $Id: account_edit.php,v 1.1 2003/09/06 21:38:27 fanta2k Exp $   
   
   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(account_edit.php,v 1.63 2003/05/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (account_edit.php,v 1.14 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
  
  // include needed functions
  require_once(DIR_FS_INC . 'xtc_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'xtc_draw_radio_field.inc.php');
  require_once(DIR_FS_INC . 'xtc_date_short.inc.php');
  require_once(DIR_FS_INC . 'xtc_image_button.inc.php');
  require_once(DIR_FS_INC . 'xtc_validate_email.inc.php');

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    xtc_redirect(xtc_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_ACCOUNT_EDIT);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (ACCOUNT_GENDER == 'true') $gender = xtc_db_prepare_input($_POST['gender']);
    $firstname = xtc_db_prepare_input($_POST['firstname']);
    $lastname = xtc_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = xtc_db_prepare_input($_POST['dob']);
    $email_address = xtc_db_prepare_input($_POST['email_address']);
    $telephone = xtc_db_prepare_input($_POST['telephone']);
    $fax = xtc_db_prepare_input($_POST['fax']);

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('account_edit', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(xtc_date_raw($dob), 4, 2), substr(xtc_date_raw($dob), 6, 2), substr(xtc_date_raw($dob), 0, 4)) == false) {
        $error = true;

        $messageStack->add('account_edit', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR);
    }

    if (xtc_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

    $check_email_query = xtc_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . xtc_db_input($email_address) . "' and customers_id != '" . (int)$_SESSION['customer_id'] . "'");
    $check_email = xtc_db_fetch_array($check_email_query);
    if ($check_email['total'] > 0) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_TELEPHONE_NUMBER_ERROR);
    }

    if ($error == false) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = xtc_date_raw($dob);

      xtc_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$_SESSION['customer_id'] . "'");

      xtc_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");

// reset the session variables
      $customer_first_name = $firstname;

      $messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');

      xtc_redirect(xtc_href_link(FILENAME_ACCOUNT, '', 'SSL'));
    }
  } else {
    $account_query = xtc_db_query("select customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_telephone, customers_fax from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
    $account = xtc_db_fetch_array($account_query);
  }

  $breadcrumb->add(NAVBAR_TITLE_1, xtc_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, xtc_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php include(DIR_WS_MODULES.FILENAME_METATAGS); ?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php require('includes/form_check.js.php'); ?>
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
    <td class="tableBody" width="100%" valign="top"><?php echo xtc_draw_form('account_edit', xtc_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', 'onSubmit="return check_form(account_edit);"') . xtc_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo xtc_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($messageStack->size('account_edit') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('account_edit'); ?></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo MY_ACCOUNT_TITLE; ?></b></td>
                <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" cellspacing="2" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') {
    $male = ($account['customers_gender'] == 'm') ? true : false;
    $female = !$male;
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_GENDER; ?></td>
                    <td class="main"><?php echo xtc_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . xtc_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (xtc_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  }
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
                    <td class="main"><?php echo xtc_draw_input_field('firstname', $account['customers_firstname']) . '&nbsp;' . (xtc_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
                    <td class="main"><?php echo xtc_draw_input_field('lastname', $account['customers_lastname']) . '&nbsp;' . (xtc_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  if (ACCOUNT_DOB == 'true') {
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
                    <td class="main"><?php echo xtc_draw_input_field('dob', xtc_date_short($account['customers_dob'])) . '&nbsp;' . (xtc_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  }
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                    <td class="main"><?php echo xtc_draw_input_field('email_address', $account['customers_email_address']) . '&nbsp;' . (xtc_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
                    <td class="main"><?php echo xtc_draw_input_field('telephone', $account['customers_telephone']) . '&nbsp;' . (xtc_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
                    <td class="main"><?php echo xtc_draw_input_field('fax', $account['customers_fax']) . '&nbsp;' . (xtc_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo xtc_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . xtc_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . xtc_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo xtc_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo xtc_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->    <td class="navRight" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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