<?php
session_start();
if(!$_SESSION['username']){ 
echo "Please login.";
exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Ice-Admin</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
a:link {
	color: #FF0000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FF0000;
}
a:hover {
	text-decoration: underline;
	color: #FF6600;
}
a:active {
	text-decoration: none;
	color: #FF6600;
}
a {
	font-size: 10px;
}
-->
</style></head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Protected Area</title>
</head>

<body>
<table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr>
    <td><table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><div align="center"><img src="logo.jpg" height="130"></div></td>
      </tr>
      <tr>
        <td height="19"><table width="100" border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#000000">
          <tr>
            <td width="180" height="21"> + <a href="#">Some Menu</a><br>
              + <a href="#">Some Menu</a><br>
              + <a href="#">Some Menu</a><br>
+ <a href="#">Some Menu</a><br>
+ <a href="#">Some Menu</a><br>
<br>
</td>
          </tr>
        </table>
          <table width="300" height="25" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="642" height="21" bgcolor="#FFFFFF">Welcome to your protected area. 
              </td>
            </tr>
          </table>
          </td>
      </tr>
      <tr>
        <td><div align="center">Powered by <a href="www.ice-host.net" target="_blank">Ice-Admin </a></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
