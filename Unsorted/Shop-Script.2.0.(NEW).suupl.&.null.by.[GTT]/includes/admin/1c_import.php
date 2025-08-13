<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/
 
 
 
	//ADMIN:: import from 1Ñ

	//ýòîò ôàéë àêòóàëåí òîëüêî äëÿ ðóññêîÿçûíûõ ïîëüçîâàòåëåé
	//ïîýòîìó çäåñü èñïîëüçóþòñÿ òîëüêî ëèøü ðóññêèå ôðàçû.
	//âñå æå, ÷òîáû íå íàðóøàòü ñòèëü îôîðìëåíèÿ èñõîäíûõ êîäîâ,
	//âñå ôðàçû âûíåñåíû â îòäåëüíûå êîíñòàíòû

define('ADMIN_IMPORT_FROM_1C', 'Èìïîðò èç 1Ñ');
define('ADMIN_1C_DESC1', 'Ïîæàëóéñòà, ââåäèòå èìÿ ýêñïîðòèðîâàííîãî èç 1Ñ ôàéëà (ñïèñêà íîìåíêëàòóðû).<br>Ôàéë äîëæåí áûòü ñîõðàíåí êàê `Òåêñòîâûé ôàéë` (ñ ðàçäåëèòåëÿìè-òàáóëÿöèÿìè).<br> Ïðèìåð ìîæíî ïîñìîòðåòü <a href="example_1c.txt">çäåñü</a>');


