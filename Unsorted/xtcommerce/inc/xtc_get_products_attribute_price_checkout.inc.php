<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_get_products_attribute_price_checkout.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (xtc_get_products_attribute_price_checkout.inc.php,v 1.6 2003/08/14); www.nextcommerce.org
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function xtc_get_products_attribute_price_checkout($attribute_price,$attribute_tax,$price_special,$quantity,$prefix)
	{
		if ($_SESSION['customers_status']['customers_status_show_price'] == '1') {
			//$attribute_tax=xtc_get_tax_rate($tax_class);
		// check if user is allowed to see tax rates
				if ($_SESSION['customers_status']['customers_status_show_price_tax'] =='0') {
				$attribute_tax='';
				}
		// add tax
		$price_string=(xtc_add_tax($attribute_price,$attribute_tax))*$quantity;
		if ($_SESSION['customers_status']['customers_status_discount_attributes']=='0') {
		// format price & calculate currency
		$price_string=xtc_format_price($price_string,$price_special,$calculate_currencies=true);
			if ($price_special=='1') {
				$price_string = ' '.$prefix.' '.$price_string.' ';
			}
			} else {
			$discount=$_SESSION['customers_status']['customers_status_discount'];
			$rabatt_string = $price_string - ($price_string/100*$discount);
			$price_string=xtc_format_price($price_string,$price_special,$calculate_currencies=true);
			$rabatt_string=xtc_format_price($rabatt_string,$price_special,$calculate_currencies=true);
			if ($price_special=='1' && $price_string != $rabatt_string) {
				$price_string = ' '.$prefix.'<font color="ff0000"><s>'.$price_string.'</s></font> '.$rabatt_string.' ';
			} else {
			$price_string=$rabatt_string;
			if ($price_special=='1') $price_string=' '.$prefix.' '.$price_string;
			}	
			}
		} else {
		$price_string= '  ' .NOT_ALLOWED_TO_SEE_PRICES;
		}
		return $price_string;
	}
 ?>