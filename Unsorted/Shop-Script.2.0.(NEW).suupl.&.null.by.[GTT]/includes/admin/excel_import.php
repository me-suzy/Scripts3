<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/
 
 
 
	//ADMIN:: import from Excel

	echo "<a href=\"admin.php\"><u>".ADMIN_MAIN_MENU."</u></a> : <u><font>".ADMIN_IMPORT_FROM_EXCEL."</font></u> :<br><br>";




	if (isset($do_excel_import) && isset($filename) && isset($update_column)) //configuration finished - update database
	{

		echo "Please wait...<hr width=90%>";

		set_time_limit(4*60);

		//import file content
		$f = fopen($filename,"r");
		$data = array();
		while ($row = fgetcsv($f, filesize($filename), ";"))
		{
			$data[] = $row;
		}
		if (!count($data)) die (ERROR_CANT_READ_FILE);

		//now get titles associations set by the import configurator
		$vars = get_defined_vars();
		foreach ($vars as $key => $val)
		{
			if (strstr($key, "column_name_"))
			{
				$i = str_replace("column_name_", "", $key);
				$cname[$i] = $val;
			}
			if (strstr($key, "db_association_"))
			{
				$i = str_replace("db_association_", "", $key);
				$db_association[$i] = $val;
			}
		}
		//now reverse -- create backwards association table: db_column -> file_column
		$dbc = array(
			"name" => "not defined",
			"product_code" => "not defined",
			"Price" => "not defined",
			"in_stock" => "not defined",
			"list_price" => "not defined",
			"items_sold" => "not defined",
			"description" => "not defined",
			"brief_description" => "not defined",
			"picture" => "not defined",
			"thumbnail" => "not defined",
			"big_picture" => "not defined"
		);

		$extra_option = array();
		for ($i=0;$i<count($cname);$i++) $extra_option[$i] = 0;

		for ($i=0;$i<count($db_association);$i++)
		{
			if ($db_association[$i] == "name") $dbc["name"] = $i;
			else if ($db_association[$i] == "product_code") $dbc["product_code"] = $i;
			else if ($db_association[$i] == "Price") $dbc["Price"] = $i;
			else if ($db_association[$i] == "in_stock") $dbc["in_stock"] = $i;
			else if ($db_association[$i] == "list_price") $dbc["list_price"] = $i;
			else if ($db_association[$i] == "items_sold") $dbc["items_sold"] = $i;
			else if ($db_association[$i] == "description") $dbc["description"] = $i;
			else if ($db_association[$i] == "brief_description") $dbc["brief_description"] = $i;
			else if ($db_association[$i] == "picture") $dbc["picture"] = $i;
			else if ($db_association[$i] == "big_picture") $dbc["big_picture"] = $i;
			else if ($db_association[$i] == "thumbnail") $dbc["thumbnail"] = $i;
			else if ($db_association[$i] == "add") //extra option
			{


				//insert option directly to the database
				$q = db_query("select count(*) from ".PRODUCT_OPTIONS_TABLE." where name LIKE '$cname[$i]'") or die (db_error());
				$row = db_fetch_row($q);
				if (!$row[0]) //no option exists => insert new
				{
					db_set_identity(PRODUCT_OPTIONS_TABLE);
					db_query("insert into ".PRODUCT_OPTIONS_TABLE." (name) values ('$cname[$i]')") or die (db_error());
					$op_id = db_insert_id("PRODUCT_OPTIONS_GEN");
				}
				else //get current $id
				{
					$q = db_query("select optionID from ".PRODUCT_OPTIONS_TABLE." where name LIKE '$cname[$i]'") or die (db_error());
					$op_id = db_fetch_row($q);
					$op_id = $op_id[0];
				}

				//update extra options list
				$extra_option[$i] = $op_id;
			}
			
		}

		//start from titles line and so on till the end of the file
		$cid = 0;

		//get update column
		$uc = $dbc[ $update_column ];
		if (!strcmp($uc,"not defined")) //not set update column
		{
			echo "<p><b><font color=red>".ERROR_UPDATE_COLUMN_IS_NOT_SET."</font></b>";
			//go to the previous step
			$proceed = 1;
			$file_excel = "";
			$file_excel_name = str_replace("products_pictures/","",$filename);
			$res = 1;
		}
		else
		{
			$parents = array(); //2 create a category tree
			$parents[0] = 0;
			for ($i=$number_of_titles_line+1; $i<count($data); $i++)
			{
				$cnt = get_NOTempty_elements_count($data[$i]);
				if ($cnt > 0)
				{
					if (($cnt==1 || $cnt==2) && $dbc["name"] != "not defined") //category
					{
						$data[$i][ "not defined" ] = "";
						$cname = $data[$i][ $dbc["name"] ];
						for ($sublevel=0; $sublevel<strlen($cname) && $cname[$sublevel] == '!'; $sublevel++);
						$cname = substr($cname,$sublevel);

						$sl = $sublevel;
						if (!isset($parents[$sublevel])) //not many '!' -- searching for root category
						{
							for (; $sl>0 && !isset($parents[$sl]); $sl--);
						}
						$q = db_query("select count(*) from ".CATEGORIES_TABLE." where categoryID<>0 and name LIKE '$cname' and parent='$parents[$sl]'") or die (db_error());
						$row = db_fetch_row($q);
						if ($cname != "")
						{
							if (!$row[0]) //insert new category (fill the name column only)
							{
								db_set_identity(CATEGORIES_TABLE);
								db_query("insert into ".CATEGORIES_TABLE." (name, parent, products_count, description, picture, products_count_admin) values ('$cname',$parents[$sl],0,'".$data[$i][ $dbc["description"] ]."','',0);") or die (db_error());
								$cid = db_insert_id("CATEGORIES_GEN");
								$parents[$sublevel+1] = $cid;
							}
							else //get categoryID
							{
								$q = db_query("select categoryID from ".CATEGORIES_TABLE." where categoryID<>0 and name LIKE '$cname' and parent='$parents[$sl]'") or die (db_error());
								$row = db_fetch_row($q);
								$cid = $row[0];
								$parents[$sublevel+1] = $cid;

								if ($dbc["description"] != "not defined") //update category description
									db_query("update ".CATEGORIES_TABLE." set description = '".$data[$i][ $dbc["description"] ]."' where categoryID='$cid'") or die (db_error());
							}
						}

					}
					else //a product
					{

						$q = db_query("select count(*) from ".PRODUCTS_TABLE." where $update_column LIKE '".$data[$i][ $uc ]."'") or die (db_error());
						$row = db_fetch_row($q);
						if (!$row[0]) //no product found - insert new
						{
							$data[$i][ "not defined" ] = "";

							db_set_identity(PRODUCTS_TABLE);
							db_query("insert into ".PRODUCTS_TABLE." (enabled, categoryID, name, description, Price, in_stock, items_sold, brief_description, list_price, product_code, picture, thumbnail, big_picture) values (1, '$cid', '".$data[$i][ $dbc["name"] ]."', '".$data[$i][ $dbc["description"] ]."', '".str_replace(" ","",$data[$i][ $dbc["Price"] ])."', '".str_replace(" ","",$data[$i][ $dbc["in_stock"] ])."', '".str_replace(" ","",$data[$i][ $dbc["items_sold"] ])."', '".$data[$i][ $dbc["brief_description"] ]."', '".str_replace(" ","",$data[$i][ $dbc["list_price"] ])."', '".$data[$i][ $dbc["product_code"] ]."', '".$data[$i][ $dbc["picture"] ]."', '".$data[$i][ $dbc["thumbnail"] ]."', '".$data[$i][ $dbc["big_picture"] ]."')") or die (db_error());
							$pid = db_insert_id("PRODUCTS_GEN");
						}
						else //update
						{
							//get productID
							$q = db_query("select productID from ".PRODUCTS_TABLE." where $update_column LIKE '".$data[$i][ $uc ]."'") or die (db_error());
							$row = db_fetch_row($q);
							$pid = $row[0];

							$query = "update ".PRODUCTS_TABLE." set ";

							$a = 0;
							if ($dbc["name"]!="not defined") { $query.=$a?", ":""; $query.= "name='".$data[$i][ $dbc["name"] ]."'"; $a++; }
							if (strcmp($dbc["product_code"],"not defined")) { $query.=$a?", ":""; $query.= "product_code='".$data[$i][ $dbc["product_code"] ]."'"; $a++; }

							if ($dbc["description"]!="not defined") { $query.=$a?", ":""; $query.= "description='".$data[$i][ $dbc["description"] ]."'"; $a++; }
							if ($dbc["brief_description"]!="not defined") { $query.=$a?", ":""; $query.= "brief_description='".$data[$i][ $dbc["brief_description"] ]."'"; $a++; }
							if ($dbc["Price"]!="not defined") { $query.=$a?", ":""; $query.= "Price='".str_replace(" ","",$data[$i][ $dbc["Price"] ])."'"; $a++; }
							if ($dbc["list_price"]!="not defined") { $query.=$a?", ":""; $query.= "list_price='".str_replace(" ","",$data[$i][ $dbc["list_price"] ])."'"; $a++; }
							if ($dbc["in_stock"]!="not defined") { $query.=$a?", ":""; $query.= "in_stock='".str_replace(" ","",$data[$i][ $dbc["in_stock"] ])."'"; $a++; }
							if ($dbc["items_sold"]!="not defined") { $query.=$a?", ":""; $query.= "items_sold='".str_replace(" ","",$data[$i][ $dbc["items_sold"] ])."'"; $a++; }
							if ($dbc["picture"]!="not defined") { $query.=$a?", ":""; $query.= "picture='".str_replace(" ","",$data[$i][ $dbc["picture"] ])."'"; $a++; }
							if ($dbc["big_picture"]!="not defined") { $query.=$a?", ":""; $query.= "big_picture='".str_replace(" ","",$data[$i][ $dbc["big_picture"] ])."'"; $a++; }
							if ($dbc["thumbnail"]!="not defined") { $query.=$a?", ":""; $query.= "thumbnail='".str_replace(" ","",$data[$i][ $dbc["thumbnail"] ])."'"; $a++; }



							$query .= " where $update_column='".$data[$i][ $uc ]."'";

							if ($a) db_query($query) or die (db_error());
						}

						//now setup all product's extra options
						for ($j=0; $j<count($extra_option); $j++)
							if ($extra_option[$j]) //add
							{
								db_query("delete from ".PRODUCT_OPTIONS_VALUES_TABLE." where optionID=$extra_option[$j] and productID=$pid") or die (db_error());
								db_query("insert into ".PRODUCT_OPTIONS_VALUES_TABLE." (optionID,productID,option_value) values ($extra_option[$j], $pid, '".$data[$i][$j]."')") or die (db_error());
							}
					}
				}
			}

			echo "<form action=admin.php method=get><input type=submit value=\"".OK_BUTTON."\"></form>";
			exit;

		}

	}






	if (isset($proceed) && isset($mode)) {

		$res = 0;

		if ($mode == 2) // reset database content
		{
			db_query("DELETE FROM ".PRODUCTS_TABLE."") or die (db_error());
			db_query("DELETE FROM ".PRODUCT_OPTIONS_TABLE."") or die (db_error());
			db_query("DELETE FROM ".PRODUCT_OPTIONS_VALUES_TABLE."") or die (db_error());
			db_query("DELETE FROM ".CATEGORIES_TABLE." WHERE categoryID<>0") or die (db_error());
			db_query("DELETE FROM ".RELATED_PRODUCTS_TABLE."") or die (db_error());
			db_query("DELETE FROM ".DISCUSSIONS_TABLE."") or die (db_error());
			$res = 1;
			echo "<br><font class=cat color=blue><b><u>".ADMIN_UPDATE_SUCCESSFUL."</u></b></font><br><br>";
		}
		else //update
		{

			//upload CSV-file
			if ($csv && $csv != "none")
			{
				$file_excel_name = "file.csv";
				$res = copy(trim($csv), "products_pictures/".$file_excel_name);
			}

			if (isset($res) && $res)
			{

				//show import configurator

				$f = fopen("products_pictures/".$file_excel_name,"r");
				$data = array();
				while ($row = fgetcsv($f, filesize("products_pictures/".$file_excel_name), ";"))
				{
					$data[] = $row;
				}
				if (!count($data)) die (ERROR_CANT_READ_FILE);

				//skip empty lines
				$i = 0;
				while ($i<count($data) && count($data[$i])>0 && ($n = get_NOTempty_elements_count($data[$i])) < count($data[$i]))
				{
					$i++;
				}
				$notl = $i;

				//display all headers into a form that allows to assign each column a value into database
				echo "<p>".ADMIN_IMPORT_DESC1."<p>".ADMIN_IMPORT_DESC2;
				echo "<form action=admin.php method=post>\n<table>";

				for ($j=0; $j<$n; $j++)
				{

					echo "
					<tr>
						<td><b><input type=text name=column_name_$j value=\"".$data[$i][$j]."\"></b></td>
						<td>=></td>
						<td>
							<select name=db_association_$j>
								<option value=\"ignore\">".ADMIN_IGNORE."</option>
								<option value=\"add\">".ADMIN_ADD_AS_NEW_PARAMETER."</option>
								<option value=\"product_code\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_CODE).">".ADMIN_PRODUCT_CODE."</option>
								<option value=\"name\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_NAME).">".ADMIN_PRODUCT_NAME."</option>
								<option value=\"Price\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_PRICE).">".ADMIN_PRODUCT_PRICE."</option>
								<option value=\"list_price\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_LISTPRICE).">".ADMIN_PRODUCT_LISTPRICE."</option>
								<option value=\"in_stock\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_INSTOCK).">".ADMIN_PRODUCT_INSTOCK."</option>
								<option value=\"items_sold\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_SOLD).">".ADMIN_PRODUCT_SOLD."</option>
								<option value=\"description\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_DESC).">".ADMIN_PRODUCT_DESC."</option>
								<option value=\"brief_description\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_BRIEF_DESC).">".ADMIN_PRODUCT_BRIEF_DESC."</option>
								<option value=\"picture\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_PICTURE).">".ADMIN_PRODUCT_PICTURE."</option>
								<option value=\"thumbnail\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_THUMBNAIL).">".ADMIN_PRODUCT_THUMBNAIL."</option>
								<option value=\"big_picture\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_BIGPICTURE).">".ADMIN_PRODUCT_BIGPICTURE."</option>
							</select>
						</td>
					</tr>
					";
				}
				echo "</table>";


				//update column
				echo "<p>".ADMIN_PRIMARY_COLUMN.": <select name=update_column>";
					echo "<option value=\"product_code\">".ADMIN_PRODUCT_CODE."</option>";
					echo "<option value=\"name\">".ADMIN_PRODUCT_NAME."</option>";
					echo "<option value=\"Price\">".ADMIN_PRODUCT_PRICE."</option>";
					echo "<option value=\"description\">".ADMIN_PRODUCT_DESC."</option>";
					echo "<option value=\"brief_description\">".ADMIN_PRODUCT_BRIEF_DESC."</option>";
				echo "</select><br>".ADMIN_PRIMARY_COLUMN_DESC;


				echo "<p><input type=submit name=do_excel_import value=\"".OK_BUTTON."\">";
				echo "<input type=hidden name=path value=excel_import>";
				echo "<input type=hidden name=number_of_titles_line value=$notl>";
				echo "<input type=hidden name=filename value=\"products_pictures/$file_excel_name\">";



				echo "</form>";


			}
			else echo ERROR_FAILED_TO_UPLOAD_FILE;
		}



	}
	else //show default file upload form
	{

?>

<form enctype="multipart/form-data" action="admin.php?path=excel_import" method=POST>
<font>
<?=ADMIN_EXCEL_DESC1;?>
</font>
<p>
<input type="file" name="csv">
</p>

<table cellpadding=7>

<tr>
<td valign=top><input type="radio" name=mode value=0 checked></td>
<td><?=ADMIN_EXCEL_UPDATE_DB; ?>
</td>
</tr>

<tr>
<td valign=top><input type="radio" name=mode value=2></td>
<td><?=ADMIN_EXCEL_CLEAR_DB;?><br><?=ADMIN_EXCEL_CLEAR_DB_DESC;?>
</td>
</tr>

</table><br>

<input type=submit value="<?=OK_BUTTON; ?>">
<input type=hidden name=proceed value=1>
<input type=button value="<?=CANCEL_BUTTON; ?>" onClick="javascript:window.location='admin.php';">
<?

	}


?>