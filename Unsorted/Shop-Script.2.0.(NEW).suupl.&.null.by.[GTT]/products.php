<?

/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//ADMIN :: products managment

	include("cfg/connect.inc.php");
	include("includes/database/".DBMS.".php");
	include("functions.php");
	

	//connect 2 database
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());

	//authorized access check
	session_start();
	include("checklogin.php");
	if (!isset($log) || strcmp($log,ADMIN_LOGIN)) //unauthorized
	{
		die (ERROR_FORBIDDEN);
	}

	//current language
	include("language_list.php");

	if (!isset($current_language) ||
		$current_language<0 || $current_language>count($lang_list))
			$current_language = 0; //set default language

	if (isset($lang_list[$current_language]) && file_exists($lang_list[$current_language]->filename))
		include($lang_list[$current_language]->filename); //include current language file
	else
	{
		die("<font color=red><b>ERROR: Couldn't find language file!</b></font>
			<p><a href=\"index.php?new_language=0\">Click here to use default language</a>");
	}

	if (!isset($productID)) $productID=0;
	if (!isset($cat)) $cat = 0;
	if (!isset($name)) $name = "";
	if (!isset($product_code)) $product_code = "";
	if (!isset($popularity)) $popularity = 0;
	if (!isset($price)) $price = 0;
	if (!isset($count)) $count = 0;
	if (!isset($picture)) $picture = "none";
	if (!isset($big_picture)) $big_picture = "none";
	if (!isset($icon)) $icon = "none";
	if (!isset($description)) $description = "";
	if (!isset($br_desc)) $br_desc = "";
	if (!isset($bshow)) $bshow = 0;
	if (!strcmp($bshow, "on")) $bshow = 1;
	else $bshow = 0;
	if (!isset($oldprice)) $oldprice = 0;

?>

<html>

<head>
<link rel=STYLESHEET href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?=DEFAULT_CHARSET;?>">
<title><?=ADMIN_PRODUCT_TITLE;?></title>
<script>
function confirmDelete(question, where)
{
	temp = window.confirm(question);
	if (temp) { //delete
		window.location=where;
	}
}
function open_window(link,w,h) //opens new window
{
	var win = "width="+w+",height="+h+",menubar=no,location=no,resizable=yes,scrollbars=yes";
	wishWin = window.open(link,'wishWin',win);
}
</script>
</head>

<body bgcolor=#FFFFE2>

<?
	function showproductsForm($cat, $name, $popularity, $price, $description, $picture, $count, $title, $productID, $icon,$bshow,$big_picture,$br_desc,$oldprice,$product_code) {
	//offers editing form

		
		
		
		

?>

<center><b><font><?=$title; ?></font></b></center>

<form enctype="multipart/form-data" action="products.php" method=post>

<table width=100% border=0 cellpadding=3 cellspacing=0>

<tr>
<td align=right><?=ADMIN_CATEGORY_PARENT;?></td>
<td>
<select name="cat">
<option value="0"><?=ADMIN_CATEGORY_ROOT;?></option>
<?
	fillTheCList(0,0,$cat);
?>
</select>
</td>
</tr>

<tr>
<td align=right><?=ADMIN_PRODUCT_NAME;?></td>
<td><input type="text" name="name" value="<?=str_replace("\"","''",$name); ?>"></td>
</tr>

<tr>
<td align=right><?=ADMIN_PRODUCT_CODE;?></td>
<td><input type="text" name="product_code" value="<?=str_replace("\"","''",$product_code); ?>"></td>
</tr>

<?	if ($productID) { ?>
<tr>
<td align=right><?=ADMIN_PRODUCT_RATING;?>:</td>
<td><input type=text name=popularity value="<?=$popularity; ?>"></b></td>
</tr>

<? }; ?>

