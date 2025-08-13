<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	// user registration and details update

	if (isset($save)) //save user to the database
	{

		//check for proper input

		if (!isset($login) || !trim($login)) $error = ERROR_INPUT_LOGIN;
		else
		if (!(((ord($login)>=ord("a")) && (ord($login)<=ord("z"))) ||
			((ord($login)>=ord("A")) && (ord($login)<=ord("Z")))))
				$error = ERROR_LOGIN_SHOULD_START_WITH_LATIN_SYMBOL;
		else
		if (!isset($pw) || !trim($pw) || !isset($_pw) || !trim($_pw) || $pw != $_pw) {
			$error = ERROR_WRONG_PASSWORD_CONFIRMATION;
			$perror = 1;
		}
		else
		if (!isset($first_name) || trim($first_name)=="") $error = ERROR_INPUT_NAME;
		else
		if (!isset($last_name) || trim($last_name)=="") $error = ERROR_INPUT_NAME;
		else
		if (!isset($email) || trim($email)=="") $error = ERROR_INPUT_EMAIL;
		else
		if (!isset($zip) || trim($zip)=="") $error = ERROR_INPUT_ZIP;
		else
		if (!isset($state) || trim($state)=="") $error = ERROR_INPUT_STATE;
		else
		if (!isset($country) || trim($country)=="") $error = ERROR_INPUT_COUNTRY;
		else
		if (!isset($city) || trim($city)=="") $error = ERROR_INPUT_CITY;
		else
		if (!isset($address) || trim($address)=="") $error = ERROR_INPUT_ADDRESS;
		else
		if (!isset($phone) || trim($phone)=="") $error = ERROR_INPUT_PHONE;
		else
		if (!isset($otch) || trim($otch)=="") $error = ERROR_INPUT_OTCH;

		$q = db_query("SELECT Login FROM ".CUSTOMERS_TABLE." WHERE Login='$login'") or die (db_error());
		$r = db_fetch_row($q);
		if (($r && !isset($update_details)) ||
			(isset($update_details) && $r && !strcmp($login,$r[0]) && strcmp($old_login,$r[0])))

				$error = ERROR_USER_ALREADY_EXISTS;


		if (!isset($error)) //everything's ok
		{

			if (isset($old_login)) $old_login = str_replace("\\","/",stripslashes(str_replace("'","`",$old_login)));
			$login = str_replace("\\","/",stripslashes(str_replace("'","`",$login)));
			$pw = str_replace("\\","/",stripslashes(str_replace("'","`",$pw)));
			$first_name = str_replace("\\","/",stripslashes(str_replace("'","`",$first_name)));
			$last_name = str_replace("\\","/",stripslashes(str_replace("'","`",$last_name)));
			$email = str_replace("\\","/",stripslashes(str_replace("'","`",$email)));
			$country = str_replace("\\","/",stripslashes(str_replace("'","`",$country)));
			$city = str_replace("\\","/",stripslashes(str_replace("'","`",$city)));
			$address = str_replace("\\","/",stripslashes(str_replace("'","`",$address)));
			$phone = str_replace("\\","/",stripslashes(str_replace("'","`",$phone)));
			$zip = str_replace("\\","/",stripslashes(str_replace("'","`",$zip)));
			$state = str_replace("\\","/",stripslashes(str_replace("'","`",$state)));
			$otch = str_replace("\\","/",stripslashes(str_replace("'","`",$otch)));


			if (!isset($news)) $news = 0;
			if (!strcmp($news,"on")) $news = 1;
			else $news = 0;

			$s = CUSTOMER_LOGIN." $login\n".CUSTOMER_PASSWORD." $pw\n\n".CUSTOMER_EMAIL." $email\n".CUSTOMER_FIRST_NAME." $first_name\n".CUSTOMER_LAST_NAME." $last_name\n".CUSTOMER_MIDDLE_NAME." $otch\n";
			$s.= CUSTOMER_COUNTRY." $country\n".CUSTOMER_CITY." $city\n".CUSTOMER_ADDRESS." $address\n".CUSTOMER_PHONE_NUMBER." $phone\n";

			if (isset($update_details)) //update details
			{
				db_query("UPDATE ".CUSTOMERS_TABLE." SET Login='$login', cust_password='$pw', first_name='$first_name', Email='$email', Country='$country', City='$city', Address='$address', Phone='$phone', subscribed4news=$news, last_name='$last_name', ZIP='$zip', State='$state', otch='$otch' WHERE Login='$old_login'") or die (db_error());
				$s = EMAIL_HELLO."\n\n".EMAIL_YOUVE_MODIFIED_YOUR_DATA_AT." $shopname.\n\n$s\n\n".EMAIL_SINCERELY.", $shopname\n$shopurl";
			}
			else
			{
				db_query("INSERT INTO ".CUSTOMERS_TABLE." (Login, cust_password, Email, Country, City, Address, Phone, first_name, default_currency, subscribed4news, ZIP, State, last_name, otch) VALUES ('$login','$pw','$email','$country', '$city', '$address', '$phone','$first_name',0,$news,'$zip','$state','$last_name','$otch')") or die (db_error());
				$s = EMAIL_HELLO."\n\n".EMAIL_YOUVE_BEEN_REGISTERED_AT." $shopname.\n".EMAIL_YOUR_REGTRATION_INFO."\n\n$s\n\n".EMAIL_SINCERELY.", $shopname\n$shopurl";
			}

			$q = db_query("SELECT Email FROM ".CUSTOMERS_TABLE." WHERE Login='".ADMIN_LOGIN."'") or die (db_error());
			$em = db_fetch_row($q);
			$em = $em ? $em[0] : "";

			mail($email,EMAIL_REGISTRATION,$s,"From: \"$shopname\"<$em>;\n".EMAIL_MESSAGE_PARAMETERS."\nReturn-path: <$em>");

			$log = $login;
			$pass = $pw;

			session_register("log");
			session_register("pass");

			moveCartFromSession2DB();
?>

<html>
<body>
<script>
window.location = 'index.php?<?
	if (isset($update_details)) echo "update=1";

	if (isset($order)) echo "&check_order=1";
	else echo "&r_successful=1";
?>';
</script>
</body>
</html>

<?
				exit;


		}
	}



	if (isset($update_details) && isset($log)) //update existing user
	{

		$q = db_query("SELECT Login, cust_password, Email, Country, City, Address, Phone, first_name, subscribed4news, ZIP, State, last_name, otch FROM ".CUSTOMERS_TABLE." WHERE Login='$log'") or die (db_error());
		if (!($row = db_fetch_row($q))) exit;
		if (!isset($login)) $login = addslashes($row[0]);
		if (!isset($perror)) $pww = addslashes($row[1]);
		else $pww = "";
		if (!isset($first_name)) $first_name = addslashes($row[7]);
		if (!isset($last_name)) $last_name = addslashes($row[11]);
		if (!isset($email)) $email = addslashes($row[2]);
		if (!isset($country)) $country = addslashes($row[3]);
		if (!isset($city)) $city = addslashes($row[4]);
		if (!isset($address)) $address = addslashes($row[5]);
		if (!isset($phone)) $phone = addslashes($row[6]);
		if (!isset($news)) $news = addslashes($row[8]);
		if (!isset($zip)) $zip = addslashes($row[9]);
		if (!isset($state)) $state = addslashes($row[10]);
		if (!isset($otch)) $otch = addslashes($row[12]);
	}

	if (!isset($login)) $login = "";
	if (!isset($pww)) $pww = "";
	if (!isset($first_name)) $first_name = "";
	if (!isset($last_name)) $last_name = "";
	if (!isset($zip)) $zip = "";
	if (!isset($state)) $state = "";
	if (!isset($email)) $email = "";
	if (!isset($country)) $country = "";
	if (!isset($city)) $city = "";
	if (!isset($address)) $address = "";
	if (!isset($phone)) $phone = "";
	if (!isset($otch)) $otch = "";
	if (!isset($news)) $news = 1;
	if ($news!=1)
		if (!strcmp($news,"on")) $news = 1;
		else $news = 0;

	$ns = $news ? " checked" : "";

	$head = isset($update_details) ? STRING_UPDATE_DETAILS : STRING_REGISTRATION_FORM;

	$out .= "

