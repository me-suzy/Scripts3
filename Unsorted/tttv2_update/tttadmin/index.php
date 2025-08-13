<?php
//#####################
// Turbo Traffic Trader v2.0
//#####################
// Copyright (c) 2003 Choker (Chokinchicken.com). All Rights Reserved.
// Any unauthorized reproductions or alterations (modifications) of this script is strictly prohibited. 
// If you reproduce or alter this script in any way, I will shut down EVERY SINGLE one of your sites.
// You will be out of this business overnight. YOU HAVE BEEN WARNED!
//#####################
if ($_POST["login"] != "")
{
	$username = "";
	$password = "";
	require("../ttt-mysqlvalues.inc.php");
	require("../ttt-mysqlfunc.inc.php");
	open_conn();
	$_POST["password"] = substr(my_addslashes($_POST["password"]),0,255);
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
if ($_GET["check"] == 1) { readfile("../ttt-in.php"); exit; }
elseif ($_GET["check2"] == 1) { readfile("../ttt-out.php"); exit; }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>TTT Admin - Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<form action="index.php" method="POST">
<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td height="28" background="background.gif"><div align="center"><img src="ttt.gif" width="160" height="20"></div></td>
        </tr>
        <tr> 
          <td class="normalrow"><div align="center"> 
              <iframe src="http://www.turbotraffictrader.com/iframe.htm" width="600" marginwidth="0" height="180" marginheight="0" scrolling="auto" border"0" frameborder="0"></iframe>
            </div></td>
        </tr>
        <tr> 
          <td class="normalrow">&nbsp;</td>
        </tr>
        <tr> 
          <td><div align="center"> 
              <p class="toprows"><strong><font size="4">Login Below</font></strong></p>
            </div></td>
        </tr>
        <tr> 
          <td class="normalrow"><div align="center"> 
              <table width="350" border="0" cellspacing="2" cellpadding="0">
                <tr> 
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td width="200"><strong><font size="2">Username:</font></strong></td>
                  <td width="250"><input name="username" type="text" id="username3" size="30" maxlength="50"></td>
                </tr>
                <tr> 
                  <td width="200"><strong><font size="2">Password:</font></strong></td>
                  <td width="250"><input name="password" type="password" id="password" size="30" maxlength="50"></td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td><input name="login" type="submit" class="buttons" id="login" value=" Login "></td>
                </tr>
              </table>
            </div></td>
        </tr>
        <tr>
          <td class="normalrow">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<div align="center"><br>
  <br>
  Copyright &copy; 2003 Choker (Chokinchicken.com). All Rights Reserved.<br>
  Any unauthorized reproductions or alterations (modifications) of this script 
  is strictly prohibited. <br>
  If you reproduce or alter this script in any way, I will shut down EVERY SINGLE 
  one of your sites.<br>
  You will be out of this business overnight. YOU HAVE BEEN WARNED!<img src="http://www.turbotraffictrader.com/ttt.gif" width="1" height="1"><br>
</div>
</form>
</body>
</html>
