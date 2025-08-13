<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



		//show simple search results (search is made in index.php)

		$out .= "<center><p><b>".$searchstring."</b>: ";

		if (count($g_search_count))
		  if (count($cats_search))
			$out .= STRING_FOUND." <b>".$g_search_count."</b> ".STRING_PRODUCTS.", <b>".count($cats_search)."</b> ".STRING_CATEGORIES."</p><br></center>\n\n";
		  else
			$out .= STRING_FOUND." <b>".$g_search_count."</b> ".STRING_PRODUCTS."</p><br></center>\n\n";
		else
		{
		  if (count($cats_search))
			$out .= STRING_FOUND." <b>".count($cats_search)."</b> ".STRING_CATEGORIES."</p><br>\n\n";
		  else
			$out .= STRING_NO_MATCHES_FOUND."</p><br>\n\n";

		  $out .= STRING_SEARCH_TIP."</center><br><br>";
		}

		if (count($cats_search)) //show categories
		{

			$out .= "<p>\n";
			for ($i=0; $i<count($cats_search); $i++)
			{

				//calculate path to category
				$path = calculatePath(&$cats, $cats_search[$i][0]);

				//show the path
				for ($j=1; $j<count($path)-1; $j++) 
					$out .= "<a class=\"standard\" href=\"index.php?categoryID=$path[$j]\">".$cats[categoryIndexInArray(&$cats, $path[$j])][1]."</a> : ";

				//matching category will be <bold>
				$out .= "<a href=\"index.php?categoryID=".$cats_search[$i][0]."\">".$cats_search[$i][1]."</a>";
				$out .= "</b></font>";

				$out .= "<br>\n\n";
			}
			$out .= "</p>";

		}


		if ($g_search_count) //show products
		{

			$out .= "<center>\n";

			$min = $products_count;
			if ($min > $g_search_count-$offset) $min = $g_search_count-$offset;

			//upper navigator
			$tmp = isset($inside) ? "&inside=yes" : "";
			showNavigator($g_search_count, $offset, $products_count, "index.php?searchstring=".$searchstring."&oldproducts=".base64_encode($s_search)."$tmp&",&$out);


			for ($j=0; $j<$min; $j++)
				showGood($products_search[$j][0],1,&$out);


			//lower navigator
			showNavigator($g_search_count, $offset, $products_count, "index.php?searchstring=".$searchstring."&oldproducts=".base64_encode($s_search)."$tmp&",&$out);

			$out .= "</center>\n";

		}


?>