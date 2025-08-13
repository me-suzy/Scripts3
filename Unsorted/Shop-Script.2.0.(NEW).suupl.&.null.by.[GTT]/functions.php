<?

/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


//frequently used functions

	

function show_price($price) //show a number and selected currency sign
		//$price is in universal currency
{
	global $currency_name;
	global $currency_value;
	global $currency_where;
	global $current_currency;

	if ($current_currency >= count($currency_name)) $current_currency = 0;

	$price = round(100*$price*$currency_value[$current_currency])/100;

	return $currency_where[$current_currency] ?
		$price.$currency_name[$current_currency] :
		$currency_name[$current_currency].$price;
}

function get_current_time() //get current date and time as a string
//required to do INSERT queries of DATETIME/TIMESTAMP in different DBMSes
{
	$timestamp = time();
	if (DBMS == 'MSSQL')
		$s = strftime("%H", $timestamp).":".strftime("%M", $timestamp).":".strftime("%S", $timestamp)." ".strftime("%d", $timestamp)."/".strftime("%m", $timestamp)."/".strftime("%Y", $timestamp);
	else // MYSQL or IB
		$s = strftime("%Y", $timestamp)."-".strftime("%m", $timestamp)."-".strftime("%d", $timestamp)." ".strftime("%H", $timestamp).":".strftime("%M", $timestamp).":".strftime("%S", $timestamp);

	return $s;
}

function ShowNavigator($a, $offset, $q, $path, &$out)
{ 	
		//shows navigator [prev] 1 2 3 4  [next]
		//$a - count of elements in the array, which is being navigated
		//$offset - current offset in array (showing elements [$offset ... $offset+$q])
		//$q - quantity of items per page
		//$path - link to the page (f.e: "index.php?categoryID=1&")

		if ($a > $q) //if all elements couldn't be placed on the page
		{

			//[prev]
			if ($offset>0) $out .= "<a href=\"".$path."offset=".($offset-$q)."\">[".STRING_PREVIOUS."]</a> &nbsp;";

			//digital links
			$k = $offset / $q;

			//not more than 4 links to the left
			$min = $k - 5;
			if ($min < 0) { $min = 0; }
			else {
				if ($min >= 1)
				{ //link on the 1st page
					$out .= "<a href=\"".$path."offset=0\">[1-".$q."]</a> &nbsp;";
					if ($min != 1) { $out .= "... &nbsp;"; };
				}
			}

			for ($i = $min; $i<$k; $i++)
			{
				$m = $i*$q + $q;
				if ($m > $a) $m = $a;

				$out .= "<a href=\"".$path."offset=".($i*$q)."\">[".($i*$q+1)."-".$m."]</a> &nbsp;";
			}

			//# of current page
			$min = $offset+$q;
			if ($min > $a) $min = $a;
			$out .= "[".($k*$q+1)."-".$min."] &nbsp;";

			//not more than 5 links to the right
			$min = $k + 6;
			if ($min > $a/$q) { $min = $a/$q; };
			for ($i = $k+1; $i<$min; $i++)
			{
				$m = $i*$q+$q;
				if ($m > $a) $m = $a;

				$out .= "<a href=\"".$path."offset=".($i*$q)."\">[".($i*$q+1)."-".$m."]</a> &nbsp;";
			}

			if ($min*$q < $a) { //the last link
				if ($min*$q < $a-$q) $out .= " ... &nbsp;";
				$out .= "<a href=\"".$path."offset=".($a-$a%$q)."\">[".($a-$a%$q+1)."-".$a."]</a> ";
			}

			//[next]
			if ($offset<$a-$q) $out .= "<a href=\"".$path."offset=".($offset+$q)."\">[".STRING_NEXT."]</a>";

		}
}


?>