<?php
/* --------------------------------------------------------------
   $Id: customers_status.php,v 1.1 2003/09/06 21:54:33 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(customers.php,v 1.76 2003/05/04); www.oscommerce.com 
   (c) 2003	 nextcommerce (customers_status.php,v 1.9 2003/08/14); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------*/
   
define('ENTRY_YES','Yes');
define('ENTRY_NO','No'); 
define('TEXT_INFO_HEADING_EDIT_CUSTOMERS_STATUS','Edit Group data');
define('TEXT_INFO_EDIT_INTRO','Change here your settings for this Group');
define('TEXT_INFO_CUSTOMERS_STATUS_PUBLIC_INTRO','<b>Show Public ?</b>');
define('ENTRY_CUSTOMERS_STATUS_PUBLIC','Public');
define('TEXT_INFO_CUSTOMERS_STATUS_NAME','<b>Groupname</b>');
define('HEADING_TITLE','Customers Groups');
define('TABLE_HEADING_CUSTOMERS_STATUS','Group');
define('TABLE_HEADING_ACTION','Action');
define('TEXT_INFO_CUSTOMERS_STATUS_IMAGE','Group image');
define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_INTRO','<b>Show price in shop</b>');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE','price');
define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_TAX_INTRO','<b>Show prices with or without tax</b>');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE_TAX','Prices incl. tax');
define('TEXT_INFO_CUSTOMERS_STATUS_ADD_TAX_INTRO','<b>If prices incl. tax set to "no"</b>');
define('ENTRY_CUSTOMERS_STATUS_ADD_TAX','show Tax in order total');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE_INTRO','<b>Max. % discount on single product</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE','discount');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO','<b>Discount on total order</b>');
define('ENTRY_OT_XMEMBER','discount');
define('ENTRY_CUSTOMERS_STATUS_COD_PERMISSION','Per cash on celivery');
define('ENTRY_CUSTOMERS_STATUS_CC_PERMISSION','Per credit Card');
define('ENTRY_CUSTOMERS_STATUS_BT_PERMISSION','Per banktransfer');
define('TEXT_INFO_CUSTOMERS_STATUS_GRADUATED_PRICES_INTRO','<b>Graduated prices</b>');
define('ENTRY_GRADUATED_PRICES','Graduated prices');
define('TABLE_HEADING_CUSTOMERS_UNALLOW','n.a. paymentmethods');
define('TABLE_HEADING_CUSTOMERS_GRADUATED','graduated price');
define('TAX_YES','incl');
define('TAX_NO','excl');
define('TABLE_HEADING_TAX_PRICE','Price / Tax');
define('TABLE_HEADING_DISCOUNT','Discount');
define('YES','yes');
define('NO','no');
define('HEADING_TITLE', 'Customer Groups');
define('TABLE_HEADING_CUSTOMERS_STATUS', 'Customer Group');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_INFO_EDIT_INTRO', 'Please make all neccessary changes.');
define('TEXT_INFO_CUSTOMERS_STATUS_NAME', 'Group:');
define('TEXT_INFO_CUSTOMERS_STATUS_IMAGE', 'Group Icon:');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE_INTRO', 'Please insert a discount between 0 und 100%, which is used on each displayed product.');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE', 'Discount (0 bis 100%):');
define('TEXT_INFO_INSERT_INTRO', 'Please create a new customer group within all neccessary values.');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure that you want to delete this customer group?');
define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_TAX_INTRO', 'Shall we display prices incusive or exclusive Tax?');
define('TEXT_INFO_CUSTOMERS_STATUS_COD_PERMISSION_INTRO', '<b>Shall we allow customers of this group to pay cod?</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_CC_PERMISSION_INTRO', '<b>Shall we allow customers of this group to pay with credit cards?</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_BT_PERMISSION_INTRO', '<b>Shall we allow customers of this group to pay via banktransfer?</b>');
define('TEXT_INFO_HEADING_NEW_CUSTOMERS_STATUS', 'New customer group');
define('TEXT_INFO_HEADING_EDIT_CUSTOMERS_STATUS', 'Change customer group');
define('TEXT_INFO_HEADING_DELETE_CUSTOMERS_STATUS', 'delete customer group');
define('ERROR_REMOVE_DEFAULT_CUSTOMER_STATUS', 'Error: You can not delete the default customer group. Please set another group to default customer group and try again.');
define('ERROR_STATUS_USED_IN_CUSTOMERS', 'Error: This customer group is actually in use.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Error: This customer group is actually in use for order history.');
define('ENTRY_OT_XMEMBER', 'Customer Discount on order total? :');
define('ENTRY_YES', 'Yes');
define('ENTRY_NO', 'No');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE_TAX', 'Prices include Tax:');
define('ENTRY_GRADUATED_PRICES', 'Graduated Prices:');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS', 'Existing customer groups:');
define('TABLE_HEADING_CUSTOMERS_UNALLOW_SHIPPING','n.a. Shipping');
define('TEXT_INFO_CUSTOMERS_STATUS_SHIPPING_UNALLOWED_INTRO','<b>Shippingmodules</b>');
define('ENTRY_CUSTOMERS_STATUS_SHIPPING_UNALLOWED','Not allowed shippingmodules');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_ATTRIBUTES_INTRO','<b>Discount on product attributtes</b><br>(Max. % discount on single product)');
define('ENTRY_CUSTOMERS_STATUS_DISCOUNT_ATTRIBUTES','Discount');
define('TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_UNALLOWED_INTRO','<b>Paymentmodules</b>');
define('ENTRY_CUSTOMERS_STATUS_PAYMENT_UNALLOWED','Not allowed paymentmodules');
?>