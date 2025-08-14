<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_write_user_info.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com
   (c) 2003	 nextcommerce (xtc_write_user_info.inc.php,v 1.4 2003/08/13); www.nextcommerce.org 

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  function xtc_write_user_info($customer_id, $user_info) {
//    global $customer_id, $user_info;                                                                                                                                             customers_id,           customers_ip,               customers_ip_date,  customers_host,                      customers_advertiser,                customers_referer_url
    xtc_db_query("insert into " . TABLE_CUSTOMERS_IP . " (customers_id, customers_ip, customers_ip_date, customers_host, customers_advertiser, customers_referer_url) values ('" . $customer_id . "', '" . $user_info['user_ip'] . "', now(), '" . $user_info['user_host'] . "', '" . $user_info['advertiser'] . "',  '" . $user_info['referer_url'] . "')");
    return -1;
  }
?>