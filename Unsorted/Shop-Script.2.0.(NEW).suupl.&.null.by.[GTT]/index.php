<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



	//core file
	include("cfg/connect.inc.php");
	include("includes/database/".DBMS.".php");

	// ------------------------OFTEN USED FUNCTIONS--------------------------//

function showGood($product_id,$brief,&$out)
{

	//show product with productID=$product_id.
	//$brief indicates whether show full product information or not
	//&$out - output-buffer

	global $light_color;
	global $middle_color;
	global $dark_color;

	$query = $brief ? "select categoryID, name, brief_description, customers_rating, Price, picture, in_stock, thumbnail, customer_votes, big_picture, list_price from ".PRODUCTS_TABLE." where productID=$product_id and enabled=1" :
			  "select categoryID, name, description, customers_rating, Price, picture, in_stock, thumbnail, customer_votes, big_picture, list_price from ".PRODUCTS_TABLE." where productID=$product_id and enabled=1";

	$q = db_query($query) or die (db_error());

	$a = db_fetch_row($q); //product array

	$which = $brief ? "small" : "big"; //shopping cart icon

	$out .= "

<p><table width=95% border=0 cellspacing=1 cellpadding=2>
<tr>
<td width=1% align=center valign=top rowspan=2> <!-- product picture -->
	";

	if ($brief)
	{ //showing a thumbnail
		if ($a[7] && file_exists("products_pictures/".$a[7]))
		{
			$size = getimagesize("products_pictures/".$a[7]);
			$out .= "<a href=\"index.php?productID=$product_id\"><img src=\"products_pictures/".$a[7]."\" border=0 $size[3] alt=\"".MORE_INFO_ON_PRODUCT." ...\">\n";
			$out .= "<font class=small><nobr>".MORE_INFO_ON_PRODUCT."</nobr></font></a>\n";
		}
		else //no thumbnail - showing regular image
		  if ($a[5] && file_exists("products_pictures/".$a[5]))
		  {
			$size = getimagesize("products_pictures/".$a[5]);
			$out .= "<a href=\"index.php?productID=$product_id\"><img src=\"products_pictures/".$a[5]."\" border=0 alt=\"".MORE_INFO_ON_PRODUCT." ...\">\n";
			$out .= "<nobr>".MORE_INFO_ON_PRODUCT."</nobr></a>\n";
		  }
	}
	else { //showing regular image
		if ($a[5] && file_exists("products_pictures/".$a[5]))
		{
		  $size = getimagesize("products_pictures/".$a[5]);
		  if ($a[9] && file_exists("products_pictures/".$a[9])) //if there's big image uploaded
		  {
			$out .= "<a target=top href=\"products_pictures/$a[9]\"><img border=0 src=\"products_pictures/".$a[5]."\" $size[3] alt=\"".ENLARGE_PICTURE."...\">";
			$out .= "<br><font class=average><nobr>".ENLARGE_PICTURE."</nobr></font></a>\n";
		  }
		  else
			$out .= "<img src=\"products_pictures/".$a[5]."\" $size[3] alt=\"$a[1]\">\n";
		}
		else //no regular image - watching for thumbnail
		  if ($a[7] && file_exists("products_pictures/".$a[7]))
		  {
			$size = getimagesize("products_pictures/".$a[7]);
			$out .= "<img src=\"products_pictures/".$a[7]."\" border=0 $size[3] alt=\"$a[1]\">\n";
		  }
	};

	$out .= "

</td>

<td valign=top width=99%> <!-- different product properties -->

<table width=100% border=0 cellpadding=4>

<tr>
<td valign=top>

<table border=0>
<tr>
<td>

	";



	if ($brief)
		$out .= "<a class=cat href=\"index.php?productID=$product_id\">$a[1]</a>";
	else
		$out .= "<font class=cat><b>$a[1]</b></font>";

	$out .= "</td>";


	//show product's rating
	if ($a[8] > 0)
	{
		if ($brief)
			$out .= "</tr><tr><td>";
		else
			$out .= "<td>";
		$out .= "&nbsp;&nbsp;&nbsp;";
		for ($i=0; $i<round($a[3]); $i++) $out .= "<img src=\"images/redstar.gif\">";
		for (    ; $i < 5; $i++) $out .= "<img src=\"images/blackstar.gif\">";
		if (!$brief) $out .= " ($a[8] ".VOTES_FOR_ITEM_STRING.")";
		$out .= "</td>";
		if ($brief)
			$out .= "</tr>";

	}


	$out .= "

</td>
</tr>
</table>
<br>

	";

	$q = db_query("SELECT count(*) FROM ".DISCUSSIONS_TABLE." WHERE productID=$product_id") or die (db_error());
	$k = db_fetch_row($q); $k = $k[0];

	if (!$brief) $out .= "<a href=\"index.php?productID=$product_id&discuss=yes\">".DISCUSS_ITEM_LINK."</a> ($k ".POSTS_FOR_ITEM_STRING.")<br><br>";

	$out .= "

</td>
<td align=right valign=top> <!-- add to cart link -->

	";

	//add 2 cart link in case product is in stock
	if ($a[6] > 0) $out .= "<a href=\"javascript:open_window('cart.php?add=$product_id',400,300);\"><img src=\"images/cart_$which.gif\" border=0 alt=\"".ADD_TO_CART_STRING."\"></a>";
	else $out .= "&nbsp;";


	$out .= "
</td>
</tr>

<tr>
<td colspan=2>

	";

	if ($a[10] > 0 && $a[10]!=$a[4]) //show list price
	{
		$out .= LIST_PRICE.": <font color=brown><strike>";
		$out .= show_price($a[10]);
		$out .= "</strike></font><br>";
	}

	$out .= "<b>".CURRENT_PRICE.": <font ";
	if (!$brief) $out .= "class=cat";
	$out .= " color=red>";
	if ($a[4] <= 0) $out .= "n/a"; //no price for item available
	else $out .= show_price($a[4]);
	$out .= "</font></b>\n";

	if ($a[10]>0 && $a[10]!=$a[4]) //show 'you save' value
	{
		$out .= "<br>".YOU_SAVE.": <font color=brown>";
		$out .= show_price($a[10]-$a[4]);
		$out .= " (".(ceil(((($a[10]-$a[4])/$a[10])*100)))."%)";
		$out .= "</font><br>";
	}

	$out .= "

</td>
</tr>

	";

	if (!$brief) { // in stock info

		$out .= "

<tr>
<td colspan=2>

		";

		if (!$brief) $out .= "<br><br>";
		$out .= IN_STOCK.": <b>\n";
		$out .= ($a[6] > 0) ? $a[6] : "<font color=red>".ANSWER_NO."</font>";
		$out .= "</b>";

		//extra fields?
		$out .= "<p>";
		$q1 = db_query("select optionID, name from ".PRODUCT_OPTIONS_TABLE."") or die (db_error());
		while ($row = db_fetch_row($q1))
		  if ($row[1]!="")
		  {
			$q = db_query("select option_value from ".PRODUCT_OPTIONS_VALUES_TABLE." where productID=$product_id AND optionID=$row[0]") or die (db_error());
			$val = db_fetch_row($q);
			if ($val && $val[0]!="")
			{
				$out .= "$row[1]: <b>$val[0]</b><br>";
			}
		  }

		$out .= "

</td>
</tr>
	";

	};

	

	$out .= "

<tr>
<!-- description -->

	";

	//now show description (brief or else)

	$out .= "<td width=99%";
	if ($brief) $out .= " colspan=2";
	$out .= ">";
	if (!$brief) $out .= "<br><br>";
	$out .= "<p>$a[2]";
	$out .= "</td>\n";
	$out .= "<td width=1% valign=top align=right>"; // poll

	if (!$brief) {

		$out .= "

<center>
<form action=\"index.php\" method=get>
<table border=0 cellspacing=1 cellpadding=2 bgcolor=#$middle_color>
<tr><td align=center>".VOTING_FOR_ITEM_TITLE."</td></tr>
<tr bgcolor=white><td>
<input type=\"radio\" name=\"mark\" value=\"5\" checked>".MARK_EXCELLENT."<br>
<input type=\"radio\" name=\"mark\" value=\"3.8\">".MARK_GOOD."<br>
<input type=\"radio\" name=\"mark\" value=\"2.5\">".MARK_AVERAGE."<br>
<input type=\"radio\" name=\"mark\" value=\"1\">".MARK_POOR."<br>
<input type=\"radio\" name=\"mark\" value=\"0.1\">".MARK_PUNY."
</td></tr>
</table><br>
<input type=\"hidden\" name=\"vote\" value=\"$product_id\">
<input type=\"submit\" class=\"redbutton\" value=\"".VOTE_BUTTON."\">
</form>
</center>

		";
	};

	$out .= "</td></tr></table>";

	//related products
	if (!$brief) {
		$q = db_query("SELECT count(*) FROM ".RELATED_PRODUCTS_TABLE." WHERE Owner=$product_id") or die (db_error());
		$cnt = db_fetch_row($q);
		if ($cnt[0] > 0)
		{
			$q = db_query("SELECT productID FROM ".RELATED_PRODUCTS_TABLE." WHERE Owner=$product_id") or die (db_error());

			// show related products in table
			$out .= "<br><br><br></tr>\n<tr><td>\n<p><u>".STRING_RELATED_ITEMS.":</u><br>";
			$out .= "<table width=40%>";
			while ($row = db_fetch_row($q))
			{
				$p = db_query("SELECT productID, name, Price FROM ".PRODUCTS_TABLE." WHERE productID=$row[0] AND Price>0") or die (db_error());
				if ($r = db_fetch_row($p))
				{
				  $out .= "<tr>";
				  $out .= "<td width=100%>&nbsp;<a href=\"index.php?productID=$r[0]\">$r[1]</a></td>";
				  $r[2] = show_price($r[2]);
				  $out .= "<td width=1% align=center><nobr><font color=brown>$r[2]</font></nobr></td>";
				  $out .= "</tr>";
				}
			}
			$out .= "</table>\n";
			$out .= "</td></tr>";
		}
	}

	$out .= "

</td>

</tr>
</table>

	";

} //showGood





