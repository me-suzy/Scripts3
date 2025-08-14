<?
/*
# File: login.php
# Script Name: vSignup 2.5
# Author: Vincent Ryan Ong
# Email: support@beanbug.net
#
# Description:
# vSignup is a member registration script which utilizes vAuthenticate
# for its security handling. This handy script features email verification,
# sending confirmation email message, restricting email domains that are 
# allowed for membership, and much more.
#
# This script is a freeware but if you want to give donations,
# please send your checks (coz cash will probably be stolen in the
# post office) to:
#
# Vincent Ryan Ong
# Rm. 440 Wellington Bldg.
# 655 Condesa St. Binondo, Manila
# Philippines, 1006
*/
?>
<? include ("authconfig.php"); ?>
<html>
<head>
<title>vSignup Sample User Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p><font face="Arial, Helvetica, sans-serif" size="5"><b>Login</b></font></p>

<form name="Sample" method="post" action="<? print $resultpage ?>">
  <table width="40%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr> 
      <td colspan="2" bgcolor="#FFFFCC" valign="middle"> 
        <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><b>vSignup</b></font></div>
    </td>
  </tr>
    <tr> 
      <td width="32%" bgcolor="#CCCCCC" valign="middle"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;Username</font></b></td>
      <td width="68%" valign="middle"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
        &nbsp;
<input type="text" name="username" size="15" maxlength="15">
        </font></b></td>
  </tr>
    <tr> 
      <td width="32%" bgcolor="#CCCCCC" valign="middle"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;Password</font></b></td>
      <td width="68%" valign="middle"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
        &nbsp;
<input type="password" name="password" size="15" maxlength="15">
        </font></b></td>
  </tr>
    <tr> 
      <td width="32%" bgcolor="#CCCCCC" valign="middle"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;</font></b></td>
      <td width="68%" valign="middle"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
        &nbsp; <i><a href="pwdremind.php">Forgot your password?</a></i>
        </font></td>
  </tr>
  <tr valign="middle" bgcolor="#CCCCCC"> 
      <td colspan="2"> 
        <div align="center">
          <input type="submit" name="Login" value="Login">
          <input type="reset" name="Clear" value="Clear">
        </div>
      </td>
  </tr>
</table>
</form>

<p>&nbsp;</p>
</body>
</html>