<center>

<table width=70% border=0>

	";

	if (isset($order))
		$out .= "
<tr>
<td colspan=2 align=center>
<br>
".STRING_ORDER_CONTINUE_TIP."
</td>
</tr>
		";

	$out .= "

<tr><td align=center>

<form action=\"index.php\" method=post>

<table border=0 width=70%>
<tr>
<td colspan=3 align=center><b><u>$head</u></b><br><br>
".STRING_REQUIRED."<br><br>

	";

	if (isset($error)) $out .= "<font color=red><b>$error</b></font><br><br>";

	$out .= "

</td>
</tr>

<tr>
<td colspan=3 align=right>
<table bgcolor=#$middle_color width=80% border=0><tr><td><font color=black class=small>".STRING_AUTHORIZATION_FIELDS."</font></td></tr></table>
</td>
</tr>

<tr>
<td colspan=2 width=50% align=right><font color=red>*</font> ".CUSTOMER_LOGIN."</td>
	";

	if (isset($error))
		$k = isset($old_login) ? $old_login : $login;
	else $k = $login;

	if ($k) $out .= "<input type=\"hidden\" name=\"old_login\" value=\"".stripslashes(str_replace("\"","&quot;",$k))."\">\n";

	$out .= "

<td width=50%><input type=\"text\" name=\"login\" value=\"".stripslashes(str_replace("\"","&quot;",$login))."\"></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_PASSWORD."</td>
<td><input type=\"password\" name=\"pw\" value=\"".stripslashes(str_replace("\"","&quot;",$pww))."\"></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_CONFIRM_PASSWORD."</td>
<td><input type=\"password\" name=\"_pw\" value=\"".stripslashes(str_replace("\"","&quot;",$pww))."\"></td>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>

