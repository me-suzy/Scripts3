<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_format_price_graduated.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (xtc_format_price_graduated.inc.php,v 1.4 2003/08/19); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function xtc_format_price_graduated($price_string,$price_special,$calculate_currencies,$products_tax_class)
	{
	$currencies_query = xtc_db_query("SELECT symbol_left,
											symbol_right,
											decimal_places,
											decimal_point,
          										thousands_point,
											value
											FROM ". TABLE_CURRENCIES ." WHERE
											code = '".$_SESSION['currency'] ."'");
	$currencies_value=xtc_db_fetch_array($currencies_query);
	$currencies_data=array();
	$currencies_data=array(
							'SYMBOL_LEFT'=>$currencies_value['symbol_left'] ,
							'SYMBOL_RIGHT'=>$currencies_value['symbol_right'] ,
							'DECIMAL_PLACES'=>$currencies_value['decimal_places'] ,
							'VALUE'=> $currencies_value['value']);
	if ($calculate_currencies=='true') {
	$price_string=$price_string * $currencies_data['VALUE'];
	}
	// add tax
	$products_tax=xtc_get_tax_rate($products_tax_class);
	if ($_SESSION['customers_status']['customers_status_show_price_tax'] =='0') {
		$products_tax='';
	}						
	$price_string= (xtc_add_tax($price_string,$products_tax));
	// round price
	$price_string=xtc_precision($price_string,$currencies_data['DECIMAL_PLACES']);
	
	if ($price_special=='1') {
	$price_string=number_format($price_string,$currencies_data['DECIMAL_PLACES'], $currencies_data['DEC_POINT'], $currencies_data['THD_POINT']);

	$price_string = $currencies_data['SYMBOL_LEFT']. ' '.$price_string.' '.$currencies_data['SYMBOL_RIGHT'];
	}
	return $price_string;
	}
 ?>