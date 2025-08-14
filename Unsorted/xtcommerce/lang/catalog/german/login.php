<?php
/* -----------------------------------------------------------------------------------------
   $Id: login.php,v 1.1 2003/09/06 21:54:34 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(login.php,v 1.13 2003/04/30); www.oscommerce.com 
   (c) 2003	 nextcommerce (login.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

if ($navigation->snapshot['page'] == FILENAME_CHECKOUT_SHIPPING) {
  define('NAVBAR_TITLE', 'Bestellen');
  define('HEADING_TITLE', 'Eine Online-Bestellung ist einfach.');
  define('TEXT_STEP_BY_STEP', 'Wir begleiten Sie Schritt f&uuml;r Schritt bei diesem Vorgang.');
} else {
  define('NAVBAR_TITLE', 'Anmelden');
  define('HEADING_TITLE', 'Melden Sie sich an');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('HEADING_NEW_CUSTOMER', 'Neuer Kunde');
define('TEXT_NEW_CUSTOMER', 'Ich bin ein neuer Kunde.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Durch Ihre Anmeldung bei ' . STORE_NAME . ' sind Sie in der Lage schneller zu bestellen, kennen jederzeit den Status Ihrer Bestellungen und haben immer eine aktuelle &Uuml;bersicht &uuml;ber Ihre bisherigen Bestellungen.');

define('HEADING_GUEST_CUSTOMER', 'Gastbesteller');
define('TEXT_GUEST_CUSTOMER', 'Ich m&ouml;chte nur als Gast bestellen.');
define('TEXT_GUEST_CUSTOMER_INTRODUCTION', 'Durch Ihre Bestellung als Gast beim ' . STORE_NAME . ' werden Ihre Daten nicht gespeichert und Sie erhalten kein eigenes Konto. Bei einer erneuten Bestellung müssen Sie jedoch alle Daten erneut eingeben.');


define('HEADING_RETURNING_CUSTOMER', 'Bereits Kunde');
define('TEXT_RETURNING_CUSTOMER', 'Ich bin bereits Kunde.');
define('ENTRY_EMAIL_ADDRESS', 'eMail Adresse:');
define('ENTRY_PASSWORD', 'Passwort:');

define('TEXT_PASSWORD_FORGOTTEN', 'Sie haben Ihr Passwort vergessen? Dann klicken Sie <u>hier</u>');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>FEHLER:</b></font> Keine &Uuml;bereinstimmung der eingebenen \'eMail-Adresse\' und/oder dem \'Passwort\'.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>ACHTUNG:</b></font> Ihre Besuchereingaben werden automatisch mit Ihrem Kundenkonto verbunden. <a href="javascript:session_win();">[Mehr Information]</a>');
?>