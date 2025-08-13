<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



	//ADMIN :: categories and products managment



	if (isset($products_update)) { //save changes in current category

		db_query("UPDATE ".PRODUCTS_TABLE." SET enabled=0 WHERE categoryID=$categoryID") or die (db_error());

		$vars = get_defined_vars();
		foreach ($vars as $key => $val)
		{

		  if (strstr($key, "price_") != false)
		  {
			$temp = $val;
			$temp = round($temp*100)/100;
			db_query("UPDATE ".PRODUCTS_TABLE." SET Price=$temp WHERE productID=".str_replace("price_","",$key)) or die (db_error());
		  }

		  if (strstr($key, "enable_") != false)
			db_query("UPDATE ".PRODUCTS_TABLE." SET enabled = 1 WHERE productID=".str_replace("enable_","",$key)) or die (db_error());

		  if (strstr($key, "left_") != false)
			db_query("UPDATE ".PRODUCTS_TABLE." SET in_stock = $val WHERE productID=".str_replace("left_","",$key)) or die (db_error());

		}

		echo "<script>\n";
		echo "window.location = 'admin.php?path=categories_items&categoryID=$categoryID';\n";
		echo "</script>\n</body>\n</html>";
		exit;

	}
	else if (isset($terminate)) //delete product
		{
			db_query("DELETE FROM ".PRODUCTS_TABLE." WHERE productID=$terminate");
			echo "<script>\n";
			echo "window.location = 'admin.php?path=categories_items&categoryID=$categoryID';\n";
			echo "</script>\n</body>\n</html>";
			exit;			
		}

	if (isset($update_gc_value)) //update button pressed
	{
		update_products_Count_Value_For_Categories(0);
		echo "<script>\n";
		echo "window.location = 'admin.php?path=categories_items';\n";
		echo "</script>\n</body>\n</html>";
		exit;
	}



	//get categories list (to avoid multiple queries)
	$q = db_query("SELECT categoryID, name, parent, products_count_admin FROM ".CATEGORIES_TABLE." where categoryID<>0 ORDER BY name") or die (db_error());
	$cats = array();
	while ($row = db_fetch_row($q)) $cats[] = $row;

?>

<a href="admin.php"><u><?=ADMIN_MAIN_MENU;?></u></a> : <u><font><?=ADMIN_CATEGORIES_PRODUCTS;?></font></u> :

<p>
<table width=300 height=40 border=0>
<tr><td align=center>
<a href="index.php"><?=ADMIN_BACK_TO_SHOP;?></a>
</td></tr>
</table>
</p>


<form action="admin.php?path=categories_items" method=post>
<input type=submit name=update_gc_value value="<?=ADMIN_UPDATE_GC_VALUE_BUTTON; ?>">
</form>



<table width=100% border=0 bgcolor=#888888 cellspacing=1>

<tr>
<td width=20% bgcolor=#D2D2FF align=center><b><?=ADMIN_CATEGORY_TITLE;?></b></td>
<td width=100% bgcolor=#F5F5B2 align=center><b><?=ADMIN_PRODUCT_TITLE;?></b></td>
</tr>



<tr>
<td valign=top bgcolor=#E2E2FF>
<table width=100%>
<tr>
<td><b><?=ADMIN_CATEGORY_ROOT;?></b> (<?
	//calculate how many products are there in the root category
	$q = db_query("SELECT count(*) FROM ".PRODUCTS_TABLE." WHERE categoryID=0") or die (db_error());
	$cnt = db_fetch_row($q); echo $cnt[0];
?>
)</td>
<td align=right><font color=red>[</font><a class=small href="admin.php?categoryID=0&path=categories_items">=></a><font color=red>]</font></td>
</tr>
<?	//show all categories
	processCategories(&$cats,0,0);
	echo "</table>\n";
	echo "<br><center>[ <a href=\"javascript:open_window('category.php?w=-1',400,400);\">".ADD_BUTTON."</a> ]</center><br>";
?>
</td>




