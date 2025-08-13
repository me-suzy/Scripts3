<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/
 
 
 
		//ADMIN :: new orders managment

		if (isset($done)) //"Complete orders" pushed
				//update orders and products tables
		{
			$vars = get_defined_vars();
			foreach ($vars as $key => $val)
			  if(strstr($key, "sel_") !== false)
			  {
				$q = db_query("SELECT productID, Quantity FROM ".ORDERED_CARTS_TABLE." WHERE orderID=$val") or die (db_error());

				while ($r = db_fetch_row($q))
					db_query("UPDATE ".PRODUCTS_TABLE." SET in_stock=in_stock-$r[1], items_sold=items_sold+$r[1] WHERE productID=$r[0]") or die (db_error());

				db_query("UPDATE ".ORDERS_TABLE." SET Done=1 WHERE orderID=$val") or die (db_error());

			  }
		}
		else if (isset($delete) && $delete) //cancel order without affecting products table
		{
				db_query("DELETE FROM ".ORDERED_CARTS_TABLE." WHERE orderID=$delete") or die (db_error());
				db_query("DELETE FROM ".ORDERS_TABLE." WHERE orderID=$delete") or die (db_error());
		}

?>

<a href="admin.php"><u><?=ADMIN_MAIN_MENU;?></u></a> : <u><font><?=ADMIN_NEW_ORDERS;?></font></u> :<br>
<p><?=ADMIN_ABOUT_PRICES;?>
<?
	//show new orders

	$q = db_query("SELECT customer_login, orderID, payment_type, shipping_type, order_time, customers_comment, final_shipping_address, shipping_cost, calculate_tax, tax FROM ".ORDERS_TABLE." WHERE Done=0 order by order_time") or die (db_error());
	$result = array(); $i=0;
	while ($row = db_fetch_row($q)) $result[$i++] = $row;

	if ($i) {
?>
<form method=post action="admin.php?path=new_orders">
<table width=95% border=0 cellspacing=1 cellpadding=2 bgcolor=#B4B4B4>
<tr bgcolor=#AAAAAA>
<td><b><?=STRING_ORDER_ID;?></b></td>
<td><b><?=TABLE_CUSTOMER;?></b></td><td><b><?=CUSTOMER_EMAIL;?></b></td><td><b><?=CUSTOMER_ADDRESS;?></b></td>
<td><b><?=CUSTOMER_PHONE_NUMBER;?></b></td><td><b><?=TABLE_ORDERED_PRODUCTS;?>, <?=STRING_UNIVERSAL_CURRENCY;?></b></td><td><b><?=STRING_TAX;?></b></td>
<td align=center><b><?=TABLE_ORDER_TOTAL;?>, <?=STRING_UNIVERSAL_CURRENCY;?></b></td>
<td><b><?=STRING_PAYMENT_TYPE;?></b></td><td><b><?=STRING_SHIPPING_TYPE;?></b></td><td><b><?=TABLE_ORDER_TIME;?></b></td>
<td><b><?=TABLE_ORDER_COMMENTS;?></b></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr><?

	for ($i=0; $i<count($result); $i++)
	{
		$p = db_query("SELECT first_name, Email, Country, City, Address, Phone, Login FROM ".CUSTOMERS_TABLE." WHERE Login='".$result[$i][0]."';") or die(db_error());
		if (!$r = db_fetch_row($p)) //user wasn`t found - delete his order
		{
			$p = db_query("DELETE FROM ".ORDERS_TABLE." WHERE customer_login='".$result[$i][0]."'") or die (db_error());
		}
		else
		{
			if (!$result[$i][9]) $result[$i][9] = 0;
			$prs = "";
			$total = 0;
			$q = db_query("SELECT name, Price, Quantity FROM ".ORDERED_CARTS_TABLE." WHERE orderID=".$result[$i][1]."") or die(db_error());
			while ($it = db_fetch_row($q))
			{
				$prs .= "$it[0] x $it[2]: ".($it[1]*$it[2])."<br>";
				$total += $it[1]*$it[2];
			}
			if ($result[$i][8]) $total *= (100+$result[$i][9])/100; //+tax if required
			$total += $result[$i][7]; //+shipping cost


			echo "<tr bgcolor=#F3F3F3>\n";
			echo "<td>".$result[$i][1]."</td>\n";
			echo "<td><a href=\"javascript:open_window('user.php?uLogin=$r[6]',270,350);\">$r[0]</td>\n";
			echo "<td><a href=\"mailto:$r[1]\">$r[1]</a></td>\n";
			echo "<td>".$result[$i][6]."</td>\n";
			echo "<td>$r[5]</td>";
			echo "<td>$prs</td>\n"; //order content
			echo "<td>".$result[$i][9]."%</td>\n";
			echo "<td align=center><b>".$total."</b> (".STRING_SHIPPING_TYPE.": ".$result[$i][7].")</td>\n";
			echo "<td>".$result[$i][2]."</td>\n";
			echo "<td>".$result[$i][3]."</td>\n";
			echo "<td>".$result[$i][4]."</td>\n";
			echo "<td>".str_replace("<","&lt;",$result[$i][5])."</td>\n";
			echo "<td><a href=\"kvit.php?client=".base64_encode($r[6])."&orderID=".$result[$i][1]."\" target=top>êâèòàíöèÿ</a></td>";
			echo "<td><input type=\"checkbox\" name=\"sel_".$result[$i][1]."\" value=\"".$result[$i][1]."\"></td>\n";
			echo "<td><a href=\"javascript:confirmDelete(".$result[$i][1].",'".QUESTION_DELETE_CONFIRMATION."','admin.php?path=new_orders&delete=');\"><img src=\"images/remove.jpg\" border=0 alt=\"".CANCEL_BUTTON."\"></a></td>\n";
			echo "</tr>\n";
		}
	}


?>
<tr bgcolor=#E5E5E5>
<td colspan=14 align=right>
<input type="submit" value="<?=COMPLETE_ORDERS_BUTTON;?>">
<input type="hidden" name="done" value="1">
</td>
<td>&nbsp;</td>
</tr>
</table>
</form>

<? 
	}
   else echo "<br><br><font>".STRING_NO_ORDERS."</font>";
?>
