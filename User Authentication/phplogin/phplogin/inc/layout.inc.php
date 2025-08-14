<?php

/* ===============================
== Basic PHP/MySQL Authentication 
== by x0x 
== Email: x0x@ukshells.co.uk
================================*/

function _begin_html()
{
  ?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
  <html>
  <head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
    <link rel="STYLESHEET" type="text/css" href="lib/style.css">
    <title>PHPLogin Version 0.0.1</title>
  </head>
  <body>
  <?
}

function _login_box()
{
  ?>
  <table width="100%" height="100%">
  <tr>
    <td align="center">
      <table width="350" align="center" border="0" cellpadding="2" bgcolor="#FFFFCE" class="ThinBorder">
      <form action="login.php" method="post">
      <tr>
        <td>Username: </td>
        <td><input type="text" name="user" size="40"></td>
      </tr>
      <tr>
        <td>Password: </td>
        <td><input type="password" name="pass" size="40"></td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          Remember Login: <input type="checkbox" name="rem" value="1" checked>
        </td>
      </tr>   
      <tr>
        <td colspan="2" align="center">
          <input type="submit" name="submit" value="Login">
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
        <p align="center">PHPLogin Version 0.0.1 by <a href="mailto:x0x@ukshells.co.uk">x0x</a></p>
        </td>
      </tr>
      </form>
      </table>
    </td>
    </tr>
  </table>
  </body>
  </html>
  <?
}

function _end_html()
{
  ?>
  <p align="center">PHPLogin Version 0.0.1 by <a href="mailto:x0x@ukshells.co.uk">x0x</a></p>
  </body>
  </html>
  <?
}

function _fake_nav()
{
  ?>
  <p align="center">
    <a href="index.php">Home</a> ::   
    <a href="view.php">Auth Test</a> :: 
    <a href="logout.php">Logout</a>
  </p>
  <?
}

function _display_user_data($user)
{
  ?>
  <p align="center">
  You are logged in as user: <b><? echo $user['user_name']; ?></b> 
  </p>
  <?
}

function _good_user()
{
  ?>
  <p align="center">
  You have been authenticated.
  </p>
  <?
}

function login_page()
{
  _begin_html();
  _login_box();
}

?>