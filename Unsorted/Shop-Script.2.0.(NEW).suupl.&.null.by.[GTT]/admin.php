<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/
 
 
 
 
 
 //main admin module

	include("cfg/connect.inc.php");
	include("includes/database/".DBMS.".php");
	include("./cfg/settings.inc.php");

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


	//connect to database
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());

	include("checklogin.php");
	if (!isset($log) || strcmp($log, ADMIN_LOGIN)) //unauthorized access
	{
		die (ERROR_FORBIDDEN);
	}

	include("functions.php");

	include("cfg/currency.inc.php");


	// several functions

	function update_products_Count_Value_For_Categories($parent)
	{
		//updates products_count and products_count_admin values for each category

		$q = db_query("SELECT categoryID FROM ".CATEGORIES_TABLE." WHERE parent=$parent AND categoryID<>0") or die (db_error());
		$cnt = array();
		$cnt[0] = 0;
		$cnt[1] = 0;

		while ($row = db_fetch_row($q))
		{

			//process subcategories

			//products_count of current category ($count[0]) surpluses it's subcategories' productsCounts
			$t = update_products_Count_Value_For_Categories($row[0]);
			$cnt[0] += $t[0];
			$cnt[1]  = $t[1];

		}

		$p = db_query("SELECT count(*) FROM ".PRODUCTS_TABLE." WHERE categoryID=$parent") or die (db_error());
		$t = db_fetch_row($p); $t = $t[0];
		$p = db_query("SELECT count(*) FROM ".PRODUCTS_TABLE." WHERE categoryID=$parent AND Price>0 AND enabled=1") or die (db_error());
		$c = db_fetch_row($p); $c = $c[0];
		$cnt[0] += $c;
		$cnt[1]  = $t;

		//save calculations
		if ($parent)
			db_query("UPDATE ".CATEGORIES_TABLE." SET products_count=$cnt[0], products_count_admin=$cnt[1] WHERE categoryID=".$parent) or die (db_error());

		return $cnt;

	} //update_products_Count_Value_For_Categories


	function mark_as_selected($a,$b) //required for excel import
	//returns " selected" if $a == $b
	{
		return !strcmp($a,$b) ? " selected" : "";

	} //mark_as_selected


	function get_NOTempty_elements_count($arr) //required for excel import
		//gets how many NOT NULL (not empty strings) elements are there in the $arr
	{
		$n = 0;
		for ($i=0;$i<count($arr);$i++)
			if (trim($arr[$i]) != "") $n++;
		return $n;
	} //get_NOTempty_elements_count


	function processCategories(&$list, $parent,$level) //draw categories navigation table
	{

		for ($i=0; $i<count($list); $i++)
		 if ($list[$i][2] == $parent)
		 {

			//make a link to category edition
			echo "<tr><td>";
			for ($j=0; $j<$level; $j++) echo "&nbsp;&nbsp;";
			echo "&nbsp;<a";
			if ($level) echo " class=\"standard\"";
			echo " href=\"javascript:open_window('category.php?c_id=".$list[$i][0]."&w=".$list[$i][2]."',400,400);\">".str_replace("<","&lt;",$list[$i][1])."</a> (".$list[$i][3].")</td>\n";
			echo "<td align=right><font color=red>[</font><a class=small href=\"admin.php?categoryID=".$list[$i][0]."&path=categories_items\">=></a><font color=red>]</font></td></tr>\n";

			processCategories(&$list, $list[$i][0],$level+1);
	 	 }

	} //processCategories

	//end of functions definition



	if (isset($sys_save)) //save system settings
	{


		$f = fopen("cfg/settings.inc.php","w");
		fputs($f,"<?\n\t\$tax = \"$tax_\";\n");
		fputs($f,"\t\$products_count = \"$productscount\";\n");
		fputs($f,"\t\$cols_count = \"$colscount\";\n");
		fputs($f,"\t\$dark_color = \"$darkcolor\";\n");
		fputs($f,"\t\$middle_color = \"$middlecolor\";\n");
		fputs($f,"\t\$light_color = \"$lightcolor\";\n");
		fputs($f,"\t\$shopname = \"$shop_name\";\n");
		fputs($f,"\t\$shopurl = \"$shop_url\";\n");
		fputs($f,"\t\$inn = \"$inn_\";\n");
		fputs($f,"\t\$kpp = \"$kpp_\";\n");
		fputs($f,"\t\$reciever = \"$reciever_\";\n");
		fputs($f,"\t\$rs = \"$rs_\";\n");
		fputs($f,"\t\$ks = \"$ks_\";\n");
		fputs($f,"\t\$bik = \"$bik_\";\n");
		fputs($f,"?>");
		fclose($f);

		//currencies
		$f = fopen("cfg/currency.inc.php","w");
		fputs($f,"<?\n");
		$vars = get_defined_vars();
		foreach ($vars as $key => $val)
		{
		  if(strstr($key, "cn_") != false && $val!="") {
			fputs($f,"\t\$currency_name[] = \"$val\";\n");
		  }
		  else
		  if(strstr($key, "cv_") != false && $val!="") {
			$val = str_replace(",",".", trim($val));
			fputs($f,"\t\$currency_value[] = \"$val\";\n");
		  }
		  else
		  if(strstr($key, "wh_") != false) {
			$val = str_replace(",",".", trim($val));
			fputs($f,"\t\$currency_where[] = $val;\n");
		  }
		}

		//add new currency type?
		if (isset($new_cn) && isset($new_cv) && $new_cn!="" && $new_cv!="")
		{
			fputs($f,"\t\$currency_name[] = \"$new_cn\";\n");
			fputs($f,"\t\$currency_value[] = \"$new_cv\";\n");
			fputs($f,"\t\$currency_where[] = $new_wh;\n");
		}

		fputs($f,"\t\$invoice_currency = $invoice_currency_;\n");

		fputs($f,"?>");
		fclose($f);

		header("Location: admin.php");
	}


	if (isset($save_SP)) //save shipping and payment types
	{
		$shippings = array();
		$payments = array();

		$vars = get_defined_vars();
		foreach ($vars as $key => $val)
		{
		  //shipping?
		  if(strstr($key, "ship_en_") != false)
		  {
			$a = str_replace("ship_en_","",$key);
			$shippings[$a]["en"] = $val;
		  }
		  if(strstr($key, "ship_name_") != false)
		  {
			$a = str_replace("ship_name_","",$key);
			$shippings[$a]["name"] = $val;
		  }
		  if(strstr($key, "ship_desc_") != false)
		  {
			$a = str_replace("ship_desc_","",$key);
			$shippings[$a]["desc"] = $val;
		  }
		  if(strstr($key, "ship_lump_") != false)
		  {
			$a = str_replace("ship_lump_","",$key);
			$shippings[$a]["lump"] = $val;
		  }
		  if(strstr($key, "ship_perc_") != false)
		  {
			$a = str_replace("ship_perc_","",$key);
			$shippings[$a]["perc"] = $val;
		  }

		  //payment?
		  if(strstr($key, "pay_en_") != false)
		  {
			$a = str_replace("pay_en_","",$key);
			$payments[$a]["en"] = $val;
		  }
		  if(strstr($key, "pay_name_") != false)
		  {
			$a = str_replace("pay_name_","",$key);
			$payments[$a]["name"] = $val;
		  }
		  if(strstr($key, "pay_desc_") != false)
		  {
			$a = str_replace("pay_desc_","",$key);
			$payments[$a]["desc"] = $val;
		  }
		  if(strstr($key, "pay_tax_") != false)
		  {
			$a = str_replace("pay_tax_","",$key);
			$payments[$a]["tax"] = $val;
		  }
		}

		//now update database ------------------------------------------------ //

		//erase all
		db_query("delete from ".SHIPPING_METHODS_TABLE) or die (db_error());
		db_query("delete from ".PAYMENT_TYPES_TABLE) or die (db_error());

		//insert new records
		for ($i=0;$i<count($shippings);$i++)
			if ($shippings[$i]["name"] != "")
			{
				db_set_identity(SHIPPING_METHODS_TABLE);
				$s = "insert into ".SHIPPING_METHODS_TABLE." (Name,description, lump_sum, percent_value, Enabled) values (";
				$s.= "'".$shippings[$i]["name"]."'";
				$s.= ",'".$shippings[$i]["desc"]."'";
				$s.= ",'".$shippings[$i]["lump"]."'";
				$s.= ",'".$shippings[$i]["perc"]."'";
				if (isset($shippings[$i]["en"]))
					$s.= ", 1";
				else
					$s.= ", 0";
				$s.= ");";
				db_query($s) or die (db_error());
			}
		//new type?
		if ($new_shipname!="") //add new type
		{
			if (isset($new_shipen)) $a = 1;
			else $a = 0;

			db_set_identity(SHIPPING_METHODS_TABLE);
			db_query("insert into ".SHIPPING_METHODS_TABLE." (Name,description, lump_sum, percent_value, Enabled) values ('$new_shipname', '$new_shipdesc', '$new_shiplump', '$new_shipperc', $a);") or die (db_error());
		}



		//payments
		for ($i=0;$i<count($payments);$i++)
			if ($payments[$i]["name"] != "")
			{
				$s = "insert into ".PAYMENT_TYPES_TABLE." (Name, description, Enabled, calculate_tax) values (";
				$s.= "'".$payments[$i]["name"]."'";
				$s.= ",'".$payments[$i]["desc"]."'";
				if (isset($payments[$i]["en"]))
					$s.= ", 1";
				else
					$s.= ", 0";
				if (isset($payments[$i]["tax"]))
					$s2 = ", 1";
				else
					$s2 = ", 0";
				$s2 .= ");";
				db_set_identity(PAYMENT_TYPES_TABLE);
				db_query($s.$s2) or die (db_error());
			}
		//new type?
		if ($new_payname!="") //add new payment type
		{
			if (isset($new_payen)) $a = 1;
			else $a = 0;
			if (isset($new_paytax)) $b = 1;
			else $b = 0;

			db_set_identity(PAYMENT_TYPES_TABLE);
			db_query("insert into ".PAYMENT_TYPES_TABLE." (Name, description, Enabled, calculate_tax) values ('$new_payname', '$new_paydesc', $a, $b);") or die (db_error());
		}

		header("Location:admin.php?path=$path");
	}

	if (isset($save_gateways)) //save payment gateways_settings
	{
		if (!isset($cc_type)) $cc_type = -1;
		$f = fopen("cfg/cc.inc.php","w");
		fputs($f,"<?\n");
		fputs($f,"\t\$cc_payment_type = $cc_type;\n");
		fputs($f,"\t\$payment_gateway = \"$gateway\";\n");
		fputs($f,"\t\$conn_type = \"$conn_type\";\n");
		fputs($f,"?>");
		fclose($f);
		header("Location: admin.php?path=gateways");
	}


	if (isset($aux_save) && $auxpage != "" && $page != "") //save auxiliary page
	{
		$f = fopen("cfg/$page","w");
		fputs($f,$auxpage);
		fclose($f);
		header("Location: admin.php");
	}

	if (isset($save_options)) //save extra product options
	{
		//save existing
		db_query("delete from ".PRODUCT_OPTIONS_TABLE."") or die (db_error());
		db_set_identity(PRODUCT_OPTIONS_TABLE);
		$vars = get_defined_vars();
		foreach ($vars as $key => $val)
		{
		  if(strstr($key, "extra_option_") !== false && trim($val)!="")
		  {
			db_query("insert into ".PRODUCT_OPTIONS_TABLE." (name) values ('$val')") or die (db_error());
		  }
		}
		if (isset($add_option) && $add_option!="")
			db_query("insert into ".PRODUCT_OPTIONS_TABLE." (name) values ('$add_option')") or die (db_error());

		header("Location: admin.php?path=product_options&succ=yes");
	}

	if (isset($kill_option))
	{
		db_query("delete from ".PRODUCT_OPTIONS_TABLE." where optionID=$kill_option") or die (db_error());
		header("Location: admin.php?path=product_options");		
	}

	if (isset($save_voting) && isset($question) && isset($answers)) // new voting
	{

		$f = fopen("cfg/voting.txt","w");
		fputs($f,"$question\n");
		fputs($f,"$answers\n");
		fclose($f);

		$f = fopen("cfg/voting_results.txt","w");
		$answers = explode("\n",$answers);
		for ($i=0; $i<count($answers); $i++) fputs($f,"0\n");
		fclose($f);

		header("Location: admin.php");
	}


	//database synchronization
	//affects only products and categories database! doesn't touch customers and orders tables

	$helper = "[#%int!g%#]"; //helper

	// generate SQL-file //

	if (isset($export_db)) //export database to SQL-file
	{
		set_time_limit(60*4);

		$f = fopen("database.sql","w");

		//categories
		$q = db_query("SELECT * FROM ".CATEGORIES_TABLE." where categoryID<>0 ORDER BY categoryID");
		while($row = db_fetch_row($q))
		{
			$cnt = 6;
			$s = "INSERT INTO ".CATEGORIES_TABLE." (categoryID, name, parent, products_count, description, picture, products_count_admin) VALUES (";
			for ($i=0; $i<$cnt; $i++)
				$s .= "'".str_replace("INSERT INTO",$helper,str_replace("'","`",$row[$i]))."', ";
			$s .= "'".str_replace("INSERT INTO",$helper,str_replace("'","`",$row[$cnt]))."');\n";
			fputs($f,$s);
		}

		//products
		$q = db_query("SELECT * FROM ".PRODUCTS_TABLE." ORDER BY productID");
		while($row = db_fetch_row($q))
		{
			$cnt = 16;
			$s = "INSERT INTO ".PRODUCTS_TABLE." (productID, categoryID, name, description, customers_rating, Price, picture, in_stock, thumbnail, customer_votes, items_sold, show_as_special_offer, big_picture, enabled, brief_description, list_price, product_code) VALUES (";
			for ($i=0; $i<$cnt; $i++)
			{
				$s .= "'".str_replace("INSERT INTO",$helper,str_replace("'","`",$row[$i]))."', ";
			}
			$s .= "'".str_replace("INSERT INTO",$helper,str_replace("'","`",$row[$cnt]))."');\n";
			fputs($f,$s);
		}

		//wish-list
		$q = db_query("SELECT * FROM ".RELATED_PRODUCTS_TABLE."");
		while($row = db_fetch_row($q))
		{
			$s = "INSERT INTO ".RELATED_PRODUCTS_TABLE." (productID, Owner) VALUES ($row[0], $row[1]);\n";
			fputs($f,$s);
		}

		//discussions
		$q = db_query("SELECT * FROM ".DISCUSSIONS_TABLE."");
		while($row = db_fetch_row($q))
		{
			$cnt = 5;
			$s = "INSERT INTO ".DISCUSSIONS_TABLE." (DID, productID, Author, Body, add_time, Topic) VALUES (";
			for ($i=0; $i<$cnt; $i++)
				$s .= "'".str_replace("INSERT INTO",$helper,str_replace("'","`",$row[$i]))."', ";
			$s .= "'".str_replace("INSERT INTO",$helper,str_replace("'","`",$row[$cnt]))."');\n";
			fputs($f,$s);
		}

		//product extra options
		$q = db_query("SELECT * FROM ".PRODUCT_OPTIONS_TABLE."");
		while($row = db_fetch_row($q))
		{
			$s = "INSERT INTO ".PRODUCT_OPTIONS_TABLE." (optionID, name) VALUES ($row[0], '$row[1]');\n";
			fputs($f,$s);
		}
		//values
		$q = db_query("SELECT * FROM ".PRODUCT_OPTIONS_VALUES_TABLE."");
		while($row = db_fetch_row($q))
		{
			$s = "INSERT INTO ".PRODUCT_OPTIONS_VALUES_TABLE." (optionID, productID, option_value) VALUES ($row[0], $row[1], '$row[2]');\n";
			fputs($f,$s);
		}

		fclose($f);

		$path = "synchronize";

	}

	if (isset($import_db)) //execute sql-file
	{
		set_time_limit(60*4);

		//upload file
		if (isset($db) && $db && $db != "none")
		{
			$db_name = "file.db";
			$res = copy(trim($db), "file.db");

			$f = implode("",file("file.db"));
			$f = explode("INSERT INTO",$f);

			db_query("DELETE FROM ".PRODUCTS_TABLE) or die (db_error());
			db_query("DELETE FROM ".CATEGORIES_TABLE." WHERE categoryID<>0") or die (db_error());
			db_query("DELETE FROM ".RELATED_PRODUCTS_TABLE) or die (db_error());
			db_query("DELETE FROM ".DISCUSSIONS_TABLE) or die (db_error());
			db_query("DELETE FROM ".PRODUCT_OPTIONS_TABLE) or die (db_error());
			db_query("DELETE FROM ".PRODUCT_OPTIONS_VALUES_TABLE) or die (db_error());

			for ($i=1;$i<count($f);$i++)
			{
				db_set_identity(PRODUCTS_TABLE);
				db_set_identity(CATEGORIES_TABLE);
				db_set_identity(DISCUSSIONS_TABLE);
				db_set_identity(PRODUCT_OPTIONS_TABLE);

				if (strstr($f[$i],PRODUCTS_TABLE))
					db_set_identity(PRODUCTS_TABLE,"ON");
				else
				if (strstr($f[$i],CATEGORIES_TABLE))
					db_set_identity(CATEGORIES_TABLE,"ON");
				else
				if (strstr($f[$i],DISCUSSIONS_TABLE))
					db_set_identity(DISCUSSIONS_TABLE,"ON");
				else
				if (strstr($f[$i],PRODUCT_OPTIONS_TABLE))
					db_set_identity(PRODUCT_OPTIONS_TABLE,"ON");

				db_query(trim("INSERT INTO ".str_replace($helper,"INSERT INTO",$f[$i]))) or die (db_error());
			}

			unlink("file.db");

		} else $imp_err = ERROR_FAILED_TO_UPLOAD_FILE;

		$path = "synchronize";

	}