<td valign=top bgcolor=#FFFFE2 align=center>
<?

	//show category name as a title
	$row = array();
	if (!isset($categoryID) || !$categoryID) {
		$categoryID = 0;
		$row[0] = ADMIN_CATEGORY_ROOT;
	}
	else { //go to the root if category doesn't exist
		$q = db_query("SELECT name FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and categoryID='$categoryID'") or die (db_error());
		$row = db_fetch_row($q);
		if (!$row) {
			$categoryID = 0;
			$row[0] = ADMIN_CATEGORY_ROOT;
		};
	};
	echo "<br><center><b>$row[0]:</b></center><br>\n";

	if (!$categoryID) //warning
	{
		echo ADMIN_ROOT_WARNING."<br><br>\n";
	}

	//get all products
	$q = db_query("SELECT productID, name, customers_rating, Price, in_stock, picture, big_picture, thumbnail, items_sold, enabled, product_code FROM ".PRODUCTS_TABLE." WHERE categoryID='$categoryID'  ORDER BY name;") or die (db_error());
	$result = array();
	$i=0;
	while ($row = db_fetch_row($q)) $result[$i++] = $row;

	if (!$i) echo "<center>".STRING_EMPTY_CATEGORY."</center>";
	else //show products
	{
		echo "<form action=admin.php?categoryID=$categoryID&path=categories_items method=POST>";
		echo "<table border=1 cellspacing=0 cellpadding=3 bordercolor=#C3BD7C bordercolordark=#FFFFE2 width=90%>\n";
		echo "<tr bgcolor=#F5F5C5 align=center><td width=1%>&nbsp;</td><td>".ADMIN_PRODUCT_CODE."</td><td>".ADMIN_PRODUCT_NAME."</td><td>".ADMIN_PRODUCT_RATING."</td><td>".ADMIN_PRODUCT_PRICE.", ".STRING_UNIVERSAL_CURRENCY."</td>";
		echo "<td>".ADMIN_PRODUCT_INSTOCK."</td><td>".ADMIN_PRODUCT_PICTURE."</td><td>".ADMIN_PRODUCT_BIGPICTURE."</td>";
		ECHO "<td>".ADMIN_PRODUCT_THUMBNAIL."</td><td>".ADMIN_PRODUCT_SOLD."</td><td width=1%>&nbsp;</td></tr>\n";
		for ($i=0; $i<count($result); $i++)
		{
			echo "<tr><td>\n";

			echo "<input type=checkbox name=enable_".$result[$i][0];

			if ($result[$i][9]) echo " value=on checked";
			else echo " value=off";
			echo "></td>\n";

			echo "<td>\n";
			echo "<a href=\"javascript:open_window('products.php?productID=".$result[$i][0]."',550,600);\">".$result[$i][10]."</a>";
			echo "&nbsp;</td>\n";

			echo "<td>\n";
			echo "<a href=\"javascript:open_window('products.php?productID=".$result[$i][0]."',550,600);\">".$result[$i][1]."</a>";
			echo "</td>\n";

			echo "<td align=right>\n";
			echo $result[$i][2];
			echo "&nbsp;</td>\n";

			echo "<td align=right>\n";
			echo "<input type=text name=price_".$result[$i][0]." size=5 value=".$result[$i][3].">";
			echo "</td>\n";

			echo "<td align=right>\n";
			if ($result[$i][4]<0) $result[$i][4] = 0;
			echo "<input type=text name=left_".$result[$i][0]." size=5 value=".$result[$i][4].">";
			echo "</td>\n";

			echo "<td align=center>\n";
			if ($result[$i][5] && file_exists("products_pictures/".$result[$i][5]))
				echo ANSWER_YES;
			else echo "<font color=red>".ANSWER_NO."</font>";
			echo "</td>\n";

			echo "<td align=center>\n";
			if ($result[$i][6] && file_exists("products_pictures/".$result[$i][6]))
				echo ANSWER_YES;
			else echo "<font color=red>".ANSWER_NO."</font>";
			echo "</td>\n";

			echo "<td align=center>\n";
			if ($result[$i][7] && file_exists("products_pictures/".$result[$i][7]))
				echo ANSWER_YES;
			else echo "<font color=red>".ANSWER_NO."</font>";
			echo "</td>\n";

			echo "<td align=right>".$result[$i][8]."</td>\n";

			echo "<td><a href=\"javascript:confirmDelete(".$result[$i][0].",'".QUESTION_DELETE_CONFIRMATION."','admin.php?path=categories_items&categoryID=$categoryID&terminate=');\"><img src=\"images/remove.jpg\" border=0 alt=\"".DELETE_BUTTON."\"></a></td>\n";

			echo "</tr>\n";
		};
		echo "</table>\n";
		echo "<input type=hidden name=products_update value=1>";
		echo "<br><input type=submit value=\"".SAVE_BUTTON."\">";
		echo "</form>";
	};
	echo "<br><center>[ <a href=\"javascript:open_window('products.php?cat=".$categoryID."',550,600);\">".ADD_BUTTON."</a> ]</center><br>";

?>
</td>


</tr>
</table>
