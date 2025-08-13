<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



	//customers shopping cart (popup window)
	include ("cfg/connect.inc.php");
	include("includes/database/".DBMS.".php");

function getCartContent() //gets cart content (total content and value)
{

	global $gids;
	global $counts;
	
	
	global $log;

	$k=0;
	$cnt = 0;
	if (isset($log)) //shopping cart is stored in the database
	{
		$q = db_query("SELECT productID, Quantity FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die (db_error());
		while ($row = db_fetch_row($q))
		{
			$t = db_query("SELECT Price FROM ".PRODUCTS_TABLE." WHERE productID=$row[0]") or die (db_error());
			$rr = db_fetch_row($t);
			$k += $row[1]*$rr[0];
			$cnt += $row[1];
		}
	}
	else
	if (isset($gids)) //get shopping cart content from session
	{
		for ($i=0; $i<count($gids); $i++)
		  if ($gids[$i])
		  {
			$t = db_query("SELECT Price FROM ".PRODUCTS_TABLE." WHERE productID='$gids[$i]'") or die (db_error());
			$rr = db_fetch_row($t);
			$k += $counts[$i]*$rr[0];
			$cnt += $counts[$i];
		  }
	}

	if ($cnt)
		return array("$cnt ".CART_CONTENT_NOT_EMPTY,show_price($k));
	else
		return array(CART_CONTENT_EMPTY,"");

} //getCartContent


function updateCartInfo()
{
		//updates brief cart info showed on the main page
		$c = getCartContent();
?>
<script>
	window.opener.document.shopping_cart_form.gc.value = '<?=$c[0]; ?>';
	window.opener.document.shopping_cart_form.ca.value = '<?=$c[1]; ?>';
</script>
<?

} //updateCartInfo




	session_start();

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

	if (!isset($current_currency)) $current_currency = 0;

	//connect 2 database
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());

	
	include("checklogin.php");
	include("cfg/settings.inc.php");
	include("cfg/currency.inc.php");
	include("functions.php");

	if (isset($add) && $add>0) //add product to cart with productID=$add
	{

		$q = db_query("select in_stock from ".PRODUCTS_TABLE." where productID=$add") or die (db_error());
		$is = db_fetch_row($q); $is = $is[0];

		if (!isset($log)) //save shopping cart in the session variables
		{

			//$gids[] contains product IDs
			//$counts[] contains product quantities ($counts[$i] corresponds to $gids[$i])
			//$gids[$i] == 0 means $i-element is 'empty'
			if (!isset($gids))
			{
				$gids = array();
				$counts = array();
			}
			//check for current item in the current shopping cart content
			$i=0;
			while ($i<count($gids) && $gids[$i] != $add) $i++;
			if ($i < count($gids)) //increase current product's quantity
			{
				if ($counts[$i]<$is)
					$counts[$i]++;
			}
			else if ($is > 0) //no item - add it to $gids array
			{
				$gids[count($gids)] = $add;
				$counts[count($counts)] = 1;
			}
			//save changes
			session_register("gids");
			session_register("counts");
		}
		else //authorized customer - get cart from database
		{
			$q = db_query("SELECT Quantity FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log' AND productID=".$add) or die (db_error());
			if ($row = db_fetch_row($q)) //increase current product's quantity
			{
				$row[0] = min($row[0],$is-1);
				$q = db_query("UPDATE ".SHOPPING_CARTS_TABLE." SET Quantity=".($row[0]+1)." WHERE customer_login='$log' AND productID=".$add) or die (db_error());
			}
			else //insert new item
			{
				if ($is > 0)
					$q = db_query("INSERT INTO ".SHOPPING_CARTS_TABLE." (customer_login, productID, Quantity) VALUES ('$log', $add, 1)") or die (db_error());
			}
		}

		updateCartInfo();

	}


	if (isset($remove) && $remove>0) { //remove from cart product with productID=$remove

		if (isset($log))
			$q = db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log' AND productID=".$remove) or die (db_error());
		else //remove from session vars
		{
			$i=0;
			while ($i<count($gids) && $gids[$i] != $remove) $i++;
			if ($i<count($gids)) $gids[$i]=0;
			//save changes
			session_register("gids");
			session_register("counts");

		}
		updateCartInfo();
	}


	if (isset($update)) //update shopping cart content
	{
		$vars = get_defined_vars();
		foreach ($vars as $key => $val)
			if (strstr($key, "count_"))
			{
			  $q = db_query("select in_stock from ".PRODUCTS_TABLE." where productID=".str_replace("count_","",$key)) or die (db_error());
			  $is = db_fetch_row($q); $is = $is[0];

			  if (isset($log)) //authorized user
			  {
				if ($val>0) //$val is a new items count in the shopping cart
				  {
					$val = min($val, $is);
					$q = db_query("UPDATE ".SHOPPING_CARTS_TABLE." SET Quantity=".floor($val)." WHERE customer_login='$log' AND productID=".str_replace("count_","",$key)."") or die (db_error());
				  }
				else //$val<=0 => delete item from cart
					$q = db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log' AND productID=".str_replace("count_","",$key)."") or die (db_error());
			  }
			  else //session vars
			  {
				if ($val>0)
				{
					for ($i=0; $i<count($gids); $i++)
						if ($gids[$i] == str_replace("count_","",$key))
						{
							$val = min($val, $is);
							$counts[$i] = floor($val);
						}
				}
				else //remove
				{
					$i=0;
					while ($gids[$i] != str_replace("count_","",$key) && $i<count($gids)) $i++;
					$gids[$i]=0;
					//save changes
					session_register("gids");
					session_register("counts");
				}
			  }
			}

		updateCartInfo();

	}

	if (isset($clear_cart)) //completely clear shopping cart
	{
		if (isset($log)) $q = db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die (db_error());
		else
		{
			//clear cart
			session_unregister("gids");
			session_unregister("counts");
			unset($gids);
			unset($counts);
		}

		updateCartInfo();

	}

