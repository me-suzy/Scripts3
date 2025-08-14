<?php
/* -----------------------------------------------------------------------------------------
   $Id: ot_shipping.php,v 1.1 2003/09/06 21:54:34 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ot_shipping.php,v 1.4 2003/02/16); www.oscommerce.com 
   (c) 2003	 nextcommerce (ot_shipping.php,v 1.4 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  define('MODULE_ORDER_TOTAL_SHIPPING_TITLE', 'Versandkosten');
  define('MODULE_ORDER_TOTAL_SHIPPING_DESCRIPTION', 'Versandkosten einer Bestellung');

  define('FREE_SHIPPING_TITLE', 'Versandkostenfrei');
  define('FREE_SHIPPING_DESCRIPTION', 'Versandkostenfrei bei einem Bestellwert &uuml;ber %s');
  
  define('MODULE_ORDER_TOTAL_SHIPPING_STATUS_TITLE','Display Shipping');
  define('MODULE_ORDER_TOTAL_SHIPPING_STATUS_DESC','Do you want to display the order shipping cost?');
  
  define('MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER_TITLE','Sort Order');
  define('MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER_DESC', 'Sort order of display.');
  
  define('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_TITLE','Allow Free Shipping');
  define('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_DESC','Do you want to allow free shipping?');
  
  define('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER_TITLE','Free Shipping For Orders Over');
  define('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER_DESC','Provide free shipping for orders over the set amount.');
  
  define('MODULE_ORDER_TOTAL_SHIPPING_DESTINATION_TITLE','Provide Free Shipping For Orders Made');
  define('MODULE_ORDER_TOTAL_SHIPPING_DESTINATION_DESC','Provide free shipping for orders sent to the set destination.');
?>