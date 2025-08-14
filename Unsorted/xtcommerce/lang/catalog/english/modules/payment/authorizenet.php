<?php
/* -----------------------------------------------------------------------------------------
   $Id: authorizenet.php,v 1.1 2003/09/06 21:54:34 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(authorizenet.php,v 1.13 2003/01/03); www.oscommerce.com 
   (c) 2003	 nextcommerce (authorizenet.php,v 1.4 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', 'Authorize.net');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DESCRIPTION', 'Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TYPE', 'Type:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try again.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DECLINED_MESSAGE', 'Your credit card was declined. Please try another card or contact your bank for more info.');
  define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR', 'Credit Card Error!');
  
  define('MODULE_PAYMENT_AUTHORIZENET_TXNKEY_TITLE' , 'Transaction Key');
define('MODULE_PAYMENT_AUTHORIZENET_TXNKEY_DESC' , 'Transaction Key used for encrypting TP data');
define('MODULE_PAYMENT_AUTHORIZENET_TESTMODE_TITLE' , 'Transaction Mode');
define('MODULE_PAYMENT_AUTHORIZENET_TESTMODE_DESC' , 'Transaction mode used for processing orders');
define('MODULE_PAYMENT_AUTHORIZENET_METHOD_TITLE' , 'Transaction Method');
define('MODULE_PAYMENT_AUTHORIZENET_METHOD_DESC' , 'Transaction method used for processing orders');
define('MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER_TITLE' , 'Customer Notifications');
define('MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER_DESC' , 'Should Authorize.Net e-mail a receipt to the customer?');
define('MODULE_PAYMENT_AUTHORIZENET_STATUS_TITLE' , 'Enable Authorize.net Module');
define('MODULE_PAYMENT_AUTHORIZENET_STATUS_DESC' , 'Do you want to accept Authorize.net payments?');
define('MODULE_PAYMENT_AUTHORIZENET_LOGIN_TITLE' , 'Login Username');
define('MODULE_PAYMENT_AUTHORIZENET_LOGIN_DESC' , 'The login username used for the Authorize.net service');
define('MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID_TITLE' , 'Set Order Status');
define('MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID_DESC' , 'Set the status of orders made with this payment module to this value');
define('MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER_TITLE' , 'Sort order of display.');
define('MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER_DESC' , 'Sort order of display. Lowest is displayed first.');
define('MODULE_PAYMENT_AUTHORIZENET_ZONE_TITLE' , 'Payment Zone');
define('MODULE_PAYMENT_AUTHORIZENET_ZONE_DESC' , 'If a zone is selected, only enable this payment method for that zone.');
define('MODULE_PAYMENT_AUTHORIZENET_ALLOWED_TITLE' , 'Einzelne Zonen');
define('MODULE_PAYMENT_AUTHORIZENET_ALLOWED_DESC' , 'Geben Sie <b>einzeln</b> die Zonen an, welche dieses Modul benützen dürfen. zb AT,DE (wenn leer, werden alle Zonen erlaubt)');
?>
