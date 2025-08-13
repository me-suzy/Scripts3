<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/

	
	//advanced search



function fillTheCList($parent,$level,$c,&$out)
{
	//fills combobox with categories

	

	$q = db_query("SELECT categoryID, name FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and parent=$parent ORDER BY name") or die (db_error());

	$a = array(); //parents
	while ($row = db_fetch_row($q))
	{

		$out .= "<option value=\"".$row[0]."\"";
		if ($c==$row[0]) $out .= " selected>";
		else $out .= ">";
		for ($j=0; $j<$level; $j++) $out .= "&nbsp;&nbsp;";
		$out .= $row[1]."</option>\n";
		//save
		$a[count($a)] = $row;
		//process subcategories
		$b = fillTheCList($row[0],$level+1,$c,&$out);
		//add $b[] to the end of $a[]
		for ($j=0; $j<count($b); $j++)
		{
			$a[] = $b[$j];
		}
	}

	return $a;

} //fillTheCList


function fillTheCList2($parent) //4 search in subcategories
{
	//analog of fillTheCList, except it doesn't fill combobox

	

	$q = db_query("SELECT categoryID FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and parent=$parent") or die (db_error());

	$a = array(); //chilren
	while ($row = db_fetch_row($q))
	{
		//save
		$a[count($a)] = $row[0];

		$b = fillTheCList2($row[0]);

		for ($j=0; $j<count($b); $j++)
		{
			$a[count($a)] = $b[$j];
		}
	}
	return $a;

} //fillTheCList2





		$out = "";

		$k = 0;
		if (!isset($cat)) $cat = 0;

		if (isset($done) && ($name!="" || $cat!=0 || $price1!=0 || $price2!=0)) //get search results
		{

			$s = "SELECT productID FROM ".PRODUCTS_TABLE." WHERE categoryID<>0 AND Price>0 AND ";
			$a = 0;
			if ($price1) //lower bound
			{
				$p1 = $price1 / $currency_value[$current_currency];
				$s .= "Price>=$p1 ";
				$a = 1;
			}
			if ($price2) //higher bound
			{
				$p2 = $price2 / $currency_value[$current_currency];
				if ($a) $s .= "AND ";
				$s .= "Price<=$p2 ";
				$a = 1;
			}
			if (trim($name))
			{
				if ($a) $s .= "AND (";
				else $s .= "(";
				$n = explode(" ",$name);
				$s .= "name LIKE '%$n[0]%'";
				for ($i=1; $i<count($n); $i++) $s .= " AND name LIKE '%$n[$i]%'";
				$s .= ") ";
				$a = 1;
			}

			//category
			if ($cat) { // != 0

				$f = fillTheCList2($cat);
				$f[count($f)] = $cat;

				if ($a) $s .= "AND (";
				else $s .= "(";

				$s .= "categoryID=$f[0]";

				for ($i=1; $i<count($f); $i++) {
					$s .= " OR categoryID=$f[$i]";
				};
				$s .= ")";

			};

			//now do query()
			$q = db_query(str_replace("SELECT productID","SELECT count(*)",$s)) or die (db_error());
			$adv_count = db_fetch_row($q); $adv_count = $adv_count[0];

			$i = 0;
			$q = db_query($s) or die (db_error());
			while ($row = db_fetch_row($q))
			{
				if ($i>=$offset && $i<$offset + $products_count)
					$radv[$k++] = $row[0];
				$i++;
			}

			if ($k) //found something
			{

				if ($offset>$adv_count) $offset=0;

				$min = $products_count;
				if ($min > $adv_count-$offset) $min = $adv_count-$offset;

				$out .= "<center><p>".STRING_FOUND." <b>$adv_count</b> ".STRING_PRODUCTS."\n<p>";

				//higher navigation
				showNavigator($adv_count, $offset, $products_count, "index.php?name=$name&cat=$cat&price1=$price1&price2=$price2&adv_search=1&done=&",&$out);

				for ($j=0; $j<$min; $j++)
					showGood($radv[$j],true,&$out);

				//lower nav
				showNavigator($adv_count, $offset, $products_count, "index.php?name=$name&cat=$cat&price1=$price1&price2=$price2&adv_search=1&done=&",&$out);
				$out .= "</center>";
			}

		}

		$out .= "

<center>
<form name=Sform method=get action=\"\" onSubmit=\"return validate_search(this);\">

		";

		if (!isset($done))
			$out .= "<b>".STRING_ADVANCED_SEACH_TITLE."</b><br><br>";
		  else if (!$k)
			$out .= "<p><b><font color=purple>".STRING_NO_MATCHES_FOUND."</font></b></p><br>";

		$s1 = isset($name) ? " value=\"$name\"" : "";
		$s2 = "";
		fillTheCList(0,0,$cat,&$s2);
		$s3 = isset($price1) ? " value=\"$price1\"" : "";
		$s4 = isset($price2) ? " value=\"$price2\"" : "";

		$out .= "

<table>

<tr>
<td width=40% align=right>".ADMIN_PRODUCT_NAME." </td>
<td>
<input class=ss type=\"text\" name=\"name\"$s1>
</td>
</tr>

<tr>
<td align=right>".ADMIN_CATEGORY_NAME.":</td>
<td>
<select name=\"cat\" class=ss>
<option value=\"0\">".STRING_ADV_SEARCH_ANY."</option>

		";

		fillTheCList(0,0,$cat,&$out);

		$out .= "

</select>
</td>
</tr>

<tr>
<td width=40% align=right>".ADMIN_PRODUCT_PRICE.": </td>
<td>
".STRING_PRICE_FROM." <input class=ss type=\"text\" name=\"price1\"$s3 size=4>
".STRING_PRICE_TO." <input class=ss type=\"text\" name=\"price2\"$s4 size=5> $currency_name[$current_currency]
</td>
</tr>

</table><br>

<input type=submit value=\"".FIND_BUTTON."\" name=\"adv_search\">
<input type=hidden name=\"done\">

</form>

</center>

		";
?>