function showSubCategories(&$categories, $i, &$out) 
{
	//show subcategories of category with index $i

	$out .= "<p>\n<table border=0 width=100% cellpadding=3><tr>";
	if (trim($categories[$i][5])!="") $out .= "<td width=1% valign=top><a href=\"index.php?categoryID=".$categories[$i][0]."\" class=cat><img src=\"products_pictures/".trim($categories[$i][5])."\" border=0></td>";
	$out .= "<td width=99% valign=top><a href=\"index.php?categoryID=".$categories[$i][0]."\" class=cat>".trim($categories[$i][1])."</a>\n";
	$out .= "[<b>".$categories[$i][3]."</b>]:<br>\n";
	//show
	$pl = 0;
	for ($j=0; $j<count($categories); $j++)
	  if ($categories[$j][2] == $categories[$i][0])
	  {
		if ($pl) $out .= "| ";
		else
		{
			$pl=1;
			$out .= "&nbsp;&nbsp;";
		}
		$out .= "<a class=standard href=\"index.php?categoryID=".$categories[$j][0]."\">".trim($categories[$j][1])."</a>\n";
	  }
	$out .= "</td></tr></table>\n";

} //showSubCategories



function categoryIndexInArray(&$list, $id)
{
	//search for index of category with categoryID=$id at array $list

	$j = 0;
	while ($j<count($list) && $list[$j][0]!=$id) $j++;
	if ($j == count($list)) return 0;
	else return $j;

} //categoryIndexInArray