<tr>
<td colspan=3 align=right>
<table bgcolor=#$middle_color width=80%><tr><td><font color=black class=small>".STRING_CONTACT_INFORMATION."</font></td></tr></table>
</td>
</tr>

<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_FIRST_NAME."</td>
<td><input type=\"text\" name=\"first_name\" value=\"".stripslashes(str_replace("\"","&quot;",$first_name))."\"></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_LAST_NAME."</td>
<td><input type=\"text\" name=\"last_name\" value=\"".stripslashes(str_replace("\"","&quot;",$last_name))."\"></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_MIDDLE_NAME."</td>
<td><input type=\"text\" name=\"otch\" value=\"".stripslashes(str_replace("\"","&quot;",$otch))."\"></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_EMAIL."</td>
<td><input type=\"text\" name=\"email\" value=\"".stripslashes(str_replace("\"","&quot;",$email))."\"></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_COUNTRY."</td>
<td><input type=\"text\" name=\"country\" value=\"".stripslashes(str_replace("\"","&quot;",$country))."\"></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_STATE."</td>
<td><input type=\"text\" name=\"state\" value=\"".stripslashes(str_replace("\"","&quot;",$state))."\"></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_ZIP."</td>
<td><input type=\"text\" name=\"zip\" value=\"".stripslashes(str_replace("\"","&quot;",$zip))."\"></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_CITY."</td>
<td><input type=\"text\" name=\"city\" value=\"".stripslashes(str_replace("\"","&quot;",$city))."\"></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_ADDRESS."</td>
<td><textarea name=\"address\" rows=4>".stripslashes(str_replace("\"","&quot;",$address))."</textarea></td>
</tr>
<tr>
<td colspan=2 align=right><font color=red>*</font> ".CUSTOMER_PHONE_NUMBER."</td>
<td><input type=\"text\" name=\"phone\" value=\"".stripslashes(str_replace("\"","&quot;",$phone))."\"></td>
</tr>

<tr>
<td colspan=3 align=center>
<input type=checkbox name=news $ns> ".CUSTOMER_SUBSCRIBE_FOR_NEWS."
</td>
</tr>

</table>

<p>
<input type=\"submit\" value=\"".OK_BUTTON."\">

	";

	if (isset($update_details)) $out .= "<input type=hidden name=\"update_details\" value=1>\n";
	else
		if (isset($register)) $out .= "<input type=hidden name=\"register\" value=1>\n";

	if (isset($order)) $out .= "<input type=hidden name=\"order\" value=1>\n";

	$out .= "

<input type=hidden name=save value=1>
<input type=reset value=\"".RESET_BUTTON."\">
</p>

</form>

	";

	if (isset($update_details) && strcmp($log,ADMIN_LOGIN)) $out .= "<p>[ <a class=bold href=\"javascript:confirmDelete();\">".TERMINATE_ACCOUNT_LINK."</a> ]</p>";

	$out .= "</td>";


	if (isset($order)) {

		$out .= "

<td valign=top><br><br><br><br><br><br><br>
<table bgcolor=#$middle_color cellspacing=1>
<form action=\"index.php\" method=post>
<tr>
<td colspan=2 align=center>
<table bgcolor=#$middle_color width=100%><tr><td align=center><font color=black class=small>".STRING_AUTHORIZATION."</font></td></tr></table>
</td>
</tr>
<tr bgcolor=white><td align=right>".CUSTOMER_LOGIN."<br>".CUSTOMER_PASSWORD."<br></td>
<td><input type=\"text\" name=\"user_login\" size=13><br><input type=\"password\" name=\"user_pw\" size=13></td></tr>
<tr bgcolor=white><td colspan=2 align=center><input type=submit value=\"".OK_BUTTON."\"></td></tr>
<input type=hidden name=check_order value=1>
<input type=hidden name=order value=1>
<input type=hidden name=enter value=1>
</form>
</table>
</td>

		";

	}


	$out .= "

</tr>
</table>

</center>


	";

?>