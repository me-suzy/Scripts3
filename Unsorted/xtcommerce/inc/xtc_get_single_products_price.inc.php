<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_get_single_products_price.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (xtc_get_single_products_price.inc.php,v 1.3 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function xtc_get_single_products_price($products_id,$price_special,$quantity)
	{
	// check if customer is allowed to see prices (if not -> no price calculations , show error message)
	if ($_SESSION['customers_status']['customers_status_show_price'] == '1') {
		// load price data into array for further use!
		$product_price_query = xtc_db_query("SELECT   products_price,
											products_discount_allowed,
											products_tax_class_id
											FROM ". TABLE_PRODUCTS ." 
											WHERE
											products_id = '".$products_id."'");
		$product_price = xtc_db_fetch_array($product_price_query);
		$price_data=array();
		$price_data=array(
					'PRODUCTS_PRICE'=>$product_price['products_price'],
					'PRODUCTS_DISCOUNT_ALLOWED'=>$product_price['products_discount_allowed'],
					'PRODUCT_TAX_CLASS_ID'=>$product_price['products_tax_class_id']
					);
		// get tax rate for tax class
		$products_tax=xtc_get_tax_rate($price_data['PRODUCT_TAX_CLASS_ID']);
		// check if user is allowed to see tax rates
			if ($_SESSION['customers_status']['customers_status_show_price_tax'] =='0') {
				$products_tax='';
			} // end $_SESSION['customers_status']['customers_status_show_price_tax'] =='0'
			
		// check if special price is aviable for product (no product discount on special prices!)
			if ($special_price=xtc_get_products_special_price($products_id)) {
	 
	 			$price_string=xtc_format_special_price($special_price,$price_data['PRODUCTS_PRICE'],$price_special,$calculate_currencies=true,$quantity,$products_tax);
			
				} else {  // if ($special_price=xtc_get_products_special_price($products_id))
	
				
			// Check if there is another price for customers_group (if not, take norm price and calculte discounts (NOTE: no discount on group PRICES(only OT DISCOUNT!)! 
			
				$group_price_query=xtc_db_query("SELECT personal_offer
									FROM personal_offers_by_customers_status_".$_SESSION['customers_status']['customers_status_id']."
									WHERE products_id='".$products_id."'");
				$group_price_data=xtc_db_fetch_array($group_price_query);
				
				// if we found a price, everything is ok if not, we will use normal price
				if 	($group_price_data['personal_offer']!='' and $group_price_data['personal_offer']!='0.0000')
					 {
					 $price_string=$group_price_data['personal_offer'];
					 		// check if customer is allowed to get graduated prices
							if ($_SESSION['customers_status']['customers_status_graduated_prices']=='1'){
							// check if there are graduated prices in db

								// get quantity for products
							$graduated_price_query=xtc_db_query("SELECT max(quantity)
									FROM personal_offers_by_customers_status_".$_SESSION['customers_status']['customers_status_id']."
									WHERE products_id='".$products_id."'
									AND quantity<='".$quantity."'");
							$graduated_price_data=xtc_db_fetch_array($graduated_price_query);
							 	// get singleprice
							$graduated_price_query=xtc_db_query("SELECT personal_offer
									FROM personal_offers_by_customers_status_".$_SESSION['customers_status']['customers_status_id']."
									WHERE products_id='".$products_id."'
									AND quantity='".$graduated_price_data['max(quantity)']."'");
							$graduated_price_data=xtc_db_fetch_array($graduated_price_query);
							$price_string=$graduated_price_data['personal_offer'];
							
							
							
							} // end $_SESSION['customers_status']['customers_status_graduated_prices']=='1'
			    $price_string= (xtc_add_tax($price_string,$products_tax));
					} else { // if 	($group_price_data['personal_offer']!='' and $group_price_data['personal_offer']!='0.0000')		
				$price_string= (xtc_add_tax($price_data['PRODUCTS_PRICE'],$products_tax));
	
				// check if product allows discount
				if ($price_data['PRODUCTS_DISCOUNT_ALLOWED'] != '0.00') {
				$discount=$price_data['PRODUCTS_DISCOUNT_ALLOWED'];
		
					// check if group discount > max. discount on product
					if ($discount > $_SESSION['customers_status']['customers_status_discount']) {
					 $discount=$_SESSION['customers_status']['customers_status_discount'];
		 			}
				// calculate price with rabatt
				$price_string = $price_string - ($price_string/100*$discount);

			}
			}
			// format price & calculate currency
			$price_string=xtc_format_price($price_string,$price_special,$calculate_currencies=true);
			}
	
	
			} else {
		// return message, if not allowed to see prices
		$price_string=NOT_ALLOWED_TO_SEE_PRICES;
		} // end ($_SESSION['customers_status']['customers_status_show_price'] == '1')
	
	
	return $price_string;
	}
 ?>