function myfgetcsv ($fname, $del)
// use this instead of standard function, because standard one doesn't parse 1C files
// still, for excel files fgetcsv is used...
{
	$f = str_replace( "'","`", implode ("", file($fname) ) );
	$f = explode ($del, $f);

	$data = array(); //all csv-file content
	$data[0] = array();
	$str_count = 0;

	//now parse $f into rows of $data table
	for ($i=0;$i<count($f);$i++)
	{
		if (strstr($f[$i], "\n")) //suspicion on new line
		{
			//is \n is inside the string?? e.g. in " ... \n ... "

			if ($f[$i][0] == "\"" && $f[$i][strlen($f[$i])-1] == "\"")
			{
				//inside => add this cell without " at the bounds
				//and do not start new line
				$tmp = "";
				for ($j=1;$j<strlen($f[$i])-1;$j++) $tmp.= $f[$i][$j];
				$data[$str_count][] = $tmp;
			}
			else
			{
				$a = explode("\n", $f[$i]);
				//actually, it's impossible for $a to contain more than 2 elements...
				if (count($a) > 2) return -1;
				$data[$str_count++][] = $a[0];
				$data[$str_count] = array();
				$data[$str_count][]   = $a[1];
			}
		}
		else
		{
			$data[$str_count][] = $f[$i];
		}

	}

	return $data;

} //myfgetcsv



	echo "<a href=\"admin.php\"><u>".ADMIN_MAIN_MENU."</u></a> : <u><font>".ADMIN_IMPORT_FROM_1C."</font></u> :<br><br>";


	if (isset($do_1c_import) && isset($filename) && isset($update_column)) //configuration finished - update database
	{

		echo "Please wait...<hr width=90%>";

		set_time_limit(4*60);

		//import file content
		$data = myfgetcsv ($filename, "\t");
		if ($data == -1) die (ERROR_CANT_READ_FILE);

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
			"brief_description" => "not defined"
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

		//start for titles line and so on till the end of the file
		$cid = 0;

		//get update column
		$uc = $dbc[ $update_column ];
		if (!strcmp($uc,"not defined")) //not set update column
		{
			echo "<p><b><font color=red>Íå óêàçàíî ïîëå, ïî êîòîðîìó îáíîâëÿòü áàçó èç ôàéëà</font></b>";
			//go to the previous step
			$proceed = 1;
			$file_1c = "";
			$file_1c_name = str_replace("products_pictures/","",$filename);
			$res = 1;
		}
		else
		if ((!strcmp($dbc["product_code"],"not defined")) && (!strcmp($dbc["name"],"not defined"))) //product_code is not set
		{
			echo "<p><b><font color=red>Ïîëÿ íàçâàíèå è êîä íå çàäàíû...</font></b>";
			//go to the previous step
			$proceed = 1;
			$file_1c = "";
			$file_1c_name = str_replace("products_pictures/","",$filename);
			$res = 1;
		}
		else
		{
			for ($i=$number_of_titles_line+1; $i<count($data); $i++)
			{
				$cnt = get_NOTempty_elements_count($data[$i]);
				if ($cnt > 0)
				{
					if ($cnt == 1) //category
					{
						$q = db_query("select count(*) from ".CATEGORIES_TABLE." where categoryID<>0 and name LIKE '".$data[$i][ $dbc["name"] ]."'") or die (db_error());
						$row = db_fetch_row($q);
						if (!$row[0]) //insert new category (fill the name column only)
						{
							db_set_identity(CATEGORIES_TABLE);
							db_query("insert into ".CATEGORIES_TABLE." (name, parent, products_count, description, picture, products_count_admin) values ('".$data[$i][ $dbc["name"] ]."',0,0,'','',0);") or die (db_error());
							$cid = db_insert_id("CATEGORIES_GEN");
						}
						else //get categoryID
						{
							$q = db_query("select categoryID from ".CATEGORIES_TABLE." where categoryID<>0 and name LIKE '".$data[$i][ $dbc["name"] ]."'") or die (db_error());
							$row = db_fetch_row($q);
							$cid = $row[0];
						}

					}
					else //a product
					{

						$q = db_query("select count(*) from ".PRODUCTS_TABLE." where $update_column LIKE '".$data[$i][ $uc ]."'") or die (db_error());
						$row = db_fetch_row($q);
						if (!$row[0]) //no product found - insert new
						{
							db_set_identity(PRODUCTS_TABLE);

							$data[$i][ "not defined" ] = "";

							db_query("insert into ".PRODUCTS_TABLE." (enabled, categoryID, name, description, Price, in_stock, items_sold, brief_description, list_price, product_code) values (1, '$cid', '".$data[$i][ $dbc["name"] ]."', '".$data[$i][ $dbc["description"] ]."', '".str_replace(" ","",$data[$i][ $dbc["Price"] ])."', '".str_replace(" ","",$data[$i][ $dbc["in_stock"] ])."', '".str_replace(" ","",$data[$i][ $dbc["items_sold"] ])."', '".$data[$i][ $dbc["brief_description"] ]."', '".str_replace(" ","",$data[$i][ $dbc["list_price"] ])."', '".$data[$i][ $dbc["product_code"] ]."')") or die (db_error());
							$pid = db_insert_id("PRODUCTS_GEN");
						}
						else //update
						{
							//get productID
							$q = db_query("select productID from ".PRODUCTS_TABLE." where product_code LIKE '".$data[$i][ $dbc["product_code"] ]."'") or die (db_error());
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





	if (isset($proceed)) { //upload file and run configurator

		//upload CSV-file
		if ($file_1c && $file_1c != "none")
		{
			$file_1c_name = "1c.txt";
			$res = copy(trim($file_1c), "products_pictures/".$file_1c_name);
		}

		if (isset($res) && $res) //file uploaded
		{
			//show import configurator

			$data = myfgetcsv ("products_pictures/".$file_1c_name, "\t");
			if ($data == -1) die ("Îøèáêà: íå óäàëîñü ïðî÷èòàòü ôàéë");

			//skip empty lines
			$i = 0;
			while ($i<count($data) && ($n = get_NOTempty_elements_count($data[$i])) < 2) $i++;
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
							<option value=\"product_code\"".mark_as_selected($data[$i][$j],"Êîä").">Êîä</option>
							<option value=\"name\"".mark_as_selected($data[$i][$j],"Íàèìåíîâàíèå").">Íàèìåíîâàíèå</option>
							<option value=\"Price\"".mark_as_selected($data[$i][$j],"Öåíà").">Öåíà</option>
							<option value=\"list_price\">Ñòàðàÿ öåíà</option>
							<option value=\"in_stock\">Îñòàòîê íà ñêëàäå (øò.)</option>
							<option value=\"items_sold\">Ïðîäàíî (øò.)</option>
							<option value=\"description\">Îïèñàíèå</option>
							<option value=\"brief_description\">Êðàòêîå îïèñàíèå</option>
						</select>
					</td>
				</tr>
				";
			}
			echo "</table>";


			//update column
			echo "<p>Êîëîíêà èäåíòèôèêàöèè: <select name=update_column>";
				echo "<option value=\"product_code\">Êîä</option>";
				echo "<option value=\"name\">Íàèìåíîâàíèå</option>";
				echo "<option value=\"Price\">Öåíà</option>";
			echo "</select><br>(óêàæèòå êîëîíêó, çíà÷åíèå â êîòîðîé îäíîçíà÷íî èäåíòèôèöèðóåò òîâàð)";


			echo "<p><input type=submit name=do_1c_import value=\"".OK_BUTTON."\">";
			echo "<input type=hidden name=path value=1c_import>";
			echo "<input type=hidden name=number_of_titles_line value=$notl>";
			echo "<input type=hidden name=filename value=\"products_pictures/$file_1c_name\">";



			echo "</form>";




		}
		else echo ERROR_FAILED_TO_UPLOAD_FILE;



	}
	else //show upload form
	{


?>

<form enctype="multipart/form-data" action="admin.php" method=POST>
<font>
<?=ADMIN_1C_DESC1;?>
</font>
<p>
<input type="file" name="file_1c">
</p>


<input type=submit value="<?=OK_BUTTON; ?>">
<input type=hidden name=proceed value=1>
<input type=hidden name=path value=1c_import>
<input type=button value="<?=CANCEL_BUTTON; ?>" onClick="javascript:window.location='admin.php';">
<?

	}

?>