<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_add_tax.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2003	 nextcommerce (xtc_add_tax.inc.php,v 1.4 2003/08/24); www.nextcommerce.org 
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function xtc_add_tax($price, $tax) 
	{	  
	  $price=$price+$price/100*$tax;
	  return $price;
      
	  }
 ?>