function calculatePath(&$categories, $categoryID)
{

	//calculates path to category with categoryID=$categoryID to the root.
	//f.e. Root/Computers/Notebooks/Compaq

	$path = array();
	$i = $categoryID;
	if ($i) do
	{
		$c = categoryIndexInArray(&$categories,$i);
		$path[] = $categories[$c][0];
		$i = $categories[$c][2];
	} while ($i);

	$path[]=0; //the last one is root
	$path = array_reverse($path); //rotate...

	return $path;

} //calculatePath



function moveCartFromSession2DB() //all products in shopping cart, which are in session vars, move to the database
{

	global $gids;
	global $counts;
	global $log;

	if (isset($gids) && isset($log))
	{
		//delete old cart content of the user
		$q = db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die(db_error());

		for ($i=0; $i<count($gids); $i++)
			if ($gids[$i]) $q = db_query("INSERT INTO ".SHOPPING_CARTS_TABLE." (customer_login, productID, Quantity) VALUES ('$log',$gids[$i],$counts[$i])");

		session_unregister("gids");
		session_unregister("counts");
	}

} //moveCartFromSession2DB



function processCategories(&$list, $level, $path, $sel,$out) //add elements to the categories navigation table
{

	//$list[] - categories array
	//$level - current: 0 for main categories, 1 for it's subcategories, etc.
	//$path - path from root to the selected category
	//$sel -- categoryID of selected category
	//$out -- output-buffer

	for ($i=0; $i<count($list); $i++)
	{

		if ($list[$i][2] == $path[$level])
		{
			$out .= "<tr><td>";
			for ($j=0; $j<$level; $j++) $out .= "&nbsp;&nbsp;";

			if ($list[$i][0] == $sel) //no link to the selected category
			{
				$out .= "<b><font class=light><nobr>> ".$list[$i][1]."</nobr></font></b>\n";
				$out .= "</td></tr>\n";
			}
			else //make a link
			{
				$out .= "<a href=\"index.php?categoryID=".$list[$i][0]."\"";
				if ($level) $out .= " class=lightstandard";
				else $out .= " class=light";
				$out .= ">".$list[$i][1]."</a></td></tr>\n";
			}
		}

		//process subcategories
		if ($level+1<count($path) && $list[$i][0] == $path[$level+1])
			$out = processCategories(&$list,$level+1,$path,$sel,$out);

	}

	return $out;

} //processCategories


function pricessCategories(&$list, $parent,$level,&$out)
{

	//same as processCategories(), except it creates a pricelist of the shop

	global $middle_color;

	for ($i=0; $i<count($list); $i++)
	 if ($list[$i][2] == $parent)
	 {

		//show category name
		$out .= "<tr><td colspan=2";

		$r = hexdec(substr($middle_color, 0, 2)); 
		$g = hexdec(substr($middle_color, 2, 2)); 
		$b = hexdec(substr($middle_color, 4, 2)); 
		$m = (float)max($r, max($g,$b));

		//define back color of the cell
		$r = round((190+20*min($level,3))*$r/$m);
		$g = round((190+20*min($level,3))*$g/$m);
		$b = round((190+20*min($level,3))*$b/$m);

		$c = dechex($r).dechex($g).dechex($b); //final color

		$out .= " bgcolor=#$c>";
		for ($j=0; $j<$level; $j++) $out .= "&nbsp;&nbsp;";
		$out .= "<a href=\"index.php?categoryID=".$list[$i][0]."\">";
		$out .= str_replace("<","&lt;",$list[$i][1])."</a></td>\n"; //w -- parent of current category
		$out .= "</tr>\n";

		//show products
		showproducts($list[$i][0], $level, &$out);

		//process all subcategories
		pricessCategories(&$list, $list[$i][0],$level+1,&$out);
 	}

} //pricessCategories


