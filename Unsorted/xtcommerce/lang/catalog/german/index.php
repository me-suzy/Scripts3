<?php
/* -----------------------------------------------------------------------------------------
   $Id: index.php,v 1.1 2003/09/06 21:54:34 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(default.php,v 1.23 2003/04/30); www.oscommerce.com 
   (c) 2003	 nextcommerce (default.php,v 1.5 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

define('TEXT_MAIN', 'Dies ist die Standardinstallation des osCommerce Forking Projektes - XT-Commerce. Alle dargestellten Produkte dienen zur Demonstration der Funktionsweise. <b>Wenn Sie Produkte bestellen, so werden diese weder ausgeliefert, noch in Rechnung gestellt.</b> Alle Informationen zu den verschiedenen Produkten sind erfunden und daher kann kein Anspruch daraus abgeleitet werden.<br><br>Sollten Sie daran interessiert sein das Programm, welches die Grundlage f&uuml;r diesen Shop bildet, einzusetzen, so besuchen Sie bitte die <a href="http://www.xt-commerce.com"><u>Supportseite von XT-Commerce</u></a>. Dieser Shop basiert auf der XT-Commerce Version <font color="#f0000"><b>' . PROJECT_VERSION . '</b></font>.<br><br>Der hier dargestellte Text kann in der folgenden Datei einer jeden Sprache ge&auml;ndert werden: [Pfad&nbsp;zu&nbsp;catalog]/lang/catalog/[language]/index.php.<br><br>Das kann manuell geschehen, oder &uuml;ber das Administration Tool mit Sprache->[language]->Sprache definieren, oder durch Verwendung des Hilfsprogrammes->Datei Manager.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Neue Produkte im %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Wann ist was verf&uuml;gbar');
define('TABLE_HEADING_DATE_EXPECTED', 'Datum');

if ( ($category_depth == 'products') || ($_GET['manufacturers_id']) ) {
  define('HEADING_TITLE', 'Unser Angebot');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Artikel-Nr.');
  define('TABLE_HEADING_PRODUCTS', 'Produkte');
  define('TABLE_HEADING_MANUFACTURER', 'Hersteller');
  define('TABLE_HEADING_QUANTITY', 'Anzahl');
  define('TABLE_HEADING_PRICE', 'Preis');
  define('TABLE_HEADING_WEIGHT', 'Gewicht');
  define('TABLE_HEADING_BUY_NOW', 'Bestellen');
  define('TEXT_NO_PRODUCTS', 'Es gibt keine Produkte in dieser Kategorie.');
  define('TEXT_NO_PRODUCTS2', 'Es gibt kein Produkt, das von diesem Hersteller stammt.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Artikel: ');
  define('TEXT_SHOW', '<b>Darstellen:</b>');
  define('TEXT_BUY', '1 x \'');
  define('TEXT_NOW', '\' bestellen!');
  define('TEXT_ALL_CATEGORIES', 'Alle Kategorien');
  define('TEXT_ALL_MANUFACTURERS', 'Alle Hersteller');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', 'Unser Angebot');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'Kategorien');
}
?>
