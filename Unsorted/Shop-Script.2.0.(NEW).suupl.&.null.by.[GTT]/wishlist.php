<?

/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//ADMIN :: related items managment

	include("cfg/connect.inc.php");
	include("includes/database/".DBMS.".php");
	
	include("functions.php");

	//connect 2 database
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());

	session_start();

	include("checklogin.php");
	if (!isset($log) || strcmp($log,ADMIN_LOGIN)) //unauthorized access
	{
		die(ERROR_FORBIDDEN);
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


function showproducts($cid, $owner) //show items from selected category
{

	

	$q = db_query("SELECT productID, name FROM ".PRODUCTS_TABLE." WHERE categoryID=$cid") or die (db_error());
	echo "<table bgcolor=#AACC88 cellpadding=2>";
	while ($row = db_fetch_row($q))
	{
		echo "<tr bgcolor=#DDEEBB><td>&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<a href=\"wishlist.php?owner=$owner&categoryID=$cid&select=$row[0]\"><u>$row[1]</u></a>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
	}
	echo "</table><br>";
}


function categoryIndexInArray($list, $id)
{

	$j = 0;
	while ($j<count($list) && $list[$j][0]!=$id) $j++;
	if ($j == count($list)) return 0;
	else return $j;

}

function processCategories(&$list, $level, $path, $sel, $owner) //analog of same-name function from admin.php
{
	for ($i=0; $i<count($list); $i++)
	{

		if ($list[$i][2] == $path[$level])
		{
			echo "<tr><td>";
			for ($j=0; $j<$level; $j++) echo "&nbsp;&nbsp;";

			if ($list[$i][0] == $sel) //no link on selected category
			{
				echo "- ".$list[$i][1]." (".$list[$i][3].")\n";

				showproducts($sel, $owner);

				echo "</td></tr>\n";
			}
			else //make a link
			{
				echo "<a href=\"wishlist.php?owner=$owner&categoryID=".$list[$i][0]."\"";
				if ($level) echo " class=standard";
				echo ">+ ".$list[$i][1]."</a> (".$list[$i][3].")</td></tr>\n";
			}
		}

		//process subcategories
		if ($level+1<count($path) && $list[$i][0] == $path[$level+1])
			processCategories($list,$level+1,$path,$sel, $owner);

	}
}


function calculatePath(&$categories, $categoryID)
{

	//calculates path from selected category ($categoryID) to the root category

	$path = array();
	$i = $categoryID;
	if ($i) do
	{
		$c = categoryIndexInArray($categories,$i);
		$path[count($path)] = $categories[$c][0];
		$i = $categories[$c][2];
	} while ($i);

	$path[count($path)]=0; //the last element is a root category
	$path = array_reverse($path); //reverse...

	return $path;

}

	if (!isset($owner)) exit;

	//load all categories to array to avoid multiple queries
	$categories = array();
	$i=0;
	$q = db_query("SELECT categoryID, name, parent, products_count FROM ".CATEGORIES_TABLE." where categoryID<>0 ORDER BY name") or die (db_error());	
	while ($row = db_fetch_row($q)) $categories[$i++] = $row;

	if (!isset($categoryID)) $categoryID = 0;

	if (isset($select)) //add 2 wish-list (related items list)
	{
		$q = db_query("SELECT count(*) FROM ".RELATED_PRODUCTS_TABLE." WHERE productID=$select AND Owner=$owner") or die (db_error());
		$cnt = db_fetch_row($q);
		if ($cnt[0] == 0) // insert
			db_query("INSERT INTO ".RELATED_PRODUCTS_TABLE." (productID, Owner) VALUES ($select, $owner)") or die (db_error());
	}

	if (isset($kill)) //remove from wish-list
	{
		db_query("DELETE FROM ".RELATED_PRODUCTS_TABLE." WHERE productID=$kill AND Owner=$owner") or die (db_error());
	}

?>

<html>

<head>
<link rel=STYLESHEET href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?=DEFAULT_CHARSET;?>">
<title><?=STRING_RELATED_ITEMS;?></title>
</head>

<body bgcolor=#DDEEBB>

<TABLE BORDER=0 WIDTH=100% BGCOLOR=#77AA88 CELLSPACING=1>
<TR bgcolor=#DDEEBB>

<TD>

<table>
<tr><td colspan=2><u><?=ADMIN_RELATED_PRODUCTS_SELECT;?>:</u><br><br></td></tr>
<tr><td>&nbsp;</td>
<td>
<table>
<?
	$path = calculatePath(&$categories, $categoryID); //calc path to selected category
	processCategories(&$categories,0,$path,$categoryID,$owner);
?>
</table>
</td>
</tr>
</table>

</TD>

<TD VALIGN=TOP WIDTH=50%>
<table width=100%>
<tr><td colspan=2><u><?=ADMIN_SELECTED_PRODUCTS;?></u><br><br></td></tr>
<tr><td>&nbsp;</td>
<td>
<?

	$q = db_query("SELECT productID FROM ".RELATED_PRODUCTS_TABLE." WHERE Owner=$owner") or die (db_error());
		echo "<table width=90% border=0 bgcolor=#77AA99 cellspacing=1 cellpadding=3>";
		while ($row = db_fetch_row($q))
		{
			$p = db_query("SELECT name FROM ".PRODUCTS_TABLE." WHERE productID=$row[0]") or die (db_error());
			if ($r = db_fetch_row($p))
			{
			  echo "<tr bgcolor=#DDEEBB>";
			  echo "<td width=100%>$r[0]</td>";
			  echo "<td width=1%><a href=\"wishlist.php?owner=$owner&categoryID=$categoryID&kill=$row[0]\"><img src=\"images/remove.jpg\" border=0 alt=\"".DELETE_BUTTON."\"></a></td>";
			  echo "</tr>";
			}
		}
		echo "</table>";

?>
</td>
</tr>
</table>


</TD>
</TR>
</TABLE>

<center>
<form>
<input type=button value="<?=CLOSE_BUTTON;?>" onClick="javascript:window.close();">
</form>
</center>

</body>

</html>