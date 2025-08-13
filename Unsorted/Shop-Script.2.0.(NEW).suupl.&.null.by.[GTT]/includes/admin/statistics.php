<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//ADMIN :: statistics

	echo "<a href=\"admin.php\"><u>".ADMIN_MAIN_MENU."</u></a> : <u><font>".ADMIN_STATISTICS."</font></u> :";

	echo "<p><b><font>".ADMIN_POPULAR_PRODUCTS."</font></b></p>";
	$q = db_query("SELECT name, customers_rating, customer_votes, items_sold FROM ".PRODUCTS_TABLE." ORDER BY customers_rating DESC") or die (db_error());
	echo "<table bgcolor=#999999 cellspacing=1 cellpadding=3>\n";
	echo "<tr bgcolor=#DDDDDD><td>".ADMIN_PRODUCT_NAME."</td>
		<td><b>".ADMIN_PRODUCT_RATING."</b></td>
		<td>".ADMIN_PRODUCT_VOTES."</td>
		<td>".ADMIN_PRODUCT_SOLD."</td></tr>";
	$i=0;
	while ($i<10 && $row = db_fetch_row($q)) {
		echo "<tr bgcolor=#FFFFFF>\n";
		echo "<td>".$row[0]."</td>\n";
		echo "<td align=right><b>".$row[1]."</b></td>\n";
		echo "<td align=right>".$row[2]."</td>\n";
		echo "<td align=right>".$row[3]."</td>\n";
		echo "</tr>\n";
		$i++;
	};
	echo "</table>\n";

	echo "<p><b><font>".ADMIN_SALABLE_PRODUCTS."</font></b></p>";
	$q = db_query("SELECT name, customers_rating, customer_votes, items_sold FROM ".PRODUCTS_TABLE." ORDER BY items_sold DESC") or die (db_error());
	echo "<table bgcolor=#999999 cellspacing=1 cellpadding=3>\n";
	echo "<tr bgcolor=#DDDDDD><td>".ADMIN_PRODUCT_NAME."</td>
		<td>".ADMIN_PRODUCT_RATING."</td>
		<td>".ADMIN_PRODUCT_VOTES."</td>
		<td><b>".ADMIN_PRODUCT_SOLD."</b></td></tr>";
	$i=0;
	while ($i<10 && $row = db_fetch_row($q)) {
		echo "<tr bgcolor=#FFFFFF>\n";
		echo "<td>".$row[0]."</td>\n";
		echo "<td align=right>".$row[1]."</td>\n";
		echo "<td align=right>".$row[2]."</td>\n";
		echo "<td align=right><b>".$row[3]."</b></td>\n";
		echo "</tr>\n";
		$i++;
	};
	echo "</table>\n";

	//show out of stock products

	$q = db_query("SELECT count(*) FROM ".PRODUCTS_TABLE." WHERE in_stock<=0") or die (db_error());
	$cnt = db_fetch_row($q); $cnt = $cnt[0];
	if ($cnt > 0)
	{
		$q = db_query("SELECT name, categoryID FROM ".PRODUCTS_TABLE." WHERE in_stock<=0 ORDER BY name DESC") or die (db_error());
		echo "<p><table bgcolor=#999999 cellspacing=1 cellpadding=5><tr><td align=center bgcolor=#CCCCCC>".ADMIN_OUT_OF_STOCK."</td></tr>\n";
		while ($row = db_fetch_row($q)) {
			echo "<tr>";
			echo "<td bgcolor=white><a href=\"admin.php?path=categories_items&categoryID=$row[1]\">$row[0]</a></td>";
			echo "</tr>";
		};
		echo "</table>";
	};
?>