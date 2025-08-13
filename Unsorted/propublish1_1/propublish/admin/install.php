<html>
<head>
</head>

<body>
<h2>INSTALL</h2>

<a href="install.php">0</a>&nbsp;|&nbsp;<a href="install.php?level=1">1</a>&nbsp;|&nbsp;<a href="install.php?level=2">2</a>&nbsp;|&nbsp;<a href="install.php?level=3">3</a>&nbsp;<br>
<? 
if (!$level)
{
	print("<p>Step 1&nbsp; &gt;&gt; Step 2&nbsp; &gt;&gt; Step 3</p>");
}
if ($level == 1)
{
	print("<p><b>Step 1</b>&nbsp; &gt;&gt; Step 2&nbsp; &gt;&gt; Step 3</p>");
}
if ($level == 2)
{
	print("<p>Step 1&nbsp; &gt;&gt; <b>Step 2</b>&nbsp; &gt;&gt; Step 3</p>");
}


?>

</p>
<table width="70%">
<tr>
<td>
<?
if ($level == "") 
{
 ?>
<p>Welcome to install. This install program will take you through all you have to do to get the program up and running. </p>
<p>
<textarea rows="5" name="S1" cols="70">All use of this program is under strict licence. If you do not agree, do not use this program.  I (Are Haugsdal email: are@a4.no), or my company (Haugsdal Webtjenester), or any other person related in/to this program accepts NO liability or claims whatsoever. Some few examples of these liabilities/claims: Damages in any way to people, property, machines, working hours, repair work or any other costs releated to this program in any matter. 
ALL USE OF THIS PROGRAM IS ON YOUR OWN RISK AND COSTS. This program is not programmed to be 100% secure, and therefore can not be used in life critical environments, or heavy critical e-commerce (mission-critical) envirionments that will loose from hundreds of dollar to millions of dollar of small coding errors. (Anyway, Haugsdal Webtjenester/Are Haugsdal accept no liabilities).
In order to use this program at your website, you will neeed to pay yhe license. 
Any claim, or disputes shall be governed by the laws and courts in Norway. 
You may not reproduce and/or (re)sell the hole program, or any part of our code without the written aknowledge from the author of this program, Are Haugsdal. All code is copyright 2001 Haugsdal Webtjenester.</textarea><br>
<?
print("<a href='install.php?level=1'>I agree to the conditions, START INSTALL</a>");
}
if ($level == "1")
{
	 ?>
	 	 </p>
 	 <form method="POST" action="install.php">
	 <input type="hidden" name="level" value="1">
	  <b>DATABASE INFO</b><br>In order to use this program, you must have access to a MySql database, and you must have created a database where the below user will have access. This info must be correct. The DB.PHP file will be installed in the current dir</b>.<p>
<table>
<tr><td>Hostname (often localhost) :</td><td><input type="text" name="hostname" size="20"></td></tr>

<tr><td>DB Username : </td><td><input type="text" name="db_username" size="20"></td></tr>
<tr><td>DB Password : </td><td><input type="text" name="db_password" size="20"></td></tr>
<tr><td>Databasename: </td><td><input type="text" name="db_name" size="20"></td></tr></table>
<input type="submit" value="Create databasefile" name="submit">
 	 </form>

	 
	 <?
	 
	 
	 
	 
	 if ($submit)
	 {
	 		
			if (file_exists("db.php"))
			{
			 	 	 unlink ("db.php");
			}
			
								
		
	 
		 		 $fd = fopen( "db.php", "w+" );			  		  
$str_gen = "<? mysql_connect (\"$hostname\",\"$db_username\",\"$db_password\");\nmysql_select_db (\"$db_name\");\n
\$datab='$db_name';\n
\$dbusr='$db_username';\n
\$dbpass='$db_password';\n
\$dbhost='$hostname';\n
?>";				 
				 $len_gen = strlen( $str_gen );
				 fwrite( $fd, $str_gen, $len_gen );
			
		 		 fclose( $fd );
							 
				 print("Writed db.php.");
				 print("<a href='install.php?level=2'>Go on to level 2</a>");
				 

		}

}

// -------------------- Step 2 ---------
if ($level == "2")
{
 	 
 	 
 	 ?>
	 
	 <form method="POST" action="install.php">
	 <input type="hidden" name="level" value="2">	 	 
	 <b>TABLE CREATION</b><p>
	 
	<? require("db.php"); ?>
	Please note, in order for this to work, the following <b>is required</b>:<br>
	<ul type="square">
	<li>A linux webserver with correct setup of mysql monitor mode.<p></li>
	<li>The following tables does NOT exist: <br>article_news<br>author_news<br>cat_news<br>level_news</li>
	</ul>
	<?
	if(getenv("OS")=="Windows_NT")
 	{
 		print "<p>System reports that you are installing on a WinNT machine. That is ok, but the ";
 		print "sql file must be loaded manually, as described a litte bit longer down.<p>";
 	}
 	
 	?>

	
	<b>If you get an error</b> after pushing Create tables, following step is mandatory:<br>
	In Linux, type <u>mysql -uusername -ppassword databasename < /full/path/to/admin/mysql.sql</u>. If this fails, your
	setup of MySql is a bit wrong. Instead, look up where you installed mysql, and in the bin dir, type 
	a dot and a slash before the same command as above (./mysql -uusernmae etc).
	<p>
	In Windows, this is the same command, but execute from mysql dir.<p>
	For any plattform, another way is to load the sql file through PHP MyAdmin.
	<p>
	Note: existing tables will NOT be deleted if they exist already! If you run this installer with tables already existing,
	this will fail.
	<p>
	<input type="submit" value="Create tables" name="submit"></form>
<?
	 
	 if ($submit AND $level==2)
	 {
		require("db.php");
		$string = "mysql -u$dbusr -p$dbpass $datab < mysql.sql";
		$res = exec("$string");
		print "<p><b>DONE</b>: If you didn´t get any error msg or a lot of text, you can continue...";
		 print("<a href='install.php?level=3'>Go on to level 3</a>");

	 
 	}
// End of level 2...
}

	 
// -------------------- Step 3	Finished	------------------	 
if ($level==3)
{
	?>
	<b>FINISHED!</b>
	<p>Program is now installed. 
	<p>
	<font color=red>Important information:</font><br>
	You can now login by accessing the <b><a href="../login.php">login.php</a></b> file from the public news dir (above /admin dir). 
	We have already set up an default root account with the following login<p>
	Username: <b>pronews@n.com</b><br> 
	Password: <b>temp</b>
	<p>
	CHANGE THAT USERNAME AND PASSWORD IMIDIATELY, SINCE IT IS A DEFAULT USERNAME AND PASS THAT IS PUBLIC !!
	<p>
	Afterwards, go into the controlpanel (settings) and change the webmaster emailadress and url to this website.
	</p>
	<?
}	 
	


?>
</td>
</tr>
</table>
</body>
</html>