function showproducts($cid, $level, &$out) //show products of selected category in the pricelist
{

	

	$q = db_query("SELECT count(*) FROM ".PRODUCTS_TABLE." WHERE categoryID=$cid") or die (db_error());
	$cnt = db_fetch_row($q);
	if ($cnt[0] > 0) $out .= "";
	$q = db_query("SELECT productID, name, Price FROM ".PRODUCTS_TABLE." WHERE categoryID=$cid AND Price>0") or die (db_error());
	while ($row = db_fetch_row($q))
	{
		$out .= "<tr bgcolor=white><td>";
		for ($i=0; $i<$level; $i++) $out .= "&nbsp;&nbsp;";
		$out .= "<a href=\"index.php?productID=$row[0]\">$row[1]</a>\n";
		if (!$row[2]) $row[2] = "n/a";
		else $row[2] = show_price($row[2]);
		$out .= "</td><td width=1% align=right><nobr>$row[2]</nobr></td></tr>";
	}

} //showproducts




	// -------------------------INITIALIZATION-----------------------------//

	session_start();

	//select new language?
	if (isset($new_language))
	{
		$current_language = $new_language;
		session_register("current_language");
	}

	include("cfg/settings.inc.php");
	include("functions.php");

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


	//connect to the database
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());

	//$output is the main output buffer.
	//include template file (it is set in the language file)
	$output = implode("",file($lang_list[$current_language]->template));

	//authorized access check
	include("checklogin.php");

	//currencies file
	include("cfg/currency.inc.php");

	//# of selected currency
	if (!isset($current_currency)) $current_currency = 0;

	//load all categories to array $cats to avoid multiple queries
	$cats = array();
	$i=0;
	$q = db_query("SELECT categoryID, name, parent, products_count, description, picture FROM ".CATEGORIES_TABLE." where categoryID<>0 ORDER BY name") or die (db_error());	
	while ($row = db_fetch_row($q)) $cats[$i++] = $row;

	if (!isset($categoryID)) $categoryID = 0;

	if (!isset($vote_completed)) $vote_completed = array();

	if (!isset($offset)) $offset=0;
	//checking for proper $offset init
	if ($offset<0 || $offset%$products_count) $offset=0;




	// -----------------------------------------------------------------//

	if (isset($logout)) //user logout
	{
		unset($log);
		session_unregister("log");
		session_unregister("pass");
		header("Location: index.php");
	}
	else
	if (isset($enter) && !isset($log)) //user login
	{

		$q = db_query("SELECT cust_password FROM ".CUSTOMERS_TABLE." WHERE Login='$user_login'") or die (db_error());
		$row = db_fetch_row($q);

		//serching for user in the database
		if (($row) && (!strcmp(trim($row[0]),stripslashes($user_pw))))
		{

			//yes. start new session
			$log = $user_login;
			$pass = stripslashes($user_pw);
			session_register("log"); //$log -- authorized user login
			session_register("pass");

			moveCartFromSession2DB();

			//update prefered currency
			db_query("UPDATE ".CUSTOMERS_TABLE." SET default_currency=$current_currency WHERE Login='$log'") or die (db_error());

			//is it admin?
			if (!isset($order))
				if (!strcmp($log,ADMIN_LOGIN))
						header("Location: admin.php");
				else
				{
					$z = "";
					if (isset($productID)) $z="?productID=$productID";
					else
						if (isset($categoryID)) $z="?categoryID=$categoryID";
					header("Location: index.php$z");
				}

		}
		else //login error
			$wrongLoginOrPw = 1;

	}

	if (isset($subscribe) && !strcmp($subscribe,"true") && isset($email)) //subscribe for the news
	{
		db_set_identity(MAILING_LIST_TABLE);
		db_query("INSERT INTO ".MAILING_LIST_TABLE." (Email) VALUES ('$email')") or die (db_error());
		$z = "";
		if (isset($productID)) $z="&productID=$productID";
		else
			if (isset($categoryID)) $z="&categoryID=$categoryID";
		header("Location: index.php?subscribe=done$z");
	}

	if (isset($killuser) && isset($log) && strcmp($log, ADMIN_LOGIN)) //terminate user account
	{
		db_query("DELETE FROM ".CUSTOMERS_TABLE." WHERE Login='$log'") or die (db_error());
		db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die (db_error()); //clear his/her cart
		unset($log);
		unset($pass);
		session_unregister("log");
		session_unregister("pass");
		session_unregister("order_step");
		$order_step=0;
		header("Location: index.php?kill_successful=1");
	}

	if (isset($vote)) //vote for product
	{
		if (!isset($vote_completed[$vote]) && isset($mark) && $mark)
			$q = db_query("UPDATE ".PRODUCTS_TABLE." SET customers_rating=(customers_rating*customer_votes+$mark)/(customer_votes+1), customer_votes=customer_votes+1 WHERE productID=".$vote) or die (db_error());
		$productID = $vote;
		$vote_completed[$vote] = 1;
		session_register("vote_completed");
	}

	if (isset($productID)) //to rollout categories navigation table
	{
		$q = db_query("SELECT categoryID FROM ".PRODUCTS_TABLE." WHERE productID=$productID") or die (db_error());
		$r = db_fetch_row($q);
		if ($r) $categoryID = $r[0];
	}

	if (isset($searchstring)) //make a simple search
	{

		$products_search = array();
		$cats_search = array();
		$g_search_count = 0;

		//explode string to a set separate of words
		$searchstring = trim(str_replace("'","",stripslashes($searchstring)));
		$search = explode(" ",$searchstring);

		$result=array();
		$r = array();
		$i = 0;
		$k = 0;

		if ($searchstring)
		{

			//searching for categories
			$s = "SELECT categoryID, name FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and name LIKE '%".$search[0]."%' ";
			for ($i=1; $i<count($search); $i++)
			{
				$s .= "AND name LIKE '%".$search[$i]."%' ";
			}
			$s.="ORDER BY name";
			$q = db_query($s);
			while ($row = db_fetch_row($q)) $cats_search[] = $row;

			//searching for products
			if (isset($inside) && isset($oldproducts))
				$s_search = stripslashes(base64_decode($oldproducts))." AND";
			else
				$s_search = "SELECT productID FROM ".PRODUCTS_TABLE." WHERE ";
			$s_search .= "((name LIKE '%".$search[0]."%' OR description LIKE '%".$search[0]."%' OR brief_description LIKE '%".$search[0]."%') ";
			for ($j=1; $j<count($search); $j++) $s_search .= "AND (name LIKE '%".$search[$j]."%' OR description LIKE '%".$search[$j]."%' OR brief_description LIKE '%".$search[$j]."%') ";
			$s_search .= ") ";

			$q = db_query(str_replace("SELECT productID", "SELECT count(*)", $s_search)) or die (db_error());
			$g_search_count = db_fetch_row($q); $g_search_count = $g_search_count[0];

			if ($offset>$g_search_count) $offset = 0;

			$q = db_query($s_search."ORDER BY customers_rating DESC") or die (db_error());

			$i = 0;
			while ($row = db_fetch_row($q))
			{
				if ($i >= $offset && $i < $offset+$products_count)
					$products_search[] = $row;
				$i++;
			}

		}
	}

	if (isset($change_c) && isset($change_currency)) //change currency type
	{

		$current_currency = $change_currency;
		session_register("current_currency");

		//make changes in the database if user is authorized
		if (isset($log))
			db_query("UPDATE ".CUSTOMERS_TABLE." SET default_currency=$change_currency WHERE Login='$log'") or die (db_error());

		header("Location: index.php");

	}

	if (isset($add_topic) && isset($productID)) // add post to the product discussion
	{

		db_set_identity(DISCUSSIONS_TABLE);
		db_query("INSERT INTO ".DISCUSSIONS_TABLE." (productID, Author, Body, add_time, Topic) VALUES ($productID, '$nick','$body','".get_current_time()."','$topic')") or die (db_error());

		header("Location: index.php?productID=$productID&discuss=yes");
	}


	if (isset($remove_topic) && isset($productID) && isset($log) && !strcmp($log, ADMIN_LOGIN)) // delete topic in the discussion
	{
		db_query("DELETE FROM ".DISCUSSIONS_TABLE." WHERE DID=$remove_topic") or die (db_error());
		header("Location: index.php?productID=$productID&discuss=yes");
	}



	// ---------------------- TEMPLATE PARSER --------------------//


	// {TITLE} //
	$r = array(); $r[0] = "";
	if (isset($categoryID) && !isset($productID) && $categoryID>0)
	{
		$q = db_query("SELECT name FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and categoryID=$categoryID") or die (db_error());
		$r = db_fetch_row($q); $out = str_replace("\"","'",$r[0]." - ".$shopname);
		$output = str_replace("{TITLE}", $out, $output);
	}
	else if (isset($productID) && $productID>0)
	{
		$q = db_query("SELECT name FROM ".PRODUCTS_TABLE." WHERE productID=$productID") or die (db_error());
		$r = db_fetch_row($q); $out = str_replace("\"","'",$r[0]." - ".$shopname);
		$output = str_replace("{TITLE}", $out, $output);
	}
	else $output = str_replace("{TITLE}", "$shopname", $output);


	// {META} //
	$r = array(); $r[0] = "";
	if (isset($categoryID) && !isset($productID) && $categoryID>0)
	{
		$q = db_query("SELECT name, description FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and categoryID=$categoryID") or die (db_error());
		$r = db_fetch_row($q); $out = str_replace("\"","'",$r[0].", ".$r[1]);
		$output = str_replace("{META}", $out, $output);
	}
	else if (isset($productID) && $productID>0)
	{
		$q = db_query("SELECT name, brief_description FROM ".PRODUCTS_TABLE." WHERE productID=$productID") or die (db_error());
		$r = db_fetch_row($q); $out = str_replace("\"","'",$r[0].", ".$r[1]);
		$output = str_replace("{META}", $out, $output);
	}
	else $output = str_replace("{META}", "$shopname, powered by Shop-Script", $output);




	// {REGISTER/HOME} //

	if (isset($log)) //link to "My account" page
	{
		$out = "<a class=light href=\"index.php?user_details=yes\">".MY_ACCOUNT_LINK."</a>";
	}
	else //link to the registration
	{
		$out = "<a class=light href=\"index.php?register=yes\">".REGISTER_LINK."</a>";
	}
	$output = str_replace("{REGISTER/HOME}", $out, $output);



	// {LANGUAGE} //

	$out = "<form name=lang_form>\n";
	$out.= "<select name=lang onChange=\"window.location='index.php?new_language='+lang_form.lang.value;\">\n";
	for ($i=0;$i<count($lang_list);$i++)
	{
		$c = ($current_language == $i) ? "selected" : "";
		$out.="<option value=$i $c>".$lang_list[$i]->description."</option>\n";
	}
	$out.= "</select>\n";
	$out.= "</form>\n";
	$output = str_replace("{LANGUAGE}", $out, $output);



	// {CURRENCY} //
	if (count($currency_name) > 1)
		$out = "<a class=light href=\"index.php?currency=yes\">".CHANGE_CURRENCY_LINK."</a>";
	else $out = "";
	$output = str_replace("{CURRENCY}", $out, $output);


	// {AUTHORIZATION} //

	if (!isset($log)) { //login form


		// !!!     NOTICE THAT YOU SHOULD PLACE SYMBOL \ BEFORE SYMBOL "     !!! //

		$out = "

<font class=middle><b>".STRING_AUTHORIZATION."</b></font>

<table cellspacing=0>

<form action=\"index.php\" method=post>

<tr>
 <td>
	<table border=0>
	 <input type=\"hidden\" name=\"enter\" value=\"1\">
	 <tr>
		<td align=right><font class=light>".CUSTOMER_LOGIN."</font></td>
		<td><input type=\"text\" class=ss name=\"user_login\" size=10></td>
	 </tr>
	 <tr>
		<td align=right><font class=light>".CUSTOMER_PASSWORD."</font></td>
		<td><input name=\"user_pw\" class=ss type=\"password\" size=10></td>
	 </tr>
	</table>
 </td>
 <td>
	<input type=\"submit\" class=redbutton value=\"".OK_BUTTON."\"><br>
	<a href=\"index.php?logging=yes\" class=lightsmall>".FORGOT_PASSWORD_LINK."</a>
 </td>
</tr>

		";

		if (isset($productID)) $out .= "<input type=hidden name=productID value=\"$productID\">";
		if (isset($categoryID)) $out .= "<input type=hidden name=categoryID value=\"$categoryID\">";

		$out .= "
</form>

</table>

		";

	}
	else // logout link
	{

		// !!!     NOTICE THAT YOU SHOULD PLACE SYMBOL \ BEFORE SYMBOL "     !!! //

		$out = "

<table>
	<tr><td align=center><a class=light href=\"index.php?logout=yes\">".LOGOUT_LINK."</a></td></tr>
</table>

		";
	}
	$output = str_replace("{AUTHORIZATION}", $out, $output);







	// {SEARCH} //

	// search form
	// !!!     NOTICE THAT YOU SHOULD PLACE SYMBOL \ BEFORE SYMBOL "     !!! //


	$tmp = isset($searchstring) ? $searchstring : "";
	$out = "

