<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//ADMIN :: categories managment

	include("cfg/connect.inc.php");
	include("includes/database/".DBMS.".php");
	include("functions.php");
	

	//connect to database
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());

	//checking for authorized access
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

?>
<html>

<head>
<link rel=STYLESHEET href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?=DEFAULT_CHARSET;?>">
<title><?=ADMIN_CATEGORY_TITLE;?></title>
<script>
function confirmDelete(text,url)
{
	temp = window.confirm(text);
	if (temp) { //delete
		window.location=url;
	}
}
</script>
</head>

<body bgcolor=#D2D2FF>

<?
	function deleteSubCategories($parent) //deletes all subcategories of category with categoryID=$parent
	{

		
		

		//subcategories
		$q = db_query("SELECT categoryID FROM ".CATEGORIES_TABLE." WHERE parent=$parent and categoryID<>0") or die (db_error());
		while ($row = db_fetch_row($q))
		{
			deleteSubCategories($row[0]); //recurrent call
		}
		$q = db_query("DELETE FROM ".CATEGORIES_TABLE." WHERE parent=$parent and categoryID<>0") or die (db_error());

		//move all product of this category to the root category
		$q = db_query("UPDATE ".PRODUCTS_TABLE." SET categoryID=0 WHERE categoryID=$parent") or die (db_error());
	}

	function category_Moves_To_Its_SubDirectories($cid, $new_parent)
	{
		//return true/false

		

		$a = false;
		$q = db_query("SELECT categoryID FROM ".CATEGORIES_TABLE." WHERE parent=$cid and categoryID<>0") or die (db_error());
		while ($row = db_fetch_row($q))
		{
			if ($row[0] == $new_parent) $a = true;
			else
			  $a = category_Moves_To_Its_SubDirectories($row[0],$new_parent);
		}
		return $a;
	}

	function fillTheList($parent,$level,$add2list,$c)
	{

		//return array $a of parental IDs of categories in the path to the root (from $parent)
		//requires to "go through" all categories
		//if ($add2list!=0) then fill a combobox with categories
		//almost same as processCategories() at admin.php

		

		$q = db_query("SELECT categoryID, name FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and parent=$parent ORDER BY name") or die (db_error());

		$a = array(); //parents
		while ($row = db_fetch_row($q))
		{
			//fill the combobox?
			if ($add2list)
			{
				echo "<option value=\"$row[0]\"";
				if ($c == $row[0]) echo " selected";
				echo ">";
				for ($j=0; $j<$level; $j++) echo "&nbsp;&nbsp;"; //outline child-categories
				echo "$row[1]</option>\n";
			}
			//save
			$a[count($a)] = $row;
			//process all subcategories
			$b = fillTheList($row[0],$level+1,$add2list,$c);
			//add $b[] to the end of $a[]
			for ($j=0; $j<count($b); $j++)
			{
				$a[] = $b[$j];
			}
		}
		return $a;
	}



	if (!isset($w)) $w=-1; //parent

	if (isset($picture_remove)) //delete category thumbnail from server
	{
		$q = db_query("SELECT picture FROM ".CATEGORIES_TABLE." WHERE categoryID=$c_id and categoryID<>0") or die (db_error());
		$r = db_fetch_row($q);
		if ($r[0] && file_exists("products_pictures/$r[0]")) unlink("products_pictures/$r[0]");
		db_query("UPDATE ".CATEGORIES_TABLE." SET picture='' WHERE categoryID=$c_id") or die (db_error());
	}

	if (isset($save) && $name) { //save changes

		if (!isset($must_delete)) //add new category
		{
			db_set_identity(CATEGORIES_TABLE);
			$q = db_query("INSERT INTO ".CATEGORIES_TABLE." (name, parent, products_count, description, picture, products_count_admin) VALUES ('".str_replace("<","&lt;",$name)."',".$parent.",0,'$desc','',0)") or die (db_error());
			$pid = db_insert_id("CATEGORIES_GEN");
		}
		else //update existing category
		{
			if ($must_delete != $parent) //if not moving category to itself
			{

				//if category is being moved to any of it's subcategories - it's
				//neccessary to 'lift up' all it's subcategories

				if (category_Moves_To_Its_SubDirectories($must_delete, $parent))
				{
					//lift up is required

					//get parent
					$q = db_query("SELECT parent FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and categoryID=$must_delete") or die (db_error());
					$r = db_fetch_row($q);

					//lift up
					db_query("UPDATE ".CATEGORIES_TABLE." SET parent='$r[0]' WHERE parent=$must_delete") or die (db_error());

					//move edited category
					db_query("UPDATE ".CATEGORIES_TABLE." SET name='".str_replace("<","&lt;",$name)."', description='$desc', parent=$parent WHERE categoryID=$must_delete") or die (db_error());

				}
				else //just move category
					db_query("UPDATE ".CATEGORIES_TABLE." SET name='".str_replace("<","&lt;",$name)."', description='$desc', parent=$parent WHERE categoryID=$must_delete") or die (db_error());
			}
			$pid = $must_delete;
		}

		if ($picture && $picture != "none") //upload category thumbnail
		{

			//old picture
			$q = db_query("SELECT picture FROM ".CATEGORIES_TABLE." WHERE categoryID=$pid and categoryID<>0") or die (db_error());
			$row = db_fetch_row($q);

			//upload new photo
			$picture_name = str_replace(" ","_", $picture_name);
			$r = copy(trim($picture), "products_pictures/$picture_name");
			if (!$r) //failed to upload
			{
				echo "<center><font color=red>".ERROR_FAILED_TO_UPLOAD_PHOTO."</font>\n<br><br>\n";
				echo "<a href=\"javascript:window.close();\">".CLOSE_BUTTON."</a></center></body>\n</html>";
				exit;
			}

			db_query("UPDATE ".CATEGORIES_TABLE." SET picture='$picture_name' WHERE categoryID=$pid") or die (db_error());

			//remove old photo...
			if ($row[0] && file_exists("products_pictures/$row[0]")) unlink("products_pictures/$row[0]");

		}

		//now close the window (in case of success)
		echo "<script>\n";
		echo "window.opener.location.reload();\n";
		echo "window.close();\n";
		echo "</script>\n</body>\n</html>";
	}
	else { //category edition from

		if (isset($c_id)) //edit existing category
		{
			$q = db_query("SELECT name, description, picture FROM ".CATEGORIES_TABLE." WHERE categoryID=$c_id and categoryID<>0") or die (db_error());
			$row = db_fetch_row($q);
			if (!$row) //can't find category....
			{
				echo "<center><font color=red>".ERROR_CANT_FIND_REQUIRED_PAGE."</font>\n<br><br>\n";
				echo "<a href=\"javascript:window.close();\">".CLOSE_BUTTON."</a></center></body>\n</html>";
				exit;
			}
			$title = "<b>$row[0]</b>";
			$n = $row[0];
			$d = $row[1];
			$picture = $row[2];

			if (isset($del)) //delete category
			{

				//photo
				$q = db_query("SELECT picture FROM ".CATEGORIES_TABLE." WHERE categoryID=$c_id and categoryID<>0") or die (db_error());
				$r = db_fetch_row($q);
				if ($r[0] && file_exists("products_pictures/$r[0]")) unlink("products_pictures/$r[0]");

				//delete from db
				$q = db_query("DELETE FROM ".CATEGORIES_TABLE." WHERE categoryID=$c_id and categoryID<>0") or die (db_error());

				deleteSubCategories($c_id);

				//close window
				echo "<script>\n";
				echo "window.opener.location.reload();\n";
				echo "window.close();";
				echo "</script>\n</body>\n</html>";
			}
		}
		else //create new			
		{
			$title = ADMIN_CATEGORY_NEW;
			$n = "";
			$d = "";
			$picture = "";
		}

?>

<center><font color=purple><?=$title; ?></font></center>
<form enctype="multipart/form-data" action="category.php" method=post>
<table width=100% border=0>

<tr>
<td align=right>
<?
	if (!isset($c_id)) echo ADMIN_CATEGORY_PARENT;
	else echo ADMIN_CATEGORY_MOVE_TO;
?>
</td>
<td width=5%>&nbsp;</td>
<td>
<select name="parent">
<option value="0"><?=ADMIN_CATEGORY_ROOT;?></option>
<?
	//fill the category combobox

	fillTheList(0,0,1,$w);
?>
</select>
</td>
</tr>

<tr>
<td align=right><?=ADMIN_CATEGORY_NAME;?></td>
<td>&nbsp;</td>
<td><input type="text" name="name" value="<?=str_replace("\"","&quot;",$n); ?>" size=13></td>
</tr>

