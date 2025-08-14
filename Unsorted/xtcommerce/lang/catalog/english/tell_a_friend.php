<?php
/* -----------------------------------------------------------------------------------------
   $Id: tell_a_friend.php,v 1.1 2003/09/06 21:54:34 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(tell_a_friend.php,v 1.6 2003/02/06); www.oscommerce.com 
   (c) 2003	 nextcommerce (tell_a_friend.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

define('NAVBAR_TITLE', 'Tell A Friend');
define('HEADING_TITLE', 'Tell A Friend About \'%s\'');
define('HEADING_TITLE_ERROR', 'Tell A Friend');
define('ERROR_INVALID_PRODUCT', 'That product is no longer available. Please try again.');

define('FORM_TITLE_CUSTOMER_DETAILS', 'Your Details');
define('FORM_TITLE_FRIEND_DETAILS', 'Your Friend\'s Details');
define('FORM_TITLE_FRIEND_MESSAGE', 'Your Message');

define('FORM_FIELD_CUSTOMER_NAME', 'Your Name:');
define('FORM_FIELD_CUSTOMER_EMAIL', 'Your Email Address:');
define('FORM_FIELD_FRIEND_NAME', 'Your Friend\'s Name:');
define('FORM_FIELD_FRIEND_EMAIL', 'Your Friend\'s Email Address:');

define('TEXT_EMAIL_SUCCESSFUL_SENT', 'Your email about <b>%s</b> has been successfully sent to <b>%s</b>.');

define('TEXT_EMAIL_SUBJECT', 'Your friend %s has recommended this great product from %s');
define('TEXT_EMAIL_INTRO', 'Hi %s!' . "\n\n" . 'Your friend, %s, thought that you would be interested in %s from %s.');
define('TEXT_EMAIL_LINK', 'To view the product click on the link below or copy and paste the link into your web browser:' . "\n\n" . '%s');
define('TEXT_EMAIL_SIGNATURE', 'Regards,' . "\n\n" . '%s');
?>
