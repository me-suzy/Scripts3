<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     formatprice
 * Purpose:  format price with configurable thousands and decimal separators
 * -------------------------------------------------------------
 */
function smarty_modifier_formatprice($price, $thousand_delim = ",", $decimal_delim = ".")
{
	if (strstr($price,".")) {
		$price .= "00";
		$price = ereg_replace("(\...).*$", "\\1", $price);
	} else
		$price .= ".00"; 
	$price = str_replace(".", $decimal_delim, $price);
	for ($i = strpos($price, $decimal_delim)-3; $i > 0; $i -= 3) {
		$price = substr($price, 0, $i).$thousand_delim.substr($price, $i);
	}

	return $price;
}

?>