<tr>
<td align=right><?=ADMIN_PRODUCT_PRICE;?>, <?=STRING_UNIVERSAL_CURRENCY;?><br>(<?=STRING_NUMBER_ONLY;?>):</td>
<td><input type="text" name="price" value=<?=$price; ?>></td>
</tr>

<tr>
<td align=right><?=ADMIN_PRODUCT_LISTPRICE;?>, <?=STRING_UNIVERSAL_CURRENCY;?><br>(<?=STRING_NUMBER_ONLY;?>):</td>
<td><input type="text" name="oldprice" value=<?=$oldprice; ?>></td>
</tr>

<tr>
<td align=right><?=ADMIN_PRODUCT_INSTOCK;?>:</td>
<td><input type="text" name="count" value="
<?
	if ($count<0) echo "0\"></td>\n";
	else echo $count."\"></td>\n";
?>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
<td align=right><?=ADMIN_PRODUCT_PICTURE;?></td>
<td><input type="file" name="picture"></td>
<tr><td></td><td>
<?
	if ($picture && $picture != "none" && file_exists("products_pictures/".$picture))
	{
		echo "<a class=small href=\"products_pictures/".$picture."\">$picture</a>\n";
		echo "<br><a href=\"javascript:confirmDelete('".QUESTION_DELETE_PICTURE."','products.php?productID=$productID&picture_remove=yes');\">".DELETE_BUTTON."</a>\n";
	}
	else echo "<font class=average color=brown>".ADMIN_PICTURE_NOT_UPLOADED."</font>";
?>
</td>
</tr>

<tr>
<td align=right><?=ADMIN_PRODUCT_BIGPICTURE;?></td>
<td valign=top><input type="file" name="big_picture"></td>
<tr><td></td><td valign=top>
<?
	if ($big_picture && $big_picture != "none" && file_exists("products_pictures/".$big_picture))
	{
		echo "<a class=small href=\"products_pictures/".$big_picture."\">$big_picture</a>\n";
		echo "<br><a href=\"javascript:confirmDelete('".QUESTION_DELETE_PICTURE."','products.php?productID=$productID&big_picture_remove=yes');\">".DELETE_BUTTON."</a>\n";
	}
	else echo "<font class=average color=brown>".ADMIN_PICTURE_NOT_UPLOADED."</font>";
?>
</td>
</tr>


<tr>
<td align=right><?=ADMIN_PRODUCT_THUMBNAIL;?></td>
<td><input type="file" name="icon"></td>
<tr><td></td><td>
<?
	if ($icon && $icon != "none" && file_exists("products_pictures/".$icon))
	{
		echo "<a class=small href=\"products_pictures/".$icon."\">$icon</a>\n";
		echo "<br><a href=\"javascript:confirmDelete('".QUESTION_DELETE_PICTURE."','products.php?productID=$productID&icon_remove=yes');\">".DELETE_BUTTON."</a>\n";
	}
	else echo "<font class=average color=brown>".ADMIN_PICTURE_NOT_UPLOADED."</font>";
?>
</td>
</tr>

<tr>
<td align=right><?=ADMIN_PRODUCT_DESC;?><br>(HTML):</td>
<td><textarea name="description" rows=15 cols=40><?=$description; ?></textarea></td>
</tr>

<tr>
<td align=right><?=ADMIN_PRODUCT_BRIEF_DESC;?><br>(HTML):</td>
<td><textarea name="br_desc" rows=7 cols=40><?=$br_desc; ?></textarea></td>
</tr>

<?
	//product extra options
	$val = array();
	$q = db_query("SELECT optionID, name FROM ".PRODUCT_OPTIONS_TABLE) or die (db_error());
	while ($row = db_fetch_row($q))
	{
		if ($productID)
		{
			$q1 = db_query("SELECT option_value FROM ".PRODUCT_OPTIONS_VALUES_TABLE." WHERE optionID=$row[0] AND productID=$productID") or die (db_error());
			if (!($val = db_fetch_row($q1))) $val[0] = "";
		} else $val[0] = "";

		echo "
<tr>
<td align=right>$row[1]:</td>
<td><input type=text name=\"option_$row[0]\" value=\"$val[0]\"></td>
</tr>

		";
	}
