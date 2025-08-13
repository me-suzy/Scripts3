<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//user's account

	$q = db_query("SELECT Login, Email, first_name, Country, City, Address, Phone, last_name, ZIP, State FROM ".CUSTOMERS_TABLE." WHERE Login='$log'") or die (db_error());
	$row = db_fetch_row($q);

	$out = "

<font class=cat><b><u>".STRING_CONTACTS."</u>:</b></font><br><br>

<table>
<tr><td>".CUSTOMER_LOGIN."</td><td><b>$row[0]</b></td></tr>
<tr><td>".CUSTOMER_EMAIL."</td><td><b>$row[1]</b></td></tr>
<tr><td></td><td></td></tr>
<tr><td>".CUSTOMER_FIRST_NAME."</td><td><b>$row[2]</b></td></tr>
<tr><td>".CUSTOMER_LAST_NAME."</td><td><b>$row[7]</b></td></tr>
<tr><td>".CUSTOMER_ADDRESS."</td><td><b>$row[8], $row[3], $row[9], $row[4], $row[5]</b></td></tr>
<tr><td>".CUSTOMER_PHONE_NUMBER."</td><td><b>$row[6]</b></td></tr>
</table><br>
<input type=button onClick=\"javascript:window.location='index.php?update_details=yes';\" value=\"".STRING_UPDATE_DETAILS."\" class=bluebutton>
<br><br><br>

	";

	$out .= "<font class=cat><b><u>".STRING_SHOPPING_HISTORY."</u>:</b></font><br><br>";

	$q = db_query("SELECT count(*) FROM ".ORDERS_TABLE." WHERE customer_login='$log'") or die (db_error());
	$orders_count = db_fetch_row($q); $orders_count = $orders_count[0];

	if (!$orders_count)
	{
		$out .= "&nbsp;&nbsp;&nbsp;&nbsp;".STRING_NO_ORDERS;
	}
	else
	{

		if ($offset>$orders_count) $offset = 0;
		$q = db_query("SELECT Done, order_time, payment_type, shipping_type, shipping_cost, orderID, calculate_tax, tax FROM ".ORDERS_TABLE." WHERE customer_login='$log' ORDER BY Done, order_time DESC") or die (db_error());

		$sum = 0;

		$out .= "<center>";
		showNavigator($orders_count, $offset, $products_count, "index.php?user_details=yes&",&$out);
		$out .= "</center><br>";

		$out .= "<table border=0 cellspacing=1 cellpadding=4 bgcolor=#$dark_color width=97%>";
		$out .= "	
			<tr bgcolor=#$middle_color align=center><td><b>".STRING_ORDER_ID."</b></td>
			<td><b>".TABLE_ORDER_TIME."</b></td>
			<td><b>".STRING_PAYMENT_TYPE."</b></td><td><b>".STRING_SHIPPING_TYPE."</b></td>
			<td width=100%><b>".TABLE_ORDERED_PRODUCTS."</b></td>
			<td><b>".TABLE_ORDER_TOTAL.", $currency_name[$current_currency]</b></td></tr>
		";
		$cn = 0;
		while ($row = db_fetch_row($q))
		{
			if ($cn>=$offset && $cn<$offset+$products_count)
			{
				$prs = "";
				$total = 0;
				$qr = db_query("SELECT name, Price, Quantity FROM ".ORDERED_CARTS_TABLE." WHERE orderID=".$row[5]) or die(db_error());
				while ($it = db_fetch_row($qr))
				{
					$prs .= "$it[0] x $it[2]: ".($it[1]*$it[2])."<br>";
					$total += $it[1]*$it[2];
				}
				if ($row[6]) $total *= (100+$row[7])/100; //add tax if required
				$total += $row[4]; //+shipping cost


				$out .= $row[0] ? "<tr bgcolor=#$light_color>" : "<tr bgcolor=white>";
				$out .= "<td>$row[5]</td>";
				$out .= "<td><nobr>$row[1]</nobr></td>";
				$out .= "<td>$row[2]</td>";
				$out .= "<td>$row[3] (".show_price($row[4]).")</td>";
				$out .= "<td>$prs</td>";
				$out .= "<td align=center>".show_price($total);
					if ($row[7] && $row[6]) $out .= "<br>(".STRING_TAX." $row[7]%)";
				$out .= "</td>";
				$out .= "</tr>";
			}
			$sum += $total;
			$cn++;
		}
		$sum = show_price($sum);
		$out .= "<tr bgcolor=#$middle_color><td colspan=5><b>".TABLE_TOTAL."</b></td><td><nobr><font class=cat><b><u>$sum</u></b></font></nobr></td></tr>";
		$out .= "</table>";

		$out .= "<br><center>";
		showNavigator($orders_count, $offset, $products_count, "index.php?user_details=yes&",&$out);
		$out .= "</center>";

		$out .= "

<p><center>
<table>
<tr>

<td>
<table border=0 cellspacing=1 cellpadding=4 bgcolor=#$dark_color>
<tr><td bgcolor=white>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
</table>
</td>
<td>
".STRING_ORDER_NOT_COMPLETED."
</td>

<td width=10%>&nbsp;</td>

<td>
<table border=0 cellspacing=1 cellpadding=4 bgcolor=#$dark_color>
<tr><td bgcolor=#$light_color>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
</table>
</td>
<td>
".STRING_ORDER_COMPLETED."
</td>

</tr>
</table>

		";
	}


?>