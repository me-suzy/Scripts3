<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_remove_non_numeric.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (xtc_remove_non_numeric.inc.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function xtc_remove_non_numeric($var) 
	{	  
	  $var=ereg_replace('[^0-9]','',$var);
	  return $var;
     }
 ?>