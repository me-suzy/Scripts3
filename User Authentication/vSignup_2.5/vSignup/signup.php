<?
/*
# File: signup.php
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
<html>
<head>
<title>vSignup 2.5</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">

<p>&nbsp;</p>
<form name="vSignup" method="POST" action="process.php">
  <table width="75%" border="1" cellspacing="0" cellpadding="0" align="center">
    <tr valign="middle"> 
      <td colspan="2" bgcolor="#FFFFCC"> 
        <div align="center"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Membership 
        Form</font></b></div>
    </td>
  </tr>
    <tr valign="middle"> 
      <td colspan="2"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">* 
        Fields in <font color="#990000">red</font> font are required to be filled 
        up</font></td>
  </tr>
    <tr valign="middle"> 
      <td width="17%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#990000">Username</font></b></td>
      <td width="83%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="username" size="20" maxlength="20">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="17%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#990000">Password</font></b></td>
      <td width="83%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="password" name="password" size="20" maxlength="20">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="17%" bgcolor="#CCCCCC">&nbsp;</td>
      <td width="83%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;</font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="17%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#990000">First 
        Name</font></b></td>
      <td width="83%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="fname" size="30" maxlength="30">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="17%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#990000">Last 
        Name</font></b></td>
      <td width="83%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="lname" size="20" maxlength="20">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="17%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#990000">Email 
        Address</font></b></td>
      <td width="83%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="email" size="45" maxlength="45">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="17%" bgcolor="#CCCCCC"><b><font size="1">&nbsp;</font></b></td>
      <td width="83%"><i><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;A 
        confirmation email will be sent to the email address you specified.</font></i></td>
  </tr>
    <tr valign="middle"> 
      <td width="17%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Country</font></b></td>
      <td width="83%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="country" size="20" maxlength="20">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td width="17%" bgcolor="#CCCCCC"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Zip 
        Code</font></b></td>
      <td width="83%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
        &nbsp;<input type="text" name="zipcode" size="20" maxlength="20">
        </font></b></td>
  </tr>
    <tr valign="middle"> 
      <td colspan="2" bgcolor="#FFFFCC">
        <div align="center"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"></font></b><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
          <input type="submit" name="Submit" value="Submit">&nbsp;
          <input type="reset" name="Reset" value="Reset">
          </font></b></div>
      </td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
</html>
