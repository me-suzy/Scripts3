<?

/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//ADMIN :: payment gateway config

	include("cfg/connect.inc.php");
	include("includes/database/".DBMS.".php");
	include("functions.php");
	

	//connect 2 database
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());

	//authorized access check
	session_start();
	include("checklogin.php");
	if (!isset($log) || strcmp($log,ADMIN_LOGIN)) //unauthorized
	{
		die (ERROR_FORBIDDEN);
	}

	if (!isset($gate)) die(ERROR_CANT_FIND_REQUIRED_PAGE);

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


	//save, etc.

	if (isset($save_gateway))
	{

		$f = fopen("cfg/gateways/$gate.inc.php", "w");
		switch($gate)
		{
			case "2checkout":
				fputs($f,"<?\n");
				fputs($f,"\t\$twocheckout_sid = \"$sid\";\n");
				fputs($f,"?>");
			break;
			case "authorizenet":
				fputs($f,"<?\n");
				fputs($f,"\t\$authorizenet_login = \"$auth_login\";\n");
				fputs($f,"\t\$authorizenet_tran_key = \"$auth_tran_key\";\n");
				fputs($f,"\t\$authorizenet_method = $auth_method;\n");
				fputs($f,"?>");
			break;
		}
		fclose($f);

		echo "

<html><body><script> window.close(); </script></body></html>

		";

	}

?>

<html>

<head>
<link rel=STYLESHEET href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?=DEFAULT_CHARSET;?>">
<title>Payment gateways</title>
</head>

<body bgcolor=white>

<center>

<img src="images/<?=$gate?>.gif">

<form action="gateway.php" method=post>

<table cellpadding=5>
<?

	include("cfg/gateways/$gate.inc.php");

	switch ($gate) //select gateway to configure
	{
		case "2checkout":

			echo "
			<tr>
				<td>Your Seller ID:</td>
				<td>&nbsp;</td>
				<td><input type=text name=sid value=\"$twocheckout_sid\"></td>
			</tr>
			";

		break;

		case "authorizenet":

			if ($authorizenet_method)
			{
				$m1 = " checked";
				$m0 = "";
			}
			else
			{
				$m0 = " checked";
				$m1 = "";
			}

			echo "
			<tr>
				<td>AuthorizeNet login:</td>
				<td>&nbsp;</td>
				<td><input type=text name=auth_login value=\"$authorizenet_login\"></td>
			</tr>
			<tr>
				<td>AuthorizeNet password:</td>
				<td>&nbsp;</td>
				<td><input type=text name=auth_tran_key value=\"$authorizenet_tran_key\"></td>
			</tr>
			<tr>
				<td>Mode:</td>
				<td>&nbsp;</td>
				<td>
					<input type=radio name=auth_method value=\"0\"$m0> Test mode<br>
					<input type=radio name=auth_method value=\"1\"$m1> Real transaction
				</td>
			</tr>
			";

		break;
	}

?>
</table>

<input type=hidden name=gate value="<?=$gate;?>">

<input type=submit name=save_gateway value="<?=SAVE_BUTTON;?>">

</form>

</center>

</body>

</html>