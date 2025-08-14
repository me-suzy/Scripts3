<?php
/* -----------------------------------------------------------------------------------------
   $Id: xtc_get_attributes_model.inc.php,v 1.1 2003/09/06 21:47:50 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (xtc_get_attributes_model.inc.php,v 1.1 2003/08/19); www.nextcommerce.org
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function xtc_get_attributes_model($attribute_name)
	{
	
	$options_value_id_query=xtc_db_query("SELECT
				products_options_values_id
				FROM ".TABLE_PRODUCTS_OPTIONS_VALUES."
				WHERE products_options_values_name='".$attribute_name."'");
	$options_value_id_data=xtc_db_fetch_array($options_value_id_query);
	
	$options_attr_query=xtc_db_query("SELECT
				attributes_model
				FROM ".TABLE_PRODUCTS_ATTRIBUTES."
				WHERE options_values_id='".$options_value_id_data['products_options_values_id']."'");
	$options_attr_data=xtc_db_fetch_array($options_attr_query);
	return 	$options_attr_data['attributes_model'];
			
	} 
?>