?>

</table>

<? if ($productID) { ?>

<hr size=1 width=90%>
<center>
<font><b><?=STRING_RELATED_ITEMS;?></b></font>
<?
	$q = db_query("SELECT count(*) FROM ".RELATED_PRODUCTS_TABLE." WHERE Owner=$productID") or die (db_error());
	$cnt = db_fetch_row($q);
	if ($cnt[0] == 0) echo "<p><font>< ".STRING_EMPTY_CATEGORY." ></font></p>";
	else {
		$q = db_query("SELECT productID FROM ".RELATED_PRODUCTS_TABLE." WHERE Owner=$productID") or die (db_error());
		echo "<table>";
		while ($row = db_fetch_row($q))
		{
			$p = db_query("SELECT productID, name FROM ".PRODUCTS_TABLE." WHERE productID=$row[0]") or die (db_error());
			if ($r = db_fetch_row($p))
			{
			  echo "<tr>";
			  echo "<td width=100%>$r[1]</td>";
			  echo "</tr>";
			}
		}
		echo "</table>";
	};
?>
[ <a href="javascript:open_window('wishlist.php?owner=<?=$productID; ?>',400,600);"><?=EDIT_BUTTON; ?></a> ]
</center>
<hr size=1 width=90%>

<? } ?>

<p><center>
<input type=checkbox name=bshow<? if ($bshow) echo " checked"; ?>> <font><?=ADMIN_PRODUCT_SHOW_IN_SPECIAL_OFFERS;?></font><br>
</p>

<p><center>
<input type="submit" value="<?=SAVE_BUTTON;?>" width=5>
<input type="hidden" name="save" value=<?=$productID; ?>>
<input type="button" value="<?=CANCEL_BUTTON;?>" onClick="window.close();">
<?	if ($productID) echo "<input type=button value=\"".DELETE_BUTTON."\" onClick=\"confirmDelete('".QUESTION_DELETE_CONFIRMATION."','products.php?productID=$productID&del=1');\">"; ?>
</center></p>
</form>

