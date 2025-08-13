<?php

//######################################
// Turbo Traffic Drive xEdition v1.0.1 #
//######################################

if ($_POST["login"] != "")
{
	$username = "";
	$password = "";
	require("../mysqlvalues.inc.php");
	require("../mysqlfunc.inc.php");
	open_conn();
	$res = mysql_query("SELECT username, password, PASSWORD('$_POST[password]') AS password2 FROM ttt_settings") or print_error(mysql_error());
	$row = mysql_fetch_array($res);
	extract($row);
	close_conn();
	if ($username == $_POST["username"] AND $password == $password2)
	{
		setcookie("ttt_admin", time() . "|$username|$_POST[password]", time()+999999);
		header("Location: trades.php");
		exit;
	}
	else { print_error("Wrong username or password"); }
}
//if ($_GET["check"] == 1) { readfile("../in.php"); exit; }
//elseif ($_GET["check2"] == 1) { readfile("../out.php"); exit; }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Admin Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="../style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<form action="index.php" method="POST">
<table border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
        </tr>
        <tr> 
<td class="normalrow"><div align="center"> 
  </div></td>
        </tr>
        <tr> 
<td class="normalrow"><div align="center"> 
<table border="1" cellpadding="3" cellspacing="2" bordercolor="#000000" bgcolor="#E4E4E4">
      <tr> 
    <td class="normalrow" background="background.gif" width="100%" colspan="2" align="center"><b>Admin Login</b></td>
      </tr>
      <tr> 
        <td class="normalrow">Username:</td>
        <td><input name="username" type="text" id="username3" size="20" maxlength="50"></td>
      </tr>
      <tr> 
        <td class="normalrow">Password:</td>
        <td><input name="password" type="password" id="password" size="20" maxlength="50"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><input name="login" type="submit" class="buttons" id="login" value=" Login "></td>
      </tr>
              </table>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>

</form>
</body>
</html>
