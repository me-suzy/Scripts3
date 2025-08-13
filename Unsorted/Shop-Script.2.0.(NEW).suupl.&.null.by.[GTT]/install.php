<?

/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//installation routine

	session_start();

	if (isset($install))
	{
		$f = fopen("cfg/connect.inc.php","w");

		$s = "<?
	//database connection settings

	define('DBMS', '$dbms'); // database system

	define('DB_HOST', '$db_host'); // database host
	define('DB_USER', '$db_user'); // username
	define('DB_PASS', '$db_pass'); // password
	define('DB_NAME', '$db_name'); // database name
	define('ADMIN_LOGIN', '$admin_login'); //administrator's login

	//database tables
	include(\"tables.inc.php\");

?>";

		fputs($f,$s);
		fclose($f);

		//try to connect to the database using new settings and register administrator
		include("cfg/connect.inc.php");

		//choose database file to include
		include("includes/database/".DBMS.".php");

		$sel = NULL;
		$conn = db_connect(DB_HOST,DB_USER,DB_PASS);
		if ($conn)
		{
			if (!(db_select_db(DB_NAME))) //database connect failed
			{
				$error =  "<p>Couldn't select database ".DB_NAME;
			}

		}
		else
			$error = "<p>Couldn't connect do the database";


		if (!isset($error)) //successful!
		{
			//create tables
			include("includes/database/install/".DBMS.".php");

			if (DBMS == 'IB') db_query("COMMIT") or die (db_error());

			//register admin
			db_query("insert into ".CUSTOMERS_TABLE." (Login, cust_password) values ('$admin_login','$admin_pass');") or die (db_error());


			$log = $admin_login;
			$pass = $admin_pass;
			session_register("log");
			session_register("pass");

			//make a backup copy of install.php
			copy("install.php","cfg/install.php");
			unlink("install.php");

		}
	}

?>
<html>

<head>

<link rel=STYLESHEET href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Shop-Script 2.0 installation routine</title>

<script>

	function validate()
	{
		if (document.form1.db_name.value.length<1)
		{
			alert("Please input database name");
			return false;
		}
		if (document.form1.admin_login.value.length<1)
		{
			alert("Please input administrator`s login");
			return false;
		}
		if (document.form1.admin_pass.value.length<1)
		{
			alert("Please input administrator`s password");
			return false;
		}

		return true;
	}


</script>

</head>

<body>
<center>

<?
	if (isset($install) && !isset($error))
	{
		echo "<h1>Installation completed successfully!</h1>";
		echo "<p>* current installation routine was moved to <b>cfg/</b> folder";
		echo "<p><a href=\"index.php\">Proceed to my new shopping cart web shop ...</a>";
		exit;
	}
?>

<h1>Shop-Script 2.0 installation routine</h1>

<?
	if (isset($error)) echo "<p><font color=red><b>$error</b></font>";
?>

<form name=form1 action="install.php" method=post onSubmit="return validate(this);">
<p>
<u><b>Database managment system you are going to use</b></u><br>
<table><tr><td>
	<input type=radio name=dbms value="MYSQL" checked> MySQL<br>
	<input type=radio name=dbms value="MSSQL"> MS SQL Server<br>
	<input type=radio name=dbms value="IB"> Interbase
</td></tr></table>

<p>
<u><b>Database connection settings</b></u><br>
<table cellpadding=5>
	<tr>
	 <td align=right>Host:</td>
	 <td><input type=text name=db_host value="localhost"></td>
	</tr>

	<tr>
	 <td align=right>Username:</td>
	 <td><input type=text name=db_user<? echo isset($db_user) ? " value=\"$db_user\"":"";?>></td>
	</tr>

	<tr>
	 <td align=right>Password:</td>
	 <td><input type=text name=db_pass<? echo isset($db_pass) ? " value=\"$db_pass\"":"";?>></td>
	</tr>

	<tr>
	 <td align=right>Database name:</td>
	 <td><input type=text name=db_name<? echo isset($db_name) ? " value=\"$db_name\"":"";?>></td>
	</tr>



</table>

<p>
<u><b>Administrators login and password</b></u><br>
later you can change login and password
<table cellpadding=5>
	<tr>
	 <td align=right>Login:</td>
	 <td><input type=text name=admin_login value="<? echo isset($admin_login) ? $admin_login:"MANAGER";?>"></td>
	</tr>
	<tr>
	 <td align=right>Password:</td>
	 <td><input type=text name=admin_pass value="<? echo isset($admin_pass) ? $admin_pass:"123";?>"></td>
	</tr>
</table>



<p>
<input type=submit name=install value="Install">

</form>

</center>
</body>

</html>