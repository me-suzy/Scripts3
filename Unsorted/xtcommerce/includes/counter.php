<?php
/* -----------------------------------------------------------------------------------------
   $Id: counter.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(counter.php,v 1.5 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (counter.php,v 1.6 2003/08/13); www.nextcommerce.org 

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  $counter_query = xtc_db_query("select startdate, counter from " . TABLE_COUNTER);
  if ($_SESSION['counter_count'] != 1) {
    if (!xtc_db_num_rows($counter_query)) {
      $date_now = date('Ymd');
      xtc_db_query("insert into " . TABLE_COUNTER . " (startdate, counter) values ('" . $date_now . "', '1')");
      $counter_startdate = $date_now;
      $counter_now = 1;
      $_SESSION['counter_count'] = 1;
    } else {
      $counter = xtc_db_fetch_array($counter_query);
      $counter_startdate = $counter['startdate'];
      $counter_now = ($counter['counter'] + 1);
      xtc_db_query("update " . TABLE_COUNTER . " set counter = '" . $counter_now . "'");
      $_SESSION['counter_count'] = 1;
    }
    $counter_startdate_formatted = strftime(DATE_FORMAT_LONG, mktime(0, 0, 0, substr($counter_startdate, 4, 2), substr($counter_startdate, -2), substr($counter_startdate, 0, 4)));
  } else {
    $counter = xtc_db_fetch_array($counter_query);
    $counter_startdate = $counter['startdate'];
    $counter_now = $counter['counter'];
    $counter_startdate_formatted = strftime(DATE_FORMAT_LONG, mktime(0, 0, 0, substr($counter_startdate, 4, 2), substr($counter_startdate, -2), substr($counter_startdate, 0, 4)));
  }
?>