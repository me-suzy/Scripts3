<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	// checking shopping cart content before ordering

	$q = db_query("SELECT productID, Quantity FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die (db_error());
	$i=0;
	$result = array();
	while ($row = db_fetch_row($q)) $result[$i++] = $row;

	if ($i) //cart is not empty
	{

		$out .= "

<center>

<font class=cat color=red><b><u>".STRING_CHECK_YOUR_ORDER."</u></b></font><br><br>

<table border=0 cellspacing=1 cellpadding=2 bgcolor=#$dark_color width=70%>
<tr bgcolor=#$middle_color align=center>
<td width=40%><font color=#902525>".TABLE_PRODUCT_NAME."</font></td>
<td width=20%><font color=#902525>".TABLE_PRODUCT_QUANTITY."</font></td>
<td width=40% colspan=2><font color=#902525>".TABLE_PRODUCT_COST.", $currency_name[$current_currency]</font></td>
</tr>

		";

		$s=0; //total cart value
		for ($i=0; $i<count($result); $i++)
		{
			$q = db_query("SELECT name, Price FROM ".PRODUCTS_TABLE." WHERE productID=".$result[$i][0]) or die (db_error());
			if ($r = db_fetch_row($q))
			{
				$out .= "<tr bgcolor=white >\n<td>".$r[0]."</td>\n";
				$out .= "<td align=center>".$result[$i][1]."</td>\n";
				$out .= "<td align=center colspan=2>".show_price($result[$i][1]*$r[1])."</td>\n";

				$s += $result[$i][1]*$r[1];
			}
		}

		$out .= "

<tr bgcolor=#FFFFFF>
<td><b>".TABLE_TOTAL."</b></td>
<td></td>
<td colspan=2 bgcolor=#$light_color align=center><b>".show_price($s)."</b></td></tr>

</table>

		";

		$ca = 

		$out .= "

<form action=\"index.php\" method=post>
<table width=80%>
<tr>
<td><input type=button class=bluebutton value=\"".EDIT_BUTTON."\" onClick=\"open_window('cart.php',400,300);\"></td>
<td align=right><input type=submit class=redbutton value=\"".CART_PROCEED_TO_CHECKOUT."\"></td>
</tr>
</table>
<input type=hidden name=proceed_ordering value=1>
</form>

</center>

		";

	}
	else
		$out .= "<center><br><br><br>".CART_EMPTY."</center>";

?>