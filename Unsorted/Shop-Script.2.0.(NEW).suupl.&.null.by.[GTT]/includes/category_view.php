<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/

	//show all products of selected category

function get_Subs($cid)
{
	$q = db_query("select categoryID from ".CATEGORIES_TABLE." where categoryID<>0 and parent=$cid;") or die (db_error());
	$r = array();
	while ($row = db_fetch_row($q))
	{
		$a = get_Subs($row[0]);
		for ($i=0;$i<count($a);$i++) $r[] = $a[$i];
		$r[] = $row[0];
	}

	return $r;
}

	//at first show current category title
		$out .= "<p>\n";

		$s = "<a href=\"index.php\" class=\"cat\">".LINK_TO_HOMEPAGE."</a> : ";
		for ($i=1; $i<count($path)-1; $i++)
			$s .= "<a class=\"cat\" href=\"index.php?categoryID=$path[$i]\">".$cats[categoryIndexInArray(&$cats, $path[$i])][1]."</a> : ";
		$t = categoryIndexInArray(&$cats, $categoryID);
		$s .= $cats[$t][1];

	//thumbnail
		$out .= "<table><tr><td>";
		if ($cats[$t][5] && file_exists("products_pictures/".$cats[$t][5]))
			$out .= "<img src=\"products_pictures/".$cats[$t][5]."\"></td><td>";
		$out .= "<font class=\"cat\"><b>\n$s:<br></b></font>";
		$out .= "</td></tr></table>";

	//description
		$out .= "<p>".$cats[$t][4]."</p>\n";

	//show subcategories
		$out .= "</p><p>";
		for ($j=0; $j<count($cats); $j++)
		  if ($cats[$j][2] == $cats[categoryIndexInArray(&$cats, $categoryID)][0])
		  {
			$out .= "&nbsp;&nbsp;<a class=standard href=\"index.php?categoryID=".$cats[$j][0]."\">".$cats[$j][1]."</a>\n";
			$out .= "(".$cats[$j][3].")<br>\n"; //products count
		  }
		$out .= "</p>\n";


	//show active products
		$q = db_query("SELECT count(*) FROM ".PRODUCTS_TABLE." WHERE categoryID=$categoryID AND enabled=1 AND Price>0") or die (db_error());
		$g_count = db_fetch_row($q);
		$g_count = $g_count[0];

		if ($g_count) // there are products in the category
		{

			if ($offset>$g_count) $offset=0;

			$q = db_query("SELECT productID FROM ".PRODUCTS_TABLE." WHERE categoryID=$categoryID AND enabled=1 AND Price>0 ORDER BY name") or die (db_error());

			$result = array();
			$i=0;
			while ($row = db_fetch_row($q))
			{
				if ($i>=$offset && $i<$offset+$products_count)
					$result[] = $row[0];
				$i++;
			}

			$min = $products_count;
			if ($min > $g_count-$offset) $min = $g_count-$offset;

			$out .= "<center>\n";

			//top navigator
			showNavigator($g_count, $offset, $products_count, "index.php?categoryID=$categoryID&",&$out);

			//draw $cols_count products in each row
			$out .= "<p><table border=0 cellpadding=5 width=100%>";

			for ($i=0; $i<$min; $i++)
			{
				if ($i % $cols_count == 0) $out .= "<tr>";

				$out .= "<td width=50% valign=top>";
				showGood($result[$i], true, &$out); //show item
				$out .= "</td>";

				if (($i+1) % $cols_count == 0) $out .= "</tr>";
			}

			$out .= "</table>";

			//lower navigator
			showNavigator($g_count, $offset, $products_count, "index.php?categoryID=$categoryID&",&$out);

			$out .= "</center>\n";


		}
		else //there are no items in the category. search for items in it's subcategories
		{

				//are there any subcateogires?
				$i = 0;
				while ($i<count($cats) && $cats[$i][2] != $categoryID) $i++;
				if ($i == count($cats)) //empty
					$out .= "&nbsp;&nbsp;&nbsp;&nbsp;&lt; ".STRING_EMPTY_CATEGORY." >";
				else //search for items
				{
					//create query

					$s = "SELECT productID FROM ".PRODUCTS_TABLE." WHERE enabled=1 AND Price>0 ";
					$a = get_Subs($categoryID);
					if (count($a) > 0)
					{
						$s.= " AND (categoryID=$a[0]";
						for ($i=1;$i<count($a);$i++)
						{
							$s.=" OR categoryID=$a[$i]";
						}
						$s.= ")";
					}

					$q = db_query(str_replace("SELECT productID","SELECT count(*)",$s)) or die (db_error());
					$cnt = db_fetch_row($q); $cnt = $cnt[0];


					$q = db_query($s." ORDER BY customers_rating DESC") or die (db_error());
					$i=0;

					if ($cnt > 0) $out .= "<br>".PRODUCTS_BEST_CHOISE.":";

					//$cols_count products in each row
					$out .= "<p><table border=0 cellpadding=5 width=100%>";

					while ($i<$products_count && $row = db_fetch_row($q)) //show not more than $products_count products
					{

						if ($i % $cols_count == 0) $out .= "<tr>";

						$out .= "<td valign=top width=50%>";
						showGood($row[0],true,&$out);
						$out .= "</td>";

						if (($i+1) % $cols_count == 0) $out .= "</tr>";

						$i++;
					}

					$out .= "</table>";

				}

		}


?>