<?	}

	function fillTheCList($parent,$level,$c)
	{
		//fills categories combobox

		

		$q = db_query("SELECT categoryID, name FROM ".CATEGORIES_TABLE." WHERE parent='$parent' and categoryID<>0 ORDER BY name;") or die (db_error());

		$a = array(); //parents
		while ($row = db_fetch_row($q))
		{
			//categories name
			echo "<option value=\"".$row[0]."\"";
			if ($c==$row[0]) echo " selected>";
			else echo ">";
			for ($j=0; $j<$level; $j++) echo "&nbsp;&nbsp;";
			echo $row[1]."</option>\n";
			//save
			$a[count($a)] = $row;
			//process subcategories
			$b = fillTheCList($row[0],$level+1,$c);
			//add $b[] to the end of $a[]
			for ($j=0; $j<count($b); $j++)
			{
				$a[count($a)] = $b[$j];
			}
		}
		return $a;
	};


	if (isset($save)) //save item to the database
	{
		//check for proper input
		$row = array();
		if (!$name) // name should be filled
		{
			showproductsForm($cat, $name, $popularity, $price, $description, $picture, $count, "<font color=red>".ERROR_INPUT_PRODUCT_NAME."</font>",$productID, $icon, $bshow, $big_picture, $br_desc,$oldprice);
			exit;
		}

		if (!$price || $price < 0) $price = 0; //price can not be negative

		if ($save) { //if $save != 0 then update item

			//at first - delete unnecessary product photos

			$q = db_query("SELECT picture, big_picture, thumbnail FROM ".PRODUCTS_TABLE." WHERE productID=".$save) or die (db_error());
			$row = db_fetch_row($q);

			//generating query

			$s = "UPDATE ".PRODUCTS_TABLE." SET categoryID=$cat, name='".str_replace("<","&lt;",$name)."', Price='$price', description='$description', in_stock='$count', show_as_special_offer=$bshow, customers_rating='$popularity', brief_description='$br_desc', list_price='$oldprice', product_code='$product_code'";

			$s1 = "";

			if ($picture && $picture != "none") {
				//delete old picture
				if ($row[0] != "none" && file_exists("products_pictures/".$row[0]))
					unlink("products_pictures/".$row[0]);
			}
			if ($big_picture && $big_picture != "none") {
				//delete old picture
				if ($row[1] != "none" && file_exists("products_pictures/".$row[1]))
					unlink("products_pictures/".$row[1]);
			}
			if ($icon && $icon != "none") {
				//delete old picture
				if ($row[2] != "none" && file_exists("products_pictures/".$row[2]))
					unlink("products_pictures/".$row[2]);
			}

			$pid = $save;

		}
		else {
			//add new product
			if(!isset($big_picture_name)) $big_picture_name = "";
			if(!isset($picture_name)) $picture_name = "";
			if(!isset($icon_name)) $icon_name = "";
			db_set_identity(PRODUCTS_TABLE);
			db_query("INSERT INTO ".PRODUCTS_TABLE." (categoryID, name, description, customers_rating, Price, picture, in_stock, thumbnail, customer_votes, items_sold, show_as_special_offer, big_picture, enabled, brief_description, list_price, product_code) VALUES ($cat,'".str_replace("<","&lt;",$name)."','$description', 0, '$price','$picture_name', $count, '$icon_name', 0, 0, $bshow, '$big_picture_name', 1, '$br_desc', '$oldprice', '$product_code');") or die (db_error());
			$pid = db_insert_id("PRODUCTS_GEN");
			$dont_update = 1;
			$s  = "";
			$s1 = "UPDATE ".PRODUCTS_TABLE." SET categoryID=categoryID";
		};



		//add photos?

		if ($picture && $picture != "none") //upload
		{

			$r = copy(trim($picture), "products_pictures/".str_replace(" ","_",$picture_name));
			$picture_name = str_replace(" ","_",$picture_name);
			if (!$r) //failed 2 upload
			{
				echo "<center><font color=red>".ERROR_FAILED_TO_UPLOAD_FILE."</font>\n<br><br>\n";
				echo "<a href=\"javascript:window.close();\">".CLOSE_BUTTON."</a></center></body>\n</html>";
				exit;
			}
			$s .= ", picture='$picture_name'";
			$s1.= ", picture='$picture_name'";
		}

		if ($big_picture && $big_picture != "none")
		{ 

			$r = copy(trim($big_picture), "products_pictures/".str_replace(" ","_",$big_picture_name));
			$big_picture_name = str_replace(" ","_",$big_picture_name);
			if (!$r)
			{
				echo "<center><font color=red>".ERROR_FAILED_TO_UPLOAD_FILE."</font>\n<br><br>\n";
				echo "<a href=\"javascript:window.close();\">".CLOSE_BUTTON."</a></center></body>\n</html>";
				exit;
			}
			$s .= ", big_picture='$big_picture_name'";
			$s1.= ", big_picture='$big_picture_name'";
		};

		if ($icon && $icon != "none")
		{

			$r = copy(trim($icon), "products_pictures/".str_replace(" ","_",$icon_name));
			$icon_name = str_replace(" ","_",$icon_name);
			if (!$r)
			{
				echo "<center><font color=red>".ERROR_FAILED_TO_UPLOAD_FILE."</font>\n<br><br>\n";
				echo "<a href=\"javascript:window.close();\">".CLOSE_BUTTON."</a></center></body>\n</html>";
				exit;
			}
			$s .= ", thumbnail='$icon_name'";
			$s1.= ", thumbnail='$icon_name'";
		}


		if (!isset($dont_update)) {
			$s .= " WHERE productID=$save";
			db_query($s) or die (db_error());
			$productID = $save;
		}
		else
		{
			$s1.= " WHERE productID=$pid";
			db_query($s1) or die (db_error());
			$productID = $pid;
		}

		//save extra fields
		db_query("delete from ".PRODUCT_OPTIONS_VALUES_TABLE." where productID=$productID") or die (db_error());
		$vars = get_defined_vars();
		foreach ($vars as $key => $val)
		{
			if (strstr($key, "option_") && trim($val)!="")
			{
				$key = str_replace("option_","",$key);
				db_query("insert into ".PRODUCT_OPTIONS_VALUES_TABLE." (optionID, productID, option_value) values ($key, $productID, '$val')") or die (db_error());
			}
		}


		//close window
		echo "<script>\n";
		echo "window.opener.location.reload();\n";
		echo "window.close();\n";
		echo "</script>\n</body>\n</html>";
		exit;
	}
	else {
		$row = array();
		if ($productID) { //get product from db

			$q = db_query("SELECT categoryID, name, description, customers_rating, Price, picture, in_stock, thumbnail, show_as_special_offer, big_picture, brief_description, list_price, product_code FROM ".PRODUCTS_TABLE." WHERE productID=".$productID) or die (db_error());
			$row = db_fetch_row($q);
 			if (!$row) //product wasn't found
			{
				echo "<center><font color=red>".ERROR_CANT_FIND_REQUIRED_PAGE."</font>\n<br><br>\n";
				echo "<a href=\"javascript:window.close();\">".CLOSE_BUTTON."</a></center></body>\n</html>";
				exit;
			}

			if (isset($picture_remove)) //delete items picture from server if requested
			{
				if ($row[5] != "none" && file_exists("products_pictures/".$row[5]))
					unlink("products_pictures/".$row[5]);
				$picture = "none";
			}


			if (isset($big_picture_remove))
			{
				if ($row[9] != "none" && file_exists("products_pictures/".$row[9]))
					unlink("products_pictures/".$row[9]);
				$big_picture = "none";
			}

			if (isset($icon_remove))
			{
				if ($row[7] != "none" && file_exists("products_pictures/".$row[7]))
					unlink("products_pictures/".$row[7]);
				$icon = "none";
			}

			if (isset($del)) //delete product
			{
				//at first photos...
				if ($row[5] != "none" && $row[5] != "" && file_exists("products_pictures/".$row[5]))
					unlink("products_pictures/".$row[5]);

				if ($row[9] != "none" && $row[9] != "" && file_exists("products_pictures/".$row[9]))
					unlink("products_pictures/".$row[9]);

				if ($row[7] != "none" && $row[7] != "" && file_exists("products_pictures/".$row[7]))
					unlink("products_pictures/".$row[7]);

				$q = db_query("DELETE FROM ".PRODUCTS_TABLE." WHERE productID=".$productID) or die (db_error());

				//close window
				echo "<script>\n";
				echo "window.opener.location.reload();\n";
				echo "window.close();\n";
				echo "</script>\n</body>\n</html>";
				exit;
			}

			$cat = $row[0];
			$name = $row[1];
			$description = $row[2];
			$popularity = $row[3];
			$price = $row[4];
			$picture = $row[5];
			$icon = $row[7];
			$count = $row[6];
			$title = $row[1];
			$bshow = $row[8];
			$big_picture = $row[9];
			$br_desc = $row[10];
			$oldprice = $row[11];
			$product_code = $row[12];
		}
		else { //creating new item
			$title = ADMIN_PRODUCT_NEW;
		};
	};
	showproductsForm($cat, $name, $popularity, $price, $description, $picture, $count, $title,$productID, $icon, $bshow, $big_picture, $br_desc,$oldprice,$product_code);

?>

</body>

</html>