<table cellspacing=0 cellpadding=1 border=0>

<form action=\"index.php\" method=get>

<tr>
<td><font class=light>".STRING_SEARCH."</font></td>
<td><input type=\"text\" class=\"ss\" name=\"searchstring\" size=7 value=\"$tmp\"></td>
<td><nobr>&nbsp;<input type=\"image\" border=0 src=\"images/search.gif\">&nbsp;&nbsp;&nbsp;</nobr></td>
</tr>

	";


	if (isset($s_search)) $out .= "<input type=hidden name=\"oldproducts\" value=\"".base64_encode($s_search)."\">";

	$tmp = isset($inside) ? " checked" : "";

	$out .= "

<tr>
<td colspan=3>
<input type=\"checkbox\" name=\"inside\"$tmp><font class=light>".STRING_SEARCH_IN_RESULTS."</font>
</td>
</tr>

</form>
</table>
<a href=\"index.php?adv_search=true\" class=lightsmall>".ADVANCED_SEARCH_LINK."</a>

	";

	$output = str_replace("{SEARCH}", $out, $output);






	// {SHOPPING_CART} //

	// !!!     NOTICE THAT YOU SHOULD PLACE SYMBOL \ BEFORE SYMBOL "     !!! //

	$out = "

		<table>
			<form name=\"shopping_cart_form\">
			<tr><td>

	";

	//shopping cart value
	$k=0;
	$cnt = 0;
	if (isset($log)) //taking products from database
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
	if (isset($gids)) //...session vars
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


	if ($k) $out .= "<input class=cart type=text name=gc value=\"$cnt ".CART_CONTENT_NOT_EMPTY."\"><br><input type=text class=cart name=ca value=\"".show_price($k)."\">";
	else $out .= "<input class=cart type=text name=gc value=\"".CART_CONTENT_EMPTY."\"><br><input type=text class=cart name=ca value=\"\">";

	//create a link to checkout process (depends on the fact is user authorized or not)
	$link = isset($log) ? "index.php?check_order=yes" : "index.php?register=yes&order=yes";
	$out .= "<br><a class=lightsmall href=\"$link\"><nobr>".CART_PROCEED_TO_CHECKOUT."</nobr></a>";

	$out .= "

		</td></tr></form></table>

	";

	$output = str_replace("{SHOPPING_CART}", $out, $output);






	// {CATEGORIES} //

	$path = calculatePath(&$cats, $categoryID); //path from root to selected category
	$out = processCategories(&$cats,0,$path,$categoryID,"");
	$output = str_replace("{CATEGORIES}", $out, $output);





	// {NEWS} //

	$q = db_query("SELECT NID, add_date, Body, add_stamp FROM ".NEWS_TABLE." ORDER BY add_stamp DESC") or die (db_error());
	$out = "<form action=\"index.php?subscribe=true\" name=\"form1\" onSubmit=\"return validate(this);\" method=post>";

	while ($row = db_fetch_row($q))
	{
		$out .= "<tr><td><b><font class=light>$row[1]</font></b></td></tr>";
		$out .= "<tr><td><font class=middle>".nl2br(str_replace("<","&lt;",$row[2]))."</font><br><br></td></tr>";
	}

	$out .= "<tr><td align=center>";
	if (!isset($subscribe))
	{
		$out .= "<font class=light>".CUSTOMER_SUBSCRIBE_FOR_NEWS.":</font><br><input type=text name=email value=\"Email\" class=ss size=15><br>";
		$out .= "<input type=submit class=redbutton value=\"OK\">";
		if (isset($productID)) $out .= "<input type=hidden name=productID value=\"$productID\">";
		if (isset($categoryID)) $out .= "<input type=hidden name=categoryID value=\"$categoryID\">";
	}
	else
	{
		$out .= "<table cellpadding=5><tr><td align=center><font class=light>".STRING_THANK_YOU."</font></td></tr></table>";
	}
	$out .= "</td></tr></form>";

	$output = str_replace("{NEWS}", $out, $output);





	// {VOTING} //

	$out = "";
	$out .= "<Table width=100%>\n<Tr>\n<Td>\n";

	if (!isset($save_voting_results)) { //main voting form

		$f = file("cfg/voting.txt");

		$r = file("cfg/voting_results.txt");
		$m = $r[0] ? $r[0] : 0;
		$m = max($m, 1);
		for ($i=0; $i<count($r); $i++) if ($m < $r[$i]) $m = $r[$i];

		//show voting form
		$out .= "<form action=\"index.php\" method=post>";
		$out .= "<table cellspacing=1 cellpadding=3 width=100%>\n";
		$out .= "<tr><td colspan=2><b>&nbsp;&nbsp;<font class=light>$f[0]</font></b></td></tr>\n<tr><td>\n";
		for ($i=1; $i<count($f); $i++) {
			$out .= "<table cellspacing=0 cellpadding=0><tr><td><input type=radio name=opt value=$i></td><td><font class=middle>$f[$i]</font></td></tr></table>\n";
		};
		$out .= "</td></tr></table>\n";

		$out .= "<p><center><input type=submit name=save_voting_results value=\"".OK_BUTTON."\"></center>\n";

		if (isset($productID)) $out .= "<input type=hidden name=productID value=\"$productID\">";
		if (isset($categoryID)) $out .= "<input type=hidden name=categoryID value=\"$categoryID\">";
		if (isset($currency)) $out .= "<input type=hidden name=currency value=\"$currency\">";
		if (isset($user_details)) $out .= "<input type=hidden name=user_details value=\"$user_details\">";
		if (isset($aux_page)) $out .= "<input type=hidden name=aux_page value=\"$aux_page\">";
		if (isset($show_price)) $out .= "<input type=hidden name=show_price value=\"$show_price\">";
		if (isset($register)) $out .= "<input type=hidden name=register value=\"$register\">";
		if (isset($adv_search)) $out .= "<input type=hidden name=adv_search value=\"$adv_search\">";
		if (isset($searchstring)) $out .= "<input type=hidden name=searchstring value=\"$searchstring\">";
		if (isset($order)) $out .= "<input type=hidden name=order value=\"$order\">";
		if (isset($check_order)) $out .= "<input type=hidden name=check_order value=\"$check_order\">";


		$out .= "</form>\n";
	}
	else {

			//vote and show results

			$f = file("cfg/voting.txt");

			//increase votes value
			if (!($r = file("cfg/voting_results.txt")))
			{
				$r = array();
				for ($i=0; $i<count($f)-1; $i++) $r[$i] = 0;
			}

			if (!isset($vote_completed[0]) && isset($opt))
				$r[$opt-1] = $r[$opt-1] + 1;

			//save modifications
			$f1 = fopen("cfg/voting_results.txt","w");
			for ($i=0; $i<count($r); $i++) fputs($f1, trim($r[$i])."\n");
			fclose($f1);

			//show results
			$m = $r[0] ? $r[0] : 0;
			for ($i=0; $i<count($r); $i++) if ($m < $r[$i]) $m = $r[$i];
			$m = max($m, 1);

			$out .= "<p><table cellspacing=1 cellpadding=1 width=100% border=0>";
			$out .= "<tr><td colspan=3><b><font class=light>$f[0]</font></b></td></tr>\n";
			for ($i=1; $i<count($f); $i++)
			{
				$out .= "<tr><td><font class=middle>$f[$i]</font></td>";
				$out .= "<td width=100%>";

				if ($r[$i-1] > 0)
				{
					$out .= "<table cellspacing=0 cellpadding=0><tr><td><nobr>";
					for ($j = 0; $j< 10*$r[$i-1]/$m; $j++) $out .= "<img src=\"images/voter.gif\">";
					$out .= "</nobr></td></tr></table>";
				}

				$out .= "</td><td width=1% align=center><font class=middle>".$r[$i-1]."</font>\n";
				$out .= "</td></tr>\n";

			}

			$out .= "</table>";

			//don't allow user to vote more than 1 time
			$vote_completed[0] = 1;
			session_register("vote_completed");

		}

	$out .= "</Td></Tr></Table>";

	$output = str_replace("{VOTING}", $out, $output);





	// {MAIN_CONTENT}

	$out = "";
