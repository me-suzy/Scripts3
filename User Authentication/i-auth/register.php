<?
if (strlen($_POST['username'])<1 || strlen($_POST['password'])<1 || strlen($_POST['passworda'])<1 || strlen($_POST['email'])<1)
{
	echo '<div align="center">Please enter your registration details.</div>';
}
elseif ($_POST['password'] != $_POST['passworda'])
{
	echo '<div align="center">Your passwords do not match.</div>';
}
elseif (strlen($_POST['username'])<4 || strlen($_POST['username'])>16 || strlen($_POST['password'])<4 || strlen($_POST['password'])>16 || strlen($_POST['passworda'])<4 || strlen($_POST['passworda'])>16)
{
	echo '<div align="center">Username and password must be between 4 and 16 characters.</div>';
}
elseif (strlen($_POST['email'])<5 || strlen($_POST['passworda'])>50)
{
	echo '<div align="center">Email addresses must be between 5 and 50 characters.</div>';
}
else
{
	$checkuser = "SELECT username FROM i_users WHERE username='$_POST[username]'";
	$resultcheck = mysql_query($checkuser, $db_conn) or die ('query failed');
	if (mysql_num_rows($resultcheck) >0)
	{
		echo '<div align="center">The username you entered already exists, please select another username.</div>';
	}
	else {
	$query = "INSERT INTO i_users (id,username,passwd,email) VALUES ('','$_POST[username]','$_POST[password]','$_POST[email]')"; 
   $result = mysql_query($query, $db_conn) or die('query failed'.mysql_error()); 
   if (isset($result))
	{
	   echo '<div align="center">Your information has been entered into the database.</div>';
	}
	}
}
?>
<div align="center">
<form action="index.php?page=reg" method="post">
User Name:<br />
<input type="text" size="16" name="username" style="font-size:10px;border:solid 1px";><br />
Password:<br />
<input type="text" size="16" name="password" style="font-size:10px;border:solid 1px";><br />
Password again:<br />
<input type="text" name="passworda" size="30" style="font-size:10px;border:solid 1px;"><br />
Email:<br />
<input type="text" name="email" size="50" style="font-size:10px;border:solid 1px;"><br />
<input type=image src="buttons/reg.gif" name=reg value="Register" style="font-size:10px;">
</form>
</div>
