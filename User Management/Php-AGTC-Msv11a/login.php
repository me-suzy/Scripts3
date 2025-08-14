<?php
// *************************************************************************************************
// Title: 		PHP AGTC-Membership system v1.1a
// Developed by: Andy Greenhalgh
// Email:		andy@agtc.co.uk
// Website:		agtc.co.uk
// Copyright:	2005(C)Andy Greenhalgh - (AGTC)
// Licence:		GPL, You may distribute this software under the terms of this General Public License
// *************************************************************************************************
//
session_start();
include("config.php");

$msg = "";

if (isset($_POST['Submit']))
{
	
	$username = $_POST['username'];
	$password = md5($_POST[password]);
	
	$result = mysql_query("Select * From login_table where user_name='$username'",$con);
	
	if(mysql_num_rows($result)>0)
	{
		$row = mysql_fetch_array($result, MYSQL_BOTH);
		if($password == $row["user_pass"])
		{
			
			$_SESSION['loginok'] = "ok";
			$_SESSION['username'] = "username";
			$_SESSION['password'] = "password";
			$_SESSION['level'] = $row["user_level"];
			
			
			header("Location: index.php");

		}
		else
		{
			$msg = "Password incorrect";
		}
	}
	else
	{
		$msg = "Username incorrect";
    }

}

?>

<html>
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<p align="center"><strong>PHP AGTC-Membership system v1.1a</strong></p>
<form name="form1" method="post" action="">
  <p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Please 
    enter your username and password to login</font></p>
  <p align="center"><?php echo "<font color='red'>$msg</font>" ?></p>
  <table class="table" width="35%" border="0" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000">
    <tr bgcolor="#000000"> 
      <td colspan="2"><div align="center"><font color="#FC9801" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>LOGIN</strong></font></div></td>
    </tr>
    <tr> 
      <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Username:&nbsp; </font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input name="username" type="text" id="username" width="200">
        </font></td>
    </tr>
    <tr> 
      <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password:&nbsp; </font></div></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input name="password" type="password" id="password" width="200">
        </font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="center">
        <input type="submit" name="Submit" value="Submit">
      </div></td>
    </tr>
  </table>
<p align="center" class="smallErrorText"><a href="forgot.php">Forgot Password ? </a></p>
</form>
<p align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
Powered by PHP AGTC-Membership system v1.1a</a></strong><br></font> Developed by <strong>Andy Greenhalgh </strong> <a href="mailto:andy@agtc.co.uk">andy@agtc.co.uk</a></p>
<p>&nbsp; </p>
</body>
</html>