?>
<html>
<head>

<script>
	function confirmDelete(id, ask, url) { //confirm order delete
		temp = window.confirm(ask);
		if (temp) { //delete
			window.location=url+id;
		};
	};
	function open_window(link,w,h) {
		var win = "width="+w+",height="+h+",menubar=no,location=no,resizable=yes,scrollbars=yes";
		newWin = window.open(link,'newWin',win);
	};
</script>

<link rel=STYLESHEET href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?=DEFAULT_CHARSET;?>">
<title><?=ADMIN_TITLE;?></title>
</head>

<body bgcolor=#EEEEEE>
<center>
<h1><?=ADMIN_TITLE;?></h1>

<?

	if (!isset($path)) //show menu
	{
		$q = db_query("SELECT count(*) FROM ".ORDERS_TABLE." WHERE Done=0") or die (db_error());
		$r = db_fetch_row($q);
?>

<table cellpadding=5>
<tr>
<td><a href="admin.php?path=new_orders"><img src="images/new.gif" border=0></a></td>
<td><a href="admin.php?path=new_orders"><u><?=ADMIN_NEW_ORDERS;?></u></a> (<?=$r[0]; ?>)</td>
</tr>
<tr>
<td><a href="admin.php?path=categories_items"><img src="images/cat.gif" border=0></a></td>
<td><a href="admin.php?path=categories_items"><u><?=ADMIN_CATEGORIES_PRODUCTS;?></u></a></td>
</tr>
<tr>
<td><a href="admin.php?path=product_options"><img src="images/extra.gif" border=0></a></td>
<td><a href="admin.php?path=product_options"><u><?=ADMIN_PRODUCT_OPTIONS;?></u></a></td>
</tr>
<tr>
<td align=center><a href="admin.php?path=settings"><img src="images/edit.gif" border=0></a></td>
<td><a href="admin.php?path=settings"><u><?=ADMIN_SETTINGS;?></u></a></td>
</tr>
<tr>
<td align=center><a href="admin.php?path=shipping_payment"><img src="images/ship.gif" border=0></td>
<td><a href="admin.php?path=shipping_payment"><u><?=ADMIN_SHIPPING_PAYMENT;?></u></a></td>
</tr>
<tr>
<td align=center><a href="admin.php?path=gateways"><img src="images/payment.gif" border=0></a></td>
<td><a href="admin.php?path=gateways"><u><?=ADMIN_CC_PROCESSING;?></u></a></td>
</tr>
<tr>
<td><a href="admin.php?path=statistics"><img src="images/stat.gif" border=0></a></td>
<td><a href="admin.php?path=statistics"><u><?=ADMIN_STATISTICS;?></u></a></td>
</tr>
<tr>
<td><a href="admin.php?path=customers"><img src="images/users.gif" border=0></a></td>
<td><a href="admin.php?path=customers"><u><?=ADMIN_CUSTOMERS;?></u></a></td>
</tr>
<tr>
<td><a href="admin.php?path=voting"><img src="images/vote.gif" border=0></a></td>
<td><a href="admin.php?path=voting"><u><?=ADMIN_VOTING;?></u></a></td>
</tr>
<tr>
<td><a href="admin.php?path=excel_import"><img src="images/excel.gif" border=0></a></td>
<td><a href="admin.php?path=excel_import"><u><?=ADMIN_IMPORT_FROM_EXCEL;?></u></a></td>
</tr>
<tr>
<td><a href="admin.php?path=1c_import"><img src="images/1c.gif" border=0></a></td>
<td><a href="admin.php?path=1c_import"><u>Èìïîðò èç 1Ñ</u></a></td>
</tr>
<tr>
<td><a href="admin.php?path=synchronize"><img src="images/sync.gif" border=0></a></td>
<td><a href="admin.php?path=synchronize"><u><?=ADMIN_SYNCHRONIZE_TOOLS;?></u></a></td>
</tr>
<tr>
<td align=center><img src="images/paper.gif" border=0></td>
<td>
<b><?=ADMIN_AUX_INFO; ?></b><br>
&nbsp;&nbsp;&nbsp;<a class=standard href="admin.php?path=news"><u><?=ADMIN_NEWS;?></u></a><br>
&nbsp;&nbsp;&nbsp;<a class=standard href="admin.php?path=aux_page&page=aux1"><u><?=ADMIN_ABOUT_PAGE;?></u></a><br>
&nbsp;&nbsp;&nbsp;<a class=standard href="admin.php?path=aux_page&page=aux2"><u><?=ADMIN_SHIPPING_PAGE;?></u></a><br>
</td>
</tr>
</table>

<?



	}
	else //include department
	{

		if (file_exists("includes/admin/$path.php"))
			include("includes/admin/$path.php");
		else
			die(ERROR_CANT_FIND_REQUIRED_PAGE);
	}



?>

<p>
<table width=300 height=40 border=0>
<tr><td align=center>
<a href="index.php"><?=ADMIN_BACK_TO_SHOP;?></a>
</td></tr>
</table>
</p>

</center></body>

</html>