<tr>
<td align=right><?=ADMIN_CATEGORY_LOGO;?></td>
<td>&nbsp;</td>
<td><input type="file" name="picture"></td>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>
<?
	if ($picture && $picture != "none" && file_exists("products_pictures/".$picture))
	{
		echo "<font class=average></font> <a class=small href=\"products_pictures/".$picture."\">$picture</a>\n";
		echo "<br><a href=\"javascript:confirmDelete('".QUESTION_DELETE_PICTURE."','category.php?c_id=$c_id&w=$w&picture_remove=yes');\">".DELETE_BUTTON."</a>\n";
	}
	else echo "<font class=average>".ADMIN_PICTURE_NOT_UPLOADED."</font>";
?>
</td>
</tr>

<tr>
<td align=right><?=ADMIN_CATEGORY_DESC;?><br>(HTML)</td>
<td></td>
<td><textarea name="desc" rows=7 cols=22><?=str_replace("\"","&quot;",$d); ?></textarea></td>
</tr>

</table>
<p><center>
<input type="submit" value="<?=SAVE_BUTTON;?>" width=5>
<input type="hidden" name="save" value="yes">
<input type="button" value="<?=CANCEL_BUTTON;?>" onClick="window.close();">
<?
	//$must_delete indicated which query should be made: insert/update
	if (isset($c_id))
	{
		echo "<input type=\"hidden\" name=\"must_delete\" value=\"".$c_id."\">\n";
		echo "<input type=\"button\" value=\"".DELETE_BUTTON."\" onClick=\"confirmDelete('".QUESTION_DELETE_CONFIRMATION."','category.php?c_id=$c_id&del=1');\"";
	}
?>
</center></p>
</form>

</body>

</html>
<? }; ?>