<?php
/* -----------------------------------------------------------------------------------------
   $Id: shopping_cart.php,v 1.1 2003/09/06 21:54:34 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(shopping_cart.php,v 1.15 2002/04/17); www.oscommerce.com 
   (c) 2003	 nextcommerce (shopping_cart.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

define('NAVBAR_TITLE', 'Warenkorb');
define('HEADING_TITLE', 'Ihr Warenkorb enth&auml;lt :');
define('TABLE_HEADING_REMOVE', 'Entfernen');
define('TABLE_HEADING_QUANTITY', 'Anzahl');
define('TABLE_HEADING_MODEL', 'Artikelnr.');
define('TABLE_HEADING_PRODUCTS', 'Artikel');
define('TABLE_HEADING_TOTAL', 'Summe');
define('TEXT_CART_EMPTY', 'Sie haben noch nichts in Ihrem Warenkorb.');
define('SUB_TITLE_SUB_TOTAL', 'Zwischensumme:');
define('SUB_TITLE_TOTAL', 'Summe:');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'Die mit ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' markierten Produkte, sind leider nicht in der von Ihnen gew&uuml;nschten Menge auf Lager.<br>Bitte reduzieren Sie Ihre Bestellmenge f&uuml;r die gekennzeichneten Produkte, vielen Dank');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'Die mit ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' markierten Produkte, sind leider nicht in der von Ihnen gew&uuml;nschten Menge auf Lager.<br>Die bestellte Menge wird kurzfristig von uns geliefert, wenn Sie es w&uuml;nschen nehmen wir auch eine Teillieferung vor.');
?>