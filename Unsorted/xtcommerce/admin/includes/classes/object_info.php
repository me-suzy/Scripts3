<?php
/* --------------------------------------------------------------
   $Id: object_info.php,v 1.1 2003/09/06 22:05:29 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(object_info.php,v 1.5 2002/01/30); www.oscommerce.com 
   (c) 2003	 nextcommerce (object_info.php,v 1.5 2003/08/18); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  class objectInfo {

    // class constructor
    function objectInfo($object_array) {
      reset($object_array);
      while (list($key, $value) = each($object_array)) {
        $this->$key = xtc_db_prepare_input($value);
      }
    }
  }
?>