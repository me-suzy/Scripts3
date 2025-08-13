<?

/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//ADMIN :: customers information

	include("cfg/connect.inc.php");
	include("includes/database/".DBMS.".php");
	

	session_start();

	//connect 2 database
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());

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

	if (!isset($uLogin)) $uLogin="";
?>

<html>

<head>
<link rel=STYLESHEET href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?=DEFAULT_CHARSET;?>">
<title><?=ADMIN_CUSTOMER_TITLE;?></title>
<script>
function confirmDelete() {
	temp = window.confirm('<?=QUESTION_DELETE_CONFIRMATION;?>');
	if (temp) { //delete
		window.location='user.php?uLogin=<?=$uLogin;?>&del=1';
	}
}
</script>
</head>

<body bgcolor=#DDDDDD>
<center>

<?
	if (isset($del) && strcmp($uLogin,ADMIN_LOGIN)) //delete user
	{
		//at first send a notification to customer
		$q = db_query("SELECT Email FROM ".CUSTOMERS_TABLE." WHERE Login='$uLogin';") or die (db_error());
		$row = db_fetch_row($q);
		$q = db_query("SELECT Email FROM ".CUSTOMERS_TABLE." WHERE Login='".ADMIN_LOGIN."';") or die (db_error());
		$r = db_fetch_row($q);

		include("cfg/settings.inc.php");
		mail($row[0],EMAIL_DELETE_CUSTOMER_SUBJECT,EMAIL_HELLO."\n\n".EMAIL_ACCOUNT_CLOSED."\n\n".EMAIL_SINCERELY.", $shopname.\n$shopurl", "From: \"$shopname\"<$r[0]>;\n".EMAIL_MESSAGE_PARAMETERS."\nReturn-path: <$r[0]>");

		$q = db_query("DELETE FROM ".CUSTOMERS_TABLE." WHERE Login='$uLogin';") or die (db_error());

		//close window
		echo "<script>\n";
		echo "window.opener.location.reload();\n";
		echo "window.close();";
		echo "</script>\n</body>\n</html>";
		exit;
	}

	$q = db_query("SELECT Login, cust_password, Email, Country, City, Address, Phone, first_name, default_currency, subscribed4news, last_name, ZIP, State FROM ".CUSTOMERS_TABLE." WHERE Login='$uLogin';") or die (db_error());
	$row = db_fetch_row($q);
	if (!$row) //no such customer found
	{
		echo "<font color=red>".ERROR_CANT_FIND_REQUIRED_PAGE."</font>\n<br><br>\n";
		echo "<a href=\"javascript:window.close();\">".CLOSE_BUTTON."</a></center></body>\n</html>";
		exit;
	};

?>

<table border=0 cellspacing=3 width=100%>

<tr>
<td align=right width=40%><?=CUSTOMER_LOGIN;?></td>
<td><b><?=str_replace("<","&lt;",$row[0]); ?></b></td>
</tr>

<tr>
<td align=right><?=CUSTOMER_PASSWORD;?></td>
<td><b><?=str_replace("<","&lt;",$row[1]); ?></b></td>
</tr>

<tr>
<td align=right><?=CUSTOMER_EMAIL;?></td>
<td><a href="mailto:<?=str_replace("<","&lt;",$row[2]); ?>"><?=str_replace("<","&lt;",$row[2]); ?></a></td>
</tr>

<tr>
<td align=right><?=CUSTOMER_FIRST_NAME;?></td>
<td><b><?=str_replace("<","&lt;",$row[7]); ?></b></td>
</tr>

<tr>
<td align=right><?=CUSTOMER_LAST_NAME;?></td>
<td><b><?=str_replace("<","&lt;",$row[10]); ?></b></td>
</tr>

<tr>
<td align=right><?=CUSTOMER_COUNTRY;?></td>
<td><b><?=str_replace("<","&lt;",$row[3]); ?></b></td>
</tr>

<tr>
<td align=right><?=CUSTOMER_ZIP;?></td>
<td><b><?=str_replace("<","&lt;",$row[11]); ?></b></td>
</tr>

<tr>
<td align=right><?=CUSTOMER_STATE;?></td>
<td><b><?=str_replace("<","&lt;",$row[12]); ?></b></td>
</tr>

<tr>
<td align=right><?=CUSTOMER_CITY;?></td>
<td><b><?=str_replace("<","&lt;",$row[4]); ?></b></td>
</tr>

<tr>
<td align=right><?=CUSTOMER_ADDRESS;?></td>
<td><b><?=str_replace("<","&lt;",$row[5]); ?></b></td>
</tr>

<tr>
<td align=right><?=CUSTOMER_PHONE_NUMBER;?></td>
<td><b><?=str_replace("<","&lt;",$row[6]); ?></b></td>
</tr>

</table>

<form>
<input type="button" value="<?=CLOSE_BUTTON; ?>" onClick="window.close();">
<?
	if (strcmp($row[0],ADMIN_LOGIN)) echo "<input type=\"button\" value=\"".DELETE_BUTTON."\" onClick=\"confirmDelete();\">\n";
?>
</form>

</center>
</body>

</html>