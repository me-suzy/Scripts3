<?php
/* -----------------------------------------------------------------------------------------
   $Id: password_forgotten.php,v 1.1 2003/09/06 21:54:34 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(password_forgotten.php,v 1.6 2002/11/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (password_forgotten.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

define('NAVBAR_TITLE_1', 'Login');
define('NAVBAR_TITLE_2', 'Password Forgotten');
define('HEADING_TITLE', 'I\'ve Forgotten My Password!');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>NOTE:</b></font> The E-Mail Address was not found in our records, please try again.');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - New Password');
define('EMAIL_PASSWORD_REMINDER_BODY', 'A new password was requested from ' . $REMOTE_ADDR . '.' . "\n\n" . 'Your new password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n");
define('TEXT_PASSWORD_SENT', 'A New Password Has Been Sent To Your Email Address');
?>