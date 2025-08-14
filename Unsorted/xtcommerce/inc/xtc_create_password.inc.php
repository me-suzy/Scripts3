<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_create_password.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  function xtc_create_password($length) { 
    if ($length > 36) { 
      return "ERROR"; 
    } else { 
      $str = md5(mktime()); 
      $cutoff = 31 - $length; 
      $start = rand(0, $cutoff); 
      return substr($str, $start, $length); 
    }
  }
  
  ?>  
