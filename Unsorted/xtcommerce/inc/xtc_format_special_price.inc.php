<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_format_special_price.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (xtc_format_special_price.inc.php,v 1.6 2003/08/20); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

function xtc_format_special_price ($special_price,$price,$price_special,$calculate_currencies,$quantity,$products_tax)
	{
	// calculate currencies
	
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
							'DECIMAL_PLACES'=>$currencies_value['decimal_places'],
							'DEC_POINT'=>$currencies_value['decimal_point'],
							'THD_POINT'=>$currencies_value['thousands_point'],
							'VALUE'=> $currencies_value['value'])							;
	if ($_SESSION['customers_status']['customers_status_show_price_tax'] =='0') {
		$products_tax='';
	}						
	//$special_price= (xtc_add_tax($special_price,$products_tax))*$quantity;
	//$price= (xtc_add_tax($price,$products_tax))*$quantity;
	$price=$price*$quantity;
	$special_price=$special_price*$quantity;
	
	if ($calculate_currencies=='true') {
	$special_price=$special_price * $currencies_data['VALUE'];
	$price=$price * $currencies_data['VALUE'];
	
	}
	// round price
	$special_price=xtc_precision($special_price,$currencies_data['DECIMAL_PLACES'] );
	$price=xtc_precision($price,$currencies_data['DECIMAL_PLACES'] );
	
	if ($price_special=='1') {
	$price=number_format($price,$currencies_data['DECIMAL_PLACES'], $currencies_data['DEC_POINT'], $currencies_data['THD_POINT']);
	$special_price=number_format($special_price,$currencies_data['DECIMAL_PLACES'], $currencies_data['DEC_POINT'], $currencies_data['THD_POINT']);

	$special_price ='<font color="ff0000"><s>'. $currencies_data['SYMBOL_LEFT'].' '.$price.' '.$currencies_data['SYMBOL_RIGHT'].' </s></font>'. $currencies_data['SYMBOL_LEFT']. ' '.$special_price.' '.$currencies_data['SYMBOL_RIGHT'];
	} 
	return $special_price;
	}
 ?>