/*
	if (isset($killuser)) //tell about user's account termination

		$out .= "<center><b>".STRING_SUCCESSFUL_ACCOUNT_TERMINATION."</b></center><br><br>\n";

	else*/
	if (isset($forgotpw)) //forgot password?
	{
		
		$q = db_query("SELECT cust_password, Email FROM ".CUSTOMERS_TABLE." WHERE Login='$forgotpw'") or die (db_error());

		if ($row = db_fetch_row($q)) //send password
		{
			$tt = db_query("SELECT Email FROM ".CUSTOMERS_TABLE." WHERE Login='".ADMIN_LOGIN."'");
			$ro = db_fetch_row($tt);
			mail($row[1], EMAIL_FORGOT_PASSWORD_SUBJECT, EMAIL_HELLO."\n\n".EMAIL_YOUR_PASSWORD.": $row[0]\n\n".EMAIL_SINCERELY.", $shopname.\n$shopurl", "From: \"$shopname\"<$ro[0]>;\n".EMAIL_MESSAGE_PARAMETERS."\nReturn-path: <$ro[0]>");
			$out .= "<center><b>".STRING_PASSWORD_SENT." &lt;".$row[1]."></b></center><br><br>\n";

		}
		else //login wasn't found in db
		{
			$out .= "<center><br><br><br><b>".STRING_CANT_FIND_USER_IN_DB." (".stripslashes($forgotpw).")!</b></center><br>";
			$logging = "yes"; //show login form again
		}

	}
	else
	if (isset($show_price)) //show pricelist
	{
		$out .= "<center><h1>".STRING_PRICELIST." $shopname</h1>";
		$out .= "<table border=0 cellspacing=1 bgcolor=#444444 cellpadding=3 width=95%>";
		pricessCategories(&$cats,0,0,&$out);
		$out .= "</table></center><br>";
	}
	else if (isset($aux_page)) // auxiliary page
	{
		if (file_exists("cfg/$aux_page")) $f = file("cfg/$aux_page");
		else
		{
			$f = array(); $f[0] = ERROR_CANT_FIND_REQUIRED_PAGE;
		}
		for ($i=0; $i<count($f); $i++) $out .= stripslashes($f[$i]);
	}
	else if (isset($currency) && count($currency_name)>1) //change currency type form
	{

		$out .= STRING_SELECT_CURRENCY_TYPE."<br>";
		$out .= "<form action=\"index.php\" method=post>";
		for ($i = 0; $i<count($currency_name); $i++)
		{
			$out .= "<input type=radio name=change_currency value=\"$i\"";
			if ($i == $current_currency) $out .= " checked";
			$out .= "> $currency_name[$i]<br>";
		}
		$out .= "<br><input type=submit class=redbutton name=change_c value=\"".OK_BUTTON."\">";
		$out .= "</form>";
	}
	else if (isset($adv_search)) //advanced search form
	{
		include("includes/adv_search.php");
	}
	else if (isset($searchstring)) //simple search results
	{
		include("includes/simple_search.php");
	}
	else if ($categoryID && !isset($productID)) //show products in the category
	{
		include("includes/category_view.php");
	}
	else if (isset($productID) && $productID>0) //show product detailed information
	{
		include("includes/show_good.php");
	}
	else if (isset($user_details) && isset($log)) //show user's account
	{
		include("includes/user_details.php");
	}
	else if (isset($logging) || isset($wrongLoginOrPw)) //wrong password page
	{
		$out .= "

<center>
		";

		if (isset($wrongLoginOrPw))
			$out .= "
<br><br><br><font color=red><b>".ERROR_WRONG_PASSWORD."</b></font>
			";

		$out .= "

<form action=\"index.php\" method=post>
<table><tr><td>
".STRING_FORGOT_PASSWORD_FIX."</td><td><input class=ss type=\"text\" name=\"forgotpw\">
<input type=\"submit\" class=bluebutton value=\"".OK_BUTTON."\">
</td></tr></table>
</form>

</center>

		";

	}
	else if (isset($register) || isset($update_details)) //customers registration
	{
		include("includes/register.php");
	}
	else if (isset($check_order) && isset($log)) //order check
	{
		include("includes/check_order.php");
	}
	else if (isset($proceed_ordering) && isset($log)) //ordering process
	{
		include("includes/proceed_ordering.php");
	}
	else if (isset($complete_order) && isset($log)) //place order
	{
		include("includes/complete_order.php");
	}
	else if (isset($r_successful)) //successful registration notification
	{
		$out .= "<br><br><br>";
		if (isset($update)) $out .= "<center><b>".STRING_ACOOUNT_UPDATE_SUCCESSFUL."</b></center>";
		else $out .= "<center><b>".STRING_REGISTRATION_SUCCESSFUL."</b></center>";
	}
	else if (isset($kill_successful)) //sucessful account termination
	{
		$out .= "<br><br><br>";
		$out .= "<center><b>".STRING_SUCCESSFUL_ACCOUNT_TERMINATION."</b></center>";
	}
	else //homepage
	{

			//greetings text

			$out .= STRING_GREETINGS;

			$out .= "<p>\n";

			//place categories into 2 columns

			$q = db_query("SELECT categoryID FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and parent=0 ORDER BY name") or die (db_error());
			$rr = array();
			while ($row = db_fetch_row($q)) $rr[] = $row;
			$k = count($rr);

			if ($k%2 == 0) $tmp = 0;
			else $tmp = 1;

			$out .= "<table width=100% border=0>\n";
			for ($i=0; $i<$k; $i++) {
				$tmp = $i%2;
				if (!$tmp) $out .= "<tr>";
				$out .= "<td width=50% valign=top>";
				showSubCategories(&$cats, categoryIndexInArray(&$cats, $rr[$i][0]), &$out);
				$out .= "</td>";
				if ($tmp) $out .= "</tr>";
			  };

			$out .= "\n</table>\n";


			//show special offers

			$c = 2; //show 2 products in each row
			$q = db_query("SELECT productID, name, picture, Price FROM ".PRODUCTS_TABLE." WHERE show_as_special_offer=1 AND picture<>'' AND enabled=1 AND Price>0 AND categoryID>0 AND in_stock>0 ORDER BY show_as_special_offer DESC, customers_rating DESC, name") or die (db_error());
			$i = 0;
			$out .= "<center><table border=0 cellspacing=5 cellpadding=2>\n<tr>";
			while (($row = db_fetch_row($q)))

			  if (file_exists("products_pictures/$row[2]"))
			  {

				if ($i % $c == 0 && $i != 0) $out .= "</td></tr><tr><td valign=top>";
				else $out .= "<td valign=top>";

				$out .= "<table border=0 width=100% bgcolor=#$middle_color cellpadding=0 cellspacing=1><tr><td>";

				$out .= "<table border=0 bgcolor=white width=100%><tr><td colspan=2>";
				$out .= "<a href=\"index.php?productID=$row[0]\"><img src=\"products_pictures/$row[2]\" border=0></a></td></tr>";
				$out .= "<tr><td align=center><a href=\"index.php?productID=$row[0]\">$row[1]</a></td>";
				$out .= "<td align=right><font color=red><b>".show_price($row[3])."</b></font></td></tr>";
				$out .= "</table>";

				$out .= "</td></tr></table>\n</td>";

				$i++;

			  }

			$out .= "</tr></table></center>";


	}



	$output = str_replace("{MAIN_CONTENT}", $out, $output);


	//show all output

	if (isset($log) && !strcmp($log, ADMIN_LOGIN)) echo "<br><center><a href=\"admin.php\"><font color=red>".ADMINISTRATE_LINK."</font></a></center><br>";

	echo $output;

?>