?>
<html>

<head>
<link rel=STYLESHEET href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?=DEFAULT_CHARSET;?>">
<title>
<?=CART_TITLE;?></title>
<script>

function Order_shopping() //place order -- close window and continue in the main window
{
<?
	echo "window.opener.location = 'index.php?";
	if (!isset($log))
		echo "register=yes&order=yes";
	else
		echo "check_order=yes";
?>';
	window.close();
}

</script>
</head>

<body><center>

<?

if (isset($log)) //getting cart content from the database
{

	$q = db_query("SELECT productID, Quantity FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die (db_error());
	$i=0;
	$result = array();
	while ($row = db_fetch_row($q)) $result[$i++] = $row;

	if ($i) //cart is not empty
	{

		echo "<table width=100%><tr><td><b>".CART_TITLE.":</b></td>\n";
		echo "<td align=right><a href=\"cart.php?clear_cart=yes\"><img src=\"images/remove.jpg\" border=0 > <u>".CART_CLEAR."</u></a></td></table>\n";

		echo "<form action=\"cart.php?update=yes\" method=post>";
		echo "<table width=100% border=0 cellspacing=1 cellpadding=2 bgcolor=#$dark_color>\n<tr align=center bgcolor=#$middle_color>\n";
		echo "<td>".TABLE_PRODUCT_NAME."</td>\n<td>".TABLE_PRODUCT_QUANTITY."</td><td>".TABLE_PRODUCT_COST.", $currency_name[$current_currency]</td><td width=20></td></tr>\n";
		$k=0; //total shopping cart value (order value)
		for ($i=0; $i<count($result); $i++)
		{
			$q = db_query("SELECT name, Price FROM ".PRODUCTS_TABLE." WHERE productID='".$result[$i][0]."'") or die (db_error());
			if ($r = db_fetch_row($q))
			{
				echo "<tr bgcolor=white >\n<td>".$r[0]."</td>\n";
				echo "<td align=center><input type=\"text\" name=\"count_".$result[$i][0]."\" size=5 value=\"".$result[$i][1]."\"></td>\n";
				echo "<td align=center>".show_price($result[$i][1]*$r[1])."</td>\n";
				echo "<td align=center><a href=\"cart.php?remove=".$result[$i][0]."\"><img src=\"images/remove.jpg\" border=0 alt=\"".DELETE_BUTTON."\"></a></td>\n</tr>\n";

				$k += $result[$i][1]*$r[1];
			}
		}
		//total...
		echo "<tr bgcolor=white><td><font class=cat><b>".TABLE_TOTAL."</b></font></td><td><br><br></td><td bgcolor=#$light_color align=center><font class=cat><b>".show_price($k)."</b></font></td><td></td></tr>\n";

		echo "</table>\n";
		echo "<table width=100% border=0>\n";
		echo "<tr><td align=right><input type=\"submit\" class=bluebutton value=\"".UPDATE_BUTTON."\"></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";

		echo "<form action=\"javascript:Order_shopping();\" method=post>\n";
		echo "<table width=100% border=0>\n";
		echo "<tr><td align=center><input type=\"submit\" class=\"redbutton\" value=\"".CART_PROCEED_TO_CHECKOUT."\"></td>\n";
		echo "<td align=center><input type=\"button\" class=\"bluebutton\" value=\"".CLOSE_BUTTON."\" onClick=\"window.close();\"></td>\n";
		echo "</table>\n";
		echo "</form>\n";

	}
	else //empty!
	{
		echo "<font>".CART_EMPTY."</font><br><br>\n";
		echo "<form><input type=\"button\" class=\"bluebutton\" value=\"".CLOSE_BUTTON."\" onClick=\"window.close();\"></form>\n";
	}
}
else { //unauthorized user - get cart from session vars

	//shopping cart items count
	$c=0;
	if (isset($gids))
		for ($j=0; $j<count($gids); $j++)
			if ($gids[$j]) $c++;

	//not empty?
	if (isset($gids) && $c)
	{
		echo "<table width=100%><tr><td><b>".CART_TITLE.":</b></td>\n";
		echo "<td align=right><a href=\"cart.php?clear_cart=yes\"><img src=\"images/remove.jpg\" border=0> <u>".CART_CLEAR."</u></a></td></table>\n";

		echo "<form action=\"cart.php?update=yes\" method=post>";
		echo "<table border=0 cellspacing=1 cellpadding=2 bgcolor=#$dark_color width=100%>\n<tr align=center bgcolor=#$middle_color>\n";
		echo "<td>".TABLE_PRODUCT_NAME."</td>\n<td>".TABLE_PRODUCT_QUANTITY."</td><td>".TABLE_PRODUCT_COST.", $currency_name[$current_currency]</td><td width=20></td></tr>\n";

		$k=0; //total cart value
		for ($i=0; $i<count($gids); $i++)
		  if ($gids[$i])
		  {
			$q = db_query("SELECT name, Price FROM ".PRODUCTS_TABLE." WHERE productID=$gids[$i]") or die (db_error());
			if ($r = db_fetch_row($q))
			{
				echo "<tr bgcolor=white >\n<td>".$r[0]."</td>\n";
				echo "<td align=center>\n";
				echo "<input type=\"text\" name=\"count_$gids[$i]\" size=5 value=\"".$counts[$i]."\">\n</td>\n";
				echo "<td align=center>".show_price($counts[$i]*$r[1])."</td>\n";
				echo "<td align=center><a href=\"cart.php?remove=$gids[$i]\" class=small><img src=\"images/remove.jpg\" border=0 alt=\"".DELETE_BUTTON."\"></a></td>\n</tr>\n";

				$k += $counts[$i]*$r[1];
			}
		  }

		//total...
		echo "<tr bgcolor=white><td><font class=cat><b>".TABLE_TOTAL."</b></font></td><td><br><br></td><td bgcolor=#$light_color align=center><font class=cat><b>".show_price($k)."</b></font></td><td></td></tr>\n";

		echo "</table>\n";

		echo "<table width=100% border=0>\n";
		echo "<tr><td align=right><input class=\"bluebutton\" type=\"submit\" value=\"".UPDATE_BUTTON."\"></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";

		echo "<form action=\"javascript:Order_shopping();\" method=post>\n";
		echo "<table width=100% border=0>\n";
		echo "<tr><td align=center><input type=\"submit\" class=\"redbutton\" value=\"".CART_PROCEED_TO_CHECKOUT."!\"></td>\n";
		echo "<td align=center><input type=\"button\" class=\"bluebutton\" value=\"".CLOSE_BUTTON."\" onClick=\"window.close();\"></td>\n";
		echo "</table>\n";

		echo "</form>\n";

	}
	else
	{
		echo "<p><font>".CART_EMPTY."</font></p>\n";
		echo "<form><input type=\"button\" class=\"bluebutton\" value=\"".CLOSE_BUTTON."\" onClick=\"window.close();\"></form>\n";
	}

}

?>
</center></body>

</html>